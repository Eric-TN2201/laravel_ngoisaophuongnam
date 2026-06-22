<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        'home_banner_media_type',
        'home_banner_media_image',
        'home_banner_media_video',
        'home_banner_media_link',
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
            'home_banner_media_type'   => 'nullable|in:image,video',
            'home_banner_media_link'   => 'nullable|url|max:2048',
            'home_banner_media_image'  => 'nullable|image|max:51200',
            'home_banner_media_video'  => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
        ]);

        if ($request->hasFile('home_banner_media_image')) {
            $oldImage = Setting::get('home_banner_media_image');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $data['home_banner_media_image'] = $request->file('home_banner_media_image')->store('settings', 'public');
        }

        if ($request->hasFile('home_banner_media_video')) {
            $oldVideo = Setting::get('home_banner_media_video');
            if ($oldVideo) {
                Storage::disk('public')->delete($oldVideo);
            }
            $data['home_banner_media_video'] = $request->file('home_banner_media_video')->store('settings', 'public');
        }

        Setting::setMany($data);

        return redirect()->route('admin.setting.edit')
            ->with('success', 'Cập nhật thông tin thành công');
    }
}
