<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Payment;

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
        } elseif ($user->isAdmin()) {
            // Show only hotels associated with this admin
            $query->where('admin_id', $user->id);
        } // Super admin sees all records
        
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
        $user = Auth::user();
        
        // Only admin or super admin can create hotels
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to create hotels.');
        }
        
        // Filter managers based on user type
        if ($user->isSuperAdmin()) {
            // Super admin can see all hotel managers
            $managers = Admin::where('user_type', 'hotel_manager')->pluck('name', 'id');
        } else {
            // Regular admin can only see hotel managers they created
            $managers = Admin::where('user_type', 'hotel_manager')
                ->where('created_by', $user->id)
                ->pluck('name', 'id');
        }
        
        return view('booking::hotels.create', compact('managers'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only admin or super admin can create hotels
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to create hotels.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:admins,id',
        ]);
        
        // Always set admin_id to the current user if they're an admin
        // For super admin, if they don't specify an admin, use their ID
        $validated['admin_id'] = $user->id;
        
        // Verify the manager belongs to this admin if not super admin
        if (!$user->isSuperAdmin() && !empty($validated['manager_id'])) {
            $manager = Admin::findOrFail($validated['manager_id']);
            if ($manager->created_by != $user->id) {
                return redirect()->route('hotels.create')
                    ->with('error', 'You can only assign managers that you have created.')
                    ->withInput();
            }
        }
        
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
    if (!$user->isSuperAdmin() && 
        !($user->isAdmin() && $hotel->admin_id == $user->id) && 
        !($user->isHotelManager() && $hotel->manager_id == $user->id)) {
        return redirect()->route('hotels.index')
            ->with('error', 'You do not have permission to view this hotel.');
    }
    
    // Load relationships and stats
    $hotel->load('manager');
    
    // Filter bookings by admin_id if the user is an admin
    $bookingsQuery = Booking::where('hotel_id', $hotel->id);
    
    if ($user->isAdmin()) {
        $bookingsQuery->where('admin_id', $user->id);
    }
    
    $bookingsCount = $bookingsQuery->count();
    $activeBookingsCount = $bookingsQuery->whereIn('status', ['pending', 'confirmed'])->count();
    
    $totalRevenue = $bookingsQuery
        ->where('status', '!=', 'cancelled')
        ->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
    
    // Calculate hotel remaining balance
    $hotelPayments = Payment::where('payment_type', 'admin_to_hotel')
        ->whereHas('booking', function($query) use ($hotel) {
            $query->where('hotel_id', $hotel->id);
        })
        ->sum('amount');
    
    $hotelRemainingBalance = $totalRevenue - $hotelPayments;
    
    $recentBookings = $bookingsQuery->with(['roomType'])
        ->latest()->take(5)->get();
    
    // Optional: Monthly performance data for chart (if needed)
    $monthlyPerformance = $this->getHotelMonthlyPerformance($hotel->id);
    
    return view('booking::hotels.show', compact(
        'hotel', 
        'bookingsCount', 
        'activeBookingsCount', 
        'totalRevenue', 
        'hotelRemainingBalance',
        'recentBookings',
        'monthlyPerformance'
    ));
}

/**
 * Get hotel monthly performance data for charts
 */
private function getHotelMonthlyPerformance($hotelId)
{
    $performanceData = [];
    
    // Get data for the last 12 months
    for ($i = 11; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $month = $date->format('M Y');
        
        $bookingsCount = Booking::where('hotel_id', $hotelId)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
        $revenue = Booking::where('hotel_id', $hotelId)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
        
        $performanceData[] = [
            'month' => $month,
            'bookings_count' => $bookingsCount,
            'revenue' => $revenue
        ];
    }
    
    return $performanceData;
}

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $hotel->admin_id == $user->id)) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to edit this hotel.');
        }
        
        // Filter managers based on user type
        if ($user->isSuperAdmin()) {
            // Super admin can see all hotel managers
            $managers = Admin::where('user_type', 'hotel_manager')->pluck('name', 'id');
        } else {
            // Regular admin can only see hotel managers they created
            $managers = Admin::where('user_type', 'hotel_manager')
                ->where('created_by', $user->id)
                ->pluck('name', 'id');
        }
        
        return view('booking::hotels.edit', compact('hotel', 'managers'));
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $hotel->admin_id == $user->id)) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to update this hotel.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:admins,id',
        ]);
        
        // For regular admin, keep the admin_id the same
        // For super admin, allow them to change the admin_id if needed
        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
            
            // Verify the manager belongs to this admin
            if (!empty($validated['manager_id'])) {
                $manager = Admin::findOrFail($validated['manager_id']);
                if ($manager->created_by != $user->id) {
                    return redirect()->route('hotels.edit', $hotel->id)
                        ->with('error', 'You can only assign managers that you have created.')
                        ->withInput();
                }
            }
        }
        
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
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $hotel->admin_id == $user->id)) {
            return redirect()->route('hotels.index')
                ->with('error', 'You do not have permission to delete this hotel.');
        }
        
        // Check if hotel has bookings
        $bookingsQuery = Booking::where('hotel_id', $hotel->id);
        
        if ($user->isAdmin()) {
            $bookingsQuery->where('admin_id', $user->id);
        }
        
        if ($bookingsQuery->exists()) {
            return redirect()->route('hotels.index')
                ->with('error', 'Cannot delete hotel with existing bookings.');
        }
        
        $hotel->delete();
        
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }

    public function processPayment(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'booking_id' => 'nullable|exists:bookings,id',
            'notes' => 'nullable|string',
        ]);

        $currentUser = Auth::user();
        $hotel = Hotel::findOrFail($validated['hotel_id']);

        // Check if user has permission to make payments for this hotel
        if (!$currentUser->isSuperAdmin() && $hotel->admin_id != $currentUser->id) {
            return back()->with('error', 'You do not have permission to make payments for this hotel.');
        }

        // Create payment record
        $payment = new Payment();
        $payment->amount = $validated['amount'];
        $payment->payment_type = 'admin_to_hotel';
        $payment->payment_date = now();
        $payment->payment_method = $validated['payment_method'];
        $payment->notes = $validated['notes'] ?? 'Payment to hotel';
        $payment->admin_id = $currentUser->id;
        
        // Attach to booking if provided
        if (!empty($validated['booking_id'])) {
            $booking = Booking::findOrFail($validated['booking_id']);
            
            // Check if booking belongs to the hotel
            if ($booking->hotel_id != $hotel->id) {
                return back()->with('error', 'The selected booking does not belong to this hotel.');
            }
            
            $payment->booking_id = $booking->id;
        }
        
        $payment->save();

        // If the payment is associated with a specific hotel manager user
        if ($hotel->manager_id) {
            $url = route('admins.show', $hotel->manager_id);
            return redirect($url)->with('success', 'Payment of $' . number_format($payment->amount, 2) . ' to ' . $hotel->name . ' has been recorded successfully.');
        }

        // Otherwise redirect to the hotel show page (if it exists) or to hotels index
        if (route('hotels.show', $hotel->id, false)) {
            return redirect()->route('hotels.show', $hotel->id)->with('success', 'Payment of $' . number_format($payment->amount, 2) . ' to ' . $hotel->name . ' has been recorded successfully.');
        }
        
        return redirect()->route('hotels.index')->with('success', 'Payment of $' . number_format($payment->amount, 2) . ' to ' . $hotel->name . ' has been recorded successfully.');
    }
}