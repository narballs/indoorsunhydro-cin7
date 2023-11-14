
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
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="{{asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css')}}" rel="stylesheet"
        id="bootstrap-css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .thank-you-page-product-options-image {
        max-height: 95px !important;
    }

    .background-colo-css {
        margin-top: -115px;
        padding-right: 52px;
        padding-top: 24px !important;
        background: #FBFBFB !important;
        border-radius: 6px !important;
        padding-left: 293px;
    }

    .thanks-page-items {
        width: 565px;
        height: 114px;
        left: 297px;
        top: 949px;
        background: #FFFFFF;
        border: 1px solid #D3D3D3;
        border-radius: 5px;
    }

    .thank-you-page-user-detais {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 15px;
        line-height: 19px;
        color: #5C5C5C;
    }

    @import url('https://fonts.googleapis.com/css?family=Mukta');
</style>

<body>
    <div class="container-fluid" style="width: 100% !important; marging:auto !important" width="100%;">
        <div class="row">
            <div style="width: 100%">
                <div class="card border-0 thank-you-page-background-img" style="background-color: #F5F5F5 !important;
                border-radius: 10px !important;
                padding-bottom: 146px !important;
                padding-top: 92px !important;">
                    <div class="row" style="padding-bottom: 77px !important;">
                        <div class="col-md-12 thank-you-page-card-row">
                            <div class="card m-auto border-0 thank-you-page-first-card" style="
                            width: 85% !important;
                            padding-bottom: 194px !important;
                            margin: auto; !important;
