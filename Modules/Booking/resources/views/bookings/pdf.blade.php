<!-- resources/views/booking/bookings/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Booking #{{ $bookingNumber }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .header {
            position: relative;
            margin-bottom: 30px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .booking-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
        }
        .label {
            font-weight: bold;
            margin-right: 5px;
            min-width: 120px;
            display: inline-block;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        .status-confirmed {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-cancelled {
            color: red;
            font-weight: bold;
        }
        .status-completed {
            color: blue;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="company-info">
            <div class="company-name">Hotel Booking System</div>
            <div>123 Booking Street</div>
            <div>City, Country, ZIP</div>
            <div>Phone: +1 234 567 890</div>
            <div>Email: bookings@example.com</div>
        </div>
        <div class="booking-info">
            <h1>BOOKING CONFIRMATION</h1>
            <div><span class="label">Booking Number:</span> {{ $bookingNumber }}</div>
            <div><span class="label">Booking Date:</span> {{ $booking->created_at->format('M d, Y') }}</div>
            <div><span class="label">Status:</span> 
                <span class="status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            </div>
        </div>
    </div>
    
    <h2>Client Information</h2>
    <table>
        <tr>
            <td width="50%"><span class="label">Name:</span> {{ $booking->client_name }}</td>
            <td width="50%"><span class="label">Phone:</span> {{ $booking->client_phone ?? 'N/A' }}</td>
        </tr>
    </table>
    
    <h2>Booking Details</h2>
    <table>
        <tr>
            <th>Hotel</th>
            <th>Room Type</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Nights</th>
            <th>Rooms</th>
        </tr>
        <tr>
            <td>{{ $booking->hotel->name ?? 'Unknown Hotel' }}</td>
            <td>{{ $booking->roomType->name ?? 'Standard Room' }}</td>
            <td>{{ $booking->enter_date->format('M d, Y') }}</td>
            <td>{{ $booking->leave_date->format('M d, Y') }}</td>
            <td class="text-center">{{ $nightsCount }}</td>
            <td class="text-center">{{ $booking->rooms_number }}</td>
        </tr>
    </table>
    
    <h2>Pricing Information</h2>
    <table>
        <tr>
            <th>Description</th>
            <th>Price per Night</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>Room Rate ({{ $booking->rooms_number }} room(s) × {{ $nightsCount }} night(s))</td>
            <td class="text-right">${{ number_format($booking->client_sell_price, 2) }}</td>
            <td class="text-right">${{ number_format($totalClientPrice, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="text-right"><strong>Total Due</strong></td>
            <td class="text-right"><strong>${{ number_format($totalClientPrice, 2) }}</strong></td>
        </tr>
    </table>
    
    <h2>Payment Status</h2>
    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Total Booking Value</td>
            <td class="text-right">${{ number_format($totalClientPrice, 2) }}</td>
        </tr>
        <tr>
            <td>Payments Received</td>
            <td class="text-right">${{ number_format($totalClientPrice - $clientRemainingBalance, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Balance Due</strong></td>
            <td class="text-right"><strong>${{ number_format($clientRemainingBalance, 2) }}</strong></td>
        </tr>
    </table>
    
    @if($booking->note)
    <h2>Special Notes</h2>
    <p>{{ $booking->note }}</p>
    @endif
    
    @if($booking->bookingSource)
    <div style="margin-top: 30px;">
        <div><span class="label">Booking Source:</span> {{ $booking->bookingSource->name }}</div>
    </div>
    @endif
    
    <div class="footer">
        <p>Thank you for your booking! If you have any questions, please contact us.</p>
        <p>Booking #{{ $bookingNumber }} - Generated on {{ now()->format('M d, Y h:i A') }}</p>
    </div>
</body>
</html>