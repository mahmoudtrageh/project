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
        // Only admin can view all booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view booking sources.');
        }

        $query = BookingSource::query();
        
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
        // Only admin can create booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('booking-sources.index')
                ->with('error', 'You do not have permission to create booking sources.');
        }
        
        return view('booking::booking-sources.create');
    }

    /**
     * Store a newly created booking source in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to create booking sources.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:booking_sources',
            'description' => 'nullable|string',
        ]);
        
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
        // Only admin can edit booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to edit booking sources.');
        }
        
        return view('booking::booking-sources.edit', compact('bookingSource'));
    }

    /**
     * Update the specified booking source in storage.
     */
    public function update(Request $request, BookingSource $bookingSource)
    {
        // Only admin can update booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to update booking sources.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:booking_sources,name,' . $bookingSource->id,
            'description' => 'nullable|string',
        ]);
        
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
        // Only admin can delete booking sources
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('booking-source.index')
                ->with('error', 'You do not have permission to delete booking sources.');
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
