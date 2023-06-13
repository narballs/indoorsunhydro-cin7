@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .nav .active {
        background: #F5F5F5;
        /* border-left: none !important; */
        /* color: green !important; */
        color: #008AD0 !important;
    }

    nav svg {
        max-height: 20px !important;
    }

    #spinner-global {
        display: none !important;
    }

    input[type=number]::-webkit-outer-spin-button {

        opacity: 20;

    }
</style>
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid" style="width:1621px  !important;">
    <div class="row bg-light">
        <div class="container-fluid" id="main-row">
            @include('my-account.my-account-top-bar')
            <div class="row flex-xl-nowrap p-0 m-0 mr-3">
                @include('my-account.my-account-side-bar')
                <div class="col-xl-10 col-sm-12 col-xs-12 py-3 bg-white ms-3" style="border-radius: 10px !important;">
                    <div class="" id="">
                        @if(!empty($user_order))
                        <div class="col-md-12 mt-4 order-detail-container pl-4 pr-4"
                            id="order-detail-container">
                            <div class="row mt-3  detail-heading" id="detail-heading">
                                <div class="row mb-4 mt-3 pr-0">
                                    <div class="col-md-4">
                                        <img src="/theme/img/order_details.png" style="margin: -1px 2px 1px 1px;">
                                        <span class="pt-1 my-account-content-heading">Order Details
                                        </span>
                                    </div>
                                    <div class="col-md-8 rounded-end" id="order_content">
                                        <div class="mt-1" id="order_id">
                                            <div class="mt-1" id="order_id">
                                                Order #<strong>{{$user_order->id}}</strong> was placed on <strong>{{ \Carbon\Carbon::createFromTimestamp(strtotime( $user_order->createdDate))->format('F d, Y') }}</strong> and is currently <strong>{{$user_order->status}}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="mt-3">
                                <table class="w-100">
                                    <tbody>
                                        <tr class="border-bottom order-table-heading">
                                            <td class="address-weight">Products</td>
                                        </tr>
                                    </tbody>
                                    <tbody id="" class="">
                                        @foreach($orderdetails as $order_detail)
                                        <tr class="border-bottom table-row-content" style="height:70px">
                                            <td style="width:491px"><a href="">{{$order_detail->product->name}}</a></td>
                                            <td class="cart-basket d-flex align-items-center justify-content-center float-sm-end quantity-counter rounded-circle mt-4">{{$order_detail->quantity}}</td>
                                            <td></td>
                                            <td class="table-order-number text-dark text-end">{{'$' . ($order_detail->price  * $order_detail->quantity)}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="border-bottom" style="height:70px">
                                            <td class="table-row-content">Subtotal</td>
                                            <td></td>
                                            <td></td>
                                            <td class="table-order-number text-dark text-end">{{'$' . $user_order->total}}</td>
                                        </tr>
                                        <tr class="border-bottom" style="height:70px">
                                            <td class="table-row-content"><img src="/theme/img/arrow_1.png"> 
                                                <span>Tax </span>
                                            </td>
                                            <td></td><td></td>
                                            <td class="table-order-number text-dark text-end">{{$user_order->texClasses->rate . '%'}}</td>
                                        </tr>
                                        <tr class="border-bottom" style="height:70px">
                                            <td class="table-row-content">
                                                <img src="/theme/img/arrow_1.png"> <span>Delivery Method </span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="table-order-number text-dark text-end">{{$user_order->paymentTerms}}</td>
                                            <td class="table-order-number text-dark "> </td>
                                        </tr>
                                        <tr class="border-bottom" style="height:70px">
                                            <td class="table-row-content"> Total</td>
                                            <td></td>
                                            <td></td>
                                            <td class="table-order-number  text-end text-danger">{{'$' . round($user_order->total_including_tax , 2)}}</td>
                                            <td class="table-order-number text-dark"> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            No Order Found!
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>