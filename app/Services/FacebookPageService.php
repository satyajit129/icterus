<?php

namespace App\Services;

use App\Models\FacebookCredential;
use App\Models\FacebookPage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FacebookPageService
{
    public function renderFacebookPagesPage(Request $request): View
    {
        $query = FacebookPage::query();

        // Apply filters
        if ($request->filled('page_id')) {
            $query->where('page_id', 'like', '%' . $request->page_id . '%');
        }

        if ($request->filled('name')) {
            $query->byName($request->name);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());

        // Get unique categories for filter dropdown
        $categories = FacebookPage::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('backend.pages.facebook_pages', compact('pages', 'categories'));
    }

    public function handleSyncFromApi(): RedirectResponse
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

            // Build API URL dynamically
            $apiUrl = rtrim($credentials->api_url, '/') . '/me/accounts';
            $accessToken = $credentials->user_access_token;

            // Make API call to Facebook Graph API
            $response = Http::timeout(30)->get($apiUrl, [
                'access_token' => $accessToken
            ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';
                return redirect()->back()->with('error', 'Facebook API Error: ' . $errorMessage);
            }

            $data = $response->json();

            if (!isset($data['data']) || !is_array($data['data'])) {
                return redirect()->back()->with('error', 'Invalid response format from Facebook API.');
            }

            $pages = $data['data'];
            $syncedCount = 0;
            $skippedCount = 0;

            foreach ($pages as $pageData) {
                // Check if page already exists
                $existingPage = FacebookPage::where('page_id', $pageData['id'])->first();

                if ($existingPage) {
                    // Update existing page
                    $existingPage->update([
                        'access_token' => $pageData['access_token'],
                        'name' => $pageData['name'],
                        'category' => $pageData['category'] ?? null,
                        'category_list' => $pageData['category_list'] ?? null,
                        'tasks' => $pageData['tasks'] ?? null,
                        'is_active' => true,
                    ]);
                    $skippedCount++;
                } else {
                    // Create new page
                    FacebookPage::create([
                        'page_id' => $pageData['id'],
                        'access_token' => $pageData['access_token'],
                        'name' => $pageData['name'],
                        'category' => $pageData['category'] ?? null,
                        'category_list' => $pageData['category_list'] ?? null,
                        'tasks' => $pageData['tasks'] ?? null,
                        'is_active' => true,
                    ]);
                    $syncedCount++;
                }
            }

            $message = "Sync completed! {$syncedCount} new pages added, {$skippedCount} existing pages updated.";
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('Facebook Pages Sync Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while syncing pages: ' . $e->getMessage());
        }
    }

    public function handlePageToggleStatus($id): RedirectResponse
    {
        try {
            $page = FacebookPage::findOrFail($id);
            $page->is_active = !$page->is_active;
            $page->save();

            $status = $page->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Page '{$page->name}' has been {$status} successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating page status: ' . $e->getMessage());
        }
    }

    public function handlePageDelete($id): RedirectResponse
    {
        try {
            $page = FacebookPage::findOrFail($id);
            $pageName = $page->name;
            $page->delete();

            return redirect()->back()->with('success', "Page '{$pageName}' has been deleted successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting page: ' . $e->getMessage());
        }
    }

    public function renderPageView($id): View
    {
        $page = FacebookPage::findOrFail($id);
        return view('backend.pages.facebook_page_view', compact('page'));
    }

    public function getPageStats($id): array
    {
        try {
            $page = FacebookPage::findOrFail($id);

            // You can add more API calls here to get page statistics
            // For now, return basic info
            return [
                'page_id' => $page->page_id,
                'name' => $page->name,
                'category' => $page->category,
                'is_active' => $page->is_active,
                'created_at' => $page->created_at,
                'updated_at' => $page->updated_at,
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
