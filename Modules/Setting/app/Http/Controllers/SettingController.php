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
            'translations.site_name.*' => 'nullable|string|max:255',
            'site_email' => 'nullable|email',
            'translations.site_description.*' => 'nullable|string',
            'translations.about_name.*' => 'nullable|string|max:255',
            'translations.about_description.*' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'about_image' => 'nullable|image|mimes:ico,png|max:1024',
            'translations.footer_text.*' => 'nullable|string',
            'social_github' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_tiktok' => 'nullable|url',
            'translations.location.*' => 'nullable|string',
            'site_phone' => 'nullable|string|max:20',
            'hero_image' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Handle file uploads
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

        if ($request->hasFile('hero_image')) {
            if (settings()->get('hero_image')) {
                $this->deleteFile(settings()->get('hero_image'));
            }
            $hero_image = $this->uploadFile($request->file('hero_image'));
            settings()->set(['hero_image' => $hero_image]);
        }

        // Handle non-translatable settings
        $nonTranslatableSettings = [
            'site_email',
            'site_phone',
            'social_github',
            'social_twitter',
            'social_linkedin',
            'social_instagram',
            'social_tiktok',
        ];

        foreach ($nonTranslatableSettings as $setting) {
            if ($request->has($setting)) {
                settings()->set([$setting => $request->input($setting)]);
            }
        }

        // Handle translatable settings
        if ($request->has('translations')) {
            foreach ($request->input('translations') as $setting => $translations) {
                // Convert translations array to JSON
                settings()->set([$setting => json_encode($translations)]);
            }
        }

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
