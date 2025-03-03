<?php

namespace Modules\Booking\Services;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;

class ReportService
{
    protected $bookingService;
    protected $financialService;
    
    public function __construct(BookingService $bookingService, FinancialService $financialService)
    {
        $this->bookingService = $bookingService;
        $this->financialService = $financialService;
    }
    
    /**
     * Generate a bookings report
     */
    public function generateBookingsReport($filters = [])
    {
        $query = Booking::with(['hotel', 'roomType', 'bookingSource', 'marketer.user']);
        
        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['hotel_id']) && $filters['hotel_id']) {
            $query->where('hotel_id', $filters['hotel_id']);
        }
        
        if (isset($filters['marketer_id']) && $filters['marketer_id']) {
            $query->where('marketer_id', $filters['marketer_id']);
        }
        
        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->where('enter_date', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->where('leave_date', '<=', $filters['end_date']);
        }
        
        $bookings = $query->get();
        
        // Calculate totals
        $totalBookings = $bookings->count();
        $totalNights = $bookings->sum(function ($booking) {
            return $booking->nights_count * $booking->rooms_number;
        });
        $totalClientRevenue = $bookings->sum(function ($booking) {
            return $booking->total_client_price;
        });
        $totalMarketerRevenue = $bookings->sum(function ($booking) {
            return $booking->total_marketer_price;
        });
        $totalBuyingCost = $bookings->sum(function ($booking) {
            return $booking->total_buying_price;
        });
        $totalMarketerProfit = $bookings->sum(function ($booking) {
            return $booking->marketer_profit;
        });
        $totalAdminProfit = $bookings->sum(function ($booking) {
            return $booking->admin_profit;
        });
        
        return [
            'bookings' => $bookings,
            'totals' => [
                'count' => $totalBookings,
                'nights' => $totalNights,
                'client_revenue' => $totalClientRevenue,
                'marketer_revenue' => $totalMarketerRevenue,
                'buying_cost' => $totalBuyingCost,
                'marketer_profit' => $totalMarketerProfit,
                'admin_profit' => $totalAdminProfit,
            ],
        ];
    }
    
    /**
     * Generate a marketers report
     */
    public function generateMarketersReport($filters = [])
    {
        $query = Marketer::with('user')->where('active', true);
        
        $marketers = $query->get();
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        
        $results = [];
        
        foreach ($marketers as $marketer) {
            $summary = $this->financialService->getMarketerFinancialSummary($marketer, $startDate, $endDate);
            
            $results[] = [
                'marketer' => $marketer,
                'summary' => $summary,
            ];
        }
        
        // Sort by profit
        usort($results, function ($a, $b) {
            return $b['summary']['marketer_profit'] <=> $a['summary']['marketer_profit'];
        });
        
        return $results;
    }
    
    /**
     * Generate a hotels report
     */
    public function generateHotelsReport($filters = [])
    {
        $query = Hotel::query();
        
        if (isset($filters['hotel_id']) && $filters['hotel_id']) {
            $query->where('id', $filters['hotel_id']);
        }
        
        $hotels = $query->get();
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        
        $results = [];
        
        foreach ($hotels as $hotel) {
            $summary = $this->financialService->getHotelFinancialSummary($hotel, $startDate, $endDate);
            
            $results[] = [
                'hotel' => $hotel,
                'summary' => $summary,
            ];
        }
        
        // Sort by revenue
        usort($results, function ($a, $b) {
            return $b['summary']['total_revenue'] <=> $a['summary']['total_revenue'];
        });
        
        return $results;
    }
    
    /**
     * Generate a financial report
     */
    public function generateFinancialReport($filters = [])
    {
        $year = $filters['year'] ?? date('Y');
        
        $monthlySummary = $this->financialService->getMonthlyFinancialSummary($year);
        
        $annualTotals = [
            'client_revenue' => array_sum(array_column($monthlySummary, 'client_revenue')),
            'hotel_costs' => array_sum(array_column($monthlySummary, 'hotel_costs')),
            'marketer_profit' => array_sum(array_column($monthlySummary, 'marketer_profit')),
            'admin_profit' => array_sum(array_column($monthlySummary, 'admin_profit')),
        ];
        
        return [
            'monthly_summary' => $monthlySummary,
            'annual_totals' => $annualTotals,
        ];
    }
}