<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting::index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_description' => 'nullable|string',
            'about_name' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'footer_text' => 'nullable|string',
            'social_github' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'location' => 'nullable|string',
            'site_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('logo')) {
            if (settings()->get('logo')) {
                $this->deleteFile(settings()->get('logo'));
            }
            $logo = $this->uploadFile($request->file('logo'));

            settings()->set(['logo' => $logo]);
        }

        if ($request->hasFile('favicon')) {
            if (settings()->get('favicon')) {
                $this->deleteFile(settings()->get('favicon'));
            }
            $favicon = $this->uploadFile($request->file('favicon'));

            settings()->set(['favicon' => $favicon]);
        }

        if ($request->hasFile('about_image')) {
            if (settings()->get('about_image')) {
                $this->deleteFile(settings()->get('about_image'));
            }
            $about_image = $this->uploadFile($request->file('about_image'));

            settings()->set(['about_image' => $about_image]);
        }

        // Update text settings
        settings()->set([
            'site_name' => $request->site_name,
            'site_email' => $request->site_email,
            'site_description' => $request->site_description,
            'about_name' => $request->about_name,
            'about_description' => $request->about_description,
            'footer_text' => $request->footer_text,
            'social_github' => $request->social_github,
            'social_twitter' => $request->social_twitter,
            'social_linkedin' => $request->social_linkedin,
            'location' => $request->location,
            'site_phone' => $request->site_phone,
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    protected function uploadFile($file)
    {
        // Generate a unique file name
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Store in the public disk under categories folder
        $path = $file->storeAs('settings', $fileName, 'public');
        
        return $path;
    }

    protected function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
