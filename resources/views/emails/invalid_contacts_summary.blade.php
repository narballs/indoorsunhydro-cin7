<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invalid Contacts Summary Sync Fron Cin7</title>
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
            padding: 6px;
            font-size: 12px;
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
    <h1>Invalid Contacts Summary Sync Fron Cin7</h1>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Billing Adress 1</th>
                <th>Billing Adress 2</th>
                <th>Billing City</th>
                <th>Billing State</th>
                <th>Billing Zip Code</th>
                <th>Shipping Adress 1</th>
                <th>Shipping Adress 2</th>
                <th>Shipping City</th>
                <th>Shipping State</th>
                <th>Shipping Zip Code</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invalid_contacts as $invalid_contact)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ !empty($invalid_contact->email) ? $invalid_contact->email : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->firstName) ? $invalid_contact->firstName : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->lastName) ? $invalid_contact->lastName : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->billing_address_1) ? $invalid_contact->billing_address_1 : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->billing_address_2) ? $invalid_contact->billing_address_2 : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->billing_city) ? $invalid_contact->billing_city : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->billing_state) ? $invalid_contact->billing_state : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->billing_postal_code) ? $invalid_contact->billing_postal_code : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->shipping_address_1) ? $invalid_contact->shipping_address_1 : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->shipping_address_2) ? $invalid_contact->shipping_address_2 : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->shipping_city) ? $invalid_contact->shipping_city : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->shipping_state) ? $invalid_contact->shipping_state : 'N/A' }}</td>
                    <td>{{ !empty($invalid_contact->shipping_postal_code) ? $invalid_contact->shipping_postal_code : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">
                        No invalid contacts found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        This report was generated automatically by the system.
    </div>
</body>
</html>
