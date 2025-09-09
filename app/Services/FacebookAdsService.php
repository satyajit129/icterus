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

class FacebookAdsService
{
    public function renderAdAccountsPage(Request $request): View
    {
        $query = FacebookAdAccount::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('active')) {
            $query->where('is_active', (bool) $request->active);
        }

        $accounts = $query->orderBy('name')->paginate(20)->appends($request->all());

        return view('backend.pages.facebook_ad_accounts', compact('accounts'));
    }

    public function syncAdAccounts(): array
    {
        $credentials = FacebookCredential::first();
        if (!$credentials || empty($credentials->user_access_token) || empty($credentials->api_url)) {
            return [
                'success' => false,
                'message' => 'Facebook credentials not configured.',
            ];
        }

        $apiUrl = rtrim($credentials->api_url, '/');
        $accessToken = $credentials->user_access_token;

        $collected = 0;
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        $messages = [];
        $nextUrl = $apiUrl . '/me/adaccounts?fields=id,account_id,name,currency,timezone_name,business{id,name}&access_token=' . urlencode($accessToken);

        try {
            do {
                $response = Http::timeout(60)->get($nextUrl);
                if (!$response->successful()) {
                    $error = $response->json('error.message') ?? $response->body();
                    $messages[] = 'API error: ' . $error;
                    $errors++;
                    break;
                }

                $data = $response->json();
                foreach (($data['data'] ?? []) as $acc) {
                    try {
                        $payload = [
                            'ad_account_id' => $acc['account_id'],
                            'ad_account_gid' => $acc['id'],
                            'name' => $acc['name'] ?? null,
                            'currency' => $acc['currency'] ?? null,
                            'timezone_name' => $acc['timezone_name'] ?? null,
                            'business_id' => $acc['business']['id'] ?? null,
                            'business_name' => $acc['business']['name'] ?? null,
                        ];

                        $existing = FacebookAdAccount::where('ad_account_id', $acc['account_id'])->first();
                        if ($existing) {
                            $existing->update($payload);
                            $updated++;
                        } else {
                            FacebookAdAccount::create($payload);
                            $collected++;
                        }
                    } catch (Exception $e) {
                        $messages[] = 'Save error for account ' . ($acc['account_id'] ?? '?') . ': ' . $e->getMessage();
                        $errors++;
                    }
                }

                $nextUrl = $data['paging']['next'] ?? null;
            } while ($nextUrl);
        } catch (Exception $e) {
            Log::error('AdAccounts sync failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => "Ad accounts synced. New: $collected, Updated: $updated, Skipped: $skipped, Errors: $errors",
            'errors' => $messages,
        ];
    }

    public function toggleActive($id): RedirectResponse
    {
        $acc = FacebookAdAccount::findOrFail($id);
        $acc->is_active = !$acc->is_active;
        $acc->save();
        return redirect()->back()->with('success', 'Status updated.');
    }

    public function delete($id): RedirectResponse
    {
        $acc = FacebookAdAccount::findOrFail($id);
        $accId = $acc->ad_account_gid;
        $acc->delete();
        return redirect()->back()->with('success', 'Deleted ' . $accId);
    }
}
