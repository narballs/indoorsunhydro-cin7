<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link rel="stylesheet" href="/theme/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #000;
        padding: 5px;
    }

    table {
        background-color: #FFFFFF;
        border-radius: 5px;
    }
</style>

<body style="padding-top: 20px;padding-bottom: 0px;padding-right:0px;padding-left:0px;" width="100%;">
    <table padding="0" bgcolor="#FFFFFF" style="background-color:#FFFFFF;border-radius:5px;border: 1px solid #EBEBEB;" width="50%" border="0" align="center" cellpadding="0">
        <tbody>
            <tr>
                <td>
                    <table width="80%" border="0" align="center">
                        <!-- Header Icon -->
                        <tr>
                            <td align="center">
                                <img class="img-fluid" src="{{ url('/theme/img/email/approve_email_icon.png') }}" alt="Approval Icon" style="margin-top: 10px;">
                            </td>
                        </tr>

                        <!-- Subject -->
                        <tr>
                            <td align="center" style="color:#000000;font-size: 18px;font-weight: 600;padding-top: 10px;">
                                <b>{{ !empty($subject) ? $subject : '' }}</b>
                            </td>
                        </tr>

                        <!-- Message Body -->
                        <tr>
                            <td align="center" style="padding-top: 10px;padding-bottom: 20px;">
                                <p style="color:#000000;font-size: 14px;font-weight: 400;">
                                    Shipment label for order #{{ $content['order_id'] }} is attached.
                                </p>
                            </td>
                        </tr>

                        <!-- Shipping Address -->
                        <tr>
                            <td style="padding: 10px 20px; color: #000;">
                                <h4 style="font-size: 16px; font-weight: 600;">Shipping to:</h4>
                                <p style="font-size: 14px; margin: 0;">
                                    @if(!empty($content['name']))
                                        Name: {{ $content['name'] }}<br/>
                                    @endif
                                    Company: {{ !empty($content['company']) ? $content['company'] : '' }}<br/>
                                    @if(!empty($content['street1']))
                                        Address: {{ $content['street1'] }}<br/>
                                    @endif
                                    @if(!empty($content['street2']))
                                        {{ $content['street2'] }}<br/>
                                    @endif
                                    @if(!empty($content['city']))
                                        {{ $content['city'] }},
                                    @endif
                                    @if(!empty($content['state']))
                                        {{ $content['state'] }}
                                    @endif
                                    @if(!empty($content['postalCode']))
                                        {{ $content['postalCode'] }}
                                    @endif
                                    <br/>
                                    @if(!empty($content['phone']))
                                        Phone: {{ $content['phone'] }}<br/>
                                    @endif
                                    @if(!empty($content['country']))
                                        Country: {{ $content['country'] }}<br/>
                                    @endif
                                </p>
                            </td>
                        </tr>

                        @php
                            $tracking_url = null;
                            $order =  App\Models\ApiOrder::where('id', $content['order_id'])->first();

                            if (!empty($order) && !empty($order->tracking_number)) {
                                if ($order->shipping_carrier_code === 'ups_walleted') {
                                    $tracking_url = 'https://www.ups.com/track?HTMLVersion=5.0&Requester=NES&AgreeToTermsAndConditions=yes&loc=en_US&tracknum=' . $order->tracking_number;
                                } elseif ($order->shipping_carrier_code === 'stamps_com') {
                                    $tracking_url = 'https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=' . $order->tracking_number;
                                }
                            }
                        @endphp

                        @if ($tracking_url)
                            <tr>
                                <td align="center" style="padding-top: 20px; padding-bottom: 25px;">
                                    <a href="{{ $tracking_url }}" target="_blank" 
                                    style="background-color: #007bff; color: #fff; padding: 8px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                                    Track Your Order
                                    </a>
                                </td>
                            </tr>
                        @endif

                        <!-- Order Items -->
                        <tr>
                            <td style="padding: 10px 20px; color: #000;">
                                @if (!empty($order_items))
                                    <h4 style="font-size: 16px; font-weight: 600;">Order Items:</h4>
                                    <ul style="font-size: 14px; padding-left: 20px;">
                                        @foreach ($order_items as $item)
                                            @if (!empty($item['quantity']) && !empty($item['name']) && !empty($item['sku']))
                                                <li>{{ $item['quantity'] }} x {{ $item['name'] }} (SKU: {{ $item['sku'] }})</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <!-- Footer with Logo -->
                        <tr>
                            <td align="center">
                                <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
                                <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="Email Logo" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
