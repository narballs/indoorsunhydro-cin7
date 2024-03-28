@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .update_checkout_labels {
        color: #111;
        font-family: 'Poppins';
        font-size: 16px !important;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .update_checkout_label_input {
        color: #111;
        font-family: 'Poppins';
        font-size: 15px !important;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .update_checkout_have_account {
        color: #444;
        text-align: right;
        font-family: 'Roboto';
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 21px; /* 116.667% */
    }
    .update_checkout_login {
        color: #7CC633;
        font-family: 'Roboto';
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 21px;
        text-decoration-line: underline;
    }
    .success_text {
        color: #7CC633;
        font-family: 'Poppins';
        font-size: 14;
        font-style: normal;
        font-weight: 500;
        line-height: 15px;
    }
    .error_text {
        color: #b91f1a;
        font-family: 'Poppins';
        font-size: 14;
        font-style: normal;
        font-weight: 500;
        line-height: 15px;
    }
    .checkout_validation_errors {
        color: #b91f1a;
        font-family: 'Poppins';
        font-size: 14;
        font-style: normal;
        font-weight: 500;
        line-height: 15px;
    }
    .update_checkout_input {
        border-radius: 4px;
        border: 1px solid #DDD;
        color: #555;
        font-family: 'Poppins';
        font-size: 15px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .update_checkout_input:focus {
        border-radius: 4px;
        border: 1px solid #DDD;
        box-shadow: 0 0 0 0;
    }
    .update_checkout_heading {
        color: #111;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .ship_to_address_label {
        color: #555;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px; /* 150% */
    }
    .cart_total_div {
        background: #F9F9F9;
    }
    .checkout_product_heading {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 24px; /* 141.176% */
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .checkout_product_title {
        color: #008BD3;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        /* line-height: 59.667px; */
    }
    .checkout_product_quantity {
        color: #81B441;
        text-align: right;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        /* line-height: 59.667px; */
    }
    .checkout_product_price {
        color: #111;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        /* line-height: 59.667px; */
    }
    .checkout_coupen_btn {
        color:#FFF;
        text-align: center;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 17px; /* 106.25% */
        text-transform: uppercase;
        border-radius: 3px;
        background-color: #7BC533;
        padding: 16px 20.031px 17px 20px;
    }

    .checkout_coupen_btn:hover {
        color: #fff;
        background-color: #7BC533;
    }
    .coupen_code_input {
        color: #555;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        border-radius: 3px;
        border: 1px solid #DDD;
        background-color: #fff;
        padding: 12.75px 0px 12.75px 19px;
    }
    .coupen_code_input:focus {
        outline: none;
        box-shadow: none;
    }

    .checkout_subtotal_heading {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-transform: uppercase;
    }
    .checkout_shipping_heading {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-transform: uppercase;
    }
    .checkout_subtotal_price {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_shipping_price {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_tax_rate {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_tax_rate_heading {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-transform: uppercase;
    }
    .checkout_total_heading {
        color: #212529;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_total_price {
        color: #7CC633;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }
    .check_out_pay_now {
        border-radius: 3px;
        background-color: #7CC633;
        text-align: center;
        font-family: 'Poppins';
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 17px; /* 94.444% */
        color: #FFF;
    }

    .check_out_pay_now:hover {
        color: #FFF;
        background-color: #7CC633;
    }

    .check_out_pay_now:focus {
        color: #FFF;
        background-color: #7CC633;
    }

    @media only screen and (max-width: 600px) and (min-width:280px) {
        .checkout_product_title {
            line-height: 21px !important;
            font-size: 14px !important;
        }
        .checkout_product_quantity , .checkout_product_price , .checkout_tax_rate_heading , .checkout_shipping_heading {
            line-height: 19.5px !important;
            font-size: 13px !important;
        }
        .checkout_subtotal_heading {
            font-size: 16px !important;
            line-height: 32.87px !important;
        }
        .checkout_total_heading , .checkout_total_price {
            line-height: 27px !important;
            font-size: 18px !important;
        }
        .checkout_subtotal_price {
            font-size: 16px !important;
            line-height: 23.28px !important;

        }
        .custom-padding {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    }
    @media only screen and (max-width: 1700px) and (min-width: 1200px) {
        .custom-width {
            min-width: 48%;
            max-width: 48%;
        }
        .checkout_total_price {
            font-size: 20px;
        }
        .checkout_total_price , .checkout_subtotal_price {
            font-size: 20px;
        }
        .checkout_product_price {
            font-size: 14px;
        }
    }
    .custom-padding {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
</style>
<div class="mb-4 desktop-view">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        Checkout
    </p>
</div>

<?php
$cart_total = 0;
$cart_price = 0;
?>
@if (Session::get('cart'))
    @foreach (Session::get('cart') as $cart)
        <?php
        $total_quatity = $cart['quantity'];
        $total_price = $cart['price'] * $total_quatity;
        $cart_total = $cart_total + $total_price;
        ?>
    @endforeach
@endif
<?php $zip_code_is_valid = true; ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form action="" class="checkout_form">
                <div class="row justify-content-between">
                    <div class="col-md-12 col-lg-12 col-xl-5">
                        <div class="row mb-2 mt-2 align-items-center">
                            <div class="col-md-6 col-4">
                                <h5 class="update_checkout_heading mb-0">Contact</h5>
                            </div>
                            <div class="col-md-6 col-8">
                                <span class="float-end">
                                <span class="update_checkout_have_account">Have an account? </span> <a href="{{url('/user')}}" class="update_checkout_login">Log in</a>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <label for="" class="update_checkout_labels">Email</label>
                                <div class="input-group">
                                    <input type="text" name="email_address_checkout" id="email_address_checkout" class="form-control update_checkout_label_input email_address_checkout" onchange="check_email()" placeholder="Please Enter your Email">
                                    <span class="input-group-text">
                                        <i class="fas fa-check" style="display: none;"></i>
                                    </span>
                                </div> --}}
                                <label for="" class="update_checkout_labels">Email</label>
                                <div class="form-group">
                                    <input type="text" name="email_address_checkout" class="form-control update_checkout_label_input email_address_checkout" id="email_address_checkout" placeholder="Enter your email">
                                    <span class="">
                                        <span class="success_text existed_text" style="position: absolute;top:45px;right:5%;"></span>
                                    </span>
                                    <div class="success_text success_div mt-1"></div>
                                    <div class="error_text error_div_email mt-1"></div>
                                    <div class="checkout_validation_errors email_errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12 password_div d-none">
                                <label for="" class="update_checkout_labels">Password</label>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control update_checkout_label_input password_checkout" id="password" placeholder="Password">
                                    <div class=" password_errors checkout_validation_errors"></div>
                                </div>
                            </div>
                            <div class="error_div error_text"></div>
                            
                        </div>
                        <div class="billing_div d-none">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="update_checkout_heading">Billing details</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="first_name" id="first_name" class="form-control update_checkout_input mb-1 first_name" placeholder="First Name">
                                                <div class=" first_name_errors checkout_validation_errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="last_name" id="last_name" class="form-control update_checkout_input mb-1 last_name" placeholder="Last Name">
                                                <div class=" last_name_errors checkout_validation_errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="company_name" id="company_name" class="form-control update_checkout_input mb-1 company_name" placeholder="Enter Company Name">
                                        <div class="company_name_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="street_address" id="street_address" class="form-control update_checkout_input mb-1 street_address" placeholder="Street Address">
                                        <div class="street_address_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="street_address_2" id="street_address_2" class="form-control update_checkout_input mb-1 street_address_2" placeholder="Address 2">
                                        <div class="street_address_2_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="country" id="country" readonly value="US" class="form-control update_checkout_input country" placeholder="Country">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="state" id="state" class="form-control update_checkout_input mb-1 state">
                                            <option value="">Select State</option>
                                            @if (count($states) > 0)
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class=" state_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="city" id="city" class="form-control update_checkout_input mb-1 city" placeholder="Town/City">
                                        <div class=" city_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="zip_code" id="zip_code" class="form-control update_checkout_input mb-1 zip_code" placeholder="Zip Code">
                                        <div class=" post_code_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="email_address" id="email_address" class="form-control update_checkout_input email_address" placeholder="Email Address">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="phone" id="phone" class="form-control update_checkout_input mb-1 phone" placeholder="Phone">
                                        <div class=" phone_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="bussiness_name" id="bussiness_name" class="form-control update_checkout_input bussiness_name" placeholder="Business Name">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group d-flex">
                                        <input type="checkbox" name="ship_to_different_address" id="ship_to_different_address" class="update_checkout_input ship_to_different_address">
                                        <span class="ship_to_address_label border-0 mx-2">Ship to a different address?</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="notes" id="notes" class="form-control update_checkout_input notes" cols="30" rows="5" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                        </div> 
                                </div>
                            </div>
                        </div>
                        <div class="shipping_div d-none">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="update_checkout_heading">Shipping details</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalStreetAddress" id="postalStreetAddress" class="form-control update_checkout_input mb-1 postalStreetAddress" placeholder="Street Address">
                                        <div class=" postal_address_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalStreetAddress_2" id="postalStreetAddress_2" class="form-control update_checkout_input mb-1 postalStreetAddress_2" placeholder="Address 2">
                                        <div class=" postal_address_2_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="postalState" id="" class="form-control update_checkout_input mb-1 postalState">
                                            <option value="">Select State</option>
                                            @if (count($states) > 0)
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="checkout_validation_errors postal_state_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalCity" id="postalCity" class="form-control update_checkout_input mb-1 postalCity" placeholder="Town/City">
                                        <div class=" postal_city_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalpostCode" id="postalpostCode" class="form-control update_checkout_input mb-1 postalpostCode" placeholder="Zip Code">
                                        <div class=" postalpostCode_errors checkout_validation_errors"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="email_address" id="email_address" class="form-control update_checkout_input email_address" placeholder="Email Address">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button class="btn check_out_pay_now w-100 p-3 d-flex align-items-center justify-content-center" type="button" id="checkout">
                                    <span>Save & Next</span>
                                    <span class="update_checkout_loader d-none mx-2">
                                        <div class="spinner-border text-white update_checkout_loader" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="Pay in Advanced" id="paymentTerms">
                    <div class="col-md-12 col-lg-12 col-xl-5">
                        <div class="row mb-0 mt-2">
                            <div class="col-md-12">
                                <h5 class="checkout_default_address">Cart Total</h5>
                            </div>
                        </div>
                        {{-- <div class="row cart_total_div"> --}}
                            {{-- <div class="border m-0 rounded"> --}}
                                <div class="row pt-2 pb-2 cart_total_div border m-0 rounded">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="checkout_product_heading">Product</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <?php
                                            $cart_total = 0;
                                            $cart_price = 0;
                                            $item_quantity = 0;
                                        ?>
                                        @if ($cart_items)
                                            @foreach ($cart_items as $product_id => $cart)
                                                <?php
                                                    $total_quatity = $cart['quantity'];
                                                    $total_price = $cart['price'] * $total_quatity;
                                                    $item_quantity = $item_quantity + $total_quatity;
                                                    $cart_total = $cart_total + $total_price;
                                                ?>
                                                <div class="row border-bottom custom-padding">
                                                    <div class="col-xl-8 col-md-8 col-7"><span class="checkout_product_title">{{ $cart['name'] }}</span></div>
                                                    <div class="col-xl-1 col-md-1 col-2"><span class="checkout_product_quantity">{{$total_quatity . 'x'}}</span></div>
                                                    <div class="col-xl-3 col-md-3 col-3 text-right"><span class="checkout_product_price ">${{ number_format($total_price, 2) }}</span></div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @php
                                            $tax=0;
                                            if (!empty($tax_class)) {
                                                $tax = $cart_total * ($tax_class->rate / 100);
                                            }
                                            $total_including_tax = $tax + $cart_total  + $shipment_price;
                                        @endphp
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9 col-9"><span class="checkout_subtotal_heading">Subtotal</span></div>
                                            <div class="col-md-3 col-3 text-right"><span class="checkout_subtotal_price">${{ number_format($cart_total, 2) }}</span></div>
                                        </div>
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9 col-9">
                                                <span class="checkout_tax_rate_heading">
                                                    Tax Rate {{!empty($tax_class) ? '('.number_format($tax_class->rate  , 2).'%)' : '('. number_format(0  , 2) . ')'}}
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-3 text-right">
                                                <span class="checkout_tax_rate">
                                                    ${{ number_format($tax, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9 col-9"><span class="checkout_shipping_heading">Shipment Price</span></div>
                                            <div class="col-md-3  col-3 text-right"><span class="checkout_shipping_price">${{number_format($shipment_price , 2)}}</span></div>
                                        </div>
                                        <div class="row justify-content-center  align-items-center py-2">
                                            <div class="col-md-9 col-9"><span class="checkout_total_heading">Total</span></div>
                                            <div class="col-md-3 col-3 text-right"><span class="checkout_total_price">${{ number_format($total_including_tax, 2) }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button class="btn check_out_pay_now w-100 p-3 d-flex align-items-center justify-content-center" type="button" id="checkout">
                                    <span>Save & Next</span>
                                    <span class="update_checkout_loader d-none mx-2">
                                        <div class="spinner-border text-white update_checkout_loader" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
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
                <button type="button" class="btn btn-primary w-75 applay-coupon-code-modal-btn">Apply
                    coupon</button>
            </div>
        </div>
    </div>
</div>
@include('partials.product-footer')
@include('partials.footer')

<script>
    $(document).ready(function() {
        $('.ship_to_different_address').change(function() {
            if ($(this).is(':checked')) {
                $('.shipping_div').removeClass('d-none');
            } else {
                $('.shipping_div').addClass('d-none');
            }
        });

        $('.email_address_checkout').on('change', function(e) {
            e.preventDefault();
            var email = $(this).val();
            if (email != '') {
                $.ajax({
                    url: '/check-existing-email',
                    type: 'get',
                    data: {
                        email: email,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.error_div_email').html('');
                            $('.existed_text').html(response.user_status);
                            $('.success_div').html(response.message);
                            $('.password_div').removeClass('d-none');
                            $('.billing_div').addClass('d-none');
                            $('.shipping_div').addClass('d-none');
                        } else {
                            $('.success_div').html('');
                            $('.existed_text').html(response.user_status);
                            $('.error_div_email').html(response.message);
                            $('.billing_div').removeClass('d-none');
                            $('.password_div').removeClass('d-none');
                        }
                    }
                });
            }
        });
        $('.check_out_pay_now').on('click', function(e) {
            e.preventDefault();
            var email = $('.email_address_checkout').val();
            var password = $('.password_checkout').val();
            var company_name = $('.company_name').val();
            var first_name = $('.first_name').val();
            var last_name = $('.last_name').val();
            var address= $('.street_address').val();
            var address_2= $('.street_address_2').val();
            var country= $('.country').val();
            var state= $('.state').val();
            var city= $('.city').val();
            var zip_code= $('.zip_code').val();
            var phone= $('.phone').val();
            var postal_address1= $('.postalStreetAddress').val();
            var postal_address2= $('.postalStreetAddress_2').val();
            var postal_state= $('.postalState').val();
            var postal_city= $('.postalCity').val();
            var postal_zip_code= $('.postalpostCode').val();
            var different_shipping_address = $('.ship_to_different_address').is(':checked') ? 1 : 0;
            if (email != '' && password != '') {
                $('.update_checkout_loader').removeClass('d-none');
                $.ajax({
                    url: '/authenticate-user',
                    type: 'post',
                    data: {
                        email: email,
                        password: password,
                        different_shipping_address: different_shipping_address,
                        company: company_name,
                        first_name: first_name,
                        last_name: last_name,
                        address: address,
                        address_2: address_2,
                        country: country,
                        state: state,
                        city: city,
                        zip_code: zip_code,
                        phone: phone,
                        postal_address1: postal_address1,
                        postal_address2: postal_address2,
                        postal_state: postal_state,
                        postal_city: postal_city,
                        postal_zip_code: postal_zip_code,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            if (response.access === true) {
                                if (response.auto_approved == true) {
                                    if (response.is_admin === true) {
                                        window.location.href = '/admin/dashboard';
                                    } else {
                                        window.location.href = '/checkout';
                                    }
                                } else {
                                    window.location.href = '/cart';
                                }
                            } else {
                                $('.update_checkout_loader').addClass('d-none');
                                $('.error_div').text(response.message);
                            }
                        } else {
                            if (response.registration_status == true) {
                                if (response.auto_approved == true) {
                                    $('.update_checkout_loader').addClass('d-none');
                                    $('.error_div').text(response.message);
                                    window.location.href = '/checkout';
                                } else {
                                    $('.update_checkout_loader').addClass('d-none');
                                    $('.error_div').text(response.message);
                                    window.location.href = '/cart';
                                }
                                
                            } else {
                                $('.update_checkout_loader').addClass('d-none');
                                $('.error_div').text(response.message);
                            }
                        }
                    },
                    error: function(response) {
                        $('.update_checkout_loader').addClass('d-none');
                        var errors = response.responseJSON.errors;
                        if (errors) {
                            if (errors.email) {
                                error_email = errors.email[0];
                                $('.email_errors').html(error_email);
                            }
                            else {
                                $('.email_errors').html('');
                            }
                            if (errors.first_name) {
                                error_first_name = errors.first_name[0];
                                $('.first_name_errors').html(error_first_name);
                            }
                            else {
                                $('.first_name_errors').html('');
                            }

                            if (errors.address) {
                                var error_billing_address = errors.address[0];
                                $('.street_address_errors').html(error_billing_address);
                            }
                            else {
                                $('.street_address_errors').html('');
                            }

                            if (errors.state) {
                                var error_billing_state = errors.state[0];
                                $('.state_errors').html(error_billing_state);
                            }
                            else {
                                $('.state_errors').html('');
                            }

                            if (errors.company) {
                                var error_company = errors.company[0];
                                $('.company_name_errors').html(error_company);
                            }
                            else {
                                $('.company_name_errors').html('');
                            }

                            if (errors.zip_code) {
                                var error_zip_code = errors.zip_code[0];
                                $('.post_code_errors').html(error_zip_code);
                            }
                            else {
                                $('.post_code_errors').html('');
                            }

                            if (errors.phone) {
                                var error_phone = errors.phone[0];
                                $('.phone_errors').html(error_phone);
                            }
                            else {
                                $('.phone_errors').html('');
                            }
                            
                            if (errors.postal_address1) {
                                var error_postal_address = errors.postal_address1[0];
                                $('.postal_address_errors').html(error_postal_address);
                            }
                            else {
                                $('.postal_address_errors').html('');
                            }

                            

                            if (errors.postal_state) {
                                var error_state = errors.postal_state[0];
                                $('.postal_state_errors').html(error_state);
                            }
                            else {
                                $('.postal_state_errors').html('');
                            }

                            if (errors.postal_zip_code) {
                                var error_text_postal_code = errors.postal_zip_code[0];
                                $('.postalpostCode_errors').html(error_text_postal_code);
                            }
                            else {
                                $('.postalpostCode_errors').html('');
                            }
                        }
                    }
                });
            } else {
                $('.error_div').text('Please enter your email and password');
                return false;
            }
        });
    });
    
</script>