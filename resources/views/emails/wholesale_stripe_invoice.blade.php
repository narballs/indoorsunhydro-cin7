<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stripe Invoice</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 10px;
                padding: 5px;
                background-color: #f8f9fa;
                color: #333;
                font-size: 16px;
            }
            .container {
                max-width: 1000px;
                margin: 20px auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .header {
                margin-bottom: 20px;
            }
            .header img {
                width: 50px;
            }
            .header h1 {
                margin: 10px 0;
                color: #333;
                font-weight: 900;
                font-style: italic;
            }
            .details {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            .details div {
                flex: 1 1 100%;
                margin-bottom: 10px;
            }
            @media (min-width: 600px) {
                .details div {
                    flex: 1;
                    margin-right: 10px;
                }
                .details div:last-child {
                    margin-right: 0;
                }
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                overflow-x: auto;
                display: block;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
                white-space: nowrap;
            }
            th {
                font-weight: bold;
            }
            .total {
                text-align: right;
                font-weight: bold;
            }
            .button {
                text-align: center;
                margin-top: 20px;
            }
            .button a {
                text-decoration: none;
                color: #fff;
                background-color: #635bff;
                padding: 10px 20px;
                border-radius: 5px;
                display: inline-block;
            }

            .logo_div {
                width: 25%;
            }
            .logo_div img {
                width: 50%;
            }
            .payment_breakdown_table td {
                text-align: right;
            }
            img {
                max-width: 100%;
                height: auto;
            }
            @media (max-width: 768px) {
                body {
                    font-size: 14px;
                }
                h1, h2 {
                    font-size: 1.5rem;
                }
                .container {
                    padding: 15px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="logo_div">
                    <img src="{{ asset('/theme/bootstrap5/images/stripe_payment_logo_new.png') }}" alt="Stripe Logo">
                </div>
                <h1>Congratulations,</h1>
            </div>

            <div class="details">
                <div>
                    <p><strong>Order ID:</strong> {{ optional($invoice)->id ?? 'N/A' }}</p>
                    <p><strong>Payment ID:</strong> {{ optional($invoice)->payment_intent ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Created Date:</strong> {{ optional($invoice)->created ? \Carbon\Carbon::parse($invoice->created)->format('M d, h:i A') : 'N/A' }}</p>
                    <p><strong>Payment Method:</strong> **** {{ optional($invoice)->payment_method_details->card->last4 ?? 'N/A' }}</p>
                </div>
            </div>

            <h2>Checkout Summary</h2>
            <table>
                <tr>
                    <th>Customer</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                <tr>
                    <td>{{ optional($invoice)->customer_email ?? 'N/A' }}</td>
                    <td>{{ optional($invoice)->customer_name ?? 'N/A' }}</td>
                    <td>{{ optional($invoice)->customer_email ?? 'N/A' }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th>Items</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
                @foreach(optional($invoice)->lines->data ?? [] as $item)
                    <tr>
                        <td>{{ $item->description ?? 'N/A' }}</td>
                        <td>{{ $item->quantity ?? 'N/A' }}</td>
                        <td>${{ number_format($item->amount / 100, 2) ?? '0.00' }}</td>
                        <td>${{ number_format($item->amount * $item->quantity / 100, 2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td>${{ number_format(optional($invoice)->amount_paid / 100, 2) }}</td>
                </tr>
            </table>

            <h2>Payment Breakdown</h2>
            <table class="payment_breakdown_table">
                <tr>
                    <th>Payment Amount</th>
                    <td>${{ number_format(optional($invoice)->amount_paid / 100, 2) }} USD</td>
                </tr>
                <tr>
                    <th>Stripe Processing Fees</th>
                    <td>-${{ number_format(optional($invoice)->fee / 100, 2) }} USD</td>
                </tr>
                <tr>
                    <th>Net Amount</th>
                    <td>${{ number_format((optional($invoice)->amount_paid - optional($invoice)->fee) / 100, 2) }} USD</td>
                </tr>
            </table>
        </div>
    </body>
</html>
