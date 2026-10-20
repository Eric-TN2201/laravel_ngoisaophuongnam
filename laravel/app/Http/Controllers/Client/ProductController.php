<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $products = Product::visible()->latest()->paginate(12);
        return view('client.product.index', compact('categories', 'products'));
    }

    public function category(Category $category, SubCategory $subCategory = null)
    {
        abort_unless($category->status == 1 && (!$subCategory || ($subCategory->status == 1 && $subCategory->category_id == $category->id)), 404);
        $query = Product::visible()->where('category_id', $category->id);
        if ($subCategory) {
            $query->where('sub_category_id', $subCategory->id);
        }
        $products = $query->where('status', 1)->latest()->paginate(12);
        $subCategories = $category->subCategories()->where('status', 1)->get();

        return view('client.product.category', compact('category', 'subCategory', 'subCategories', 'products'));
    }

    public function show(Product $product)
    {
        abort_unless(Product::visible()->whereKey($product->id)->exists(), 404);
        $relatedProducts = Product::visible()->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->latest()
            ->take(4)
            ->get();
        return view('client.product.show', compact('product', 'relatedProducts'));
    }
}
