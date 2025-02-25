<?php

namespace Modules\Cms\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Basic\Models\Category;
use Modules\Cms\Http\Requests\Blog\BlogRequest;
use Modules\Cms\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(2);
        return view('cms::blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('cms::blogs.create', ['categories' => $categories]);
    }

    public function store(BlogRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('blogs', 'public');
            }

            if ($data['status'] === 'published' && !isset($data['published_at'])) {
                $data['published_at'] = now();
            }

            $data['featured'] = $request->has('featured');

            Blog::create($data);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Blog $blog)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('cms::blogs.edit', compact('blog', 'categories'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                    Storage::disk('public')->delete($blog->image);
                }
                $data['image'] = $request->file('image')->store('blogs', 'public');
            }

            $data['featured'] = $request->has('featured');

            if ($data['status'] === 'published' && !$blog->published_at) {
                $data['published_at'] = now();
            }

            $blog->update($data);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $blog->delete();

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting blog post: ' . $e->getMessage());
        }
    }

    public function toggleFeatured(Blog $blog)
    {
        $blog->update(['featured' => !$blog->featured]);
        return back()->with('success', 'Blog featured status updated.');
    }
}
