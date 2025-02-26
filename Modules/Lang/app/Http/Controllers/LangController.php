<?php

namespace Modules\Lang\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

use Barryvdh\TranslationManager\Manager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Basic\Services\FileUploadService;
use Modules\Lang\Models\Lang;

class LangController extends Controller
{
    protected $manager;
    private FileUploadService $fileUploadService;

    public function __construct(Manager $manager,  FileUploadService $fileUploadService)
    {
        $this->manager = $manager;
        $this->fileUploadService = $fileUploadService;
    }

    public function getIndex($group = null)
    {
        $languages = Lang::latest()->paginate(10);
        $locales = $this->manager->getLocales();
        $groups = Translation::groupBy('group');
        $excludedGroups = $this->manager->getConfig('exclude_groups');
        if($excludedGroups){
            $groups->whereNotIn('group', $excludedGroups);
        }

        $groups = $groups->select('group')->orderBy('group')->get()->pluck('group', 'group');
        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }
        $groups = [''=>'Choose a group'] + $groups;
        $numChanged = Translation::where('group', $group)->where('status', Translation::STATUS_CHANGED)->count();


        $allTranslations = Translation::where('group', $group)->orderBy('key', 'asc')->get();
        $numTranslations = count($allTranslations);
        $translations = [];
        foreach($allTranslations as $translation){
            $translations[$translation->key][$translation->locale] = $translation;
        }

