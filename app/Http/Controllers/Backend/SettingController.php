<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('backend.pages.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'site_name' => '',
                'email'     => '',
            ]);
        }

        $request->validate([
            'site_name'  => 'required|string',
            'email'      => 'required|email',
            'site_logo'  => 'nullable|image',
            'admin_logo' => 'nullable|image',
            'favicon'    => 'nullable|image',
        ]);

        $data = $request->only('site_name', 'email');

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $name = 'site_logo_' . time() . '.' . $file->extension();
            $file->move(public_path('uploads/settings'), $name);
            $data['site_logo'] = 'uploads/settings/' . $name;
        }

        if ($request->hasFile('admin_logo')) {
            $file = $request->file('admin_logo');
            $name = 'admin_logo_' . time() . '.' . $file->extension();
            $file->move(public_path('uploads/settings'), $name);
            $data['admin_logo'] = 'uploads/settings/' . $name;
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $name = 'favicon_' . time() . '.' . $file->extension();
            $file->move(public_path('uploads/settings'), $name);
            $data['favicon'] = 'uploads/settings/' . $name;
        }

        $setting->update($data);

        return back()->with('success', 'Settings updated successfully!');
    }
}
