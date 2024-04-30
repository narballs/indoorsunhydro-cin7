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
                <th>
                    S.No
                </th>
                <th>Product Name</th>
                <th>Sku</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$notification['product']['name']}}</td>
                <td>{{$notification['product']['code']}}</td>
                <td>{{$notification['email']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
