<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip</title>
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/poppins/v20/pgitxiEyp8kv8JHgFVrJJbecmNE.woff2) format('woff2');
        }
        body {
            font-family:'poppins', sans-serif;
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
        <div class="header">Packing Slip</div>
        <div class="" style="display: flex;">
            <div class="company-info" style="width:75%;">
                <p><strong>Indoor Sun Hydro INC</strong></p>
                <p>5671 Warehouse Way</p>
                <p>Sacramento, CA 95826</p>
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
                    <p>Erik Chavez</p>
                    <p>775 W 3RD ST</p>
                    <p>POMONA, CA 91766-1525 US</p>
                </div>
        
                <!-- Order Details Section -->
                <div class="" style="width: 50%; display: flex;">
                    <div class="order-details" style="width: 50%;padding:5px;">
                        <div style="border-right:1px solid #ddd;padding-right:5px;">
                            <p><strong>Order #</strong></p>
                            <p><strong>Date</strong></p>
                            <p><strong>Ship Date</strong></p>
                        </div>
                    </div>
                    <div class="order-details-dates" style="width: 50%;padding:5px;">
                        <p>3248</p>
                        <p>11/12/2024</p>
                        <p>11/14/2024</p>
                    </div>
                </div>
            </div>
        </div>
        

        <table class="table-container">
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Ext. Price</th>
            </tr>
            <tr>
                <td>HGC724464</td>
                <td>AzalMax 4 oz</td>
                <td>$45.00</td>
                <td>3</td>
                <td>$135.00</td>
            </tr>
        </table>

        <div class="" style="display: flex;justify-content:flex-end">
            <div class="" style="width: 20%;">
                <p><span style="margin-right:10px;">Sub Total:</span> <span> $135.00</span></p>
                <p><span style="margin-right:10px;">Tax:</span> <span> $11.81</span></p>
                <p><span style="margin-right:10px;">Shipping:</span> <span> $16.86</span></p>
                <p class="total"><span  style="margin-right:10px;">Total:</span> <span> $163.67</span></p>
            </div>
        </div>
    </div>
</body>

</html>