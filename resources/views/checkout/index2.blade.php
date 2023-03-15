@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .thank-you-page-table,
    thead,
    tbody,
    tfoot,
    tr,
    td,
    th {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border-color: #8C8C8C;
    }

    /* 
    @media only screen and (max-width: 1024px) and (min-width: 821px) {
        .desktop-view {
            display: none !important;
        }

        .laptop-view-xl {
            display: none !important;
        }


        .mobile-view {
            display: none !important;
        }

        .ipad-view {
            display: none !important;
        }

        .laptop-view {
            display: block !important;
        }
    }

    @media only screen and (max-width: 1440px) and (min-width: 1025px) {
        .laptop-view-xl {
            display: block !important;
        }

        .laptop-view {
            display: none !important;
        }

        .desktop-view {
            display: none !important;
        }
    } */
</style>
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

<div class="container-fluid w-75 desktop-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card p-5 border-0" style="background: #FAFAFA; border-radius: 6px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="billing-address-thank-you-page-heading">Billing Address</p>
                            </div>
                            <div class="col-md-6">
                                <a data-bs-toggle="modal" href="#address_modal_id" role="button" class="float-end">
                                    <img src="/theme/img/thank_you_page_edit_icon.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="user-first-name-thank-you-page"> {{$user_address->firstName}}
                                    {{$user_address->lastName}}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 1</p>
                                <p class="user-address-thank-you-page-item">{{$user_address->postalAddress1}}</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">City</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalCity}}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">State</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalState}}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">Zip</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalPostCode}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 2</p>
                                <p class="user-address-thank-you-page-item">{{$user_address->postalAddress2}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="billing-address-thank-you-page-heading">Billing Address</p>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="user-first-name-thank-you-page"> {{$user_address->firstName}}
                                    {{$user_address->lastName}}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 1</p>
                                <p class="user-address-thank-you-page-item">{{$user_address->postalAddress1}}</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">City</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalCity}}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">State</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalState}}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">Zip</p>
                                        <p class="user-address-thank-you-page-item">{{$user_address->postalPostCode}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 2</p>
                                <p class="user-address-thank-you-page-item">{{$user_address->postalAddress2}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ps-5">
        <div class="col-md-12">
            <p class="item-purchased-thank-you-page">Item Purchased </p>
        </div>
        <div class=" col-xl-9 col-lg-9 col-md-12 col-sm-12 pe-5">
            <table class="table">
                <tr>
                    <th class="thank-you-page-table-data-heading">Name</th>
                    <th class="thank-you-page-table-data-heading" style="padding-left: 0px; !important">Quantity</th>
                    {{-- <th class="thank-you-page-table-data-heading">Shipping</th> --}}
                    <th class="thank-you-page-table-data-heading">Price</th>
                </tr>
                <tbody class="border-0">
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
                        <td>
                            <div class="row">
                                <div class="col-md-2 py-2">
                                    @if ($cart['image'])
                                    <img class="img-fluid img-thumbnail" src="{{ $cart['image']}}" alt="" width="90px"
                                        style="max-height: 90px">
                                    @else
                                    <img src="/theme/img/image_not_available.png" alt="" width="80px">
                                    @endif
                                </div>
                                <div class="col-md-8 py-2 ps-0">
                                    <a class="category-name-thank-you-page pb-3"
                                        href="{{ url('product-detail/'. $cart['product_id'] . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">
                                        {{$cart['name']}}
                                    </a>
                                    <br>
                                    <p class="product-title-thank-you-page ">Title:<span
                                            class="product-title-thank-you-page-title">
                                            {{$cart['name']}}</span>
                                    </p>
                                    <p class="product-delete-icon-thank-you-page-icon">
                                        <img class="img-fluid" src="/theme/img/thank-you-page-delete.icon.png" alt="">
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="pt-4 thank-you-page-product-items-cart">{{$cart['quantity']}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="pt-4 thank-you-page-product-items-price">${{number_format($cart['price'],2)}}</p>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-3" style="background: #FAFAFA;
        border-radius: 5px;
        height: 426px;">
            <p class="thank-you-page-product-items-delivery-options">Delivery Options</p>
            <div class="row">
                <div class="col-md-12 mt-2">
                    @foreach($payment_methods as $payment_method)
                    <form action="{{url('order')}}" method="POST" id="order_form" name="order_form">
                        @php
                        $session_contact_id = Session::get('contact_id');
                        @endphp
                        @csrf
                        <div class="row">
                            @foreach($payment_method->options as $payment_option)
                            <div class="col-md-6 ps-4">
                                <input type="hidden" value="{{$payment_method->name}}" name="method_name">
                                <input type="radio" id="local_delivery_{{$payment_option->id}}" name="method_option"
                                    value="{{$payment_option->option_name}}" style="background: #008BD3;">
                                <label for="local_delivery payment-option-label"
                                    class="thank-you-page-product-items-payment-method-cart">{{$payment_option->option_name}}</label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="thank-you-page-select-date-options">Please Select Date</p>
                        <input type="date" name="date" class="form-control " id="date">
                    </div>
                    <div class="col-md-12">
                        <p class="thank-you-page-select-date-options">PO Number</p>
                        <input type="text" name="po_number" placeholder=" PO Number" id="po_number"
                            class="form-control fontAwesome">
                    </div>
                    <div class="col-md-12">
                        <p class="thank-you-page-select-date-options">Memo</p>
                        <input type="text" name="memo" placeholder="&#xf328; Memo" id="memo"
                            class="form-control fontAwesome">
                    </div>
                </div>
            </div>
            <div class="row">
                <?php 
                    $tax = $cart_total * ($tax_class->rate/100);
                    $total_including_tax = $tax + $cart_total;
                ?>
                <input type="hidden" name="incl_tax" id="incl_tax" value="{{$total_including_tax}}">
                <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class->id}}">
                <div class="col-md-12 mt-3 py-3" style="background: #F7F7F7; border-radius: 5px;">
                    <p class="thank-you-page-product-imtes-total-cart">Total</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="thank-you-page-product-items-subtotal-cart">
                                <img class="img-fluid" src="theme/img/pricing_tag.png" width=" 35px">
                            </p>
                            <p class="thank-you-page-product-items-subtotal-cart">
                                <img class=" img-fluid" src="/theme/img/tax_icon_check_out_page.png">
                                <span>Rate</span> ({{$tax_class->rate}}%)
                            </p>
                            <p class="thank-you-page-product-items-subtotal-cart mt-4">
                                <img class=" img-fluid" src="/theme/img/sub_total_icon_check_out_page.png">
                                <strong>SubTotal</strong>
                            </p>
                        </div>
                        <div class="col-md-6 ">
                            <p class=" thank-you-page-product-item-cart">{{$cart_total}}</p>
                            {{-- <p class=" thank-you-page-product-item-cart">shipping</p> --}}
                            <p class=" thank-you-page-product-item-cart">${{number_format($tax, 2)}}</p>
                            <p class="thank-you-page-product-item-cart mt-4" id="tax-rate">
                                ${{number_format($total_including_tax, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 m-auto"
            style="margin-top: 118px !important;margin:auto; !important; max-width:600px !important;">
            <button type="button" class="button-cards w-100" id="proceed_to_checkout" onclick="validate()" style="background: #008BD3 ;
            border-radius: 5px;">Proceed
                to
                checkout</button>
        </div>
        </form>
    </div>
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
                                                            <label class="label mt-5 fw-bold">First
                                                                Name</label><span
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
                                                            <label class="label">company name
                                                                (optional)</label><span
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
                                                    <th style="border-top:none !important" scope="col">Cart Total
                                                    </th>
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
                                                            <label class="label mt-5 fw-bold">First
                                                                Name</label><span
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
                                                            <label class="label">company name
                                                                (optional)</label><span
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
                                    <table class=" border-white" style="width:658px">
                                        <thead>
                                            <tr class="border-white">
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
                                            <tr class="border-white">
                                                <td class="ps-4 border-white">
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
                                                    <th style="border-top:none !important" scope="col">Cart Total
                                                    </th>
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
                                                        <div class="payment-option ps-3">Delivery Options</div>
                                                        @foreach($payment_methods as $payment_method)
                                                        <form class="p-2" action="{{url('order')}}" method="POST"
                                                            id="order_form" name="order_form">
                                                            @csrf
                                                            @foreach($payment_method->options as $payment_option)
                                                            <div class="row">
                                                                <div class="ps-4">
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
<div class="row mt-5 pt-5 desktop-view" style="padding-top:100px  !important;">
    @include('partials.product-footer')
</div>

<div class="modal fade" id="address_modal_id" data-dismiss="modal" data-backdrop="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Update Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="update-address-section" id="address-form-update">
                    <form class="needs-validation mt-4 novalidate" action="{{url('order')}}" method="POST">
                        @csrf
                        <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control bg-light" name="firstName"
                                    placeholder="First name" value="{{$user_address->firstName}}" required>
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
                                <input type="text" class="form-control bg-light" name="company"
                                    placeholder="Enter you company name" value="{{$user_address->company}}" required>
                            </div>
                            <div id="error_company" class="text-danger">

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username">Country</label>&nbsp;<span>United States</span>
                            <input type="hidden" name="country" value="United States">
                        </div>
                        <div class="mb-3">
                            <label for="address">Street Address</label>
                            <input type="text" class="form-control bg-light" name="address"
                                value="{{$user_address->postalAddress1}}" placeholder="House number and street name"
                                required>
                        </div>
                        <div id="error_address1" class="text-danger">
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light" name="address2"
                                value="{{$user_address->postalAddress2}}"
                                placeholder="Apartment, suite, unit etc (optional)">
                        </div>
                        <div id="error_address2" class="text-danger">
                        </div>
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light" name="town_city"
                                value="{{$user_address->postalCity}}" placeholder="Enter your town">
                        </div>
                        <div id="error_city" class="text-danger">
                        </div>
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
                                    <option value="{{$state->name}}" <?php echo $selected;?>>{{$state->name}}
                                    </option>
                                    @endforeach
                                </select>
                                <!--    <input type="text" class="form-control bg-light" name="state" value="{{$user_address->postalState}}" placeholder="Enter State" value="" required> -->
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light" name="zip" placeholder="Enter zip code"
                                    value="{{$user_address->postalPostCode}}" required>
                                <div id="error_zip" class="text-danger">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light" name="phone"
                                    placeholder="Enter your phone" value="{{$user_address->phone}}" required>
                                <div id="error_phone" class="text-danger"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn button-cards primary"
                    onclick="updateContact('{{auth()->user()->id}}')">Update</button>
            </div>
        </div>
    </div>
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
                            'C.O.D': 'C.O.D',
                            'Pickup Order': 'Pickup Order'
                        })
                }, 1000)
            })
            Swal.fire({
                imageUrl: "theme/img/delivery-icon.png",
                title: 'Please choose delivery option',
                input: 'radio',
                inputOptions: inputOptions,
                showCancelButton: false,
                confirmButtonColor: '#8282ff',
                confirmButtonText: 'Continue',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                    if (result.value !== null) {
                        var sessionContact_id = '{{ Session::get('contact_id');}}';
                        if(sessionContact_id == ''){
                            var companiesData = {}
                                jQuery.ajax({
                                        method: 'GET',
                                        url: "{{ url('/my-account/') }}",
                                        success: function(response) 
                                            {
                                                $.each(response.companies, function( index, value ) 
                                                    {
                                                    let companyID = null;
                                                    if (value.contact_id) 
                                                        {
                                                            companyID = value.contact_id+"-P";
                                                        }
                                                    if (value.secondary_id) 
                                                        {
                                                            companyID = value.secondary_id+"-S";;
                                                        }
                                                        companiesData[companyID] = value.company
                                                    });
                                        } 
                                        });
                                        const companiesDate = new Promise((resolve) => {
                                            setTimeout(() => {
                                                resolve(companiesData)
                                            }, 1000)
                                        })
                                    Swal.fire({
                                        title: 'Please choose the Company',
                                        showCancelButton: false,
                                        input:'radio',
                                        inputOptions: companiesDate,
                                        confirmButtonColor: '#8282ff',
                                        confirmButtonText: 'Continue',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false
                                    }).then((result) => {
                                        if (result.value !== null) {
                                            var contact_id = result.value;
                                            $.ajax({
                                                url: "{{ url('/switch-company-select/') }}",
                                                method: 'POST',
                                                data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        contact_id: contact_id,
                                                        },
                                                    success: function (response){
                                                        $("#order_form").submit();     
                                                    }                                  
                                            });
                                        }
                                });
                            if (result.value == 'C.O.D') {
                                $("#local_delivery_1").attr('checked', 'checked');
                            } 
                            else {
                            $("#local_delivery_2").attr('checked', 'checked'); 
                            }
                            } else {
                                if (result.value == 'C.O.D') {
                                    $("#local_delivery_1").attr('checked', 'checked');
                                }  else {
                                 $("#local_delivery_2").attr('checked', 'checked'); 
                                }
                                 $("#order_form").submit(); 
                            }
                }
        });
    }
    else {
        var sessionContact_id = '{{ Session::get('contact_id');}}';
        if(sessionContact_id == ''){
        var companiesData = {}
            jQuery.ajax({
                method: 'GET',
                url: "{{ url('/my-account/') }}",
                success: function(response) {
                    $.each(response.companies, function( index, value ) 
                    {
                        let companyID = null;
                        if (value.contact_id) 
                            {
                                companyID = value.contact_id+"-P";
                            }
                        if (value.secondary_id) 
                            {
                                companyID = value.secondary_id+"-S";;
                            }
                            companiesData[companyID] = value.company
                        });
                    } 
                });
                const companiesDate = new Promise((resolve) => {
                    setTimeout(() => {
                        resolve(companiesData)
                    }, 1000)
                })   
                Swal.fire({
                    title: 'Please choose the Company',
                    showCancelButton: false,
                    input:'radio',
                    inputOptions: companiesDate,
                    confirmButtonColor: '#8282ff',
                    confirmButtonText: 'Continue',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                if (result.value !== null) {
                    var contact_id = result.value;
                    $.ajax({
                        url: "{{ url('/switch-company-select/') }}",
                        method: 'POST',
                        data: {
                                "_token": "{{ csrf_token() }}",
                                contact_id: contact_id,
                                },
                            success: function (response){
                                $("#order_form").submit();     
                            }                                  
                    });
                }
            });
        }else {
            $("#order_form").submit();
        }
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