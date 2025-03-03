<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingSource;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Models\Payment;
use Modules\Booking\Models\RoomType;

class BookingController extends Controller
{
/**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Booking::with(['hotel', 'roomType', 'bookingSource', 'marketer.user']);
        
        // Filter by user type
        if ($user->isMarketer()) {
            $query->where('marketer_id', $user->marketerProfile->id);
        } elseif ($user->isHotelManager()) {
            $hotelIds = $user->managedHotels->pluck('id')->toArray();
            $query->whereIn('hotel_id', $hotelIds);
        }
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('hotel_id') && $request->hotel_id != '') {
            $query->where('hotel_id', $request->hotel_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('enter_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('leave_date', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }
        
        $bookings = $query->latest()->paginate(15);
        
        // Get data for filters
        $hotels = Hotel::pluck('name', 'id');
        
        return view('booking::bookings.index', compact('bookings', 'hotels'));
    }

 /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && !$user->isMarketer()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to create bookings.');
        }
        
        $hotels = Hotel::pluck('name', 'id');
        $roomTypes = RoomType::pluck('name', 'id');
        $bookingSources = BookingSource::pluck('name', 'id');
        $marketers = Marketer::with('user')->where('active', true)->get()
            ->pluck('user.name', 'id');
        
        return view('booking::bookings.create', compact('hotels', 'roomTypes', 'bookingSources', 'marketers'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && !$user->isMarketer()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to create bookings.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'enter_date' => 'required|date',
            'leave_date' => 'required|date|after:enter_date',
            'client_sell_price' => 'required|numeric|min:0',
            'marketer_sell_price' => 'required|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'room_type_id' => 'nullable|exists:room_types,id',
            'rooms_number' => 'required|integer|min:1',
            'booking_source_id' => 'nullable|exists:booking_sources,id',
            'marketer_id' => 'nullable|exists:marketers,id',
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
        ]);
        
        // Set marketer ID automatically if user is a marketer
        if ($user->isMarketer() && empty($validated['marketer_id'])) {
            $validated['marketer_id'] = $user->marketerProfile->id;
        }
        
        // Create the booking
        $booking = Booking::create($validated);
        
        // Record the deposit as a payment if it exists
        if (!empty($validated['deposit']) && $validated['deposit'] > 0) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $validated['deposit'],
                'payment_type' => 'client_to_admin',
                'payment_date' => now(),
                'payment_method' => 'Deposit',
                'notes' => 'Initial deposit',
            ]);
        }
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && 
            !($user->isMarketer() && $booking->marketer_id == $user->marketerProfile->id) && 
            !($user->isHotelManager() && $user->managedHotels->contains('id', $booking->hotel_id))) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to view this booking.');
        }
        
        // Load relationships
        $booking->load(['hotel', 'roomType', 'bookingSource', 'marketer.user', 'payments']);
        
        // Calculate financial data
        $nightsCount = $booking->nights_count;
        $totalClientPrice = $booking->total_client_price;
        $totalMarketerPrice = $booking->total_marketer_price;
        $totalBuyingPrice = $booking->total_buying_price;
        $marketerProfit = $booking->marketer_profit;
        $adminProfit = $booking->admin_profit;
        $clientRemainingBalance = $booking->client_remaining_balance;
        $hotelRemainingBalance = $booking->hotel_remaining_balance;
        $marketerRemainingBalance = $booking->marketer_remaining_balance;
        
        return view('booking::bookings.show', compact(
            'booking',
            'nightsCount',
            'totalClientPrice',
            'totalMarketerPrice',
            'totalBuyingPrice',
            'marketerProfit',
            'adminProfit',
            'clientRemainingBalance',
            'hotelRemainingBalance',
            'marketerRemainingBalance'
        ));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && 
            !($user->isMarketer() && $booking->marketer_id == $user->marketerProfile->id)) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to edit this booking.');
        }
        
        $hotels = Hotel::pluck('name', 'id');
        $roomTypes = RoomType::pluck('name', 'id');
        $bookingSources = BookingSource::pluck('name', 'id');
        $marketers = Marketer::with('user')->where('active', true)->get()
            ->pluck('user.name', 'id');
        
        return view('booking::bookings.edit', compact('booking', 'hotels', 'roomTypes', 'bookingSources', 'marketers'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && 
            !($user->isMarketer() && $booking->marketer_id == $user->marketerProfile->id)) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to edit this booking.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'enter_date' => 'required|date',
            'leave_date' => 'required|date|after:enter_date',
            'client_sell_price' => 'required|numeric|min:0',
            'marketer_sell_price' => 'required|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'room_type_id' => 'nullable|exists:room_types,id',
            'rooms_number' => 'required|integer|min:1',
            'booking_source_id' => 'nullable|exists:booking_sources,id',
            'marketer_id' => 'nullable|exists:marketers,id',
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
        ]);
        
        // Update the booking
        $booking->update($validated);
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        $user = Auth::user();
        
        // Only admin can delete bookings
        if (!$user->isAdmin()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to delete bookings.');
        }
        
        $booking->delete();
        
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
