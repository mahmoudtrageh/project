<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        
        // Default values
        $totalProfit = 0;
        $profitHistory = [];
        $recentBookings = collect();
        
        // Debug variable to track role checks
        $userRoles = [
            'isAdmin' => $admin->isAdmin(),
            'isSuperAdmin' => $admin->isSuperAdmin(),
            'isMarketer' => $admin->isMarketer(),
            'isHotelManager' => $admin->isHotelManager()
        ];
        
        try {
            if ($admin->isAdmin() || $admin->isSuperAdmin()) {
                // Get regular admin data
                $totalProfit = $this->getAdminTotalProfit($admin->id);
                $profitHistory = $this->getAdminProfitHistory($admin->id);
                $recentBookings = $this->getRecentProfitableBookings($admin->id);
            } 
            else if ($admin->isMarketer()) {
                // Get marketer data
                $totalProfit = $this->getMarketerTotalProfit($admin->id);
                $profitHistory = $this->getMarketerProfitHistory($admin->id);
                $recentBookings = $this->getMarketerRecentBookings($admin->id);
            }
            else if ($admin->isHotelManager()) {
                // Get hotel manager data
                $managedHotels = Hotel::where('manager_id', $admin->id)->get();
                $hotelIds = $managedHotels->pluck('id')->toArray();
                
                // Calculate summary data
                $totalBookings = Booking::whereIn('hotel_id', $hotelIds)
                    ->where('status', '!=', 'cancelled')
                    ->count();
                    
                $totalProfit = $totalBookings; // Use as a metric for hotel managers
                
                // Get monthly booking history
                $profitHistory = $this->getHotelManagerBookingHistory($admin->id);
                
                // Get recent bookings
                $recentBookings = Booking::whereIn('hotel_id', $hotelIds)
                    ->with(['hotel', 'roomType'])
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error getting financial data: ' . $e->getMessage());
            
            // Set default values in case of error
            $totalProfit = 0;
            $profitHistory = $this->getEmptyProfitHistory();
            $recentBookings = collect();
        }
        
        // Set financial access flag
        $hasFinancialAccess = $admin->isAdmin() || $admin->isSuperAdmin() || $admin->isMarketer();
        
        return view('admin::admin.profile.edit', compact(
            'admin', 
            'totalProfit', 
            'profitHistory', 
            'recentBookings',
            'userRoles',
            'hasFinancialAccess'
        ));
    }
    
    protected function getHotelManagerBookingHistory($adminId)
    {
        $currentYear = Carbon::now()->year;
        $profitByMonth = [];
        
        // Initialize all months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('F');
            $profitByMonth[$month] = [
                'month' => $monthName,
                'profit' => 0, // Will use booking count instead of profit
                'bookings_count' => 0
            ];
        }
        
        // Get managed hotels
        $managedHotels = Hotel::where('manager_id', $adminId)->get();
        $hotelIds = $managedHotels->pluck('id')->toArray();
        
        // Get bookings for these hotels
        $bookings = Booking::whereIn('hotel_id', $hotelIds)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->get();
        
        // Calculate bookings by month
        foreach ($bookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            $profitByMonth[$month]['bookings_count']++;
            $profitByMonth[$month]['profit']++; // Use the booking count as the "profit" value
        }
        
        return array_values($profitByMonth);
    }
    
    protected function getEmptyProfitHistory()
    {
        $profitByMonth = [];
        
        // Initialize all months with zero values
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate(Carbon::now()->year, $month, 1)->format('F');
            $profitByMonth[] = [
                'month' => $monthName,
                'profit' => 0,
                'bookings_count' => 0
            ];
        }
        
        return $profitByMonth;
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->file('image')) {
            if ($admin->image) {
                $this->deleteFile($admin->image);
            }
            $admin->image = $this->uploadFile($request->file('image'));
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    /**
     * Get admin's total profit
     */
    protected function getAdminTotalProfit($adminId)
    {
        $bookings = Booking::where('admin_id', $adminId)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        $totalProfit = 0;
        
        foreach ($bookings as $booking) {
            $totalProfit += $booking->admin_profit;
        }
        
        return $totalProfit;
    }
    
    /**
     * Get admin's profit history by month
     */
    protected function getAdminProfitHistory($adminId)
    {
        $currentYear = Carbon::now()->year;
        $profitByMonth = [];
        
        // Initialize all months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('F');
            $profitByMonth[$month] = [
                'month' => $monthName,
                'profit' => 0,
                'bookings_count' => 0
            ];
        }
        
        // Get bookings for the year
        $bookings = Booking::where('admin_id', $adminId)
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->get();
        
        // Calculate profit by month
        foreach ($bookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            $profitByMonth[$month]['profit'] += $booking->admin_profit;
            $profitByMonth[$month]['bookings_count']++;
        }
        
        return array_values($profitByMonth);
    }
    
    /**
     * Get recent profitable bookings
     */
    protected function getRecentProfitableBookings($adminId)
    {
        return Booking::with(['hotel', 'roomType'])
            ->where('admin_id', $adminId)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    protected function uploadFile($file)
    {
        // Generate a unique file name
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Store in the public disk under categories folder
        $path = $file->storeAs('profile', $fileName, 'public');
        
        return $path;
    }

    protected function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

// Add this method to your controller
protected function getMarketerTotalProfit($adminId)
{
    // Find the marketer profile associated with this admin user
    $marketerProfile = Marketer::where('admin_id', $adminId)->first();
    
    if (!$marketerProfile) {
        return 0;
    }
    
    $bookings = Booking::where('marketer_id', $marketerProfile->id)
        ->where('status', '!=', 'cancelled')
        ->get();
        
    $totalProfit = 0;
    
    foreach ($bookings as $booking) {
        $totalProfit += $booking->marketer_profit;
    }
    
    return $totalProfit;
}

protected function getMarketerProfitHistory($adminId)
{
    $marketerProfile = Marketer::where('admin_id', $adminId)->first();
    
    if (!$marketerProfile) {
        return $this->getEmptyProfitHistory();
    }
    
    $currentYear = Carbon::now()->year;
    $profitByMonth = [];
    
    // Initialize all months
    for ($month = 1; $month <= 12; $month++) {
        $monthName = Carbon::createFromDate($currentYear, $month, 1)->format('F');
        $profitByMonth[$month] = [
            'month' => $monthName,
            'profit' => 0,
            'bookings_count' => 0
        ];
    }
    
    // Get bookings for the marketer
    $bookings = Booking::where('marketer_id', $marketerProfile->id)
        ->where('status', '!=', 'cancelled')
        ->whereYear('created_at', $currentYear)
        ->get();
    
    foreach ($bookings as $booking) {
        $month = Carbon::parse($booking->created_at)->month;
        $profitByMonth[$month]['profit'] += $booking->marketer_profit;
        $profitByMonth[$month]['bookings_count']++;
    }
    
    return array_values($profitByMonth);
}

protected function getMarketerRecentBookings($adminId)
{
    $marketerProfile = Marketer::where('admin_id', $adminId)->first();
    
    if (!$marketerProfile) {
        return collect();
    }
    
    return Booking::with(['hotel', 'roomType'])
        ->where('marketer_id', $marketerProfile->id)
        ->where('status', '!=', 'cancelled')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
}
}