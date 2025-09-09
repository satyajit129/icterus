<?php

namespace App\Services;

use App\Models\FacebookAdAccount;
use App\Models\FacebookCredential;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FacebookAdAccountService
{
    public function renderAdAccountsPage(Request $request): View
    {
        $query = FacebookAdAccount::query();

        $search = $request->get('search');
        $status = $request->get('status');
        $businessId = $request->get('business_id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('ad_account_id', 'like', '%' . $search . '%')
                    ->orWhere('ad_account_gid', 'like', '%' . $search . '%')
                    ->orWhere('business_name', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null) {
            $query->where('is_active', $status);
        }

        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        $adAccounts = $query->paginate(10);

        $statuses = [
            1 => 'Active',
            0 => 'Inactive',
        ];

        $businesses = FacebookAdAccount::whereNotNull('business_id')
            ->select('business_id', 'business_name')
            ->distinct()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->business_id => $item->business_name];
            });

        return view('backend.pages.facebook_ad_accounts', compact('adAccounts', 'statuses', 'businesses'));
    }

    public function handleSyncAdAccountsFromApi(): RedirectResponse
    {
        try {
            $credentials = FacebookCredential::first();

            if (!$credentials || empty($credentials->user_access_token)) {
                return redirect()->back()->with('error', 'Facebook credentials not configured.');
            }

            $apiUrl = $this->ensureLatestApiVersion($credentials->api_url);
            $adAccountsResult = $this->getUserAdAccounts($credentials->user_access_token, $apiUrl);

            if ($adAccountsResult['error']) {
                Log::error('Facebook Ad Accounts API Error: ' . $adAccountsResult['message']);
                return redirect()->back()->with('error', $adAccountsResult['message']);
            }

            $adAccountsData = $adAccountsResult['data'];
            $totalCollected = 0;
            $totalSkipped = 0;
            $totalUpdated = 0;
            $totalErrors = 0;

            foreach ($adAccountsData as $adAccountData) {
                try {
                    FacebookAdAccount::updateOrCreate(
                        ['ad_account_gid' => $adAccountData['id']],
                        [
                            'ad_account_id' => $adAccountData['account_id'],
                            'name' => $adAccountData['name'],
                            'currency' => $adAccountData['currency'],
                            'timezone_name' => $adAccountData['timezone_name'],
                            'business_id' => $adAccountData['business']['id'] ?? null,
                            'business_name' => $adAccountData['business']['name'] ?? null,
                            'is_active' => ($adAccountData['account_status'] ?? 1) == 1,
                        ]
                    );
                    $totalCollected++;
                } catch (Exception $e) {
                    Log::error('Error saving ad account ' . $adAccountData['id'] . ': ' . $e->getMessage());
                    $totalErrors++;
                }
            }

            $message = "Ad accounts synced. New: {$totalCollected}, Updated: {$totalUpdated}, Skipped: {$totalSkipped}, Errors: {$totalErrors}";

            if ($totalErrors > 0) {
                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('Facebook Ad Accounts Sync Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while syncing ad accounts: ' . $e->getMessage());
        }
    }

    public function handleDeleteAdAccount($id): RedirectResponse
    {
        try {
            $adAccount = FacebookAdAccount::findOrFail($id);
            $adAccount->delete();

            return redirect()->back()->with('success', 'Ad account deleted successfully.');
        } catch (Exception $e) {
            Log::error('Facebook Ad Account Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the ad account: ' . $e->getMessage());
        }
    }

    public function ensureLatestApiVersion($apiUrl)
    {
        // If the URL doesn't contain a version, add v23.0
        if (!preg_match('/\/v\d+\.\d+\//', $apiUrl)) {
            return rtrim($apiUrl, '/') . '/v23.0';
        }

        // Replace any version with v23.0
        if (strpos($apiUrl, 'v18.0') !== false) {
            return str_replace('v18.0', 'v23.0', $apiUrl);
        } elseif (strpos($apiUrl, 'v19.0') !== false) {
            return str_replace('v19.0', 'v23.0', $apiUrl);
        } elseif (strpos($apiUrl, 'v20.0') !== false) {
            return str_replace('v20.0', 'v23.0', $apiUrl);
        } elseif (strpos($apiUrl, 'v21.0') !== false) {
            return str_replace('v21.0', 'v23.0', $apiUrl);
        } elseif (strpos($apiUrl, 'v22.0') !== false) {
            return str_replace('v22.0', 'v23.0', $apiUrl);
        }

        return $apiUrl;
    }

    private function getUserAdAccounts($accessToken, $apiUrl)
    {
        try {
            $response = Http::timeout(30)->get($apiUrl . '/me/adaccounts', [
                'access_token' => $accessToken,
                'fields' => 'id,account_id,name,currency,timezone_name,account_status,business{id,name}'
            ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                return [
                    'error' => true,
                    'message' => 'Failed to fetch ad accounts: ' . ($errorData['error']['message'] ?? 'Unknown error'),
                    'data' => []
                ];
            }

            $data = $response->json();
            return [
                'error' => false,
                'message' => 'Success',
                'data' => $data['data'] ?? []
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'Error fetching ad accounts: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
