<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\RoomType;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the room types.
     */
    public function index(Request $request)
    {
        // Only admin can view all room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view room types.');
        }

        $query = RoomType::query();
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $roomTypes = $query->latest()->paginate(10);
        
        return view('booking::room-types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new room type.
     */
    public function create()
    {
        // Only admin can create room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('room-types.index')
                ->with('error', 'You do not have permission to create room types.');
        }
        
        return view('booking::room-types.create');
    }

    /**
     * Store a newly created room type in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('room-types.index')
                ->with('error', 'You do not have permission to create room types.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:room_types',
            'description' => 'nullable|string',
        ]);
        
        // Create the room type
        RoomType::create($validated);
        
        return redirect()->route('room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    /**
     * Show the form for editing the specified room type.
     */
    public function edit(RoomType $roomType)
    {
        // Only admin can edit room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('room-types.index')
                ->with('error', 'You do not have permission to edit room types.');
        }
        
        return view('booking::room-types.edit', compact('roomType'));
    }

    /**
     * Update the specified room type in storage.
     */
    public function update(Request $request, RoomType $roomType)
    {
        // Only admin can update room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('room-types.index')
                ->with('error', 'You do not have permission to update room types.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name,' . $roomType->id,
            'description' => 'nullable|string',
        ]);
        
        // Update the room type
        $roomType->update($validated);
        
        return redirect()->route('room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    /**
     * Remove the specified room type from storage.
     */
    public function destroy(RoomType $roomType)
    {
        // Only admin can delete room types
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('room-types.index')
                ->with('error', 'You do not have permission to delete room types.');
        }
        
        // Check if room type is associated with any bookings
        if ($roomType->bookings()->exists()) {
            return redirect()->route('room-types.index')
                ->with('error', 'Cannot delete room type that is associated with bookings.');
        }
        
        // Delete the room type
        $roomType->delete();
        
        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}
