<?php

namespace App\Services;

use App\Models\FacebookCredential;
use App\Models\FacebookLeadgenForm;
use App\Models\FacebookPage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FacebookLeadgenFormService
{
    public function renderLeadgenFormsPage(Request $request): View
    {
        $query = FacebookLeadgenForm::with('facebookPage');

        // Apply filters
        if ($request->filled('form_id')) {
            $query->where('form_id', 'like', '%' . $request->form_id . '%');
        }

        if ($request->filled('name')) {
            $query->byName($request->name);
        }

        if ($request->filled('page_id')) {
            $query->byPage($request->page_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('locale')) {
            $query->byLocale($request->locale);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $forms = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());

        // Get filter options
        $pages = FacebookPage::active()->select('page_id', 'name')->orderBy('name')->get();
        $statuses = ['ACTIVE', 'ARCHIVED', 'DELETED'];
        $locales = FacebookLeadgenForm::select('locale')
            ->distinct()
            ->pluck('locale')
            ->filter()
            ->sort()
            ->values();

        return view('backend.pages.facebook_leadgen_forms', compact('forms', 'pages', 'statuses', 'locales'));
    }

    public function handleSyncFromApi($pageId = null): RedirectResponse
    {
        try {
            // Get Facebook credentials
            $credentials = FacebookCredential::first();

            if (!$credentials) {
                return redirect()->back()->with('error', 'Facebook credentials not found. Please configure them first.');
            }

            if (empty($credentials->user_access_token)) {
                return redirect()->back()->with('error', 'User Access Token not configured. Please update Facebook credentials.');
            }

            // Get pages to sync
            if ($pageId) {
                $pages = FacebookPage::where('page_id', $pageId)->active()->get();
                if ($pages->isEmpty()) {
                    return redirect()->back()->with('error', 'Page not found or inactive.');
                }
            } else {
                $pages = FacebookPage::active()->get();
                if ($pages->isEmpty()) {
                    return redirect()->back()->with('error', 'No active Facebook pages found. Please sync pages first.');
                }
            }

            // Build API URL dynamically
            $apiUrl = rtrim($credentials->api_url, '/');
            $accessToken = $credentials->user_access_token;

            $totalSynced = 0;
            $totalSkipped = 0;
            $errors = [];

            foreach ($pages as $page) {
                try {
                    $response = Http::timeout(30)->get($apiUrl . '/' . $page->page_id . '/leadgen_forms', [
                        'access_token' => $page->access_token
                    ]);

                    if (!$response->successful()) {
                        $errorData = $response->json();
                        $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';
                        $errors[] = "Page '{$page->name}': {$errorMessage}";
                        continue;
                    }

                    $data = $response->json();

                    if (!isset($data['data']) || !is_array($data['data'])) {
                        $errors[] = "Page '{$page->name}': Invalid response format";
                        continue;
                    }

                    $forms = $data['data'];

                    foreach ($forms as $formData) {
                        // Check if form already exists
                        $existingForm = FacebookLeadgenForm::where('form_id', $formData['id'])->first();

                        if ($existingForm) {
                            // Update existing form
                            $existingForm->update([
                                'page_id' => $page->page_id,
                                'name' => $formData['name'],
                                'locale' => $formData['locale'],
                                'status' => $formData['status'],
                                'is_active' => true,
                            ]);
                            $totalSkipped++;
                        } else {
                            // Create new form
                            FacebookLeadgenForm::create([
                                'form_id' => $formData['id'],
                                'page_id' => $page->page_id,
                                'name' => $formData['name'],
                                'locale' => $formData['locale'],
                                'status' => $formData['status'],
                                'is_active' => true,
                            ]);
                            $totalSynced++;
                        }
                    }
                } catch (Exception $e) {
                    $errors[] = "Page '{$page->name}': " . $e->getMessage();
                    Log::error("Facebook Leadgen Forms Sync Error for Page {$page->page_id}: " . $e->getMessage());
                }
            }

            $message = "Sync completed! {$totalSynced} new forms added, {$totalSkipped} existing forms updated.";

            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " and " . (count($errors) - 3) . " more errors.";
                }
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('Facebook Leadgen Forms Sync Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while syncing forms: ' . $e->getMessage());
        }
    }

    public function handleFormToggleStatus($id): RedirectResponse
    {
        try {
            $form = FacebookLeadgenForm::findOrFail($id);
            $form->is_active = !$form->is_active;
            $form->save();

            $status = $form->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Form '{$form->name}' has been {$status} successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating form status: ' . $e->getMessage());
        }
    }

    public function handleFormDelete($id): RedirectResponse
    {
        try {
            $form = FacebookLeadgenForm::findOrFail($id);
            $formName = $form->name;
            $form->delete();

            return redirect()->back()->with('success', "Form '{$formName}' has been deleted successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting form: ' . $e->getMessage());
        }
    }

    public function renderFormView($id): View
    {
        $form = FacebookLeadgenForm::with('facebookPage')->findOrFail($id);
        return view('backend.pages.facebook_leadgen_form_view', compact('form'));
    }

    public function getFormDetails($id): array
    {
        try {
            $form = FacebookLeadgenForm::with('facebookPage')->findOrFail($id);

            return [
                'form_id' => $form->form_id,
                'name' => $form->name,
                'page_name' => $form->facebookPage->name ?? 'Unknown Page',
                'page_id' => $form->page_id,
                'locale' => $form->locale,
                'status' => $form->status,
                'is_active' => $form->is_active,
                'created_at' => $form->created_at,
                'updated_at' => $form->updated_at,
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function syncFormForSpecificPage($pageId): RedirectResponse
    {
        return $this->handleSyncFromApi($pageId);
    }
}
