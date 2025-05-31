<?php
namespace App\Services;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function renderSettingsPage(): \Illuminate\View\View
    {
        $settings = Setting::first();
        // dd($settings);
        return view('backend.pages.settings', compact('settings'));
    }

    public function handleSettingsUpdate($request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->all();
            $settings = Setting::first();
            if (!$settings) {
                $settings = new Setting();
            }
            if ($request->hasFile('logo')) {
                $logoExtension = $request->logo->getClientOriginalExtension();
                $logoName = 'logo_' . time() . '.' . $logoExtension;
                $request->logo->move(public_path('uploads'), $logoName);
                $data['logo'] = $logoName;
            }
            if ($request->hasFile('favicon')) {
                $faviconExtension = $request->favicon->getClientOriginalExtension();
                $faviconName = 'favicon_' . time() . '.' . $faviconExtension;
                $request->favicon->move(public_path('uploads'), $faviconName);
                $data['favicon'] = $faviconName;
            }
            $settings->website_name = $data['website_name'];
            $settings->website_email = $data['website_email'];
            $settings->copy_right_text = $data['copy_right_text'] ?? $settings->copy_right_text;
            $settings->logo = $data['logo'] ?? $settings->logo;
            $settings->favicon = $data['favicon'] ?? $settings->favicon;
            $settings->save();
            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Settings update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the settings.');
        }
    }



    public function getSetting(string $key, $default = null)
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }
}
