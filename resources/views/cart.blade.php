
@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .cart-behaviour-button , .cart-button-slogen {
        font-family: 'poppins';
    }
    .cart-behaviour-table > tr , td {
        font-family: 'poppins';
    }

    /* Custom Title Styling */
    .swal-title-black {
        color: black !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        font-family: 'poppins' !important;
    }

    /* Custom Popup Styling */
    .swal-popup-custom {
        padding: 25px !important;
        width: 450px !important;
        border-radius: 10px !important;
    }

    

    .swal-confirm-button , .swal-cancel-button {
        background-color: #7BC533 !important;
        color: #FFFFFF !important;
        border: none !important;
        line-height: 27px   !important;
        font-style: normal !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        line-height: 24px !important;
        letter-spacing: 0.05em  !important;
        padding: 5px 25px !important;
        border-radius: 20px !important;
    }

    .swal-confirm-button:focus , .swal-cancel-button:focus {
        box-shadow: 0 0 0 0px rgba(112,102,224,.5) !important;
    }

    .swal-text-black {
        color: 000 !important;
        font-size: 16px !important;
        font-weight: 400 !important;
        font-family: 'poppins' !important;
    }
    

</style>

{{-- <input type="hidden" name="" id="initial_free_shipping_value" class="initial_free_shipping_value" value="{{$free_shipping}}"> --}}
<div class="row desktop-view cart-title mt-4">
    <p style="line-height: 95px;"
        class=" fs-2 product-btn my-auto border-0 text-white text-center align-middle cart-title">
        <span class="cart-page-cart-title">CART</span>
    </p>
    <input type="hidden" name="" id="buy_list_id" class="buy_list_id" value="{{ !empty($buy_list_id) ? $buy_list_id : '' }}">
