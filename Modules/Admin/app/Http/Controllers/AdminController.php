<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Marketer;

class AdminController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Only admin can view users
        if (!Auth::user()->isAdmin()) {
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
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only admin can create users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create users.');
        }
        
        return view('admin::admins.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create users.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => ['required', Rule::in(['admin', 'marketer', 'hotel_manager', 'client'])],
        ]);
        
        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        // Create the user
        $admin = Admin::create($validated);
        
        // Create marketer profile if user is a marketer
        if ($validated['user_type'] === 'marketer') {
            $commissionPercentage = $request->input('commission_percentage', 0);
            
            Marketer::create([
                'user_id' => $admin->id,
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
        // Only admin can edit users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to edit users.');
        }
        
        // Load marketer profile if exists
        if ($admin->isMarketer()) {
            $admin->load('marketerProfile');
        }
        
        return view('admin::admins.edit', compact('admin'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        // Only admin can update users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to update users.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => ['required', Rule::in(['admin', 'marketer', 'hotel_manager', 'client'])],
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
                ['user_id' => $admin->id],
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
        // Only admin can delete users
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to delete users.');
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
}
