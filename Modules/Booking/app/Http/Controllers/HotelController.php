<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;

class HotelController extends Controller
{
     /**
     * Display a listing of the hotels.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Hotel::with('manager');
        
        // Filter by user type
        if ($user->isHotelManager()) {
            $query->where('manager_id', $user->id);
        }
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->latest()->paginate(15);
        
        return view('booking::hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create()
    {
        // Only admin can create hotels
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to create hotels.');
        }
        
        $managers = Admin::where('user_type', 'hotel_manager')->pluck('name', 'id');
        
        return view('booking::hotels.create', compact('managers'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create hotels
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to create hotels.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        
        // Create the hotel
        $hotel = Hotel::create($validated);
        
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && !($user->isHotelManager() && $hotel->manager_id == $user->id)) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to view this hotel.');
        }
        
        // Load relationships and stats
        $hotel->load('manager');
        
        $bookingsCount = Booking::where('hotel_id', $hotel->id)->count();
        $activeBookingsCount = Booking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        $totalRevenue = Booking::where('hotel_id', $hotel->id)
            ->where('status', '!=', 'cancelled')
            ->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
        
        $recentBookings = Booking::with(['roomType'])
            ->where('hotel_id', $hotel->id)
            ->latest()->take(5)->get();
        
        return view('booking::hotels.show', compact('hotel', 'bookingsCount', 'activeBookingsCount', 'totalRevenue', 'recentBookings'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel)
    {
        // Only admin can edit hotels
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to edit hotels.');
        }
        
        $managers = Admin::where('user_type', 'hotel_manager')->pluck('name', 'id');
        
        return view('booking::hotels.edit', compact('hotel', 'managers'));
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        // Only admin can update hotels
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to update hotels.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        
        // Update the hotel
        $hotel->update($validated);
        
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(Hotel $hotel)
    {
        // Only admin can delete hotels
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to delete hotels.');
        }
        
        // Check if hotel has bookings
        if ($hotel->bookings()->exists()) {
            return redirect()->route('hotels.index')
                ->with('error', 'Cannot delete hotel with existing bookings.');
        }
        
        $hotel->delete();
        
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }
}
