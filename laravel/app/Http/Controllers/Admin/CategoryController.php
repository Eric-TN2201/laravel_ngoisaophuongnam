<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        info($categories);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        try {
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
            }

            Category::create($data);

            return redirect()
                ->route('admin.category.index')
                ->with('success', 'Thêm thành công');
        } catch (Throwable $e) {

            // Log error for debugging
            Log::error('Create category failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Thêm thất bại, vui lòng thử lại');
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validatedData($request, $category->id);


        try {
            if ($request->hasFile('thumbnail')) {
                if ($category->thumbnail) {
                    Storage::disk('public')->delete($category->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
            }

            $category->update($data);

            return redirect()->route('admin.category.index')
                ->with('success', 'Cập nhật thành công');
        } catch (Throwable $e) {

            // Log error for debugging
            Log::error('Create category failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Thêm thất bại, vui lòng thử lại');
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->thumbnail) {
                Storage::disk('public')->delete($category->thumbnail);
            }

            $category->delete();

            return redirect()->route('admin.category.index')
                ->with('success', 'Xóa thành công');
        } catch (Throwable $e) {
            Log::error('Delete category failed', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Xóa thất bại, vui lòng thử lại');
        }
    }

    private function validatedData(Request $request, $id = null)
    {
        $request->merge([
            'slug' => Str::slug($request->name)
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                Rule::unique('categories', 'slug')->ignore($id),
            ],
            'status' => 'required|boolean',
            'thumbnail' => 'nullable|image|max:2048',
        ];
        $messages = [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'slug.unique'   => 'Tên danh mục đã tồn tại.',
            'thumbnail.image' => 'Ảnh danh mục phải là định dạng hình ảnh hợp lệ.',
            'thumbnail.max' => 'Ảnh danh mục không được lớn hơn 2MB.',
        ];
        return $request->validate($rules, $messages);
    }
}
