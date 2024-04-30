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
        border-color: #DFDFDF;
    }

    .checkout_address_heading {
        color: #767676;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 59.667px; /* 372.919% */
    }
    .checkout_address_text {
        color: #131313;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 59.667px; /* 372.919% */
    }
    .checkout_address_btn {
        color: #006FBE;
        text-align: right;
        font-family: 'Poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 59.667px; /* 426.194% */
    }
    .checkout_main_head_address {
        color: #111;
        font-family: 'Poppins';
        font-size: 26px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 138.462% */
    }
    .checkout_main_sub_address {
        color: #111;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 24px; /* 141.176% */
        letter-spacing: 0.5px;
        text-transform: uppercase;
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
        color: #212529;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_subtotal_price {
        color: #111;
        text-align: right;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_tax_rate_heading , .checkout_discount_rate_heading , .checkout_discount_rate_heading_manuall {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-transform: uppercase;
        line-height: 36px; /* 150% */
    }
    .checkout_tax_rate , .checkout_discount_rate, .checkout_discount_rate_manuall {
        color: #111;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_shipping_heading {
        color: #555;
        font-family: 'Poppins';
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-transform: uppercase;
        line-height: 36px; /* 150% */
    }
    .checkout_shipping_price{
        color: #111;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
    }
    .checkout_surcharge_price{
        color: #111;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
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
        color: #212529;
        font-family: 'Poppins';
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 36px; /* 150% */
        color:#7CC633;
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
    
    .stripe-radio-label {
        background-image: url('/theme/bootstrap5/images/stripe_logo_new.png');
        background-repeat: no-repeat;
        background-position: center;
        width: 100%;
        height: 60px;
        background-size: contain;
    }
    .square-radio-label {
        background-image: url('/theme/bootstrap5/images/square_payment_logo.png');
        background-repeat: no-repeat;
        background-position: center;
        width: 100%;
        height: 60px;
    }
    .manuall-radio-label {
        font-size: 20px;
        font-weight: 400;
        width: 100%;
        text-align: center;
        padding-top: 15px;
        padding-bottom: 15px;
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
        .checkout_address_heading {
            font-size: 13px;
            line-height: 19.5px;
        }
        .checkout_address_text {
            line-height: 21px;
            font-size: 14px;
            color: #131313;
        }
        .custom_address_padding {
            padding-top: 0.5rem;
            padding-bottom:0.5rem;
        }
    }
    .custom-padding {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    @media only screen and (max-width: 550px) and (min-width: 280px) {
        .proceedCheckoutmbl {
            border-radius: 5px;
            background: #008BD3;
            border-color: #008BD3;
            color: #FFF;
            font-family: 'Roboto';
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            letter-spacing: 0.16px;
        }
        .billing-address-thank-you-page-heading {
            font-size: 20px;
            line-height:normal;
            padding-bottom:0px;
        }
        .shipping-body-div-mbl {
            border-bottom: 2px dashed #CDCDCD;
        }
        .mbl-checkout-card {
            background: #FAFAFA; 
            border-radius: 6px;
        }
        .user-address-thank-you-page-title , .user-address-thank-you-page-item {
            font-size:13.35px;
            line-height:normal;
        }
        .cart-items-checkout {
            color: #000;
            font-family: 'Roboto';
            font-size: 20.862px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }
        .border_top_mb {
			border-top:1px solid #DFDFDF !important;
		}
        .m_chechout_image {
			width: 57.08px !important;
			height: 57.08px !important;
		}
        .prod-name-img {
            margin-left: -48px !important;
            margin-top:3px;
        }
        .order-confirmation-page-product-category-name {
			font-size: 12px !important;
		}
        .order-confirmation-page-product-price {
            font-size: 16px !important;
        }
        .order-confirmation-page-product-title {
            padding-top:2px;
            margin-bottom:0px;
        }
        .checkout-image-td-mbl {
            width: 20% !important;
            vertical-align:baseline;
        }
        .checkout-product-name-td-mbl {
            width: 80% !important;
            vertical-align:middle;
        }
        .product-title-thank-you-page , .product-title-thank-you-page-title {
            font-size: 12px;
            line-height: 19px;
        }
        .delievery_options_div_mbl{
            border-radius: 5.245px 5.245px 0px 0px;
            background: #FAFAFA;
        }
        .delievery_options_heading {
            color: #000;
            font-family: 'Roboto';
            font-size: 18.881px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }
        .delievery_label {
            color: #303030;
            font-family: 'Poppins';
            font-size: 14.686px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }
        .suborderSummarymbl_main {
			background: #FAFAFA;
		}
		.suborderSummarymbl{
			background: #F7F7F7;
			border-radius: 5.24485px;
		}
        .delievery_options_mbl {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 18.8814px;
			line-height: 22px;

			color: #000000;

		}
        .summary_sub_total_head {
			
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 400;
			font-size: 16.7835px;
			line-height: 20px;
			color: #303030;

		}
		.summary_sub_total_price {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 16.7835px;
			line-height: normal;
			color: #000000;

		}
        .summary_total_price {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 24px;
			line-height: normal;
			color: #000000;
		}
        .mbl-footer-modal-address {
            align-items: center;
            justify-content: center
        }
        .mbl-btn-update-address {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50% !important;
        }
        
        @media (max-width: 550px) {
            .main_row_checkout {
                justify-content: center ;
            }
        }
    }
    .payment-custom-radio.selected {
        outline: 2px solid #7CC633;
    }
    .payment-hidden-radio {
        display: none;
    }
    .payment-custom-radio {
        cursor: pointer;
    }
</style>
<div class="mb-4 desktop-view">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        Checkout
    </p>
</div>

@if (\Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        {!! \Session::get('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
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
<?php $zip_code_is_valid = true;
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form action="{{ url('order') }}" method="POST" id="order_form" name="order_form" class="">
                <input type="hidden" name="charge_shipment_to_customer" id="charge_shipment_to_customer" value="{{$charge_shipment_to_customer}}">
                <div class="row main_row_checkout justify-content-between">
                    <div class="col-md-12 col-lg-12 col-xl-5 col-12 order-xl-2 custom-width">
                        <div class="row mt-2 mb-2">
                            <div class="col-md-12">
                                <h5 class="checkout_default_address">Cart Total</h5>
                            </div>
                        </div>
                        {{-- <div class="row cart_total_div"> --}}
                            {{-- <div class="border m-0 rounded"> --}}
                                <div class="row pb-2 pt-2 border m-0 rounded cart_total_div">
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
                                        
                                        <div class="row justify-content-center border-bottom py-3">
                                            <div class="col-md-12">
                                                <p class="checkout_product_heading ml-0 mb-2">Delivery Options</p>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row justify-content-between">
                                                    @foreach ($payment_methods as $payment_method)
                                                        @php
                                                            $session_contact_id = Session::get('contact_id');
                                                        @endphp
                                                        @csrf
                                                        @foreach ($payment_method->options as $payment_option)
                                                            <div class="col-md-12">
                                                                <input type="hidden" value="{{ $payment_method->name }}"
                                                                    name="method_name">
                                                                <input type="radio" id="local_delivery_{{ $payment_option->id }}"
                                                                    name="method_option"{{ $payment_option->option_name == 'Delivery' ? 'checked' : '' }}
                                                                    value="{{ $payment_option->option_name }}" style="background: #008BD3;">
                                                                <label for="local_delivery payment-option-label"
                                                                    class="checkout_product_heading ml-2 mb-0">{{ $payment_option->option_name }}
                                                                
                                                                </label>
                                                                @if (strtolower($payment_option->option_name) == 'pickup order')
                                                                    <span class="mx-2">
                                                                        (Monday - Friday 9:00 AM - 5:00 PM only)
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom py-3">
                                            <div class="col-md-12 mt-1">
                                                <p class="checkout_product_heading mb-1 ml-0">Please Select Date (Optional)</p>
                                                <input type="datetime-local" name="date" class="checkout_product_heading form-control datetime_" min="" id="date">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom py-3">
                                            <div class="col-md-12">
                                                <p class="checkout_product_heading mb-1 ml-0">Purchase Order Number (Optional)</p>
                                                <input type="text" name="po_number" placeholder="PO Number" id="po_number"
                                                    class="form-control fontAwesome checkout_product_heading">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom py-3">
                                            <div class="col-md-12">
                                                <p class="checkout_product_heading mb-1 ml-0">Memo (Optional)</p>
                                                <textarea type="text" name="memo" cols="20" rows="5" placeholder="Enter your Message"
                                                    id="memo" class="form-control fontAwesome">
                                                    </textarea>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center border-bottom py-3">
                                            <div class="col-md-12 mb-3">
                                                <p class="checkout_product_heading mb-2 ml-0">Payment Terms</p>
                                                <select name="paymentTerms" id="pay_terms" class="form-control checkout_product_heading">
                                                    @if(strtolower($user_address->paymentTerms) == "pay in advanced" )
                                                        <option value="Pay in Advanced" selected>Pay in Advanced</option>
                                                    @else
                                                        <option value="30 days from invoice" selected>30 days from invoice</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @php
                                            $discount_amount = 0;
                                            $remove_discount = 0;
                                            if (!empty($discount_code) && strtolower($discount_code->mode) === 'automatic') {
                                                if ($discount_code->discount_variation === 'percentage') {
                                                    $discount_amount = $cart_total * ($discount_code->discount_variation_value / 100);
                                                } else {
                                                    $discount_amount = $discount_code->discount_variation_value;
                                                }
                                            } 
                                            $tax=0;
                                            if (!empty($tax_class)) {
                                                $tax = $cart_total * ($tax_class->rate / 100);
                                            }
                                            $remove_discount = $cart_total - $discount_amount;   
                                            $total_including_tax = $tax + $remove_discount  + $shipment_price;
                                            
                                        @endphp
                                        <input type="hidden" name="address_1_billing" value="{{ !empty($user_address->postalAddress1) ?  $user_address->postalAddress1 : '' }}">
                                        <input type="hidden" name="state_billing" value="{{ !empty($user_address->postalState) ?  $user_address->postalState : '' }}">
                                        <input type="hidden" name="zip_code_billing" value="{{ !empty($user_address->postalPostCode) ?  $user_address->postalPostCode : '' }}">
                                        
                                        {{-- shipping --}}
                                        <input type="hidden" name="address_1_shipping" value="{{ !empty($user_address->address1) ?  $user_address->address1 : '' }}">
                                        <input type="hidden" name="state_shipping" value="{{ !empty($user_address->state) ?  $user_address->state : '' }}">
                                        <input type="hidden" name="zip_code_shipping" value="{{ !empty($user_address->postCode) ?  $user_address->postCode : '' }}">
                                        <input type="hidden" name="incl_tax" id="incl_tax" value="{{ number_format($total_including_tax, 2, '.', '') }}">
                                        <input type="hidden" name="original_shipment_price" id="original_shipment_price" value="{{ $shipment_price }}">
                                        <input type="hidden" name="shipment_price" id="shipment_price" value="{{ $shipment_price }}">
                                        <input type="hidden" name="discount_amount" class="discount_amount" id="discount_amount" value="{{ number_format($discount_amount , 2, '.', '') }}">
                                        <input type="hidden" name="items_total_price" class="items_total_price" id="" value="{{ number_format($cart_total, 2, '.', '') }}">
                                        <input type="hidden" name="total_tax" class="total_tax" id="" value="{{ number_format($tax , 2, '.', '') }}">
                                        <input type="hidden" name="shipping_free_over_1000" id="shipping_free_over_1000" value="{{$shipping_free_over_1000}}">
                                        @if(!empty($tax_class))
                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{ $tax_class->id }}">
                                        @else
                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class_none->id}}">
                                        @endif
                                        
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9 col-8"><span class="checkout_subtotal_heading">Subtotal</span></div>
                                            <div class="col-md-3  col-4 text-right"><span class="checkout_subtotal_price">${{ number_format($cart_total, 2) }}</span></div>
                                        </div>
                                        @if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) === 'yes')
                                            @if (!empty($discount_code))
                                                <input type="hidden" name="discount_id" class="" id="" value="{{$discount_code->id}}">
                                                <input type="hidden" name="discount_mode" class="discount_mode" value="{{strtolower($discount_code->mode) === 'automatic' ?  'automatic' : 'manuall'}}">
                                                <input type="hidden" name="user_contact_id" id="" class="user_contact_id" value="{{!empty($user_address->contact_id) ? $user_address->contact_id : ''}}">
                                                <input type="hidden" name="discount_variation" id="" class="discount_variation" value="{{$discount_code->discount_variation}}">
                                                <input type="hidden" name="discount_variation_value" id="" class="discount_variation_value" value="{{$discount_code->discount_variation_value}}">
                                                @if (strtolower($discount_code->mode) === 'manuall')
                                                    <div class="row my-3 align-items-center discount_form">
                                                        <p for="" class="checkout_product_heading mb-2 ml-0">Enter Promo Code</p>
                                                        <div class="col-md-9">
                                                            <div class="form-group mb-0">
                                                                <input type="text" name="coupen_code" id="coupen_code" class="coupen_code_input form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group mb-0">
                                                                <input type="button" class="checkout_coupen_btn btn btn-sm w-100" value="Apply" onclick="apply_discount_code()">
                                                            </div>
                                                        </div>
                                                        <div class="coupen_code_message text-info"></div>
                                                    </div>
                                                    <div class="row justify-content-center border-bottom align-items-center py-2 manuall_discount d-none">
                                                        <div class="col-md-9 col-8">
                                                            <span class="checkout_discount_rate_heading_manuall">
                                                                @if ($discount_code->discount_variation === 'percentage')
                                                                    Discount ({{$discount_code->discount_variation_value . '%'}})
                                                                @else
                                                                    Discount (${{ number_format($discount_code->discount_variation_value, 2) }})
                                                                @endif
                                                            </span>
                                                            <span class="coupen_code_name">(<label for="">Coupon Code : </label>{{!empty($discount_code) && !empty($discount_code->discount_code)  ? $discount_code->discount_code : ''}}</span>)
                                                            <span class="remove_coupen_code"><a href="{{url('/checkout')}}">Remove Code</a></span>
                                                        </div>
                                                        <div class="col-md-3 col-4 text-right">
                                                            <span class="checkout_discount_rate_manuall">
                                                                ${{ number_format($discount_amount, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @else
                                                <div class="row my-2 justify-content-center border-bottom align-items-center py-2">
                                                        <p for="" class="checkout_product_heading mb-2 ml-0">Enter Promo Code</p>
                                                        <div class="col-md-9 col-8">
                                                            <span class="checkout_discount_rate_heading">
                                                                @if ($discount_code->discount_variation === 'percentage')
                                                                    Discount ({{$discount_code->discount_variation_value . '%'}})
                                                                @else
                                                                    Discount (${{ number_format($discount_code->discount_variation_value, 2) }})
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="col-md-3 col-4 text-right">
                                                            <span class="checkout_discount_rate">
                                                                ${{ number_format($discount_amount, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        <div class="row justify-content-center border-bottom align-items-center py-2">
                                            <div class="col-md-9 col-8">
                                                <span class="checkout_tax_rate_heading">
                                                    Tax Rate {{!empty($tax_class) ? '('.number_format($tax_class->rate  , 2).'%)' : '('. number_format(0  , 2) . ')'}}
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-4 text-right">
                                                <span class="checkout_tax_rate">
                                                    ${{ number_format($tax, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{$products_weight}}" name="product_weight" class="product_weight">
                                        @php
                                            $surcharge_value = 0;
                                        @endphp
                                        @if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes')
                                            <input type="hidden" name="admin_control_shipping" id="admin_control_shipping" value="true">
                                            <input type="hidden" name="shipment_error" value="{{$shipment_error}}">
                                            @if (!empty($products_weight) && $products_weight > 150)
                                                <input type="hidden" name="shipping_carrier_code" id="" value="{{$shipping_carrier_code}}">
                                                <input type="hidden" name="shipping_service_code" id="" value="{{$shipping_service_code}}">
                                                <input type="hidden" name="shipment_cost_single" id="shipment_price_heavy_weight" value="{{!empty($shipment_price) ? number_format($shipment_price , 2, '.', '')  : 0 }}">
                                                <div class="row justify-content-center border-bottom align-items-center py-2">
                                                    @if ($shipment_error == 1)
                                                        <div class="col-md-12">
                                                            <span class="checkout_shipping_price text-danger">
                                                                There was an issue getting a freight quote, please try again later
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="col-md-9 col-8"><span class="checkout_shipping_heading">Shipment Price</span></div>
                                                        <div class="col-md-3 col-4 text-right"><span class="checkout_shipping_price">${{!empty($shipment_price)  ? number_format($shipment_price , 2)  : 0}}</span></div>
                                                    @endif
                                                    {{-- <div class="col-md-3 col-3 text-right"><span class="checkout_shipping_price">${{number_format($shipment_price , 2)}}</span></div> --}}
                                                </div>
                                            @else
                                                <div class="row justify-content-center border-bottom align-items-center py-2">
                                                    @if (count($admin_selected_shipping_quote) > 0)
                                                        <div class="col-md-12">
                                                            <p class="checkout_product_heading ml-0 mb-2">Shipping Methods</p>
                                                        </div>
                                                        @if (count($admin_selected_shipping_quote) == 1)
                                                            @foreach ($admin_selected_shipping_quote as $shipping_quote)
                                                                @php
                                                                    $shipment_cost_without_surcharge = $shipping_quote->shipmentCost + $shipping_quote->otherCost;
                                                                    if (!empty($surcharge_settings) && $surcharge_settings->apply_surcharge == 1) {
                                                                        if (!empty($surcharge_settings->surcharge_type) && $surcharge_settings->surcharge_type == 'fixed') {
                                                                            $surcharge_value = $surcharge_settings->surcharge_value;
                                                                        } else {
                                                                            $surcharge_value = $shipment_cost_without_surcharge * ($surcharge_settings->surcharge_value / 100);
                                                                        }
                                                                    }
                                                                    $shipment_cost_with_surcharge = $shipment_cost_without_surcharge + $surcharge_value;
                                                                    $adding_shipping_cost_to_total = 0;
                                                                    if (!empty($shipment_cost_with_surcharge)) {
                                                                        $adding_shipping_cost_to_total = $total_including_tax + $shipment_cost_with_surcharge;
                                                                    } else {
                                                                        $adding_shipping_cost_to_total = $total_including_tax + $shipment_cost_without_surcharge;
                                                                    }
                                                                @endphp
                                                                
                                                                <input type="hidden" name="original_shipping_cost_from_shipstation" id="" value="{{ number_format($shipment_cost_without_surcharge , 2, '.', '')}}">
                                                                <input type="hidden" name="shipping_carrier_code" id="" value="{{$shipping_carrier_code}}">
                                                                <input type="radio" name="shipping_service_code" id="" class="d-none" value="{{$shipping_quote->serviceCode}}" checked>
                                                                <div class="col-md-9 col-8">
                                                                    <input type="radio" name="shipping_multi_price" class="shipping_multi_price" id="single_shipping_quote" value="{{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2, '.', '') : number_format($shipment_cost_without_surcharge , 2, '.', '')}}" checked>
                                                                    <span class="checkout_shipping_heading">{{$shipping_quote->serviceName}}</span>
                                                                </div>
                                                                <div class="col-md-3 col-4 text-right">
                                                                    <span class="checkout_shipping_price">${{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2) : number_format($shipment_cost_without_surcharge , 2)}}</span>
                                                                </div>
                                                                <input type="hidden" name="shipment_cost_multiple" id="shipment_price_single" value="{{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2, '.', '') : number_format($shipment_cost_without_surcharge , 2, '.', '')}}">
                                                            @endforeach
                                                        @else
                                                            @foreach ($admin_selected_shipping_quote as $shipping_quote)
                                                                @php
                                                                    $shipment_cost_without_surcharge = $shipping_quote->shipmentCost + $shipping_quote->otherCost;
                                                                    if (!empty($surcharge_settings) && $surcharge_settings->apply_surcharge == 1) {
                                                                        if (!empty($surcharge_settings->surcharge_type) && $surcharge_settings->surcharge_type == 'fixed') {
                                                                            $surcharge_value = $surcharge_settings->surcharge_value;
                                                                        } else {
                                                                            $surcharge_value = $shipment_cost_without_surcharge * ($surcharge_settings->surcharge_value / 100);
                                                                        }
                                                                    }
                                                                    $shipment_cost_with_surcharge = $shipment_cost_without_surcharge + $surcharge_value;
                                                                @endphp
                                                                <div class="col-md-9 col-8">
                                                                    <input type="hidden" name="original_shipping_cost_from_shipstation" id="" value="{{ number_format($shipment_cost_without_surcharge , 2, '.', '')}}">
                                                                    <input type="hidden" name="shipping_carrier_code" id="" value="{{$shipping_carrier_code}}">
                                                                    <input type="radio" name="shipping_service_code" id="" class="shipping_service_code d-none" value="{{$shipping_quote->serviceCode}}">
                                                                    <input type="radio" name="shipping_multi_price" class="shipping_multi_price" id="" value="{{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2, '.', '') : number_format($shipment_cost_without_surcharge , 2, '.', '')}}" onclick="assign_service_code(this)">
                                                                    <span class="checkout_shipping_heading">{{$shipping_quote->serviceName}}</span>
                                                                </div>
                                                                <div class="col-md-3 col-4 text-right">
                                                                    <span class="checkout_shipping_price">${{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2) : number_format($shipment_cost_without_surcharge , 2)}}</span>
                                                                </div>
                                                                <input type="hidden" name="shipment_cost_multiple" id="shipment_price_{{$shipping_quote->serviceCode}}" class="shipstation_multi_shipment_price" value="{{!empty($shipment_cost_with_surcharge) ? number_format($shipment_cost_with_surcharge , 2, '.', '') : number_format($shipment_cost_without_surcharge , 2, '.', '')}}">
                                                            @endforeach
                                                        @endif
                                                    @else
                                                        <div class="col-md-9 col-8"><span class="checkout_shipping_heading">Shipment Price</span></div>
                                                        <div class="col-md-3 col-4 text-right"><span class="checkout_shipping_price">${{number_format($shipment_price , 2)}}</span></div>
                                                    @endif
                                                </div>
                                            @endif 
                                        @else
                                            <input type="hidden" name="admin_control_shipping" id="admin_control_shipping" value="false">
                                            <input type="hidden" name="shipping_carrier_code" id="" value="{{$shipping_carrier_code}}">
                                            <input type="hidden" name="shipping_service_code" id="" value="{{$shipping_service_code}}">
                                            <div class="row justify-content-center border-bottom align-items-center py-2">
                                                <div class="col-md-9 col-8"><span class="checkout_shipping_heading">Shipment Price</span></div>
                                                <div class="col-md-3 col-4 text-right"><span class="checkout_shipping_price">${{number_format($shipment_price , 2)}}</span></div>
                                            </div>
                                        @endif
                                        <div class="row justify-content-center  align-items-center py-2">
                                            <div class="col-md-9 col-8"><span class="checkout_total_heading">Total</span></div>
                                            <div class="col-md-3 col-4 text-right"><span class="checkout_total_price" id="checkout_order_total">${{ number_format($total_including_tax, 2) }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-5 col-12 order-xl-1 custom-width">
                        <div class="row mt-2 mb-2">
                            <div class="col-md-12">
                                <h5 class="checkout_default_address">Default Address</h5>
                            </div>
                            {{-- <div class="col-md-2 p-0">
                                <span class="">
                                    <a data-bs-toggle="modal" href="#address_modal_id" role="button" class="float-end">Edit</a>
                                </span>
                            </div> --}}
                        </div>
                        {{-- <div class="row"> --}}
                            {{-- <div class="border m-0 rounded"> --}}
                                <div class="row pb-2 pt-2 border m-0 rounded">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-10 col-10">
                                                <h6 class="checkout_main_sub_address mb-0">Billing Address</h6>
                                            </div>
                                            <div class="col-md-2 col-2">
                                                <a data-bs-toggle="modal" href="#address_modal_id_billing" role="button" class="float-end" style="font-size:20px">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row justify-content-center border-bottom custom_address_padding">
                                            <div class="col-md-3"><span class="checkout_address_heading">Contact</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->firstName ? $user_address->firstName : '' }}
                                                {{ $user_address->lastName ? $user_address->lastName : '' }}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">Bill to</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->postalAddress1 ? $user_address->postalAddress1 : ''}} {{$user_address->postalAddress2 ? ', ' .$user_address->postalAddress2 : ''}}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">City</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->postalCity ? $user_address->postalCity : '' }}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">State</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->postalState ? $user_address->postalState : '' }}</span></div>
                                            
                                        </div>
                                        
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">Zip Code</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->postalPostCode ? $user_address->postalPostCode : '' }}</span></div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-3 align-items-center">
                                            <div class="col-md-10 col-10">
                                                <h6 class="checkout_main_sub_address mb-0">Shipping Address</h6>
                                            </div>
                                            <div class="col-md-2 col-2">
                                                <a data-bs-toggle="modal" href="#address_modal_id_shipping" role="button" class="float-end" style="font-size:20px">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">Contact</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->firstName ? $user_address->firstName : '' }}
                                                {{ $user_address->lastName ? $user_address->lastName : '' }}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">Ship to</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->address1 ? $user_address->address1 : ''}}  {{$user_address->address2 ? ', ' .$user_address->address2 : ''}}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">City</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->city ? $user_address->city : '' }}</span></div>
                                            
                                        </div>
                                        <div class="row justify-content-center border-bottom custom_address_padding ">
                                            <div class="col-md-3"><span class="checkout_address_heading">State</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->state ? $user_address->state : '' }}</span></div>
                                            
                                        </div>
                                        
                                        <div class="row justify-content-center custom_address_padding">
                                            <div class="col-md-3"><span class="checkout_address_heading">Zip Code</span></div>
                                            <div class="col-md-9"><span class="checkout_address_text">{{ $user_address->postCode ? $user_address->postCode : '' }}</span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                        
                        <div class="row justify-content-between mt-2 mb-3">
                            <div class="col-md-12 mb-2">
                                <h5 class="checkout_default_address mb-0">{{!empty($user_address->paymentTerms) && strtolower($user_address->paymentTerms) == 'pay in advanced' ? 'Payment Method' : 'Payment Terms' }}</h5>
                            </div>
                            <div class="col-md-12">
                                {{-- <div class="row"> --}}
                                    @if (!empty($user_address->paymentTerms) && strtolower($user_address->paymentTerms) == 'pay in advanced')
                                    <div id="stripe_payment" class="d-flex col-md-6 border  m-0 payment-custom-radio py-1 mt-2 selected">
                                        <input type="radio" name="checkout_payment_type" class="radio_check_payment payment-hidden-radio border-0" value="stripe" id="stripe" >
                                        <span class="stripe-radio-label"></span>
                                    </div>
                                    @else
                                    {{-- <div id="square_payment" class="col-md-3 d-flex border justify-content-center align-items-center m-0 payment-custom-radio py-1 mt-2">
                                        <input type="radio" name="checkout_payment_type" class="radio_check_payment payment-hidden-radio border-0" value="square" id="square">
                                        <span class="square-radio-label"></span>
                                    </div> --}}
                                    <div id="manuall_payment" class="col-md-6 d-flex border justify-content-center align-items-center m-0 payment-custom-radio py-1 mt-2 selected">
                                        <input type="radio" name="checkout_payment_type" class="radio_check_payment payment-hidden-radio border-0" value="Manuall" id="manuall">
                                        <span class="manuall-radio-label">30 Days from Invoice</span>
                                    </div>
                                    @endif
                                {{-- </div> --}}
                            </div>
                            {{-- <span class="payment_type_errors text-danger" id="payment_type_errors"></span> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center d-none" id="progress_spinner"><img src="/theme/img/progress.gif" alt=""></div>
                                <button class="btn check_out_pay_now w-100 p-3" id="proceed_to_checkout" onclick="validate()">Place Order</button>
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
                    <form class="needs-validation mt-4 novalidate" action="{{ url('order') }}" method="POST">
                        @csrf
                        @if(!empty($user_address->contact_id))
                        <input type="hidden" value="{{$user_address->contact_id}}" name="contact_id" id="contact_id_val">
                        @elseif(!empty($user_address->secondary_id))
                        <input type="hidden" value="{{$user_address->secondary_id}}" name="secondary_id" id="secondary_id_val">
                        @endif
                        <div class="alert alert-success mt-3 d-none" id="success_msg_previous"></div>
                        <div class="alert alert-danger mt-3 d-none" id="failure_msg"></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control bg-light" id="first_name" name="firstName"
                                    placeholder="First name" value="{{ $user_address->firstName }}" disabled>
                                <div id="error_first_name" class="text-danger">

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control bg-light" id="last_name" name="lastName"
                                    placeholder="" value="{{ $user_address->lastName }}" disabled>
                                <div id="error_last_name" class="text-danger">

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="company">Company Name(optional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light" name="user_company"
                                    id="user_company" placeholder="Enter you company name"
                                    value="{{ $user_address->company }}" disabled>
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
                                value="{{ $user_address->address1 }}" placeholder="House number and street name"
                                required>
                        </div>
                        <div id="error_address1" class="text-danger">
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light" name="address2"
                                value="{{ $user_address->address2 }}"
                                placeholder="Apartment, suite, unit etc (optional)">
                        </div>
                        <div id="error_address2" class="text-danger">
                        </div>
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light" name="town_city"
                                value="{{ $user_address->city }}" placeholder="Enter your town">
                        </div>
                        <div id="error_city" class="text-danger">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>

                                <select class="form-control bg-light" name="state" id="state">
                                    @foreach ($states as $state)
                                        <?php
                                        if ($user_address->state == $state->state_name) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option value="{{ $state->state_name }}" <?php echo $selected; ?>>
                                            {{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!--    <input type="text" class="form-control bg-light" name="state" value="{{ $user_address->postalState }}" placeholder="Enter State" value="" required> -->
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light" name="zip"
                                    placeholder="Enter zip code" value="{{ $user_address->postCode }}" required>
                                <div id="error_zip" class="text-danger">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light" name="phone"
                                    id="delivery_phone" placeholder="Enter your phone"
                                    value="{{ $user_address->phone }}">
                                <div id="error_phone" class="text-danger"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer mbl-footer-modal-address">
                <div class="spinner-border text-primary d-none" role="status" id="address_loader_previous">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary mbl-btn-update-address"
                    onclick="updateContact('{{ $contact_id }}')">Update</button>
            </div>
        </div>
    </div>
</div>
{{-- billing address --}}
<div class="modal fade" id="address_modal_id_billing" data-dismiss="modal" data-backdrop="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Update Billing Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="update-address-section" id="address-form-update">

                    <form class="needs-validation mt-4 novalidate" action="{{ url('order') }}" method="POST">
                        @if(!empty($user_address->contact_id))
                        <input type="hidden" value="{{$user_address->contact_id}}" name="contact_id" id="contact_id_val">
                        @elseif(!empty($user_address->secondary_id))
                        <input type="hidden" value="{{$user_address->secondary_id}}" name="secondary_id" id="secondary_id_val">
                        @endif
                        @csrf
                        <div class="spinner-border text-primary d-none" role="status" id="waiting_loader">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="alert alert-success mt-3 d-none" id="success_msg_billing"></div>
                        <div class="alert alert-info mt-3 d-none" id="processing_msg"></div>
                        <div class="alert alert-danger mt-3 d-none" id="error_msg_billing"></div>
                        <input type="hidden" name="email" id="billing_email" value="{{!empty($user_address->email) ? $user_address->email : ''}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control bg-light" name="firstName" id="billing_first_name"
                                    placeholder="First name" value="{{!empty($user_address->firstName) ? $user_address->firstName : ''}}" required>
                                <div id="error_first_name_billing" class="text-danger">

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control bg-light" name="lastName" id="billing_last_name" placeholder=""
                                    value="{{!empty($user_address->lastName) ? $user_address->lastName : ''}}" required>
                                <div id="error_last_name_billing" class="text-danger">

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company">Select Location</label>
                            @php
                                $companies = Session::get('companies');
                                $session_company = Session::get('company');
                            @endphp
                            <div class="input-group">
                                <div class="row">
                                    @if ($companies)
                                    @foreach ($companies as $company)
                                        @php
                                            if ($company->contact_id) {
                                                $contact_id = $company->contact_id;
                                                $primary = '(primary)';
                                            } else {
                                                $contact_id = $company->secondary_id;
                                                $primary = '(secondary)';
                                            }
                                            if ($company->status == 0) {
                                                $disabled = 'disabled';
                                                $disable_text = '(Disabled)';
                                                $muted = 'text-muted';
                                            } else {
                                                $disabled = '';
                                                $disable_text = '';
                                                $muted = '';
                                            }
                                        @endphp
                                        @if($company->type != "Supplier")
                                            <div class="col-md-12">
                                                <input  onclick="change_company_billing(this  , {{ $contact_id }})" type="radio" {{!empty($session_company) && $session_company === $company->company ? 'checked' : ''}} value="{{ $company->company }}" class="companyName companyNameBilling" name="company" id="companyName" {{ $disabled }} {{ $muted }}>
                                                <label for="" {{ $disabled }} {{ $muted }}>{{ $company->company }}
                                                    <span
                                                    style="font-size: 9px;font-family: 'Poppins';"
                                                    class="{{ $muted }}">{{ $primary }}
                                                </span>
                                                </label>
                                            </div>
                                        @endif
                                        {{-- <input type="text" class="form-control bg-light" name="company" id="companyName" placeholder="Enter you company name" value="{{ $user_address->company }}" required> --}}
                                    @endforeach
                                @endif
                                </div>
                            </div>
                            <div id="error_company_billing" class="text-danger"> </div>
                        </div>

                        <div class="mb-3">
                            <label for="username">Country</label>&nbsp;<span>United States</span>
                            <input type="hidden" name="country" value="United States">
                        </div>


                        <div class="mb-3">
                            <label for="address">Street Address</label>
                            <input type="text" class="form-control bg-light billing_address_1 " name="address" id="address1"
                            value="{{ !empty($user_address->postalAddress1) ?  $user_address->postalAddress1 : '' }}" placeholder="House number and street name"
                            required>
                            <div id="error_address1_billing" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light billing_address_2" name="address2"
                                value="{{ !empty($user_address->postalAddress2) ?  $user_address->postalAddress2 : '' }}"
                                placeholder="Apartment, suite, unit etc (optional)">
                                <div id="error_address2_billing" class="text-danger"></div>
                        </div>
                       
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light billing_city" name="town_city"
                                value="{{ !empty($user_address->postalCity) ? $user_address->postalCity : '' }}" placeholder="Enter your town">
                                <div id="error_city_billing" class="text-danger"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>

                                <select class="form-control bg-light billing_state"  name="state" id="state">
                                    @if (empty($user_address->postalState)) <option value="">Select State</option>@endif
                                    @foreach ($states as $state)
                                        <?php
                                        if (!empty($user_address->postalState) && ($user_address->postalState == $state->state_name)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        
                                        ?>
                                        <option value="{{ $state->state_name }}" <?php echo $selected; ?>>{{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light billing_post_code" name="zip"
                                    placeholder="Enter zip code" value="{{ !empty($user_address->postalPostCode) ? $user_address->postalPostCode : ''}}"
                                    required>
                                <div id="error_zip_billing" class="text-danger">

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light billing_phone" name="phone"
                                    placeholder="Enter your phone" value="{{!empty($user_address->phone) ? $user_address->phone  : ''}}" required>
                                <div id="error_phone_billing" class="text-danger"></div>



                            </div>

                        </div>
                    </form>
                </div>


            </div>
            <div class="modal-footer justify-content-center">
                <div class="spinner-border text-primary d-none" role="status" id="address_loader">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary"
                    onclick="updateContact_address('{{'update billing address'}}' , '{{ auth()->user()->id }}'  )">Update Billing</button>
            </div>
        </div>
    </div>
</div>
{{-- shipping address --}}
<div class="modal fade" id="address_modal_id_shipping" data-dismiss="modal" data-backdrop="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Update Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="update-address-section" id="address-form-update">

                    <form class="needs-validation mt-4 novalidate" action="{{ url('order') }}" method="POST">
                        @if(!empty($user_address->contact_id))
                        <input type="hidden" value="{{$user_address->contact_id}}" name="contact_id" id="contact_id_val">
                        @elseif(!empty($user_address->secondary_id))
                        <input type="hidden" value="{{$user_address->secondary_id}}" name="secondary_id" id="secondary_id_val">
                        @endif
                        @csrf
                        <div class="spinner-border text-primary d-none" role="status" id="waiting_loader_shipping">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="alert alert-success mt-3 d-none" id="success_msg_shipping"></div>
                        <div class="alert alert-info mt-3 d-none" id="processing_msg_shipping"></div>
                        <div class="alert alert-danger mt-3 d-none" id="error_msg_shipping"></div>
                        <input type="hidden" name="email" id="shipping_email" value="{{!empty($user_address->email) ? $user_address->email : ''}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control bg-light" id="shipping_first_name" name="firstName"
                                    placeholder="First name" value="{{!empty($user_address->firstName) ? $user_address->firstName  : ''}}" required>
                                <div id="error_first_name_shipping" class="text-danger">

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control bg-light" id="shipping_last_name" name="lastName" placeholder=""
                                    value="{{!empty($user_address->lastName) ? $user_address->lastName : '' }}" required>
                                <div id="error_last_name_shipping" class="text-danger">

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company">Select Location</label>
                            @php
                                $companies = Session::get('companies');
                                $session_company = Session::get('company');
                            @endphp
                            <div class="input-group">
                                <div class="row">
                                    @if ($companies)
                                    @foreach ($companies as $company)
                                        @php
                                            if ($company->contact_id) {
                                                $contact_id = $company->contact_id;
                                                $primary = '(primary)';
                                            } else {
                                                $contact_id = $company->secondary_id;
                                                $primary = '(secondary)';
                                            }
                                            if ($company->status == 0) {
                                                $disabled = 'disabled';
                                                $disable_text = '(Disabled)';
                                                $muted = 'text-muted';
                                            } else {
                                                $disabled = '';
                                                $disable_text = '';
                                                $muted = '';
                                            }
                                        @endphp
                                        @if($company->type != "Supplier")
                                            <div class="col-md-12">
                                                <input type="radio"  {{!empty($session_company) && $session_company === $company->company ? 'checked' : ''}} value="{{ $company->company }}" name="company" onclick="change_company_shipping(this  , {{ $contact_id }})" class="companyName companyNameShipping" id="companyName" {{ $disabled }} {{ $muted }}>
                                                <label for="" {{ $disabled }} {{ $muted }}>{{ $company->company }}
                                                    <span
                                                    style="font-size: 9px;font-family: 'Poppins';"
                                                    class="{{ $muted }}">{{ $primary }}
                                                </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                </div>
                            </div>
                            <div id="error_company_shipping" class="text-danger"> </div>
                        </div>

                        <div class="mb-3">
                            <label for="username">Country</label>&nbsp;<span>United States</span>
                            <input type="hidden" name="country" value="United States">
                        </div>


                        <div class="mb-3">
                            <label for="address">Street Address</label>
                            <input type="text" class="form-control bg-light shipping_address_1" name="address"  id="address1"
                            value="{{ !empty($user_address->address1) ? $user_address->address1 : '' }}" placeholder="House number and street name"
                            required>
                            <div id="error_address_1_shipping" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light shipping_address_2" name="address2"
                                value="{{ !empty($user_address->address2) ? $user_address->address2 : '' }}"
                                placeholder="Apartment, suite, unit etc (optional)">
                                <div id="error_address_2_shipping" class="text-danger"></div>
                        </div>
                       
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light shipping_city" name="town_city"
                                value="{{ !empty($user_address->city) ?  $user_address->city  : ''}}" placeholder="Enter your town">
                                <div id="error_city_shipping" class="text-danger"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>

                                <select class="form-control bg-light shipping_state" name="state" id="state" autocomplete="address-level1">
                                    @if (empty($user_address->state)) <option value="">Select State</option>@endif
                                    @foreach ($states as $state)
                                        <?php
                                        if (!empty($user_address->state ) && ($user_address->state == $state->state_name)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        
                                        ?>
                                        <option value="{{ $state->state_name }}" <?php echo $selected; ?>>{{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light shipping_post_code" name="zip"
                                    placeholder="Enter zip code" value="{{ !empty($user_address->postCode) ?  $user_address->postCode: ''}}"
                                    required>
                                <div id="error_zip_shipping" class="text-danger">

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light shipping_phone" name="phone"
                                    placeholder="Enter your phone" value="{{!empty($user_address->phone) ? $user_address->phone : '' }}" required>
                                <div id="error_phone_shipping" class="text-danger"></div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
            <div class="modal-footer justify-content-center">
                <div class="spinner-border text-primary d-none" role="status" id="address_loader_shipping">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary"
                    onclick="updateContact_address('{{'update shipping address'}}'  , '{{ auth()->user()->id }}' )">Update Shipping</button>
            </div>
        </div>
    </div>
</div>

<form class="needs-validation mt-4 novalidate" style="display:none" action="{{ url('order') }}" method="POST">
    @csrf
    <div class="alert alert-success  d-none" id="success_msg"></div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="firstName">First name</label>
            <input type="text" class="form-control bg-light" name="first_name" placeholder="First name"
                value="{{ $user_address->firstName }}" required>
            <div id="error_first_name" class="text-danger">

            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="lastName">Last name</label>
            <input type="text" class="form-control bg-light" name="last_name" placeholder=""
                value="{{ $user_address->lastName }}" required>
            <div id="error_last_name" class="text-danger">
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="company">Company Name(optional)</label>
        <div class="input-group">
            <input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name"
                value="{{ $user_address->company }}" required>
        </div>
        <div id="error_company" class="text-danger"></div>
    </div>
    <div class="mb-3">
        <label for="username">Country</label>&nbsp;<span>United States</span>
        <input type="hidden" name="country" value="United States">
    </div>
    <div class="mb-3">
        <label for="address">Street Address</label>
        <input type="text" class="form-control bg-light" name="address"
            value="{{ $user_address->address1 }}" placeholder="House number and street name" required>
        <div id="error_address1" class="text-danger"></div>
    </div>

    <div class="mb-3">
        <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control bg-light" name="address2"
            value="{{ $user_address->address2 }}" placeholder="Apartment, suite, unit etc (optional)">
            <div id="error_address2" class="text-danger"></div>
    </div>
    <div class="mb-3">
        <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control bg-light" name="town_city"
            value="{{ $user_address->city }}" placeholder="Enter your town">
            <div id="error_city" class="text-danger"></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="state">State</label>

            <select class="form-control bg-light" name="state" id="state">
                @foreach ($states as $state)
                    <?php
                    if ($user_address->state == $state->state_name) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <option value="{{ $state->state_name }}" <?php echo $selected; ?>>{{ $state->state_name }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Valid first name is required.
            </div>
            <script>
                function select_payment_method(element) {
                    $('#payment_type_errors').html('');
                    $('.payment-custom-radio').removeClass('selected');
                    let all_inputs = $('.payment-hidden-radio');
                    all_inputs.removeAttr('checked');
                    all_inputs.each(function() {
                        $(this).val('');
                    });
                    var radioInput = $(element).find('.payment-hidden-radio');
                    var getRadioId = radioInput.attr('id');
                    var assign_value = $('#' + getRadioId).val(getRadioId)
                    $('#' + getRadioId).attr('checked', 'checked');
                    $(element).addClass('selected');
                }

                
                function updateContact_address(type , user_id) {
        
                    if (type === 'update shipping address') {
                        $('#address_loader_shipping').removeClass('d-none');
                        var companyNameShipping = $('.companyNameShipping:checked').val();
                        var first_name_shipping = $('#shipping_first_name').val();
                        var last_name_shipping = $('#shipping_last_name').val();
                        var shipping_address_1 = $('.shipping_address_1').val();
                        var shipping_address_2 = $('.shipping_address_2').val();
                        var shipping_city = $('.shipping_city').val();
                        var shipping_state = $('.shipping_state').val();
                        var post_code = $('.shipping_post_code').val();
                        var shipping_email = $('.shipping_email').val();
                        var shipping_phone = $('.shipping_phone').val();

                        var company_name = companyNameShipping;
                        var first_name = first_name_shipping;
                        var last_name = last_name_shipping;
                        var address = shipping_address_1;
                        var address2 = shipping_address_2;
                        var town_city = shipping_city;
                        var state = shipping_state;
                        var zip = post_code;
                        var email = shipping_email;
                        var phone = shipping_phone;

                        if (companyNameShipping == '' || companyNameShipping == null) {
                            $('#error_company_shipping').html('Please select location');
                            $('#address_loader_shipping').addClass('d-none');
                            return false;
                        } 
                        else {
                            $('#error_company_shipping').html('');
                        }
                    } else {
                        $('#address_loader').removeClass('d-none');
                        var companyNameBilling = $('.companyNameBilling:checked').val();
                        var first_name_billing = $('#billing_first_name').val();
                        var last_name_billing = $('#billing_last_name').val();
                        var billing_address_1 = $('.billing_address_1').val();
                        var billing_address_2 = $('.billing_address_2').val();
                        var billing_city = $('.billing_city').val();
                        var billing_state = $('.billing_state').val();
                        var post_code = $('.billing_post_code').val();
                        var billing_email = $('.billing_email').val();
                        var billing_phone = $('.billing_phone').val();

                        var company_name = companyNameBilling;
                        var first_name = first_name_billing;
                        var last_name = last_name_billing;
                        var address = billing_address_1;
                        var address2 = billing_address_2;
                        var town_city = billing_city;
                        var state = billing_state;
                        var zip = post_code;
                        var email = billing_email;
                        var phone = billing_phone;

                        if (companyNameBilling == '' || companyNameBilling == null) {
                            $('#error_company_billing').html('Please select location');
                            $('#address_loader').addClass('d-none');
                            return false;
                        } 
                        else {
                            $('#error_company_billing').html('');
                        }
                    }
                    
                    var companyName = $('.companyName:checked').val();
                    var contact_id = $('#contact_id_val').val();
                    var secondary_id = $('input[name=secondary_id]').val();
                    
                    console.log(state);
                    jQuery.ajax({
                        method: 'GET',
                        url: "{{ url('/my-account-user-addresses/') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "user_id": user_id,
                            "first_name": first_name,
                            "last_name": last_name,
                            "company_name": company_name,
                            "phone": phone,
                            "address": address,
                            "address2": address2,
                            "town_city": town_city,
                            "state": state,
                            "zip": zip,
                            "email": email,
                            'contact_id': contact_id,
                            'secondary_id': secondary_id,
                            // 'company_name': companyName,
                            'type': type
                        },
                        success: function(response) {
                            if (response.cin7_status == 200 || response.status == true) {
                                $('#address_loader').addClass('d-none');
                                $('#address_loader_shipping').addClass('d-none');
                                $('.modal-backdrop').remove()
                                if (type === 'update shipping address') { 
                                    $('#waiting_loader_shipping').removeClass('d-none');
                                    $('#success_msg_shipping').removeClass('d-none');
                                    $('#success_msg_shipping').html(response.msg);
                                } else {
                                    $('#waiting_loader').removeClass('d-none');
                                    $('#success_msg_billing').removeClass('d-none');
                                    $('#success_msg_billing').html(response.msg);
                                }
                                setTimeout(function() {
                                    if (type === 'update shipping address') {
                                        $('#success_msg_shipping').addClass('d-none');
                                        $('#success_msg_shipping').html('');
                                        $('#processing_msg_shipping').removeClass('d-none');
                                        $('#processing_msg_shipping').html('Fetching Data ...');
                                    } else {
                                        $('#success_msg_billing').addClass('d-none');
                                        $('#success_msg_billing').html('');
                                        $('#processing_msg').removeClass('d-none');
                                        $('#processing_msg').html('Fetching Data ...');
                                    }
                                    window.location.href = "{{ url('/checkout') }}";
                                }, 2000);
                            }   else {
                                $('#address_loader').addClass('d-none');
                                $('#address_loader_shipping').addClass('d-none');
                                $('.modal-backdrop').remove();
                                if (type === 'update shipping address') {  
                                    $('#waiting_loader_shipping').removeClass('d-none');
                                    $('#error_msg_shipping').removeClass('d-none');
                                    $('#error_msg_shipping').html('Something went wrong');
                                } else {
                                    $('#waiting_loader').removeClass('d-none');
                                    $('#error_msg_billing').removeClass('d-none');
                                    $('#error_msg_billing').html('Something went wrong');
                                }
                                setTimeout(function() {
                                    if (type === 'update shipping address') {
                                        $('#error_msg_shipping').removeClass('d-none');
                                        $('#error_msg_shipping').html('Something went wrong');
                                        $('#processing_msg_shipping').removeClass('d-none');
                                        $('#processing_msg_shipping').html('Fetching Data ...');
                                    } else {
                                        $('#error_msg_billing').removeClass('d-none');
                                        $('#error_msg_billing').html('Something went wrong');
                                        $('#processing_msg').removeClass('d-none');
                                        $('#processing_msg').html('Fetching Data ...');
                                    }
                                    window.location.href = "{{ url('/checkout') }}";
                                }, 2000);
                            }
                        },
                        error: function(response) {
                            if (type === 'update shipping address') {
                                
                                $('#address_loader_shipping').addClass('d-none');
                                var error_message = response.responseJSON;
                                var error_text = '';
                                if (typeof error_message.errors.first_name != 'undefined') {
                                    error_text = error_message.errors.first_name;
                                    $('#error_first_name_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_first_name_shipping').html(error_text);
                                }
                                if (typeof error_message.errors.last_name != 'undefined') {
                                    var error_text = error_message.errors.last_name;
                                    $('#error_last_name_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_last_name_shipping').html(error_text);
                                }
                                if (typeof error_message.errors.company_name != 'undefined') {
                                    var error_text = error_message.errors.company_name;
                                    $('#error_company_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_company_shipping').html(error_text);
                                }
                                if (typeof error_message.errors.address != 'undefined') {
                                    var error_text = error_message.errors.address;
                                    $('#error_address1_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_address1_shipping').html(error_text);
                                }

                                if (typeof error_message.errors.zip != 'undefined') {
                                    var error_text = error_message.errors.zip;
                                    $('#error_zip_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_zip_shipping').html(error_text);
                                }
                                if (typeof error_message.errors.town_city != 'undefined') {
                                    var error_text = error_message.errors.town_city;
                                    $('#error_city_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_city_shipping').html(error_text);
                                }
                                if (typeof error_message.errors.phone != 'undefined') {
                                    var error_text = error_message.errors.phone;
                                    $('#error_phone_shipping').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_phone_shipping').html(error_text);
                                }

                            } else {
                                $('#address_loader').addClass('d-none');
                                var error_message = response.responseJSON;
                                var error_text = '';
                                if (typeof error_message.errors.first_name != 'undefined') {
                                    error_text = error_message.errors.first_name;
                                    $('#error_first_name_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_first_name_billing').html(error_text);
                                }
                                if (typeof error_message.errors.last_name != 'undefined') {
                                    var error_text = error_message.errors.last_name;
                                    $('#error_last_name_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_last_name_billing').html(error_text);
                                }
                                if (typeof error_message.errors.company_name != 'undefined') {
                                    var error_text = error_message.errors.company_name;
                                    $('#error_company_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_company_billing').html(error_text);
                                }
                                if (typeof error_message.errors.address != 'undefined') {
                                    var error_text = error_message.errors.address;
                                    $('#error_address1_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_address1_billing').html(error_text);
                                }

                                if (typeof error_message.errors.zip != 'undefined') {
                                    var error_text = error_message.errors.zip;
                                    $('#error_zip_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_zip_billing').html(error_text);
                                }
                                if (typeof error_message.errors.town_city != 'undefined') {
                                    var error_text = error_message.errors.town_city;
                                    $('#error_city_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_city_billing').html(error_text);
                                }
                                if (typeof error_message.errors.phone != 'undefined') {
                                    var error_text = error_message.errors.phone;
                                    $('#error_phone_billing').html(error_text);
                                } else {
                                    error_text = '';
                                    $('#error_phone_billing').html(error_text);
                                }
                                
                            }
                        }
                    });
                }
                function validate() {
                    $('#progress_spinner').removeClass('d-none');
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#progress_spinner").offset().top
                    }, 2000);

                    $('#proceed_to_checkout').prop('disabled', true);
                    $('#proceed_to_checkout').addClass('text-muted');
                    if (!$("input[name=method_option]").is(':checked')) {
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
                                var sessionContact_id = '{{ Session::get('contact_id') }}';
                                if (sessionContact_id == '') {
                                    var companiesData = {}
                                    jQuery.ajax({
                                        method: 'GET',
                                        url: "{{ url('/select-companies-to-order/') }}",
                                        success: function(response) {
                                            console.log(response.companies);
                                            $.each(response.companies, function(index, value) {
                                                let companyID = null;
                                                if (value.contact_id) {
                                                    companyID = value.contact_id + "-P";
                                                }
                                                if (value.secondary_id) {
                                                    companyID = value.secondary_id + "-S";;
                                                }
                                                if (value.status == 1) {
                                                    companiesData[companyID] = value.company;
                                                }
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
                                        input: 'radio',
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
                                                success: function(response) {
                                                    $("#order_form").submit();
                                                    // Generate dynamic transaction ID and purchase value
                                                    // var transaction_id = 'T_' + Date.now();
                                                    // var purchase_value = calculatePurchaseValue(); // You need to define this function

                                                    // // Add gtag event for purchase
                                                    // gtag('event', 'purchase', {
                                                    //     'transaction_id': transaction_id,
                                                    //     'value': purchase_value,
                                                    //     // Add other parameters as needed
                                                    // });
                                                }
                                            });
                                        }
                                    });
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                } else {
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                    $("#order_form").submit();
                                    // Generate dynamic transaction ID and purchase value
                                    // var transaction_id = 'T_' + Date.now();
                                    // var purchase_value = calculatePurchaseValue(); // You need to define this function

                                    // // Add gtag event for purchase
                                    // gtag('event', 'purchase', {
                                    //     'transaction_id': transaction_id,
                                    //     'value': purchase_value,
                                    //     // Add other parameters as needed
                                    // });
                                }
                            }
                        });
                    } else {
                        var sessionContact_id = '{{ Session::get('contact_id') }}';
                        if (sessionContact_id == '') {
                            var companiesData = {}
                            jQuery.ajax({
                                method: 'GET',
                                url: "{{ url('/select-companies-to-order') }}",
                                success: function(response) {
                                    $.each(response.companies, function(index, value) {
                                        let companyID = null;
                                        if (value.contact_id) {
                                            companyID = value.contact_id + "-P";
                                        }
                                        if (value.secondary_id) {
                                            companyID = value.secondary_id + "-S";;
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
                                input: 'radio',
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
                                        success: function(response) {
                                            $("#order_form").submit();
                                        }
                                    });
                                }
                            });
                        } else {
                            $("#order_form").submit();
                        }
                    }
                }
                // Function to calculate purchase value (replace this with your actual calculation logic)
                function calculatePurchaseValue() {
                    // Example: Get the total amount from the form
                    var totalAmount = parseFloat( $('#incl_tax').val());
                    return totalAmount;
                }
                function validate_mbl() {
                    $('#progress_spinner').removeClass('d-none');
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#progress_spinner").offset().top
                    }, 2000);

                    $('#proceed_to_checkout').prop('disabled', true);
                    $('#proceed_to_checkout').addClass('text-muted');
                    if (!$("input[name=method_option]").is(':checked')) {
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
                                var sessionContact_id = '{{ Session::get('contact_id') }}';
                                if (sessionContact_id == '') {
                                    var companiesData = {}
                                    jQuery.ajax({
                                        method: 'GET',
                                        url: "{{ url('/select-companies-to-order/') }}",
                                        success: function(response) {
                                            console.log(response.companies);
                                            $.each(response.companies, function(index, value) {
                                                let companyID = null;
                                                if (value.contact_id) {
                                                    companyID = value.contact_id + "-P";
                                                }
                                                if (value.secondary_id) {
                                                    companyID = value.secondary_id + "-S";;
                                                }
                                                if (value.status == 1) {
                                                    companiesData[companyID] = value.company;
                                                }
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
                                        input: 'radio',
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
                                                success: function(response) {
                                                    $("#order_form_mbl").submit();
                                                }
                                            });
                                        }
                                    });
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                } else {
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                    $("#order_form_mbl").submit();
                                }
                            }
                        });
                    } else {
                        var sessionContact_id = '{{ Session::get('contact_id') }}';
                        if (sessionContact_id == '') {
                            var companiesData = {}
                            jQuery.ajax({
                                method: 'GET',
                                url: "{{ url('/select-companies-to-order') }}",
                                success: function(response) {
                                    $.each(response.companies, function(index, value) {
                                        let companyID = null;
                                        if (value.contact_id) {
                                            companyID = value.contact_id + "-P";
                                        }
                                        if (value.secondary_id) {
                                            companyID = value.secondary_id + "-S";;
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
                                input: 'radio',
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
                                        success: function(response) {
                                            $("#order_form_mbl").submit();
                                        }
                                    });
                                }
                            });
                        } else {
                            $("#order_form_mbl").submit();
                        }
                    }
                }
                
                function validate_ipad() {
                    $('#progress_spinner').removeClass('d-none');
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#progress_spinner").offset().top
                    }, 2000);

                    $('#proceed_to_checkout').prop('disabled', true);
                    $('#proceed_to_checkout').addClass('text-muted');
                    if (!$("input[name=method_option]").is(':checked')) {
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
                                var sessionContact_id = '{{ Session::get('contact_id') }}';
                                if (sessionContact_id == '') {
                                    var companiesData = {}
                                    jQuery.ajax({
                                        method: 'GET',
                                        url: "{{ url('/select-companies-to-order/') }}",
                                        success: function(response) {
                                            console.log(response.companies);
                                            $.each(response.companies, function(index, value) {
                                                let companyID = null;
                                                if (value.contact_id) {
                                                    companyID = value.contact_id + "-P";
                                                }
                                                if (value.secondary_id) {
                                                    companyID = value.secondary_id + "-S";;
                                                }
                                                if (value.status == 1) {
                                                    companiesData[companyID] = value.company;
                                                }
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
                                        input: 'radio',
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
                                                success: function(response) {
                                                    $("#order_form_ipad").submit();
                                                }
                                            });
                                        }
                                    });
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                } else {
                                    if (result.value == 'C.O.D') {
                                        $("#local_delivery_1").attr('checked', 'checked');
                                    } else {
                                        $("#local_delivery_2").attr('checked', 'checked');
                                    }
                                    $("#order_form_ipad").submit();
                                }
                            }
                        });
                    } else {
                        var sessionContact_id = '{{ Session::get('contact_id') }}';
                        if (sessionContact_id == '') {
                            var companiesData = {}
                            jQuery.ajax({
                                method: 'GET',
                                url: "{{ url('/select-companies-to-order') }}",
                                success: function(response) {
                                    $.each(response.companies, function(index, value) {
                                        let companyID = null;
                                        if (value.contact_id) {
                                            companyID = value.contact_id + "-P";
                                        }
                                        if (value.secondary_id) {
                                            companyID = value.secondary_id + "-S";;
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
                                input: 'radio',
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
                                        success: function(response) {
                                            $("#order_form_ipad").submit();
                                        }
                                    });
                                }
                            });
                        } else {
                            $("#order_form_ipad").submit();
                        }
                    }
                }


                function updateAddress() {
                    $('#address-form-update').toggle();
                    $('#address-form-update').removeClass('d-none');

                }
                function updateContact_ipad (contact_id) {
                    next_btn_mbl();
                }
                function updateContact(contact_id) {
                    $('#address_loader').removeClass('d-none');
                    var state = document.getElementById("state").value;
                    var first_name = $('#first_name').val();
                    var last_name = $('#last_name').val();
                    var company_name = $('#user_company').val();
                    var phone = $('#delivery_phone').val();
                    var address1 = $('input[name=address]').val();
                    var address2 = $('input[name=address2]').val();
                    var town_city = $('input[name=town_city]').val();

                    var zip = $('input[name=zip]').val();
                    var contact_id = $('input[name=contact_id]').val();
                    var secondary_id = $('input[name=secondary_id]').val();
                    //var email = $('input[name=email]').val();

                    jQuery.ajax({
                        method: 'GET',
                        url: "{{ url('/my-account-user-addresses/') }}",

                        data: {
                            "_token": "{{ csrf_token() }}",
                            "contact_id": contact_id,
                            "secondary_id": secondary_id,
                            "first_name": first_name,
                            "last_name": last_name,
                            "company_name": company_name,
                            "phone": phone,
                            "address": address1,
                            "address2": address2,
                            "town_city": town_city,
                            "state": state,
                            "zip": zip,
                            // "email": email
                        },
                        success: function(response) {
                            if (response.success == true) {
                                $('#address_loader').addClass('d-none');
                                $('.modal-backdrop').remove()
                                $('#success_msg').removeClass('d-none');
                                $('#success_msg').html(response.msg);
                                window.location.reload();
                            } else {
                                $('#address_loader').addClass('d-none');
                                $('.modal-backdrop').remove()
                                $('#failure_msg').removeClass('d-none');
                                $('#failure_msg').html(response.msg);
                                window.location.reload();

                            }
                        },
                        error: function(response) {
                            var error_message = response.responseJSON;
                            console.log(error_message);
                            var error_text = '';
                            if (typeof error_message.errors.first_name != 'undefined') {
                                error_text = error_message.errors.first_name;
                                $('#error_first_name').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_first_name').html(error_text);
                            }
                            if (typeof error_message.errors.last_name != 'undefined') {
                                var error_text = error_message.errors.last_name;
                                $('#error_last_name').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_last_name').html(error_text);
                            }
                            if (typeof error_message.errors.company_name != 'undefined') {
                                var error_text = error_message.errors.company_name;
                                $('#error_company').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_company').html(error_text);
                            }
                            if (typeof error_message.errors.address != 'undefined') {
                                var error_text = error_message.errors.address;
                                $('#error_address1').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_address1').html(error_text);
                            }

                            if (typeof error_message.errors.zip != 'undefined') {
                                var error_text = error_message.errors.zip;
                                $('#error_zip').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_zip').html(error_text);
                            }
                            if (typeof error_message.errors.town_city != 'undefined') {
                                var error_text = error_message.errors.town_city;
                                $('#error_city').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_city').html(error_text);
                            }
                            if (typeof error_message.errors.zip != 'undefined') {
                                var error_text = error_message.zip;
                                $('#error_zip').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_zip').html(error_text);
                            }
                            if (typeof error_message.errors.phone != 'undefined') {
                                var error_text = error_message.errors.phone;
                                $('#error_phone').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_phone').html(error_text);
                            }

                        }
                    });
                }
                function updateContact_mbl(contact_id) {
                    $('#address_loader').removeClass('d-none');
                    var state = document.getElementById("state").value;
                    var first_name = $('#f_name').val();
                    var last_name = $('#l_name').val();
                    var company_name = $('#u_company').val();
                    var phone = $('#d_phone').val();
                    var address1 = $('#add_1').val();
                    var address2 = $('#add_2').val();
                    var town_city = $('#t_city').val();

                    var zip = $('#p_code').val();
                    var contact_id = $('input[name=contact_id]').val();
                    var secondary_id = $('input[name=secondary_id]').val();
                    //var email = $('input[name=email]').val();

                    jQuery.ajax({
                        method: 'GET',
                        url: "{{ url('/my-account-user-addresses/') }}",

                        data: {
                            "_token": "{{ csrf_token() }}",
                            "contact_id": contact_id,
                            "secondary_id": secondary_id,
                            "first_name": first_name,
                            "last_name": last_name,
                            "company_name": company_name,
                            "phone": phone,
                            "address": address1,
                            "address2": address2,
                            "town_city": town_city,
                            "state": state,
                            "zip": zip,
                            // "email": email
                        },
                        success: function(response) {
                            if (response.success == true) {
                                $('#address_loader').addClass('d-none');
                                $('.modal-backdrop').remove()
                                $('#success_msg').removeClass('d-none');
                                $('#success_msg').html(response.msg);
                                next_btn_mbl();
                                // window.location.reload();
                            } else {
                                $('#address_loader').addClass('d-none');
                                $('.modal-backdrop').remove()
                                $('#failure_msg').removeClass('d-none');
                                $('#failure_msg').html(response.msg);
                                // window.location.reload();

                            }
                        },
                        error: function(response) {
                            var error_message = response.responseJSON;
                            console.log(error_message);
                            var error_text = '';
                            if (typeof error_message.errors.first_name != 'undefined') {
                                error_text = error_message.errors.first_name;
                                $('#error_first_name').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_first_name').html(error_text);
                            }
                            if (typeof error_message.errors.last_name != 'undefined') {
                                var error_text = error_message.errors.last_name;
                                $('#error_last_name').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_last_name').html(error_text);
                            }
                            if (typeof error_message.errors.company_name != 'undefined') {
                                var error_text = error_message.errors.company_name;
                                $('#error_company').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_company').html(error_text);
                            }
                            if (typeof error_message.errors.address != 'undefined') {
                                var error_text = error_message.errors.address;
                                $('#error_address1').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_address1').html(error_text);
                            }

                            if (typeof error_message.errors.zip != 'undefined') {
                                var error_text = error_message.errors.zip;
                                $('#error_zip').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_zip').html(error_text);
                            }
                            if (typeof error_message.errors.town_city != 'undefined') {
                                var error_text = error_message.errors.town_city;
                                $('#error_city').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_city').html(error_text);
                            }
                            if (typeof error_message.errors.zip != 'undefined') {
                                var error_text = error_message.zip;
                                $('#error_zip').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_zip').html(error_text);
                            }
                            if (typeof error_message.errors.phone != 'undefined') {
                                var error_text = error_message.errors.phone;
                                $('#error_phone').html(error_text);
                            } else {
                                error_text = '';
                                $('#error_phone').html(error_text);
                            }

                        }
                    });
                }
                function next_btn_mbl () {
                    var next = $('#next_step');
                    // $(".next").click(function() {
                    // updateContact_mbl($contact_id);
                        current_fs = next.parent();
                        next_fs = next.parent().next();

                        //Add Class Active
                        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                        //show the next fieldset
                        next_fs.show();
                        //hide the current fieldset with style
                        current_fs.animate({
                            opacity: 0
                        }, {
                            step: function(now) {
                                // for making fielset appear animation
                                opacity = 1 - now;
                                current_fs.css({
                                    'display': 'none',
                                    'position': 'relative'
                                });
                                next_fs.css({
                                    'opacity': opacity
                                });
                            },
                            duration: 600
                        });
                    // });
                }

                function apply_discount_code() {
                    var coupen_code = $('.coupen_code_input').val();
                    var contact_id = $('.user_contact_id').val();
                    var cart_total_including_tax_shipping = $('#incl_tax').val() != null ? $('#incl_tax').val() : 0;
                    var shipment_price  = $('#shipment_price').val() != null ? $('#shipment_price').val() : 0;
                    var total_tax = $('.total_tax').val() != null ? $('.total_tax').val() : 0;
                    var cartTotal = 0;
                    var add_discount = 0;
                    var tax_discount = 0;
                    var shipping_discount = 0;
                    var total  = 0;
                    var subtotal = 0;
                    var message = null;
                    if (coupen_code == '') {
                        $('.coupen_code_message').html('Please enter the coupen code');
                        return false;
                    } else {
                        $('.coupen_code_message').html('');
                        $.ajax({
                            url: "{{ url('/apply-discount-code') }}",
                            method: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                coupen_code: coupen_code,
                                contact_id : contact_id,
                            },
                            success: function(response) {
                                if (response.success == true) {
                                    if (response.specific_customers == true) {
                                        if (response.eligible == true) {
                                            $('.manuall_discount').removeClass('d-none');
                                            if (response.discount_per_user == true && response.max_uses == true) {
                                                
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else if (response.discount_max_times == true && response.max_uses == true) {
                                                
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else if (response.discount_max_times == false && response.discount_per_user == false && response.max_uses == true && response.max_discount_uses_none == true) {
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else {
                                                $('.manuall_discount').addClass('d-none');
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                            } 
                                        } else {
                                            $('.manuall_discount').addClass('d-none');
                                            message = response.message;
                                            $('.coupen_code_message').html(message);
                                        }
                                    } else {
                                        if (response.eligible == true) {
                                            $('.manuall_discount').removeClass('d-none');
                                            if (response.discount_per_user == true && response.max_uses == true) {
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else if (response.discount_max_times == true && response.max_uses == true) {
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else if (response.discount_max_times == false && response.discount_per_user == false && response.max_uses == true && response.max_discount_uses_none == true) {
                                                if (response.discount_variation == 'percentage') {
                                                    apply_discount_to_percentage(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                } else {
                                                    apply_discount_to_fixed(response , cart_total_including_tax_shipping, cartTotal , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal);
                                                }
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                                $('.discount_form').addClass('d-none');
                                            } else {
                                                $('.manuall_discount').addClass('d-none');
                                                message = response.message;
                                                $('.coupen_code_message').html(message);
                                            }
                                        } else {
                                            $('.manuall_discount').addClass('d-none');
                                            message = response.message;
                                            $('.coupen_code_message').html(message);
                                        }
                                    }
                                } else {
                                    message = response.message;
                                    $('.coupen_code_message').html(message);
                                }
                            }
                        });
                    }
                }
                function apply_discount_to_percentage(response ,cartTotal ,cart_total_including_tax_shipping , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal) {
                    var productTotal = $('.items_total_price').val() != null ?  parseFloat($('.items_total_price').val()) : 0;
                    var total_shipping_price = 0;
                    var total_tax_price = 0;
                    var multi_shipping_price = 0;
                    var order_weight_greater_then_150 = 0;
                    var product_weight = $('.product_weight').val() != null ?  parseFloat($('.product_weight').val()) : 0;
                    var admin_area_for_shipping = $('#admin_control_shipping').val();
                    $('.discount_variation').val('percentage');
                    $('.discount_variation_value').val(response.discount_variation_value);
                    if (admin_area_for_shipping === 'true') {
                        if (product_weight > 150) {
                            order_weight_greater_then_150 = $('#shipment_price_heavy_weight').val() != null ? parseFloat($('#shipment_price_heavy_weight').val()) : 0;
                            // shipment_price = order_weight_greater_then_150 + (order_weight_greater_then_150 * order_weight_greater_then_150 / 100);
                            shipment_price = order_weight_greater_then_150; 
                            add_discount = ((productTotal + parseFloat(total_tax) + parseFloat(shipment_price))  * parseFloat(response.discount_variation_value) / 100);
                            $('#discount_amount').val(add_discount.toFixed(2));
                            $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                            tax_discount = (total_tax * response.discount_variation_value / 100);
                            shipping_discount = (shipment_price * response.discount_variation_value / 100);
                            
                            subtotal = (productTotal + parseFloat(total_tax) + parseFloat(shipment_price)) - add_discount;
                            total = subtotal;
                            total_shipping_price = shipment_price - shipping_discount;
                            total_tax_price = total_tax - tax_discount;
                            $('#shipment_price_heavy_weight').val(total_shipping_price.toFixed(2));
                            $('.total_tax').val(total_tax_price.toFixed(2));
                            $('#incl_tax').val(total.toFixed(2));
                            $('.checkout_total_price').html('$' + total.toFixed(2));
                            $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                            // $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                            $('#shipment_price_heavy_weight').next().find('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                        } else {
                            $('.shipping_multi_price').each(function() {
                                // multi_shipping_price = parseFloat($(this).val()) + (parseFloat($(this).val()) * response.discount_variation_value / 100);
                                shipment_price = $(this).val();
                                add_discount = ((productTotal + parseFloat(total_tax) + parseFloat(shipment_price))  * parseFloat(response.discount_variation_value) / 100);
                                console.log(add_discount ,'discount');
                                $('#discount_amount').val(add_discount.toFixed(2));
                                $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                                tax_discount = (total_tax * response.discount_variation_value / 100);
                                shipping_discount = (shipment_price * response.discount_variation_value / 100);
                                
                                subtotal = (productTotal + parseFloat(total_tax) + parseFloat(shipment_price)) - add_discount;
                                console.log(subtotal ,'subtotal');
                                total = subtotal;
                                total_shipping_price = shipment_price - shipping_discount;
                                total_tax_price = total_tax - tax_discount;
                                $(this).val(total_shipping_price.toFixed(2));
                                $('.total_tax').val(total_tax_price.toFixed(2));
                                $('#incl_tax').val(total.toFixed(2));
                                $('.checkout_total_price').html('$' + total.toFixed(2));
                                $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                                // $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                                $(this).parent().next().find('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                            });
                        
                        }
                    } else {
                        shipment_price = shipment_price;
                        
                        add_discount = ((productTotal + parseFloat(total_tax) + parseFloat(shipment_price))  * parseFloat(response.discount_variation_value) / 100);
                        $('#discount_amount').val(add_discount.toFixed(2));
                        $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                        tax_discount = (total_tax * response.discount_variation_value / 100);
                        shipping_discount = (shipment_price * response.discount_variation_value / 100);
                        
                        subtotal = (productTotal + parseFloat(total_tax) + parseFloat(shipment_price)) - add_discount;
                        total = subtotal;
                        total_shipping_price = shipment_price - shipping_discount;
                        total_tax_price = total_tax - tax_discount;
                        $('#shipment_price').val(total_shipping_price.toFixed(2));
                        $('.total_tax').val(total_tax_price.toFixed(2));
                        $('#incl_tax').val(total.toFixed(2));
                        $('.checkout_total_price').html('$' + total.toFixed(2));
                        $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                        $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                    }
                    
                    
                }
                function apply_discount_to_fixed(response , cartTotal,cart_total_including_tax_shipping , shipment_price , total_tax , add_discount , tax_discount , shipping_discount , total , subtotal) {
                    var productTotal = $('.items_total_price').val() != null ?  parseFloat($('.items_total_price').val()) : 0;
                    var multi_shipping_price = 0;
                    var order_weight_greater_then_150 = 0;
                    var product_weight = $('.product_weight').val() != null ?  parseFloat($('.product_weight').val()) : 0;
                    var admin_area_for_shipping = $('#admin_control_shipping').val();
                    $('.discount_variation').val('fixed');
                    $('.discount_variation_value').val(response.discount_variation_value);
                    add_discount = response.discount_variation_value;
                    tax_discount = parseInt(response.discount_variation_value);
                    shipping_discount = parseInt(response.discount_variation_value);
                    var  total_shipping_price = 0;
                    var  total_tax_price = 0;
                    if (admin_area_for_shipping === 'true') {
                        if (product_weight > 150) {
                            order_weight_greater_then_150 = $('#shipment_price_heavy_weight').val() != null ? parseFloat($('#shipment_price_heavy_weight').val()) : 0;
                            shipment_price = order_weight_greater_then_150; 
                            if (tax_discount > total_tax) {
                                total_tax_price = 0;
                            } else {
                                total_tax_price = total_tax - tax_discount;
                            }
                            if (shipping_discount > shipment_price) {
                                total_shipping_price = 0;
                            } else {
                                total_shipping_price = shipment_price - shipping_discount;
                            }

                            if (add_discount > productTotal) {
                                subtotal = 0;
                                total = 0;
                            } else if(total_tax_price == 0  && total_shipping_price == 0) {
                                subtotal = productTotal - add_discount;
                                total = subtotal;
                            } else {
                                subtotal = productTotal - add_discount;
                                total = subtotal;
                            }
                            $('#discount_amount').val(add_discount.toFixed(2));
                            $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                            $('#shipment_price').val(total_shipping_price.toFixed(2));
                            $('.total_tax').val(total_tax_price.toFixed(2));
                            $('#incl_tax').val(total.toFixed(2));
                            $('.checkout_total_price').html('$' + total.toFixed(2));
                            $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                            // $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                            $('#shipment_price_heavy_weight').next().find('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                        } else {
                            $('.shipping_multi_price').each(function() {
                                shipment_price = $(this).val();
                                if (tax_discount > total_tax) {
                                total_tax_price = 0;
                                } else {
                                    total_tax_price = total_tax - tax_discount;
                                }
                                if (shipping_discount > shipment_price) {
                                    total_shipping_price = 0;
                                } else {
                                    total_shipping_price = shipment_price - shipping_discount;
                                }

                                if (add_discount > productTotal) {
                                    subtotal = 0;
                                    total = 0;
                                } else if(total_tax_price == 0  && total_shipping_price == 0) {
                                    subtotal = productTotal - add_discount;
                                    total = subtotal;
                                } else {
                                    subtotal = productTotal - add_discount;
                                    total = subtotal;
                                }
                                $('#discount_amount').val(add_discount.toFixed(2));
                                $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                                $('#shipment_price').val(total_shipping_price.toFixed(2));
                                $('.total_tax').val(total_tax_price.toFixed(2));
                                $('#incl_tax').val(total.toFixed(2));
                                $('.checkout_total_price').html('$' + total.toFixed(2));
                                $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                                // $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                                $(this).parent().next().find('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                            });
                            
                        }
                    }
                    else {
                        if (tax_discount > total_tax) {
                            total_tax_price = 0;
                        } else {
                            total_tax_price = total_tax - tax_discount;
                        }
                        if (shipping_discount > shipment_price) {
                            total_shipping_price = 0;
                        } else {
                            total_shipping_price = shipment_price - shipping_discount;
                        }

                        if (add_discount > productTotal) {
                            subtotal = 0;
                            total = 0;
                        } else if(total_tax_price == 0  && total_shipping_price == 0) {
                            subtotal = productTotal - add_discount;
                            total = subtotal;
                        } else {
                            subtotal = productTotal - add_discount;
                            total = subtotal;
                        }
                        $('#discount_amount').val(add_discount.toFixed(2));
                        $('.checkout_discount_rate_manuall').html('$' + add_discount.toFixed(2));
                        $('#shipment_price').val(total_shipping_price.toFixed(2));
                        $('.total_tax').val(total_tax_price.toFixed(2));
                        $('#incl_tax').val(total.toFixed(2));
                        $('.checkout_total_price').html('$' + total.toFixed(2));
                        $('.checkout_tax_rate').html('$' + total_tax_price.toFixed(2));
                        $('.checkout_shipping_price').html('$' + total_shipping_price.toFixed(2));
                    }
                        
                }

                function assign_service_code(element) {
                    var product_total = $('.items_total_price').val() != null ? parseFloat($('.items_total_price').val()) : 0;
                    var tax = $('.total_tax').val() != null ? parseFloat($('.total_tax').val()) : 0;
                    var total_including_shipping = 0;
                    $('.shipping_service_code').each(function() {
                        $(this).removeAttr('checked');
                    });
                    if ($(element).is(':checked')) {
                        $(element).parent().find('.shipping_service_code').removeClass('d-none').attr('checked', 'checked');
                        $(element).parent().find('.shipping_service_code').addClass('d-none');
                        total_including_shipping =  product_total + tax + parseFloat($(element).val());
                        $('#incl_tax').val(total_including_shipping.toFixed(2));
                        $('#checkout_order_total').html('$' + total_including_shipping.toFixed(2));
                    }
                }

                function update_total_with_shipping_selected() {
                    var single_shipping_quote = $('#single_shipping_quote');
                    var admin_area_for_shipping_check = $('#admin_control_shipping').val();
                    if (admin_area_for_shipping_check === 'true') { 
                        if (single_shipping_quote.attr('checked')) {
                            var product_total = $('.items_total_price').val() != null ? parseFloat($('.items_total_price').val()) : 0;
                            var tax = $('.total_tax').val() != null ? parseFloat($('.total_tax').val()) : 0;
                            var total_including_shipping = 0;
                            total_including_shipping =  product_total + tax + parseFloat(single_shipping_quote.val());
                            $('#incl_tax').val(total_including_shipping.toFixed(2));
                            $('#checkout_order_total').html('$' + total_including_shipping.toFixed(2));
                        }
                    }
                }

                function update_total_with_shipping_for_greater_weight () {
                    var order_weight_greater_then_150 = $('#shipment_price_heavy_weight').val() != null ? parseFloat($('#shipment_price_heavy_weight').val()) : 0;
                    var product_total = $('.items_total_price').val() != null ? parseFloat($('.items_total_price').val()) : 0;
                    var tax = $('.total_tax').val() != null ? parseFloat($('.total_tax').val()) : 0;
                    var total_including_shipping = 0;
                    total_including_shipping =  product_total + tax + order_weight_greater_then_150;
                    $('#incl_tax').val(total_including_shipping.toFixed(2));
                    $('#checkout_order_total').html('$' + total_including_shipping.toFixed(2));
                }
            </script>
            @include('partials.footer')
            <script>
                $(document).ready(function() {
                    update_total_with_shipping_selected();
                    var admin_area_for_shipping_check = $('#admin_control_shipping').val();
                    var product_weight = $('.product_weight').val() != null ?  parseFloat($('.product_weight').val()) : 0;
                    if (admin_area_for_shipping_check === 'true' && product_weight > 150) {
                        update_total_with_shipping_for_greater_weight();
                    }   
                    const currentDate = new Date();
                    const formattedDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}T${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}`;
                    $('.datetime_').val(formattedDate);
                    $('.datetime_').attr('min', formattedDate);
                    $('.datetimeipad_').val(formattedDate);
                    $('.datetimeipad_').attr('min', formattedDate);
                    $('.datetimembl_').val(formattedDate);
                    $('.datetimembl_').attr('min', formattedDate);
                    var current_fs, next_fs, previous_fs; //fieldsets
                    var opacity;

                    function next_btn () {
                        var next = $('#next_step');
                        // $(".next").click(function() {
                        // updateContact_mbl($contact_id);
                            current_fs = next.parent();
                            next_fs = next.parent().next();

                            //Add Class Active
                            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                            //show the next fieldset
                            next_fs.show();
                            //hide the current fieldset with style
                            current_fs.animate({
                                opacity: 0
                            }, {
                                step: function(now) {
                                    // for making fielset appear animation
                                    opacity = 1 - now;
                                    current_fs.css({
                                        'display': 'none',
                                        'position': 'relative'
                                    });
                                    next_fs.css({
                                        'opacity': opacity
                                    });
                                },
                                duration: 600
                            });
                        // });
                    }

                    $(".previous").click(function() {

                        current_fs = $(this).parent();
                        previous_fs = $(this).parent().prev();

                        //Remove class active
                        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                        //show the previous fieldset
                        previous_fs.show();

                        //hide the current fieldset with style
                        current_fs.animate({
                            opacity: 0
                        }, {
                            step: function(now) {
                                // for making fielset appear animation
                                opacity = 1 - now;

                                current_fs.css({
                                    'display': 'none',
                                    'position': 'relative'
                                });
                                previous_fs.css({
                                    'opacity': opacity
                                });
                            },
                            duration: 600
                        });
                    });

                    $('.radio-group .radio').click(function() {
                        $(this).parent().find('.radio').removeClass('selected');
                        $(this).addClass('selected');
                    });

                    $(".submit").click(function() {
                        return false;
                    })

                });
            </script>







