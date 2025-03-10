<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Models\Payment;

class AdminController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        // Only super_admin can view all users
        if (!$currentUser->isSuperAdmin()) {
            // Regular admins can only see their related users
            if ($currentUser->isAdmin()) {
                return $this->indexForRegularAdmin($request);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view users.');
        }
        
        $query = Admin::query();
        
        // Apply filters
        if ($request->has('user_type') && $request->user_type != '') {
            $query->where('user_type', $request->user_type);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $admins = $query->latest()->paginate(15);
        
        return view('admin::admins.index', compact('admins'));
    }
    
    /**
     * Display a listing of users for a regular admin.
     */
    private function indexForRegularAdmin(Request $request)
    {
        $currentUser = Auth::user();
        $query = Admin::where('created_by', $currentUser->id);
        
        // Apply filters
        if ($request->has('user_type') && $request->user_type != '') {
            $query->where('user_type', $request->user_type);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $admins = $query->latest()->paginate(15);
        
        return view('admin::admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Only super_admin can create admins, while admins can create other user types
        if (!$currentUser->isSuperAdmin() && !$currentUser->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create users.');
        }
        
        return view('admin::admins.create', ['currentUserType' => $currentUser->user_type]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        // Only super_admin can create admins, while admins can create other user types
        if (!$currentUser->isSuperAdmin() && !$currentUser->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create users.');
        }
        
        // Determine allowed user types based on current user role
        $allowedUserTypes = $currentUser->isSuperAdmin() 
            ? ['admin', 'marketer', 'hotel_manager', 'client']
            : ['marketer', 'hotel_manager', 'client'];
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => ['required', Rule::in($allowedUserTypes)],
        ]);
        
        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        // Add created_by field to track which admin created this user
        $validated['created_by'] = $currentUser->id;
        
        // Create the user
        $admin = Admin::create($validated);
        
        // Create marketer profile if user is a marketer
        if ($validated['user_type'] === 'marketer') {
            $commissionPercentage = $request->input('commission_percentage', 0);
            
            Marketer::create([
                'admin_id' => $admin->id,
                'commission_percentage' => $commissionPercentage,
                'active' => true,
            ]);
        }
        
        return redirect()->route('admins.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Admin $admin)
    {
        $currentUser = Auth::user();
        
        // Check permissions
        if (!$this->canManageUser($currentUser, $admin)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to edit this user.');
        }
        
        // Load marketer profile if exists
        if ($admin->isMarketer()) {
            $admin->load('marketerProfile');
        }
        
        return view('admin::admins.edit', [
            'admin' => $admin,
            'currentUserType' => $currentUser->user_type
        ]);
    }

    public function show(Admin $admin)
    {
        // Only admin can view users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view users.');
        }
        
        // For marketers, check marketer profile
        if ($admin->isMarketer()) {
            // Get marketer profile
            $marketerProfile = Marketer::where('admin_id', $admin->id)->first();
            
            // Get marketer's financial data
            if ($marketerProfile) {
                $bookings = Booking::where('marketer_id', $marketerProfile->id)
                    ->where('status', '!=', 'cancelled')
                    ->get();
                
                $totalBookings = $bookings->count();
                $totalCommission = 0;
                
                foreach ($bookings as $booking) {
                    $totalCommission += $booking->marketer_profit;
                }
                
                $payments = Payment::where('payment_type', 'admin_to_marketer')
                    ->whereIn('booking_id', $bookings->pluck('id')->toArray())
                    ->get();
                
                $totalPaid = $payments->sum('amount');
                $pendingPayment = $totalCommission - $totalPaid;
                
                $financialData = [
                    'totalBookings' => $totalBookings,
                    'totalCommission' => $totalCommission,
                    'totalPaid' => $totalPaid,
                    'pendingPayment' => $pendingPayment,
                    'commissionRate' => $marketerProfile->commission_percentage ?? 0,
                    'payments' => $payments,
                ];
                
                return view('admin::admins.show', compact('admin', 'marketerProfile', 'financialData'));
            }
        }
        
        // For hotel managers, get managed hotels
        if ($admin->isHotelManager()) {
            $hotels = Hotel::where('manager_id', $admin->id)->get();
            
            $hotelIds = $hotels->pluck('id')->toArray();
            $bookings = Booking::whereIn('hotel_id', $hotelIds)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $totalRevenue = 0;
            foreach ($bookings as $booking) {
                $totalRevenue += $booking->total_buying_price;
            }
            
            $bookingIds = $bookings->pluck('id')->toArray();
            $payments = Payment::where('payment_type', 'admin_to_hotel')
                ->whereIn('booking_id', $bookingIds)
                ->get();
            
            $totalPaid = $payments->sum('amount');
            $pendingPayment = $totalRevenue - $totalPaid;
            
            $financialData = [
                'hotels' => $hotels,
                'totalRevenue' => $totalRevenue,
                'totalPaid' => $totalPaid,
                'pendingPayment' => $pendingPayment,
                'payments' => $payments
            ];
            
            return view('admin::admins.show', compact('admin', 'hotels', 'financialData'));
        }
        
        // For admin and super_admin
        if ($admin->isAdmin() || $admin->user_type === 'super_admin') {
            $bookings = Booking::where('admin_id', $admin->id)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $totalBookings = $bookings->count();
            $totalRevenue = 0;
            $totalProfit = 0;
            
            foreach ($bookings as $booking) {
                $totalRevenue += $booking->total_client_price;
                $totalProfit += $booking->admin_profit;
            }
            
            $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
            
            // Get monthly profit data for chart
            $currentYear = date('Y');
            $monthlyProfits = [];
            
            for ($month = 1; $month <= 12; $month++) {
                $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
                
                $monthlyBookings = Booking::where('admin_id', $admin->id)
                    ->where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                
                $monthProfit = 0;
                foreach ($monthlyBookings as $booking) {
                    $monthProfit += $booking->admin_profit;
                }
                
                $monthlyProfits[] = [
                    'month' => Carbon::createFromDate($currentYear, $month, 1)->format('M'),
                    'profit' => $monthProfit
                ];
            }
            
            // Get recent transactions
            $bookingIds = $bookings->pluck('id')->toArray();
            $recentTransactions = Payment::whereIn('booking_id', $bookingIds)
                ->orderBy('payment_date', 'desc')
                ->limit(10)
                ->get();
            
            // Get users created by this admin
            $createdUsers = Admin::where('created_by', $admin->id)->get();
            
            $financialData = [
                'totalBookings' => $totalBookings,
                'totalRevenue' => $totalRevenue,
                'totalProfit' => $totalProfit,
                'profitMargin' => $profitMargin,
                'monthlyProfits' => $monthlyProfits,
                'recentTransactions' => $recentTransactions
            ];
            
            return view('admin::admins.show', compact('admin', 'financialData', 'createdUsers'));
        }
        
        // For clients (no financial data)
        return view('admin::admins.show', compact('admin'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $currentUser = Auth::user();
        
        // Check permissions
        if (!$this->canManageUser($currentUser, $admin)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to update this user.');
        }
        
        // Determine allowed user types based on current user role
        $allowedUserTypes = $currentUser->isSuperAdmin() 
            ? ['admin', 'marketer', 'hotel_manager', 'client']
            : ['marketer', 'hotel_manager', 'client'];
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => ['required', Rule::in($allowedUserTypes)],
        ]);
        
        // Hash the password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Update the user
        $admin->update($validated);
        
        // Handle marketer profile
        if ($validated['user_type'] === 'marketer') {
            $commissionPercentage = $request->input('commission_percentage', 0);
            
            Marketer::updateOrCreate(
                ['admin_id' => $admin->id],
                [
                    'commission_percentage' => $commissionPercentage,
                    'active' => $request->has('active'),
                ]
            );
        }
        
        return redirect()->route('admins.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Admin $admin)
    {
        $currentUser = Auth::user();
        
        // Check permissions
        if (!$this->canManageUser($currentUser, $admin)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to delete this user.');
        }
        
        // Cannot delete yourself
        if ($admin->id === Auth::id()) {
            return redirect()->route('admins.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        $admin->delete();
        
        return redirect()->route('admins.index')
            ->with('success', 'User deleted successfully.');
    }
    
    /**
     * Check if current user can manage the target user.
     */
    private function canManageUser($currentUser, $targetUser)
    {
        // Super admin can manage any user
        if ($currentUser->isSuperAdmin()) {
            return true;
        }
        
        // Regular admin can only manage users they created or non-admin users
        if ($currentUser->isAdmin()) {
            // Can manage if they created the user
            if ($targetUser->created_by === $currentUser->id) {
                return true;
            }
            
            // Can manage if target is a non-admin user
            if (in_array($targetUser->user_type, ['marketer', 'hotel_manager', 'client'])) {
                return true;
            }
        }
        
        return false;
    }

    public function processPayment(Request $request, Marketer $marketer)
    {
        // Validate the request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $currentUser = Auth::user();
        $marketerUser = Admin::findOrFail($marketer->admin_id);

        // Check if user has permission to make payments to this marketer
        if (!$currentUser->isSuperAdmin() && $marketerUser->created_by != $currentUser->id) {
            return back()->with('error', 'You do not have permission to make payments to this marketer.');
        }

        // Create payment record
        $payment = new Payment();
        $payment->amount = $validated['amount'];
        $payment->payment_type = 'admin_to_marketer';
        $payment->payment_date = now();
        $payment->payment_method = $validated['payment_method'];
        $payment->notes = $validated['notes'] ?? 'Commission payment to marketer';
        $payment->admin_id = $currentUser->id;
        
        // Find the most recent booking with unpaid commission for this marketer
        $bookingsWithUnpaidCommission = Booking::where('marketer_id', $marketer->id)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->filter(function($booking) {
                // Calculate unpaid commission
                $paid = Payment::where('booking_id', $booking->id)
                    ->where('payment_type', 'admin_to_marketer')
                    ->sum('amount');
                
                return ($booking->marketer_profit - $paid) > 0;
            })
            ->sortByDesc('created_at');
        
        // Attach to the most recent booking with unpaid commission if available
        if ($bookingsWithUnpaidCommission->count() > 0) {
            $payment->booking_id = $bookingsWithUnpaidCommission->first()->id;
        }
        
        $payment->save();

        return redirect()->route('admins.show', $marketer->admin_id)
            ->with('success', 'Payment of $' . number_format($payment->amount, 2) . ' to marketer has been recorded successfully.');
    }
}