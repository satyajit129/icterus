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
            $userAccessToken = $credentials->user_access_token;

            // Build query parameters
            $params = [
                'access_token' => $accessToken,
                'limit' => 100
            ];

            // Add date filtering using Facebook Lead Ads API format
            if ($startDate && $endDate) {
                // Convert to Unix timestamps properly
                $startTimestamp = $startDate->timestamp;
                $endTimestamp = $endDate->timestamp;

                // Try sending filtering as JSON string (Facebook API might expect this format)
                $filteringArray = [
                    [
                        'field'    => 'time_created',
                        'operator' => 'GREATER_THAN_OR_EQUAL',
                        'value'    => $startTimestamp,
                    ],
                    [
                        'field'    => 'time_created',
                        'operator' => 'LESS_THAN_OR_EQUAL',
                        'value'    => $endTimestamp,
                    ]
                ];

                $params['filtering'] = json_encode($filteringArray);

                // Log the filtering parameters for debugging
                Log::info("Date filtering applied", [
                    'start_date' => $startDate->toDateTimeString(),
                    'end_date' => $endDate->toDateTimeString(),
                    'start_timestamp' => $startTimestamp,
                    'end_timestamp' => $endTimestamp,
                    'expected_timestamp' => 1756802594, // Reference timestamp
                    'carbon_timezone' => $startDate->timezone->getName(),
                    'carbon_offset' => $startDate->offset,
                    'filtering_array' => $filteringArray,
                    'filtering_json' => $params['filtering']
                ]);
            }

            $totalCollected = 0;
            $totalSkipped = 0;
            $totalErrors = 0;
            $errorMessages = [];
            $nextPageUrl = null;
            $pageCount = 0;
            $tokenRefreshed = false;
            $consecutiveTokenErrors = 0;
            $maxConsecutiveTokenErrors = 3;

            do {
                $pageCount++;
                $url = $nextPageUrl ?: $apiUrl . '/' . $formId . '/leads';

                try {
                    // Log the request details for debugging
                    Log::info("Making Facebook API request", [
                        'url' => $url,
                        'params' => $params,
                        'page_count' => $pageCount
                    ]);

                    $response = Http::timeout(30)->get($url, $params);

                    if (!$response->successful()) {
                        $errorData = $response->json();
                        $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';
                        $errorCode = $errorData['error']['code'] ?? 'Unknown';
                        $errorType = $errorData['error']['type'] ?? 'Unknown';

                        // Log detailed error information
                        Log::warning("Facebook API Error on page {$pageCount}: {$errorMessage}", [
                            'error_code' => $errorCode,
                            'error_type' => $errorType,
                            'response_status' => $response->status(),
                            'response_body' => $response->body(),
                            'request_url' => $url,
                            'request_params' => $params
                        ]);

                        $errorMessages[] = "Page {$pageCount}: {$errorMessage} (Code: {$errorCode})";
                        $totalErrors++;

                        // Check for rate limit errors
                        if (strpos($errorMessage, 'Application request limit reached') !== false) {
                            Log::warning("Facebook rate limit reached. Waiting 60 seconds before retry...");
                            sleep(60); // Wait 1 minute for rate limit to reset
                            continue; // Retry the same page
                        }

                        // If it's an access token error, try to refresh the token
                        if (strpos($errorMessage, 'access token') !== false) {
                            $consecutiveTokenErrors++;

                            // Safety check to prevent infinite loops
                            if ($consecutiveTokenErrors > $maxConsecutiveTokenErrors) {
                                Log::error("Too many consecutive token errors ({$consecutiveTokenErrors}). Stopping collection to prevent infinite loop.");
                                $errorMessages[] = "Stopped due to consecutive token errors";
                                break;
                            }

                            Log::info("Attempting to refresh page access token for form {$formId} (attempt {$consecutiveTokenErrors})");

                            // Try to get a fresh page access token
                            $newPageToken = $this->refreshPageAccessToken($form->page_id, $userAccessToken, $apiUrl);

                            if ($newPageToken) {
                                // Update the page token in database
                                $form->facebookPage->update(['access_token' => $newPageToken]);
                                $accessToken = $newPageToken;
                                $params['access_token'] = $accessToken;
                                $tokenRefreshed = true;

                                Log::info("Page access token refreshed successfully");

                                // If this is a pagination URL, update it with the new token
                                if ($nextPageUrl) {
                                    $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $accessToken);
                                }

                                // Retry the same request with new token
                                $response = Http::timeout(30)->get($url, $params);

                                if ($response->successful()) {
                                    // Reset consecutive error counter on success
                                    $consecutiveTokenErrors = 0;
                                    // Continue processing the response
                                    $errorMessages = array_slice($errorMessages, 0, -1); // Remove the last error
                                    $totalErrors--;
                                } else {
                                    // If still failing, try with user access token as fallback
                                    Log::info("Trying with user access token as fallback");
                                    $params['access_token'] = $userAccessToken;
                                    if ($nextPageUrl) {
                                        $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $userAccessToken);
                                    }
                                    $response = Http::timeout(30)->get($url, $params);

                                    if ($response->successful()) {
                                        $consecutiveTokenErrors = 0;
                                    } else {
                                        Log::error("Both page and user access tokens failed");
                                        break;
                                    }
                                }
                            } else {
                                // If token refresh failed, try with user access token as fallback
                                Log::info("Token refresh failed, trying with user access token as fallback");
                                $params['access_token'] = $userAccessToken;
                                if ($nextPageUrl) {
                                    $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $userAccessToken);
                                }
                                $response = Http::timeout(30)->get($url, $params);

                                if ($response->successful()) {
                                    $consecutiveTokenErrors = 0;
                                } else {
                                    Log::error("User access token also failed");
                                    break;
                                }
                            }
                        } else {
                            // For other errors, continue to next page if available
                            $nextPageUrl = isset($errorData['paging']['next']) ? $errorData['paging']['next'] : null;
                            if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                                $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                                $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                            }
                            $params = [];
                            continue;
                        }
                    }

                    $data = $response->json();

                    // Reset consecutive error counter on successful response
                    $consecutiveTokenErrors = 0;

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

                    // Check for next page using pagination cursors
                    $nextPageUrl = null;
                    if (isset($data['paging']['cursors']['after'])) {
                        // Use pagination cursors instead of full URL to avoid token issues
                        $nextPageUrl = $apiUrl . '/' . $formId . '/leads';
                        $params = [
                            'access_token' => $accessToken,
                            'limit' => 100,
                            'after' => $data['paging']['cursors']['after']
                        ];

                        // Add date filters using Facebook Lead Ads API format
                        if ($startDate && $endDate) {
                            $params['filtering'] = [
                                [
                                    'field'    => 'time_created',
                                    'operator' => 'GREATER_THAN_OR_EQUAL',
                                    'value'    => $startDate->timestamp,
                                ],
                                [
                                    'field'    => 'time_created',
                                    'operator' => 'LESS_THAN_OR_EQUAL',
                                    'value'    => $endDate->timestamp,
                                ]
                            ];
                        }
                    }
                } catch (Exception $pageException) {
                    // Log page error but continue if possible
                    Log::warning("Error processing page {$pageCount}: " . $pageException->getMessage());
                    $errorMessages[] = "Page {$pageCount}: " . $pageException->getMessage();
                    $totalErrors++;

                    // Try to continue with next page using pagination cursors
                    $nextPageUrl = null;
                    if (isset($data['paging']['cursors']['after'])) {
                        $nextPageUrl = $apiUrl . '/' . $formId . '/leads';
                        $params = [
                            'access_token' => $accessToken,
                            'limit' => 100,
                            'after' => $data['paging']['cursors']['after']
                        ];

                        if ($startDate && $endDate) {
                            $params['since'] = $startDate->timestamp;
                            $params['until'] = $endDate->timestamp;
                        }
                    }
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
            $userAccessToken = $credentials->user_access_token;

            // Build query parameters with optimized settings
            $params = [
                'access_token' => $accessToken,
                'limit' => 100 // Facebook's maximum per request
            ];

            // Add date filtering using Facebook Lead Ads API format
            if ($startDate && $endDate) {
                $params['filtering'] = [
                    [
                        'field'    => 'time_created',
                        'operator' => 'GREATER_THAN_OR_EQUAL',
                        'value'    => $startDate->timestamp,
                    ],
                    [
                        'field'    => 'time_created',
                        'operator' => 'LESS_THAN_OR_EQUAL',
                        'value'    => $endDate->timestamp,
                    ]
                ];
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
            $tokenRefreshed = false;
            $consecutiveTokenErrors = 0;
            $maxConsecutiveTokenErrors = 3;

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
                        $errorCode = $errorData['error']['code'] ?? 'Unknown';
                        $errorType = $errorData['error']['type'] ?? 'Unknown';

                        // Log detailed error information
                        Log::warning("Facebook API Error on page {$pageCount}: {$errorMessage}", [
                            'error_code' => $errorCode,
                            'error_type' => $errorType,
                            'response_status' => $response->status(),
                            'response_body' => $response->body(),
                            'request_url' => $url,
                            'request_params' => $params
                        ]);

                        $errorMessages[] = "Page {$pageCount}: {$errorMessage} (Code: {$errorCode})";
                        $totalErrors++;

                        // Check for rate limit errors
                        if (strpos($errorMessage, 'Application request limit reached') !== false) {
                            Log::warning("Facebook rate limit reached. Waiting 60 seconds before retry...");
                            sleep(60); // Wait 1 minute for rate limit to reset
                            continue; // Retry the same page
                        }

                        // If it's an access token error, try to refresh the token
                        if (strpos($errorMessage, 'access token') !== false) {
                            $consecutiveTokenErrors++;

                            // Safety check to prevent infinite loops
                            if ($consecutiveTokenErrors > $maxConsecutiveTokenErrors) {
                                Log::error("Too many consecutive token errors ({$consecutiveTokenErrors}). Stopping collection to prevent infinite loop.");
                                $errorMessages[] = "Stopped due to consecutive token errors";
                                break;
                            }

                            Log::info("Attempting to refresh page access token for form {$formId} (attempt {$consecutiveTokenErrors})");

                            // Try to get a fresh page access token
                            $newPageToken = $this->refreshPageAccessToken($form->page_id, $userAccessToken, $apiUrl);

                            if ($newPageToken) {
                                // Update the page token in database
                                $form->facebookPage->update(['access_token' => $newPageToken]);
                                $accessToken = $newPageToken;
                                $params['access_token'] = $accessToken;
                                $tokenRefreshed = true;

                                Log::info("Page access token refreshed successfully");

                                // If this is a pagination URL, update it with the new token
                                if ($nextPageUrl) {
                                    $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $accessToken);
                                }

                                // Retry the same request with new token
                                $response = Http::timeout(config('facebook_leads.collection.api_timeout', 120))->get($url, $params);

                                if ($response->successful()) {
                                    // Reset consecutive error counter on success
                                    $consecutiveTokenErrors = 0;
                                    // Continue processing the response
                                    $errorMessages = array_slice($errorMessages, 0, -1); // Remove the last error
                                    $totalErrors--;
                                } else {
                                    // If still failing, try with user access token as fallback
                                    Log::info("Trying with user access token as fallback");
                                    $params['access_token'] = $userAccessToken;
                                    if ($nextPageUrl) {
                                        $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $userAccessToken);
                                    }
                                    $response = Http::timeout(config('facebook_leads.collection.api_timeout', 120))->get($url, $params);

                                    if ($response->successful()) {
                                        $consecutiveTokenErrors = 0;
                                    } else {
                                        Log::error("Both page and user access tokens failed");
                                        break;
                                    }
                                }
                            } else {
                                // If token refresh failed, try with user access token as fallback
                                Log::info("Token refresh failed, trying with user access token as fallback");
                                $params['access_token'] = $userAccessToken;
                                if ($nextPageUrl) {
                                    $nextPageUrl = $this->updatePaginationUrlWithToken($nextPageUrl, $userAccessToken);
                                }
                                $response = Http::timeout(config('facebook_leads.collection.api_timeout', 120))->get($url, $params);

                                if ($response->successful()) {
                                    $consecutiveTokenErrors = 0;
                                } else {
                                    Log::error("User access token also failed");
                                    break;
                                }
                            }
                        } else {
                            // For other errors, continue to next page if available
                            $nextPageUrl = isset($errorData['paging']['next']) ? $errorData['paging']['next'] : null;
                            if ($nextPageUrl && strpos($nextPageUrl, 'access_token=') === false) {
                                $separator = (parse_url($nextPageUrl, PHP_URL_QUERY) ? '&' : '?');
                                $nextPageUrl .= $separator . 'access_token=' . urlencode($accessToken);
                            }
                            $params = [];
                            continue;
                        }
                    }

                    $data = $response->json();

                    // Reset consecutive error counter on successful response
                    $consecutiveTokenErrors = 0;

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

                    // Check for next page using pagination cursors
                    $nextPageUrl = null;
                    if (isset($data['paging']['cursors']['after'])) {
                        // Use pagination cursors instead of full URL to avoid token issues
                        $nextPageUrl = $apiUrl . '/' . $formId . '/leads';
                        $params = [
                            'access_token' => $accessToken,
                            'limit' => 100,
                            'after' => $data['paging']['cursors']['after']
                        ];

                        // Add date filters using Facebook Lead Ads API format
                        if ($startDate && $endDate) {
                            $params['filtering'] = [
                                [
                                    'field'    => 'time_created',
                                    'operator' => 'GREATER_THAN_OR_EQUAL',
                                    'value'    => $startDate->timestamp,
                                ],
                                [
                                    'field'    => 'time_created',
                                    'operator' => 'LESS_THAN_OR_EQUAL',
                                    'value'    => $endDate->timestamp,
                                ]
                            ];
                        }
                    }

                    // Add small delay to avoid rate limiting
                    if ($nextPageUrl) {
                        usleep(config('facebook_leads.collection.request_delay', 200000));
                    }
                } catch (Exception $pageException) {
                    // Log page error but continue if possible
                    Log::warning("Error processing page {$pageCount}: " . $pageException->getMessage());
                    $errorMessages[] = "Page {$pageCount}: " . $pageException->getMessage();
                    $totalErrors++;

                    // Try to continue with next page using pagination cursors
                    $nextPageUrl = null;
                    if (isset($data['paging']['cursors']['after'])) {
                        $nextPageUrl = $apiUrl . '/' . $formId . '/leads';
                        $params = [
                            'access_token' => $accessToken,
                            'limit' => 100,
                            'after' => $data['paging']['cursors']['after']
                        ];

                        if ($startDate && $endDate) {
                            $params['since'] = $startDate->timestamp;
                            $params['until'] = $endDate->timestamp;
                        }
                    }
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

    /**
     * Refresh page access token using user access token
     */
    private function refreshPageAccessToken($pageId, $userAccessToken, $apiUrl): ?string
    {
        try {
            // Get fresh page access token from Facebook API
            $response = Http::timeout(30)->get($apiUrl . '/' . $pageId, [
                'access_token' => $userAccessToken,
                'fields' => 'access_token'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::warning("Failed to refresh page access token: " . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error("Error refreshing page access token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update pagination URL with new access token
     */
    private function updatePaginationUrlWithToken($url, $accessToken): string
    {
        // Remove existing access_token parameter if present
        $parsedUrl = parse_url($url);
        $query = [];

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
        }

        // Update or add access_token
        $query['access_token'] = $accessToken;

        // Rebuild URL
        $newQuery = http_build_query($query);
        $separator = isset($parsedUrl['query']) ? '?' : '?';

        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . $separator . $newQuery;
    }

    /**
     * Check if pagination URL has valid token by making a test request
     */
    private function isPaginationUrlValid($url): bool
    {
        try {
            $response = Http::timeout(10)->get($url);
            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }
}
