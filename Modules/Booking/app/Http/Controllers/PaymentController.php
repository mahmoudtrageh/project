<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only admin or super admin can add payments
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->back()
                ->with('error', 'You do not have permission to add payments.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => ['required', Rule::in(['client_to_admin', 'admin_to_hotel', 'admin_to_marketer'])],
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Check if the booking belongs to this admin
        $booking = Booking::findOrFail($validated['booking_id']);
        
        if ($user->isAdmin() && $booking->admin_id != $user->id) {
            return redirect()->back()
                ->with('error', 'You do not have permission to add payments to this booking.');
        }
        
        // Add admin_id for admin users
        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
        }
        
        // Create the payment
        $payment = Payment::create($validated);
        
        return redirect()->back()
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isSuperAdmin() && 
            !($user->isAdmin() && $payment->admin_id == $user->id)) {
            return redirect()->back()
                ->with('error', 'You do not have permission to delete this payment.');
        }
        
        $payment->delete();
        
        return redirect()->back()
            ->with('success', 'Payment deleted successfully.');
    }
}