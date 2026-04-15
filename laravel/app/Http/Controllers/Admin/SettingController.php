<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $settingKeys = [
        'company_name',
        'company_address',
        'company_address_pluscode',
        'company_phone',
        'company_email',
        'company_facebook',
        'company_zalo',
    ];

    public function edit()
    {
        $settings = [];
        foreach ($this->settingKeys as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name'             => 'nullable|string|max:255',
            'company_address'          => 'nullable|string|max:500',
            'company_address_pluscode'  => 'nullable|string|max:500',
            'company_phone'            => 'nullable|string|max:50',
            'company_email'            => 'nullable|string|email|max:255',
            'company_facebook'         => 'nullable|string|max:255',
            'company_zalo'             => 'nullable|string|max:255',
        ]);

        Setting::setMany($data);

        return redirect()->route('admin.setting.edit')
            ->with('success', 'Cập nhật thông tin thành công');
    }
}
