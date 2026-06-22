<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::where('status', 1)->orderBy('order')->get();

        // 8 promote products (status=1)
        $promoteProducts = \App\Models\Product::where('status', 1)->latest()->take(8)->get();

        // Our story
        $story = optional(\App\Models\Story::first())->content;

        // All categories with products (max 8 per category)
        $categories = \App\Models\Category::with(['products' => function($q){ $q->where('status',1)->latest()->take(8); }])->get();

        // Hot news (status=posted, latest, max 6)
        $hotNews = \App\Models\News::posted()->latest()->take(1)->get();

        return view('client.home', compact('banners', 'promoteProducts', 'story', 'categories', 'hotNews'));
    }
}
