@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('order-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row mt-3">
                <div class="col-md-8 col-xl-6">
                    @include('my-account.my-account-side-bar')
                </div>
                
                <div class="col-md-4 col-xl-6">
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
                                <div class="col-md-10 col-xl-8">
                                    <div class="row mt-3 filter-div-mbl-account">
                                        <div class="col-md-9 d-flex filter1_mbl">
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
                                        <div class="col-md-3 d-flex filter2_mbl">
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
                                <div class="col-md-9 col-xl-8">
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
                                                                    @if(!empty($user_order->contact->address1))
                                                                        <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->address1}}  {{$user_order->contact->city}}  {{$user_order->contact->state}} {{$user_order->contact->postCode}}">
                                                                            {{ \Illuminate\Support\Str::limit($user_order->contact->address1, 10) }}
                                                                        </span>
                                                                    @elseif(!empty($user_order->contact->postalAddress1))
                                                                        <span class="header-row-mbl-my-account shipping_to_my_account" title="{{$user_order->contact->postalAddress1}}  {{$user_order->contact->postalCity}}   {{$user_order->contact->postalState}}  {{$user_order->contact->postalPostCode}}">
                                                                            {{ \Illuminate\Support\Str::limit($user_order->contact->postalAddress1, 10) }}
                                                                        </span>
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
                                                                        @if($user_order->order_id != null && $user_order->isApproved == 1)
                                                                            <span class="badge badge-success">FullFilled</span>
                                                                        @elseif($user_order->order_id == null && $user_order->isApproved == 0)
                                                                            <span class="badge badge-primary">New</span>
                                                                        @elseif($user_order->order_id == null && $user_order->isApproved == 2)
                                                                            <span class="badge badge-danger">Cancelled</span>
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
                                                                                <img src="{{ $orderItem->product->images }}"
                                                                                    alt=""
                                                                                    class="img-fluid my_account_images">
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
                                                                                <img src="{{ $orderItem->product->images }}"
                                                                                    alt=""
                                                                                    class="img-fluid my_account_images">
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
                                    {{ $user_orders->appends(Request::all())->links() }}
                                </div>
                                <div class="col-md-3 col-xl-4 p-3 h-50">
                                    <div class="card rounded buy_again_div">
                                        <div class="card-body">
                                            @if(!empty($frequent_products))
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p class="buy_again_heading">Buy Again</p>
                                                    </div>
                                                </div>
                                                @foreach($frequent_products as $frequent_product)
                                                    @if(!empty($frequent_product))
                                                        <div class="row mt-4 mb-3">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-4 image-div image-div-account d-flex justify-content-center">
                                                                        @if(!empty($frequent_product->product->images))
                                                                            <img src="{{ $frequent_product->product->images }}" alt="" class="buy_again_product_image">
                                                                        @else
                                                                            <img src="{{ asset('/theme/img/image_not_available.png') }}" alt="" class="buy_again_product_image">
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-8 data-div data-div-account">
                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                
                                                                                <p class="product_name mb-1">
                                                                                    @if(!empty($frequent_product->product->name))
                                                                                        <a class="product_name" id="prd_name_{{$frequent_product->product->id }}" href="{{ url('product-detail/' . $frequent_product->product->id . '/' . $frequent_product->product->options[0]->option_id . '/' . $frequent_product->product->slug) }}">{{ $frequent_product->product->name }}</a>
                                                                                    @endif
                                                                                </p>
                                                                                
                                                                            </div>
                                                                            <div class="col-md-10">
                                                                            <p class="product_price mb-1">
                                                                                    @if(!empty($frequent_product->price))
                                                                                        ${{ number_format($frequent_product->price, 2) }}
                                                                                    @endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-md-10">
                                                                                <p class="category_name mb-1">Category:
                                                                                    @if(!empty($frequent_product->product->categories))
                                                                                    <a class="category_name" href="{{ url('products/' . $frequent_product->product->categories->id . '/' .$frequent_product->product->categories->slug) }}"> 
                                                                                        {{$frequent_product->product->categories->name}}
                                                                                    </a> 
                                                                                    @endif
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center mt-4">
                                                                    <div class="col-md-10">
                                                                        <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="add_to_cart('{{ $frequent_product->product->id }}', '{{ $frequent_product->product->options[0]->option_id }}')">Add to Cart</button>
                                                                    </div>
                                                                    <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p class="buy_again_heading">No Frequently buyed products to show</p>
                                                    </div>
                                                </div>
                                            @endif
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
</div>
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
                console.log(response);
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
                        toast: true,
                        icon: 'success',
                        title: 1 + ' X ' + product_name +
                            ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
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
