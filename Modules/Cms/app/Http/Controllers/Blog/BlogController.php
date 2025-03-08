<?php

namespace Modules\Cms\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Basic\Models\Category;
use Modules\Cms\Http\Requests\Blog\BlogRequest;
use Modules\Cms\Models\Blog;
use Illuminate\Support\Str;
use Modules\Seo\Models\Seo;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
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

            if($request->has('slug'))
            {
                $data['slug'] = $request->slug;
            } else {
                $data['slug'] = Str::slug($request->title['en']);
            }

            $blog = Blog::create($data);

            // Handle SEO data if present
            if ($request->has('seo')) {
                $this->handleSeoData($blog, $request);
            }

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post created successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
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

            if($request->has('slug'))
            {
                $data['slug'] = $request->slug;
            } else {
                $data['slug'] = Str::slug($request->title['en']);
            }

            $blog->update($data);

             // Handle SEO data if present
             if ($request->has('seo')) {
                $this->handleSeoData($blog, $request);
            }

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

    protected function handleSeoData(Blog $blog, Request $request)
    {
        try {
        $seoData = $request->input('seo');
        
        // Process multilingual SEO data
        $localizedSeoData = [];
        foreach (config('app.available_locales') as $locale) {
            if (isset($seoData[$locale])) {
                $localizedSeoData[$locale] = $seoData[$locale];
            }
        }
        
        // Remove locale-specific data from main seo array
        foreach (config('app.available_locales') as $locale) {
            if (isset($seoData[$locale])) {
                unset($seoData[$locale]);
            }
        }
        
        // Default values for required fields
        foreach (config('app.available_locales') as $locale) {
            // Set defaults from blog content if SEO fields are empty
            if (empty($localizedSeoData[$locale]['title'] ?? null)) {
                $localizedSeoData[$locale]['title'] = $blog->getTranslation('title', $locale);
            }
            
            if (empty($localizedSeoData[$locale]['description'] ?? null)) {
                // Create a short description from content (first 160 chars)
                $content = $blog->getTranslation('content', $locale);
                $description = Str::limit(strip_tags($content), 160);
                $localizedSeoData[$locale]['description'] = $description;
            }
        }
        
        $seoData['canonical'] = route('blogs.show', $blog->slug);
                
        // Save or update SEO data
        if ($blog->seo) {
            // Update existing SEO record
            $blog->seo->update($seoData);
            
            // Update translations
            foreach ($localizedSeoData as $locale => $trans) {
                foreach ($trans as $key => $value) {
                    $blog->seo->setTranslation($key, $locale, $value);
                }
            }
            
            $blog->seo->save();
        } else {
            // Create new SEO record
            $seo = new Seo($seoData);
            
            // Set route/URI for the SEO record
            $seo->route = 'blog.show';
            $seo->uri = 'blog/' . $blog->slug;
            
            // Set translations
            foreach ($localizedSeoData as $locale => $trans) {
                foreach ($trans as $key => $value) {
                    $seo->setTranslation($key, $locale, $value);
                }
            }
            
            // Associate with blog
            $blog->seo()->save($seo);
        }

    } catch (\Exception $e) {
        dd($e->getMessage());
        return back()->with('error', 'Error updating blog post: ' . $e->getMessage())
            ->withInput();
    }
    }
}
