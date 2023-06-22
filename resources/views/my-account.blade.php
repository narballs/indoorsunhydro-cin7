@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('order-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 pt-3">
                    @include('my-account.my-account-side-bar')
                </div>
                {{-- <div class="col-md-6 py-4">
                    <div class="row search_row_my_account_page">
                        <div class="col-md-10 d-flex ">
                            <div class="has-search my_account_search w-100 ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="#" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search all orders" value="{{ isset($search) ? $search : '' }}" />
                            </div>
                            <div class="ps-3">
                                <button type="button" class="btn my_account_search_btn">Search</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    {{-- <div class="row">
                        <div class="col-md-12 d-flex ps-4">
                            <span>
                                <p class="total_order_my_account">12 orders
                                    <span class="placed_in_my_account">
                                        &nbsp; placed in
                                    </span>
                                </p>
                            </span>
                            <span class="select_months_my_account">
                                <select class="custom-select" id="inputGroupSelect01">
                                    <option selected>Past 3 months</option>
                                    <option value="1">Past 2 months</option>
                                    <option value="2">Past 1 months</option>
                                    <option value="3">Past 4 months</option>
                                </select>
                            </span>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12 my-3">
                            <div class="row">
                                <div class="col-md-8">
                                    @foreach ($user_orders as $user_order)
                                        <div class="card my_account_order_card my-3">
                                            <div class="card-header my_account_order_card_header">
                                                <div class="row">
                                                    <div
                                                        class="col-md-8 d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <p class="order_place_my_account">
                                                                ORDER PLACED <br>
                                                                <span class="order_date_my_account">
                                                                    {{ $user_order->created_at->format('M d, Y') }}
                                                                </span>
                                                            </p>
                                                        </span>
                                                        <span>
                                                            <p class="order_place_my_account">
                                                                TOTAL <br>
                                                                <span class="total_price_my_account">
                                                                    ${{ $user_order->total }}
                                                                </span>
                                                            </p>

                                                        </span>
                                                        <span>
                                                            <p class="order_place_my_account">
                                                                SHIP TO <br>
                                                                <span class="shipping_to_my_account">
                                                                    {{ \Illuminate\Support\Str::limit($user_order->contact->postalAddress1, 20) }}
                                                                </span>
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <p class="order_place_my_account">
                                                            ORDER <br>
                                                            <span class="order_number_my_account">
                                                                #{{ $user_order->id }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        @if (count($user_order->apiOrderItem) > 1)
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
                                                        @endif
                                                        <div class="row">
                                                            @php
                                                                $totalItems = $user_order->apiOrderItem->count();
                                                            @endphp
                                                            @if (count($user_order->apiOrderItem) > 1)
                                                                @foreach ($user_order->apiOrderItem as $orderItem)
                                                                    <div class="my-2" style="width:20% !important">
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
                                                            @else
                                                                @foreach ($user_order->apiOrderItem as $orderItem)
                                                                    <div class="my-2" style="width:20% !important">
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
                                                                    <div class="my-2" style="width:75% !important">
                                                                        <p class="my_account_product_name">
                                                                            {{ $orderItem->product->name }}
                                                                        </p>
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
                                                    @if (count($user_order->apiOrderItem) > 1)
                                                        <div class="col-md-5 text-right">
                                                            {{-- <button type="button" class="btn all_items_to_cart_btn ">
                                                                Add
                                                                all
                                                                items
                                                                to
                                                                Cart
                                                            </button> --}}
                                                            <br>
                                                            <a href="{{ url('my-account/my-order-detail/' . $user_order->id) }}"
                                                                class="btn my_account_view_order_btn my-1">View
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
                                                            <a href="{{ url('my-account/my-order-detail/' . $user_order->id) }}"
                                                                class="btn return_or_replace_items_btn my-1">View
                                                                Order Details</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="border-top">
                                                <p class="my_account_total_items_listing">
                                                    {{ $totalItems }} items
                                                    in
                                                    the order
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{ $user_orders->appends(Request::all())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('my-account.my-account-scripts')
@include('partials.product-footer')
@include('partials.footer')
