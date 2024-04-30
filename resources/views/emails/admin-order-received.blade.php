<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link rel="stylesheet" href="/theme/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.css">
    <link rel="noopener" target="_blank" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap"
        rel="stylesheet">

    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="{{ asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css') }}" rel="stylesheet"
        id="bootstrap-css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    @font-face {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        src: url(https://fonts.gstatic.com/s/poppins/v20/pgitxiEyp8kv8JHgFVrJJbecmNE.woff2) format('woff2');
    }
</style>
<body bgcolor="#000000" style="background-color: #000000;padding-top:5px;padding-bottom:5px;padding-right:0px;padding-left:0px;" width="100%">
    <table padding="0" bgcolor="#FFFFFF" style="background-color:#FFFFFF;border-radius:5px;" width="50%" border="0" align="center" cellpadding="0">
        <tbody>
            <tr>
                <td>
                    <table width="80%" border="0" align="center">
                        <tr>
                            <td align="" style="text-align: center;">
                                <img class="img-fluid "
                                    src="{{ url('/theme/img/email/approve_email_icon.png') }}">
                            </td>
                        </tr>
                        @if (!empty($addresses['order_status']) && $addresses['order_status'] === 'updated')
                            
                            @if (!empty($addresses['new_order_status']) && (!empty($addresses['previous_order_status'])))
                                <tr>
                                    <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 11px;font-weight:400;">
                                        Your order #{{ $addresses['order_id'] }} status has been updated from <b>{{$addresses['previous_order_status']}}</b> to <b>{{$addresses['new_order_status']}}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 11px;font-weight:400;">
                                        Your order #{{ $addresses['order_id'] }} status has been updated.
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 18px;font-weight:bold;">
                                    We received your  order #{{ $addresses['order_id'] }} !
                                </td>
                            </tr>
                            <tr>
                                <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 11px;font-weight:400;">
                                    Your order #{{ $addresses['order_id'] }} is completed and ready to ship
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 11px;font-weight:400;">
                                Suggestions , Comments and website feature requests . <a href="{{url('/contact-us')}}">Please click here to contact us</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td>
                    <table width="100%" align="left" border="0">
                        <tr>
                            <td width="40%" style="vertical-align: top;">
                                <table border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                                BILLING ADDRESS
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                {{ $addresses['billing_address']['firstName'] .' ' . ' '.$addresses['billing_address']['lastName'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['shipping_address']['postalAddress1'] != null)
                                                {{$addresses['shipping_address']['postalAddress1']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['shipping_address']['postalAddress2'] != null)
                                                {{$addresses['shipping_address']['postalAddress2']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['shipping_address']['postalCity'] != null)
                                                {{$addresses['shipping_address']['postalCity']}}
                                                @endif
                                                @if ($addresses['shipping_address']['postalState'] != null)
                                                {{$addresses['shipping_address']['postalState']}}
                                                @endif
                                                @if ($addresses['shipping_address']['postalPostCode'] != null)
                                                {{$addresses['shipping_address']['postalPostCode']}}
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['shipping_address']['postalState'] != null)
                                                {{$addresses['shipping_address']['postalState']}}
                                                @endif
                                                @if ($addresses['shipping_address']['postalPostCode'] != null)
                                                {{$addresses['shipping_address']['postalPostCode']}}
                                                @endif
                                            </td>
                                        </tr> --}}
                                        {{-- <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['shipping_address']['postalPostCode'] != null)
                                                {{$addresses['shipping_address']['postalPostCode']}}
                                                @endif
                                            </td>
                                        </tr> --}}
                                        <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['mobile'] != null)
                                                {{$addresses['billing_address']['mobile']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['phone'] != null)
                                                {{$addresses['billing_address']['phone']}}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td align="center" width="40%" style="vertical-align: top;">
                                <table border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                                SHIPPING ADDRESS
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                {{ $addresses['billing_address']['firstName'] .' ' . ' '.$addresses['billing_address']['lastName'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['billing_address']['address1'] != null)
                                                {{$addresses['billing_address']['address1']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['billing_address']['address2'] != null)
                                                {{$addresses['billing_address']['address2']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['billing_address']['city'] != null)
                                                {{$addresses['billing_address']['city']}}
                                                @endif
                                                @if ($addresses['billing_address']['state'] != null)
                                                {{$addresses['billing_address']['state']}}
                                                @endif
                                                @if ($addresses['billing_address']['zip'] != null)
                                                {{$addresses['billing_address']['zip']}}
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['billing_address']['state'] != null)
                                                {{$addresses['billing_address']['state']}}
                                                @endif
                                                @if ($addresses['billing_address']['zip'] != null)
                                                {{$addresses['billing_address']['zip']}}
                                                @endif
                                            </td>
                                        </tr> --}}
                                        {{-- <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['zip'] != null)
                                                {{$addresses['billing_address']['zip']}}
                                                @endif
                                            </td>
                                        </tr> --}}
                                        <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['mobile'] != null)
                                                {{$addresses['billing_address']['mobile']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['phone'] != null)
                                                {{$addresses['billing_address']['phone']}}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            
                            <td align="right" width="20" style="vertical-align:top;">
                                <table border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                                Company Name
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                {{!empty($addresses['company']) ? $addresses['company'] : ''}}  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                                PAYMENT INFO
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                {{ $addresses['payment_terms'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                                Delievery Option
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if (!empty($addresses['delievery_method']) && strtolower($addresses['delievery_method']) == 'pickup order')
                                                    {{ !empty($addresses['delievery_method']) ? $addresses['delievery_method'] : '' }}
                                                    <br/><span class="" style="margin-left: 10px;">
                                                        (Monday - Friday 9:00 AM - 5:00 PM only)
                                                    </span>
                                                @else
                                                    {{ !empty($addresses['delievery_method']) ? $addresses['delievery_method'] : '' }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                    Order Items
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" align="left" border="0">
                        @foreach ($order_items as $item)
                            @foreach ($item['product']->options as $option)
                                <tr>
                                    <td width="20%">
                                        <img src="{{ asset($option->image) }}" width="55" height="49" alt="" style="border:5px solid #d9d9d9;border-radius:2px;">
                                    </td>
                                    <td style="vertical-align:top;">
                                        <table width="100%" align="left" border="0">
                                            <tr>
                                                <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">{{ $item['product']->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">SKU: {{ $item['product']->code }}</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="20%" style="color:#000000;font-color:#000000;font-size: 12px;vertical-align:top; font-weight:400;">{{ $item->quantity . 'x' }}</span></td>
                                    <td width="20%" style="text-align: right;vertical-align:top;color:#000000;font-color:#000000;font-size: 12px; font-weight:400; ">{{ '$'. number_format($item->price, 2) }}</td>
                                </tr>
                                
                            @endforeach
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0">
                        <tr>
                            <td width="50%" style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                Sub Total
                            </td>
                            <td align="right" style="text-align: right;color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                @php
                                    $sub_total = 0;
                                    foreach ($order_items as $item) {
                                        $sub_total += $item->price;
                                    }
                                @endphp
                                {{-- {{'$'. number_format($sub_total, 2) }} --}}
                                {{ '$'.number_format($currentOrder->total, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0">
                        <tr>
                            <td width="50%" style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                @php
                                    $taxt_rate = 0;
                                    foreach ($order_items as $order_item) {
                                        $taxt_rate = $order_item->order->texClasses->name;
                                    }
                                @endphp
                                Tax ({{ $taxt_rate }})
                            </td>
                            <td align="right" style="text-align: right;color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                @php
                                    $tax = 0;
                                    foreach ($order_items as $item) {
                                        $tax += ($item->price * $item->order->texClasses->rate) / 100;
                                    }
                                @endphp
                                {{-- {{'$'. number_format($tax, 2) }} --}}
                                {{ '$'.number_format($currentOrder->tax_rate, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                Shipping
                            </td>
                            <td align="right" style="text-align: right;color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                {{ '$'.number_format($currentOrder->shipment_price, 2) }} 
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                Discount
                            </td>
                            <td align="right" style="text-align: right;color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                {{ '$'.number_format($currentOrder->discount_amount, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0">
                        <tr>
                            <td width="50%" style="color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                Total
                            </td>
                            <td align="right" style="text-align: right;color:#000000;font-color:#000000;font-size: 14px; font-weight:600;">
                                @php
                                    $total = 0;
                                    foreach ($order_items as $item) {
                                        $total = $item['order']->total_including_tax;
                                    }
                                @endphp
                                {{-- {{ '$'.number_format($total, 2) }} --}}
                                {{ '$'.number_format($currentOrder->total_including_tax, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
                    <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="" />
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
