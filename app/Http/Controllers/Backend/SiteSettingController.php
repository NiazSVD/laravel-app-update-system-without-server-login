<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();

        return view('backend.pages.settings.site', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = SiteSetting::firstOrCreate(['id' => 1]);

        if ($request->hasFile('site_logo')) {
            $logoFile = $request->file('site_logo');
            $logoName = 'logo.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('uploads/site'), $logoName);
            $setting->site_logo = 'uploads/site/' . $logoName;
        }

        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconName = 'favicon.' . $faviconFile->getClientOriginalExtension();
            $faviconFile->move(public_path('uploads/site'), $faviconName);
            $setting->favicon = 'uploads/site/' . $faviconName;
        }

        $setting->fill($request->except(['site_logo', 'favicon']));
        $setting->save();

        return back()->with('success', 'Settings updated successfully!');
    }
}
