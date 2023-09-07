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
    @media only screen and (max-width: 425px) and (min-width: 280px) {
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
    }
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
@if (Session::get('cart'))
    @foreach (Session::get('cart') as $cart)
        <?php
        $total_quatity = $cart['quantity'];
        $total_price = $cart['price'] * $total_quatity;
        $cart_total = $cart_total + $total_price;
        ?>
    @endforeach
@endif
<?php $check_allowed_zip_code = false; ?>
<div class="container-fluid w-75 desktop-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card p-5 border-0" style="background: #FAFAFA; border-radius: 6px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row" style="border-bottom: 2px dashed #CDCDCD;">
                            <div class="col-md-6">
                                <p class="billing-address-thank-you-page-heading mb-0">Shipping Address</p>
                            </div>
                            <div class="col-md-6">
                                <a data-bs-toggle="modal" href="#address_modal_id" role="button" class="float-end">
                                    <img src="/theme/img/thank_you_page_edit_icon.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="user-first-name-thank-you-page"> {{ $user_address->first_name ? $user_address->first_name : $user_address->firstName }}{{ $user_address->last_name ? $user_address->last_name : $user_address->lastName }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 1</p>
                                <p class="user-address-thank-you-page-item">{{ $user_address->address1 ? $user_address->address1 :  $user_address->postalAddress1}}</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">City</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->city ? $user_address->city : $user_address->postalCity }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">State</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->state ? $user_address->state : $user_address->postalState }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">Zip</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 2</p>
                                <p class="user-address-thank-you-page-item">{{ $user_address->address2 ? $user_address->address2 : $user_address->postalAddress2 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <p class="billing-address-thank-you-page-heading billing-border">Billing Address</p>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="user-first-name-thank-you-page"> {{ $user_address->first_name ? $user_address->first_name : $user_address->firstName }}{{ $user_address->last_name ? $user_address->last_name : $user_address->lastName }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 1</p>
                                <p class="user-address-thank-you-page-item">{{ $user_address->address1 ? $user_address->address1 :  $user_address->postalAddress1}}</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">City</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->city ? $user_address->city : $user_address->postalCity }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">State</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->state ? $user_address->state : $user_address->postalState }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="user-address-thank-you-page-title">Zip</p>
                                        <p class="user-address-thank-you-page-item">{{ $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="user-address-thank-you-page-title">Address line 2</p>
                                <p class="user-address-thank-you-page-item">{{ $user_address->address2 ? $user_address->address2 : $user_address->postalAddress2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ps-5">
        <div class="text-center d-none" id="progress_spinner"><img src="/theme/img/progress.gif" alt=""></div>
        <div class="col-md-12">
            <p class="item-purchased-thank-you-page">Item(s) Purchased </p>
        </div>
        <form action="{{ url('order') }}" method="POST" id="order_form" name="order_form" class="row mx-1">
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
                        @if (Session::get('cart'))
            
                            @foreach (Session::get('cart') as $product_id => $cart)
                                <?php
                                $total_quatity = $cart['quantity'];
                                $total_price = $cart['price'] * $total_quatity;
                                $cart_total = $cart_total + $total_price;
                                ?>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-2 py-2">
                                                @if ($cart['image'])
                                                    <img class="img-fluid img-thumbnail" src="{{ $cart['image'] }}"
                                                        alt="" width="90px" style="max-height: 90px">
                                                @else
                                                    <img src="/theme/img/image_not_available.png" alt=""
                                                        width="80px">
                                                @endif
                                            </div>
                                            <div class="col-md-8 py-2 ps-0">
                                                <a class="category-name-thank-you-page pb-3"
                                                    href="{{ url('product-detail/' .$product_id . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">
                                                    {{ $cart['name'] }}
                                                </a>
                                                <br>
                                                <p class="product-title-thank-you-page ">Title:<span
                                                        class="product-title-thank-you-page-title">
                                                        {{ $cart['name'] }}</span>
                                                </p>
                                                <p class="product-delete-icon-thank-you-page-icon">
                                                    <img class="img-fluid" src="/theme/img/thank-you-page-delete.icon.png"
                                                        alt="">
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="pt-4 thank-you-page-product-items-cart">{{ $cart['quantity'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="pt-4 thank-you-page-product-items-price">
                                            ${{ number_format($cart['price'], 2) }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="row">
                    @if($check_allowed_zip_code === true)
                        @if(empty($matchZipCode))
                            <div class="col-md-6 mt-5"style="margin:auto; !important; max-width:600px !important;">
                                <div class="alert alert-danger text-center">
                                    <span>
                                        <strong>Sorry, we don't deliver to this address.</strong>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4 mt-5"style="margin:auto; !important; max-width:600px !important;">
                                <button type="button" class="button-cards w-100 proceed_checkout_desktop" id="proceed_to_checkout" onclick="validate()"
                                style="background: #008BD3 ;border-radius: 5px;">Place order</button>
                            </div>
                        @endif
                    @else
                        <div class="col-md-4 mt-5"style="margin:auto; !important; max-width:600px !important;">
                            <button type="button" class="button-cards w-100 proceed_checkout_desktop" id="proceed_to_checkout" onclick="validate()"
                            style="background: #008BD3 ;border-radius: 5px;">Place order</button>
                        </div>
                    @endif
                
                </div>
            </div>
            <div class="col-md-3">
                <div class="row" style="background: #FAFAFA;border-radius: 5px;">
                    <p class="thank-you-page-product-items-delivery-options">Delivery Options</p>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            @foreach ($payment_methods as $payment_method)
                                
                                    @php
                                        $session_contact_id = Session::get('contact_id');
                                    @endphp
                                    @csrf
                                    <div class="row mx-0">
                                        @foreach ($payment_method->options as $payment_option)
                                            <div class="col-md-6 p-0 ps-4 d-flex align-items-center">
                                                <input type="hidden" value="{{ $payment_method->name }}"
                                                    name="method_name">
                                                <input type="radio" id="local_delivery_{{ $payment_option->id }}"
                                                    name="method_option"{{ $payment_option->option_name == 'Local Delivery' ? 'checked' : '' }}
                                                    value="{{ $payment_option->option_name }}" style="background: #008BD3;">
                                                <label for="local_delivery payment-option-label"
                                                    class="thank-you-page-product-items-payment-method-cart ml-2 mb-0">{{ $payment_option->option_name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mt-1">
                            <p class="thank-you-page-select-date-options mb-1">Please Select Date</p>
                            <input type="datetime-local" name="date" class="form-control datetime_" min="" id="date">
                        </div>
                        <div class="col-md-12">
                            <p class="thank-you-page-select-date-options mb-1">Purchase Order Number</p>
                            <input type="text" name="po_number" placeholder="PO Number" id="po_number"
                                class="form-control fontAwesome">
                        </div>
                        <div class="col-md-12">
                            <p class="thank-you-page-select-date-options mb-1">Memo</p>
                            <textarea type="text" name="memo" cols="20" rows="5" placeholder="Enter your Message"
                                id="memo" class="form-control fontAwesome">
                                </textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p class="thank-you-page-select-date-options mb-2">Payment Terms</p>
                            <select name="paymentTerms" id="pay_terms" class="form-control">
                                @if($user_address->paymentTerms == "Pay in Advanced" )
                                    <option value="Pay in Advanced" selected>Pay in Advanced</option>
                                @else
                                    <option value="30 days from invoice" selected>30 days from invoice</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <?php
                    $tax=0;
                    if (!empty($tax_class)) {
                        $tax = $cart_total * ($tax_class->rate / 100);
                    }
                    $total_including_tax = $tax + $cart_total  + $shipment_price;

                    ?>
                    <input type="hidden" name="incl_tax" id="incl_tax" value="{{ $total_including_tax }}">
                    <input type="hidden" name="shipment_price" id="shipment_price" value="{{ $shipment_price }}">
                    @if(!empty($tax_class))
                    <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{ $tax_class->id }}">
                    @else
                    <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class_none->id}}">
                    @endif
                    <div class="col-md-12 mt-3 py-3" style="background: #F7F7F7; border-radius: 5px;">
                        <p class="thank-you-page-product-imtes-total-cart">Total</p>
                        <div class="row">
                            <div class="col-md-6 mt-1">
                                <p class="thank-you-page-product-items-subtotal-cart">
                                    {{-- <img class="img-fluid" src="theme/img/pricing_tag.png" width=" 35px"> --}}
                                    Subtotal
                                </p>
                                <p class="thank-you-page-product-items-subtotal-cart">
                                    {{-- <img class=" img-fluid" src="/theme/img/tax_icon_check_out_page.png"> --}}
                                    <span>Rate</span> 
                                    @if(!empty($tax_class))
                                        ({{ number_format($tax_class->rate  , 2)}}%)
                                    @else 
                                        ({{ number_format(0  , 2)}})
                                    @endif
                                </p>
                                <p class="thank-you-page-product-items-subtotal-cart mt-4">
                                    {{-- <img class=" img-fluid" src="/theme/img/sub_total_icon_check_out_page.png"> --}}
                                    <span>Shipment Price</span>
                                </p>
                                <p class="thank-you-page-product-items-subtotal-cart mt-4">
                                    {{-- <img class=" img-fluid" src="/theme/img/sub_total_icon_check_out_page.png"> --}}
                                    <span>Total</span>
                                </p>

                            </div>
                            <div class="col-md-6">
                                <p class=" thank-you-page-product-item-cart mb-0">${{ number_format($cart_total, 2) }}</p>
                                {{-- <p class=" thank-you-page-product-item-cart">shipping</p> --}}
                                <p class=" thank-you-page-product-item-cart mb-0">${{ number_format($tax, 2) }}</p>
                                <p class=" thank-you-page-product-item-cart mb-0" id="shipment_price">${{number_format($shipment_price , 2)}}</p>
                                <p class="thank-you-page-product-item-cart-total mb-0" id="tax-rate">
                                    ${{ number_format($total_including_tax, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- <div class="row">
        <div class="col-md-4 m-auto"
            style="margin-top: 192px !important;margin:auto; !important; max-width:600px !important;">
            @if(empty($matchZipCode))
                <div class="alert alert-danger">
                    <span>
                        <strong>Sorry, we don't deliver to this address.</strong>
                    </span>
                </div>
                @else
                <button type="button" class="button-cards w-100 proceed_checkout_desktop" id="proceed_to_checkout" onclick="validate()"
                style="background: #008BD3 ;border-radius: 5px;">Place order</button>
            @endif
        </div>
        </form>
    </div> --}}
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
                            {{--<fieldset>
                                <button class="text-white billing-div-mobile" style="">
                                    Billing Details
                                </button>
                                <div class="form-card">
                                    <div class="card border-0">
                                        <div class="card-body p-0 m-0">
                                            <div class="form-signup-secondary">
                                                <div class="user-info">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label
                                                                class="label custom_label_style mt-5 text-uppercase fw-bold">First
                                                                Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" required placeholder="Enter your first name"
                                                                id="f_name" name="first_name"
                                                                value="{{ $user_address->first_name ? $user_address->first_name : $user_address->firstName }}"
                                                                class="form-control mt-0fontAwesome ">
                                                                <div id="error_first_name" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">last
                                                                Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your last"
                                                                id="l_name" name="last_name"
                                                                value="{{ $user_address->last_name ? $user_address->last_name : $user_address->lastName }}"
                                                                class="form-control fontAwesome  ">
                                                                <div id="error_last_name" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">company
                                                                name
                                                                (optional)</label>
                                                            <input type="text"
                                                                placeholder="Enter your company name"
                                                                value="{{ $user_address->company }}" id="u_company"
                                                                name="company"
                                                                class="form-control  company-info fontAwesome  ">
                                                                <div id="error_company" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">street
                                                                address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="House number and street name"
                                                                id="add_1" name="address"
                                                                value="{{ $user_address->address1 ? $user_address->address1 : $user_address->postalAddress1 }}"
                                                                class="form-control  company-info fontAwesome  ">
                                                                <div id="error_address1" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                placeholder="Aprtmant, suit, unit, etc.(optional)"
                                                                id="add_2" name="address2"
                                                                value="{{ $user_address->address2 ? $user_address->address2 : $user_address->postalAddress2 }}"
                                                                class="form-control  company-info fontAwesome  ">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">town
                                                                / city</label>
                                                            <input type="text" placeholder="Enter your town"
                                                                id="t_city" name="city"
                                                                value="{{ $user_address->city ? $user_address->city : $user_address->postalCity }}"
                                                                class="form-control  company-info fontAwesome  ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">state</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
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
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">zip</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your zip"
                                                                id="p_code" name="zip"
                                                                value="{{ $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}"
                                                                class="form-control  company-info fontAwesome  ">
                                                                <div id="error_zip" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">phone</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your phone"
                                                                id="d_phone" name="phone"
                                                                value="{{ $user_address->phone }}"
                                                                class="form-control  company-info fontAwesome  ">
                                                                <div id="error_phone" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button onclick="updateContact_mbl('{{ $contact_id }}')"
                                    class=" action-button btn btn-success w-100 text-white mt-4 mx-auto mob_next_btn_footer"
                                    style="background:#7bc533 !important;"> NEXT STEP </button>
                                <input type="hidden" class="" id="next_step">
                            </fieldset>--}}
                            <fieldset>
                                <div class="card p-4 border-0 mbl-checkout-card">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <div class="d-flex justify-content-between shipping-body-div-mbl">
                                                <div class="w-75">
                                                    <p class="billing-address-thank-you-page-heading mb-0">Billing Address</p>
                                                </div>
                                                <div class="w-25 text-right">
                                                    <a data-bs-toggle="modal" href="#address_modal_id" role="button">
                                                        <img src="/theme/img/thank_you_page_edit_icon.png" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row billing-border-row">
                                                <div class="col-md-12">
                                                    <p class="user-address-thank-you-page-item mt-3"> {{ $user_address->first_name ? $user_address->first_name : $user_address->firstName }}{{ $user_address->last_name ? $user_address->last_name : $user_address->lastName }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="user-address-thank-you-page-title mb-1">Address line 1</p>
                                                    <p class="user-address-thank-you-page-item">{{ $user_address->address1 ? $user_address->address1 :  $user_address->postalAddress1}}</p>
                                                    <p class="user-address-thank-you-page-title mb-1">Address line 2</p>
                                                    <p class="user-address-thank-you-page-item">{{ $user_address->address2 ? $user_address->address2 : $user_address->postalAddress2 }}</p>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">City</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->city ? $user_address->city : $user_address->postalCity }}
                                                            </p>
                                                        </div>
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">State</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->state ? $user_address->state : $user_address->postalState }}
                                                            </p>
                                                        </div>
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">Zip</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-5 text-left">
                                            <div class="d-flex justify-content-between shipping-body-div-mbl">
                                                <div class="w-75">
                                                    <p class="billing-address-thank-you-page-heading mb-0">Shipping Address</p>
                                                </div>
                                                <div class="w-25 text-right">
                                                    <a data-bs-toggle="modal" href="#address_modal_id" role="button">
                                                        <img src="/theme/img/thank_you_page_edit_icon.png" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row billing-border-row">
                                                <div class="col-md-12">
                                                    <p class="user-address-thank-you-page-item mt-3"> {{ $user_address->first_name ? $user_address->first_name : $user_address->firstName }}{{ $user_address->last_name ? $user_address->last_name : $user_address->lastName }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="user-address-thank-you-page-title mb-1">Address line 1</p>
                                                    <p class="user-address-thank-you-page-item">{{ $user_address->address1 ? $user_address->address1 :  $user_address->postalAddress1}}</p>
                                                    <p class="user-address-thank-you-page-title mb-1">Address line 2</p>
                                                    <p class="user-address-thank-you-page-item">{{ $user_address->address2 ? $user_address->address2 : $user_address->postalAddress2 }}</p>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">City</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->city ? $user_address->city : $user_address->postalCity }}
                                                            </p>
                                                        </div>
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">State</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->state ? $user_address->state : $user_address->postalState }}
                                                            </p>
                                                        </div>
                                                        <div class="">
                                                            <p class="user-address-thank-you-page-title mb-1">Zip</p>
                                                            <p class="user-address-thank-you-page-item">{{ $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-card">
                                    <div class="cart-items-checkout mx-4 mt-4 mb-4">Cart Items</div>
                                    <table>
                                        <tbody class="border-0">
                                            <?php
                                            $cart_total = 0;
                                            $cart_price = 0;
                                            ?>
                                            @if (Session::get('cart'))
                                                @foreach (Session::get('cart') as  $product_id => $cart)
                                                    <?php
                                                        $total_quatity = $cart['quantity'];
                                                        $total_price = $cart['price'] * $total_quatity;
                                                        $cart_total = $cart_total + $total_price;
                                                    ?>
                                                    {{-- <tr>
                                                        <td class="ps-3">
                                                            <div class="mt-3">
                                                                <a class="product-name"
                                                                    href="{{ url('product-detail/' . $product_id . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">
                                                                    {{ $cart['name'] }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr> --}}
                                                    <tr class="border_top_mb">
                                                        <td class="pl-4 checkout-image-td-mbl pt-3 pb-3" style="">
                                                            <div class="py-2 mobile_thankyou_img_div">
                                                                @if ($cart['image'])
                                                                <img class="img-fluid img-thumbnail m_chechout_image" src="{{$cart['image']}}" alt=""
                                                                    width="90px" style="max-height: 90px">
                                                                @else
                                                                <img src="/theme/img/image_not_available.png" class="m_chechout_image" alt="" width="80px">
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="pl-3 checkout-product-name-td-mbl pt-3 pb-3">
                                                            <div class="ps-0 mobile_text_class" style="">
                                                                <p class="mb-0">
                                                                    <a class="order-confirmation-page-product-category-name pb-2"
                                                                        href=" {{ url('product-detail/'. $product_id.'/'.$cart['option_id'].'/'.$cart['slug']) }}">
                                                                        {{$cart['name']}}
                                                                    </a>
                                                                </p>
                                                                <p class="product-title-thank-you-page mb-0">Title:<span
                                                                    class="product-title-thank-you-page-title">
                                                                    {{ $cart['name'] }}</span>
                                                                </p>
                                                                <p class=" mb-0 order-confirmation-page-product-price text-right"> ${{number_format($cart['price'],2)}}</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <form class="" action="{{ url('order') }}" method="POST" id="order_form_mbl" class="order_form" name="order_form">
                                        <div class="delievery_options_div_mbl p-3">
                                            <h3 class="delievery_options_heading ml-2">Delivery Options</h3>
                                            <div class="d-flex justify-content-between">
                                                @csrf
                                                @foreach ($payment_methods as $payment_method)
                                                    @foreach ($payment_method->options as $payment_option)
                                                        <div class="w-50 text-center d-flex align-items-center justify-content-center">
                                                            <input type="hidden" value="{{ $payment_method->name }}" name="method_name">
                                                            <input type="radio" class="mb-0 radio_delievery" id="local_delivery_{{ $payment_option->id }}"
                                                            name="method_option"  {{ $payment_option->option_name == 'Local Delivery' ? 'checked' : '' }}
                                                            value="{{ $payment_option->option_name }}">
                                                            <label for="local_delivery payment-option-label" class="mb-0 ml-2 delievery_label">{{ $payment_option->option_name }}</label>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                                <input type="hidden" name="incl_tax" id="incl_tax" value="{{ $total_including_tax }}">
                                                <input type="hidden" name="shipment_price" id="shipment_price" value="{{ $shipment_price }}">
                                                @if(!empty($tax_class))
                                                <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{ $tax_class->id }}">
                                                @else
                                                <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class_none->id}}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="w-100 suborderSummarymbl_main p-3">
                                            <div class="suborderSummarymbl p-2">
                                                <div>
                                                    <h3 class="delievery_options_mbl mb-3">
                                                        Total
                                                    </h3>
                                                    <div class="d-flex w-100 mb-2">
                                                        <div class="w-50 p-1">
                                                            <span class="summary_sub_total_head">Subtotal:</span>
                                                        </div>
                                                        <div class="w-50 p-1 text-right">
                                                            <span class="summary_sub_total_price text-right">${{ number_format($cart_total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 mb-2">
                                                        <div class="w-50 p-1">
                                                            <span class="summary_sub_total_head">Rate
                                                                @if(!empty($tax_class))
                                                                    ({{ number_format($tax_class->rate  , 2)}}%)
                                                                @else 
                                                                        ({{ number_format(0  , 2)}})
                                                                @endif 
                                                                :
                                                            </span>
                                                        </div>
                                                        <div class="w-50 p-1 text-right">
                                                            <p class="summary_sub_total_price text-right mb-0" id="mbl_tax_price">${{ number_format($tax, 2) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 mb-2">
                                                        <div class="w-50 p-1">
                                                            <span class="summary_sub_total_head">Shipment Price:</span>
                                                        </div>
                                                        <div class="w-50 p-1 text-right">
                                                            <span class="summary_sub_total_price text-right">${{number_format($shipment_price , 2)}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100">
                                                        <div class="w-50 p-1 d-flex align-items-center">
                                                            <span class="summary_sub_total_head">Total:</span>
                                                        </div>
                                                        <div class="w-50 p-1 text-right">
                                                            <p class="summary_total_price text-right mb-0" id="mbl_total_p">${{ number_format($total_including_tax, 2) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2 p-3">
                                            <div class="ps-1">
                                                <div class=" mt-1">
                                                    <p class="payment-option">Please Select Date</p>
                                                    <input type="datetime-local" name="date" class="form-control datetimembl_" min="" id="date">
                                                </div>
                                                <div class="">
                                                    <p class="payment-option">Purchase Order Number</p>
                                                    <input type="text" name="po_number" placeholder="PO Number" id="po_number"
                                                        class="form-control fontAwesome">
                                                </div>
                                                <div class="">
                                                    <p class="payment-option">Memo</p>
                                                    <textarea type="text" name="memo" cols="20" rows="5" placeholder="Enter your Message"
                                                        id="memo" class="form-control fontAwesome">
                                                        </textarea>
                                                </div>
                                                <div class="">
                                                    <p class="payment-option">Payment Terms</p>
                                                    
                                                    <select name="paymentTerms" id="pay_terms" class="form-control w-75">
                                                        @if($user_address->paymentTerms == "Pay in Advanced" )
                                                            <option value="Pay in Advanced" selected>Pay in Advanced</option>
                                                        @else
                                                            <option value="30 days from invoice" selected>30 days from invoice</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if($check_allowed_zip_code === true)
                                            @if(empty($matchZipCode))
                                                <div class="w-100">
                                                    <div class="alert alert-danger text-center">
                                                        <span>
                                                            <strong>Sorry, we don't deliver to this address.</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <button type="button" class="proceedCheckoutmbl mt-4 w-100 p-2 border-0"
                                                        id="proceed_to_checkout" onclick="validate_mbl()">
                                                        Place Order</button>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <button type="button" class="proceedCheckoutmbl mt-4 w-100 p-2 border-0"
                                                    id="proceed_to_checkout" onclick="validate_mbl()">
                                                    Place Order</button>
                                            </div>
                                        @endif
                                    </form>
                                    <div>
                                        <table class="table mt-5">
                                            {{-- <thead>
                                                <tr>
                                                    <th style="border-top:none !important" scope="col">Cart Total
                                                    </th>
                                                </tr>
                                            </thead> --}}
                                            {{-- <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="theme/img/pricing_tag.png" width=" 22px">
                                                                <span class="cart-total-checkout-page">Sub Total</span>
                                                            </span>
                                                            <span class="d-flex justify-content-end aling-items-end">
                                                                <p class="sub-total-checkout-page"> ${{ number_format($cart_total, 2) }} </p>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="/theme/img/text-rate-icon.png" alt="" width=" 22px">
                                                                <span id="mbl_tax_rate">Rate
                                                                        @if(!empty($tax_class))
                                                                            ({{ number_format($tax_class->rate  , 2)}}%)
                                                                        @else 
                                                                                ({{ number_format(0  , 2)}})
                                                                        @endif
                                                                </span>
                                                            </span>
                                                            <p id="mbl_tax_price">${{ number_format($tax, 2) }}</p>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="/theme/img/pricing_tag.png" alt="" width=" 22px">
                                                                <span class="cart-total-checkout-page">Total</span>
                                                            </span>
                                                            <span>
                                                                <p id="mbl_total_p" class="sub-total-checkout-page">${{ number_format($total_including_tax, 2) }}</p>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody> --}}
                                            {{-- <tfoot class="border-0">
                                                <tr>
                                                    <td style="border-bottom:none !important;">
                                                        <div class="payment-option">Delivery Options</div>
                                                        @foreach ($payment_methods as $payment_method)
                                                            <form class="p-2" action="{{ url('order') }}"
                                                                method="POST" id="order_form_mbl" class="order_form" name="order_form">
                                                                @csrf

                                                                @foreach ($payment_method->options as $payment_option)
                                                                    <div class="row">
                                                                        <div class="ps-1">
                                                                            <input type="hidden"
                                                                                value="{{ $payment_method->name }}"
                                                                                name="method_name">
                                                                            <input type="radio"
                                                                                id="local_delivery_{{ $payment_option->id }}"
                                                                                name="method_option"  {{ $payment_option->option_name == 'Local Delivery' ? 'checked' : '' }}
                                                                                value="{{ $payment_option->option_name }}">
                                                                            <label
                                                                                for="local_delivery payment-option-label">{{ $payment_option->option_name }}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                        @endforeach
                                                        <input type="hidden" name="incl_tax" id="incl_tax" value="{{ $total_including_tax }}">
                                                        @if(!empty($tax_class))
                                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{ $tax_class->id }}">
                                                        @else
                                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class_none->id}}">
                                                        @endif
                                                        <div class="row mt-2">
                                                            <div class="ps-1">
                                                                <div class=" mt-1">
                                                                    <p class="payment-option">Please Select Date</p>
                                                                    <input type="date" name="date" class="form-control" min="{{ now()->toDateString('Y-m-d') }}" id="date">
                                                                </div>
                                                                <div class="">
                                                                    <p class="payment-option">Phone Number</p>
                                                                    <input type="text" name="po_number" placeholder="Enter your phone number" id="po_number"
                                                                        class="form-control fontAwesome">
                                                                </div>
                                                                <div class="">
                                                                    <p class="payment-option">Memo</p>
                                                                    <textarea type="text" name="memo" cols="20" rows="5" placeholder="Enter your Message"
                                                                        id="memo" class="form-control fontAwesome">
                                                                        </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="w-100 text-center">
                                                            <button type="button" class="procedd-to-checkout mt-4 w-100"
                                                                id="proceed_to_checkout" onclick="validate_mbl()">
                                                                Place Order</button>
                                                        </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tfoot> --}}
                                        </table>
                                    </div>
                                    {{-- <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <img class="img-fluid coupon-code-modal-btn"
                                                src="/theme/img/modal-icon1.png" alt="">
                                        </div>
                                        <button type="button"
                                            class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Apply Coupon
                                        </button>
                                    </div> --}}
                                </div>
                                {{-- <input type="button" name="previous" class="previous action-button-previous prev_btn_mbl" value="Previous" /> --}}
                                {{-- <input type="button" name="next" class="next action-button" value="Next Step" /> --}}
                            </fieldset>
                            {{-- <fieldset>
                                <div class="form-card form-signup-secondary"></div>
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
                                            <input type="text" placeholder="MM/YY" id="expiryDate"
                                                name="expiryDate" class="form-control company-info fontAwesome"
                                                style="
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
                                                class="form-control company-info fontAwesome"
                                                step="width: 154px;
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;">
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                            </fieldset> --}}
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
                                <input type="button" value="Next" name="next"
                                    class="next action-button next-btn-mobile"
                                    style="background:#7bc533 !important;left:86% !important;">
                                <button class="text-white billing-div-mobile" style="width: 104% !important;">
                                    Billing Details
                                </button>
                                <div class="form-card">
                                    <div class="card border-0">
                                        <div class="card-body p-0 m-0">
                                            <div class="form-signup-secondary">
                                                <div class="user-info">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style mt-5 fw-bold text-uppercase">First
                                                                Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your first name"
                                                                id="company_website" name="firstName"
                                                                value="{{ $user_address->first_name ?  $user_address->first_name : $user_address->firstName}}"
                                                                class="form-control mt-0fontAwesome">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">last
                                                                Name</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your last"
                                                                id="company_website" name="lastName"
                                                                value="{{ $user_address->last_name ?  $user_address->last_name : $user_address->lastName}}"
                                                                class="form-control fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase">company
                                                                name
                                                                (optional)</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="Enter your company name"
                                                                value="{{ $user_address->company }}" id="company"
                                                                name="company"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">street
                                                                address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="House number and street name"
                                                                id="postalAddress1" name="postalAddress1"
                                                                value="{{ $user_address->address1 ?  $user_address->address1 : $user_address->postalAddress1}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text"
                                                                placeholder="Aprtmant, suit, unit, etc.(optional)"
                                                                id="postalAddress2" name="postalAddress2"
                                                                value="{{ $user_address->address2 ?  $user_address->address2 : $user_address->postalAddress2}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">town
                                                                / city</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your town"
                                                                id="postalCity" name="postalCity"
                                                                value="{{ $user_address->city ?  $user_address->city : $user_address->postalCity}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">state</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your state"
                                                                id="postalState" name="postalState"
                                                                value="{{ $user_address->state ?  $user_address->state : $user_address->postalState}}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">zip</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your zip"
                                                                id="postalPostCode" name="postalPostCode"
                                                                value="{{$user_address->postCode ? $user_address->postCode : $user_address->postalPostCode }}"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">phone</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text" placeholder="Enter your phone"
                                                                id="phone" name="phone"
                                                                value="{{ $user_address->phone }}"
                                                                class="form-control  company-info fontAwesome ">
                                                            <div class="text-danger" id="password_errors"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label
                                                                class="label custom_label_style fw-bold text-uppercase ">email
                                                                address</label><span
                                                                class="text-danger fw-bold pl-1">*</span>
                                                            <input type="text"
                                                                placeholder="Enter your email adress"
                                                                id="emailAddress" name="password"
                                                                class="form-control  company-info fontAwesome ">
                                                        </div>
                                                        {{-- <button type="button" class="btn btn-success next action-button text-white ipad_next_btn_footer mx-3" style="background:#7bc533 !important;"> NEXT STEP </button> --}}
                                                        {{-- <div class="d-flex justify-content-center align-items-center">
                                                            <div>
                                                                <img class="img-fluid coupon-code-modal-btn" src="/theme/img/modal-icon1.png" alt="">
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#staticBackdrop">
                                                                applay coupon
                                                            </button>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="updateContact_ipad({{$contact_id}})" class="btn btn-success  action-button text-white ipad_next_btn_footer"
                                    style="background:#7bc533 !important;"> NEXT STEP </button>
                                <input type="hidden" class="" id="next_step">
                                {{-- <div class="d-flex justify-content-center align-items-center">
                                    <div>
                                        <img class="img-fluid coupon-code-modal-btn" src="/theme/img/modal-icon1.png"
                                            alt="">
                                    </div>
                                    <button type="button" class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        applay coupon
                                    </button>
                                </div> --}}
                                {{-- <input type="button" name="next" class="next action-button" value="Next Step" /> --}}
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
                                            @if (Session::get('cart'))
                                                @foreach (Session::get('cart') as $cart)
                                                    <?php
                                                    $total_quatity = $cart['quantity'];
                                                    $total_price = $cart['price'] * $total_quatity;
                                                    $cart_total = $cart_total + $total_price;
                                                    ?>
                                                    <tr class="border-white">
                                                        <td class="ps-4 border-white">
                                                            <div class="mt-3">
                                                                <a class="product-name"
                                                                    href="
                                                        {{ url('product-detail/' . $cart['product_id'] . '/' . $cart['option_id'] . '/' . $cart['slug']) }}
                                                        ">
                                                                    {{ $cart['name'] }}
                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td class="d-flex justify-content-end align-items-end">
                                                            <div class="text-muted rounded-circle mt-3  product-quantity"
                                                                id="circle">
                                                                {{ $cart['quantity'] }}</div>
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
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="theme/img/pricing_tag.png" width=" 22px">
                                                                <span class="cart-total-checkout-page">Sub Total</span>
                                                            </span>
                                                            <span class="d-flex justify-content-end aling-items-end">
                                                                <p class="sub-total-checkout-page"> ${{ number_format($cart_total, 2) }} </p>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="/theme/img/text-rate-icon.png" alt="" width=" 22px">
                                                                <span id="ipad_tax_rate">Rate
                                                                    @if(!empty($tax_class))
                                                                        ({{ number_format($tax_class->rate  , 2)}}%)
                                                                    @else 
                                                                        ({{ number_format(0  , 2)}})
                                                                    @endif
                                                                </span>
                                                            </span>
                                                            <p id="ipad_tax_price">${{ number_format($tax, 2) }}</p>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="/theme/img/text-rate-icon.png" alt="" width=" 22px">
                                                                <span id="ipad_shipment">Shipment Price
                                                                </span>
                                                            </span>
                                                            <p id="ipad_shipment_price">${{number_format($shipment_price , 2)}}</p>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                <img src="/theme/img/pricing_tag.png" alt="" width=" 22px">
                                                                <span class="cart-total-checkout-page">Total</span>
                                                            </span>
                                                            <span>
                                                                <p id="ipad_total_p" class="sub-total-checkout-page">${{ number_format($total_including_tax, 2) }}</p>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="border-0">
                                                <tr>
                                                    <td style="border-bottom:none !important;">
                                                        <div class="cart-total-checkout-page ps-3">Delivery Options</div>
                                                        @foreach ($payment_methods as $payment_method)
                                                            <form class="p-2" action="{{ url('order') }}"
                                                                method="POST" id="order_form_ipad" name="order_form">
                                                                @csrf
                                                                @foreach ($payment_method->options as $payment_option)
                                                                    <div class="row">
                                                                        <div class="ps-4">
                                                                            <input type="hidden"
                                                                                value="{{ $payment_method->name }}"
                                                                                name="method_name">
                                                                            <input type="radio"
                                                                                id="local_delivery_{{ $payment_option->id }}"
                                                                                name="method_option"  {{ $payment_option->option_name == 'Local Delivery' ? 'checked' : '' }}
                                                                                value="{{ $payment_option->option_name }}">
                                                                            <label
                                                                                for="local_delivery payment-option-label">{{ $payment_option->option_name }}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                        @endforeach
                                                        <input type="hidden" name="shipment_price" id="shipment_price" value="{{ $shipment_price }}">
                                                        <input type="hidden" name="incl_tax" id="incl_tax" value="{{ $total_including_tax }}">
                                                        @if(!empty($tax_class))
                                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{ $tax_class->id }}">
                                                        @else
                                                        <input type="hidden" name="tax_class_id" id="tax_class_id" value="{{$tax_class_none->id}}">
                                                        @endif
                                                        <div class="row mt-2">
                                                            <div class="ps-1">
                                                                <div class=" mt-1">
                                                                    <p class="cart-total-checkout-page">Please Select Date</p>
                                                                    <input type="datetime-local" name="date" class="form-control datetimeipad_" min="" id="date">
                                                                </div>
                                                                <div class="">
                                                                    <p class="cart-total-checkout-page">Purchase Order Number</p>
                                                                    <input type="text" name="po_number" placeholder="PO Number" id="po_number"
                                                                        class="form-control fontAwesome">
                                                                </div>
                                                                <div class="">
                                                                    <p class="cart-total-checkout-page">Memo</p>
                                                                    <textarea type="text" name="memo" cols="20" rows="5" placeholder="Enter your Message"
                                                                        id="memo" class="form-control fontAwesome">
                                                                        </textarea>
                                                                </div>
                                                                <div class="">
                                                                    <p class="cart-total-checkout-page">Payment Terms</p>
                                                                    <select name="paymentTerms" id="pay_terms" class="form-control">
                                                                        @if($user_address->paymentTerms == "Pay in Advanced" )
                                                                            <option value="Pay in Advanced" selected>Pay in Advanced</option>
                                                                        @else
                                                                            <option value="30 days from invoice" selected>30 days from invoice</option>
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($check_allowed_zip_code === true)
                                                            @if(empty($matchZipCode))
                                                                <div class="col-md-6 mt-5"style="margin:auto; !important; max-width:600px !important;">
                                                                    <div class="alert alert-danger text-center">
                                                                        <span>
                                                                            <strong>Sorry, we don't deliver to this address.</strong>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="d-flex justify-content-center mt-3">
                                                                    <button type="button" class="button-cards w-50 proceed_checkout_ipad" id="proceed_to_checkout" onclick="validate_ipad()"> Place Order</button>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="d-flex justify-content-center mt-3">
                                                                <button type="button" class="button-cards w-50 proceed_checkout_ipad" id="proceed_to_checkout" onclick="validate_ipad()"> Place Order</button>
                                                            </div>
                                                        @endif

                                                        </form>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    {{-- <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <img class="img-fluid coupon-code-modal-btn"
                                                src="/theme/img/modal-icon1.png" alt="">
                                        </div>
                                        <button type="button"
                                            class="btn btn-primary fw-blod coupon-code-modal-btn ps-0"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Apply Coupon
                                        </button>
                                    </div> --}}
                                </div>
                                {{-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> --}}
                                {{-- <input type="button" name="next" class="next action-button" value="Next Step" /> --}}
                            </fieldset>
                            {{-- <fieldset>
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
                                            <input type="text" placeholder="MM/YY" id="expiryDate"
                                                name="expiryDate" class="form-control company-info fontAwesome"
                                                style="
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
                                                class="form-control company-info fontAwesome"
                                                style="
                                                height: 42px;
                                                background-color: #f6f6f6;
                                                border: 1px solid #dedede;
                                                font-size: 14px;
                                                ">
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" /> --}}
                                {{-- <input type="button" name="make_payment" class="next action-button"
                                    value="Confirm" /> --}}
                            {{-- </fieldset> --}}
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
                        <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
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
                <div class="spinner-border text-primary d-none" role="status" id="address_loader">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary mbl-btn-update-address"
                    onclick="updateContact('{{ $contact_id }}')">Update</button>
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
            </script>
            @include('partials.footer')
            <script>
                $(document).ready(function() {
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

