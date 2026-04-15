<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $subCategoryCount = SubCategory::count();
        $newsCount = News::count();
        $activeCount = Product::where('status', 1)->count();
        $pausedCount = Product::where('status', 0)->count();

        return view('admin.dashboard', compact(
            'productCount',
            'categoryCount',
            'subCategoryCount',
            'newsCount',
            'activeCount',
            'pausedCount'
        ));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.products.index');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
