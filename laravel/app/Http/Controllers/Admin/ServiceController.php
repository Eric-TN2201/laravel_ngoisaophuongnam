<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('services', 'public');
            }

            Service::create($data);
            return redirect()->route('admin.service.index')
                ->with('success', 'Dịch vụ được tạo thành công');
        } catch (Throwable $e) {
            Log::error('Create service failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tạo dịch vụ thất bại, vui lòng thử lại');
        }
    }

    public function edit(Service $service)
    {
        return view('admin.service.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validatedData($request);

        try {
            if ($request->hasFile('image')) {
                if ($service->image) {
                    Storage::disk('public')->delete($service->image);
                }
                $data['image'] = $request->file('image')->store('services', 'public');
            }

            $service->update($data);
            return redirect()->route('admin.service.index')
                ->with('success', 'Dịch vụ được cập nhật thành công');
        } catch (Throwable $e) {
            Log::error('Update service failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cập nhật dịch vụ thất bại, vui lòng thử lại');
        }
    }

    public function destroy(Service $service)
    {
        try {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();
            return redirect()->route('admin.service.index')
                ->with('success', 'Dịch vụ đã bị xóa');
        } catch (Throwable $e) {
            Log::error('Delete service failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Xóa dịch vụ thất bại, vui lòng thử lại');
        }
    }

    private function validatedData(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'image.image' => 'Hình ảnh phải là file ảnh.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);
    }
}
