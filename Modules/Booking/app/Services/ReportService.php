<?php

namespace Modules\Booking\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Models\Payment;

class ReportService
{
    /**
     * Generate bookings report.
     *
     * @param array $filters
     * @return array
     */
    public function generateBookingsReport($filters = [])
    {
        $user = Auth::user();
        $query = Booking::with(['hotel', 'roomType', 'bookingSource', 'marketer.user', 'payments']);
        
        // Always filter by current admin's ID if user is admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $query->where('admin_id', $user->id);
        } 
        // Apply admin_id filter if explicitly provided (for super admin)
        elseif (!empty($filters['admin_id'])) {
            $query->where('admin_id', $filters['admin_id']);
        }
        
        // Apply other filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }
        
        if (!empty($filters['marketer_id'])) {
            $query->where('marketer_id', $filters['marketer_id']);
        }
        
        if (!empty($filters['start_date'])) {
            $query->where('enter_date', '>=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query->where('leave_date', '<=', $filters['end_date']);
        }
        
        $bookings = $query->latest()->get();
        $bookingsCount = $bookings->count(); // Get the count of bookings
        
        // Calculate totals
        $nightsTotal = 0;
        $clientRevenueTotal = 0;
        $marketerRevenueTotal = 0;
        $buyingCostTotal = 0;
        $marketerProfitTotal = 0;
        $adminProfitTotal = 0;
        
        foreach ($bookings as $booking) {
            $nightsTotal += $booking->nights_count;
            $clientRevenueTotal += $booking->total_client_price;
            $marketerRevenueTotal += $booking->total_marketer_price;
            $buyingCostTotal += $booking->total_buying_price;
            $marketerProfitTotal += $booking->marketer_profit;
            $adminProfitTotal += $booking->admin_profit;
        }
        
        return [
            'bookings' => $bookings,
            'totals' => [
                'count' => $bookingsCount, // Include the count in the results
                'nights' => $nightsTotal,
                'client_revenue' => $clientRevenueTotal,
                'marketer_revenue' => $marketerRevenueTotal,
                'buying_cost' => $buyingCostTotal,
                'marketer_profit' => $marketerProfitTotal,
                'admin_profit' => $adminProfitTotal,
            ]
        ];
    }
    
    /**
     * Generate marketers report.
     *
     * @param array $filters
     * @return array
     */
    public function generateMarketersReport($filters = [])
    {
        $user = Auth::user();
        $marketersQuery = Marketer::with('user');
        
        // Always filter by current admin's ID if user is admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            // Get marketers created by this admin
            $marketersQuery->whereHas('user', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }
        // Filter marketers by admin_id if provided (for super admin)
        elseif (!empty($filters['admin_id'])) {
            $marketersQuery->where('admin_id', $filters['admin_id']);
        }
        
        $marketers = $marketersQuery->get();
        $results = [];
        
        foreach ($marketers as $marketer) {
            $bookingsQuery = Booking::where('marketer_id', $marketer->id);
            
            // Always filter by current admin's ID if user is admin (not super admin)
            if ($user->isAdmin() && !$user->isSuperAdmin()) {
                $bookingsQuery->where('admin_id', $user->id);
            }
            // Filter by admin_id if provided (for super admin)
            elseif (!empty($filters['admin_id'])) {
                $bookingsQuery->where('admin_id', $filters['admin_id']);
            }
            
            // Apply date filters
            if (!empty($filters['start_date'])) {
                $bookingsQuery->where('enter_date', '>=', $filters['start_date']);
            }
            
            if (!empty($filters['end_date'])) {
                $bookingsQuery->where('leave_date', '<=', $filters['end_date']);
            }
            
            $bookings = $bookingsQuery->get();
            
            // Calculate totals
            $totalBookings = $bookings->count();
            $totalClientRevenue = 0;
            $totalMarketerRevenue = 0;
            $marketerProfit = 0;
            
            foreach ($bookings as $booking) {
                $totalClientRevenue += $booking->total_client_price;
                $totalMarketerRevenue += $booking->total_marketer_price;
                $marketerProfit += $booking->marketer_profit;
            }
            
            // Get payments to marketer
            $paymentsQuery = Payment::where('payment_type', 'admin_to_marketer')
                ->whereIn('booking_id', $bookings->pluck('id')->toArray());
            
            // Always filter by current admin's ID if user is admin (not super admin)
            if ($user->isAdmin() && !$user->isSuperAdmin()) {
                $paymentsQuery->where('admin_id', $user->id);
            }
            // Filter payments by admin_id if provided (for super admin)
            elseif (!empty($filters['admin_id'])) {
                $paymentsQuery->where('admin_id', $filters['admin_id']);
            }
            
            $paidAmount = $paymentsQuery->sum('amount');
            $outstandingAmount = max(0, $marketerProfit - $paidAmount);
            
            $results[] = [
                'marketer' => $marketer,
                'summary' => [
                    'total_bookings' => $totalBookings,
                    'total_client_revenue' => $totalClientRevenue,
                    'total_marketer_revenue' => $totalMarketerRevenue,
                    'marketer_profit' => $marketerProfit,
                    'paid_amount' => $paidAmount,
                    'outstanding_amount' => $outstandingAmount,
                ],
            ];
        }
        
        return $results;
    }
    
