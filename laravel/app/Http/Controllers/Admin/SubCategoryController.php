<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->get();
        return view('admin.sub-categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        try {
            SubCategory::create([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'status' => $data['status'],
            ]);

            return redirect()
                ->route('admin.sub-category.index')
                ->with('success', 'Thêm thành công');
        } catch (Throwable $e) {
            Log::error('Create sub-category failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Thêm thất bại, vui lòng thử lại');
        }
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('admin.sub-categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $data = $this->validatedData($request, $subCategory->id);

        try {
            $subCategory->update($data);

            return redirect()
                ->route('admin.sub-category.index')
                ->with('success', 'Thêm thành công');
        } catch (Throwable $e) {
            Log::error('Update sub-category failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Cập nhật thất bại, vui lòng thử lại');
        }
    }

    public function destroy(SubCategory $subCategory)
    {
        try {
            $subCategory->delete();

            return redirect()->route('admin.sub-category.index')
                ->with('success', 'Xóa thành công');
        } catch (Throwable $e) {
            Log::error('Delete sub-category failed', ['error' => $e->getMessage()]);

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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'string',
                Rule::unique('sub_categories', 'slug')->ignore($id),
            ],
            'status' => 'required|boolean',
        ];

        $messages = [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'name.required' => 'Vui lòng nhập tên danh mục con.',
            'slug.unique' => 'Tên danh mục con đã tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];

        return $request->validate($rules, $messages);
    }
}
