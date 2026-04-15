<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;


class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('banners', 'public');
            }
            Banner::create($data);
            return redirect()->route('admin.banner.index')->with('success', 'Thêm thành công');
        } catch (Throwable $e) {
            Log::error('Create banner failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Thêm thất bại, vui lòng thử lại');
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validatedData($request, $banner->id);
        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $data['image'] = $request->file('image')->store('banners', 'public');
            }
            $banner->update($data);
            return redirect()->route('admin.banner.index')->with('success', 'Cập nhật thành công');
        } catch (Throwable $e) {
            Log::error('Update banner failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Cập nhật thất bại, vui lòng thử lại');
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();
            return redirect()->route('admin.banner.index')->with('success', 'Xóa thành công');
        } catch (Throwable $e) {
            Log::error('Delete banner failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Xóa thất bại, vui lòng thử lại');
        }
    }

    private function validatedData(Request $request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'image' => [
                $id ? 'nullable' : 'required',
                'image',
            ],
            'status' => 'required|boolean',
        ];
        $messages = [
            'title.required' => 'Vui lòng nhập tên banner.',
            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'File phải là hình ảnh.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
        return $request->validate($rules, $messages);
    }
}
