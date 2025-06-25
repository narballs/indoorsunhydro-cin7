<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            background-color: #dee2e6;
            font-family: Arial, sans-serif;
        }

        .main-div {
            width: 100%;
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
        }

        table.bordered {
            width: 100%;
            border-collapse: collapse;
            background-color: #FFF;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table.bordered th, table.bordered td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .buy-now-btn {
            background: #7CC633;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
        }

        img {
            max-height: 120px;
        }
    </style>
</head>
<body>
    <div class="main-div">
        <h2 style="text-align: center;">Re-Order Reminder</h2>
        <p>Hello {{ $name ?? 'Customer' }},</p>
        <p>This is a friendly reminder about your previous order. You can reorder the same items by clicking the button at the bottom.</p>

        <table class="bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp

                @foreach($order_data->apiOrderItem as $apiOrderItem)
                    @php
                        $retail_price = 0;
                        $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                        $product = $apiOrderItem->product;
                        $option = $apiOrderItem->product_option;
                        foreach ($option->price ?? [] as $price) {
                            $retail_price = $price->$user_price_column ?? 0;
                            if (!$retail_price) $retail_price = $price->sacramentoUSD ?? 0;
                            if (!$retail_price) $retail_price = $price->retailUSD ?? 0;
                        }
                        $subtotal = $retail_price * $apiOrderItem->quantity;
                        $grandTotal = $subtotal + 
                        ($order_data->tax_rate ?? 0) + 
                        ($order_data->shipment_price ?? 0) - 
                        ($order_data->buylist_discount ?? 0) - 
                        ($order_data->discount_amount ?? 0);
                    @endphp

                    <tr>
                        <td>
                            @if (!empty($product->images))
                                <img src="{{ $product->images }}" alt="{{ $product->name }}" width="100">
                            @else
                                <img src="{{ asset('theme/img/image_not_available.png') }}" alt="N/A" width="100">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->code }}</td>
                        <td>${{ number_format($retail_price, 2) }}</td>
                        <td>{{ $apiOrderItem->quantity }}</td>
                        <td>${{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach

                <!-- Totals -->
                <tr>
                    <td colspan="5" style="text-align:right;"><strong>Tax:</strong></td>
                    <td>${{ number_format($order_data->tax_rate ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:right;"><strong>Shipping:</strong></td>
                    <td>${{ number_format($order_data->shipment_price ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:right;"><strong>Discount:</strong></td>
                    <td>
                        @php
                            $discount = $order_data->buylist_id
                                ? ($order_data->buylist_discount ?? 0)
                                : ($order_data->discount_amount ?? 0);
                        @endphp
                        ${{ number_format($discount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:right;"><strong>Grand Total:</strong></td>
                    <td>${{ number_format($order_data->total_including_tax ?? $grandTotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center; padding-top: 20px;">
                        <a href="{{ $link }}" class="buy-now-btn">Buy Now</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 40px;">
            <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
            <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="" />
        </p>
    </div>
</body>
</html>