">
                                <div class="card-boday" style="background-color: #ffff;
                                margin: auto;
                                width: 85%;
                                border-radius: 10px;">
                                    <div class=" col-md-12 card-body-content">
                                        <p class="thank-page-date" style="font-family: 'Poppins' !important;
                                        font-style: normal !important;
                                        font-weight: 400 !important;
                                        font-size: 20px !important;
                                        line-height: 119.5% !important;
                                        color: #575757 !important;
                                        padding-left: 60px !important;
                                        padding-top: 85px !important;
                                        ">
                                            {{$addresses['currentOrder']['created_at']->format('F '.'d, Y, '.'g:i A')}}
                                        </p>
                                        <hr class="border" style="
                                            margin-left: 63px;
                                            margin-top: 40px!important;
                                            margin-bottom: 1rem;
                                            border: 0;
                                            border-top: 1px solid #000000!important;
                                            width: 1142px!important;
                                            border: 1px solid #000000!important;">
                                    </div>
                                    <div class="row ps-5" style="
                                    display: flex;
                                    padding-bottom: 171px; ">
                                        <div class="col-md-7 mt-4" style="width: 57%;padding-left: 63px;">
                                            <div class="row" style="display: flex">
                                                <div class="col-md-7" style="width: 100%;">
                                                    <p class="ps-5 thanks-heading" style="    font-family: 'Poppins';
                                                    font-style: normal;
                                                    font-weight: 600;
                                                    font-size: 27px;
                                                    line-height: 120%;
                                                    letter-spacing: 0.545em;
                                                    color: #575757;
                                                    ">Thank you for your order</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mt-5" style="width: 100%">
                                                    <p class="description-thank-you-page ps-5" style="font-family: 'Poppins';
                                                    font-style: normal;
                                                    font-weight: 400;
                                                    font-size: 16px;
                                                    line-height: 129.19%;
                                                    letter-spacing: 0.025em;
                                                    color: #000000;">Hey
                                                        {{$addresses['billing_address']['firstName']}},<br>
                                                 </p> Order exceeded the credit limit please review and perform required action.
                                                </div>
                                                <div class="mt-5" style="margin-top: 50px !important;">
                                                    <div style="display: flex;">
                                                        <div style="width:60%">
                                                            <p style="
                                                            font-size: 15px;
                                                            font-weight: 600;
                                                            font-family: 'Poppins';
                                                            padding-left: 18px;">Name</p>
                                                        </div>
                                                        <div style="width:10%">
                                                            <p style="
                                                            font-size: 15px;
                                                            font-weight: 600;
                                                            font-family: 'Poppins';">Quantity</p>
                                                        </div>
                                                        <div style="width:15%">
                                                            <p style=" padding-left: 25px;
                                                            font-size: 15px;
                                                            font-weight: 600;
                                                            font-family: 'Poppins';">Price</p>
                                                        </div>
                                                        <div style="width:15%">
                                                            <p style="
                                                            font-size: 15px;
                                                            font-weight: 600;
                                                            font-family: 'Poppins';">Total </p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $total_quantity	 = 0;
                                                        $cart_total = 0;
                                                        $cart_price = 0;
                                                    ?>
                                                    @foreach ($order_items as $item )
                                                    <?php 
                                                     $total_q[] = $item->quantity;
                                                     $total_quantity = array_sum($total_q);
                                                        $total_quatity =  $item->quantity;
                                                        $total_price = $item->price * $total_quatity;
                                                        $cart_total  = $cart_total + $total_price ;
                                                     ?>
                                                    <div
                                                        style="display: flex;  background: #FFFFFF !important; border: 1px solid #D3D3D3 !important;  border-radius: 5px !important; width: 722px !important;min-height: 114px !important; margin-top: 10px;">
                                                        
                                                        <div style="width:60%;">
                                                            <p class=" thank-you-sku ps-0" style="font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 600;
                                                            font-size: 18px;
                                                            line-height: 21px;
                                                            color: #000000;
                                                            padding-left: 18px;">Sku:
                                                                {{$item->product->code}}
                                                            </p>
                                                            <p class="thank-page-title" style="font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #4E4E4E;
                                                            margin-top: -16px;
                                                            padding-left: 18px;">
                                                                {{$item->product->name}}
                                                            </p>
                                                        </div>
                                                        <div style="width:10%">
                                                            <p style="padding-left: 22px;
                                                            font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #4e4e4e;
                                                            margin-top: 40px;">
                                                                {{$item->quantity}}
                                                            </p>
                                                        </div>
                                                        <div style="width:15%">
                                                            <p class="thnak-you-page-price" style="    margin-top: 35px;
                                                            padding-left: 20px;
                                                            font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;">
                                                                ${{number_format($item->price,2)}}
                                                            </p>
                                                        </div>
                                                        <div style="width:15%"">
                                                        <p style=" padding-top: 20px; font-family: 'Poppins' ;
                                                            font-style: normal; font-weight: 600; font-size: 16px;
                                                            line-height: 19px;">
                                                            ${{number_format($item->quantity * $item->price, 2)}}</p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-md-5 thnak-you-page-box-billing-address mt-5" style="    width: 31%;
                                            background: #fbfbfb;
                                            border-radius: 10px;
                                            padding-left: 39px;">
                                            <p class="thank-you-page-billing-address " style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 600;
                                            font-size: 30px;
                                            line-height: 36px;
                                            color: #000000;">Billing Address</p>
                                            <p class="thank-you-page-delivery-address" style="font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 700;
                                            font-size: 18px;
                                            line-height: 22px;
                                            color: #5C5C5C;">Delivery Address</p>
                                            <span class="thank-you-page-user-detais" style="font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['billing_address']['firstName']}}
                                                {{$addresses['billing_address']['lastName']}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;
                                            ">
                                                {{$user_email}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['shipping_address']['postalAddress1']}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['shipping_address']['postalAddress1']}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['shipping_address']['postalPostCode']}}

                                            </span>
                                            <p class="thank-you-page-delivery-address mt-4" style="font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 700;
                                            font-size: 18px;
                                            line-height: 22px;
                                            color: #5C5C5C;">Billing Address</p>
                                            <span class="thank-you-page-user-detais" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['billing_address']['firstName']}}
                                                {{$addresses['billing_address']['lastName']}}

                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$user_email}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['billing_address']['address1']}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style=" font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C;">
                                                {{$addresses['billing_address']['address1']}}
                                            </span><br>
                                            <span class="thank-you-page-user-detaiss" style="  font-family: 'Poppins';
                                            font-style: normal;
                                            font-weight: 400;
                                            font-size: 15px;
                                            line-height: 19px;
                                            color: #5C5C5C">
                                                {{$addresses['billing_address']['zip']}}
                                            </span>
                                            <div class=" row mt-5 ms-0 py-3 thank-you-page-second-row" style="background: #FBFBFB !important;
                                            border: 3px solid #2653A0 !important;
                                            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05) !important;
                                            border-radius: 10px !important;
                                            max-width: 100% !important">
                                                <div class="col-md-12" style="width: 96%;
                                                padding-left: 22px;
                                                padding-bottom: 14px;">
                                                    <p class="thank-you-page-order-summary" style="font-family: 'Poppins';
                                                    font-style: normal;
                                                    font-weight: 600;
                                                    font-size: 27px;
                                                    line-height: 13px;
                                                    color: #000000;
                                                ">Order #{{$addresses['order_id']}}
                                                        Summary
                                                    </p>
                                                    <div class="row" style="display: flex">
                                                        <div class="col-md-6" style="width: 50%">
                                                            <p class="thank-you-page-item-count" style="   font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 700;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #000000;
                                                            margin-bottom: 0px;">Item count</p>
                                                            <span class="thank-you-page-item-counter" style="font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #5C5C5C;">
                                                                {{$total_quantity}}
                                                            </span>
                                                        </div>
                                                        <div class="col-md-6 ps-5" style="width: 50%">
                                                            <p class="thank-you-page-item-count" style="   font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 700;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #000000;
                                                            margin-bottom: 0px;">Delivery Method</p>
                                                            <span class="thank-you-page-item-counter" style="font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #5C5C5C;">
                                                                {{$addresses['currentOrder']['logisticsCarrier']}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-5" style="width: 100%">
                                                        <p class="thank-you-page-item-count" style="  font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 700;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #000000;
                                                            margin-bottom: 0px;">Total</p>
                                                        <span class="thank-you-page-item-counter" style="font-family: 'Poppins';
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            font-size: 16px;
                                                            line-height: 19px;
                                                            color: #5C5C5C;">
                                                            ${{number_format($cart_total,2)}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div style="    background-color: #2653a0!important;
                        padding-bottom: 1px!important;
                        margin: auto!important;
                        width: 47%!important;
                        border-radius: 10px!important;
                        padding: 30px!important;
                        margin-top: -111px !important;
                        ">
                            <p class="thank-you-page-card-footer" style=" font-family: 'Poppins';
                                font-style: normal;
                                font-weight: 400;
                                font-size: 16px;
                                line-height: 22px;
                                text-align: center;
                                color: #FFFFFF !important;
                            ">
                                {{ \App\Helpers\SettingHelper::getSetting('website_name') }} isn't your grandma's gardening
                                store.<br> But you can bring her along
                                if you want. <br> Walk-ins welcome anytime — except Sunday. Even
                                gardeners need a day of rest.
                            </p>
                            <center>
                                <p style="display: flex !important; justify-content: center !important; padding: 3px; !important">
                                    <img src="{{ \App\Helpers\SettingHelper::getSetting('website_url') }}/theme/img/thank-you-page-icon-3.png" alt="" />
                                    <span>
                                        <img src="{{ \App\Helpers\SettingHelper::getSetting('website_url') }}/theme/img/thank-you-page-icon-2.png" alt="" />
                                    </span>
                                    <span>
                                        <img src="{{ \App\Helpers\SettingHelper::getSetting('website_url') }}/theme/img/thank-you-page-icon-1.png" alt="" />
                                    </span>
                                </p>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>