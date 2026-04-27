<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        // Check Access (Only Super Admin / Admin)
        $userId = session('id');
        if (!$userId) return redirect('/login');
        
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user || ($user->level_id != 5 && $user->level_id != 6)) {
            return abort(403);
        }

        $setting = Setting::first();
        return view('app_settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        // Check Access
        $userId = session('id');
        if (!$userId) return redirect('/login');
        
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user || ($user->level_id != 5 && $user->level_id != 6)) {
            return abort(403);
        }

        $request->validate([
            'site_name' => 'nullable|string|max:191',
            'manager_name' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'contact_info' => 'nullable|string|max:191',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'theme_primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'theme_secondary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'app_background_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'sidebar_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'topbar_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'background_overlay_opacity' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'footer_text' => 'nullable|string|max:500',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->site_name = $request->site_name;
        $setting->manager_name = $request->manager_name;
        $setting->address = $request->address;
        $setting->contact_info = $request->contact_info;
        $setting->theme_primary_color = $request->theme_primary_color ?: '#4f46e5';
        $setting->theme_secondary_color = $request->theme_secondary_color ?: '#3730a3';
        $setting->app_background_color = $request->app_background_color ?: '#f8fafc';
        $setting->sidebar_bg_color = $request->sidebar_bg_color ?: '#0f172a';
        $setting->topbar_bg_color = $request->topbar_bg_color ?: '#ffffff';
        $setting->background_overlay_opacity = $request->background_overlay_opacity !== null
            ? (float) $request->background_overlay_opacity
            : 0.88;
        $setting->footer_text = $request->footer_text;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }
            
            $path = $request->file('logo')->store('settings', 'public');
            $setting->logo = $path;
        }

        if ($request->hasFile('background_image')) {
            if ($setting->background_image && Storage::exists('public/' . $setting->background_image)) {
                Storage::delete('public/' . $setting->background_image);
            }

            $backgroundPath = $request->file('background_image')->store('settings/backgrounds', 'public');
            $setting->background_image = $backgroundPath;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