</div>
{{-- @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif --}}
{{-- @if (auth()->user())
    @php
        $pass_out_of_stock_items = session('out_of_stock_items') ?? $out_of_stock_items ?? [];
        $pass_original_items_quantity = session('original_items_quantity') ?? $original_items_quantity ?? [];
        
    @endphp
    <input type="hidden" name="pass_original_items_quantity" id="pass_original_items_quantity" value='{{ json_encode($pass_original_items_quantity) }}'>
    @if (!empty($pass_out_of_stock_items) || !empty($pass_original_items_quantity))
        <div class="row justify-content-center my-3">
            <div class="col-md-12 col-lg-12 col-xl-9">
                <div class="table-responsive">
                    <h5 class="mt-3 text-white mb-3 product-btn h-auto p-3">
                        The following items are out of stock or have been updated to the maximum available stock
                    </h5>
                    <div class="row mb-3">
                        @foreach ([
                            ['Notify', 'primary', 'send_notification_to_admin', $pass_out_of_stock_items, 'Send Notification for Out of Stock Items'],
                            ['Update', 'info', 'update_quantity_to_original', $pass_original_items_quantity, 'Update Quantity for Updated Items'],
                            ['Remove', 'danger', 'remove_out_of_stock_items', $pass_out_of_stock_items, 'Remove Out of Stock Items From Cart']
                        ] as [$label, $btnClass, $funcName, $data, $text])
                            <div class="col-12 col-md-4 mb-2">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <span class="cart-button-slogen">{{ $text }}</span>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-{{ $btnClass }} cart-behaviour-button" type="button" onclick="{{ $funcName }}({{ json_encode($data) }})">
                                            {{ $label }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <table class="table table-bordered cart-behaviour-table">
                        <thead class="thead-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Stock Available</th>
                                <th>Requested Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Out of Stock Items -->
                            @foreach ($pass_out_of_stock_items as $item)
                                <tr class="text-danger">
                                    <td>{{ $item['product_name'] }}</td>
                                    <td><span class="badge badge-danger">Out of Stock</span></td>
                                    <td>{{ $item['quantity'] }}</td>
                                </tr>
                            @endforeach
                            @foreach ($pass_original_items_quantity as $item)
                                <tr class="text-secondary" id="pass_original_items_quantity_{{$item['product_primary_id']}}">
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['stock_available'] }}</td>
                                    <td id="pass_original_items_quantity_child_{{$item['product_primary_id']}}">{{ $item['quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endif --}}



@php
    $address_url = 'my-account/address';
@endphp
<div class="container-fluid desktop-view">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 col-xl-10">
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {!! \Session::get('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {!! \Session::get('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                @if(auth()->user())
                <div class="col-sm-12 col-md-12 col-lg-9 mt-2">
                    @else
                <div class="col-md-12 col-lg-9 ">
                    @endif
                    <section class=" h-100">
                        <div class="h-100 py-5">
                            <div class="row">
                                <div class="col-md-12">
        
                                    {{-- @if (Auth::check() == true && $contact->status == 0)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Checkout has been disabled.
                                        </div>
                                    @endif --}}
                                    @if (session('message'))
                                        <div class="alert alert-danger">
                                            {{ session('message') }}
                                        </div>
                                    @endif
                                    @if (session('address_message'))
                                        <div class="alert alert-danger">
                                            {{ session('address_message') }}  <a href="{{route('my_account_address')}}">Click Here</a>
                                            {{'to update your address'}}
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table mt-4 mb-0" id="cart_table">
                                            <thead class="table-head-items">
                                                <tr>
                                                    {{-- <th scope="col" class="th-lg" class="table-head-items">
                                                        <span>
                                                            <img class="img-fluid" src="/theme/img/barcode.png"
                                                                style="width: 29px;
                                                            ">
                                                        </span>
                                                        <span class=" cart-total">SKU</span>
                                                    </th> --}}
                                                    <th>
                                                        <div
                                                            class="d-flex aling-items-center justify-content-between sku-img-cart-page-table-header">
                                                            <span>
                                                                <img class="img-fluid w-75" src="/theme/img/barcode.png">
                                                            </span>
                                                            <span class=" cart-total">SKU</span>
                                                        </div>
                                                    </th>
                                                    <th scope="col" class="th-lg" class="table-head-items">
                                                        <span>
                                                            <img class="img-fluid" src="/theme/img/box.png">
                                                        </span>
                                                        <span class=" cart-total">Product</span>
        
                                                    </th>
                                                    <th scope="col" class="th-lg" width="147px" class="table-head-items">
                                                        <span>
                                                            {{-- <img src="/theme/img/price_target.png" class="img-fluid"> --}}
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <span class="cart-total">Price</span>
        
                                                    </th>
                                                    <th scope="col" class="th-lg" width="170px;" class="table-head-items">
                                                        <span>
                                                            {{-- <img src="/theme/img/Arrows_Down_Up.png" alt=""> --}}
                                                            <i class="fas fa-sort-numeric-up-alt"></i>
                                                        </span>
                                                        <span class="cart-total">Quantity</span>
        
                                                    </th>
                                                    <th scope="col" class="th-lg text-center" class="table-head-items">
                                                        <span>
                                                            {{-- <img class=" cart-icons-cart " src="/theme/img/pricing_tag.png"> --}}
                                                            <i class="fa fa-tags"></i>
                                                        </span>
                                                        <span class=" cart-total">Total</span>
        
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody style="padding: 40px">
                                                <?php
                                                $cart_total = 0;
                                                $cart_price = 0;
                                                ?>
                                                {{-- @if (Session::get('cart')) --}}
                                                @if (count($cart_items) > 0)
                                                @foreach ($cart_items as $pk_product_id => $cart)
                                                        <?php
                                                        if (!auth()->user()) {
                                                            $pk_product_id = $pk_product_id;
                                                        } else {
                                                            if (!empty(Session::get('contact_id'))) {
                                                                $pk_product_id = $pk_product_id;
                                                            } else {
                                                                $pk_product_id = $cart['qoute_id'];
                                                            }
                                                        }
                                                        $total_quatity = $cart['quantity'];
                                                        $total_price = $cart['price'] * $total_quatity;
                                                        $cart_total = $cart_total + $total_price;
                                                        $stock_per_product = 0;
                                                        $stock_per_product = App\Helpers\UserHelper::get_stock_per_product_option($pk_product_id, $cart['option_id']);  
                                                        ?>
                                                        <tr id="{{ 'row_' . $pk_product_id }}" class="quantities">
                                                            <td class="align-middle">
                                                                <span class="mb-0" style="font-weight: 500;">
                                                                    <a class="cart-page-items"
                                                                        href="{{ url('product-detail/' . $pk_product_id . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">{{ $cart['code'] }}
                                                                    </a>
                                                                </span>
                                                            </td>
                                                            <td scope=" row">
                                                                <div class="d-flex align-items-center mt-3">
                                                                        @if (!empty($cart['image']))
                                                                            <img src="{{ $cart['image'] }}"
                                                                                class="img-fluid rounded-3" style="width: 80px;"
                                                                                alt="Book">
                                                                        @else
                                                                            <img src="/theme/img/image_not_available.png"
                                                                                class="img-fluid rounded-3" style="width: 78px;"
                                                                                alt="Book">
                                                                        @endif
                                                                    <div class="flex-column ms-4">
                                                                        <span class="mb-2">
                                                                            <a class=" pe-3 cart-page-items"
                                                                                href="{{ url('product-detail/' . $pk_product_id . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">{{ $cart['name'] }}
                                                                            </a>
                                                                            
                                                                        </span>
                                                                        @if (intval($cart['quantity']) > intval($stock_per_product))
                                                                            @if ((empty($get_wholesale_terms) || strtolower($get_wholesale_terms) != 'pay in advanced') && auth()->user())
                                                                                <div class="d-flex mt-2 stock_tooltip" id="badge_{{ $pk_product_id }}">
                                                                                    <div class="stock_tooltip_message">
                                                                                        {{ App\Helpers\SettingHelper::getSetting('stock_tooltip_message') }}
                                                                                    </div>
                                                                                    <span class="badge badge-warning">
                                                                                        On Back Order 
                                                                                    </span>
                                                                                    <span class="ms-2 show_message">
                                                                                        <i class="fas fa-question-circle"></i>
                                                                                    </span>
                                                                                </div>
                                                                            @else
                                                                                @if (intval($stock_per_product) < 1) 
                                                                                    <div class="d-flex">
                                                                                        <span class="badge badge-danger">
                                                                                            {{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');}}
                                                                                        </span>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="d-flex">
                                                                                        <span class="badge badge-danger">
                                                                                            Stock Limit Exceeded
                                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class=" align-middle">
                                                                <p class="mb-0 ps-2  cart-page-items">
                                                                    ${{ number_format($cart['price'], 2) }}
                                                                </p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="mt-4 ml-1">
                                                                    <div class="quantity cart_qty">
                                                                        <input type="number" name="quantity" class="quantity_calculator" id={{ 'row_quantity_' . $pk_product_id }}
                                                                            min="1" max="{{$stock_per_product}}" step="1" data-old = "{{ $cart['quantity'] }}"
                                                                            value="{{ $cart['quantity'] }}" onchange="update_cart_products('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')">
                                                                        <input type="hidden" name="p_id" id="p_id"
                                                                            value="{{ $cart['product_id'] }}">
                                                                        <input type="hidden" name="p_id" id="option_id"
                                                                            value="{{ $cart['option_id'] }}">
                                                                        <div class="quantity-nav cart-quantity-nav">
                                                                            <div class="quantity-div quantity-up"
                                                                                onclick="increase_qty('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')">
                                                                                
                                                                            </div>
                                                                            <div class="quantity-div quantity-down"
                                                                                onclick="decrease_qty('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')">
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                <div class="row align-items-center text-center">
                                                                    <span class="mb-0 text-danger ps-2  cart-page-items">
                                                                        <span
                                                                            id="subtotal_{{ $pk_product_id}}">${{ number_format($cart['price'] * $cart['quantity'], 2) }}</span>
                                                                    </span>
                                                                    <p class="text-center remove-item-cart mb-0">
                                                                        {{-- <input type="hidden" name="stock_per_product" id="user_id" value="{{ $stock_per_product }}">
                                                                        <input type="hidden" name="user_id" id="user_id" value="{{!empty($cart['user_id']) ? $cart['user_id'] : null}}">
                                                                        <input type="hidden" name="contact_id" id="contact_id" value="{{!empty($cart['contact_id']) ? $cart['contact_id'] : null}}"> 
                                                                        <a href="{{ url('remove/' . $pk_product_id) }}" id="remove"
                                                                            class="remove-cart-page-button">Remove</a> --}}
                                                                            <!-- Hidden Form -->
                                                                        <form id="remove-form-{{ $pk_product_id }}" action="{{ url('remove/' . $pk_product_id) }}" method="get" style="display: none;">
                                                                            @csrf
                                                                            <input type="hidden" name="stock_per_product" value="{{ $stock_per_product }}">
                                                                            <input type="hidden" name="user_id" value="{{ !empty($cart['user_id']) ? $cart['user_id'] : null }}">
                                                                            <input type="hidden" name="contact_id" value="{{ !empty($cart['contact_id']) ? $cart['contact_id'] : null }}">
                                                                        </form>

                                                                        <!-- Anchor styled as button -->
                                                                        <a href="#" class="remove-cart-page-button" onclick="event.preventDefault(); document.getElementById('remove-form-{{ $pk_product_id }}').submit();">
                                                                            Remove
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot class="border-0" style="border-color: #ffff !important;">
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="w-100 d-flex mt-3">
                                                            
                                                            <div class="col-md-8 ps-0">
                                                                <div class=" align-items-center d-flex">
                                                                    <a href="{{ route('emptyCart') }}" class="clear_cart btn btn-info btn-sm" onclick="confirmEmptyCart(event)">
                                                                        Empty Cart
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 p-0 text-right">
                                                                <span class="cart-page-items text-danger">Price Subject to Change</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                @if(auth()->user())
                <div class="col-md-6 col-sm-6 col-lg-3 col-xl-3 p-0  mt-3 cart_total_div">
                    <div class="col-md-12 p-0">
                        <div class="row mb-1 align-items-center">
                            <div class="col-md-4">
                                <h5>
                                    Location(s):
                                </h5>
                            </div>
                            <div class="col-md-8 text-right">
                                <div class="dropdown">
                                    @php
                                        $session_contact_company = Session::get('company');
                                        $companies = Session::get('companies');
                                    @endphp
                                    @if(!empty($companies))
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{!empty($session_contact_company) ? \Illuminate\Support\Str::limit($session_contact_company, 20)  : 'Select Company'}}
                                        </button>
                                        <div class="dropdown-menu mx-4 pl-1" aria-labelledby="dropdownMenuButton">
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
                                                        <a class="dropdown-item {{ $disabled }} {{ $muted }}" type="button" onclick="switch_company_user({{ $contact_id }})">{{ !empty($company->company) ? $company->company : $company->firstName . ' ' . $company->lastName }}
                                                            <span
                                                                style="font-size: 9px;font-family: 'Poppins';"
                                                                class="{{ $muted }}">{{ $primary }}
                                                            </span>
                                                            
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    @elseif(!empty($session_contact_company))
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {!! \Illuminate\Support\Str::limit($session_contact_company, 20) !!}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                <div class="col-md-6 col-sm-6 col-lg-3 col-xl-3 p-0  mt-5 cart_total_div">
                    @endif
                    <div class="table-responsive" style="padding-top:3px !important;">
                        <?php
                            $tax=0;
                            $discounted_value = 0;
                            // if (!empty($tax_class)) {
                            //     $tax = $cart_total * ($tax_class->rate / 100);
                            // }

                            $total_including_tax = $tax + $cart_total;


                            if (!empty($shipping_cost)) {
                                $total_including_tax = $total_including_tax + floatval($shipping_cost);
                            }

                            // dd($total_including_tax, '1');


                            // if (!empty($discount)) {
                            //     if ($discount_type == 'percentage') {
                            //         $discounted_value = ($total_including_tax * $discount) / 100;
                            //         $total_including_tax = $total_including_tax - floatval($discounted_value);                                    
                            //     } else {
                            //         $discounted_value = floatval($discount);
                            //         $total_including_tax = $total_including_tax - floatval($discounted_value);
                            //     }
                            // }


                            if (!empty($buy_list_discount_calculated)) {
                                $total_including_tax = $total_including_tax - floatval($buy_list_discount_calculated);
                                $discounted_value = $buy_list_discount_calculated;
                            } else {
                                $discounted_value = 0;
                                $total_including_tax = $total_including_tax - floatval($discounted_value);
                            }


                            // dd($total_including_tax , '2');


                        ?>
                        {{-- @if(!empty($tax_class->rate))
                        <input type="hidden" value="{{$tax_class->rate}}" id="tax_rate_number">
                        @else
                        <input type="hidden" value="0" id="tax_rate_number">
                        @endif --}}
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th colspan="" scope="col" class="th-lg">
                                        <span class=" cart-total p-1">
                                            Cart Totals
                                        </span>
                                    </th>
                                    <th scope="col" class="th-lg">
                                        <span class="d-flex align-items-center justify-content-end">
                                            <button onclick="showZendesk()" class="bg-transparent border-0 show_zendesk_btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M4.08579 5.58579C3.71071 5.96086 3.5 6.46957 3.5 7V11C3.5 11.5304 3.71071 12.0391 4.08579 12.4142C4.46086 12.7893 4.96957 13 5.5 13H7.5V16L10.5 13H12.5C13.0304 13 13.5391 12.7893 13.9142 12.4142C14.2893 12.0391 14.5 11.5304 14.5 11V7C14.5 6.46957 14.2893 5.96086 13.9142 5.58579C13.5391 5.21071 13.0304 5 12.5 5H5.5C4.96957 5 4.46086 5.21071 4.08579 5.58579Z" fill="#A2A2A2"/>
                                                    <path d="M16.5 11V9H17.5C18.0304 9 18.5392 9.21071 18.9142 9.58579C19.2893 9.96086 19.5 10.4696 19.5 11V15C19.5 15.5304 19.2893 16.0391 18.9142 16.4142C18.5392 16.7893 18.0304 17 17.5 17H15.5V20L12.5 17H10.5C10.161 17 9.84201 16.916 9.56201 16.767L11.328 15H12.5C13.5609 15 14.5783 14.5786 15.3284 13.8284C16.0786 13.0783 16.5 12.0609 16.5 11Z" fill="#A2A2A2"/>
                                                </svg> 
                                                Feedback?
                                            </button>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-0" colspan="2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>
                                                {{-- <img class=" img-fluid" src="/theme/img/sub-totals-icon.png"> --}}
                                                <i class="fas fa-calculator"></i>
                                                <strong class=" cart-total">Subtotal</strong>
                                            </span>
                                            <span id="cart_grand_total">
                                                <strong class=" d-flex justify-content-end cart-page-items ">
                                                    ${{ number_format($cart_total, 2) }}
                                                </strong>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                {{-- buy list shipping  --}}
                                @if (!empty($shipping_cost))
                                    <tr>
                                        <td class="ps-0" colspan="2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>
                                                    {{-- <i class="fa fa-shipping-fast mx-1" style="font-size: 20x;"></i> --}}
                                                    <span class="cart-total">Shipping</span>
                                                </span>
                                                <span id="shipping_total_value">
                                                    <strong class=" d-flex justify-content-end cart-page-items ">
                                                        ${{ number_format($shipping_cost, 2) }}
                                                    </strong>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    <input type="hidden" id="buy_list_shipping_cost" value="{{ !empty($shipping_cost) ? floatval($shipping_cost) : 0 }}">

                                {{-- buylist discount  --}}
                                @if (!empty($discount))
                                    <tr>
                                        <td class="ps-0" colspan="2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>
                                                    {{-- <i class="fa fa-percent mx-1" style="font-size: 20px;"></i> --}}
                                                    <span> <strong class="cart-total" id="discountHead">Discount
                                                            {{-- @if(!empty($discount_type))
                                                                @if ($discount_type == 'percentage')
                                                                    {{ number_format($discount, 2) }}%
                                                                @else
                                                                    ${{ number_format($discount, 2) }}
                                                                @endif
                                                            @endif --}}
                                                        </strong>
                                                    </span>
                                                </span>
                                                <span id="" class="discountValue">
                                                    <strong class=" d-flex justify-content-end cart-page-items" id="discountValue">
                                                        ${{ number_format($discounted_value, 2) }}
                                                    </strong>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                <input type="hidden" id="buy_list_discount_cost" value="{{ !empty($discounted_value) ? floatval($discounted_value) : 0 }}">
                                {{-- <tr>
                                    <td class="ps-0" colspan="2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                <span> <strong class="cart-total" id="tax_rate">Tax
                                                        @if(!empty($tax_class->rate))
                                                            {{ number_format($tax_class->rate, 2) }}%
                                                        @else
                                                            {{number_format(0, 2)}}
                                                        @endif
                                                    </strong>
                                                </span>
                                            </span>
                                            <span id="" class="tax_cart">
                                                <strong class=" d-flex justify-content-end cart-page-items" id="tax_amount">
                                                    ${{ number_format($tax, 2) }}
                                                </strong>
                                            </span>
                                        </div>
                                        @if(!empty($tax_class->name))
                                            <div class="mx-2"><span><strong class="cart-total mx-3 px-3" >{{'Tax Class:' . $tax_class->name}}</strong></span></div>
                                        @endif
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td class="ps-0" colspan="2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>
                                                {{-- <img class="img-fluid subtotal-img-cart-page" src="/theme/img/total-icon.png"> --}}
                                                <i class="fa fa-tag"></i>
                                                <strong class="cart-total">SubTotal</strong>
                                            </span>
                                            <span id="cart_grand_total" class="grandTotal">
                                                <strong class=" d-flex justify-content-end cart-page-items text-danger g_total">
                                                    ${{ number_format($total_including_tax, 2) }}
                                                </strong>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="tax-calculater">
                                                @if (auth()->user())
                                                    @if(!empty($parent_contact) && strtolower($parent_contact->paymentTerms) != 'pay in advanced')
                                                        (Your account has terms, No payment will be collected at checkout)
                                                    @else
                                                        (Pay in Advanced)
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            @if (!empty($new_checkout_flow) && strtolower($new_checkout_flow->option_value) == 'yes')
                                @if (auth()->user())
                                    @if ($contact->status == 1 && !empty($contact->contact_id))
                                        <a href="{{ url('/checkout') }}">
                                            <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                                PROCEED TO CHECKOUT
                                            </button>
                                        </a>
                                    @elseif (!empty($contact) && $contact->status == 0)
                                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                            <span class="d-flex justify-content-center align-items-center">
                                                Checkout has been disabled for this email address, please contact your
                                                account
                                                manager to re-enable checkout.
                                            </span>
                                        </div>
                                    @elseif (empty($contact->contact_id))
                                        <a href="{{ url('/checkout/') }}">
                                            <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                                PROCEED TO CHECKOUT
                                            </button>
                                        </a>
                                    @endif
                                @else
                                    @if ($contact->status == 1 && !empty($contact->contact_id))
                                        <a href="{{ url('/checkout') }}">
                                            <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                                PROCEED TO CHECKOUT
                                            </button>
                                        </a>
                                    @elseif (!empty($contact) && $contact->status == 0)
                                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                            <span class="d-flex justify-content-center align-items-center">
                                                Checkout has been disabled for this email address, please contact your
                                                account
                                                manager to re-enable checkout.
                                            </span>
                                        </div>
                                    @elseif (empty($contact->contact_id))
                                        <a href="{{ url('/checkout/') }}">
                                            <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                                PROCEED TO CHECKOUT
                                            </button>
                                        </a>
                                    @endif
                                @endif
                            @else
                                @if (Auth::check() == true && $contact->status == 1 && !empty($contact->contact_id))
                                    <a href="{{ url('/checkout') }}">
                                        <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                            PROCEED TO CHECKOUT
                                        </button>
                                    </a>
                                @elseif (Auth::check() == true && $contact->status == 0)
                                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                        <span class="d-flex justify-content-center align-items-center">
                                            Checkout has been disabled for this email address, please contact your
                                            account
                                            manager to re-enable checkout.
                                        </span>
                                    </div>
                                @elseif(Auth::check() == true && empty($contact->contact_id))
                                    <a href="{{ url('/checkout/') }}">
                                        <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                            PROCEED TO CHECKOUT
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ url('/user/') }}">
                                        <button class="procedd-to-checkout mt-3 w-100 mb-4">
                                            Login or Register
                                        </button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<!--Mobile View -->
<!-- MultiStep Form  -->
<div class="container-fluid mobile-view">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0">
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {!! \Session::get('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {!! \Session::get('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card border-0 px-0">
                <div class="row">
                    <div class="col-md-12 mx-0">
                        @if (Auth::check() == true && $contact->status == 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Checkout has been disabled.
                            </div>
                        @endif
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <div id="">
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
                                            @if(auth()->user())
                                            <div class="d-flex">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <h6 class="text-dark">
                                                        Location(s):
                                                    </h6>
                                                </div>
                                                <div class="dropdown mb-1 col-md-8 p-0 text-right">
                                                    @php
                                                        $session_contact_company = Session::get('company');
                                                        $companies = Session::get('companies');
                                                    @endphp
                                                    @if(!empty($companies))
                                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{!empty($session_contact_company) ? \Illuminate\Support\Str::limit($session_contact_company, 16) : 'Select Company'}}
                                                        </button>
                                                        <div class="dropdown-menu mb_item_mnu" aria-labelledby="dropdownMenuButton">
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
                                                                        <a class="mb_item {{ $disabled }} {{ $muted }}" type="button" onclick="switch_company_user({{ $contact_id }})">
                                                                            {{ !empty($company->company) ? $company->company : $company->firstName . ' ' . $company->lastName }}
                                                                            <span
                                                                                style="font-size: 9px;font-family: 'Poppins';"
                                                                                class="{{ $muted }}">{{ $primary }}
                                                                            </span>
                                                                            
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @elseif(!empty($session_contact_company))
                                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{\Illuminate\Support\Str::limit($session_contact_company, 16)}}
                                                      </button>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                            <div class="p-3 mb-3" style="border: 0.793792px solid #DFDFDF;box-shadow: 0px 3.17517px 3.17517px rgba(231, 231, 231, 0.25);">
                                                <div class="card-header bg-white p-0 border-0 d-flex align-items-center justify-content-between">
                                                    <p class="your-cart-title ps-1 mb-0"> Your Cart</p>
                                                    <p class="cart-page-items text-danger text-right mb-0"> Price Subject to Change</p>
                                                </div>
                                                <div class="text-md-right text-left my-1">
                                                    <a href="{{ route('emptyCart') }}" class="clear_cart btn btn-info btn-sm" onclick="confirmEmptyCart(event)">
                                                        Empty Cart
                                                    </a>
                                                </div>
                                                {{-- <div class="col-md-12 p-0 pt-3" style="border-top: 1.5px solid #EBEBEB;"> --}}
                                                <div class="col-md-12 p-0">
                                                    <table class="table cart_table_mobile mb-0">
                                                        <thead>
                                                        </thead>
                                                        <tbody style="border-top: none !important">
                                                            {{-- @if (Session::get('cart'))
            
                                                            @foreach (Session::get('cart') as $pk_product_id => $cart) --}}
                                                            @if (count($cart_items) > 0)
                                                            @foreach ($cart_items as $pk_product_id => $cart)
                                                            @php
                                                                $stock_per_product_mbl = 0;
                                                                $stock_per_product_mbl = App\Helpers\UserHelper::get_stock_per_product_option($pk_product_id, $cart['option_id']);
                                                                if (!auth()->user()) {
                                                                    $pk_product_id = $pk_product_id;
                                                                } else {
                                                                    if (!empty(Session::get('contact_id'))) {
                                                                        $pk_product_id = $pk_product_id;
                                                                    } else {
                                                                        $pk_product_id = $cart['qoute_id'];
                                                                    }
                                                                }
                                                            @endphp
                                                                    <tr>
                                                                        <td colspan="3" class="" style="vertical-align: center !important;">
                                                                            <div class="row" style="border-top: 1px solid #EBEBEB;"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="p-1" style="vertical-align: middle;width:20%;background-color:#F7F7F7;">
                                                                            @if (!empty($cart['image']))
                                                                                <img src="{{ $cart['image'] }}"
                                                                                    class="img-fluid rounded-3 "
                                                                                    alt="Book" style="width:100px;height:50px;">
                                                                            @else
                                                                                <img src="/theme/img/image_not_available.png"
                                                                                    class="img-fluid rounded-3 "
                                                                                    alt="Book" style="width:100px;height:50px;">
                                                                            @endif
                                                                        </td>
                                                                        <td class="p-1 pl-3" style="width:80%;">
                                                                            <table style="width: 100%">
                                                                                <tr>
                                                                                    <td class="p-0" style="width:80%;">
                                                                                        <div class="">
                                                                                            <a class="cart-page-items"
                                                                                                href="{{ url('product-detail/' . $pk_product_id . '/' . $cart['option_id'] . '/' . $cart['slug']) }}">{{ $cart['name'] }}
                                                                                            </a>
                                                                                        </div>
                                                                                        @if (intval($cart['quantity']) > intval($stock_per_product_mbl))
                                                                                            @if (empty($get_wholesale_terms) || strtolower($get_wholesale_terms) != 'pay in advanced' && auth()->user())
                                                                                                <div class="d-flex mt-2 stock_tooltip" id="badge_{{ $pk_product_id }}">
                                                                                                    <div class="stock_tooltip_message">
                                                                                                        {{ App\Helpers\SettingHelper::getSetting('stock_tooltip_message') }}
                                                                                                    </div>
                                                                                                    <span class="badge badge-warning">
                                                                                                        On Back Order 
                                                                                                    </span>
                                                                                                    <span class="ms-2 show_message">
                                                                                                        <i class="fas fa-question-circle"></i>
                                                                                                    </span>
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="d-flex">
                                                                                                    <span class="badge badge-danger">
                                                                                                        {{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');}}
                                                                                                    </span>
                                                                                                </div>
                                                                                            @endif

                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="p-0 text-right"  style="width:20%;" align="right">
                                                                                        <div class="cart-page-price ps-3"
                                                                                            id="m_p_price_{{ $pk_product_id }}">
                                                                                            ${{ number_format($cart['price'], 2) }}
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="p-0 pt-3" style="width:80%;">
                                                                                        <div class="d-flex">
                                                                                            <button class="btn p-1 text-center mb_plusqty" style="border: 0.485995px solid #EBEBEB; border-radius:0px;" onclick="minusq('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')">
                                                                                                <i class="fa fa-angle-left text-dark align-middle" style="font-size: 8px;"></i>
                                                                                            </button>
                                                                                            <div class="">
                                                                                                <input type="number" class="py-1 text-center mb-0 qtyMob_input bg-white"  min="0" class="itm_qty" max="{{$stock_per_product_mbl}}" step="1" data-old="{{ $cart['quantity'] }}" id="itm_qty{{ $pk_product_id }}"
                                                                                                data-type="{{ $pk_product_id }}" onchange="update_cart_products_mbl('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')" value="{{ $cart['quantity'] }}" style="width: 31px;font-weight: 600;font-size: 10px !important;
                                                                                                color: #7CC633;background-color: #ffffff !important;border-top:0.485995px solid #EBEBEB !important;border-bottom:0.485995px solid #EBEBEB !important;line-height: 15px !important;border-left:0px !important;border-right:0px !important;">
                                                                                            </div>
                                                                                            <button class="btn p-1 text-center mb_minusqty" style="border: 0.485995px solid #EBEBEB; border-radius:0px;" onclick="plusq('{{ $pk_product_id }}' , '{{$get_wholesale_terms}}')">
                                                                                                <i class="fa fa-angle-right text-dark align-middle" style="font-size: 8px;"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </td> 
                                                                                    <td class="p-0 pt-3" style="width:20%;vertical-align:bottom;" align="right">
                                                                                        <div class="d-flex justify-content-end">
                                                                                            <input type="hidden" name="user_id" id="user_id" value="{{!empty($cart['user_id']) ? $cart['user_id'] : null}}">
                                                                                            <input type="hidden" name="contact_id" id="contact_id" value="{{!empty($cart['contact_id']) ? $cart['contact_id'] : null}}"> 
                                                                                            <a href="{{ url('remove/' . $pk_product_id) }}" id="remove" class="remove_p_mbl">
                                                                                                <span>
                                                                                                    Remove
                                                                                                </span>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="w-100 p-3" style="border: 0.793792px solid #DFDFDF;box-shadow: 0px 3.17517px 3.17517px rgba(231, 231, 231, 0.25);">
                                                <div class="w-100 mb-3 pb-3 d-flex" style="border-bottom:1px solid #dee2e6;">
                                                    <span class="mb-2 cart-total-checkout-page text-dark mb-2 w-50 text-left ps-1">
                                                        Cart totals
                                                    </span>
                                                    <span class="ml-2 cart-sub-total-checkout-page text-dark mt-0 w-50 text-right">
                                                        <button onclick="showZendesk()" class="bg-transparent border-0 show_zendesk_btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path d="M4.08579 5.58579C3.71071 5.96086 3.5 6.46957 3.5 7V11C3.5 11.5304 3.71071 12.0391 4.08579 12.4142C4.46086 12.7893 4.96957 13 5.5 13H7.5V16L10.5 13H12.5C13.0304 13 13.5391 12.7893 13.9142 12.4142C14.2893 12.0391 14.5 11.5304 14.5 11V7C14.5 6.46957 14.2893 5.96086 13.9142 5.58579C13.5391 5.21071 13.0304 5 12.5 5H5.5C4.96957 5 4.46086 5.21071 4.08579 5.58579Z" fill="#A2A2A2"/>
                                                                <path d="M16.5 11V9H17.5C18.0304 9 18.5392 9.21071 18.9142 9.58579C19.2893 9.96086 19.5 10.4696 19.5 11V15C19.5 15.5304 19.2893 16.0391 18.9142 16.4142C18.5392 16.7893 18.0304 17 17.5 17H15.5V20L12.5 17H10.5C10.161 17 9.84201 16.916 9.56201 16.767L11.328 15H12.5C13.5609 15 14.5783 14.5786 15.3284 13.8284C16.0786 13.0783 16.5 12.0609 16.5 11Z" fill="#A2A2A2"/>
                                                            </svg> 
                                                            Feedback?
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="d-flex pb-3 mb-3" style="border-bottom:1px solid #dee2e6;">
                                                    <div class="w-50 d-flex align-items-center text-left">
                                                        <span class="">
                                                            {{-- <img src="/theme/img/sub-totals-icon.png" width="25px" height="30"> --}}
                                                            <i class="fas fa-calculator"></i>
                                                        </span>
                                                        <span class="ml-2 cart-sub-total-checkout-page text-dark mt-0">
                                                            Sub Total
                                                        </span>
                                                    </div>
                                                    <div class="w-50 d-flex align-items-center justify-content-end">
                                                        <p
                                                            class="sub-total-checkout-page mbl_cart_subtotal mt-0 mb-0 text-right text-dark">
                                                            ${{ number_format($cart_total, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex pb-3 mb-3" style="border-bottom:1px solid #dee2e6;">
                                                    <div class="w-50 d-flex align-items-center">
                                                        <span>
                                                            {{-- <img src="/theme/img/text-rate-icon.png" alt=""  width="25px" height="30"> --}}
                                                            <i class="fas fa-file-invoice-dollar"></i>
                                                        </span>
                                                        <span id="mbl_tax_rate" class="ml-2 cart-sub-total-checkout-page text-dark">Rate
                                                            @if(!empty($tax_class->rate))
                                                {{ number_format($tax_class->rate, 2) }}%
                                            @else
                                                {{number_format(0, 2)}}%
                                            @endif</span>
                                                    </div>
                                                    <div class="w-50 d-flex align-items-center justify-content-end">
                                                        <p class="sub-total-checkout-page mbl_cart_subtotal mt-0 mb-0 text-right text-dark" id="mbl_tax_price">@if(!empty($tax_class->rate))
                                                
                                                ${{ number_format($tax, 2) }}
                                            @else
                                                ${{number_format(0, 2)}}
                                            @endif</p>
                                                    </div>
                                                    
                                                </div>
                                                @if(!empty($tax_class->name))
                                                <div class="d-flex pb-3 mb-3" style="border-bottom:1px solid #dee2e6;">
                                                    
                                                    <div class="w-100 d-flex align-items-center "><p class="sub-total-checkout-page  mt-0 mb-0 ml-0 text-dark ps-1">{{'Tax Class :' . $tax_class->name}}</p></div>
                                                    
                                                </div> 
                                                @endif
                                                <div class="d-flex pb-3 mb-3" style="border-bottom:1px solid #dee2e6;">
                                                    <div class="w-50 d-flex align-items-center">
                                                        <span>
                                                            {{-- <img src="/theme/img/pricing_tag.png" alt=""  width="25px" height="30"> --}}
                                                            <i class="fas fa-tags"></i>
                                                        </span>
                                                        <span class="ml-2 cart-total-checkout-page text-dark">Total</span>
                                                    </div>
                                                    <div class="w-50 d-flex align-items-center justify-content-end">
                                                        <p id="mbl_total_p" class="cart-total-checkout-page mt-0 mb-0 text-right" style="color:#E74B3B;">${{ number_format($total_including_tax, 2) }}</p>
                                                    </div>
                                                </div>
                                                @if (!empty($new_checkout_flow) && strtolower($new_checkout_flow->option_value) == 'yes')
                                                    @if (auth()->user())
                                                        @if ($contact->status == 1 && !empty($contact->contact_id))
                                                            <div class="row">
                                                                <a href="{{ url('/checkout') }}">
                                                                    <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                        PROCEED TO CHECKOUT
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        @elseif (!empty($contact) && $contact->status == 0)
                                                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                                                <span class="d-flex justify-content-center align-items-center">
                                                                    Checkout has been disabled for this email address, please contact your
                                                                    account
                                                                    manager to re-enable checkout.
                                                                </span>
                                                            </div>
                                                        @elseif (empty($contact->contact_id))
                                                            <div class="row">
                                                                <a href="{{ url('/checkout') }}">
                                                                    <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                        PROCEED TO CHECKOUT
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if ($contact->status == 1 && !empty($contact->contact_id))
                                                            <div class="row">
                                                                <a href="{{ url('/checkout') }}">
                                                                    <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                        PROCEED TO CHECKOUT
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        @elseif (!empty($contact) && $contact->status == 0)
                                                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                                                <span class="d-flex justify-content-center align-items-center">
                                                                    Checkout has been disabled for this email address, please contact your
                                                                    account
                                                                    manager to re-enable checkout.
                                                                </span>
                                                            </div>
                                                        @elseif (empty($contact->contact_id))
                                                            <div class="row">
                                                                <a href="{{ url('/checkout') }}">
                                                                    <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                        PROCEED TO CHECKOUT
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (Auth::check() == true && $contact->status == 1 && !empty($contact->contact_id))
                                                        <div class="row">
                                                            <a href="{{ url('/checkout') }}">
                                                                <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                    PROCEED TO CHECKOUT
                                                                </button>
                                                            </a>
                                                        </div>
                                                    @elseif (Auth::check() == true && $contact->status == 0)
                                                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                                            <span class="d-flex justify-content-center align-items-center">
                                                                Checkout has been disabled for this email address, please contact your
                                                                account
                                                                manager to re-enable checkout.
                                                            </span>
                                                        </div>
                                                    @elseif(Auth::check() == true && empty($contact->contact_id))
                                                        <div class="row">
                                                            <a href="{{ url('/checkout/') }}">
                                                                <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                    PROCEED TO CHECKOUT
                                                                </button>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <a href="{{ url('/user/') }}">
                                                                <button id="mbl_chk_btn" class="procedd-to-checkout mt-4  w-100">
                                                                    Login or Register
                                                                </button>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </fieldset>
                            {{-- <fieldset>
                                <div class="form-card">

                                </div>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                                <input type="button" name="make_payment" class="next action-button"
                                    value="Confirm" />
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
                                            <h5>You Have Successfully Signed Up</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset> --}}
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
                <button type="button" class="btn btn-primary w-75 applay-coupon-code-modal-btn">apply
                    coupon</button>
            </div>
        </div>
    </div>
</div>
@include('partials.footer')
<div class="desktop-view">
    @include('partials.product-footer')
</div>
<script>
    function plusq(pk_product_id , get_wholesale_terms) {
        var result = 0;
        var new_result = 0;
        var old_qty = parseInt($('#itm_qty' + pk_product_id).attr('data-old'));
        var stock_available = parseInt($('#itm_qty' + pk_product_id).attr('max'));
        var plus = parseInt($('#itm_qty' + pk_product_id).val());
        var result = plus + 1;
        var new_result = result > stock_available ? old_qty : result;
        var new_qty = $('#itm_qty' + pk_product_id).val(new_result);
        increase_qty(pk_product_id , get_wholesale_terms)
    }

    function minusq(pk_product_id ,get_wholesale_terms) {
        var result = 0;
        var new_result = 0;
        var old_qty = parseInt($('#itm_qty' + pk_product_id).attr('data-old'));
        var stock_available = parseInt($('#itm_qty' + pk_product_id).attr('max'));
        var minus = parseInt($('#itm_qty' + pk_product_id).val());
        if (minus > 0) {
            var result = minus - 1;
            var new_result = result > stock_available ? old_qty : result;
            var new_qty = $('#itm_qty' + pk_product_id).val(new_result);
            decrease_qty(pk_product_id ,get_wholesale_terms);
        }

    }

    

    /* *****   INCREASE QTY   ****  */
    function increase_qty(pk_product_id , get_wholesale_terms) { 
        var old_qty = parseInt($('#row_quantity_' + pk_product_id).attr('data-old'));
        var stock_available = parseInt($('#row_quantity_' + pk_product_id).attr('max'));
        var qty_input = parseInt($('#row_quantity_' + pk_product_id).val());
        var product_id = pk_product_id;
        var new_qty = parseInt(qty_input + 1);
        if ((new_qty > stock_available) && ((get_wholesale_terms || "").toLowerCase() == 'pay in advanced' && get_wholesale_terms.trim() !== '')) {
        // if (new_qty > stock_available) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
                timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });
            // $('#row_quantity_' + pk_product_id).val(old_qty);
            return false;
        }
        var new_qty_value = $('#row_quantity_' + pk_product_id).val(new_qty);
        

        jQuery.ajax({
            url: "{{ url('update-cart') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "items_quantity": new_qty,
                "product_id": product_id
            },
            success: function(response) {
                var row_price = response.cart_items[product_id].price;
                var new_quantity = response.cart_items[product_id].quantity;
                var new_row_price = parseFloat(row_price) * parseInt(new_quantity);
                new_row_price = parseFloat(new_row_price).toFixed(2);
                $('#row_quantity_' + product_id).val(new_quantity);
                $('#subtotal_' + product_id).html('$' + new_row_price);

                $('#itm_qty' + product_id).val(new_quantity);
                $('#itm_qty_ipad' + product_id).val(new_quantity);

                var grand_total = 0;
                var total_cart_quantity = 0;
                var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
                Object.keys(response.cart_items).forEach(function(key) {
                    row_total = parseFloat(response.cart_items[key].price) * response.cart_items[
                        key].quantity;
                    grand_total += parseFloat(row_total);
                    total_cart_quantity += parseInt(response.cart_items[key].quantity);

                });
                
                $('#cart_grand_total').children().html('$' + grand_total.toFixed(2));
                $('#topbar_cart_total').html('$' + grand_total.toFixed(2));
                $('#top_cart_quantity').html(total_cart_quantity);
                $('.topbar_cart_total_ipad').html('$' + grand_total.toFixed(2));


                $('.cartQtymbl').html(total_cart_quantity);
                $('.mbl_cart_subtotal').html('$' + grand_total.toFixed(2));

                $('.cartQtyipad').html(total_cart_quantity);
                $('.ipad_cart_subtotal').html('$' + grand_total.toFixed(2));



                var tax = 0;
                var tax_rate = parseFloat($('#tax_rate_number').val());
                var tax = grand_total.toFixed(2) * (tax_rate / 100);
                var grand_total_include_tax = 0;
                $('.tax_cart').children().html('$' + tax.toFixed(2));
                var buy_list_shipping = $('#buy_list_shipping_cost').val() != '' ?  parseFloat($('#buy_list_shipping_cost').val()) : 0;
                var buy_list_discount = $('#buy_list_discount_cost').val() != '' ?  parseFloat($('#buy_list_discount_cost').val()) : 0;
                $('.grandTotal').children().html('$' + (tax + grand_total + buy_list_shipping - buy_list_discount).toFixed(2));
                grand_total_include_tax = tax + grand_total + buy_list_shipping - buy_list_discount;
                if (response.free_postal_state == true) {
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                }
                else {
                    $('.promotional_banner_div').addClass('d-none');
                    $('.promotional_banner_div_congrats').addClass('d-none');
                } 

                $('#mbl_tax_price').html('$' + tax.toFixed(2));
                $('#mbl_total_p').html('$' + (tax + grand_total + buy_list_shipping - buy_list_discount).toFixed(2));

                $('#ipad_tax_price').html('$' + tax.toFixed(2));
                $('#ipad_total_p').html('$' + (tax + grand_total + buy_list_shipping - buy_list_discount).toFixed(2));
            }
        });
    }

    /* *****   DECREASE QTY   ****  */
    function decrease_qty(pk_product_id ,get_wholesale_terms) {
        var buy_list_id = $('#buy_list_id').val() != '' ? $('#buy_list_id').val() : null;
        var contact_us_url = "{{ url('contact-us') }}";

        if (buy_list_id != null) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You cannot decrease the quantity of a product in the buy list. Please contact admin at <a href="' + contact_us_url + '" target="_blank">Contact Us</a>',
                // timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: false,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });

            return false;
        }

        var pass_original_items_quantity = $('#pass_original_items_quantity').val();
        var product_id = pk_product_id;
        var old_qty = parseInt($('#row_quantity_' + pk_product_id).attr('data-old'));
        var stock_available = parseInt($('#row_quantity_' + pk_product_id).attr('max'));
        var qty_input = parseFloat($('#row_quantity_' + pk_product_id).val());
        if (qty_input > 1) {
            var new_qty = parseFloat(qty_input - 1);
            // if (new_qty > stock_available) {
            //     Swal.fire({
            //         toast: true,
            //         icon: 'error',
            //         title: 'Maximum stock limit reached',
            //         timer: 3000,
            //         position: 'top',
            //         showConfirmButton: true,  // Show the confirm (OK) button
            //         confirmButtonText: 'Okay',
            //         timerProgressBar: true,
            //         customClass: {
            //             confirmButton: 'my-confirm-button',  // Class for the confirm button
            //             actions: 'my-actions-class'  // Class for the actions container
            //         }
            //     });
            //     $('#row_quantity_' + pk_product_id).val(old_qty);
            //     return false;
            // }
            var new_qty_value = $('#row_quantity_' + pk_product_id).val(new_qty);
            var session_quantity = $('#pass_original_items_quantity_' + pk_product_id).find('#pass_original_items_quantity_child_' + pk_product_id).html(new_qty);
        } else {
            var new_qty = parseFloat(1);
            var new_qty_value = $('#row_quantity_' + pk_product_id).val(1);
        }
        
        jQuery.ajax({
            url: "{{ url('update-cart') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "items_quantity": new_qty,
                "product_id": product_id,
                'pass_original_items_quantity' : pass_original_items_quantity
            },
            success: function(response) {
                var row_price = response.cart_items[product_id].price;
                var new_quantity = response.cart_items[product_id].quantity;
                var new_row_price = parseFloat(row_price) * parseInt(new_quantity);
                new_row_price = parseFloat(new_row_price).toFixed(2);
                $('#row_quantity_' + product_id).val(new_quantity);
                $('#subtotal_' + product_id).html('$' + new_row_price);

                $('#itm_qty' + product_id).val(new_quantity);
                $('#itm_qty_ipad' + product_id).val(new_quantity);

                var grand_total = 0;
                var total_cart_quantity = 0;
                var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
                Object.keys(response.cart_items).forEach(function(key) {
                    row_total = parseFloat(response.cart_items[key].price) * response.cart_items[
                        key].quantity;
                    grand_total += parseFloat(row_total);
                    total_cart_quantity += parseInt(response.cart_items[key].quantity);

                });
                $('#cart_grand_total').children().html('$' + grand_total.toFixed(2));
                $('#topbar_cart_total').html('$' + grand_total.toFixed(2));
                $('#top_cart_quantity').html(total_cart_quantity);
                $('.topbar_cart_total_ipad').html('$' + grand_total.toFixed(2));

                $('.cartQtymbl').html(total_cart_quantity);
                $('.mbl_cart_subtotal').html('$' + grand_total.toFixed(2));

                $('.cartQtyipad').html(total_cart_quantity);
                $('.ipad_cart_subtotal').html('$' + grand_total.toFixed(2));

                var tax = 0;
                var grand_total_include_tax = 0;
                var tax_rate = parseFloat($('#tax_rate_number').val());
                var tax = grand_total.toFixed(2) * (tax_rate / 100);
                $('.tax_cart').children().html('$' + tax.toFixed(2));

                var buy_list_shipping = $('#buy_list_shipping_cost').val() != '' ?  parseFloat($('#buy_list_shipping_cost').val()) : 0;
                var buy_list_discount = $('#buy_list_discount_cost').val() != '' ?  parseFloat($('#buy_list_discount_cost').val()) : 0;
                grand_total_include_tax = tax + grand_total + buy_list_shipping - buy_list_discount;
                $('.grandTotal').children().html('$' + (tax + grand_total + buy_list_shipping - buy_list_discount).toFixed(2));


                if (response.free_postal_state == true) {
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                }
                else {
                    $('.promotional_banner_div').addClass('d-none');
                    $('.promotional_banner_div_congrats').addClass('d-none');
                } 

                $('#mbl_tax_price').html('$' + tax.toFixed(2));
                $('#mbl_total_p').html('$' + (grand_total_include_tax).toFixed(2));

                $('#ipad_tax_price').html('$' + tax.toFixed(2));
                $('#ipad_total_p').html('$' + (grand_total_include_tax).toFixed(2));
            }
        });
    }

    // on change update cart
    function update_cart_products(pk_product_id ,get_wholesale_terms) {
        var pass_original_items_quantity = $('#pass_original_items_quantity').val();
        var product_id = pk_product_id;
        var qty_input = 0;
        var old_qty = parseInt($('#row_quantity_' + pk_product_id).attr('data-old'));
        var new_qty_input = $('#row_quantity_' + pk_product_id).val() != '' ? parseInt($('#row_quantity_' + pk_product_id).val()) : '';
        var buy_list_id = $('#buy_list_id').val() != '' ? $('#buy_list_id').val() : null;
        var contact_us_url = "{{ url('contact-us') }}";

        if ((new_qty_input != '' || new_qty_input < old_qty) && (buy_list_id != '' && buy_list_id != null)) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You cannot decrease the quantity of a product in the buy list. Please contact admin at <a href="' + contact_us_url + '" target="_blank">Contact Us</a>',
                // timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: false,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });

            $('#row_quantity_' + product_id).val(old_qty);
            return false;
        }

        if (($('#row_quantity_' + pk_product_id).val() != '') && $('#row_quantity_' + pk_product_id).val()) {
            qty_input = parseInt($('#row_quantity_' + pk_product_id).val());
        } else {
            qty_input = 1;
        }
        var stock_available = parseInt($('#row_quantity_' + pk_product_id).attr('max'));
        
        // if (qty_input > stock_available) {
        if ((qty_input > stock_available) && ((get_wholesale_terms || "").toLowerCase() == 'pay in advanced' && get_wholesale_terms.trim() !== '')) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
                timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });
            $('#row_quantity_' + pk_product_id).val(old_qty);
            $('#pass_original_items_quantity_' + pk_product_id).find('#pass_original_items_quantity_child_' + pk_product_id).html(old_qty);
            return false;
        } 
        $('#row_quantity_' + pk_product_id).val(qty_input)
        $('#pass_original_items_quantity_' + pk_product_id).find('#pass_original_items_quantity_child_' + pk_product_id).html(qty_input);
        jQuery.ajax({
            url: "{{ url('update-cart') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "items_quantity": qty_input,
                "product_id": product_id,
                'pass_original_items_quantity' : pass_original_items_quantity
            },
            success: function(response) {
                var row_price = response.cart_items[product_id].price;
                var new_quantity = response.cart_items[product_id].quantity;
                var new_row_price = parseFloat(row_price) * parseInt(new_quantity);
                new_row_price = parseFloat(new_row_price).toFixed(2);
                $('#row_quantity_' + product_id).val(new_quantity);
                $('#subtotal_' + product_id).html('$' + new_row_price);

                $('#itm_qty' + product_id).val(new_quantity);
                $('#itm_qty_ipad' + product_id).val(new_quantity);

                var grand_total = 0;
                var total_cart_quantity = 0;
                var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
                Object.keys(response.cart_items).forEach(function(key) {
                    row_total = parseFloat(response.cart_items[key].price) * response.cart_items[
                        key].quantity;
                    grand_total += parseFloat(row_total);
                    total_cart_quantity += parseInt(response.cart_items[key].quantity);

                });
                $('#cart_grand_total').children().html('$' + grand_total.toFixed(2));
                $('#topbar_cart_total').html('$' + grand_total.toFixed(2));
                $('#top_cart_quantity').html(total_cart_quantity);
                $('.topbar_cart_total_ipad').html('$' + grand_total.toFixed(2));


                $('.cartQtymbl').html(total_cart_quantity);
                $('.mbl_cart_subtotal').html('$' + grand_total.toFixed(2));

                $('.cartQtyipad').html(total_cart_quantity);
                $('.ipad_cart_subtotal').html('$' + grand_total.toFixed(2));



                var tax = 0;
                var tax_rate = parseFloat($('#tax_rate_number').val());
                var tax = grand_total.toFixed(2) * (tax_rate / 100);
                var grand_total_include_tax = 0;
                var buy_list_shipping = $('#buy_list_shipping_cost').val() != '' ?  parseFloat($('#buy_list_shipping_cost').val()) : 0;
                var buy_list_discount = $('#buy_list_discount_cost').val() != '' ?  parseFloat($('#buy_list_discount_cost').val()) : 0;
                grand_total_include_tax = tax + grand_total + buy_list_shipping - buy_list_discount;
                $('.tax_cart').children().html('$' + tax.toFixed(2));
                $('.grandTotal').children().html('$' + (grand_total_include_tax).toFixed(2));

                if (response.free_postal_state == true) {
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                }
                else {
                    $('.promotional_banner_div').addClass('d-none');
                    $('.promotional_banner_div_congrats').addClass('d-none');
                } 


                $('#mbl_tax_price').html('$' + tax.toFixed(2));
                $('#mbl_total_p').html('$' + (grand_total_include_tax).toFixed(2));

                $('#ipad_tax_price').html('$' + tax.toFixed(2));
                $('#ipad_total_p').html('$' + (grand_total_include_tax).toFixed(2));
            }
        });
    }
    

    function update_cart_products_mbl(pk_product_id , get_wholesale_terms) {
        var pass_original_items_quantity = $('#pass_original_items_quantity').val();
        var product_id = pk_product_id;
        var qty_input = 0;
        var old_qty = parseInt($('#itm_qty' + pk_product_id).attr('data-old'));
        var stock_available = parseInt($('#itm_qty' + pk_product_id).attr('max'));
        var qty_input = parseInt($('#itm_qty' + product_id).val());
        var new_qty_input = $('#itm_qty' + pk_product_id).val() != '' ? parseInt($('#itm_qty' + pk_product_id).val()) : '';
        var buy_list_id = $('#buy_list_id').val() != '' ? $('#buy_list_id').val() : null;
        var contact_us_url = "{{ url('contact-us') }}";

        if ((new_qty_input != '' || new_qty_input < old_qty) && (buy_list_id != '' && buy_list_id != null)) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You cannot decrease the quantity of a product in the buy list. Please contact admin at <a href="' + contact_us_url + '" target="_blank">Contact Us</a>',
                // timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: false,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });


            $('#itm_qty' + product_id).val(old_qty);

            return false;
        }

        if (($('#itm_qty' + product_id).val() != '') && $('#itm_qty' + product_id).val()) {
            qty_input = parseInt($('#itm_qty' + product_id).val());
        } else {
            qty_input = 0;
        }
        
        // if (qty_input > stock_available) {
        if ((qty_input > stock_available) && ((get_wholesale_terms || "").toLowerCase() == 'pay in advanced' && get_wholesale_terms.trim() !== '')) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
                timer: 3000,
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });
            $('#itm_qty' + product_id).val(old_qty);
            $('#pass_original_items_quantity_' + pk_product_id).find('#pass_original_items_quantity_child_' + pk_product_id).html(old_qty);
            return false;
        }
        $('#itm_qty' + product_id).val(qty_input);
        var new_qty = $('#itm_qty' + product_id).val();
        $('#pass_original_items_quantity_' + pk_product_id).find('#pass_original_items_quantity_child_' + pk_product_id).html(qty_input);

        jQuery.ajax({
            url: "{{ url('update-cart') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "items_quantity": new_qty,
                "product_id": product_id,
                'pass_original_items_quantity' : pass_original_items_quantity
            },
            success: function(response) {
                var row_price = response.cart_items[product_id].price;
                var new_quantity = response.cart_items[product_id].quantity;
                var new_row_price = parseFloat(row_price) * parseInt(new_quantity);
                new_row_price = parseFloat(new_row_price).toFixed(2);
                $('#row_quantity_' + product_id).val(new_quantity);
                $('#subtotal_' + product_id).html('$' + new_row_price);

                $('#itm_qty' + product_id).val(new_quantity);
                $('#itm_qty_ipad' + product_id).val(new_quantity);

                var grand_total = 0;
                var total_cart_quantity = 0;
                Object.keys(response.cart_items).forEach(function(key) {
                    row_total = parseFloat(response.cart_items[key].price) * response.cart_items[
                        key].quantity;
                    grand_total += parseFloat(row_total);
                    total_cart_quantity += parseInt(response.cart_items[key].quantity);

                });
                $('#cart_grand_total').children().html('$' + grand_total.toFixed(2));
                $('#topbar_cart_total').html('$' + grand_total.toFixed(2));
                $('#top_cart_quantity').html(total_cart_quantity);
                $('.topbar_cart_total_ipad').html('$' + grand_total.toFixed(2));


                $('.cartQtymbl').html(total_cart_quantity);
                $('.mbl_cart_subtotal').html('$' + grand_total.toFixed(2));

                $('.cartQtyipad').html(total_cart_quantity);
                $('.ipad_cart_subtotal').html('$' + grand_total.toFixed(2));



                var tax = 0;
                var grand_total_include_tax = 0;
                var tax_rate = parseFloat($('#tax_rate_number').val());
                var tax = grand_total.toFixed(2) * (tax_rate / 100);
                var buy_list_shipping = $('#buy_list_shipping_cost').val() != '' ?  parseFloat($('#buy_list_shipping_cost').val()) : 0;
                var buy_list_discount = $('#buy_list_discount_cost').val() != '' ?  parseFloat($('#buy_list_discount_cost').val()) : 0;

                grand_total_include_tax = tax + grand_total + buy_list_shipping - buy_list_discount;

                $('.tax_cart').children().html('$' + tax.toFixed(2));
                $('.grandTotal').children().html('$' + (grand_total_include_tax).toFixed(2));


                $('#mbl_tax_price').html('$' + tax.toFixed(2));
                $('#mbl_total_p').html('$' + (grand_total_include_tax).toFixed(2));

                $('#ipad_tax_price').html('$' + tax.toFixed(2));
                $('#ipad_total_p').html('$' + (grand_total_include_tax).toFixed(2));
            }
        });
    }
    
