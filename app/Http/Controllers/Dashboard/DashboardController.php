<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Carbon\Carbon;
use Modules\Admin\Models\Admin;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Stats for dashboard based on user type
        $stats = [];
        $financialData = [];
        $monthlyData = [];
        $recentBookings = [];
        
        // Get current and previous month date ranges
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentYear = Carbon::now()->year;
        
        if ($user->isAdmin() || $user->isSuperAdmin()) {
           // Basic stats
$stats = [
    'total_bookings' => Booking::count(),
    'active_bookings' => Booking::whereIn('status', ['pending', 'confirmed'])->count(),
    'total_hotels' => Hotel::count(),
    'total_marketers' => $user->isAdmin() ? Admin::where('is_active', true)->where('created_by', $user->id)->count()
    : Admin::where('is_active', true)->count(),
    'recent_bookings' => Booking::with(['hotel', 'roomType', 'marketer.user'])
        ->latest()->take(5)->get(),
    'admin_profit' => Booking::where('status', '!=', 'cancelled')
        ->get()
        ->sum(function($booking) {
            // Fix: Calculate nights correctly by swapping the parameters
            $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
            
            return $booking->marketer_id 
                ? ($booking->marketer_sell_price * $booking->rooms_number * $nights) - 
                  ($booking->buying_price * $booking->rooms_number * $nights)
                : ($booking->client_sell_price * $booking->rooms_number * $nights) - 
                  ($booking->buying_price * $booking->rooms_number * $nights);
        }),
];
            
// Current month revenue and profit
$currentMonthBookings = Booking::where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
    ->get();
            
// Previous month revenue and profit
$previousMonthBookings = Booking::where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
    ->get();
            
// Calculate current month financials
$currentRevenue = $currentMonthBookings->sum('total_client_price');
$currentProfit = $currentMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters and storing the result
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    
    return $booking->marketer_id 
        ? ($booking->marketer_sell_price * $booking->rooms_number * $nights) - 
          ($booking->buying_price * $booking->rooms_number * $nights)
        : ($booking->client_sell_price * $booking->rooms_number * $nights) - 
          ($booking->buying_price * $booking->rooms_number * $nights);
});
            
// Calculate previous month financials
$previousRevenue = $previousMonthBookings->sum('total_client_price');
$previousProfit = $previousMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters and storing the result
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    
    return $booking->marketer_id 
        ? ($booking->marketer_sell_price * $booking->rooms_number * $nights) - 
          ($booking->buying_price * $booking->rooms_number * $nights)
        : ($booking->client_sell_price * $booking->rooms_number * $nights) - 
          ($booking->buying_price * $booking->rooms_number * $nights);
});
            
// Calculate profit margin
$currentMargin = $currentRevenue > 0 ? ($currentProfit / $currentRevenue) * 100 : 0;
$previousMargin = $previousRevenue > 0 ? ($previousProfit / $previousRevenue) * 100 : 0;
            
// Get outstanding payments
$outstandingAmount = Booking::where('status', '!=', 'cancelled')
    ->where(function($query) {
        $query->where('payment_status', '!=', 'paid')
              ->orWhereNull('payment_status');
    })->sum('total_client_price');
                
$previousOutstandingAmount = Booking::where('status', '!=', 'cancelled')
    ->where(function($query) {
        $query->where('payment_status', '!=', 'paid')
              ->orWhereNull('payment_status');
    })
    ->where('created_at', '<', $previousMonthEnd)
    ->sum('total_client_price');
            
// Calculate percentage changes
$revenueChange = $this->calculatePercentageChange($previousRevenue, $currentRevenue);
$profitChange = $this->calculatePercentageChange($previousProfit, $currentProfit);
$marginChange = $this->calculatePercentageChange($previousMargin, $currentMargin);
$outstandingChange = $this->calculatePercentageChange($previousOutstandingAmount, $outstandingAmount);
            
