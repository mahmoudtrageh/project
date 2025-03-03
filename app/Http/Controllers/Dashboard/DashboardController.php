<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Stats for dashboard based on user type
        $stats = [];
        
        if ($user->isAdmin()) {
            $stats = [
                'total_bookings' => Booking::count(),
                'active_bookings' => Booking::whereIn('status', ['pending', 'confirmed'])->count(),
                'total_hotels' => Hotel::count(),
                'total_marketers' => Marketer::where('active', true)->count(),
                'recent_bookings' => Booking::with(['hotel', 'roomType', 'marketer.user'])
                    ->latest()->take(5)->get(),
                'admin_profit' => Booking::where('status', '!=', 'cancelled')
                    ->sum(DB::raw('(
                        CASE 
                            WHEN marketer_id IS NOT NULL THEN 
                                (marketer_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)) - (buying_price * rooms_number * DATEDIFF(leave_date, enter_date))
                            ELSE 
                                (client_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)) - (buying_price * rooms_number * DATEDIFF(leave_date, enter_date))
                        END
                    )')),
            ];
        } elseif ($user->isMarketer()) {
            $marketerId = $user->marketerProfile->id;
            $stats = [
                'total_bookings' => Booking::where('marketer_id', $marketerId)->count(),
                'active_bookings' => Booking::where('marketer_id', $marketerId)
                    ->whereIn('status', ['pending', 'confirmed'])->count(),
                'marketer_profit' => Booking::where('marketer_id', $marketerId)
                    ->where('status', '!=', 'cancelled')
                    ->sum(DB::raw('(client_sell_price - marketer_sell_price) * rooms_number * DATEDIFF(leave_date, enter_date)')),
                'recent_bookings' => Booking::with(['hotel', 'roomType'])
                    ->where('marketer_id', $marketerId)
                    ->latest()->take(5)->get(),
            ];
        } elseif ($user->isHotelManager()) {
            $hotelIds = $user->managedHotels->pluck('id')->toArray();
            $stats = [
                'total_bookings' => Booking::whereIn('hotel_id', $hotelIds)->count(),
                'active_bookings' => Booking::whereIn('hotel_id', $hotelIds)
                    ->whereIn('status', ['pending', 'confirmed'])->count(),
                'hotel_revenue' => Booking::whereIn('hotel_id', $hotelIds)
                    ->where('status', '!=', 'cancelled')
                    ->sum(DB::raw('buying_price * rooms_number * DATEDIFF(leave_date, enter_date)')),
                'recent_bookings' => Booking::with(['roomType'])
                    ->whereIn('hotel_id', $hotelIds)
                    ->latest()->take(5)->get(),
            ];
        }
        
        return view('admin.dashboard', compact('stats'));
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('editors', 'public');
        }

        $url = asset('storage/' . $path);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }

    public function index()
    {
        
    }
}
