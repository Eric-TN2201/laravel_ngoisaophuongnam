<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Story;

class PageController extends Controller
{
    public function about()
    {
        // Show 'Our Story' on client page
        $story = Story::first();
        return view('client.about', ['story' => $story ? $story->content : '']);
        // return view('client.about');
    }
}
