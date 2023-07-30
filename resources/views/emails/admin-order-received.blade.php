<!DOCTYPE html>
<html lang="en">
{{-- {{ dd($order_items) }} --}}

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


{{-- <body>
    <table bgcolor="#000000" style="width:100% !important;">
        <div class="container-fluid" style="background-color: #000!important; width:100% !important; padding-top: 36px !important; padding-bottom: 36px !important;">
           
                <div class="container" style="width: 586px !important; margin:auto !important; background-color:#ffffff !important; margin-bottom: 12px !important; padding-bottom: 20px !important; border-radius: 10px !important;">
                <p style=" text-align: center; margin-top:1rem;">
                    <img class="img-fluid" src="{{ asset('https://stage.indoorsunhydro.com/theme/img/email/template_icon.png') }}" alt="" style="margin-top: 33px !important;">
                </p>
                <p style="text-align: center; font-family: 'Poppins'!important; font-style: normal!important; font-weight: 600!important; font-size: 18px!important; line-height: 27px!important; color: #282828!important;margin-top: 0px !important; margin-bottom: 0px !important;">
                    We received your order!
                </p>
                <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 400 !important;font-size: 11px !important;line-height: 16px !important;text-align: center;color: #6C6C6C !important;padding-bottom: 27px !important;border-bottom: 1px solid #EBEBEB !important;margin-left: 51px!important;margin-right: 31px!important;margin-top: 0px !important;">
                    Your order #{{ $addresses['order_id'] }} is completed and ready to ship
                </p>
                <div style="display: flex !important;border-bottom: 1px solid #EBEBEB !important;padding-bottom: 37px !important;margin-left: 53px!important; margin-right: 26px !important; background-color:#ffffff !important;">
                    
                                <div style=" width: 50% !important;">
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 10px !important;line-height: 15px !important;letter-spacing: 0.09em !important;text-transform: uppercase !important;color:#E8E8E !important;margin-bottom: 0px !important;">
                                        Shipping Address
                                    </p>
                                    <br>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;">
                                        {{ $addresses['billing_address']['firstName'] }}
                                        &nbsp;&nbsp;&nbsp;{{ $addresses['billing_address']['lastName'] }}
                                    </p>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;">
                                        @if ($addresses['billing_address']['address1'] == null)
                                            <span style="color:skyblue !important; padding: 5px !important;border-radius: 5px !important;margin-bottom: 12px !important;">
                                                empty
                                            </span>
                                        @else
                                            {{ $addresses['billing_address']['address1'] }}
                                        @endif
            
                                    </p>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['address2'] == null)
                                            <span style="color:skyblue !important; padding: 5px !important;border-radius: 5px !important;margin-bottom: 12px !important;">
                                                empty
                                            </span>
                                        @else
                                            {{ $addresses['billing_address']['address2'] }}
                                        @endif
                                    </p>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['city'] == null)
                                            <span style="color:skyblue !important; padding: 5px !important;border-radius: 5px !important;margin-bottom: 12px !important;">
                                                empty
                                            </span>
                                        @else
                                            {{ $addresses['billing_address']['city'] }}
                                        @endif
                                    </p>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['state'] == null)
                                            <span style="color:skyblue !important; padding: 5px !important;border-radius: 5px !important;margin-bottom: 12px !important;">
                                                empty
                                            </span>
                                        @else
                                            {{ $addresses['billing_address']['state'] }}
                                        @endif
                                    </p>
                                    <p style="font-family: 'Poppins' !important;font-style: normal !important;font-weight: 500 !important;font-size: 11px !important;line-height: 16px !important;color: #282828 !important;margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['zip'] == null)
                                            <span style="color:skyblue !important; padding: 5px !important;border-radius: 5px !important;margin-bottom: 12px !important;">
                                                empty
                                            </span>
                                        @else
                                            {{ $addresses['billing_address']['zip'] }}
                                        @endif
                                    </p>
                                    <p
                                        style="font-family: 'Poppins' !important;
                                                font-style: normal !important;
                                                font-weight: 500 !important;
                                                font-size: 11px !important;
                                                line-height: 16px !important;
                                                color: #282828 !important;
                                                margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['mobile'] == null)
                                            <span
                                                style="
                                                    color:skyblue !important; 
                                                    padding: 5px !important;
                                                    border-radius: 5px !important;
                                                ">
                                                empty</span>
                                        @else
                                            {{ $addresses['billing_address']['mobile'] }}
                                        @endif
                                    </p>
                                    <p
                                        style="font-family: 'Poppins' !important;
                                                font-style: normal !important;
                                                font-weight: 500 !important;
                                                font-size: 11px !important;
                                                line-height: 16px !important;
                                                color: #282828 !important;
                                                margin-bottom: 0px !important;">
                                        @if ($addresses['billing_address']['phone'] == null)
                                            <span
                                                style=";
                                                color:skyblue !important; 
                                                padding: 5px !important;
                                                border-radius: 5px !important;
                                                ">
                                                empty</span>
                                        @else
                                            {{ $addresses['billing_address']['phone'] }}
                                        @endif
                                    </p>
                                </div>
                           
                                <div style="width: 50% !important;">
                                    <p
                                        style="font-family: 'Poppins' !important;
                                            font-style: normal !important;
                                            font-weight: 500 !important;
                                            font-size: 10px !important;
                                            line-height: 15px !important;
                                            letter-spacing: 0.09em !important;
                                            text-transform: uppercase !important;
                                            color: #8E8E8E !important;
                                            margin-left: 39px !important;
                                            padding-bottom: 17px !important;
                                        ">
                                        Payment Info
                                    </p>
                                    <span
                                        style="
                                            font-family: 'Poppins' !important;
                                            font-style: normal !important;
                                            font-weight: 500 !important;
                                            font-size: 11px !important;
                                            line-height: 16px !important;
                                            color: #282828 !important;
                                            margin-left: 37px !important;">
                                        {{ $addresses['currentOrder']['paymentTerms'] }}
                                    </span>
                                </div>
                            
                </div>
                <div style="margin-left: 27px !important;">
                    <div>
                        <p
                            style="font-family: 'Poppins' !important;
                            font-style: normal !important;
                            font-weight: 500 !important;
                            font-size: 10px !important;
                            line-height: 15px !important;
                            letter-spacing: 0.09em !important;
                            text-transform: uppercase !important;
                            color: #8E8E8E !important;
                            margin-top: 15px !important;
                            margin-left: 21px !important;">
                            Order Items</p>
                    </div>
                    @foreach ($order_items as $item)
                        @foreach ($item['product']->options as $option)
                            <div
                                style="display: flex !important; 
                                    margin-left: 21px!important;; 
                                    margin-right: 32px!important;
                                    border-bottom: 1px solid #EBEBEB !important;
                                    padding-bottom: 16px !important;
                                    margin-top:12px !important;
                    ">
                                <span style=" width: 13% !important;
                                        height: 62px !important;
                                        background: #D9D9D9 !important;
                                        border-radius: 5px !important;">
                                    <img src="{{ asset($option->image) }}" width="55" height="49" alt=""
                                        style=" 
                                    margin-left: 6px !important;
                                        margin-top: 6px !important;
                                        width: 55px  !important;
                                        height: 49px !important;">
                                </span>
                                <span
                                    style=" font-family: 'Poppins' !important;
                                        font-style: normal !important;
                                        font-weight: 500 !important;
                                        font-size: 11px !important;
                                        line-height: 16px !important;
                                        leading-trim: both !important;
                                        text-edge: cap !important;
                                        color: #282828 !important;
                                        margin-left: 23px !important;
                                        width: 50% !important;">
                                    {{ $item['product']->name }}
                                    <br>
                                    <br>
                                    <span
                                        style="font-family: 'Poppins' !important;
                                            font-style: normal !important;
                                            font-weight: 500 !important;
                                            font-size: 11px !important;
                                            line-height: 16px !important;
                                            color: #6C6C6C !important;">
                                        SKU: {{ $item['product']->code }}</span>
                                </span>
                                <span
                                    style="font-family: 'Poppins' !important;
                                        font-style: normal !important;
                                        font-weight: 600 !important;
                                        font-size: 11px !important;
                                        line-height: 16px !important;
                                        width: 33%!important;">
                                    <span style="float: right;">{{ number_format($item->price, 2) }}</span>

                                </span>
                            </div>
                        @endforeach
                    @endforeach
                    <div style="display:flex !important;">
                        <span
                            style="
                            font-family: 'Poppins' !important;
                            font-style: normal !important;
                            font-weight: 500 !important;
                            font-size: 11px !important;
                            line-height: 16px !important;
                            color: #8E8E8E !important;
                            margin-left: 21px!important;
                            margin-top: 15px !important;
                            width: 305px!important;
                            ">
                            Sub Total
                        </span>
                        <span
                            style="font-family: 'Poppins' !important;
                            font-style: normal !important;
                            font-weight: 600 !important;
                            font-size: 11px !important;
                            line-height: 16px !important;
                            width: 240px!important;
                            justify-content: flex-end !important;
                            margin-top: 15px !important;
                            ">
                            @php
                                $sub_total = 0;
                                foreach ($order_items as $item) {
                                    $sub_total += $item->price;
                                }
                            @endphp
                            <span
                                style="float: right; margin-right: 34px!important;">{{ number_format($sub_total, 2) }}</span>

                        </span>
                    </div>
                    <div
                        style="
                    display: flex !important;
                        margin-left: 21px !important;
                ">
                        @php
                            $taxt_rate = 0;
                            foreach ($order_items as $order_item) {
                                $taxt_rate = $order_item->order->texClasses->name;
                            }
                        @endphp
                        <span
                            style="font-family: 'Poppins' !important;
                                font-style: normal !important;
                                font-weight: 500 !important;
                                font-size: 11px !important;
                                line-height: 16px !important;
                                color: #8E8E8E !important;
                                width: 283px !important;
                                ">
                            Tax ({{ $taxt_rate }})
                        </span>
                        <span
                            style="font-family: 'Poppins' !important;
                                font-style: normal !important;
                                font-weight: 600 !important;
                                font-size: 11px !important;
                                line-height: 16px !important;
                                width: 259px!important;
                                ">
                            @php
                                $tax = 0;
                                foreach ($order_items as $item) {
                                    $tax += ($item->price * $item->order->texClasses->rate) / 100;
                                }
                            @endphp


                            <span style="float: right; margin-right: 33px !important">{{ number_format($tax, 2) }}</span>
                        </span>
                    </div>
                    <div
                        style="
                    display: flex !important;
                        margin-left: 22px!important;
                        margin-right: 18px !important;
                        margin-top: 15px !important;">
                        <span
                            style="
                        font-family: 'Poppins' !important;
                        font-style: normal !important;
                        font-weight: 600 !important;
                        font-size: 11px !important;
                        line-height: 16px !important;
                        color: #282828 !important;
                        width: 249px !important;">
                            Total
                        </span>
                        <span
                            style="
                        font-family: 'Poppins';
                        font-style: normal;
                        font-weight: 600;
                        font-size: 15px;
                        line-height: 22px;
                        text-align: right;
                        color: #282828;
                        width: 293px!important">
                            @php
                                $total = 0;
                                foreach ($order_items as $item) {
                                    $total = $item['order']->total_including_tax;
                                }
                            @endphp
                            <span style="float: right; margin-right: 15px!important;">{{ number_format($total, 2) }}</span>
                        </span>
                    </div>
                    <div>
                        <span>
                            <img src="{{ asset('https://stage.indoorsunhydro.com/theme/img/email/email_template.png') }}"
                                alt=""
                                style="margin-top: 15px !important;
                            margin-left: 15px !important;">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </table>
