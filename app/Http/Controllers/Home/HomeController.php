<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Cms\Models\Blog;
use Modules\Cms\Models\Page;
use Modules\Cms\Models\Project;

class HomeController extends Controller
{
    public function home()
    {
        $projects = Project::where('is_featured', true)->latest()->limit(3)->get();
        $blogs = Blog::where('featured', true)->latest()->limit(3)->get();
        return view('front.home', ['projects' => $projects, 'blogs' => $blogs]);
    }

    public function about()
    {
        $about_page = Page::where('slug', 'about')->first();
        return view('front.about', ['about_page' => $about_page]);
    }

    public function projects()
    {
        $projects = Project::where('is_featured', true)->latest()->get();
        return view('front.projects', ['projects' => $projects]);
    }

    public function blogs()
    {
        $blogs = Blog::where('featured', true)->latest()->get();
        return view('front.blog.blogs', ['blogs' => $blogs]);
    }

    public function blogDetails(Blog $blog)
    {
        return view('front.blog.blog-details', ['blog' => $blog]);
    }

    public function contact()
    {
        return view('contact::contact');
    }
}