         return view('lang::languages.translations')
            ->with('translations', $translations)
            ->with('locales', $locales)
            ->with('groups', $groups)
            ->with('group', $group)
            ->with('numTranslations', $numTranslations)
            ->with('numChanged', $numChanged)
            ->with('editUrl', $group ? route('post.edit', [$group]) : null)
            ->with('deleteEnabled', $this->manager->getConfig('delete_enabled'));
    }

    public function index($group = null)
    {
        $languages = Lang::latest()->paginate(1);
       

         return view('lang::languages.index', compact('languages'));
    }

    public function create()
    {        
        return view('lang::languages.create');
    }

    public function edit(Lang $language)
    {        
        return view('lang::languages.edit', ['language' => $language]);
    }
    public function getView($group = null)
    {
        return $this->getIndex($group);
    }

    protected function loadLocales()
    {
        //Set the default locale as the first one.
        $locales = Translation::groupBy('locale')
            ->select('locale')
            ->get()
            ->pluck('locale');

        if ($locales instanceof Collection) {
            $locales = $locales->all();
        }
        $locales = array_merge([config('app.locale')], $locales);
        return array_unique($locales);
    }

    public function postAdd($group = null)
    {
        $keys = explode("\n", request()->get('keys'));

        foreach($keys as $key){
            $key = trim($key);
            if($group && $key){
                $this->manager->missingKey('*', $group, $key);
            }
        }
        return redirect()->back();
    }

    public function postEdit($group = null)
    {
        if(!in_array($group, $this->manager->getConfig('exclude_groups'))) {
            $name = request()->get('name');
            $value = request()->get('value');

            list($locale, $key) = explode('|', $name, 2);
            $translation = Translation::firstOrNew([
                'locale' => $locale,
                'group' => $group,
                'key' => $key,
            ]);
            $translation->value = (string) $value ?: null;
            $translation->status = Translation::STATUS_CHANGED;
            $translation->save();
            return array('status' => 'ok');
        }
    }

    public function postDelete($group, $key)
    {
        if(!in_array($group, $this->manager->getConfig('exclude_groups')) && $this->manager->getConfig('delete_enabled')) {
            Translation::where('group', $group)->where('key', $key)->delete();
            return ['status' => 'ok'];
        }
    }

    public function postImport(Request $request)
    {
        $replace = $request->get('replace', false);
        $counter = $this->manager->importTranslations($replace);

        return ['status' => 'ok', 'counter' => $counter];
    }

    public function postFind()
    {
        $numFound = $this->manager->findTranslations();

        return ['status' => 'ok', 'counter' => (int) $numFound];
    }

    
    public function postPublish($group = null)
    {
         $json = false;

        if($group === '_json'){
            $json = true;
        }

        $this->manager->exportTranslations($group, $json);

        return ['status' => 'ok'];
    }

    public function postAddGroup(Request $request)
    {
        $group = str_replace(".", '', $request->input('new-group'));
        if ($group)
        {
            return redirect()->route('get.view', $group);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function postAddLocale(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:langs,code',
            'icon' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $locales = $this->manager->getLocales();
        $newLocale = str_replace([], '-', trim($request->input('code')));
        if (!$newLocale || in_array($newLocale, $locales)) {
            return redirect()->back();
        }

        $language = new Lang();
        $language->name = $request->name;
 
        $language->code = $request->code;
        if($request->status) {
            $language->status = $request->status;
        } else {
            $language->status = 0;
        }
        
        if ($request->hasFile('icon')) {
            $language->icon = $this->fileUploadService->uploadFile($request->file('icon'), 'languages');            
        }

        if($language->save()){
            $this->manager->addLocale($newLocale);
        }
        return redirect()->back();
    }

    public function postRemoveLocale(Request $request)
    {
        $language = Lang::findOrFail($request->lang_id);
        if($language->delete()) {
            $path = resource_path('lang') . '\\' . $language->code . '.json';
        
            if (File::exists($path)) {
                File::delete($path);
            }

            $directory = resource_path('lang') . '\\' . $language->code;

            if(File::isDirectory($directory)) {
                File::deleteDirectory($directory);
            }

        }
        return back()->with('success',__('Language Removed'));
    }

    public function postTranslateMissing(Request $request){
        $locales = $this->manager->getLocales();
        $newLocale = str_replace([], '-', trim($request->input('new-locale')));
        if($request->has('with-translations') && $request->has('base-locale') && in_array($request->input('base-locale'),$locales) && $request->has('file') && in_array($newLocale, $locales)){
            $base_locale = $request->get('base-locale');
            $group = $request->get('file');
            $base_strings = Translation::where('group', $group)->where('locale', $base_locale)->get();
            foreach ($base_strings as $base_string) {
                $base_query = Translation::where('group', $group)->where('locale', $newLocale)->where('key', $base_string->key);
                if ($base_query->exists() && $base_query->whereNotNull('value')->exists()) {
                    // Translation already exists. Skip
                    continue;
                }
                $translated_text = Str::apiTranslateWithAttributes($base_string->value, $newLocale, $base_locale);
                request()->replace([
                    'value' => $translated_text,
                    'name' => $newLocale . '|' . $base_string->key,
                ]);
                app()->call(
                    route('post.edit'),
                    [
                        'group' => $group
                    ]
                );
            }
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function store(Request $request)
{
    try {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:langs,code',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|boolean',
            'rtl' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create new language instance
        $language = new Lang();
        $language->name = $request->name;
        $language->code = strtolower($request->code); // Ensure lowercase code
        $language->status = $request->has('status') ? 1 : 0;
        $language->rtl = $request->has('rtl') ? 1 : 0;

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $language->icon = $this->fileUploadService->uploadFile($request->file('icon'), 'languages');            
        }
        
        // Create language files and directories
        $jsonPath = resource_path("lang/{$language->code}.json");
        $langDirectory = resource_path("lang/{$language->code}");
        
        // Create JSON language file if it doesn't exist
        if (!File::exists($jsonPath)) {
            File::put($jsonPath, '{}');
        }
        
        // Create language directory if it doesn't exist
        if (!File::isDirectory($langDirectory)) {
            File::makeDirectory($langDirectory, 0755, true, true);
        }
        
        // Copy default validation files to the new language
        if (File::isDirectory(resource_path('lang/en'))) {
            File::copyDirectory(resource_path('lang/en'), $langDirectory);
        }

        // Save language record
        if ($language->save()) {
            return redirect()->route('admin.languages.index')
                ->with('success', "Language '{$language->name}' has been created successfully.");
        }
        
        return back()->with('error', 'Failed to create language.')->withInput();
        
    } catch (\Exception $e) {
        Log::error('Language creation failed: ' . $e->getMessage());
        return back()->with('error', 'An error occurred while creating the language. Please try again.')->withInput();
    }
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lang $lang)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => [
        'required',
        'string',
        'max:10',
        Rule::unique('langs', 'code')->ignore($lang->id)
    ],
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'nullable|boolean',
                'rtl' => 'nullable|boolean',
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Update language data
            $lang->name = $request->name;
            $lang->code = strtolower($request->code); // Ensure lowercase code
            $lang->status = $request->has('status') ? 1 : 0;
            $lang->rtl = $request->has('rtl') ? 1 : 0;
    
            // Handle icon upload
            if ($request->hasFile('image')) {
                if ($lang->image && Storage::disk('public')->exists($lang->image)) {
                    Storage::disk('public')->delete($lang->image);
                }
                $lang->image = $request->file('image')->store('languages', 'public');
            }
            
            if($request->status)
            {
                $lang->status = $request->status;
            } else {
                $lang->status = 0;
            }

            // Save language record
            if ($lang->save()) {
                return redirect()->route('admin.languages.index')
                    ->with('success', "Language '{$lang->name}' has been updated successfully.");
            }
            
            return back()->with('error', 'Failed to update language.')->withInput();
            
        } catch (\Exception $e) {
            Log::error('Language update failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the language. Please try again.')->withInput();
        }
    }

    public function destroy(Lang $language)
    {
        try {
            if ($language->image && Storage::disk('public')->exists($language->image)) {
                Storage::disk('public')->delete($language->image);
            }

            $language->delete();

            return redirect()->route('admin.languages.index')
                ->with('success', 'lang deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting blog post: ' . $e->getMessage());
        }
    }

    public function switchLang(Request $request)
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:' . implode(',', Config::get('app.available_locales', ['en']))]
        ]);

        Session::put('locale', $validated['locale']);
        App::setLocale($validated['locale']); 

        logger('Current Locale: ' . Session::get('locale'));

        return redirect()->back();
    }
}
