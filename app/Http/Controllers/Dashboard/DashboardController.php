<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Cms\Models\Blog;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $blogs = Blog::where('status', 'published')
                            ->whereNotNull('published_at')
                            ->orderBy('published_at', 'desc')
                            ->inRandomOrder()
                            ->limit(10)
                            ->paginate(5);

        return view('admin.dashboard', ['blogs' => $blogs]);
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('editors', 'public');
        }

        $url = asset('storage/' . $path);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }
}