</body> --}}

<body padding="2" bgcolor="#000000" style="background-color: #000000;padding:2rem;">
    <table padding="2" bgcolor="#FFFFFF" style="background-color:#FFFFFF;border-radius:5px;" width="60%" border="0" align="center" cellpadding="7">
        <tbody>
            <tr>
                <td>
                    <table width="100%" border="0" align="center">
                        <tr>
                            <td align="" style="text-align: center;">
                                <img class="img-fluid "
                                    src="{{ asset('https://stage.indoorsunhydro.com/theme/img/email/approve_icon.png') }}">
                            </td>
                        </tr>
                        <tr>
                            <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 18px;font-weight:bold;">
                                We received your order!
                            </td>
                        </tr>
                        <tr>
                            <td align="" style="text-align: center;color:#000000;font-color:#000000;font-size: 11px;font-weight:400;">
                                Your order #{{ $addresses['order_id'] }} is completed and ready to ship
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td>
                    <table width="100%" align="left" border="0">
                        <tr>
                            <td width="60%">
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                @if ($addresses['billing_address']['state'] != null)
                                                {{$addresses['billing_address']['state']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                            <td>
                                                @if ($addresses['billing_address']['zip'] != null)
                                                {{$addresses['billing_address']['zip']}}
                                                @endif
                                            </td>
                                        </tr>
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
                                                PAYMENT INFO
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">
                                                {{ $addresses['currentOrder']['paymentTerms'] }}
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
                                    <td>
                                        <table width="100%" align="left" border="0">
                                            <tr>
                                                <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">{{ $item['product']->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#000000;font-color:#000000;font-size: 12px; font-weight:400;">SKU: {{ $item['product']->code }}</span></td>
                                            </tr>
                                        </table>
                                    </td>
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
                                {{'$'. number_format($sub_total, 2) }}
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
                                {{'$'. number_format($tax, 2) }}
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
                                {{ '$'.number_format($total, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="{{ asset('https://stage.indoorsunhydro.com/theme/img/email/email_template.png') }}" alt="">
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
