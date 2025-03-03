<?php

namespace Modules\Booking\Services;

use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Models\Payment;

class FinancialService
{
    /**
     * Get total client revenue in a date range
     */
    public function getTotalClientRevenue($startDate = null, $endDate = null)
    {
        $query = Booking::where('status', '!=', 'cancelled');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        return $query->sum(DB::raw('client_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
    }
    
    /**
     * Get total marketer revenue in a date range
     */
    public function getTotalMarketerRevenue($startDate = null, $endDate = null)
    {
        $query = Booking::where('status', '!=', 'cancelled')
            ->whereNotNull('marketer_id');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        return $query->sum(DB::raw('marketer_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
    }
    
    /**
     * Get total hotel costs in a date range
     */
    public function getTotalHotelCosts($startDate = null, $endDate = null)
    {
        $query = Booking::where('status', '!=', 'cancelled');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        return $query->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
    }
    
    /**
     * Get total marketer profit in a date range
     */
    public function getTotalMarketerProfit($startDate = null, $endDate = null)
    {
        $query = Booking::where('status', '!=', 'cancelled')
            ->whereNotNull('marketer_id');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        return $query->sum(DB::raw('(client_sell_price - marketer_sell_price) * rooms_number * DATEDIFF(leave_date, enter_date)'));
    }
    
    /**
     * Get total admin profit in a date range
     */
    public function getTotalAdminProfit($startDate = null, $endDate = null)
    {
        $query = Booking::where('status', '!=', 'cancelled');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        return $query->sum(DB::raw('
            CASE 
                WHEN marketer_id IS NOT NULL THEN 
                    (marketer_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)) - (buying_price * rooms_number * DATEDIFF(leave_date, enter_date))
                ELSE 
                    (client_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)) - (buying_price * rooms_number * DATEDIFF(leave_date, enter_date))
            END
        '));
    }
    
    /**
     * Get financial summary for a specific hotel
     */
    public function getHotelFinancialSummary(Hotel $hotel, $startDate = null, $endDate = null)
    {
        $query = Booking::where('hotel_id', $hotel->id)
            ->where('status', '!=', 'cancelled');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        $totalBookings = $query->count();
        $totalNights = $query->sum(DB::raw('DATEDIFF(leave_date, enter_date) * rooms_number'));
        $totalRevenue = $query->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
        $paidAmount = Payment::whereIn('booking_id', $query->pluck('id'))
            ->where('payment_type', 'admin_to_hotel')
            ->sum('amount');
        $outstandingAmount = $totalRevenue - $paidAmount;
        
        return [
            'total_bookings' => $totalBookings,
            'total_nights' => $totalNights,
            'total_revenue' => $totalRevenue,
            'paid_amount' => $paidAmount,
            'outstanding_amount' => $outstandingAmount,
        ];
    }
    
    /**
     * Get financial summary for a specific marketer
     */
    public function getMarketerFinancialSummary(Marketer $marketer, $startDate = null, $endDate = null)
    {
        $query = Booking::where('marketer_id', $marketer->id)
            ->where('status', '!=', 'cancelled');
        
        if ($startDate) {
            $query->where('enter_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('leave_date', '<=', $endDate);
        }
        
        $totalBookings = $query->count();
        $totalClientRevenue = $query->sum(DB::raw('client_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
        $totalMarketerRevenue = $query->sum(DB::raw('marketer_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
        $marketerProfit = $totalClientRevenue - $totalMarketerRevenue;
        $paidAmount = Payment::whereIn('booking_id', $query->pluck('id'))
            ->where('payment_type', 'admin_to_marketer')
            ->sum('amount');
        $outstandingAmount = $marketerProfit - $paidAmount;
        
        return [
            'total_bookings' => $totalBookings,
            'total_client_revenue' => $totalClientRevenue,
            'total_marketer_revenue' => $totalMarketerRevenue,
            'marketer_profit' => $marketerProfit,
            'paid_amount' => $paidAmount,
            'outstanding_amount' => $outstandingAmount,
        ];
    }
    
    /**
     * Get monthly financial summary
     */
    public function getMonthlyFinancialSummary($year)
    {
        $months = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $clientRevenue = $this->getTotalClientRevenue($startDate, $endDate);
            $hotelCosts = $this->getTotalHotelCosts($startDate, $endDate);
            $marketerProfit = $this->getTotalMarketerProfit($startDate, $endDate);
            $adminProfit = $this->getTotalAdminProfit($startDate, $endDate);
            
            $months[] = [
                'month' => date('F', strtotime($startDate)),
                'client_revenue' => $clientRevenue,
                'hotel_costs' => $hotelCosts,
                'marketer_profit' => $marketerProfit,
                'admin_profit' => $adminProfit,
            ];
        }
        
        return $months;
    }
}