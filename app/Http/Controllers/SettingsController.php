<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{


    public function __construct()
    {
        $this->middleware('checkPermission:settings')->only('index', 'store');
    }

    public function index()
    {
        $settings = Setting::all();

        return Inertia::render('Settings', [
            'settings' => $settings->keyBy('key')
        ]);
    }

    public function store(Request $request)
    {
        // validate the request
        $validated = $request->validate([
            //general settings
            'date_format' => 'required',
            'default_timezone' => 'required',
            'currency_symbol' => 'required',
            'currency_code' => 'required',
            'currency_format' => 'required',
            // 'default_language' => 'required',
            // 'allow_employee_login' => 'required',
            'max_file_size_upload' => 'required',
            'google_map_key' => 'required',
            //company settings
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required',
            'company_website' => 'required',
            'company_address' => 'required',
            'company_city' => 'required',
            'company_state' => 'required',
            'company_country' => 'required',
            'company_tax_no' => 'required',
            //slack settings
            'signing_secret' => 'required',
            // 'webhook_url' => 'required',
            //mail settings
            'mail_from_name' => 'required',
            'mail_from_address' => 'required',
            'mail_encryption' => 'required',
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
        ]);

        // Save the settings to the database
        foreach($validated as $key => $value) {
            if ($key === 'signing_secret') {
                $value = encrypt($value);
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Include a success message in the next Inertia response
        return Inertia::render('Settings', [
            'settings' => Setting::all(), // Fetch all settings here
        ])->with('flash', ['message' => 'Settings saved successfully']);
    }

}
