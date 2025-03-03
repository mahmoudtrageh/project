<?php

namespace Modules\Booking\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Show bookings report.
     */
    public function bookings(Request $request)
    {
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isMarketer()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this report.');
        }

        // Prepare filters
        $filters = [
            'status' => $request->input('status'),
            'hotel_id' => $request->input('hotel_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        // Add marketer filter if user is marketer
        if ($user->isMarketer()) {
            $filters['marketer_id'] = $user->marketerProfile->id;
        } else {
            $filters['marketer_id'] = $request->input('marketer_id');
        }

        // Generate report
        $report = $this->reportService->generateBookingsReport($filters);

        // Get data for filter dropdowns
        $hotels = Hotel::pluck('name', 'id');
        $marketers = Marketer::with('user')->where('active', true)->get()
            ->pluck('user.name', 'id');
        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
        ];

        return view('booking::reports.bookings', compact('report', 'filters', 'hotels', 'marketers', 'statuses'));
    }

    /**
     * Show marketers report.
     */
    public function marketers(Request $request)
{
    // Only admin can view this report
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to view this report.');
    }

    // Prepare filters
    $filters = [
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ];

    // Generate report
    $results = $this->reportService->generateMarketersReport($filters); // Changed from $report to $results

    return view('booking::reports.marketers', compact('results', 'filters'));
}

    /**
     * Show hotels report.
     */
    public function hotels(Request $request)
    {
        // Only admin can view this report
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this report.');
        }
    
        // Prepare filters
        $filters = [
            'hotel_id' => $request->input('hotel_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];
    
        // Generate report
        $results = $this->reportService->generateHotelsReport($filters); // Changed from $report to $results
    
        // Get data for filter dropdowns
        $hotels = Hotel::pluck('name', 'id');
    
        return view('booking::reports.hotels', compact('results', 'filters', 'hotels'));
    }
    /**
     * Show financial report.
     */
    public function financial(Request $request)
    {
        // Only admin can view this report
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this report.');
        }

        // Prepare filters
        $filters = [
            'year' => $request->input('year', date('Y')),
        ];

        // Generate report
        $report = $this->reportService->generateFinancialReport($filters);

        // Available years for filter
        $currentYear = date('Y');
        $years = range($currentYear - 5, $currentYear);

        return view('booking::reports.financial', compact('report', 'filters', 'years'));
    }

    /**
     * Export report to CSV.
     */
    public function export(Request $request, $type)
    {
        // Only admin can export reports
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to export reports.');
        }

        // Prepare filters
        $filters = [
            'status' => $request->input('status'),
            'hotel_id' => $request->input('hotel_id'),
            'marketer_id' => $request->input('marketer_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'year' => $request->input('year', date('Y')),
        ];

        $filename = $type . '_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Generate the appropriate report based on type
        switch ($type) {
            case 'bookings':
                $report = $this->reportService->generateBookingsReport($filters);
                $callback = function () use ($report) {
                    $file = fopen('php://output', 'w');
                    
                    // Header row
                    fputcsv($file, [
                        'Booking ID',
                        'Hotel',
                        'Client Name',
                        'Client Phone',
                        'Check-in Date',
                        'Check-out Date',
                        'Nights',
                        'Rooms',
                        'Room Type',
                        'Client Price',
                        'Marketer Price',
                        'Buying Price',
                        'Total Client Price',
                        'Total Marketer Price',
                        'Total Buying Price',
                        'Marketer Profit',
                        'Admin Profit',
                        'Status',
                    ]);
                    
                    // Data rows
                    foreach ($report['bookings'] as $booking) {
                        fputcsv($file, [
                            $booking->id,
                            $booking->hotel->name,
                            $booking->client_name,
                            $booking->client_phone,
                            $booking->enter_date->format('Y-m-d'),
                            $booking->leave_date->format('Y-m-d'),
                            $booking->nights_count,
                            $booking->rooms_number,
                            $booking->roomType ? $booking->roomType->name : 'N/A',
                            $booking->client_sell_price,
                            $booking->marketer_sell_price,
                            $booking->buying_price,
                            $booking->total_client_price,
                            $booking->total_marketer_price,
                            $booking->total_buying_price,
                            $booking->marketer_profit,
                            $booking->admin_profit,
                            $booking->status,
                        ]);
                    }
                    
                    // Totals row
                    fputcsv($file, []);
                    fputcsv($file, [
                        'Totals',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $report['totals']['nights'],
                        '',
                        '',
                        '',
                        '',
                        '',
                        $report['totals']['client_revenue'],
                        $report['totals']['marketer_revenue'],
                        $report['totals']['buying_cost'],
                        $report['totals']['marketer_profit'],
                        $report['totals']['admin_profit'],
                        '',
                    ]);
                    
                    fclose($file);
                };
                break;
                
            case 'marketers':
                $report = $this->reportService->generateMarketersReport($filters);
                $callback = function () use ($report) {
                    $file = fopen('php://output', 'w');
                    
                    // Header row
                    fputcsv($file, [
                        'Marketer ID',
                        'Marketer Name',
                        'Email',
                        'Phone',
                        'Total Bookings',
                        'Total Client Revenue',
                        'Total Marketer Revenue',
                        'Marketer Profit',
                        'Paid Amount',
                        'Outstanding Amount',
                    ]);
                    
                    // Data rows
                    foreach ($report as $item) {
                        $marketer = $item['marketer'];
                        $summary = $item['summary'];
                        
                        fputcsv($file, [
                            $marketer->id,
                            $marketer->user->name,
                            $marketer->user->email,
                            $marketer->user->phone,
                            $summary['total_bookings'],
                            $summary['total_client_revenue'],
                            $summary['total_marketer_revenue'],
                            $summary['marketer_profit'],
                            $summary['paid_amount'],
                            $summary['outstanding_amount'],
                        ]);
                    }
                    
                    fclose($file);
                };
                break;
                
            case 'hotels':
                $report = $this->reportService->generateHotelsReport($filters);
                $callback = function () use ($report) {
                    $file = fopen('php://output', 'w');
                    
                    // Header row
                    fputcsv($file, [
                        'Hotel ID',
                        'Hotel Name',
                        'Address',
                        'Contact Person',
                        'Contact Phone',
                        'Total Bookings',
                        'Total Nights',
                        'Total Revenue',
                        'Paid Amount',
                        'Outstanding Amount',
                    ]);
                    
                    // Data rows
                    foreach ($report as $item) {
                        $hotel = $item['hotel'];
                        $summary = $item['summary'];
                        
                        fputcsv($file, [
                            $hotel->id,
                            $hotel->name,
                            $hotel->address,
                            $hotel->contact_person,
                            $hotel->contact_phone,
                            $summary['total_bookings'],
                            $summary['total_nights'],
                            $summary['total_revenue'],
                            $summary['paid_amount'],
                            $summary['outstanding_amount'],
                        ]);
                    }
                    
                    fclose($file);
                };
                break;
                
            case 'financial':
                $report = $this->reportService->generateFinancialReport($filters);
                $callback = function () use ($report) {
                    $file = fopen('php://output', 'w');
                    
                    // Header row
                    fputcsv($file, [
                        'Month',
                        'Client Revenue',
                        'Hotel Costs',
                        'Marketer Profit',
                        'Admin Profit',
                    ]);
                    
                    // Data rows
                    foreach ($report['monthly_summary'] as $month) {
                        fputcsv($file, [
                            $month['month'],
                            $month['client_revenue'],
                            $month['hotel_costs'],
                            $month['marketer_profit'],
                            $month['admin_profit'],
                        ]);
                    }
                    
                    // Annual totals
                    fputcsv($file, []);
                    fputcsv($file, [
                        'Annual Total',
                        $report['annual_totals']['client_revenue'],
                        $report['annual_totals']['hotel_costs'],
                        $report['annual_totals']['marketer_profit'],
                        $report['annual_totals']['admin_profit'],
                    ]);
                    
                    fclose($file);
                };
                break;
                
            default:
                return redirect()->back()
                    ->with('error', 'Invalid report type.');
        }

        return response()->stream($callback, 200, $headers);
    }
}