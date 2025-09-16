@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('order-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
        MY ACCOUNT
    </p>
</div>


<input type="hidden" value="{{App\Helpers\UserHelper::getUserPriceColumn()}}" id="get_column">
<input type="hidden" name="products_to_hide" id="products_to_hide"
    value="{{ htmlspecialchars(json_encode($products_to_hide)) }}">


@php
    $auth = false;
    $paymentTerms = false;
    
    if (auth()->user()) {
        $auth = true;
        $contact = App\Models\Contact::where('user_id', auth()->user()->id)->first();
        if (empty($contact)) {
            $paymentTerms = false;
        }
        $contact_id_new = null; 
        if ($contact->is_parent == 1) {
            $contact_id_new = $contact->contact_id;
        } else {
            $contact_id_new = $contact->parent_id;
        }

        $get_main_contact = App\Models\Contact::where('id', $contact_id_new)->first();
        if (!empty($get_main_contact) && strtolower($get_main_contact->paymentTerms) == 'pay in advanced') {
            $paymentTerms = true;
        } else {
            $paymentTerms = false;
        }
    } else {
        $auth = false;
        $paymentTerms = false;
    }
@endphp

<input type="hidden" name="paymentTerms" id="paymentTerms" value="{{$paymentTerms === false ? 0 : 1}}">
<input type="hidden" name="auth_value" id="auth_value" value="{{$auth === false ? 0 : 1}}">


<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="w-100 my-3">
                <div class="alert alert-success alert-dismissible d-none mb-0 text-center notify_user_div_detail">
                    <a href="#" onclick="hide_notify_user_div()" class="close" aria-label="close">&times;</a>
                    <span class="notify_text_detail"></span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 col-xl-6">
                    @include('my-account.my-account-side-bar')
                </div>
                
                <div class="col-md-12 col-xl-6">
                    <div class="row search_row_my_account_page">
                        <div class="col-md-12 d-flex">
                            <div class="has-search my_account_search w-100 mt-0">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="#" class="mb-2">
                                    <input type="text" class="form-control border-0" id="order_search" name="search"
                                        placeholder="Search all orders" value="{{ isset($search) ? $search : '' }}" />
                            </div>
                            <div class="ps-3">
                                <button type="button" class="btn my_account_search_btn my_account_search_btn_mbl" onclick="search_orders()">Search</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 col-lg-10 col-xl-8">
                                    <div class="row mt-3 filter-div-mbl-account">
                                        <div class="col-lg-6 col-md-6 col-xl-5 d-flex filter1_mbl col-12">
                                            <span>
                                                <p class="total_order_my_account mt-2">@if(!empty($date_filter)) {{$user_orders->total()}} @else {{$user_orders->total()}} @endif orders
                                                    <span class="placed_in_my_account">
                                                        &nbsp; placed in
                                                    </span>
                                                </p>
                                            </span>
                                            <span class="select_months_my_account mx-2" style="margin-top: 0px !important;">
                                                <select class="custom-select date_filter date_filter_mbl" id="inputGroupSelect01" name="date_filter" onchange="date_filter()">
                                                        
                                                    <option value="last-3-months" {{ $date_filter }} {{ isset($date_filter) && $date_filter =='last-3-months' ? 'selected="selected"' : ''
                                                    }}>Last 3 Months</option>
                                                    <option value="this-month" {{ $date_filter }} {{ isset($date_filter) && $date_filter =='this-month' ? 'selected="selected"' : ''
                                                    }}>This Month</option>
                                                    <option value="last-5-months" {{ $date_filter }} {{ isset($date_filter) && $date_filter =='last-5-months' ? 'selected="selected"' : ''
                                                    }}>Last 5 Months</option>
                                                        
                                                    <option value="last-month" {{ $date_filter }} {{ isset($date_filter) && $date_filter =='last-month' ? 'selected="selected"' : ''
                                                    }}>Last Month</option>
                                                    <option value="last-year" {{ $date_filter }} {{ isset($date_filter) && $date_filter =='last-year' ? 'selected="selected"' : ''
                                                    }}>Last Year</option>
                                                    
                                                </select>
                                            </span>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xl-5 d-flex filter2_mbl justify-content-start col-12">
                                            <span>
                                                <p class="total_order_my_account mt-2">
                                                    Submitter
                                                </p>
                                            </span>
                                            <span class="select_months_my_account mx-2" style="margin-top: 0px !important;">
                                                
                                                @if(count($order_submitters) > 0)
                                                   
                                                    <select name="submitter_filter" class="custom-select submitter_filter date_filter_mbl" id="inputGroupSelect01" onchange="submitter_filter()">
                                                            <option value="all"  {{$submitter_filter === 'all' ? 'selected' : ''}}>All</option>
                                                            @foreach ($order_submitters as $order_submitter)
                                                                @php
                                                                    $parent_contact = null;
                                                                    if (!empty($order_submitter->secondary_id)) {
                                                                        $secondary_contact_data = App\Models\Contact::where('secondary_id', $order_submitter->secondary_id)->where('is_parent' , 0)->first();
                                                                        if (!empty($secondary_contact_data)) {
                                                                            $parent_contact = App\Models\Contact::where('contact_id', $secondary_contact_data->parent_id)->where('is_parent' , 1)->first();
                                                                        }
                                                                    }
                                                                @endphp
                                                                <option value="{{ !empty($order_submitter->contact_id) ? $order_submitter->contact_id : $order_submitter->secondary_id}}" {{isset($submitter_filter) && (!empty($submitter_filter)) && ($submitter_filter == $order_submitter->contact_id || $submitter_filter == $order_submitter->secondary_id) ? 'selected' : ''}}>{{$order_submitter->firstName . ' ' . $order_submitter->lastName}} (@if(!empty($order_submitter->contact_id)){{ $order_submitter->company}} @elseif (!empty($parent_contact->company)) {{$parent_contact->company}} @endif )</option>
                                                            @endforeach
                                                    </select>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-xl-2 d-flex filter2_mbl justify-content-start col-12">
                                            <ul class="nav ">
                                                <li class="text-center">
                                                    <select id="handle_sort_by" name="sort_by" class="py-1" onchange="handleSortBY()">
                                                        <option value="recent" {{ $sort_by }} {{ isset($sort_by) && $sort_by=='recent' ? 'selected="selected"' : ''
                                                            }}>Recent</option>
                                                        <option value="amount" {{ $sort_by }} {{ isset($sort_by) && $sort_by=='amount' ? 'selected="selected"' : ''
                                                            }}>Amount</option>
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-8">
                                    @foreach ($user_orders as $user_order)
                                        @if(!empty($user_order->apiOrderItem))
                                        <div class="card my_account_order_card my-3">
                                            <div class="card-header my_account_order_card_header">
                                                <div class="table-responsive hide-scroll">
                                                    <table class="my-account-table-desktop">
                                                        <thead>
                                                            <tr>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">
                                                                        ORDER PLACED
                                                                    </p>
                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">
                                                                        TOTAL
                                                                    </p>
                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">
                                                                        SHIP TO
                                                                    </p>
                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">
                                                                        COMPANY
                                                                    </p>

                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">
                                                                        Status
                                                                    </p>
                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">Submitter</p>
                                                                </th>
                                                                <th class="" width="">
                                                                    <p class="order_place_my_account mb-0">ORDER</p>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <span class="header-row-mbl-my-account order_date_my_account">
                                                                        {{ $user_order->created_at->format('M d, Y') }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="header-row-mbl-my-account total_price_my_account">
                                                                        ${{ number_format($user_order->total_including_tax, 2) }}
                                                                    </span>
                                                                </td>
                        
                                                                <td>
                                                                    @if (!empty($user_order->DeliveryAddress1))
                                                                        <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->DeliveryAddress1}}  {{$user_order->DeliveryCity}}  {{$user_order->DeliveryState}} {{$user_order->DeliveryZip}}">
                                                                            {{ \Illuminate\Support\Str::limit($user_order->DeliveryAddress1, 10) }}
                                                                        </span>
                                                                    @else
                                                                        @if(!empty($user_order->contact->address1))
                                                                            <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->address1}}  {{$user_order->contact->city}}  {{$user_order->contact->state}} {{$user_order->contact->postCode}}">
                                                                                {{ \Illuminate\Support\Str::limit($user_order->contact->address1, 10) }}
                                                                            </span>
                                                                        @elseif(!empty($user_order->contact->postalAddress1))
                                                                            <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->postalAddress1}}  {{$user_order->contact->postalCity}}   {{$user_order->contact->postalState}}  {{$user_order->contact->postalPostCode}}">
                                                                                {{ \Illuminate\Support\Str::limit($user_order->contact->postalAddress1, 10) }}
                                                                            </span>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if(!empty($user_order->contact->company))
                                                                        <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->company}}">
                                                                            {{ \Illuminate\Support\Str::limit($user_order->contact->company, 10) }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="header-row-mbl-my-account shipping_to_my_account">
                                                                        <input type="hidden" name="" id="" value="{{$user_order->isApproved}}">
                                                                        @if($user_order->order_id != null && $user_order->isApproved == 1)
                                                                            <span class="badge badge-success">FullFilled</span>
                                                                        @elseif($user_order->order_id == null && $user_order->isApproved == 0)
                                                                            <span class="badge badge-primary">New</span>
                                                                        @elseif($user_order->order_id == null && $user_order->isApproved == 2)
                                                                            <span class="badge badge-danger">Cancelled</span>
                                                                        @elseif ($user_order->isApproved == 3)
                                                                            <span class="badge badge-info">Refunded</span>
                                                                        @elseif ($user_order->isApproved == 4)
                                                                            <span class="badge badge-info">Partially Refunded</span>
                                                                        @elseif ($user_order->isApproved == 5)
                                                                            <span class="badge badge-info">Pending</span>
                                                                        @endif
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if (!empty($user_order->primaryId) && !empty($user_order->primary_contact))
                                                                    <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->primary_contact->firstName.' '.$user_order->primary_contact->lastName}}">
                                                                            {{ \Illuminate\Support\Str::limit($user_order->primary_contact->firstName.' '.$user_order->primary_contact->lastName, 10) }}
                                                                    </span>
                                                                    @elseif (!empty($user_order->secondaryId) && !empty($user_order->secondary_contact))
                                                                    <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->secondary_contact->firstName.' '.$user_order->secondary_contact->lastName}}">
                                                                        {{ \Illuminate\Support\Str::limit($user_order->secondary_contact->firstName.' '.$user_order->secondary_contact->lastName, 10) }}
                                                                    </span>
                                                                    @elseif (!empty($user_order->contact))
                                                                    <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->firstName.' '.$user_order->contact->lastName}}">
                                                                        {{ \Illuminate\Support\Str::limit($user_order->contact->firstName.' '.$user_order->contact->lastName , 10) }}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="header-row-mbl-my-account order_number_my_account">
                                                                        #{{ $user_order->id }}</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{-- <div class="account-header-mbl d-flex">
                                                    <span class="account-header-items-mbl border-bottom-my-account-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            ORDER PLACED <br>
                                                            <span class="order_date_my_account">
                                                                {{ $user_order->created_at->format('M d, Y') }}
                                                            </span>
                                                        </p>
                                                    </span>
                                                    <span class="account-header-items-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            TOTAL <br>
                                                            <span class="total_price_my_account">
                                                                ${{ number_format($user_order->total_including_tax, 2) }}
                                                            </span>
                                                        </p>

                                                    </span>
                                                    <span class="account-header-items-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            SHIP TO <br>
                                                            @if(!empty($user_order->contact->address1))
                                                                <span class="shipping_to_my_account" title="{{$user_order->contact->address1}}">
                                                                    {{ \Illuminate\Support\Str::limit($user_order->contact->address1, 10) }}
                                                                </span>
                                                            @elseif(!empty($user_order->contact->postalAddress1))
                                                                <span class="shipping_to_my_account" title="{{$user_order->contact->postalAddress1}}">
                                                                    {{ \Illuminate\Support\Str::limit($user_order->contact->postalAddress1, 10) }}
                                                                </span>
                                                            @endif
                                                        </p>
                                                    </span>
                                                    <span class="account-header-items-mbl border-bottom-my-account-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            Company <br>
                                                            @if(!empty($user_order->contact->company))
                                                            <span class="shipping_to_my_account">
                                                                {{ \Illuminate\Support\Str::limit($user_order->contact->company, 10) }}
                                                            </span>
                                                            @endif
                                                        </p>
                                                    </span>
                                                    <span class="account-header-items-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            Status <br>
                                                            <span class="shipping_to_my_account">
                                                                @if($user_order->order_id != null && $user_order->isApproved == 1)
                                                                    <span class="badge badge-success">FullFilled</span>
                                                                @elseif($user_order->order_id == null && $user_order->isApproved == 0)
                                                                    <span class="badge badge-primary">New</span>
                                                                @elseif($user_order->order_id == null && $user_order->isApproved == 2)
                                                                    <span class="badge badge-danger">Cancelled</span>
                                                                @endif
                                                            </span>
                                                        </p>
                                                    </span>
                                                    <span class="account-header-items-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            Submitter <br>
                                                            <span class="shipping_to_my_account">
                                                                @if (!empty($user_order->primaryId) && !empty($user_order->primary_contact))
                                                                    {{ \Illuminate\Support\Str::limit($user_order->primary_contact->firstName.' '.$user_order->primary_contact->lastName, 10) }}
                                                                @elseif (!empty($user_order->secondaryId) && !empty($user_order->secondary_contact))
                                                                    {{ \Illuminate\Support\Str::limit($user_order->secondary_contact->firstName.' '.$user_order->secondary_contact->lastName, 10) }}
                                                                @elseif (!empty($user_order->contact))
                                                                    {{ \Illuminate\Support\Str::limit($user_order->contact->firstName.' '.$user_order->contact->lastName , 10) }}
                                                                @endif
                                                            </span>
                                                        </p>
                                                    </span>
                                                    <span class="account-header-items-mbl">
                                                        <p class="order_place_my_account mb-0">
                                                            ORDER <br>
                                                            <span class="order_number_my_account">
                                                                #{{ $user_order->id }}</span>
                                                        </p>
                                                    </span>
                                                </div> --}}
                                            </div>
                                            <div class="card-body my-account-card-body">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        {{-- @if (count($user_order->apiOrderItem) > 1)
                                                            <p class="picked_up_date">Picked up
                                                                {{ $user_order->created_at->format('M d') }} </p>
                                                        @else
                                                            @if ($user_order->isApproved == 0)
                                                                <p class="arriving_wednesday_my_account mb-0"> Not Yet
                                                                    Shipped</p>
                                                                <p class="delivery_estimate_date_my_account">Delivery
                                                                    Estimate:
                                                                    {{ $user_order->created_at->format('D, M d, Y, A') }}
                                                                </p>
                                                            @else
                                                                <p class="arriving_wednesday_my_account mb-0">Arriving
                                                                    {{ $user_order->created_at->format('D') }}
                                                                </p>
                                                                <p class="delivery_estimate_date_my_account">Shipped</p>
                                                            @endif
                                                        @endif --}}
                                                        <div class="row main-order-row" id="main_div_{{$user_order->id}}">
                                                            @php
                                                                $totalItems = $user_order->apiOrderItem->count();
                                                            @endphp
                                                            @if (count($user_order->apiOrderItem) > 1)
                                                                @foreach ($user_order->apiOrderItem as $key => $orderItem)
                                                                    <div class="my-2 my-account-image-div-mbl {{($key == 4 || $key > 4) ? 'd-none' : '' }}">
                                                                        <div class="img_my_account_order p-2">
                                                                            @if (!empty($orderItem->product->images))
                                                                                @if (!empty($orderItem->product) && !empty($orderItem->product->product_image->image))
                                                                                    <picture>
                                                                                        <source srcset="{{ asset('theme/products/images/' . $orderItem->product->product_image->image . '.webp') }}" type="image/webp">
                                                                                        <img src="{{ asset('theme/products/images/' . $orderItem->product->product_image->image . '.png') }}"
                                                                                            alt="{{ $orderItem->product->name }}"
                                                                                            class="img-fluid my_account_images" loading="lazy" />
                                                                                    </picture>
                                                                                @else
                                                                                    <img src="{{ $orderItem->product->images }}" alt="" class="img-fluid my_account_images">
                                                                                @endif
                                                                            @else
                                                                                <img src="{{ asset('/theme/img/image_not_available.png') }}"
                                                                                    alt=""
                                                                                    class="img-fluid my_account_images">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                @if( $totalItems > 5 )
                                                                    <button class="w-25 text-decoration-underline border-0 p-0 m-0 bg-white text-success text-left ml-3" id="show_div_{{$user_order->id}}" onclick="show_more('{{$user_order->id}}')">ShowMore</button>
                                                                    <button class="w-25 text-decoration-underline border-0 p-0 m-0 bg-white text-success text-left ml-3 d-none" id="hide_div_{{$user_order->id}}" onclick="show_less('{{$user_order->id}}')">ShowLess</button>
                                                                @endif
                                                            @else
                                                                @foreach ($user_order->apiOrderItem as $orderItem)
                                                                    <div class="my-2 my-account-image-div-mbl">
                                                                        <div class="img_my_account_order p-2">
                                                                            @if (!empty($orderItem->product->images))
                                                                                @if (!empty($orderItem->product) && !empty($orderItem->product->product_image->image))
                                                                                    <picture>
                                                                                        <source srcset="{{ asset('theme/products/images/' . $orderItem->product->product_image->image . '.webp') }}" type="image/webp">
                                                                                        <img src="{{ asset('theme/products/images/' . $orderItem->product->product_image->image . '.png') }}"
                                                                                            alt="{{ $orderItem->product->name }}"
                                                                                            class="img-fluid my_account_images" loading="lazy" />
                                                                                    </picture>
                                                                                @else
                                                                                    <img src="{{ $orderItem->product->images }}" alt="" class="img-fluid my_account_images">
                                                                                @endif
                                                                            @else
                                                                                <img src="{{ asset('/theme/img/image_not_available.png') }}"
                                                                                    alt=""
                                                                                    class="img-fluid my_account_images">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="my-2  my-account-name-div-mbl">
                                                                        @if (!empty($orderItem->product->name))
                                                                        <p class="my_account_product_name">
                                                                            {{ $orderItem->product->name }}
                                                                        </p>
                                                                        @endif
                                                                        <p>
                                                                            {{-- <button type="button"
                                                                                class="btn my_account_buy_it_again">
                                                                                <span>
                                                                                    <img src="/theme/img/green_icon.png"
                                                                                        alt=""
                                                                                        class="img-fluid pe-2 ">
                                                                                </span>Buy it
                                                                                again
                                                                            </button> --}}
                                                                        </p>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if (count($user_order->apiOrderItem) > 0)
                                                        <div class="col-md-5 button-div-my-account">
                                                            <button type="button" class="btn all_items_to_cart_btn mbl-my-account-add-btn" onclick="add_products_to_cart({{$user_order->id}})">
                                                                Add
                                                                all
                                                                items
                                                                to
                                                                Cart
                                                            </button>
                                                            <br>
                                                            <a href="{{ url('my-account/my-order-detail/' . $user_order->id) }}"
                                                                class="btn my_account_view_order_btn my-1 mbl-my-account-add-btn">View
                                                                order</a>
                                                        </div>
                                                    @else
                                                        <div class="col-md-5 text-right">
                                                            {{-- <button type="button"
                                                                class="btn track_package_cart_btn">Track
                                                                package
                                                                Cart
                                                            </button>
                                                            <br>
                                                            <button type="button"
                                                                class="btn return_or_replace_items_btn my-1">Return
                                                                or replace items</button> <br> --}}
                                                            <button type="button" class="btn all_items_to_cart_btn mbl-my-account-add-btn" onclick="add_products_to_cart({{$user_order->id}})">
                                                                Add
                                                                all
                                                                items
                                                                to
                                                                Cart
                                                            </button>
                                                            <br>
                                                            <a href="{{ url('my-account/my-order-detail/' . $user_order->id) }}"
                                                                class="btn return_or_replace_items_btn my-1 mbl-my-account-add-btn">View
                                                                Order Details</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="border-top">
                                                <p class="my_account_total_items_listing d-flex align-items-center mb-0 p-2 pl-3">
                                                    {{ $totalItems }} items
                                                    in
                                                    the order
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="w-100 justify-content-center p-2 mt-3 mb-2">
                                        {{ $user_orders->appends(Request::all())->links() }}
                                        {{-- {{ $user_orders->appends(Request::all())->links('pagination.front_custom_pagination') }} --}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4 p-3">
                                    <div class="card rounded buy_again_div">
                                        @include('my-account.buy_again_slider')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notify_popup_modal_similar_portion" id="notify_user_modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock Portion</h5>
                <button type="button" class="close" onclick="close_notify_user_modal_similar()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="hidden" name="sku" id="sku_value" class="similar_productSku_value" value="">
                            <input type="hidden" name="product_id" id="product_id_value" class="similar_productId_value"
                                value="">
                            <div class="col-md-12">
                                <input type="text" name="notify_user_email" id="notify_user_email"
                                    class="form-control similar_notifyEmail_sidebar" value=""
                                    placeholder="Enter your email">
                                <div class="text-danger email_required_alert_similar"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="spinner-border text-primary stock_spinner_modal_similar d-none" role="status">
                    <span class="sr-only"></span>
                </div>
                <button type="button" class="btn btn-secondary"
                    onclick="notify_user_about_product_stock_similar_portion ($('.similar_productId_value').val() , $('.similar_productSku_value').val())">Submit</button>
                <!-- You can add additional buttons here if needed -->
            </div>
        </div>
    </div>
</div>
<script>
    function show_notify_popup_modal_similar_portion (id , sku_value) {
            $('.notify_popup_modal_similar_portion').modal('show');
            $('.similar_productId_value').val(id);
            $('.similar_productSku_value').val(sku_value);
        } 
        function close_notify_user_modal_similar () {
            $('.notify_popup_modal_similar_portion').modal('hide');
            $('.notify_stock_btn_class').each(function() {
                $(this).attr('disabled', false);
            });
        }
        
        function  notify_user_about_product_stock_similar_portion  (id , sku_value) {
            $('.notify_stock_btn_class').each(function() {
                var p_id = $(this).attr('data-product-id');
                if (p_id != id) {
                    $(this).attr('disabled', true);
                }
            });
            var email = $('.similar_notifyEmail_sidebar').val();
            var sku = sku_value;
            var product_id = id;
            $('.stock_spinner_modal_similar').removeClass('d-none');
            $('.stock_spinner_'+product_id).removeClass('d-none');
            if (email != '') {
                $('.email_required_alert_similar').html('');
            }
            if (email == '') {
                $('.email_required_alert_similar').html('Email is Required');
                $('.stock_spinner_modal_similar').addClass('d-none');
                $('.stock_spinner_'+product_id).addClass('d-none');
                return false;
            }
            else {
                $.ajax({
                    url: "{{ url('product-stock/notification') }}",
                    method: 'post',
                    data: {
                    "_token": "{{ csrf_token() }}",
                        email : email,
                        sku : sku,
                        product_id : product_id
                    },
                    success: function(response){

                        if (response.status === true) {
                            $('.stock_spinner_modal_similar').addClass('d-none');
                            $('.stock_spinner_'+product_id).addClass('d-none');
                            $('.notify_user_div_detail').removeClass('d-none');
                            close_notify_user_modal_similar();
                            $('.notify_text_detail').html(response.message);
                        } else {
                            $('.stock_spinner_modal_similar').addClass('d-none');
                            $('.stock_spinner_'+product_id).addClass('d-none');
                            $('.notify_user_div_detail').removeClass('d-none');
                            $('.notify_text_detail').html('Something went wrong!');
                        }
                    },
                    error: function(response) {
                        var error_message = response.responseJSON;
                        $('.stock_spinner_modal_similar').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div').addClass('d-none');
                        var error_text  = error_message.errors.email[0];
                        $('.email_required_alert').html(error_text)
                    },
                    complete: function() {
                        // Re-enable all buttons with class 'notify_stock_btn_class'
                        $('.notify_stock_btn_class').prop('disabled', false);
                    }
                });
            }
        }
        
        function hide_notify_user_div() {
            $('.notify_text_detail').html('');
            $('.notify_user_div_detail').addClass('d-none');
        }
</script>
<style>
    #handle_sort_by {
        border-radius: 6px;
        border: 1px solid #E1E1E1;
        background-color: #fffffF;
        padding: 4px 4px 4px 12px;
        display: inline-flex;
        height: 38px;
        color:#252C32;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        letter-spacing: -0.084px;
        font-family:'poppins';
    }
    #handle_sort_by:focus-visible {
        border-radius: 6px;
        border: 1px solid #E1E1E1;
    }
    #handle_sort_by::after{
        border-radius: 6px;
        border: 1px solid #E1E1E1;
        color:red;
    }
    #handle_sort_by::-ms-expand {
        color:#989898;
    }
    #handle_sort_by::-webkit-appearance {
        color:#989898;
    }
    #handle_sort_by::-moz-appearance {
        color:#989898;
    }
    .buy_again_heading {
        color: #242424;
        font-family: 'Poppins';
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .product_name {
        color: #000;
        font-family: 'Poppins';
        font-size: 14.669px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .product_price {
        color: #DC4E41;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }
    .category_name {
        color: #8A8A8A;
        font-family: 'Poppins';
        font-size: 11.002px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }
    .buy_frequent_again_btn {
        flex-shrink: 0;
        border-radius: 6px;
        background: #7BC533;
        box-shadow: 0px 2.474916458129883px 3.712374687194824px 0px rgba(0, 0, 0, 0.08);
        color: #FFF;
        text-align: center;
        font-family: 'Poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 21.037px; /* 150.263% */
    }
    .border-div {
        height: 1.237px;
        background: #E1E1E1;
        width: 90% !important;
    }
    .buy_again_div {
        border:1px solid rgb(234 236 240);
    }
    .search_row_my_account_page {
        margin-top: 0px;
    }
    .buy_again_product_image {
        height: 80px;
        width: 80px;
    }
</style>
<script>
    function show_more(id) {
        $('#main_div_'+id).find('.d-none').removeClass('d-none');
        $('#show_div_'+id).addClass('d-none');
        $('#hide_div_'+id).removeClass('d-none');
    }
    function show_less (id) {
        $('#main_div_'+id).find('.my-account-image-div-mbl').slice(4).addClass('d-none');
        $('#show_div_'+id).removeClass('d-none');
        $('#hide_div_'+id).addClass('d-none');
    }
    function search_orders() {
        var search = jQuery('#order_search').val();
        var basic_url = `/my-account`;
        if (search != '') {
            basic_url = basic_url+`?search=${search}`;
        }
        window.location.href = basic_url
    }
    function date_filter() {
        var date_filter = jQuery('.date_filter').val();
        var basic_url = `/my-account`;
        if (date_filter != '') {
            basic_url = basic_url+`?date_filter=${date_filter}`;
        }
        window.location.href = basic_url
    }
    function submitter_filter() {
        var submitter_filter = jQuery('.submitter_filter').val();
        var basic_url = `/my-account`;
        if (submitter_filter != '') {
            basic_url = basic_url+`?submitter_filter=${submitter_filter}`;
        }
        window.location.href = basic_url
    }
    function add_to_cart(id, option_id) {
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                quantity: 1,
            },
            success: function(response) {
                if (response.status == 'error') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }

                    Swal.fire({
                        toast: false,
                        icon: 'error',
                        title: response.message,
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            popup: 'swal2-popup-class',  // Class for the actions container
                            actions: 'my-actions-class'  // Class for the actions container
                        },
                    });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }

                    Swal.fire({
                        toast: false,
                        icon: 'success',
                        title: 1 + 'X ' + '<span class="text-dark toast_title">'+ product_name+'</span>' + '<br/>'+  '<div class="added_tocart">Added to your cart</div>',
                        // customClass: {popup: 'short-toast-popup'}
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                popup: 'swal2-popup-class',  // Class for the actions container
                                actions: 'my-actions-class'  // Class for the actions container
                        },
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);

                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
    }