</script>


<style>

    #mbl_chk_btn {
        font-family: 'Poppins' !important;
        font-style: normal !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        line-height: 18px !important;
        align-items: center !important;
        letter-spacing: 0.03em !important;
        text-transform: uppercase !important;
        color: #FFFFFF !important;
        height: 43px !important;
    }
    .mb_minusqty:focus , .mb_minusqty:active , .mb_plusqty:focus , .mb_plusqty:active {
        outline: none !important;
        box-shadow: none !important;
    }
    .remove_p_mbl {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 10px;
        line-height: 15px;
        /* identical to box height */

        text-align: right;
        text-decoration-line: underline;

        color: #9A9A9A;
    }
    .cart_table_mobile tr td {
        border: none !important
    }
    .table-responsive {
        border: 1px solid #DFDFDF;
        padding: 13px;
        padding-top: 0px;
    }

    .table thead>tr>th {
        border-top: 1px solid #fff;
    }

    .table tbody>tr>td {
        border-top: 1px solid #fff;
    }

    .table thead th {
        vertical-align: bottom;
    }

    .table-head-items {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 600;
        font-size: 16px;
        line-height: 24px;
        color: #000000;
    }


    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    .cart_qty input {
        width: 96px;
        height: 49px;
        font-size: 16px;
        line-height: 24px;
        float: left;
        display: block;
        padding: 0;
        margin: 0;
        padding-left: 34px;
        border: none;
        color: #7CC633;
        box-shadow: 0 0 0 1px rgb(0 0 0 / 8%);
        border-radius: 1px;
    }

    .cart_qty input:focus {
        outline: 0;
    }

    @media only screen and (max-width: 820px) and (min-width: 280px) {
        .cart_qty input {
            height: 47px !important;
        }
    }

    .cart-quantity-nav {
        float: left;
        position: relative;
        height: 72px !important;
    }

    .quantity-div {
        position: relative;
        cursor: pointer;
        border: none;
        border-left: 1px solid rgba(0, 0, 0, 0.08);
        width: 18px;
        text-align: center;
        color: #333;
        font-size: 13px;
        font-family: "FontAwesome" !important;
        line-height: 2.5 !important;
        padding: 0;
        background: #FAFAFA;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .quantity-div:active {
        background: #EAEAEA;
    }

    .quantity-div.quantity-up {
        position: absolute;
        width: 25;
        height: 25px;
        top: 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        font-family: "FontAwesome";
        border-radius: 0 4px 0 0;
        line-height: 1.6
    }

    .quantity-div.quantity-down {
        position: absolute;
        width: 25;
        height: 25px;
        bottom: 0;
        font-family: "FontAwesome";
        border-radius: 0 0 4px 0;
        margin-bottom: 25px;
    }
    .zendesk_img {
        width: 25px;
        height: 25px;
    }
    @media only screen and (max-width: 820px) and (min-width: 426px) {
        .quantity-div.quantity-down {
            margin-bottom: 0px !important;
        }
        .cart_total_div {
            padding: 1rem !important;
            margin-top: 0rem !important;
        } 
    }
    @media screen and (min-width : 550px) and (max-width:768px) {
        .quantity_calculator {
            padding-left:30px !important;
            width: 100px !important;
        }
    }
