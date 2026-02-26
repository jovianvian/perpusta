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
            'site_name' => 'required|string|max:191',
            'manager_name' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'contact_info' => 'nullable|string|max:191',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->site_name = $request->site_name;
        $setting->manager_name = $request->manager_name;
        $setting->address = $request->address;
        $setting->contact_info = $request->contact_info;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }
            
            $path = $request->file('logo')->store('settings', 'public');
            $setting->logo = $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}

