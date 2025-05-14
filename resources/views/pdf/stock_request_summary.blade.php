<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Request Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Stock Request Notifications</h1>
    <p><strong>Summary Date:</strong> {{ \Carbon\Carbon::yesterday()->format('l, F j, Y') }}</p>
    {{-- <p><strong>Current Stock Level As Of:</strong> {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p> --}}

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Date Notification Requested</th>
                <th>Current Stock Level</th>
                <th>User Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product_stock_notification_users as $notification)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $notification->product->name ?? 'N/A' }}</td>
                    <td>{{ $notification->product->code ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}</td>
                    <td>{{ $notification->product->options[0]->stockAvailable ?? 'N/A' }}</td>
                    <td>{{ $notification->email ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No stock requests found for the previous day.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        This report was generated automatically by the system.
    </div>
</body>
</html>
