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
        line-height: 59.667px;
    }
    .checkout_product_quantity {
        color: #81B441;
        text-align: right;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 59.667px;
    }
    .checkout_product_price {
        color: #111;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 59.667px;
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
        line-height: 59.667px;
    }
    .checkout_shipping_price {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 59.667px;
    }
    .checkout_tax_rate {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 59.667px;
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
                        <div class="row mb-2 mt-2">
                            <div class="col-md-6 ">
                                <h5 class="update_checkout_heading mb-0">Contact</h5>
                            </div>
                            <div class="col-md-6 ">
                                <span class="float-end">
                                <span class="update_checkout_have_account">Have an account? </span> <a href="" class="update_checkout_login">Log in</a>
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
                                        <span class="success_text d-none existed_text" style="position: absolute;top:45px;right:5%;">Existed</span>
                                    </span>
                                    <div class="success_text success_div mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-12 password_div d-none">
                                <label for="" class="update_checkout_labels">Password</label>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control update_checkout_label_input password_checkout" id="password" placeholder="Password">
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
                                                <input type="text" name="first_name" id="first_name" class="form-control update_checkout_input first_name" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="last_name" id="last_name" class="form-control update_checkout_input last_name" placeholder="Last Name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="company_name" id="company_name" class="form-control update_checkout_input company_name" placeholder="Enter Company Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="street_address" id="street_address" class="form-control update_checkout_input street_address" placeholder="Street Address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="street_address_2" id="street_address_2" class="form-control update_checkout_input street_address_2" placeholder="Address 2">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="country" id="country" class="form-control update_checkout_input country" placeholder="Country">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="state" id="state" class="form-control update_checkout_input">
                                            <option value="">Select State</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="city" id="city" class="form-control update_checkout_input city" placeholder="Town/City">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="zip_code" id="zip_code" class="form-control update_checkout_input zip_code" placeholder="Zip Code">
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
                                        <input type="text" name="phone" id="phone" class="form-control update_checkout_input phone" placeholder="Phone">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="bussiness_name" id="bussiness_name" class="form-control update_checkout_input bussiness_name" placeholder="Business Name">
                                    </div>
                                </div>
                            </div>
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
                                        <input type="text" name="postalStreetAddress" id="postalStreetAddress" class="form-control update_checkout_input postalStreetAddress" placeholder="Street Address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalStreetAddress_2" id="postalStreetAddress_2" class="form-control update_checkout_input postalStreetAddress_2" placeholder="Address 2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="postalState" id="" class="form-control update_checkout_input">
                                            <option value="">Select State</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalCity" id="postalCity" class="form-control update_checkout_input postalCity" placeholder="Town/City">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="postalpostCode" id="postalpostCode" class="form-control update_checkout_input postalpostCode" placeholder="Zip Code">
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
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-5">
                        <div class="row mb-0 mt-2">
                            <h5 class="p-0 checkout_default_address">Cart Total</h5>
                        </div>
                        <div class="row cart_total_div">
                            <div class="border m-0 rounded">
                                <div class="row p-3">
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
                                                <div class="row justify-content-center border-bottom ">
                                                    <div class="col-md-9"><span class="checkout_product_title">{{ $cart['name'] }}</span></div>
                                                    <div class="col-md-1"><span class="checkout_product_quantity">{{$total_quatity . 'x'}}</span></div>
                                                    <div class="col-md-2 text-right"><span class="checkout_product_price ">${{ number_format($total_price, 2) }}</span></div>
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
                                            <div class="col-md-9"><span class="checkout_subtotal_heading">Subtotal</span></div>
                                            <div class="col-md-3 text-right"><span class="checkout_subtotal_price">${{ number_format($cart_total, 2) }}</span></div>
                                        </div>
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9">
                                                <span class="checkout_tax_rate_heading">
                                                    Tax Rate {{!empty($tax_class) ? '('.number_format($tax_class->rate  , 2).'%)' : '('. number_format(0  , 2) . ')'}}
                                                </span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <span class="checkout_tax_rate">
                                                    ${{ number_format($tax, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9"><span class="checkout_shipping_heading">Shipment Price</span></div>
                                            <div class="col-md-3 text-right"><span class="checkout_shipping_price">${{number_format($shipment_price , 2)}}</span></div>
                                        </div>
                                        <div class="row justify-content-center  align-items-center py-2">
                                            <div class="col-md-9"><span class="checkout_total_heading">Total</span></div>
                                            <div class="col-md-3 text-right"><span class="checkout_total_price">${{ number_format($total_including_tax, 2) }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <button class="btn check_out_pay_now w-100 p-3" type="button" id="checkout">Checkout</button>
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
                            $('.existed_text').removeClass('d-none');
                            $('.success_div').html('Please enter your password to continue.')
                            $('.password_div').removeClass('d-none');
                        } else {
                            $('.existed_text').addClass('d-none');
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
            if (email != '') {
                $.ajax({
                    url: '/authenticate-user',
                    type: 'post',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            if (response.access === true) {
                                if (response.is_admin === true) {
                                    window.location.href = '/admin/dashboard';
                                } else {
                                    window.location.href = '/checkout';
                                }
                            } else {
                                $('.error_div').text(response.message);
                            }
                        } else {
                            $('.error_div').text(response.message);
                        }
                    }
                });
            }
        });
    });
    
</script>