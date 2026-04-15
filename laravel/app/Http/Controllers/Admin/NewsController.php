<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class NewsController extends Controller
{
    public function index()
    {
        $newsItems = News::latest()->get();
        return view('admin.news.index', compact('newsItems'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['created_by'] = auth()->id();

        try {
            if ($request->hasFile('banner')) {
                $data['banner'] = $request->file('banner')->store('news', 'public');
            }

            News::create($data);
            return redirect()->route('admin.news.index')
                ->with('success', 'Tin tức / sự kiện được tạo thành công');
        } catch (Throwable $e) {
            Log::error('Create news failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tạo tin tức thất bại, vui lòng thử lại');
        }
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $this->validatedData($request, $news->id);

        try {
            if ($request->hasFile('banner')) {
                if ($news->banner) {
                    Storage::disk('public')->delete($news->banner);
                }
                $data['banner'] = $request->file('banner')->store('news', 'public');
            }

            // Clean up orphaned images from editor if description changed
            if (isset($data['description']) && $data['description'] !== $news->description) {
                $this->deleteOrphanedImages($news->description, $data['description']);
            }

            $news->update($data);
            return redirect()->route('admin.news.index')
                ->with('success', 'Tin tức / sự kiện được cập nhật thành công');
        } catch (Throwable $e) {
            Log::error('Update news failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cập nhật tin tức thất bại, vui lòng thử lại');
        }
    }

    public function destroy(News $news)
    {
        try {
            if ($news->banner) {
                Storage::disk('public')->delete($news->banner);
            }

            // Delete all images in editor description
            if ($news->description) {
                $this->deleteOrphanedImages($news->description, null);
            }

            $news->delete();
            return redirect()->route('admin.news.index')
                ->with('success', 'Tin tức / sự kiện đã bị xóa');
        } catch (Throwable $e) {
            Log::error('Delete news failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Xóa tin tức thất bại, vui lòng thử lại');
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);
        $path = $request->file('file')->store('news', 'public');
        // Log::info($path);
        return response()->json(['location' => asset('storage/' . $path)]);
    }

    private function extractImagePathsFromHtml($html)
    {
        if (!$html) {
            return [];
        }

        $paths = [];
        preg_match_all('/src=["\']([^"\'\/]+\/news\/[^"\'\/]+\.[a-zA-Z0-9]+)["\']/', $html, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                if (strpos($match, 'storage/') !== false) {
                    $path = str_replace('storage/', '', $match);
                } else {
                    $path = $match;
                }
                if (strpos($path, 'news/') === 0) {
                    $paths[] = $path;
                }
            }
        }

        return array_unique($paths);
    }

    private function deleteOrphanedImages($oldHtml, $newHtml)
    {
        $oldImages = $this->extractImagePathsFromHtml($oldHtml);
        $newImages = $this->extractImagePathsFromHtml($newHtml);
        // Log::info($oldHtml);
        // Log::info($newHtml);
        $orphaned = array_diff($oldImages, $newImages);

        foreach ($orphaned as $imagePath) {
            try {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    Log::info('Deleted orphaned image: ' . $imagePath);
                }
            } catch (Throwable $e) {
                Log::warning('Failed to delete orphaned image: ' . $imagePath, ['error' => $e->getMessage()]);
            }
        }
    }

    private function validatedData(Request $request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'banner' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'time_start' => 'nullable|date',
            'status' => 'required',
        ];

        $messages = [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'banner.image' => 'Banner phải là hình ảnh.',
            'banner.max' => 'Banner không được vượt quá 2MB.',
        ];

        return $request->validate($rules, $messages);
    }
}