$financialData = [
    'total_revenue' => [
        'value' => $currentRevenue,
        'change' => $revenueChange,
        'change_type' => $revenueChange >= 0 ? 'increase' : 'decrease'
    ],
    'net_profit' => [
        'value' => $currentProfit,
        'change' => $profitChange,
        'change_type' => $profitChange >= 0 ? 'increase' : 'decrease'
    ],
    'profit_margin' => [
        'value' => $currentMargin,
        'change' => $marginChange,
        'change_type' => $marginChange >= 0 ? 'increase' : 'decrease'
    ],
    'outstanding_amount' => [
        'value' => $outstandingAmount,
        'change' => $outstandingChange,
        'change_type' => $outstandingChange >= 0 ? 'increase' : 'decrease'
    ]
];
            
// Get monthly data for charts
$monthlyData = $this->getMonthlyData();
$recentBookings = Booking::with(['hotel', 'roomType', 'marketer.user'])
    ->latest()->take(5)->get();
                
        } elseif ($user->isMarketer()) {
            $marketerId = $user->marketerProfile ? $user->marketerProfile->id : null;
            
// Basic stats with fixed profit calculation
$stats = [
    'total_bookings' => Booking::where('marketer_id', $marketerId)->count(),
    'active_bookings' => Booking::where('marketer_id', $marketerId)
        ->whereIn('status', ['pending', 'confirmed'])->count(),
    'marketer_profit' => Booking::where('marketer_id', $marketerId)
        ->where('status', '!=', 'cancelled')
        ->get()
        ->sum(function($booking) {
            // Fix: Calculate nights correctly by swapping the parameters (enter_date should be first)
            $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
            return ($booking->client_sell_price - $booking->marketer_sell_price) * $booking->rooms_number * $nights;
        }),
    'recent_bookings' => Booking::with(['hotel', 'roomType'])
        ->where('marketer_id', $marketerId)
        ->latest()->take(5)->get(),
];
            
// Current month revenue and profit for marketers
$currentMonthBookings = Booking::where('marketer_id', $marketerId)
    ->where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
    ->get();

// Previous month revenue and profit
$previousMonthBookings = Booking::where('marketer_id', $marketerId)
    ->where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
    ->get();

// Calculate current month financials with fixed profit calculation
$currentRevenue = $currentMonthBookings->sum('total_client_price');
$currentProfit = $currentMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters (enter_date should be first)
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return ($booking->client_sell_price - $booking->marketer_sell_price) * $booking->rooms_number * $nights;
});

// Calculate previous month financials with fixed profit calculation
$previousRevenue = $previousMonthBookings->sum('total_client_price');
$previousProfit = $previousMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters (enter_date should be first)
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return ($booking->client_sell_price - $booking->marketer_sell_price) * $booking->rooms_number * $nights;
});

// Calculate profit margin
$currentMargin = $currentRevenue > 0 ? ($currentProfit / $currentRevenue) * 100 : 0;
$previousMargin = $previousRevenue > 0 ? ($previousProfit / $previousRevenue) * 100 : 0;

// Get outstanding payments
$outstandingAmount = Booking::where('marketer_id', $marketerId)
    ->where('status', '!=', 'cancelled')
    ->where(function($query) {
        $query->where('payment_status', '!=', 'paid')
            ->orWhereNull('payment_status');
    })->sum('total_client_price');
    
$previousOutstandingAmount = Booking::where('marketer_id', $marketerId)
    ->where('status', '!=', 'cancelled')
    ->where(function($query) {
        $query->where('payment_status', '!=', 'paid')
            ->orWhereNull('payment_status');
    })
    ->where('created_at', '<', $previousMonthEnd)
    ->sum('total_client_price');

// Calculate percentage changes
$revenueChange = $this->calculatePercentageChange($previousRevenue, $currentRevenue);
$profitChange = $this->calculatePercentageChange($previousProfit, $currentProfit);
$marginChange = $this->calculatePercentageChange($previousMargin, $currentMargin);
$outstandingChange = $this->calculatePercentageChange($previousOutstandingAmount, $outstandingAmount);

$financialData = [
    'total_revenue' => [
        'value' => $currentRevenue,
        'change' => $revenueChange,
        'change_type' => $revenueChange >= 0 ? 'increase' : 'decrease'
    ],
    'net_profit' => [
        'value' => $currentProfit,
        'change' => $profitChange,
        'change_type' => $profitChange >= 0 ? 'increase' : 'decrease'
    ],
    'profit_margin' => [
        'value' => $currentMargin,
        'change' => $marginChange,
        'change_type' => $marginChange >= 0 ? 'increase' : 'decrease'
    ],
    'outstanding_amount' => [
        'value' => $outstandingAmount,
        'change' => $outstandingChange,
        'change_type' => $outstandingChange >= 0 ? 'increase' : 'decrease'
    ]
];

