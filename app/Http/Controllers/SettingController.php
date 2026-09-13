<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Example: Fetching settings key-value pairs
        $settings = Setting::pluck('value', 'key')->toArray();

        // Convert storage relative path to full public URL
        $faviconUrl = !empty($settings['app_favicon'])
            ? Storage::url($settings['app_favicon'])
            : asset('images/default-favicon.ico'); // Fallback icon

        return view('settings.index', compact('settings', 'faviconUrl'));
    }

    public function update(Request $request)
    {
        $rules = [
            // General Properties
            'app_name'           => 'required|string|max:255',
            'contact_email'      => 'required|email',
            'app_logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'app_favicon'        => 'nullable|mimes:ico,png,x-icon|max:1024',
            'maintenance_mode'   => 'nullable|in:0,1',
            'allow_registration' => 'nullable|in:0,1',

            // SMTP Configurations Matrix
            'mail_driver'        => 'nullable|string|max:50',
            'mail_host'          => 'nullable|string|max:255',
            'mail_port'          => 'nullable|integer',
            'mail_username'      => 'nullable|string|max:255',
            'mail_password'      => 'nullable|string|max:255',
            'mail_from_address'  => 'nullable|email',
        ];

        $validated = $request->validate($rules);

        // Fetch current settings to check for existing image file paths
        $existingSettings = Setting::pluck('value', 'key')->toArray();

        // 1. Process Logo Upload
        if ($request->hasFile('app_logo')) {
            // Unlink/Delete previous logo file if it exists
            if (!empty($existingSettings['app_logo']) && Storage::disk('public')->exists($existingSettings['app_logo'])) {
                Storage::disk('public')->delete($existingSettings['app_logo']);
            }

            $logoPath = $request->file('app_logo')->store('branding', 'public');
            $validated['app_logo'] = $logoPath;
        } else {
            // Keep existing logo path if no new file uploaded
            unset($validated['app_logo']);
        }

        // 2. Process Favicon Upload
        if ($request->hasFile('app_favicon')) {
            // Unlink/Delete previous favicon file if it exists
            if (!empty($existingSettings['app_favicon']) && Storage::disk('public')->exists($existingSettings['app_favicon'])) {
                Storage::disk('public')->delete($existingSettings['app_favicon']);
            }

            $faviconPath = $request->file('app_favicon')->store('branding', 'public');
            $validated['app_favicon'] = $faviconPath;
        } else {
            // Keep existing favicon path if no new file uploaded
            unset($validated['app_favicon']);
        }

        // 3. Loop keys and synchronize tracking properties
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'group' => $this->getGroup($key)]
            );
        }

        // Evict obsolete runtime cache instantly
        Cache::forget('global_system_settings');

        return response()->json([
            'success' => true,
            'message' => 'System settings configurations compiled and cache matrix updated.'
        ]);
    }

    private function getGroup($key)
    {
        if (str_starts_with($key, 'mail_')) {
            return 'mail';
        }

        if (in_array($key, ['app_logo', 'app_favicon'])) {
            return 'branding';
        }

        return 'general';
    }

    public function showImage($key)
    {
        // Retrieve stored relative file path by setting key (e.g., 'app_logo')
        $path = Setting::where('key', $key)->value('value');

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // Streams the file automatically setting correct MIME type and headers
        return Storage::disk('public')->response($path);
    }
}