    /**
     * Generate hotels report.
     *
     * @param array $filters
     * @return array
     */
    public function generateHotelsReport($filters = [])
    {
        $user = Auth::user();
        $hotelsQuery = Hotel::query();
        
        // Always filter by current admin's ID if user is admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $hotelsQuery->where('admin_id', $user->id);
        }
        // Filter hotels by admin_id if provided (for super admin)
        elseif (!empty($filters['admin_id'])) {
            $hotelsQuery->where('admin_id', $filters['admin_id']);
        }
        
        // Filter by specific hotel if provided
        if (!empty($filters['hotel_id'])) {
            $hotelsQuery->where('id', $filters['hotel_id']);
        }
        
        $hotels = $hotelsQuery->get();
        $results = [];
        
        foreach ($hotels as $hotel) {
            $bookingsQuery = Booking::where('hotel_id', $hotel->id);
            
            // Always filter by current admin's ID if user is admin (not super admin)
            if ($user->isAdmin() && !$user->isSuperAdmin()) {
                $bookingsQuery->where('admin_id', $user->id);
            }
            // Filter by admin_id if provided (for super admin)
            elseif (!empty($filters['admin_id'])) {
                $bookingsQuery->where('admin_id', $filters['admin_id']);
            }
            
            // Apply date filters
            if (!empty($filters['start_date'])) {
                $bookingsQuery->where('enter_date', '>=', $filters['start_date']);
            }
            
            if (!empty($filters['end_date'])) {
                $bookingsQuery->where('leave_date', '<=', $filters['end_date']);
            }
            
            $bookings = $bookingsQuery->get();
            
            // Calculate totals
            $totalBookings = $bookings->count();
            $totalNights = 0;
            $totalRevenue = 0;
            
            foreach ($bookings as $booking) {
                $totalNights += $booking->nights_count;
                $totalRevenue += $booking->total_buying_price;
            }
            
            // Get payments to hotel
            $paymentsQuery = Payment::where('payment_type', 'admin_to_hotel')
                ->whereIn('booking_id', $bookings->pluck('id')->toArray());
            
            // Always filter by current admin's ID if user is admin (not super admin)
            if ($user->isAdmin() && !$user->isSuperAdmin()) {
                $paymentsQuery->where('admin_id', $user->id);
            }
            // Filter payments by admin_id if provided (for super admin)
            elseif (!empty($filters['admin_id'])) {
                $paymentsQuery->where('admin_id', $filters['admin_id']);
            }
            
            $paidAmount = $paymentsQuery->sum('amount');
            $outstandingAmount = max(0, $totalRevenue - $paidAmount);
            
            $results[] = [
                'hotel' => $hotel,
                'summary' => [
                    'total_bookings' => $totalBookings,
                    'total_nights' => $totalNights,
                    'total_revenue' => $totalRevenue,
                    'paid_amount' => $paidAmount,
                    'outstanding_amount' => $outstandingAmount,
                ],
            ];
        }
        
        return $results;
    }
    
    /**
     * Generate financial report.
     *
     * @param array $filters
     * @return array
     */
    public function generateFinancialReport($filters = [])
    {
        $user = Auth::user();
        $year = !empty($filters['year']) ? $filters['year'] : date('Y');
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
        
        $bookingsQuery = Booking::whereBetween('enter_date', [$startDate, $endDate]);
        
        // Always filter by current admin's ID if user is admin (not super admin)
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $bookingsQuery->where('admin_id', $user->id);
        }
        // Filter by admin_id if provided (for super admin)
        elseif (!empty($filters['admin_id'])) {
            $bookingsQuery->where('admin_id', $filters['admin_id']);
        }
        
        $bookings = $bookingsQuery->get();
        
        // Group bookings by month
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($year, $month, 1)->format('F');
            
            // Filter bookings for this month
            $monthBookings = $bookings->filter(function ($booking) use ($year, $month) {
                return Carbon::parse($booking->enter_date)->format('Y-m') == sprintf('%04d-%02d', $year, $month);
            });
            
            // Calculate totals for this month
            $clientRevenue = 0;
            $hotelCosts = 0;
            $marketerProfit = 0;
            $adminProfit = 0;
            
            foreach ($monthBookings as $booking) {
                $clientRevenue += $booking->total_client_price;
                $hotelCosts += $booking->total_buying_price;
                $marketerProfit += $booking->marketer_profit;
                $adminProfit += $booking->admin_profit;
            }
            
            $monthlyData[] = [
                'month' => $monthName,
                'client_revenue' => $clientRevenue,
                'hotel_costs' => $hotelCosts,
                'marketer_profit' => $marketerProfit,
                'admin_profit' => $adminProfit,
            ];
        }
        
        // Calculate annual totals
        $annualClientRevenue = 0;
        $annualHotelCosts = 0;
        $annualMarketerProfit = 0;
        $annualAdminProfit = 0;
        
        foreach ($monthlyData as $month) {
            $annualClientRevenue += $month['client_revenue'];
            $annualHotelCosts += $month['hotel_costs'];
            $annualMarketerProfit += $month['marketer_profit'];
            $annualAdminProfit += $month['admin_profit'];
        }
        
        return [
            'monthly_summary' => $monthlyData,
            'annual_totals' => [
                'client_revenue' => $annualClientRevenue,
                'hotel_costs' => $annualHotelCosts,
                'marketer_profit' => $annualMarketerProfit,
                'admin_profit' => $annualAdminProfit,
            ],
        ];
    }
}