<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingSource;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Models\Payment;
use Modules\Booking\Models\RoomType;
use PDF;
use Carbon\Carbon;

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
        $marketerProfile = Marketer::where('admin_id', $user->id)->first();
        if ($marketerProfile) {
            $query->where('marketer_id', $marketerProfile->id);
        }
    } elseif ($user->isHotelManager()) {
        $hotelIds = $user->managedHotels->pluck('id')->toArray();
        $query->whereIn('hotel_id', $hotelIds);
    } elseif ($user->isAdmin()) {
        // Show only bookings associated with this admin
        $query->where('admin_id', $user->id);
    } // Super admin sees all records
    
    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Apply hotel filter
    if ($request->filled('hotel_id')) {
        $query->where('hotel_id', $request->hotel_id);
    }
    
    // Apply room type filter
    if ($request->filled('room_type_id')) {
        $query->where('room_type_id', $request->room_type_id);
    }
    
    // Apply check-in date filter (enter_date)
    if ($request->filled('enter_date')) {
        $query->whereDate('enter_date', '>=', $request->enter_date);
    }
    
    // Apply check-out date filter (leave_date)
    if ($request->filled('leave_date')) {
        $query->whereDate('leave_date', '<=', $request->leave_date);
    }
    
    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('booking_number', 'like', "%{$search}%")
              ->orWhere('client_name', 'like', "%{$search}%")
              ->orWhere('client_phone', 'like', "%{$search}%")
              ->orWhere('note', 'like', "%{$search}%");
        });
    }
    
    // Order by created date (latest first)
    $bookings = $query->latest()->paginate(15)->withQueryString();
    
    // Get hotels for filter dropdown based on user role
    if ($user->isAdmin()) {
        $hotels = Hotel::where('admin_id', $user->id)->get();
    } elseif ($user->isSuperAdmin()) {
        $hotels = Hotel::all();
    } else {
        $hotels = Hotel::whereIn('id', $user->managedHotels->pluck('id')->toArray())->get();
    }
    
    // Get room types for filter dropdown
    $roomTypes = RoomType::all();
    
    return view('booking::bookings.index', compact('bookings', 'hotels', 'roomTypes'));
}

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isMarketer()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to create bookings.');
        }
        
        // Get only the hotels, room types, and booking sources associated with this admin
        if ($user->isAdmin()) {
            $hotels = Hotel::where('admin_id', $user->id)->pluck('name', 'id');
            $roomTypes = RoomType::pluck('name', 'id');
            $bookingSources = BookingSource::where('admin_id', $user->id)->pluck('name', 'id');
            
            // Get marketers created by this admin
            $adminMarketers = Admin::where('created_by', $user->id)
                ->where('user_type', 'marketer')
                ->get();
                
            // Map to marketer profiles
            $marketerIds = [];
            foreach ($adminMarketers as $adminMarketer) {
                $marketerProfile = Marketer::where('admin_id', $adminMarketer->id)->first();
                if ($marketerProfile) {
                    $marketerIds[$marketerProfile->id] = $adminMarketer->name;
                }
            }
            $marketers = collect($marketerIds);
            
        } else {
            // Super admin sees all records
            $hotels = Hotel::pluck('name', 'id');
            $roomTypes = RoomType::pluck('name', 'id');
            $bookingSources = BookingSource::pluck('name', 'id');
            
            // Get all marketers
            $marketers = Marketer::with('user')->get()->mapWithKeys(function ($marketer) {
                return [$marketer->id => $marketer->user->name ?? 'Unknown'];
            });
        }
        
        return view('booking::bookings.create', compact('hotels', 'roomTypes', 'bookingSources', 'marketers'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isMarketer()) {
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
            $marketerProfile = Marketer::where('admin_id', $user->id)->first();
            if ($marketerProfile) {
                $validated['marketer_id'] = $marketerProfile->id;
            }
        }
        
        // Set admin_id to the current admin's ID
        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
        } elseif ($user->isSuperAdmin()) {
            $validated['admin_id'] = $user->id;
        } elseif ($user->isMarketer()) {
            // If the user is a marketer, set admin_id to their creator's ID
            $validated['admin_id'] = $user->created_by ?? null;
        }
        
        // Check if the hotel belongs to this admin
        if ($user->isAdmin()) {
            $hotel = Hotel::findOrFail($validated['hotel_id']);
            if ($hotel->admin_id != $user->id) {
                return redirect()->route('bookings.index')
                    ->with('error', 'You do not have permission to create bookings for this hotel.');
            }
        }
        
        // Create the booking
        $booking = Booking::create($validated);
        
        // Record the deposit as a payment if it exists
        if (!empty($validated['deposit']) && $validated['deposit'] > 0) {
            $paymentData = [
                'booking_id' => $booking->id,
                'amount' => $validated['deposit'],
                'payment_type' => 'client_to_admin',
                'payment_date' => now(),
                'payment_method' => 'Deposit',
                'notes' => 'Initial deposit',
            ];
            
            // Set admin_id for the payment
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                $paymentData['admin_id'] = $user->id;
            } elseif ($user->isMarketer()) {
                $paymentData['admin_id'] = $user->created_by ?? null;
            }
            
            Payment::create($paymentData);
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
    if (!$user->isSuperAdmin()) {
        if ($user->isMarketer()) {
            $marketerProfile = Marketer::where('admin_id', $user->id)->first();
            if (!$marketerProfile || $booking->marketer_id != $marketerProfile->id) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to view this booking.');
            }
        } elseif ($user->isHotelManager()) {
            $hotelIds = $user->managedHotels->pluck('id')->toArray();
            if (!in_array($booking->hotel_id, $hotelIds)) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to view this booking.');
            }
        } elseif ($user->isAdmin()) {
            if ($booking->admin_id != $user->id) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to view this booking.');
            }
        }
    }
    
    // Load relationships
    $booking->load(['hotel', 'roomType', 'bookingSource', 'marketer.user']);
    
    // Get booking payments
    $payments = Payment::where('booking_id', $booking->id)
        ->orderBy('payment_date', 'desc')
        ->get();
    
    // Calculate financial metrics
    $nightsCount = $booking->nights_count ?? Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    $totalClientPrice = $booking->total_client_price ?? ($booking->client_sell_price * $booking->rooms_number * $nightsCount);
    $totalMarketerPrice = $booking->total_marketer_price ?? ($booking->marketer_sell_price * $booking->rooms_number * $nightsCount);
    $totalBuyingPrice = $booking->total_buying_price ?? ($booking->buying_price * $booking->rooms_number * $nightsCount);
    
    $marketerProfit = $booking->marketer_profit ?? ($booking->marketer_id ? ($booking->client_sell_price - $booking->marketer_sell_price) * $booking->rooms_number * $nightsCount : 0);
    $adminProfit = $booking->admin_profit ?? ($booking->marketer_id 
        ? ($booking->marketer_sell_price - $booking->buying_price) * $booking->rooms_number * $nightsCount
        : ($booking->client_sell_price - $booking->buying_price) * $booking->rooms_number * $nightsCount);
    
    // Calculate payment summaries
    $clientPayments = $payments->where('payment_type', 'client_to_admin')->sum('amount');
    $hotelPayments = $payments->where('payment_type', 'admin_to_hotel')->sum('amount');
    $marketerPayments = $payments->where('payment_type', 'admin_to_marketer')->sum('amount');
    
    $clientRemainingBalance = $totalClientPrice - $clientPayments;
    $hotelRemainingBalance = $totalBuyingPrice - $hotelPayments;
    $marketerRemainingBalance = $marketerProfit - $marketerPayments;
    
    // Get related entities
    $hotel = $booking->hotel;
    $roomType = $booking->roomType;
    $marketer = $booking->marketer;
    
    return view('booking::bookings.show', compact(
        'booking',
        'payments',
        'hotel',
        'roomType',
        'marketer',
        'adminProfit',
        'marketerProfit',
        'totalClientPrice',
        'totalMarketerPrice',
        'totalBuyingPrice',
        'clientPayments',
        'hotelPayments',
        'marketerPayments',
        'clientRemainingBalance',
        'hotelRemainingBalance',
        'marketerRemainingBalance',
        'nightsCount'
    ));
}

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        $user = Auth::user();
        
        // Get marketer ID if user is a marketer
        $marketerProfileId = null;
        if ($user->isMarketer()) {
            $marketerProfile = Marketer::where('admin_id', $user->id)->first();
            if ($marketerProfile) {
                $marketerProfileId = $marketerProfile->id;
            }
        }
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $booking->admin_id == $user->id) && 
            !($user->isMarketer() && $booking->marketer_id == $marketerProfileId)) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to edit this booking.');
        }
        
        // Get data for dropdowns
        if ($user->isAdmin()) {
            $hotels = Hotel::where('admin_id', $user->id)->pluck('name', 'id');
            $roomTypes = RoomType::pluck('name', 'id');
            $bookingSources = BookingSource::where('admin_id', $user->id)->pluck('name', 'id');
            
            // Get marketers created by this admin
            $adminMarketers = Admin::where('created_by', $user->id)
                ->where('user_type', 'marketer')
                ->get();
                
            // Map to marketer profiles
            $marketerIds = [];
            foreach ($adminMarketers as $adminMarketer) {
                $marketerProfile = Marketer::where('admin_id', $adminMarketer->id)->first();
                if ($marketerProfile) {
                    $marketerIds[$marketerProfile->id] = $adminMarketer->name;
                }
            }
            $marketers = collect($marketerIds);
            
        } else {
            $hotels = Hotel::pluck('name', 'id');
            $roomTypes = RoomType::pluck('name', 'id');
            $bookingSources = BookingSource::pluck('name', 'id');
            
            // Get all marketers
            $marketers = Marketer::with('user')->get()->mapWithKeys(function ($marketer) {
                return [$marketer->id => $marketer->user->name ?? 'Unknown'];
            });
        }
        
        return view('booking::bookings.edit', compact('booking', 'hotels', 'roomTypes', 'bookingSources', 'marketers'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Get marketer ID if user is a marketer
        $marketerProfileId = null;
        if ($user->isMarketer()) {
            $marketerProfile = Marketer::where('admin_id', $user->id)->first();
            if ($marketerProfile) {
                $marketerProfileId = $marketerProfile->id;
            }
        }
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $booking->admin_id == $user->id) && 
            !($user->isMarketer() && $booking->marketer_id == $marketerProfileId)) {
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
        
        // Check if the hotel belongs to this admin
        if ($user->isAdmin()) {
            $hotel = Hotel::findOrFail($validated['hotel_id']);
            if ($hotel->admin_id != $user->id) {
                return redirect()->route('bookings.index')
                    ->with('error', 'You do not have permission to assign bookings to this hotel.');
            }
        }
        
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
        
        // Check permissions - only admin or super admin can delete bookings
        if (!$user->isSuperAdmin() && !($user->isAdmin() && $booking->admin_id == $user->id)) {
            return redirect()->route('bookings.index')
                ->with('error', 'You do not have permission to delete bookings.');
        }
        
        $booking->delete();
        
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    /**
 * Export booking as PDF
 */
public function exportPdf(Booking $booking)
{
    $user = Auth::user();
    
    // Check permissions
    if (!$user->isSuperAdmin() && 
        !($user->isAdmin() && $booking->admin_id == $user->id) && 
        !($user->isMarketer() && $booking->marketer_id == $user->marketerProfile?->id) && 
        !($user->isHotelManager() && $user->managedHotels->contains('id', $booking->hotel_id))) {
        return redirect()->route('bookings.index')
            ->with('error', 'You do not have permission to view this booking.');
    }
    
    // Load relationships
    $booking->load(['hotel', 'roomType', 'bookingSource', 'marketer.user', 'payments']);
    
    // Calculate financial data
    $nightsCount = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    $totalClientPrice = $booking->client_sell_price * $booking->rooms_number * $nightsCount;
    $totalMarketerPrice = $booking->marketer_sell_price * $booking->rooms_number * $nightsCount;
    $totalBuyingPrice = $booking->buying_price * $booking->rooms_number * $nightsCount;
    
    $marketerProfit = $booking->marketer_id ? ($booking->client_sell_price - $booking->marketer_sell_price) * $booking->rooms_number * $nightsCount : 0;
    $adminProfit = $booking->marketer_id 
        ? ($booking->marketer_sell_price - $booking->buying_price) * $booking->rooms_number * $nightsCount
        : ($booking->client_sell_price - $booking->buying_price) * $booking->rooms_number * $nightsCount;
    
    // Calculate remaining balances
    $clientPayments = $booking->payments->where('payment_type', 'client_to_admin')->sum('amount');
    $hotelPayments = $booking->payments->where('payment_type', 'admin_to_hotel')->sum('amount');
    $marketerPayments = $booking->payments->where('payment_type', 'admin_to_marketer')->sum('amount');
    
    $clientRemainingBalance = $totalClientPrice - $clientPayments;
    $hotelRemainingBalance = $totalBuyingPrice - $hotelPayments;
    $marketerRemainingBalance = $marketerProfit - $marketerPayments;
    
    // Format the booking number
    $bookingNumber = $booking->booking_number ?? 'B-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
    
    // Generate PDF
    $pdf = PDF::loadView('booking::bookings.pdf', compact(
        'booking',
        'bookingNumber',
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
    
    // Set paper size to A4
    $pdf->setPaper('a4');
    
    return $pdf->download("booking-{$bookingNumber}.pdf");
}
}