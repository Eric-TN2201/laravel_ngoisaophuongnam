<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;

class StoryController extends Controller
{
    // Show the story edit page
    public function edit()
    {
        $story = Story::first();
        return view('admin.about', ['story' => $story ? $story->content : '']);
    }

    // Update the story content
    public function update(Request $request)
    {
        $request->validate([
            'story_content' => 'required|string',
        ]);
        $story = Story::first();
        if ($story) {
            $story->content = $request->story_content;
            $story->save();
        } else {
            Story::create(['content' => $request->story_content]);
        }
        return redirect()->route('admin.story.edit')->with('success', 'Cập nhật thành công!');
    }

    // Upload image from TinyMCE
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:4096',
        ]);
        $path = $request->file('file')->store('stories', 'public');
        return response()->json(['location' => asset('storage/' . $path)]);
    }
}
