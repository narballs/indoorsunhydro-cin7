<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 816px;
            margin: auto;
            color: #333;
            padding: 20px;
        }

        .header {
            background-color: #808080;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            color: #FFFFFF;
        }

        .company-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .shipping-info,
        .order-details {
            width: 48%;
            vertical-align: top;
            display: inline-block;
            font-size: 12px;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .table-container th, .table-container td {
            border: 1px solid #808080;
            padding: 5px;
            text-align: left;
        }

        .table-container th {
            background-color: #808080;
            color: #FFFFFF;
            font-weight: bold;
        }

        .summary {
            width: 100%;
            margin-top: 10px;
        }

        .summary p {
            margin: 0;
            text-align: right;
            font-size: 12px;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="packing_slip_main_div">
        <div class="header">Packing Slip</div>
        @php
            $website_name = App\Helpers\SettingHelper::getSetting('website_name');
            $address1 = App\Helpers\SettingHelper::getSetting('store_address_line_1');
            $address2 = App\Helpers\SettingHelper::getSetting('store_address_line_2');
            $sub_total = 0;
        @endphp

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 70%;">
                    <p><strong>{{$website_name}} INC</strong></p>
                    <p>{{$address1}}</p>
                    <p>{{$address2}}</p>
                </td>
                <td style="width: 30%;vertical-align:top;">
                    @php
                        $logo_name = \App\Helpers\SettingHelper::getSetting('logo_name'); 
                    @endphp
                    <img src="{{ url('/theme/img/' . $logo_name) }}" alt="logo" style="width: 100%;">
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td class="shipping-info">
                    <p><strong>Ship To:</strong></p>
                    <p>{{$name}}</p>
                    @if (!empty($street1))
                        <p>{{$street1}}</p>
                    @endif
                    @if (!empty($street2))
                        <p>{{$street2}}</p>
                    @endif
                    <p>{{$city}}, {{$state}} {{$postalCode}} US</p>
                    <p>{{$phone}}</p>
                </td>
                <td class="order-details">
                    <p><strong>Order #</strong> {{$order_id}}</p>
                    <p><strong>Order Date</strong>: {{ \Carbon\Carbon::parse($orderDate)->format('F d, Y') }}</p>
                    {{-- <p><strong>Order Date</strong>: {{ $orderDate}}</p> --}}
                    {{-- <p><strong>Order Reference</strong>: {{$reference}}</p>
                    <p><strong>Ship Date</strong>: {{$shipDate}}</p> --}}
                </td>
            </tr>
        </table>

        @if (!empty($order_items))
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Sku</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Ext. Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order_items as $item)
                    @php
                        $sub_total += intval($item['quantity']) * floatval($item['unitPrice']);
                    @endphp
                    <tr>
                        <td>{{$item['sku']}}</td>
                        <td>{{$item['name']}}</td>
                        <td>{{$item['quantity']}}</td>
                        <td>${{number_format($item['unitPrice'] , 2)}}</td>
                    </tr>
                    @endforeach  
                </tbody>          
            </table>
        @endif

        <div class="summary">
            <p style="margin-bottom:5px;">Sub Total: ${{ number_format($sub_total , 2) }}</p>
            <p style="margin-bottom:5px;">Tax: ${{ number_format($taxAmount , 2) }}</p>
            <p style="margin-bottom:5px;">Shipping: ${{ number_format($shippingAmount , 2) }}</p>
            <p class="total">Total: ${{ number_format($orderTotal , 2) }}</p>
        </div>
    </div>
</body>
</html>