// Get monthly data for charts
$monthlyData = $this->getMonthlyDataForMarketer($marketerId);
$recentBookings = Booking::with(['hotel', 'roomType'])
    ->where('marketer_id', $marketerId)
    ->latest()->take(5)->get();
                
        } elseif ($user->isHotelManager()) {
            $hotelIds = $user->managedHotels->pluck('id')->toArray();
            
$stats = [
    'total_bookings' => Booking::whereIn('hotel_id', $hotelIds)->count(),
    'active_bookings' => Booking::whereIn('hotel_id', $hotelIds)
        ->whereIn('status', ['pending', 'confirmed'])->count(),
    'hotel_revenue' => Booking::whereIn('hotel_id', $hotelIds)
        ->where('status', '!=', 'cancelled')
        ->get()
        ->sum(function($booking) {
            // Fix: Calculate nights correctly by swapping the parameters
            $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
            return $booking->buying_price * $booking->rooms_number * $nights;
        }),
    'recent_bookings' => Booking::with(['roomType'])
        ->whereIn('hotel_id', $hotelIds)
        ->latest()->take(5)->get(),
];
            
// Current month revenue for hotel managers
$currentMonthBookings = Booking::whereIn('hotel_id', $hotelIds)
    ->where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
    ->get();
            
// Previous month revenue
$previousMonthBookings = Booking::whereIn('hotel_id', $hotelIds)
    ->where('status', '!=', 'cancelled')
    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
    ->get();
            
// Calculate current month financials
$currentRevenue = $currentMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return $booking->buying_price * $booking->rooms_number * $nights;
});
            
// Calculate previous month financials
$previousRevenue = $previousMonthBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return $booking->buying_price * $booking->rooms_number * $nights;
});
            
// Get pending bookings value
$pendingBookingsQuery = Booking::whereIn('hotel_id', $hotelIds)
    ->whereIn('status', ['pending', 'confirmed'])
    ->get();
                
$pendingBookingsValue = $pendingBookingsQuery->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return $booking->buying_price * $booking->rooms_number * $nights;
});
                
$previousPendingBookingsQuery = Booking::whereIn('hotel_id', $hotelIds)
    ->whereIn('status', ['pending', 'confirmed'])
    ->where('created_at', '<', $previousMonthEnd)
    ->get();
                
$previousPendingBookingsValue = $previousPendingBookingsQuery->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters
    $nights = Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
    return $booking->buying_price * $booking->rooms_number * $nights;
});
            
// Calculate percentage changes
$revenueChange = $this->calculatePercentageChange($previousRevenue, $currentRevenue);
$pendingChange = $this->calculatePercentageChange($previousPendingBookingsValue, $pendingBookingsValue);
            
// Calculate average stay
$averageStayBookings = Booking::whereIn('hotel_id', $hotelIds)
    ->where('status', '!=', 'cancelled')
    ->get();
                
$totalNights = $averageStayBookings->sum(function($booking) {
    // Fix: Calculate nights correctly by swapping the parameters
    return Carbon::parse($booking->enter_date)->diffInDays(Carbon::parse($booking->leave_date));
});
            
$averageStay = $averageStayBookings->count() > 0 
    ? round($totalNights / $averageStayBookings->count(), 1) 
    : 0;
            
$financialData = [
    'total_revenue' => [
        'value' => $currentRevenue,
        'change' => $revenueChange,
        'change_type' => $revenueChange >= 0 ? 'increase' : 'decrease'
    ],
    'pending_bookings_value' => [
        'value' => $pendingBookingsValue,
        'change' => $pendingChange,
        'change_type' => $pendingChange >= 0 ? 'increase' : 'decrease'
    ],
    'completed_bookings' => [
        'value' => Booking::whereIn('hotel_id', $hotelIds)
            ->where('status', 'completed')
            ->count(),
        'change' => 0,
        'change_type' => 'increase'
    ],
    'average_stay' => [
        'value' => $averageStay,
        'change' => 0,
        'change_type' => 'increase'
    ]
];
            
