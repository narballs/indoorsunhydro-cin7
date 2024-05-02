<!-- resources/views/pdf/stock_request.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Request Notifications</title>
    <style>
        /* Define your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Stock Request Notifications</h1>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Product Name</th>
                <th>Sku</th>
                <th>Date Notification Requested</th>
                <th>Current Stock Level as of "Date"</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product_stock_notification_users as $notification)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$notification->product->name}}</td>
                    <td>{{$notification->product->code}}</td>
                    <td>{{$notification->created_at}}</td>
                    <td>{{$notification->product->options[0]->stockAvailable}}</td>
                    <td>{{$notification->email}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td align="center">
                    <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
                    <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="" />
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
