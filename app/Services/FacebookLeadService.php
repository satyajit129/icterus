<?php

namespace App\Services;

use App\Models\FacebookCredential;
use App\Models\FacebookLead;
use App\Models\FacebookLeadgenForm;
use App\Models\FacebookPage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class FacebookLeadService
{
    public function renderLeadsPage(Request $request): View
    {
        $query = FacebookLead::with(['facebookLeadgenForm', 'facebookPage']);

        // Apply filters
        if ($request->filled('form_id')) {
            $query->byForm($request->form_id);
        }

        if ($request->filled('page_id')) {
            $query->byPage($request->page_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->byDateRange($startDate, $endDate);
        }

        if ($request->filled('is_processed')) {
            $query->where('is_processed', $request->is_processed);
        }

        if ($request->filled('field_name') && $request->filled('field_value')) {
            $query->byFieldValue($request->field_name, $request->field_value);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('lead_id', 'like', '%' . $searchTerm . '%')
                    ->orWhereJsonContains('extracted_fields->name', $searchTerm)
                    ->orWhereJsonContains('extracted_fields->phone', $searchTerm)
                    ->orWhereJsonContains('extracted_fields->email', $searchTerm);
            });
        }

        $leads = $query->orderBy('created_time', 'desc')->paginate(20)->appends($request->all());

        // Get filter options
        $forms = FacebookLeadgenForm::active()
            ->select('form_id', 'name', 'page_id')
            ->with('facebookPage:id,page_id,name')
            ->orderBy('name')
            ->get();

        $pages = FacebookPage::active()
            ->select('page_id', 'name')
            ->orderBy('name')
            ->get();

        // Get unique field names for filtering
        $fieldNames = FacebookLead::selectRaw('JSON_EXTRACT(field_data, "$[*].name") as field_names')
            ->get()
            ->pluck('field_names')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();

        return view('backend.pages.facebook_leads', compact('leads', 'forms', 'pages', 'fieldNames'));
    }

    public function handleCollectLeads(Request $request): RedirectResponse
    {
        try {
            $formId = $request->form_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            if (!$formId) {
                return redirect()->back()->with('error', 'Please select a form to collect leads from.');
            }

            // Get form details
            $form = FacebookLeadgenForm::with('facebookPage')->where('form_id', $formId)->first();
            if (!$form) {
                return redirect()->back()->with('error', 'Form not found.');
            }

            // Get Facebook credentials
            $credentials = FacebookCredential::first();
            if (!$credentials || empty($credentials->user_access_token)) {
                return redirect()->back()->with('error', 'Facebook credentials not configured.');
            }

            // Build API URL
            $apiUrl = rtrim($credentials->api_url, '/');
            $accessToken = $form->facebookPage->access_token;

            // Build query parameters
            $params = [
                'access_token' => $accessToken,
                'limit' => 100
            ];

            if ($startDate && $endDate) {
                $params['since'] = $startDate->timestamp;
                $params['until'] = $endDate->timestamp;
            }

            $totalCollected = 0;
            $totalSkipped = 0;
            $totalErrors = 0;
            $errorMessages = [];
            $nextPageUrl = null;
            $pageCount = 0;

            do {
                $pageCount++;
                $url = $nextPageUrl ?: $apiUrl . '/' . $formId . '/leads';

                try {
                    $response = Http::timeout(30)->get($url, $params);

                    if (!$response->successful()) {
                        $errorData = $response->json();
                        $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';

                        // Log the error but continue with next page if available
                        Log::warning("Facebook API Error on page {$pageCount}: {$errorMessage}");
                        $errorMessages[] = "Page {$pageCount}: {$errorMessage}";
                        $totalErrors++;

                        // If it's an access token error, break the loop
                        if (strpos($errorMessage, 'access token') !== false) {
                            break;
                        }

                        // Continue to next page if available (preserve token)
                        $nextPageUrl = isset($errorData['paging']['next']) ? $errorData['paging']['next'] : null;
                        if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                            $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                            $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                        }
                        $params = [];
                        continue;
                    }

                    $data = $response->json();

                    if (!isset($data['data']) || !is_array($data['data'])) {
                        Log::warning("Invalid response format on page {$pageCount}");
                        $errorMessages[] = "Page {$pageCount}: Invalid response format";
                        $totalErrors++;
                        continue;
                    }

                    $leads = $data['data'];

                    foreach ($leads as $leadData) {
                        try {
                            // Validate lead data structure
                            if (!isset($leadData['id']) || !isset($leadData['field_data']) || !is_array($leadData['field_data'])) {
                                Log::warning("Invalid lead data structure for lead: " . ($leadData['id'] ?? 'unknown'));
                                $totalErrors++;
                                continue;
                            }

                            // Check if lead already exists
                            $existingLead = FacebookLead::where('lead_id', $leadData['id'])->first();

                            if ($existingLead) {
                                $totalSkipped++;
                                continue;
                            }

                            // Safely extract common fields
                            $extractedFields = FacebookLead::extractCommonFields($leadData['field_data']);

                            // Create new lead
                            FacebookLead::create([
                                'lead_id' => $leadData['id'],
                                'form_id' => $formId,
                                'page_id' => $form->page_id,
                                'created_time' => Carbon::parse($leadData['created_time']),
                                'field_data' => $leadData['field_data'],
                                'extracted_fields' => $extractedFields,
                                'is_processed' => false,
                            ]);

                            $totalCollected++;
                        } catch (Exception $leadException) {
                            // Log individual lead error but continue processing
                            Log::warning("Error processing lead {$leadData['id']}: " . $leadException->getMessage());
                            $totalErrors++;
                            continue;
                        }
                    }

                    // Check for next page (preserve token)
                    $nextPageUrl = $data['paging']['next'] ?? null;
                    if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                        $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                        $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                    }
                    $params = []; // Clear params for next page URL

                } catch (Exception $pageException) {
                    // Log page error but continue if possible
                    Log::warning("Error processing page {$pageCount}: " . $pageException->getMessage());
                    $errorMessages[] = "Page {$pageCount}: " . $pageException->getMessage();
                    $totalErrors++;

                    // Try to continue with next page if available (preserve token)
                    $nextPageUrl = isset($data['paging']['next']) ? $data['paging']['next'] : null;
                    if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                        $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                        $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                    }
                    $params = [];
                }
            } while ($nextPageUrl);

            // Build success message
            $message = "Lead collection completed! ";
            $message .= "✅ {$totalCollected} new leads collected, ";
            $message .= "⏭️ {$totalSkipped} existing leads skipped";

            if ($totalErrors > 0) {
                $message .= ", ❌ {$totalErrors} errors encountered";
            }

            if ($startDate && $endDate) {
                $message .= " (Date range: {$startDate->format('M d, Y')} to {$endDate->format('M d, Y')})";
            }

            // Add error details if any
            if (!empty($errorMessages)) {
                $message .= "\n\nErrors encountered:\n" . implode("\n", array_slice($errorMessages, 0, 5));
                if (count($errorMessages) > 5) {
                    $message .= "\n... and " . (count($errorMessages) - 5) . " more errors";
                }
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('Facebook Leads Collection Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while collecting leads: ' . $e->getMessage());
        }
    }

    public function handleLeadToggleStatus($id): RedirectResponse
    {
        try {
            $lead = FacebookLead::findOrFail($id);
            $lead->is_processed = !$lead->is_processed;
            $lead->save();

            $status = $lead->is_processed ? 'marked as processed' : 'marked as unprocessed';
            return redirect()->back()->with('success', "Lead has been {$status} successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating lead status: ' . $e->getMessage());
        }
    }

    public function handleLeadDelete($id): RedirectResponse
    {
        try {
            $lead = FacebookLead::findOrFail($id);
            $leadId = $lead->lead_id;
            $lead->delete();

            return redirect()->back()->with('success', "Lead {$leadId} has been deleted successfully.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting lead: ' . $e->getMessage());
        }
    }

    public function renderLeadView($id): View
    {
        $lead = FacebookLead::with(['facebookLeadgenForm', 'facebookPage'])->findOrFail($id);
        return view('backend.pages.facebook_lead_view', compact('lead'));
    }

    public function exportLeads(Request $request)
    {
        try {
            $query = FacebookLead::with(['facebookLeadgenForm', 'facebookPage']);

            // Apply same filters as the main page
            if ($request->filled('form_id')) {
                $query->byForm($request->form_id);
            }

            if ($request->filled('page_id')) {
                $query->byPage($request->page_id);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->byDateRange($startDate, $endDate);
            }

            if ($request->filled('is_processed')) {
                $query->where('is_processed', $request->is_processed);
            }

            $leads = $query->orderBy('created_time', 'desc')->get();

            // Prepare CSV data
            $csvData = [];
            $csvData[] = ['Lead ID', 'Form Name', 'Page Name', 'Name', 'Phone', 'Email', 'Location', 'Created Time', 'Processed'];

            foreach ($leads as $lead) {
                $csvData[] = [
                    $lead->lead_id,
                    $lead->facebookLeadgenForm->name ?? 'N/A',
                    $lead->facebookPage->name ?? 'N/A',
                    $lead->getFieldValue('name') ?? 'N/A',
                    $lead->getFieldValue('phone') ?? 'N/A',
                    $lead->getFieldValue('email') ?? 'N/A',
                    $lead->getFieldValue('location') ?? 'N/A',
                    $lead->created_time->format('Y-m-d H:i:s'),
                    $lead->is_processed ? 'Yes' : 'No'
                ];
            }

            $filename = 'facebook_leads_' . now()->format('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($csvData) {
                $file = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            Log::error('Facebook Leads Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while exporting leads: ' . $e->getMessage());
        }
    }

    public function getFormFieldNames($formId)
    {
        try {
            $lead = FacebookLead::where('form_id', $formId)->first();
            if (!$lead) {
                return [];
            }

            $fieldNames = [];
            foreach ($lead->field_data as $field) {
                $fieldNames[] = $field['name'];
            }

            return array_unique($fieldNames);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getFieldValues($fieldName)
    {
        try {
            return FacebookLead::whereJsonContains('field_data', [['name' => $fieldName]])
                ->get()
                ->pluck('field_data')
                ->flatten()
                ->where('name', $fieldName)
                ->pluck('values')
                ->flatten()
                ->unique()
                ->filter()
                ->values()
                ->toArray();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getFormLeadCount($formId)
    {
        try {
            return FacebookLead::where('form_id', $formId)->count();
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getFormLeadStats($formId)
    {
        try {
            $total = FacebookLead::where('form_id', $formId)->count();
            $processed = FacebookLead::where('form_id', $formId)->where('is_processed', true)->count();
            $unprocessed = $total - $processed;

            return [
                'total' => $total,
                'processed' => $processed,
                'unprocessed' => $unprocessed
            ];
        } catch (Exception $e) {
            return [
                'total' => 0,
                'processed' => 0,
                'unprocessed' => 0
            ];
        }
    }

    public function handleCollectLeadsOptimized(Request $request): RedirectResponse
    {
        try {
            $formId = $request->form_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            if (!$formId) {
                return redirect()->back()->with('error', 'Please select a form to collect leads from.');
            }

            // Get form details
            $form = FacebookLeadgenForm::with('facebookPage')->where('form_id', $formId)->first();
            if (!$form) {
                return redirect()->back()->with('error', 'Form not found.');
            }

            // Get Facebook credentials
            $credentials = FacebookCredential::first();
            if (!$credentials || empty($credentials->user_access_token)) {
                return redirect()->back()->with('error', 'Facebook credentials not configured.');
            }

            // Build API URL
            $apiUrl = rtrim($credentials->api_url, '/');
            $accessToken = $form->facebookPage->access_token;

            // Build query parameters with optimized settings
            $params = [
                'access_token' => $accessToken,
                'limit' => 100 // Facebook's maximum per request
            ];

            if ($startDate && $endDate) {
                $params['since'] = $startDate->timestamp;
                $params['until'] = $endDate->timestamp;
            }

            // Set PHP settings for large datasets
            ini_set('memory_limit', config('facebook_leads.collection.memory_limit', '512M'));
            ini_set('max_execution_time', config('facebook_leads.collection.max_execution_time', 0));

            $totalCollected = 0;
            $totalSkipped = 0;
            $totalErrors = 0;
            $errorMessages = [];
            $nextPageUrl = null;
            $pageCount = 0;
            $maxPages = config('facebook_leads.collection.max_pages', 100);
            $batchSize = config('facebook_leads.collection.batch_size', 50);

            do {
                $pageCount++;

                // Safety check to prevent infinite loops
                if ($pageCount > $maxPages) {
                    Log::warning("Reached maximum page limit ({$maxPages}). Stopping collection.");
                    $errorMessages[] = "Reached maximum page limit for safety";
                    break;
                }

                $url = $nextPageUrl ?: $apiUrl . '/' . $formId . '/leads';

                try {
                    // Increased timeout for large datasets
                    $response = Http::timeout(config('facebook_leads.collection.api_timeout', 120))->get($url, $params);

                    if (!$response->successful()) {
                        $errorData = $response->json();
                        $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';

                        // Log the error but continue with next page if available
                        Log::warning("Facebook API Error on page {$pageCount}: {$errorMessage}");
                        $errorMessages[] = "Page {$pageCount}: {$errorMessage}";
                        $totalErrors++;

                        // If it's an access token error, break the loop
                        if (strpos($errorMessage, 'access token') !== false) {
                            break;
                        }

                        // Continue to next page if available (preserve token)
                        $nextPageUrl = isset($errorData['paging']['next']) ? $errorData['paging']['next'] : null;
                        if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                            $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                            $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                        }
                        $params = [];
                        continue;
                    }

                    $data = $response->json();

                    if (!isset($data['data']) || !is_array($data['data'])) {
                        Log::warning("Invalid response format on page {$pageCount}");
                        $errorMessages[] = "Page {$pageCount}: Invalid response format";
                        $totalErrors++;
                        continue;
                    }

                    $leads = $data['data'];
                    $leadsToInsert = [];

                    // Process leads in batches for better performance
                    foreach ($leads as $leadData) {
                        try {
                            // Validate lead data structure
                            if (!isset($leadData['id']) || !isset($leadData['field_data']) || !is_array($leadData['field_data'])) {
                                Log::warning("Invalid lead data structure for lead: " . ($leadData['id'] ?? 'unknown'));
                                $totalErrors++;
                                continue;
                            }

                            // Check if lead already exists
                            $existingLead = FacebookLead::where('lead_id', $leadData['id'])->first();

                            if ($existingLead) {
                                $totalSkipped++;
                                continue;
                            }

                            // Safely extract common fields
                            $extractedFields = FacebookLead::extractCommonFields($leadData['field_data']);

                            // Prepare lead data for batch insert
                            $leadsToInsert[] = [
                                'lead_id' => $leadData['id'],
                                'form_id' => $formId,
                                'page_id' => $form->page_id,
                                'created_time' => Carbon::parse($leadData['created_time']),
                                'field_data' => json_encode($leadData['field_data']),
                                'extracted_fields' => json_encode($extractedFields),
                                'is_processed' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        } catch (Exception $leadException) {
                            // Log individual lead error but continue processing
                            Log::warning("Error processing lead {$leadData['id']}: " . $leadException->getMessage());
                            $totalErrors++;
                            continue;
                        }
                    }

                    // Batch insert leads for better performance
                    if (!empty($leadsToInsert)) {
                        try {
                            // Process in smaller batches to avoid memory issues
                            $chunks = array_chunk($leadsToInsert, $batchSize);
                            foreach ($chunks as $chunk) {
                                FacebookLead::insert($chunk);
                                $totalCollected += count($chunk);
                            }

                            // Log progress
                            if (config('facebook_leads.optimization.progress_logging', true)) {
                                Log::info("Facebook Leads Collection Progress - Page {$pageCount}: {$totalCollected} leads collected so far");
                            }
                        } catch (Exception $insertException) {
                            Log::error("Batch insert error on page {$pageCount}: " . $insertException->getMessage());
                            $errorMessages[] = "Page {$pageCount}: Batch insert failed";
                            $totalErrors++;
                        }
                    }

                    // Check for next page (preserve token)
                    $nextPageUrl = $data['paging']['next'] ?? null;
                    if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                        $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                        $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                    }
                    $params = []; // Clear params for next page URL

                    // Add small delay to avoid rate limiting
                    if ($nextPageUrl) {
                        usleep(config('facebook_leads.collection.request_delay', 200000));
                    }
                } catch (Exception $pageException) {
                    // Log page error but continue if possible
                    Log::warning("Error processing page {$pageCount}: " . $pageException->getMessage());
                    $errorMessages[] = "Page {$pageCount}: " . $pageException->getMessage();
                    $totalErrors++;

                    // Try to continue with next page if available (preserve token)
                    $nextPageUrl = isset($data['paging']['next']) ? $data['paging']['next'] : null;
                    if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                        $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                        $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                    }
                    $params = [];
                }
            } while ($nextPageUrl);

            // Build success message
            $message = "Lead collection completed! ";
            $message .= "✅ {$totalCollected} new leads collected, ";
            $message .= "⏭️ {$totalSkipped} existing leads skipped";

            if ($totalErrors > 0) {
                $message .= ", ❌ {$totalErrors} errors encountered";
            }

            if ($startDate && $endDate) {
                $message .= " (Date range: {$startDate->format('M d, Y')} to {$endDate->format('M d, Y')})";
            }

            // Add error details if any
            if (!empty($errorMessages)) {
                $message .= "\n\nErrors encountered:\n" . implode("\n", array_slice($errorMessages, 0, 5));
                if (count($errorMessages) > 5) {
                    $message .= "\n... and " . (count($errorMessages) - 5) . " more errors";
                }
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('Facebook Leads Collection Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while collecting leads: ' . $e->getMessage());
        }
    }
}
