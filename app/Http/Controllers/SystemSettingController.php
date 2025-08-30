<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $data['setting'] = SystemSetting::first();
        $data['main_menu'] = 'basic_settings';
        $data['child_menu'] = 'system_settings';
        return view('systemsetting.index', $data);
    }

    public function update(Request $request)
    {
       $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'about' => 'nullable|string',
            'phone' => 'nullable|string',
            'copyright_text' => 'nullable|string',
            'develop_by' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'fav_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $setting = SystemSetting::first();

        if (!$setting) {
            $setting = new SystemSetting();
        }

        $setting->name = $request->name;
        $setting->email = $request->email;
        $setting->address = $request->address;
        $setting->about = $request->about;
        $setting->phone = $request->phone;
        $setting->copyright_text = $request->copyright_text;
        $setting->develop_by = $request->develop_by;

        if ($request->hasFile('logo')) {
            $newLogoName = 'logo_' . uniqid() . '.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('settings/'), $newLogoName);
            $setting->logo = 'settings/' . $newLogoName;
        }

        if ($request->hasFile('fav_icon')) {
            $newFavIconName = 'fav_icon_' . uniqid() . '.' . $request->file('fav_icon')->extension();
            $request->file('fav_icon')->move(public_path('settings/'), $newFavIconName);
            $setting->fav_icon = 'settings/' . $newFavIconName;
        }

        $setting->save();



        return redirect()->route('systemsetting.index')->with('success', 'System settings updated successfully!');
    }
}

