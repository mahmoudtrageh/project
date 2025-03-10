<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\BookingSource;

class BookingSourceController extends Controller
{
    /**
     * Display a listing of the booking sources.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only admin or super admin can view booking sources
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view booking sources.');
        }

        $query = BookingSource::query();
        
        // Filter by admin_id if the user is an admin (not super admin)
        if ($user->isAdmin()) {
            $query->where('admin_id', $user->id);
        }
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $bookingSources = $query->latest()->paginate(10);
        
        return view('booking::booking-sources.index', compact('bookingSources'));
    }

    /**
     * Show the form for creating a new booking source.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only admin or super admin can create booking sources
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to create booking sources.');
        }
        
        return view('booking::booking-sources.create');
    }

    /**
     * Store a newly created booking source in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only admin or super admin can create booking sources
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to create booking sources.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        // Check for unique name per admin
        $exists = BookingSource::where('name', $validated['name']);
        
        if ($user->isAdmin()) {
            $exists->where('admin_id', $user->id);
        }
        
        if ($exists->exists()) {
            return redirect()->back()
                ->withErrors(['name' => 'This name already exists.'])
                ->withInput();
        }
        
        // Add admin_id for admin users
        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
        }
        
        // Create the booking source
        BookingSource::create($validated);
        
        return redirect()->route('booking-source.index')
            ->with('success', 'Booking source created successfully.');
    }

    /**
     * Show the form for editing the specified booking source.
     */
    public function edit(BookingSource $bookingSource)
    {
        $user = Auth::user();
        
        // Only admin who owns this booking source or super admin can edit
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $bookingSource->admin_id == $user->id)) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to edit this booking source.');
        }
        
        return view('booking::booking-sources.edit', compact('bookingSource'));
    }

    /**
     * Update the specified booking source in storage.
     */
    public function update(Request $request, BookingSource $bookingSource)
    {
        $user = Auth::user();
        
        // Only admin who owns this booking source or super admin can update
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $bookingSource->admin_id == $user->id)) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to update this booking source.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        // Check for unique name per admin
        $exists = BookingSource::where('name', $validated['name'])
            ->where('id', '!=', $bookingSource->id);
        
        if ($user->isAdmin()) {
            $exists->where('admin_id', $user->id);
        }
        
        if ($exists->exists()) {
            return redirect()->back()
                ->withErrors(['name' => 'This name already exists.'])
                ->withInput();
        }
        
        // Update the booking source
        $bookingSource->update($validated);
        
        return redirect()->route('booking-source.index')
            ->with('success', 'Booking source updated successfully.');
    }

    /**
     * Remove the specified booking source from storage.
     */
    public function destroy(BookingSource $bookingSource)
    {
        $user = Auth::user();
        
        // Only admin who owns this booking source or super admin can delete
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $bookingSource->admin_id == $user->id)) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to delete this booking source.');
        }
        
        // Check if booking source is associated with any bookings
        if ($bookingSource->bookings()->exists()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'Cannot delete booking source that is associated with bookings.');
        }
        
        // Delete the booking source
        $bookingSource->delete();
        
        return redirect()->route('booking-source.index')
            ->with('success', 'Booking source deleted successfully.');
    }
}