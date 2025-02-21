<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Contact\Http\Requests\StoreContactRequest;
use Modules\Contact\Http\Resources\ContactResource;
use Modules\Contact\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Modules\Contact\Models\Contact;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {}

    public function index(Request $request)
    {
        Contact::query()->where('status', 'new')->update(['status' => 'read']);

        $status = $request->get('status');
        $perPage = $request->get('per_page', 1);
        
        $contacts = $this->contactService->getAllContacts($perPage);
        
        return view('contact::admin.contacts', compact('contacts'));
    }

    public function store(StoreContactRequest $request)
    {
        $contact = $this->contactService->createContact($request->validated());
        
        return redirect()->route('contact')
            ->with('success', 'Contact created successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact::create');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('contact::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('contact::edit');
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
}
