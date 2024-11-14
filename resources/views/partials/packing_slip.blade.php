<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip</title>
    {{-- <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet"> --}}
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap'); */

        /* @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/poppins/v20/pgitxiEyp8kv8JHgFVrJJbecmNE.woff2) format('woff2');
        } */
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
            font-weight: 500;
            color: #FFFFFF;
        }

        .company-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .company-info p {
            margin: 0;
        }

        .shipping-info,
        .order-details {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .order-details {
            text-align: right;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-container th {
            border: 1px solid #808080;
            padding: 5px;
            text-align: left;
            color: #FFFFFF;
            font-weight: 500;
            border: 1px solid #808080;
        }
        .table-container td {
            /* border: 1px solid #808080; */
            padding: 5px;
            /* text-align: center; */
            font-weight: 500;

        }

        .table-container th {
            background-color: #808080;
        }

        .table-container tr {
            border-bottom: 2px solid #A3A3A3;
            border-left: 2px solid #A3A3A3;
            border-right: 2px solid #A3A3A3;

        }

        .summary {
            width: 100%;
            margin-top: 10px;
        }

        .summary p {
            display: flex;
            justify-content: flex-end;
            margin: 0;
        }

        .total {
            font-weight: bold;
        }
    </style>
    
</head>

<body>
    <div class="packing_slip_main_div">
        <div class="header">Packing Slip # {{$order_id}}</div>
        @php
            $website_name = App\Helpers\SettingHelper::getSetting('website_name');
            $address1 = App\Helpers\SettingHelper::getSetting('store_address_line_1');
            $address2 = App\Helpers\SettingHelper::getSetting('store_address_line_2');
            $sub_total = 0;
        @endphp
        <div class="" style="display: flex;">
            <div class="company-info" style="width:75%;">
                <p><strong>{{$website_name}} INC</strong></p>
                <p>{{$address1}}</p>
                <p>{{$address2}}</p>
            </div>
            <div class="" style="width:25%;">
                <img class="" src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}" ;
                    style="width:100%;">
            </div>
        </div>

        <div class="order-info" style="display: flex; justify-content: center;">
            <div style="display: flex; width: 100%; max-width: 600px; justify-content: space-between;">
                <!-- Shipping Info Section -->
                <div class="shipping-info" style="width: 50%;">
                    <p><strong>Ship To:</strong></p>
                    <p>{{$name}}</p>
                    <p>{{!empty($street1) ? $street1 : ''}}</p>
                    <p>{{!empty($street2) ? $street2 : ''}}</p>
                    <p>{{$city}}, {{$state}} {{$postalCode}} US</p>
                </div>
        
                <!-- Order Details Section -->
                <div class="" style="width: 50%; display: flex;">
                    <div class="order-details" style="width: 50%;padding:5px;">
                        <div style="border-right:1px solid #ddd;padding-right:5px;">
                            <p><strong>Order #</strong></p>
                            <p><strong>Order Reference</strong></p>
                            <p><strong>Date</strong></p>
                            <p><strong>Ship Date</strong></p>
                        </div>
                    </div>
                    <div class="order-details-dates" style="width: 50%;padding:5px;">
                        <p>{{$order_id}}</p>
                        <p>{{$reference}}</p>
                        <p>
                            {{ \Carbon\Carbon::parse($orderDate)->format('Y-m-d H:i:s') }}
                        </p>
                        <p>{{$shipDate}}</p>
                    </div>
                </div>
            </div>
        </div>
        
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
                        <td>{{$item['unitPrice']}}</td>
                    </tr>
                    @endforeach  
                </tbody>          
            </table>
        @endif

        <div class="" style="display: flex;justify-content:flex-end">
            <div class="" style="width: 20%;">
                <p><span style="margin-right:10px;">Sub Total:</span> <span> ${{number_format($sub_total , 2)}}</span></p>
                <p><span style="margin-right:10px;">Tax:</span> <span> ${{number_format($taxAmount , 2)}}</span></p>
                <p><span style="margin-right:10px;">Shipping:</span> <span> ${{number_format($shippingAmount , 2)}}</span></p>
                <p class="total"><span  style="margin-right:10px;">Total:</span> <span> ${{number_format($orderTotal , 2)}}</span></p>
            </div>
        </div>
    </div>
</body>

</html>