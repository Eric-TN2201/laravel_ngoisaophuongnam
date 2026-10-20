<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $newsItems = News::posted()->latest()->paginate(10);
        return view('client.news.index', compact('newsItems'));
    }

    public function show(News $news)
    {
        abort_unless($news->status == 1, 404);
        $latestNews = News::posted()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();
        return view('client.news.show', compact('news', 'latestNews'));
    }
}
