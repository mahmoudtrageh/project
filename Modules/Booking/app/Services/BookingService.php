<?php

namespace Modules\Booking\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;

class BookingService
{
    /**
     * Calculate the number of nights for a booking
     */
    public function calculateNights($enterDate, $leaveDate)
    {
        $enter = new \DateTime($enterDate);
        $leave = new \DateTime($leaveDate);
        
        return $leave->diff($enter)->days;
    }
    
    /**
     * Calculate the total client price
     */
    public function calculateTotalClientPrice($clientPrice, $roomsNumber, $nights)
    {
        return $clientPrice * $roomsNumber * $nights;
    }
    
    /**
     * Calculate the total marketer price
     */
    public function calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights)
    {
        return $marketerPrice * $roomsNumber * $nights;
    }
    
    /**
     * Calculate the total buying price
     */
    public function calculateTotalBuyingPrice($buyingPrice, $roomsNumber, $nights)
    {
        return $buyingPrice * $roomsNumber * $nights;
    }
    
    /**
     * Calculate the marketer profit
     */
    public function calculateMarketerProfit($clientPrice, $marketerPrice, $roomsNumber, $nights)
    {
        $totalClientPrice = $this->calculateTotalClientPrice($clientPrice, $roomsNumber, $nights);
        $totalMarketerPrice = $this->calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights);
        
        return $totalClientPrice - $totalMarketerPrice;
    }
    
    /**
     * Calculate the admin profit
     */
    public function calculateAdminProfit($clientPrice, $marketerPrice, $buyingPrice, $roomsNumber, $nights, $hasMarketer = true)
    {
        if ($hasMarketer) {
            $totalMarketerPrice = $this->calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights);
            $totalBuyingPrice = $this->calculateTotalBuyingPrice($buyingPrice, $roomsNumber, $nights);
            
            return $totalMarketerPrice - $totalBuyingPrice;
        } else {
            $totalClientPrice = $this->calculateTotalClientPrice($clientPrice, $roomsNumber, $nights);
            $totalBuyingPrice = $this->calculateTotalBuyingPrice($buyingPrice, $roomsNumber, $nights);
            
            return $totalClientPrice - $totalBuyingPrice;
        }
    }
    
    /**
     * Get a query for active bookings (pending or confirmed)
     */
    public function getActiveBookingsQuery()
    {
        return Booking::whereIn('status', ['pending', 'confirmed']);
    }
    
    /**
     * Get a query for completed bookings
     */
    public function getCompletedBookingsQuery()
    {
        return Booking::where('status', 'completed');
    }
    
    /**
     * Get a query for cancelled bookings
     */
    public function getCancelledBookingsQuery()
    {
        return Booking::where('status', 'cancelled');
    }
    
    /**
     * Get bookings for a specific date range
     */
    public function getBookingsInDateRange($startDate, $endDate)
    {
        return Booking::where(function(Builder $query) use ($startDate, $endDate) {
            // Bookings that start within the range
            $query->whereBetween('enter_date', [$startDate, $endDate]);
            
            // Or bookings that end within the range
            $query->orWhereBetween('leave_date', [$startDate, $endDate]);
            
            // Or bookings that span the entire range
            $query->orWhere(function(Builder $q) use ($startDate, $endDate) {
                $q->where('enter_date', '<=', $startDate)
                  ->where('leave_date', '>=', $endDate);
            });
        });
    }
}