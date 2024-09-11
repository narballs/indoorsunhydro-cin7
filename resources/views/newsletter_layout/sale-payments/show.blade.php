@extends('newsletter_layout.dashboard')
@section('content')
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
    <div class="col-md-12">
        @if (empty($sale_payment))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h6 summary"></h3>No Order Detail Found</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sale Payments</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9 p-3">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <span>Order Reference :</span>
                                    <strong>{{$sale_payment->orderRef}}</strong>
                                </div>
                                <div class="col-md-3">
                                    <span>
                                        Order Date : 
                                    </span>
                                    <span>
                                        <strong>{{ str_replace(['T', 'Z'], ' ', $sale_payment->order_created_date)}}</strong>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span>Order Status : </span>
                                    <span>
                                        <strong>
                                            {{ $sale_payment->status }}
                                        </strong>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span>Payment Method : </span>
                                    <span>
                                        <strong>
                                            {{ !empty($sale_payment->method) ? strtoupper($sale_payment->method) : ''}}
                                        </strong>
                                    </span>
                                </div>
                            </div>
                            <div class="card">
                                
                                <div class="">
                                    <table class="table">
                                        <thead>
                                            <tr class="background-color">
                                                <th class="">Line Items</th>
                                                <th class="">Sku</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Item Price</th>
                                                <th class="text-center">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="order-detail-tbody">
                                            
                                            @foreach($api_orders as $order_item)
                                                <tr class="border-bottom order_items_row">
                                                    <td class="align-middle">
                                                        <h6 class=" mb-0">
                                                            {{ $order_item->name }}
                                                        </h6>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="sku">{{ $order_item->code }}</span>
                                                    </td>
                                                    <td class="ms-2 align-middle d-flex justify-content-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="33" class="itemQuantityText mt-2" height="32" viewBox="0 0 33 32" fill="none">
                                                            <circle cx="16.5752" cy="15.8466" r="15.8466" fill="#E3F5F5"/>
                                                            <text x="50%" y="50%" text-anchor="middle" class="order-item-quantity itemQuantityText" stroke="#131313" stroke-width="" dy=".3em">{{ $order_item->qty }}</text>
                                                        </svg>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="order-item-price item_prices" >${{ number_format($order_item->unitCost, 2) }}</span>
                                                        <input type="text" value="{{ number_format($order_item->price , 2) }}" class="item_price_class form-control form-control-sm mx-auto w-75 h-auto p-1 text-center d-none" id="itemPrice_number_{{$order_item->id}}">
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="item_total" id="itemTotal_{{$order_item->id}}">${{number_format($order_item->unitPrice , 2)}} </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="border-bottom">
                                                <td colspan="4" class="add_colspan"><span class="">Subtotal</span></td>
                                                <td class="text-center"><span class="order-item-price" id="subtotal_text">${{ number_format($sale_payment->productTotal, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td colspan="4" class="add_colspan"><span class="">Discount</span></td>
                                                <td class="text-center"><span class="order-item-price" id="subtotal_text">${{ number_format($sale_payment->discountTotal, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td colspan="4" class="add_colspan"><span class="">Shipping</span></td>
                                                <td class="text-center">
                                                    <span class="order-item-price">${{ number_format($sale_payment->freightTotal, 2) }}</span>
                                                    
                                                    
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td colspan="4" class="add_colspan"><span class="">Shipping Method</span></td>
                                                <td class="text-center">
                                                    <span class="order-item-price">{{$sale_payment->freightDescription  === 'Pickup Order' ? 'Pickup Order' : 'Delievery' }}</span>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td colspan="4" class="add_colspan"><span class="">Added Tax</span></td>
                                                <td class="text-center"><span class="order-item-price" id="tax_text">${{ !empty($sale_payment->tax_rate) ? number_format($sale_payment->tax_rate , 2) : '0.00' }}</span>
                                                </td>
                                            </tr>
                                            <tr class="fw-bold">
                                                <td colspan="4" class="add_colspan"><span class="">GRAND TOTAL</span></td>
                                                <td class="text-center"><span class="order-grand-total" id="grand_total_text">${{ number_format($sale_payment->total, 2) }}</span></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5 class="">Shipping Address</h5>
                                            <p>
                                                @if (!empty($sale_payment->company ))
                                                    Company: <strong>{{ ucfirst($sale_payment->company) }}</strong><br>
                                                @endif
                                                {{ !empty($sale_payment->deliveryFirstName) ? $sale_payment->deliveryFirstName : '' }} {{ !empty($sale_payment->deliveryLastName) ? ' ' . $sale_payment->deliveryLastName : '' }}</strong><br>
                                                {{ !empty($sale_payment->deliveryAddress1) ? $sale_payment->deliveryAddress1 . ',' : '' }}
                                                {{ !empty($sale_payment->deliveryAddress2) ? $sale_payment->deliveryAddress2 . ',': ''}}<br>
                                                {{ !empty($sale_payment->deliveryCity) ? $sale_payment->deliveryCity . ',' : '' }}
                                                {{ !empty($sale_payment->deliveryState) ? $sale_payment->deliveryState . ',' : '' }}
                                                {{ !empty($sale_payment->deliveryPostalCode) ? $sale_payment->deliveryPostalCode . ',' : '' }}
                                                <p title="Phone" class="mb-0">P: {{ !empty($sale_payment->phone) ? '('. $sale_payment->phone . ')' : '' }}</p>
                                                <p title="email">{{ $sale_payment->email }}</p>
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <h5 class="">Billing address</h5>
                                            <p>
                                                @if (!empty($sale_payment->company ))
                                                    Company:  <strong>{{ ucfirst($sale_payment->company) }}</strong><br>
                                                @endif
                                                @if (!empty($sale_payment->billingFirstName))
                                                    {{ $sale_payment->billingFirstName }}
                                                @endif
                                                @if (!empty($sale_payment->billingLastName))
                                                    {{ ' '. $sale_payment->billingLastName }}
                                                @endif
                                                <br>
                                                @if(!empty($sale_payment->billingAddress1))
                                                    {{$sale_payment->billingAddress1 . ','}} <br>
                                                @endif
                                                @if(!empty($sale_payment->billingAddress2))
                                                    {{$sale_payment->billingAddress2 . ','}} 
                                                @endif
                                                @if(!empty($sale_payment->billingCity))
                                                    {{$sale_payment->billingCity . ','}}
                                                @endif
                                                @if(!empty($sale_payment->billingState))
                                                    {{$sale_payment->billingState . ','}}
                                                @endif
                                                @if(!empty($sale_payment->billingPostalCode))
                                                    {{$sale_payment->billingPostalCode}}
                                                @endif
                                                <p title="Phone" class="mb-0">P: {{ !empty($sale_payment->phone) ? '('. $sale_payment->phone . ')' : '' }}</p>
                                                <p title="email">{{ !empty($sale_payment->email) ? $sale_payment->email : '' }}</p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-3">
                            <div class="card  mb-3">
                                <div class="card-body">
                                    <h3 class="h6 summary-head"><strong>Internal Comments</strong></h3>
                                    <span class="delievery">{{ $sale_payment->internalComments }}</span></p>
                                </div>
                            </div>
                            <div class="card  mb-3">
                                <div class="card-body">
                                    <h3 class="h6 summary-head"><strong>Delievery Instructions</strong></h3>
                                    <span class="delievery">{{ !empty($sale_payment->deliveryInstructions) ? $sale_payment->deliveryInstructions : '' }}</span></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection