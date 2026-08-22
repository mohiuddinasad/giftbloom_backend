<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Backend\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Settings::instance();

        return view('backend.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg|max:512',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $setting = Settings::instance();

        $data = $request->only([
            'site_name',
            'contact_phone',
            'contact_email',
            'contact_address',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ]);

        if ($request->hasFile('site_logo')) {
            if ($setting->site_logo && file_exists(public_path($setting->site_logo))) {
                unlink(public_path($setting->site_logo));
            }
            $data['site_logo'] = $this->storeFile($request->file('site_logo'), 'logo');
        }

        if ($request->hasFile('site_favicon')) {
            if ($setting->site_favicon && file_exists(public_path($setting->site_favicon))) {
                unlink(public_path($setting->site_favicon));
            }
            $data['site_favicon'] = $this->storeFile($request->file('site_favicon'), 'favicon');
        }

        $setting->update($data);

        return redirect()->route('dashboard.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    private function storeFile($file, string $prefix): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $prefix.'_'.uniqid().'_'.time().'.'.$extension;

        $file->move(public_path('uploads/settings'), $filename);

        return 'uploads/settings/'.$filename;
    }
}