</script>
@include('my-account.my-account-scripts')
@include('partials.product-footer')

@include('partials.footer')
{{-- pagination for buy again script --}}
<script>
    $(document).ready(function() {
        loadPaginationBuyAgain(1);

        function loadPaginationBuyAgain(page) {
            
            $.ajax({
                url: '/my-account/buy-again-products?page=${page}',
                method: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.frequent_products.data.length > 0) {
                        var html = buildBuyagainProductsHtml(response);
                        $('#buy_again_container').html(html);
                        updateBuyagainProductsPaginationLinks();
                    } else {
                        var html = '<div class="row"><div class="col-md-12"><p class="buy_again_heading">No products found</p></div></div>';
                        $('#buy_again_container').html(html);
                    }
                }
            });
        }


        function updateBuyagainProductsPaginationLinks() {
            $('body').on('click', '.pagination-link-buy-again', function(e) { 
                e.preventDefault();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                var page = $(this).text();
                loadPaginationBuyAgain(page);
            });
            
        }

        function buildBuyagainProductsHtml(response) {
            var html = '';
            var data = response.frequent_products.data;
            var get_wholesale_terms = response.get_wholesale_terms;
            var header = '<div class="row"><div class="col-md-12"><p class="buy_again_heading">Buy Again</p></div></div>';
            html += header;
            // for (var i = 0; i < data.length; i++) {
            //     html += buildProductRow(data[i]);
            // }

            data.forEach(product => {
                if (product.options && product.options.length > 0) {
                    product.options.forEach(option => {
                        html += buildProductRow(product, option ,get_wholesale_terms);
                    });
                } 
                // else {
                //     html += buildProductRow(product, null);
                // }
            });

            var totalPages = response.frequent_products.last_page;
            var currentPage = response.frequent_products.current_page;
            var paginationHtml = $('#pagination-list-buy-again').html(generatePaginationLinks(totalPages, currentPage));
            // html += paginationHtml;

            return html;
        }

        

        function buildProductRow(productData , option ,get_wholesale_terms) {
            var rowHtml = '<div class="row mt-4 mb-3">';
            rowHtml += '    <div class="col-md-12">';
            rowHtml += '        <div class="row">';
            rowHtml += buildImageColumn(productData , productData.images);
            rowHtml += buildDataColumn(productData , option ,get_wholesale_terms);
            rowHtml += '        </div>';
            rowHtml += buildButtonRow(productData , option ,get_wholesale_terms);
            rowHtml += '    </div>';
            rowHtml += '</div>';

            return rowHtml;
        }

        // function buildImageColumn(product , imageUrl) {
        //     if (imageUrl != '') {
        //         imageUrl = imageUrl;
        //     } else {
        //         imageUrl = '/theme/img/image_not_available.png';
        //     }
        //     return '<div class="col-md-4 col-xl-5 col-lg-4 image-div image-div-account d-flex justify-content-center">' +
        //         '<img src="' + imageUrl  + '" alt="Product Image" class="img-fluid">' +
        //         '</div>';
        // }
        const productImageBase = "{{ asset('theme/products/images') }}";
        const placeholderImage = "{{ asset('theme/img/image_not_available.png') }}";

        function buildImageColumn(product, imageUrl) {
            if (imageUrl) {
                if (product.product_image && product.product_image.image) {
                    return `
                        <div class="col-md-4 col-lg-4 col-xl-5 image-div image-div-account d-flex justify-content-center">
                            <picture>
                                <source srcset="${productImageBase}/${product.product_image.image}.webp" type="image/webp">
                                <img id="main-image"
                                    alt="${product.name ?? 'Product Image'}"
                                    src="${productImageBase}/${product.product_image.image}.png"
                                    class="img-fluid"
                                    loading="lazy" />
                            </picture>
                        </div>
                    `;
                } else {
                    return `
                        <div class="col-md-4 col-lg-4 col-xl-5 image-div image-div-account d-flex justify-content-center">
                            <img src="${imageUrl}" alt="Product Image" class="img-fluid">
                        </div>
                    `;
                }
            } else {
                return `
                    <div class="col-md-4 col-lg-4 col-xl-5 image-div image-div-account d-flex justify-content-center">
                        <img src="${placeholderImage}" alt="No Image" class="img-fluid">
                    </div>
                `;
            }
        }

        function buildDataColumn(productData, option ,get_wholesale_terms) {
            const products_to_hide = JSON.parse($('#products_to_hide').val() || '[]');
            let show_price = true;
            const paymentTerms = $('#payment_terms').val() === 'true';
            const auth_value = $('#auth_value').val() === 'true';
            const column = $('#get_column').val() || 'default';

            // Determine whether to show the price based on product hiding and authorization/payment terms
            if (option?.option_id && products_to_hide.includes(option.option_id)) {
                if (!auth_value || !paymentTerms) {
                    show_price = false;
                }
            }

            // Start building HTML
            let dataHtml = `
                <div class="col-md-8 col-xl-7 col-lg-8 data-div data-div-account d-flex align-items-center">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="product_name mb-1">
                                <a class="product_name" id="prd_name_${productData.id}" href="/product-detail/${productData.id}/${option?.option_id || ''}/${productData.slug}">
                                    ${productData.name}
                                </a>
                            </p>
                        </div>
            `;

            const retail_price = 
                (option?.default_price?.[column] != null && option?.default_price?.[column] > 0) ? option.default_price[column] :
                (option?.default_price?.sacramentoUSD != null && option?.default_price?.sacramentoUSD > 0) ? option.default_price.sacramentoUSD :
                (option?.default_price?.retailUSD != null && option?.default_price?.retailUSD > 0) ? option.default_price.retailUSD :
                0;

            // Add price if applicable
            // $${(productData.retail_price || 0).toFixed(2)}
            if (show_price) {
                dataHtml += `
                    <div class="col-md-10">
                        <p class="product_price mb-1">
                            $${retail_price.toFixed(2)}
                        </p>
                    </div>
                `;
            }

            // Add category information
            if (productData.categories) {
                dataHtml += `
                    <div class="col-md-10">
                        <p class="category_name mb-1">Category:
                            <a class="category_name" href="/products/${productData.categories.id}/${productData.categories.slug}">
                                ${productData.categories.name}
                            </a>
                        </p>
                    </div>
                `;
            }

            dataHtml += `
                    </div>
                </div>
            `;
            return dataHtml;
        }


        function buildButtonRow(productData, option ,get_wholesale_terms) {
        const products_to_hide = JSON.parse($('#products_to_hide').val() || '[]');
            let add_to_cart = true;
            const paymentTerms = $('#paymentTerms').val() && $('#paymentTerms').val() !== '0';
            const auth_value = $('#auth_value').val() && $('#auth_value').val() !== '0';
            let lowerCaseTerms = (get_wholesale_terms?.trim() || "").toLowerCase();

            // Check conditions for hiding products and authorization/payment terms
            if (products_to_hide.includes(option.option_id)) {
                if (!auth_value || !paymentTerms) {
                    add_to_cart = false;
                }
            }

            let buttonRowHtml = '<div class="row justify-content-center mt-4">';
            if (add_to_cart) {
                if (lowerCaseTerms !== 'pay in advanced') {
                    buttonRowHtml += `
                        <div class="col-md-10">
                            <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="add_to_cart('${productData.id}', '${option.option_id}')">
                                Add to Cart
                            </button>
                        </div>`;
                }
                else {
                    if (option.stockAvailable > 0) {
                        // Show "Add to Cart" button
                        buttonRowHtml += `
                            <div class="col-md-10">
                                <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="add_to_cart('${productData.id}', '${option.option_id}')">
                                    Add to Cart
                                </button>
                            </div>`;
                    } else {
                        // Show "Notify" button if out of stock
                        if (auth_value) {
                            buttonRowHtml += `
                                <div class="col-md-10">
                                    <button type="button" class="btn btn-sm btn-primary w-100 p-2 rounded" onclick="notify_user_about_product_stock_similar_portion('${productData.id}', '${productData.code}')">
                                        Notify
                                    </button>
                                    <div class="spinner-border text-white custom_stock_spinner stock_spinner_${productData.id} ml-1 d-none" role="status" style="position:absolute;top:5px,left:45% !important;">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>`;
                        } else {
                            buttonRowHtml += `
                                <div class="col-md-10">
                                    <button type="button" class="btn btn-sm btn-primary w-100 p-2 rounded" onclick="show_notify_popup_modal_similar_portion('${productData.id}', '${productData.code}')">
                                        Notify
                                    </button>
                                </div>`;
                        }
                    }
                }
            } else {
                // Show "Call to Order" button
                buttonRowHtml += `
                    <div class="col-md-10">
                        <button type="button" class="buy_frequent_again_btn_call_to_order btn-info btn-sm btn border-0 w-100 p-2">
                            Call To Order
                        </button>
                    </div>`;
            }

            buttonRowHtml += '</div>';
            return buttonRowHtml;
        }


        function generatePaginationLinks(totalPages, currentPage) {
            var paginationHtml = '';

            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                paginationHtml += '<li class="pagination-item-buy-again"><a href="#" class="pagination-link-buy-again ' + activeClass + '">' + i + '</a></li>';
            }

            return paginationHtml;
        }
    });
</script>

{{-- end --}}