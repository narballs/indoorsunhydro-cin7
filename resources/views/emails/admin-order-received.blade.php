<!DOCTYPE html>
<html>
<head>
    <title>IndoorSun</title>
    <style type="text/css">
        .main-div {
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 10px;
            padding-bottom: 100px;
            margin-bottom: 100px;
        }

        table.bordered {
            border-top: 1px solid gray;
            border-right: 1px solid gray;
            border: none;
        }

        table.bordered td.has-bordered {
            /*border-left: 1px solid gray;*/
             border-bottom: 1px solid gray;
            padding: 10px;
        }

        .bottom-row td.border-bottom {
            border-bottom: 1px solid gray;
        }
    </style>
</head>
<body style="background: #f9f9f9;">
    <div class="main-div">
        <table width="100%" align="left" cellpadding="10" cellspacing="10">
            <tr>
                <td style="text-align: center;">
                    <div >
                        <img src="{{ url('/theme/img/indoor_sun.png') }}" width="200">
                    </div>
                    <div style="margin-top:100px; border-bottom: 1px solid gray">
                        <h2>ORDER CONFIRMATION</h2>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="border-bottom: 1px solid gray;margin-bottom:50px">
                        <p style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;
                        line-height: 32px;
                        font-weight: 400;
                        color: #333333;
                        margin: 11px 0 26px;">
                            Hey {{$name}},<br>
                            Thank you for your purchase! This email is to confirm your order with IndoorSunHydro, <br>
                            This order will be delivered to you within 2 to 3 working days Registered Courier.
                            We will send you another email with your shipment tracking details as soon as we ship your order.

                            Please do not hesitate to send us an email at orders@indoorsunhydro.com if you have any questions at all.<br>

                            


                            Thanks,<br>
                            Team IndoorSunHydro
                        </p>
                    </div >
                </td>
            </tr>
            <tr style="padding-bottom:0">
                <td style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';color: #333333;font-size: 20px;line-height: 28px;font-weight: 700;text-transform: uppercase;margin: 0;padding-bottom:0">ORDER NO. {{$reference}}</td>
            </tr>
            <tr style="padding-bottom:0">
                <td style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 13px;line-height: 22px;font-weight: normal;color: #c3c3c3;margin: 0; padding-top:0">{{$dateCreated}}</td>
            </tr>
        </table>
        <table style="background:#f9f9f9; margin-bottom:20px; padding-bottom: 20px;" width="100%">
            <tr style="margin-bottom: 20px;">
                <td width="50%" valign="top">
                    <div style="padding-left:20px;">
                        <h3>Billing Address</h3>
                        <div style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;line-height: 22px;font-weight: 400;color: #333333;margin: 0;">
                            <div>
                                {{$addresses['billing_address']['firstName']}} {{$addresses['billing_address']['lastName']}}
                            </div>
                            <div>
                                {{$addresses['billing_address']['address1']}}
                            </div>
                            <div>
                                {{$addresses['billing_address']['address2']}}
                            </div>
                            <div>
                                {{$addresses['billing_address']['state']}}
                            </div>
                             <div>
                                {{$addresses['billing_address']['city']}}
                            </div>
                              <div>
                                {{$addresses['billing_address']['zip']}}
                            </div>
                        </div>               
                    </div>
                </td>
                <td width="50%" style="margin-left:5px; float: left;">
                    <div>
                        <h3>Shipping Address</h3>
                        <div style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;line-height: 22px;font-weight: 400;color: #333333;margin: 0;">
                             <div>
                                {{$addresses['billing_address']['firstName']}} {{$addresses['billing_address']['lastName']}}
                            </div>
                             <div>
                                {{$addresses['shipping_address']['postalAddress1']}}
                            </div>
                            <div>
                                {{$addresses['shipping_address']['postalAddress2']}}
                            </div>
                             <div>
                                {{$addresses['shipping_address']['postalState']}}
                            </div>
                             <div>
                                {{$addresses['shipping_address']['postalCity']}}
                            </div>
                             <div>
                                {{$addresses['shipping_address']['postalPostCode']}}
                            </div>                   
                        </div>               
                    </div>
                </td>
            </tr>
        </table>

        <table class="bordered" width="100%" align="left" cellpadding="0" cellspacing="0">
            <?php $grand_total = 0;?>
            @foreach ($order_items as $item) 
                <tr>
                    <td class="has-bordered" valign="top" width="20%" style="vertical-align: middle;text-align: left;width: 120px;max-width: 120px;height: auto!important;border-radius: 0px;">
                        @if (!empty($item->product->options[0]->image))
                            <img src="{{$item->product->options[0]->image}}" width="50px">
                        @endif
                    </td>
                  
                    <td class="has-bordered" valign="top">
                        <table width="100%">
                            <tr>
                                <td width="65%" style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;line-height: 22px;font-weight: 400;color: #333333;margin: 0;">{{ $item->product->name }}</td>
                                <td valign="top" style="text-align:right">X {{$item->quantity}}</td>
                                <td valign="top" style="text-align: right">
                                   <?php $sub_total = $item->product->retail_price * $item->quantity; ?>
                                    ${{ number_format($sub_total, 2)}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php $grand_total +=  $sub_total; ?>
            @endforeach
            <tr style="margin-top:20px">
                <td>Grand Total</td>
                <td>{{ number_format($grand_total, 2)}}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 200px;">&nbsp;</td>
            </tr>
        </table> 
    </div>
</body>
</html>