<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Contacts retrieved successfully',
            'data' => ContactResource::collection($contacts),
            'meta' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'per_page' => $contacts->perPage(),
                'total' => $contacts->total(),
                'from' => $contacts->firstItem(),
                'to' => $contacts->lastItem(),
            ],
            'links' => [
                'first' => $contacts->url(1),
                'last' => $contacts->url($contacts->lastPage()),
                'prev' => $contacts->previousPageUrl(),
                'next' => $contacts->nextPageUrl(),
            ],
        ]);
    }
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());
        return response()->json([
            'success' => true,
            'data' => $contact,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'success' => true,
            'data' => $contact,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => $contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
