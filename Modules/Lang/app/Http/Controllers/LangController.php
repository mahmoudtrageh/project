<?php

namespace Modules\Lang\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lang::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lang::create');
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
        return view('lang::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('lang::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
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