// Get monthly data for charts
$monthlyData = $this->getMonthlyDataForHotel($hotelIds);
$recentBookings = Booking::with(['roomType'])
    ->whereIn('hotel_id', $hotelIds)
    ->latest()->take(5)->get();
        }
        
        return view('admin.dashboard', compact('stats', 'financialData', 'monthlyData', 'recentBookings'));
    }
    
    /**
     * Get monthly data for chart visualization
     */
    private function getMonthlyData()
    {
        $currentYear = Carbon::now()->year;
        $data = [];
        
        // Initialize data array with months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('M');
            $data[$month] = [
                'month' => $monthName,
                'revenue' => 0,
                'profit' => 0,
                'bookings' => 0
            ];
        }
        
        // Get bookings for the current year
        $bookings = Booking::where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->select([
                'id', 'created_at', 'client_sell_price', 'marketer_sell_price', 
                'buying_price', 'rooms_number', 'enter_date', 'leave_date', 
                'total_client_price', 'marketer_id'
            ])
            ->get();
            
        // Calculate monthly totals
        foreach ($bookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            
            // Use the actual values rather than DB::raw expressions
            $data[$month]['revenue'] += floatval($booking->total_client_price);
            
            // Calculate profit directly
            $profit = 0;
            $nights = Carbon::parse($booking->leave_date)->diffInDays(Carbon::parse($booking->enter_date));
            
            if ($booking->marketer_id) {
                $profit = ($booking->marketer_sell_price * $booking->rooms_number * $nights) - 
                         ($booking->buying_price * $booking->rooms_number * $nights);
            } else {
                $profit = ($booking->client_sell_price * $booking->rooms_number * $nights) - 
                         ($booking->buying_price * $booking->rooms_number * $nights);
            }
            
            $data[$month]['profit'] += $profit;
            $data[$month]['bookings']++;
        }
        
        return array_values($data);
    }
    
    /**
     * Get monthly data for marketers
     */
    private function getMonthlyDataForMarketer($marketerId)
    {
        $currentYear = Carbon::now()->year;
        $data = [];
        
        // Initialize data array with months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('M');
            $data[$month] = [
                'month' => $monthName,
                'revenue' => 0,
                'profit' => 0,
                'bookings' => 0
            ];
        }
        
        // Get bookings for the current year
        $bookings = Booking::where('marketer_id', $marketerId)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->select([
                'id', 'created_at', 'client_sell_price', 'marketer_sell_price', 
                'buying_price', 'rooms_number', 'enter_date', 'leave_date', 
                'total_client_price'
            ])
            ->get();
            
        // Calculate monthly totals
        foreach ($bookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            
            $data[$month]['revenue'] += floatval($booking->total_client_price);
            
            // Calculate marketer profit directly
            $nights = Carbon::parse($booking->leave_date)->diffInDays(Carbon::parse($booking->enter_date));
            $profit = ($booking->client_sell_price - $booking->marketer_sell_price) * 
                    $booking->rooms_number * $nights;
                    
            $data[$month]['profit'] += $profit;
            $data[$month]['bookings']++;
        }
        
        return array_values($data);
    }
    
    /**
     * Get monthly data for hotel managers
     */
    private function getMonthlyDataForHotel($hotelIds)
    {
        $currentYear = Carbon::now()->year;
        $data = [];
        
        // Initialize data array with months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('M');
            $data[$month] = [
                'month' => $monthName,
                'revenue' => 0,
                'bookings' => 0,
                'occupancy_rate' => 0
            ];
        }
        
        // Get bookings for the current year
        $bookings = Booking::whereIn('hotel_id', $hotelIds)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->select([
                'id', 'created_at', 'buying_price', 'rooms_number', 
                'enter_date', 'leave_date'
            ])
            ->get();
            
        // Calculate monthly totals
        foreach ($bookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            
            // Calculate hotel revenue directly
            $nights = Carbon::parse($booking->leave_date)->diffInDays(Carbon::parse($booking->enter_date));
            $revenue = $booking->buying_price * $booking->rooms_number * $nights;
                    
            $data[$month]['revenue'] += $revenue;
            $data[$month]['bookings']++;
        }
        
        return array_values($data);
    }
    
    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('editors', 'public');
        }

        $url = asset('storage/' . $path);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }

    public function index()
    {
        
    }
}