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

                    <div class="mt-3 mb-3 pr-4 pl-4" id="orders">
                        <div class="col-md-12 border-bottom border-4 pb-4 p-0">
                            <img src="/theme/img/orders_main.png" style="margin: -6px 1px 1px 1px;">
                            <span class="pt-1 my-account-content-heading ">Orders</span>
                        </div>

                        @if(count($user_orders) > 0)

                        <table cellpadding="10" cellspacing="10" class="w-100" class="mt-3">
                            <tr class="order-table-heading border-bottom">
                                <td class="pl-0" style="width:90px;">Order</td>
                                <td style="width: 200px;">Date</td>
                                <td style="width: 185px">Status</td>
                                <td style="width:350px">Company</td>
                                <td style="width:350px">Total</td>
                                <td style="width:350px">Cin7 Status</td>
                                <td class="text-center pr-0" style="width:103px;">Action</td>
                            </tr>
                            <!-- <tr class="border-bottom ms-3 mr-3"></tr> -->
                            <tbody id="" class="">
                                @foreach($user_orders as $order)
                                <tr class="table-row-content border-bottom" id="{{$order->id}}">
                                    <td class="table-order-number pl-0" style="width: 12%;">
                                        {{'#' . ' ' . $order->id}}
                                    </td>
                                    
                                    <td>
                                        {{ \Carbon\Carbon::createFromTimestamp(strtotime( $order->createdDate))->format('F d, Y') }}
                                    </td>
                                    <td>
                                        {{$order->status}}
                                    </td>
                                    <td>
                                        {{$order->contact->company}}
                                    </td>
                                    <td>
                                        {{$order->total}}
                                    </td>
                                    <td id="status_{{$order->id}}">
                                        @if($order->order_id != '' || $order->order_id != null) 
                                        <span>
                                            Approved
                                        </span>
                                        @else
                                        <span>
                                            Pending
                                            <input type="hidden" id="verify_order_{{$order->id}}">
                                        </span> 
                                        @endif
                                    </td>
                                    <td class="pr-0">
                                        <a href="{{route('order_detail' , $order->id)}}" onmouseover="replaceEye({{$order->id}})" onmouseout="replaceEye2({{$order->id}})">
                                            <button class="btn btn-outline-success view-btn p-0" type="" style="width:100%;height:32px;">
                                                <img src="{{asset('/theme/img/eye.png')}}" class="mr-1 mb-1" id="eye_icon_{{$order->id}}">View
                                            </button>
                                        </a>
                                    </td>
                                   
                                    @if($can_approve_order && $order->isApproved != 1 && $order_approver_for_company === true)
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="({{$order->id}})" id="approve_{{$order->id}}">
                                            Approve
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="row">
                            <h3>
                                No Orders Found!
                            </h3>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
