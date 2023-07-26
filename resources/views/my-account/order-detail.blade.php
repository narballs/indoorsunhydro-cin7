@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 pt-4">
                    @include('my-account.my-account-side-bar')
                </div>
                <div class="col-md-12 my-4">
                    <span>
                        <a href="{{ url('my-account') }}" class="btn order_detail_page_order_btn">Order</a>
                    </span>
                    <span>
                        <img src="/theme/img/arow_order_detail.png" alt="">
                    </span>
                    <span>
                        <a href="#" class="btn order_detail_page_order_detail_btn">Order detail</a>
                    </span>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card order_detail_page_card">
                        <div class="card-header bg-white">
                            <p class="my_account_order_details_heading mb-0">Order Details</p>
                        </div>
                        <div class="card-header bg-white">
                            <div class="row">
                                <div class="col-md-2">
                                    <span class="my_account_order_details_page_date_title">
                                        ORDER PLACED
                                    </span><br>
                                    <span class="my_account_order_details_page_date_item">
                                        {{ $order_detail->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span class="my_account_order_details_page_date_order_id_title">
                                        ORDER
                                    </span><br>
                                    <span class="my_account_order_details_page_date_order_id_item">
                                        #{{ $order_detail->id }}
                                    </span>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="my_account_order_details_page_date_order_id_title">
                                        Status
                                    </span><br>
                                    <span class="shipping_to_my_account">
                                        @if($order_detail->order_id != null && $order_detail->isApproved == 1)
                                            <span class="badge badge-success">FullFilled</span>
                                        @elseif($order_detail->order_id == null && $order_detail->isApproved == 0)
                                            <span class="badge badge-primary">New</span>
                                        @elseif($order_detail->order_id == null && $order_detail->isApproved == 2)
                                            <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table address-table-items-data m-0 ps-2">
                                    <thead>
                                        <tr class="table-header-background">
                                            <td class="my_account_order_detail_page_table_title">PRODUCT</td>
                                            <td class="my_account_order_detail_page_table_title">SKU</td>
                                            <td class="my_account_order_detail_page_table_title">Quantity</td>
                                            <td class="my_account_order_detail_page_table_title">Item Price</td>
                                            <td class="my_account_order_detail_page_table_title">SubTotal</td>
                                        </tr>
                                    </thead>
                                    @php
                                        $total = 0;
                                        
                                        foreach ($order_detail->apiOrderItem as $orderItem) {
                                            $total += $orderItem->quantity * $orderItem->price;
                                        }
                                        
                                    @endphp
                                    <tbody>
                                        @if (count($order_detail->apiOrderItem) > 0)
                                            @foreach ($order_detail->apiOrderItem as $orderItem)
                                                <tr>
                                                    <td class="order_detail_page_product_name">
                                                        <a href="{{ url('product-detail/' . $orderItem->product->id . '/' . $orderItem->option_id . '/' . $orderItem->product->slug) }}"
                                                            class="btn order_detail_page_product_name">
                                                            {{ $orderItem->product->name }}
                                                        </a>
                                                    </td>
                                                    <td class="my_account_all_items">
                                                        {{ $orderItem->product->code }}
                                                    </td>
                                                    <td class="my_account_all_items">
                                                        {{ $orderItem->quantity }}
                                                    </td>
                                                    <td class="my_account_all_items">
                                                        ${{ number_format($orderItem->price, 2) }}
                                                    </td>
                                                    <td class="my_account_all_items">
                                                        ${{ number_format($orderItem->quantity * $orderItem->price , 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="order_detail_page_prices">
                                                    Subtotal
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    ${{ number_format($total, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="order_detail_page_prices">
                                                    Tax
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    @if(!empty($order_detail['texClasses']))
                                                        {{ $order_detail['texClasses']->name }}
                                                     @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="order_detail_page_prices">
                                                    Delivery Method
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail->paymentTerms }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="order_detail_page_prices">
                                                    Total
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    ${{number_format($order_detail->total_including_tax , 2)}}
                                                </td>
                                            </tr>
                                        @else
                                            @if(!empty($order_detail['apiOrderItem'][0]['product']) && !empty($order_detail['apiOrderItem'][0]['option']))
                                            <tr>
                                                <td class="order_detail_page_product_name">
                                                    <a class="btn order_detail_page_product_name "
                                                        href="{{ url('product-detail/' . $order_detail['apiOrderItem'][0]['product']->id . '/' . $order_detail['apiOrderItem'][0]['option_id'] . '/' . $order_detail['apiOrderItem'][0]['product']->slug) }}">
                                                        {{ $order_detail['apiOrderItem'][0]['product']->name }}
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail['apiOrderItem'][0]['product']->code }}
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail['apiOrderItem'][0]['quantity'] }}
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail['apiOrderItem'][0]['product']->price }}
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail->productTotal }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="my_account_address_items">
                                                    Subtotal
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail->productTotal }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="my_account_address_items">
                                                    Tax
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail['texClasses']->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="my_account_address_items">
                                                    Total
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
    
                                                </td>
                                                <td class="my_account_all_items">
                                                    {{ $order_detail->total_including_tax }}
                                                </td>
                                            </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-5">
                    <a href="{{ url('my-account') }}">
                        <img src="/theme/img/back_btn_order.png " alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
