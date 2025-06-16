<?php

namespace App\Services;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SettingService
{
    public function renderSettingsPage(): \Illuminate\View\View
    {
        $settings = Setting::first();
        return view('backend.pages.settings', compact('settings'));
    }

    public function handleSettingsUpdate($request): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'website_name' => 'required|string',
                'website_email' => 'required|email',
                'copy_right_text' => 'required|string',
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

            $settings->save();

            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    public function getSetting(string $key, $default = null)
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }
}
