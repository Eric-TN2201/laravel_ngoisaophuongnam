<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeoController extends Controller
{
    public function preview(Request $request)
    {
        $data = $request->validate([
            'page_route' => ['required', 'string', Rule::in(array_merge(array_keys(Seo::STATIC_PAGES), Seo::CONTENT_PAGES))],
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        $seo = Seo::preview($data['page_route'], $data);

        return response()->json([
            'title' => $seo['title'], 'description' => $seo['description'],
            'title_length' => mb_strlen($seo['title']),
            'description_length' => mb_strlen($seo['description']),
        ]);
    }

    public function edit()
    {
        $settings = [];
        foreach (Seo::STATIC_PAGES as $route => $label) {
            $prefix = 'seo_'.str_replace('.', '_', $route);
            $settings[$prefix.'_title'] = Setting::get($prefix.'_title', '');
            $settings[$prefix.'_description'] = Setting::get($prefix.'_description', '');
        }

        return view('admin.seo.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (Seo::STATIC_PAGES as $route => $label) {
            $prefix = 'seo_'.str_replace('.', '_', $route);
            $rules[$prefix.'_title'] = 'nullable|string|max:255';
            $rules[$prefix.'_description'] = 'nullable|string|max:500';
        }
        Setting::setMany($request->validate($rules));

        return redirect()->route('admin.seo.edit')->with('success', 'Đã lưu SEO website.');
    }
}
