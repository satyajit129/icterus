<?php
namespace App\Services;

use App\Http\Requests\SettingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingService
{
    public function renderSettingsPage()
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('backend.pages.settings', compact('settings'));
    }

    public function handleSettingsUpdate(SettingRequest $request)
    {
        
    }

    public function getSetting(string $key, $default = null)
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }
}
