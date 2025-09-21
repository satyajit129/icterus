<?php

namespace App\Services;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingService
{
    public function renderSettingsPage(): View
    {
        $settings = Setting::first();
        return view('backend.pages.settings', compact('settings'));
    }

    public function handleSettingsUpdate($request): RedirectResponse
    {
        try {
            $request->validate([
                'website_name' => 'required|string',
                'website_email' => 'required|email',
                'copy_right_text' => 'required|string',
                'phone' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'hero_title' => 'nullable|string|max:500',
                'hero_subtitle' => 'nullable|string|max:1000',
                'featured_products_title' => 'nullable|string|max:500',
                'featured_products_subtitle' => 'nullable|string|max:1000',
                'fast_delivery_title' => 'nullable|string|max:200',
                'fast_delivery_description' => 'nullable|string|max:500',
                'quality_guarantee_title' => 'nullable|string|max:200',
                'quality_guarantee_description' => 'nullable|string|max:500',
                'support_title' => 'nullable|string|max:200',
                'support_description' => 'nullable|string|max:500',
                'bkash_merchant_number' => 'nullable|string|max:20',
                'mail_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|string|max:10',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_encryption' => 'nullable|string|in:tls,ssl',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
                'facebook_pixel_id' => 'nullable|string|max:50',
                'facebook_pixel_enabled' => 'nullable|boolean',
                'facebook_pixel_events' => 'nullable|array',
                'gtm_id' => 'nullable|string|max:50',
                'gtm_enabled' => 'nullable|boolean',
                'gtm_events' => 'nullable|array',
                'gtm_custom_dimensions' => 'nullable|string|max:1000',
                'gtm_ecommerce_settings' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'favicon' => 'nullable|image|mimes:ico,jpg,jpeg,png|max:1024',
            ]);

            $settings = Setting::first() ?? new Setting();

            if ($request->hasFile('logo')) {
                $logoName = 'logo_' . time() . '.' . $request->logo->getClientOriginalExtension();
                $request->logo->move(public_path('uploads'), $logoName);
                $settings->logo = $logoName;
            }

            if ($request->hasFile('favicon')) {
                $faviconName = 'favicon_' . time() . '.' . $request->favicon->getClientOriginalExtension();
                $request->favicon->move(public_path('uploads'), $faviconName);
                $settings->favicon = $faviconName;
            }

            $settings->website_name = $request->website_name;
            $settings->website_email = $request->website_email;
            $settings->copy_right_text = $request->copy_right_text;
            $settings->phone = $request->phone;
            $settings->whatsapp = $request->whatsapp;
            $settings->address = $request->address;
            $settings->hero_title = $request->hero_title;
            $settings->hero_subtitle = $request->hero_subtitle;
            $settings->featured_products_title = $request->featured_products_title;
            $settings->featured_products_subtitle = $request->featured_products_subtitle;
            $settings->fast_delivery_title = $request->fast_delivery_title;
            $settings->fast_delivery_description = $request->fast_delivery_description;
            $settings->quality_guarantee_title = $request->quality_guarantee_title;
            $settings->quality_guarantee_description = $request->quality_guarantee_description;
            $settings->support_title = $request->support_title;
            $settings->support_description = $request->support_description;
            $settings->bkash_merchant_number = $request->bkash_merchant_number;
            $settings->mail_mailer = $request->mail_mailer;
            $settings->mail_host = $request->mail_host;
            $settings->mail_port = $request->mail_port;
            $settings->mail_username = $request->mail_username;
            $settings->mail_password = $request->mail_password;
            $settings->mail_encryption = $request->mail_encryption;
            $settings->mail_from_address = $request->mail_from_address;
            $settings->mail_from_name = $request->mail_from_name;
            $settings->facebook_pixel_id = $request->facebook_pixel_id;
            $settings->facebook_pixel_enabled = $request->has('facebook_pixel_enabled') ? true : false;
            $settings->facebook_pixel_events = $request->facebook_pixel_events ? json_encode($request->facebook_pixel_events) : null;
            $settings->gtm_id = $request->gtm_id;
            $settings->gtm_enabled = $request->has('gtm_enabled') ? true : false;
            $settings->gtm_events = $request->gtm_events ? json_encode($request->gtm_events) : null;
            $settings->gtm_custom_dimensions = $request->gtm_custom_dimensions;
            $settings->gtm_ecommerce_settings = $request->gtm_ecommerce_settings;

            $settings->save();

            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }



    public function getSetting(string $key, $default = null)
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }

    public function getAllSettings()
    {
        return Setting::first() ?? new Setting();
    }
}
