<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $config = config('huda');
        
        return view('settings.index', compact('settings', 'config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'currency_code' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:10',
            'date_format' => 'required|string',
            'timezone' => 'required|string',
            'default_cycle_time' => 'required|integer|min:1',
            'low_stock_threshold' => 'required|integer|min:1',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settings = $request->only([
            'company_name',
            'currency_code',
            'currency_symbol',
            'currency_position',
            'date_format',
            'time_format',
            'timezone',
            'language',
            'default_cycle_time',
            'quality_check_required',
            'auto_assign_employees',
            'low_stock_threshold',
            'auto_reorder',
            'auto_generate_invoice',
            'default_payment_terms',
            'tax_rate',
            'tax_inclusive',
            'invoice_prefix',
            'default_credit_limit',
            'email_notifications',
            'sms_notifications',
            'low_stock_alerts',
            'session_timeout',
            'password_min_length',
            'auto_backup',
            'backup_frequency',
            'primary_color',
            'dark_mode',
            'cache_enabled',
            'pagination_limit',
        ]);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear cache to ensure new settings are loaded
        Cache::forget('settings');
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }

    public function reset()
    {
        Setting::truncate();
        Cache::forget('settings');
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings reset to default values');
    }

    public function export()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return response()->json($settings, 200, [], JSON_PRETTY_PRINT)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="huda_settings.json"');
    }

    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json'
        ]);

        $file = $request->file('settings_file');
        $content = file_get_contents($file->getPathname());
        $settings = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->route('settings.index')
                ->with('error', 'Invalid JSON file');
        }

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('settings');
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings imported successfully');
    }
}
