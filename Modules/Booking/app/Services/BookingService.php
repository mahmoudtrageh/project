<?php

namespace Modules\Booking\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Booking\Models\Booking;

class BookingService
{
    /**
     * Calculate the number of nights for a booking
     *
     * @param string $enterDate
     * @param string $leaveDate
     * @return int
     */
    public function calculateNights($enterDate, $leaveDate)
    {
        $enter = new \DateTime($enterDate);
        $leave = new \DateTime($leaveDate);
        
        return $leave->diff($enter)->days;
    }
    
    /**
     * Calculate the total price based on price per night, rooms, and nights
     *
     * @param float $pricePerNight
     * @param int $roomsNumber
     * @param int $nights
     * @return float
     */
    private function calculateTotalPrice($pricePerNight, $roomsNumber, $nights)
    {
        return $pricePerNight * $roomsNumber * $nights;
    }
    
    /**
     * Calculate the total client price
     *
     * @param float $clientPrice
     * @param int $roomsNumber
     * @param int $nights
     * @return float
     */
    public function calculateTotalClientPrice($clientPrice, $roomsNumber, $nights)
    {
        return $this->calculateTotalPrice($clientPrice, $roomsNumber, $nights);
    }
    
    /**
     * Calculate the total marketer price
     *
     * @param float $marketerPrice
     * @param int $roomsNumber
     * @param int $nights
     * @return float
     */
    public function calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights)
    {
        return $this->calculateTotalPrice($marketerPrice, $roomsNumber, $nights);
    }
    
    /**
     * Calculate the total buying price
     *
     * @param float $buyingPrice
     * @param int $roomsNumber
     * @param int $nights
     * @return float
     */
    public function calculateTotalBuyingPrice($buyingPrice, $roomsNumber, $nights)
    {
        return $this->calculateTotalPrice($buyingPrice, $roomsNumber, $nights);
    }
    
    /**
     * Calculate the marketer profit
     *
     * @param float $clientPrice
     * @param float $marketerPrice
     * @param int $roomsNumber
     * @param int $nights
     * @return float
     */
    public function calculateMarketerProfit($clientPrice, $marketerPrice, $roomsNumber, $nights)
    {
        $totalClientPrice = $this->calculateTotalClientPrice($clientPrice, $roomsNumber, $nights);
        $totalMarketerPrice = $this->calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights);
        
        return $totalClientPrice - $totalMarketerPrice;
    }
    
    /**
     * Calculate the admin profit
     *
     * @param float $clientPrice
     * @param float $marketerPrice
     * @param float $buyingPrice
     * @param int $roomsNumber
     * @param int $nights
     * @param bool $hasMarketer
     * @return float
     */
    public function calculateAdminProfit($clientPrice, $marketerPrice, $buyingPrice, $roomsNumber, $nights, $hasMarketer = true)
    {
        $totalBuyingPrice = $this->calculateTotalBuyingPrice($buyingPrice, $roomsNumber, $nights);
        
        if ($hasMarketer) {
            $totalMarketerPrice = $this->calculateTotalMarketerPrice($marketerPrice, $roomsNumber, $nights);
            return $totalMarketerPrice - $totalBuyingPrice;
        } 
        
        $totalClientPrice = $this->calculateTotalClientPrice($clientPrice, $roomsNumber, $nights);
        return $totalClientPrice - $totalBuyingPrice;
    }
    
    /**
     * Get a query for bookings with specified statuses
     * 
     * @param array $statuses
     * @return Builder
     */
    private function getBookingsByStatusQuery(array $statuses)
    {
        return Booking::whereIn('status', $statuses);
    }
    
    /**
     * Get a query for active bookings (pending or confirmed)
     * 
     * @return Builder
     */
    public function getActiveBookingsQuery()
    {
        return $this->getBookingsByStatusQuery(['pending', 'confirmed']);
    }
    
    /**
     * Get a query for completed bookings
     * 
     * @return Builder
     */
    public function getCompletedBookingsQuery()
    {
        return $this->getBookingsByStatusQuery(['completed']);
    }
    
    /**
     * Get a query for cancelled bookings
     * 
     * @return Builder
     */
    public function getCancelledBookingsQuery()
    {
        return $this->getBookingsByStatusQuery(['cancelled']);
    }
    
    /**
     * Get bookings for a specific date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return Builder
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