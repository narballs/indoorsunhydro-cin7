@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5 desktop-view">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        Checkout
    </p>
</div>

<?php
    $cart_total = 0;
    $cart_price = 0;
?>
@if(Session::get('cart'))

@foreach(Session::get('cart') as $cart)
<?php 
            $total_quatity =  $cart['quantity'];
            $total_price = $cart['price'] * $total_quatity;
            $cart_total  = $cart_total + $total_price ;
?>
@endforeach
@endif
<div class="container desktop-view" id="">
    @if(session('message'))
    <div class="alert alert-danger">
        {{ session('message') }}
    </div>
    @endif
    @include('checkout.modals.address-modal')
    <div class="row ">
        <div class="col-md-7">
            <div class="billing-address bg-light p-3">
                <div class="bg-light">
                    <div style="font-weight: 600; font-size: 20px;">Billing Address</div>
                    <div class="row mt-2">
                        <div class="col-md-6 name">
                            {{$user_address->firstName}} {{$user_address->lastName}}
                        </div>
                        <div class="col-md-6 name">
                            {{$user_address->company}}
                        </div>
                    </div>
                </div>
                <div class="address-line bg-light">
                    Address line 1
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress1}}
                </div>
                <div class="address-line bg-light">
                    Address line 2
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress2}}
                </div>
                <div class="row m-0 bg-light">
                    <div class="col p-0 address-line">
                        City
                    </div>
                    <div class="col p-0 address-line">
                        State
                    </div>
                    <div class=" col p-0 address-line">
                        Zip
                    </div>
                </div>
                <div class="billing-address bg-light">
                    <div class="row m-0">
                        <div class="col p-0 name">
                            {{$user_address->postalCity}}
                        </div>
                        <div class="col p-0 name">
                            {{$user_address->postalState}}
                        </div>
                        <div class="col p-0 name">
                            {{$user_address->postalPostCode}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5" id="shipping_address">
            <div class="billing-address bg-light p-3">
                <div class="bg-light">
                    <div style="font-weight: 600;font-size: 20px;">Shipping Address</div>
                    <div class="row mt-2">
                        <div class="col-md-6 name">{{$user_address->firstName}} {{$user_address->lastName}}</div>
                        <div class="col-md-6 name">{{$user_address->company}}</div>
                    </div>
                </div>
                <div class="address-line bg-light">
                    Address line 1
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress1}}
                </div>
                <div class="address-line bg-light">
                    Address line 2
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress2}}
                </div>
                <div class="row m-0 bg-light">
                    <div class="col p-0 address-line">
                        City
                    </div>
                    <div class="col p-0 address-line">
                        State
                    </div>
                    <div class=" col p-0 address-line">
                        Zip
                    </div>
                </div>
                <div class="billing-address bg-light">
                    <div class="row m-0">
                        <div class="col p-0 name">
                            {{$user_address->postalCity}}
                        </div>
                        <div class="col p-0 name">
                            {{$user_address->postalState}}
                        </div>
                        <div class="col p-0 name">
                            {{$user_address->postalPostCode}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-5 order-md-2 mb-4">
            <div class="cart-headings">Cart Total</div>
            <div class="border-bottom"></div>
            <div class="row mt-4 max-width">
                <div class="col-md-10">
                    <img src="theme/img/pricing_tag.png">
                    <span class="totals">Total</span>
                </div>
                <div class="col-md-2 text-danger totals">${{number_format($cart_total,2)}}</div>
            </div>
            <div class="border-bottom mt-4"></div>
            <div>
                <div class="mt-4 payment-option">Delivery Options</div>
                @foreach($payment_methods as $payment_method)
                <form class="p-2" action="{{url('order')}}" method="POST" id="order_form" name="order_form">
                    @csrf
                    @foreach($payment_method->options as $payment_option)
                    <div class="row">
                        <div class="ms-3">
                            <input type="hidden" value="{{$payment_method->name}}" name="method_name">
                            <input type="radio" id="local_delivery_{{$payment_option->id}}" name="method_option"
                                value="{{$payment_option->option_name}}">
                            <label for="local_delivery payment-option-label">{{$payment_option->option_name}}</label>
                        </div>
                    </div>
                    @endforeach
                    @endforeach
            </div>
        </div>
        <div class="col-md-7 order-md-1">
            <div class="cart-headings border-bottom">Items in Cart</div>
            <div class="row  mt-4">
                <div class="col-md-10">
                    <img src="theme/img/box.png">
                    <span class="ms-1 cart-subtitles">Products</span>
                </div>
                <div class="col-md-2"><span class="ms-3 cart-subtitles">Quantity</span></div>
            </div>
            <?php
                $cart_total = 0;
                $cart_price = 0;
            ?>
            @if(Session::get('cart'))
            @foreach(Session::get('cart') as $cart)
            <?php 
                    $total_quatity =  $cart['quantity'];
                    $total_price = $cart['price'] * $total_quatity;
                    $cart_total  = $cart_total + $total_price ;
             ?>
            <li class="d-flex justify-content-between border-bottom mt-1">
                @if ($cart['image'])
                    <div class="mt-2">
                        <img src="{{ $cart['image']}}" alt="" width="70px;">
                    </div>
                @else
                    <div class="mt-2">
                        <img src="/theme/img/image_not_available.png" alt="" width="80px">
                    </div>
                @endif
                <div class="mt-4 mb-4">
                    <h6 class="my-0" style="color: #008BD3 !important;">
                        <a
                            href="{{ url('product-detail/'. $cart['product_id'] . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">
                            {{$cart['name']}}
                        </a>
                    </h6>
                </div>
                <div class="text-muted rounded-circle mt-4" id="circle">{{$cart['quantity']}}</div>
            </li>
            @endforeach
            @endif
        </div>
    </div>

    <div class="col-md-6" style="margin-top: 118px !important;margin:auto; !important; max-width:600px !important;">
        <button type="button" class="button-cards w-100" id="proceed_to_checkout" onclick="validate()">Proceed to
            checkout</button>
    </div>
    </form>
</div>

<!--Mobile View -->
<!-- MultiStep Form  -->
<div class="container-fluid mobile-view">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0">
            <div class="card border-0 px-0">
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <!-- progressbar -->
                            {{-- <ul id="progressbar">
                                <li class="active" id="account"><strong>Your Card</strong></li>
                                <li id="personal"><strong>Personal</strong></li>
                                <li id="payment"><strong>Payment</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul> --}}
                            <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="card border-0">
                                        <div class="card-body p-0 m-0">
                                            <div class="form-signup-secondary">
                                                <div class="user-info">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="label mt-5 fw-bold">First Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your first name"
                                                                id="company_website" name="firstName"
                                                                value="{{$user_address->firstName}}"
                                                                class="form-control mt-0fontAwesome">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="label ">last Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your last"
                                                                id="company_website" name="lastName"
                                                                value="{{$user_address->lastName}}"
                                                                class="form-control fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <label class="label">company name (optional)</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your company name"
                                                                value="{{$user_address->company}}" id="company"
                                                                name="company"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">street address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="House number and street name"
                                                                id="postalAddress1" name="postalAddress1"
                                                                value="{{$user_address->postalAddress1}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                placeholder="Aprtmant, suit, unit, etc.(optional)"
                                                                id="postalAddress2" name="postalAddress2"
                                                                value="{{$user_address->postalAddress2}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="label ">town / city</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your town"
                                                                id="postalCity" name="postalCity"
                                                                value="{{$user_address->postalCity}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">state</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your state"
                                                                id="postalState" name="postalState"
                                                                value="{{$user_address->postalState}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">zip</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your zip"
                                                                id="postalPostCode" name="postalPostCode"
                                                                value="{{$user_address->postalPostCode}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">phone</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your phone" id="phone"
                                                                name="phone" value="{{$user_address->phone}}"
                                                                class="form-control  company-info fontAwesome ">
                                                            <div class="text-danger" id="password_errors"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">email address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your email adress"
                                                                id="emailAddress" name="password"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div>
                                                                <img class="img-fluid coupon-code-modal-btn"
                                                                    src="/theme/img/modal-icon1.png" alt="">
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                                applay coupon
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="ps-3">
                                                    <span>
                                                        <img class="img-fluid" src="/theme/img/product-iccon.png">
                                                    </span>
                                                    <span class="product-title">Product</span>
                                                </th>
                                                <th class="text-white">
                                                    Quantity
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $cart_total = 0;
                                                $cart_price = 0;
                                                ?>
                                            @if(Session::get('cart'))
                                            @foreach(Session::get('cart') as $cart)
                                            <?php 
                                                $total_quatity =  $cart['quantity'];
                                                $total_price = $cart['price'] * $total_quatity;
                                                $cart_total  = $cart_total + $total_price ;
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="mt-3">
                                                        <a class="product-name" href="
                                                        {{ url('product-detail/'. $cart['product_id'] . '/' . $cart['option_id'] . '/' . $cart['slug']) }}
                                                        ">
                                                            {{$cart['name']}}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="d-flex justify-content-end align-items-end">
                                                    <div class="text-muted rounded-circle mt-3  product-quantity"
                                                        id="circle">
                                                        {{$cart['quantity']}}</div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div>
                                        <table class="table mt-5">
                                            <thead>
                                                <tr>
                                                    <th style="border-top:none !important" scope="col">Cart Total</th>
                                                    <th style="border-top:none !important" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex">
                                                            <span class="">
                                                                <img src="theme/img/pricing_tag.png" width=" 22px">
                                                            </span>
                                                            <span>
                                                                <p class="cart-total-checkout-page ps-3">Total</p>
                                                            </span>
                                                            <div
                                                                class="d-flex justify-content-end aling-items-end ps-5">
                                                                <p class="sub-total-checkout-page">
                                                                    ${{number_format($cart_total,2)}} </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="border-0">
                                                <tr>
                                                    <td style="border-bottom:none !important;">
                                                        <div class="payment-option">Delivery Options</div>
                                                        @foreach($payment_methods as $payment_method)
                                                        <form class="p-2" action="{{url('order')}}" method="POST"
                                                            id="order_form" name="order_form">
                                                            @csrf
                                                            @foreach($payment_method->options as $payment_option)
                                                            <div class="row">
                                                                <div class="ps-1">
                                                                    <input type="hidden"
                                                                        value="{{$payment_method->name}}"
                                                                        name="method_name">
                                                                    <input type="radio"
                                                                        id="local_delivery_{{$payment_option->id}}"
                                                                        name="method_option"
                                                                        value="{{$payment_option->option_name}}">
                                                                    <label
                                                                        for="local_delivery payment-option-label">{{$payment_option->option_name}}</label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endforeach
                                                            <div>
                                                                <button type="button" class="button-cards w-100"
                                                                    id="proceed_to_checkout" onclick="validate()">
                                                                    Proceed to checkout</button>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <img class="img-fluid coupon-code-modal-btn"
                                                src="/theme/img/modal-icon1.png" alt="">
                                        </div>
                                        <button type="button" class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            applay coupon
                                        </button>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card form-signup-secondary">
                                    <div class="d-flex justify-content-center aling-items-center">
                                        <img class="img-fluid" src="/theme/img/payment-img.png" alt="">
                                    </div>
                                    <div class="col-md-12 user-info">
                                        <label class="label mt-5">Card Number</label><span
                                            class="text-danger fw-bold pl-1">*</span>
                                        <input type="text" placeholder="4242 4242 4242 4242" id="cardNumber"
                                            name="cardNumber" class="form-control company-info fontAwesome "
                                            style="margin-bottom: 10px;">
                                    </div>
                                    <div class="d-flex">
                                        <div class="user-info w-50 m-0 ps-3" style="max-width: 164px  !important;">
                                            <label class="label">Expiry date</label><span
                                                class="text-danger fw-bold pl-1">*</span>
                                            <input type="text" placeholder="MM/YY" id="expiryDate" name="expiryDate"
                                                class="form-control company-info fontAwesome" style="
                                                width: 154px;
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;
                                                font-size: 14px;
                                                ">
                                        </div>
                                        <div class="user-info w-50 m-0 ps-3" style=" max-width: 163px  !important;">
                                            <label class="label">Cvc</label><span
                                                class="text-danger fw-bold pl-1">*</span>
                                            <input type="text" placeholder="MM/YY" id="cvc" name="cvc"
                                                class="form-control company-info fontAwesome" step="width: 154px;
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;">
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                                {{-- <input type="button" name="make_payment" class="next action-button"
                                    value="Confirm" /> --}}
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3">
                                            <img src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                class="fit-image">
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully !</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--ipad View -->
<!-- MultiStep Form  -->
<div class="container-fluid ipad-view">
    <div class="row justify-content-center mt-0">
        <div class="col-md-12">
            <div class="card border-0 px-0">
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <!-- progressbar -->
                            {{-- <ul id="progressbar">
                                <li class="active" id="account"><strong>Your Card</strong></li>
                                <li id="personal"><strong>Personal</strong></li>
                                <li id="payment"><strong>Payment</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul> --}}
                            <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="card border-0">
                                        <div class="card-body p-0 m-0">
                                            <div class="form-signup-secondary">
                                                <div class="user-info">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="label mt-5 fw-bold">First Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your first name"
                                                                id="company_website" name="firstName"
                                                                value="{{$user_address->firstName}}"
                                                                class="form-control mt-0fontAwesome">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">last Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your last"
                                                                id="company_website" name="lastName"
                                                                value="{{$user_address->lastName}}"
                                                                class="form-control fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <label class="label">company name (optional)</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your company name"
                                                                value="{{$user_address->company}}" id="company"
                                                                name="company"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">street address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="House number and street name"
                                                                id="postalAddress1" name="postalAddress1"
                                                                value="{{$user_address->postalAddress1}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                placeholder="Aprtmant, suit, unit, etc.(optional)"
                                                                id="postalAddress2" name="postalAddress2"
                                                                value="{{$user_address->postalAddress2}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="label ">town / city</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your town"
                                                                id="postalCity" name="postalCity"
                                                                value="{{$user_address->postalCity}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">state</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your state"
                                                                id="postalState" name="postalState"
                                                                value="{{$user_address->postalState}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">zip</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your zip"
                                                                id="postalPostCode" name="postalPostCode"
                                                                value="{{$user_address->postalPostCode}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">phone</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your phone" id="phone"
                                                                name="phone" value="{{$user_address->phone}}"
                                                                class="form-control  company-info fontAwesome ">
                                                            <div class="text-danger" id="password_errors"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="label ">email address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your email adress"
                                                                id="emailAddress" name="password"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div>
                                                                <img class="img-fluid coupon-code-modal-btn"
                                                                    src="/theme/img/modal-icon1.png" alt="">
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                                applay coupon
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="ps-3">
                                                    <span>
                                                        <img class="img-fluid" src="/theme/img/product-iccon.png">
                                                    </span>
                                                    <span class="product-title">Product</span>
                                                </th>
                                                <th class="text-white">
                                                    Quantity
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $cart_total = 0;
                                                $cart_price = 0;
                                                ?>
                                            @if(Session::get('cart'))
                                            @foreach(Session::get('cart') as $cart)
                                            <?php 
                                                $total_quatity =  $cart['quantity'];
                                                $total_price = $cart['price'] * $total_quatity;
                                                $cart_total  = $cart_total + $total_price ;
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="mt-3">
                                                        <a class="product-name" href="
                                                        {{ url('product-detail/'. $cart['product_id'] . '/' . $cart['option_id'] . '/' . $cart['slug']) }}
                                                        ">
                                                            {{$cart['name']}}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="d-flex justify-content-end align-items-end">
                                                    <div class="text-muted rounded-circle mt-3  product-quantity"
                                                        id="circle">
                                                        {{$cart['quantity']}}</div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div>
                                        <table class="table mt-5">
                                            <thead>
                                                <tr>
                                                    <th style="border-top:none !important" scope="col">Cart Total</th>
                                                    <th style="border-top:none !important" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex">
                                                            <span class="">
                                                                <img src="theme/img/pricing_tag.png" width=" 22px">
                                                            </span>
                                                            <span>
                                                                <p class="cart-total-checkout-page ps-3">Total</p>
                                                            </span>
                                                            <div
                                                                class="d-flex justify-content-end aling-items-end ps-5">
                                                                <p class="sub-total-checkout-page">
                                                                    ${{number_format($cart_total,2)}} </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="border-0">
                                                <tr>
                                                    <td style="border-bottom:none !important;">
                                                        <div class="payment-option">Delivery Options</div>
                                                        @foreach($payment_methods as $payment_method)
                                                        <form class="p-2" action="{{url('order')}}" method="POST"
                                                            id="order_form" name="order_form">
                                                            @csrf
                                                            @foreach($payment_method->options as $payment_option)
                                                            <div class="row">
                                                                <div class="ps-1">
                                                                    <input type="hidden"
                                                                        value="{{$payment_method->name}}"
                                                                        name="method_name">
                                                                    <input type="radio"
                                                                        id="local_delivery_{{$payment_option->id}}"
                                                                        name="method_option"
                                                                        value="{{$payment_option->option_name}}">
                                                                    <label
                                                                        for="local_delivery payment-option-label">{{$payment_option->option_name}}</label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endforeach
                                                            <div>
                                                                <button type="button" class="button-cards w-100"
                                                                    id="proceed_to_checkout" onclick="validate()">
                                                                    Proceed to checkout</button>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <img class="img-fluid coupon-code-modal-btn"
                                                src="/theme/img/modal-icon1.png" alt="">
                                        </div>
                                        <button type="button" class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            applay coupon
                                        </button>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card form-signup-secondary">
                                    <div class="d-flex justify-content-center aling-items-center">
                                        <img class="img-fluid" src="/theme/img/payment-img.png" alt=""
                                            style="width: 444px;">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 user-info">
                                            <label class="label mt-5">Card Number</label><span
                                                class="text-danger fw-bold pl-1">*</span>
                                            <input type="text" placeholder="4242 4242 4242 4242" id="cardNumber"
                                                name="cardNumber" class="form-control company-info fontAwesome "
                                                style="margin-bottom: 10px;">
                                        </div>
                                        <div class="user-info col-md-6" style="padding-left: 93px;">
                                            <label class="label">Expiry date</label><span
                                                class="text-danger fw-bold pl-1">*</span>
                                            <input type="text" placeholder="MM/YY" id="expiryDate" name="expiryDate"
                                                class="form-control company-info fontAwesome" style="
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;
                                                font-size: 14px;
                                                ">
                                        </div>
                                        <div class="user-info col-md-6"
                                            style="padding-right: 93px;padding-left: 0px !important;">
                                            <label class="label">Cvc</label><span
                                                class="text-danger fw-bold pl-1">*</span>
                                            <input type="text" placeholder="MM/YY" id="cvc" name="cvc"
                                                class="form-control company-info fontAwesome" style="
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;
                                                font-size: 14px;
                                                ">
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                                {{-- <input type="button" name="make_payment" class="next action-button"
                                    value="Confirm" /> --}}
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3">
                                            <img src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                class="fit-image">
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully !</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content py-4" style="width:70% ;margin: auto;">
            <div class="modal-header border-0 pb-0 pt-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border-0 p-0">
                <div class="d-flex justify-content-center align-items-center">
                    <img class="img-fluid" src="/theme/img/modal-icon.png" alt="">
                </div>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <p class="coupon-code-input-label m-0">coupon code</p>
                </div>
                <div class="form-signupp d-flex justify-content-center align-items-center mt-2">
                    <div class="w-75 d-flex justify-content-center align-items-center">
                        <input type="text" name="code" id="code" class="fontAwesome form-control"
                            placeholder="Your code" required
                            style="height: 46px; border-radius: inherit; text-align: center;">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-0 mt-2 d-flex justify-content-center align-items-center">
                <button type="button" class="btn btn-primary w-75 applay-coupon-code-modal-btn">applay
                    coupon</button>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5 pt-5 desktop-view">
    @include('partials.product-footer')
</div>
<form class="needs-validation mt-4 novalidate" style="display:none" action="{{url('order')}}" method="POST">
    @csrf
    <div class="alert alert-success  d-none" id="success_msg"></div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="firstName">First name</label>
            <input type="text" class="form-control bg-light" name="firstName" placeholder="First name"
                value="{{$user_address->firstName}}" required>
            <div id="error_first_name" class="text-danger">

            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="lastName">Last name</label>
            <input type="text" class="form-control bg-light" name="lastName" placeholder=""
                value="{{$user_address->lastName}}" required>
            <div id="error_last_name" class="text-danger">
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="company">Company Name(optional)</label>
        <div class="input-group">
            <input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name"
                value="{{$user_address->company}}" required>
        </div>
        <div id="error_company" class="text-danger"></div>
    </div>
    <div class="mb-3">
        <label for="username">Country</label>&nbsp;<span>United States</span>
        <input type="hidden" name="country" value="United States">
    </div>
    <div class="mb-3">
        <label for="address">Street Address</label>
        <input type="text" class="form-control bg-light" name="address" value="{{$user_address->postalAddress1}}"
            placeholder="House number and street name" required>
    </div>
    <div id="error_address1" class="text-danger"></div>

    <div class="mb-3">
        <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control bg-light" name="address2" value="{{$user_address->postalAddress2}}"
            placeholder="Apartment, suite, unit etc (optional)">
    </div>
    <div id="error_address2" class="text-danger"></div>
    <div class="mb-3">
        <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control bg-light" name="town_city" value="{{$user_address->postalCity}}"
            placeholder="Enter your town">
    </div>
    <div id="error_city" class="text-danger"></div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="state">State</label>

            <select class="form-control bg-light" name="state" id="state">
                @foreach($states as $state)
                <?php 
                     if($user_address->postalState == $state->name)
                     {
                        $selected = 'selected';

                        }
                        else
                        {
                        $selected = '';
                    }           
                ?>
                <option value="{{$state->name}}" <?php echo $selected;?>>{{$state->name}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Valid first name is required.
            </div>
            <script>
                function validate(){   
    if ( ! $("input[name=method_option]").is(':checked') ) {
        const inputOptions = new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    'Local Delivery': 'Local Delivery',
                    'Pickup Order': 'Pickup Order'
                })
            }, 1000)
        })
        console.log(inputOptions);
        Swal.fire({
            title: 'Please choose delivery option',
            input: 'radio',
            imageUrl: "theme/img/delivery.png",
            inputOptions: inputOptions,
            showCancelButton: false,
            confirmButtonColor: '#8282ff',
            confirmButtonText: 'Continue',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.value !== null) {
                if (result.value == 'Local Delivery') {
                    $("#local_delivery_1").attr('checked', 'checked');
                } 
                else {
                $("#local_delivery_2").attr('checked', 'checked'); 
                }
                $("#order_form").submit();
            }
        });
    }

    else {
    $("#order_form").submit(); 
    }
}
function updateAddress() {
    $('#address-form-update').toggle();
    $('#address-form-update').removeClass('d-none');

}
function updateContact(user_id) {
    var first_name = $('input[name=firstName]').val();
    var last_name = $('input[name=lastName]').val();
    var company_name = $('input[name=company]').val();
    var phone = $('input[name=phone]').val();
    var address = $('input[name=address]').val();
    var address2 = $('input[name=address2]').val();
    var town_city = $('input[name=town_city]').val();
    var state = document.getElementById("state").value;
    var zip = $('input[name=zip]').val();
    var email = $('input[name=email]').val();

    jQuery.ajax({
            method: 'GET',
            url: "{{ url('/user-addresses/') }}",

            data: {
                "_token": "{{ csrf_token() }}",
                "user_id": user_id,
                "first_name" : first_name,
                "last_name" : last_name,
                "company_name" : company_name,
                "phone" : phone,
                "address" : address,
                "address2" : address2,
                "town_city" : town_city,
                "state" : state,
                "zip" : zip,
                "email" : email
            },
            success: function(response) {

                if(response.success == true) {

                    $('.modal-backdrop').remove()
                    $('#success_msg').removeClass('d-none');
                    $('#success_msg').html(response.msg);
                    window.location.reload();
                }
            },
            error: function (response) {
                
                var error_message = response.responseJSON;
                console.log(error_message);
                var error_text = '';
                if (typeof error_message.errors.first_name != 'undefined') {
                    error_text = error_message.errors.first_name;
                    $('#error_first_name').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_first_name').html(error_text);
                }
                if (typeof error_message.errors.last_name != 'undefined') {
                    var error_text = error_message.errors.last_name;
                    $('#error_last_name').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_last_name').html(error_text);
                }
                if (typeof error_message.errors.company_name != 'undefined') {
                    var error_text = error_message.errors.company_name;
                    $('#error_company').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_company').html(error_text);
                }
                if (typeof error_message.errors.address != 'undefined') {
                    var error_text = error_message.errors.address;
                    $('#error_address1').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_address1').html(error_text);
                }
            
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.errors.zip;
                    $('#error_zip').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.town_city != 'undefined') {
                    var error_text = error_message.errors.town_city;
                    $('#error_city').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_city').html(error_text);
                }
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.zip;
                    $('#error_zip').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.phone != 'undefined') {
                    var error_text = error_message.errors.phone;
                    $('#error_phone').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_phone').html(error_text);
                }

            }
    });
}
            </script>
            @include('partials.footer')
            <script>
                $(document).ready(function(){
var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

$(".next").click(function(){
    
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();
    
    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
    
    //show the next fieldset
    next_fs.show(); 
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            // for making fielset appear animation
            opacity = 1 - now;

            current_fs.css({
                'display': 'none',
                'position': 'relative'
            });
            next_fs.css({'opacity': opacity});
        }, 
        duration: 600
    });
});

$(".previous").click(function(){
    
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();
    
    //Remove class active
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
    
    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            // for making fielset appear animation
            opacity = 1 - now;

            current_fs.css({
                'display': 'none',
                'position': 'relative'
            });
            previous_fs.css({'opacity': opacity});
        }, 
        duration: 600
    });
});

$('.radio-group .radio').click(function(){
    $(this).parent().find('.radio').removeClass('selected');
    $(this).addClass('selected');
});

$(".submit").click(function(){
    return false;
})
    
});
            </script>