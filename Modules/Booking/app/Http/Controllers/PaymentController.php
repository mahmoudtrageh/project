<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only admin can add payments
        if (!$user->isAdmin()) {
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
        
        // Only admin can delete payments
        if (!$user->isAdmin()) {
            return redirect()->back()
                ->with('error', 'You do not have permission to delete payments.');
        }
        
        $payment->delete();
        
        return redirect()->back()
            ->with('success', 'Payment deleted successfully.');
    }
}
