<?php

namespace Modules\Booking\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;
use Modules\Booking\Services\ReportService;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        
        // Get filters from request
        $filters = [
            'status' => $request->input('status'),
            'hotel_id' => $request->input('hotel_id'),
            'marketer_id' => $request->input('marketer_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];
        
        // Generate report
        $report = $this->reportService->generateBookingsReport($filters);
        
        // Get data for filter dropdowns
        // Only get hotels associated with the current admin
        if ($user->isAdmin() && !$user->isSuperAdmin()) {
            $hotels = Hotel::where('admin_id', $user->id)->pluck('name', 'id');
            
            // Get marketers created by this admin
            $adminMarketers = Admin::where('created_by', $user->id)
                ->where('user_type', 'marketer')
                ->get();
                
            // Map to marketer profiles with names
            $marketers = [];
            foreach ($adminMarketers as $adminMarketer) {
                $marketerProfile = Marketer::where('admin_id', $adminMarketer->id)->first();
                if ($marketerProfile) {
                    $marketers[$marketerProfile->id] = $adminMarketer->name;
                }
            }
        } else {
            // Super admin sees all records
            $hotels = Hotel::pluck('name', 'id');
            $marketers = Marketer::with('user')->get()->mapWithKeys(function ($marketer) {
                return [$marketer->id => $marketer->user->name ?? 'Unknown'];
            })->toArray();
        }
        
        return view('booking::reports.bookings', compact('report', 'hotels', 'marketers'));
    }

    /**
     * Display marketers report.
     */
    public function marketers(Request $request)
{
    // Get filters from request
    $filters = [
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ];
    
    // Generate report
    $marketersReport = $this->reportService->generateMarketersReport($filters);
    
    return view('booking::reports.marketers', [
        'results' => $marketersReport // Rename to match the variable used in the view
    ]);
}

    /**
     * Display hotels report.
     */
    public function hotels(Request $request)
{
    $user = Auth::user();
    
    // Get filters from request
    $filters = [
        'hotel_id' => $request->input('hotel_id'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ];
    
    // Generate report
    $results = $this->reportService->generateHotelsReport($filters);
    
    // Get data for filter dropdowns
    if ($user->isAdmin() && !$user->isSuperAdmin()) {
        $hotelsList = Hotel::where('admin_id', $user->id)->pluck('name', 'id');
    } else {
        $hotelsList = Hotel::pluck('name', 'id');
    }
    
    return view('booking::reports.hotels', [
        'results' => $results,
        'hotels' => $hotelsList
    ]);
}

    /**
     * Display financial report.
     */
    public function financial(Request $request)
{
    // Get filters from request
    $filters = [
        'year' => $request->input('year', date('Y')),
    ];
    
    // Generate report
    $financialReport = $this->reportService->generateFinancialReport($filters);
    
    // Available years for dropdown (last 5 years)
    $currentYear = date('Y');
    $years = range($currentYear - 4, $currentYear);
    
    return view('booking::reports.financial', [
        'report' => $financialReport,
        'years' => $years,
        'filters' => $filters
    ]);
}

    /**
     * Export report data to CSV.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'bookings');
        $filters = $request->except('type');
        
        switch ($type) {
            case 'bookings':
                return $this->exportBookings($filters);
            case 'marketers':
                return $this->exportMarketers($filters);
            case 'hotels':
                return $this->exportHotels($filters);
            case 'financial':
                return $this->exportFinancial($filters);
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }

    /**
     * Export bookings report to CSV.
     */
    private function exportBookings($filters)
    {
        $report = $this->reportService->generateBookingsReport($filters);
        $bookings = $report['bookings'];
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings_report.csv"',
        ];
        
        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($file, [
                'Client Name',
                'Client Phone',
                'Hotel',
                'Room Type',
                'Check-in',
                'Check-out',
                'Nights',
                'Rooms',
                'Client Price',
                'Marketer Price',
                'Buying Price',
                'Admin Profit',
                'Status',
            ]);
            
            // Add data rows
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->client_name,
                    $booking->client_phone,
                    $booking->hotel->name ?? 'Unknown',
                    $booking->roomType->name ?? 'Standard',
                    $booking->enter_date->format('Y-m-d'),
                    $booking->leave_date->format('Y-m-d'),
                    $booking->nights_count,
                    $booking->rooms_number,
                    $booking->total_client_price,
                    $booking->total_marketer_price,
                    $booking->total_buying_price,
                    $booking->admin_profit,
                    $booking->status,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}