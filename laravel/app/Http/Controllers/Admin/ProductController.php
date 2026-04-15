<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('subCategory.category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $subCategories = SubCategory::with('category')->get();
        $categories = Category::with('subCategories')->get();
        return view('admin.products.create', compact('subCategories', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        try {
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            // ensure nullable sub_category_id stored as null when not provided
            if (isset($data['sub_category_id']) && $data['sub_category_id'] === '') {
                $data['sub_category_id'] = null;
            }

            if(!isset($data['meta_title'])){
                $data['meta_title'] = $data['name'] . ' - ' . setting('company_name', config('app.name'));
            }

            if(!isset($data['meta_description'])){
                $data['meta_description'] = $data['name'] . ' - ' . setting('company_name', config('app.name'));
            }

            Product::create($data);

            return redirect()->route('admin.product.index')
                ->with('success', 'Thêm sản phẩm thành công');
        } catch (Throwable $e) {
            Log::error('Create product failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Thêm sản phẩm thất bại, vui lòng thử lại');
        }
    }

    public function edit(Product $product)
    {
        $subCategories = SubCategory::with('category')->get();
        $categories = \App\Models\Category::with('subCategories')->get();
        return view('admin.products.edit', compact('product', 'subCategories', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product->id);

        try {
            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            if (isset($data['sub_category_id']) && $data['sub_category_id'] === '') {
                $data['sub_category_id'] = null;
            }

            if(!isset($data['meta_title'])){
                $data['meta_title'] = $data['name'] . ' - ' . setting('company_name', config('app.name'));
            }

            if(!isset($data['meta_description'])){
                $data['meta_description'] = $data['name'] . ' - ' . setting('company_name', config('app.name'));
            }

            $product->update($data);

            return redirect()->route('admin.product.index')
                ->with('success', 'Cập nhật sản phẩm thành công');
        } catch (Throwable $e) {
            Log::error('Update product failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Cập nhật sản phẩm thất bại, vui lòng thử lại');
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->delete();

            return redirect()->route('admin.product.index')
                ->with('success', 'Xóa sản phẩm thành công');
        } catch (Throwable $e) {
            Log::error('Delete product failed', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Xóa sản phẩm thất bại, vui lòng thử lại');
        }
    }

    private function validatedData(Request $request, $id = null)
    {
        $request->merge([
            'slug' => Str::slug($request->name)
        ]);

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => [
                'nullable',
                Rule::exists('sub_categories', 'id')->where(function ($query) use ($request) {
                    $query->where('category_id', $request->input('category_id'));
                }),
            ],
            'name' => 'required|string|max:255',
            'slug' => [
                'string',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
        ];

        $messages = [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'sub_category_id.exists' => 'Danh mục con không thuộc danh mục đã chọn.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'slug.unique' => 'Tên sản phẩm đã tồn tại.',
            'price.numeric' => 'Giá phải là một số.',
            'price.min' => 'Giá không được âm.',
            'thumbnail.image' => 'Tập tin phải là hình ảnh.',
            'thumbnail.max' => 'Hình ảnh không được vượt quá 2MB.',
        ];

        return $request->validate($rules, $messages);
    }
}