</style>

<script>
    function showZendesk() {
        zE('webWidget', 'open');
    }
    $(document).ready(function() {
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function() {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

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
        });

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

    $(document).ready(function () {
        $(".stock_tooltip").hover(
            function () {
                $(this).find(".stock_tooltip_message").stop(true, true).fadeIn();
            },
            function () {
                $(this).find(".stock_tooltip_message").stop(true, true).fadeOut();
            }
        );
    });


    function confirmEmptyCart(event) {

        // var buy_list_id = $('#buy_list_id').val() != '' ? $('#buy_list_id').val() : null;

        // if (buy_list_id == null) {
           
        //     event.preventDefault(); // Prevent the default navigation

        //     Swal.fire({
        //         title: "Are you sure?",
        //         html: '<p class="swal-custom-text">This will remove all items from your cart!</p>',
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, Empty Cart",
        //         cancelButtonText: "Cancel",
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         customClass: {
        //             title: 'swal-title-black',
        //             popup: 'swal-popup-custom',
        //             confirmButton: 'swal-confirm-button',
        //             cancelButton: 'swal-cancel-button',
        //             text: 'swal-text-black'
        //         }
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.location.href = event.target.href; // Redirect to empty cart route
        //         }
        //     });
        // } else {
        //     window.location.href = event.target.href
        // }
        event.preventDefault(); // Prevent the default navigation

        Swal.fire({
            title: "Are you sure?",
            html: '<p class="swal-custom-text">This will remove all items from your cart!</p>',
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Empty Cart",
            cancelButtonText: "Cancel",
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                title: 'swal-title-black',
                popup: 'swal-popup-custom',
                confirmButton: 'swal-confirm-button',
                cancelButton: 'swal-cancel-button',
                text: 'swal-text-black'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Redirect to empty cart route
            }
        });
    }

</script>
