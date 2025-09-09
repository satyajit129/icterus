<?php

namespace App\Services;

use App\Models\FacebookCredential;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FacebookCredentialsService
{
    public function renderFacebookCredentialsPage(): View
    {
        $facebookCredential = FacebookCredential::first();

        // If no record exists, create one with default values
        if (!$facebookCredential) {
            $facebookCredential = FacebookCredential::create([
                'app_id' => '',
                'app_secret' => '',
                'agency_id' => '',
                'user_access_token' => '',
                'api_url' => '',
                'version' => '',
            ]);
        }

        return view('backend.pages.facebook_credentials', compact('facebookCredential'));
    }

    public function handleFacebookCredentialsUpdate($request): RedirectResponse
    {
        try {
            $request->validate([
                'app_id' => 'nullable|string|max:255',
                'app_secret' => 'nullable|string|max:1000',
                'agency_id' => 'nullable|string|max:255',
                'user_access_token' => 'nullable|string|max:1000',
                'api_url' => 'nullable|url|max:500',
                'version' => 'nullable|string|max:50',
            ]);

            $facebookCredential = FacebookCredential::first();

            if (!$facebookCredential) {
                $facebookCredential = new FacebookCredential();
            }

            $facebookCredential->app_id = $request->app_id;
            $facebookCredential->app_secret = $request->app_secret;
            $facebookCredential->agency_id = $request->agency_id;
            $facebookCredential->user_access_token = $request->user_access_token;
            $facebookCredential->api_url = $request->api_url;
            $facebookCredential->version = $request->version;
            $facebookCredential->save();

            return redirect()->route('adminFacebookCredentials')
                ->with('success', 'Facebook Credentials updated successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
