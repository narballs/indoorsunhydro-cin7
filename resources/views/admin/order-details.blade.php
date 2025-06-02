@extends('adminlte::page')
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('title', 'Dashboard')

@section('content_header')
    <h1>Order Detail</h1>
@stop
@section('content')
    <!-- sdfkjlsdkfjsdlkfk -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="row">
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
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible success_text_div d-none">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="success_text text-white"></span>
            </div>
        </div>
        <div class="col-md-9 d-flex mb-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h6 class="mb-0">
                            @if ($order->is_stripe == 1 &&  $order->isApproved == 3)
                                <span class="text-info">
                                    This order is processed through stripe , and is refunded.
                                </span>
                            @else
                                @if ($order->is_stripe == 1 && strtolower($order->payment_status) == 'unpaid')
                                    <span class="text-danger">This order is processed through stripe. But we are unable to verify payment.</span>
                                @endif
                            @endif
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 ">
                                    <form class="mb-0">
                                        @csrf
                                        <input type="hidden" value="{{ $time_diff }}" id="timeSpanToCancel">
                                        <input type="hidden" value="{{ $time_difference_seconds }}" id="seconds">
                                        @if ($order->isApproved == 2)
                                            <button type="button" class="btn btn-danger btn-sm" disabled>Cancelled</button>
                                            <div class="countdown">
                                            </div>
                                        @elseif($order->isApproved == 1 || $time_diff > 3)
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    Cancel Order
                                                </button>
                                            </div>
                                        @else
                                            <div class="col-md-10">
                                                <input type="hidden" value="{{ $orderitems[0]['order_id'] }}"
                                                    id="order_id">
                                                <input class="btn btn-danger btn-sm" type="button" value="Cancel Order"
                                                    id="cancel_order" onclick=" cancelOrder(); addComment(0);">
                        
                                            </div>
                                            <div class="countdown"></div>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form class="mb-0">
                                        @csrf
                                        @if ($order->isApproved == 1 )
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    Fullfilled
                                                </button>
                                            </div>
                                        @elseif ($order->isApproved == 2)
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    Not Fullfilled
                                                </button>
                                            </div>
                                        @elseif ($order->is_stripe == 1 && strtolower($order->payment_status) == 'unpaid')
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    Fullfilled
                                                </button>
                                            </div>
                                        @elseif ($order->is_stripe == 1 && strtolower($order->payment_status) == 'refunded')
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    Fullfilled
                                                </button>
                                            </div>
                        
                                        @elseif ($order->isApproved == 0 && $auto_fullfill == true )
                                            <div class="col-md-12">
                                                <input id="full_fill" class="btn btn-primary btn-sm " type="button"
                                                    value="Fullfill Order" disabled>
                                            </div>
                                            <div class="spinner-border d-none" role="status" id="spinner">
                                                <span class="sr-only" style="margin-left: 227px">Activating...</span>
                                            </div>
                                         @elseif ($order->isApproved == 0 && $auto_fullfill == false )
                                            <div class="col-md-12">
                                                <input id="full_fill" class="btn btn-primary btn-sm" type="button"
                                                    value="Fullfill Order" onclick="fullFillOrder()">
                                            </div>
                                            <div class="spinner-border d-none" role="status" id="spinner">
                                                <span class="sr-only" style="margin-left: 227px">Activating...</span>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                                @if (!empty($order->buylist_id) && ($order->label_created == 0) && ($order->is_shipped == 0) && empty($order->shipstation_orderId) && $order->isApproved == 1)
                                    <div class="col-md-5 ">
                                        @if (
                                                (!empty($order->DeliveryAddress1) || !empty($order->DeliveryAddress2)) &&
                                                (App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress1) ||
                                                App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress2))
                                            )
                                        
                                            <button type="button" 
                                                    class="btn btn-primary send_po_box_wholesale_order_to_shipstation btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#send_po_box_wholesale_order_to_shipstation" 
                                                    id="send_po_box_wholesale_order_shipstation" 
                                                    data-id="{{ $order->id }}">
                                                    Send Order to Shipstation
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-primary badge_wholesale send_buy_list_order_to_shipstation btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#send_buy_list_order_to_shipstation" 
                                                    id="send_buy_list_order_to_shipstation_btn" 
                                                    data-id="{{ $order->id }}">
                                                Send to Shipstation
                                            </button>
                                        @endif
                                    </div>
                                    @elseif ( $order->is_stripe == 1 && $order->shipstation_orderId == null && $order->payment_status == 'paid' && $order->isApproved == 1 && floatval($order->shipment_price) <= 0)
                                        <div class="col-md-5 ">
                                            @if (
                                                    (!empty($order->DeliveryAddress1) || !empty($order->DeliveryAddress2)) &&
                                                    (App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress1) ||
                                                    App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress2))
                                                )
                                            
                                                <button type="button" 
                                                        class="btn btn-primary send_po_box_wholesale_order_to_shipstation btn-sm" 
                                                        data-toggle="modal" 
                                                        data-target="#send_po_box_wholesale_order_to_shipstation" 
                                                        id="send_po_box_wholesale_order_shipstation" 
                                                        data-id="{{ $order->id }}">
                                                        Send Order to Shipstation
                                                </button>
                                            @else
                                            
                                                <button type="button" 
                                                        class="btn btn-primary badge_wholesale send_wholesale_order_to_shipstation btn-sm" 
                                                        data-toggle="modal" 
                                                        data-target="#send_wholesale_order_to_shipstation" 
                                                        id="send_wholesale_order_shipstation" 
                                                        data-id="{{ $order->id }}">
                                                    Send to Shipstation
                                                </button>
                                            @endif
                                        </div>
                                    @elseif ( $order->is_stripe == 1 && $order->shipstation_orderId == null && $order->payment_status == 'paid' && $order->isApproved == 1 && floatval($order->shipment_price) > 0)
                                    <div class="col-md-5 ">
                                        @if (
                                                (!empty($order->DeliveryAddress1) || !empty($order->DeliveryAddress2)) &&
                                                (App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress1) ||
                                                App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress2))
                                            )
                                        
                                            <button type="button" 
                                                    class="btn btn-primary send_po_box_wholesale_order_to_shipstation btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#send_po_box_wholesale_order_to_shipstation" 
                                                    id="send_po_box_wholesale_order_shipstation" 
                                                    data-id="{{ $order->id }}">
                                                    Send Order to Shipstation
                                            </button>
                                        @else
                                        
                                            <form action="{{route('send_order_to_shipstation')}}" class="" method="post" class="mb-0">
                                                @csrf
                                                <div class="col-md-12">
                                                    <input type="hidden" name="order_id" id="" value="{{$order->id}}">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Send Order to Shipstation
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                    @elseif ( $order->is_stripe == 0 && $order->shipstation_orderId == null  && $order->isApproved == 1)
                                    <div class="col-md-5 ">
                                        @if (
                                                (!empty($order->DeliveryAddress1) || !empty($order->DeliveryAddress2)) &&
                                                (App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress1) ||
                                                App\Helpers\SettingHelper::startsWithPOBox($order->DeliveryAddress2))
                                            )
                                        
                                            <button type="button" 
                                                    class="btn btn-primary send_po_box_wholesale_order_to_shipstation btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#send_po_box_wholesale_order_to_shipstation" 
                                                    id="send_po_box_wholesale_order_shipstation" 
                                                    data-id="{{ $order->id }}">
                                                    Send Order to Shipstation
                                            </button>
                                        @else
                                        
                                            <button type="button" 
                                                    class="btn btn-primary badge_wholesale send_wholesale_order_to_shipstation btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#send_wholesale_order_to_shipstation" 
                                                    id="send_wholesale_order_shipstation" 
                                                    data-id="{{ $order->id }}">
                                                Send to Shipstation
                                            </button>
                                        @endif
                                    </div>
                               

                                @endif
                                @if ($order->confirmation_email_flag == 0 && $order->is_stripe == 1)
                                    <form action="{{route('send_confirmation_email')}}" class="" method="post" class="mb-0 d-none">
                                        @csrf
                                        <div class="col-md-12">
                                            <input type="hidden" name="order_id" id="" value="{{$order->id}}">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Send Order Confirmation Email
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress border d-none w-50 mb-2" id="progress-bar">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
        </div>
    </div>
    <div class="bg-success text-white text-center w-50 mb-2" id="fullfill_success"></div>
    <div class="bg-warning text-white text-center w-50 mb-2" id="fullfill_failed"></div>
    
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" value="{{$order->id}}" name="order_id" id="orderID">
                @if(!empty($tax_class))
                <input type="hidden" value="{{$tax_class->rate}}" name="tax_rate" id="tax_rate">
                @else
                <input type="hidden" value="0" name="tax_rate" id="tax_rate">
                @endif
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="d-flex card-header">
                               <div class="col-md-12">
                                    <div class="spinner-border text-warning order-status-spinner d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Order Placed</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="">{{ $formatedDate }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Order</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="order_number">#{{ $order->id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Payment Gateway</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="text-info">{{ !empty($order->is_stripe) && $order->is_stripe ==  1 ? 'Stripe' : 'None' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Status</span>
                                                </div>
                                                <div class="col-md-12">
                                                    @if ($order->isApproved == 1 && $order->isVoid == 1)
                                                    <span class="text-secondary">Void</span>
                                                    @elseif ($order->isApproved == 0 && $order->isVoid == 0)
                                                        <span class="text-warning">New</span>
                                                    @elseif ($order->isApproved == 1)
                                                        <span class="text-success">Fullfilled</span>
                                                    @elseif ($order->isApproved == 2)
                                                        <span class="text-danger">Cancelled</span>
                                                    @elseif ($order->isApproved == 3)
                                                        <span class="text-info">Refunded</span>
                                                    @elseif ($order->isApproved == 4)
                                                        <span class="text-info">Partially Refunded</span>
                                                    @elseif ($order->isApproved == 5)
                                                        <span class="text-info">Pending</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1 p-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Payment Status</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="text-primary">{{strtoupper($order->payment_status)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 order-status-drop-down d-none">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Order Statuses</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <select name="order_status_id" id="order_status_id" class="p-1 order-status-select" onchange="update_order_status('{{$order->id}}' , '{{'paid'}}')">
                                                        @if(count($order_statuses) > 0)
                                                            @if(!empty($order->order_status_id))
                                                                @foreach($order_statuses as $order_status)
                                                                    <option value="{{$order_status->id}}" {{(!empty($order->order_status_id) &&  $order->order_status_id == $order_status->id) ? 'selected' : ''}}>{{$order_status->status}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Select Status</option>
                                                                @foreach($order_statuses as $order_status)
                                                                    <option value="{{$order_status->id}}">{{$order_status->status}}</option>
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            <option value="">No Statuses</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if(($order->isApproved == 0 || $order->isApproved == 1  || $order->isApproved == 2 || $order->isApproved == 4)   && $order->isVoid == 0)
                                        <div class="col-md-2 d-flex align-items-center justify-content-end edit_order_div">
                                            <button class="btn btn-light btn-sm edit_admin_order" type="button" onclick="edit_order('{{ $order->id }}')">
                                                    Edit Order
                                            </button>
                                        </div>
                                        @endif

                                        <div class="col-md-2 d-none edit-order-butttons align-items-center justify-content-end mt-3 pt-1">
                                            <button class="btn btn-light btn-sm cancel_order_changes mx-3" type="button" onclick="cancel_order_changes('{{ $order->id }}')">
                                                   Cancel
                                            </button>
                                            <button class="btn btn-primary btn-sm text-white btn-sm update_order border-0" type="button" onclick="update_order('{{ $order->id }}')">
                                                   Save
                                            </button>
                                        </div>
                                </div>
                               </div>
                                <div class="d-flex">
                                    <button class="btn btn-link p-0 me-3 d-none btn-icon-text"><i class="bi bi-download"></i> <span class="text">Invoice</span></button>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0 text-muted" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i>
                                                    Edit</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i>
                                                    Print</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="">
                                <table class="table">
                                    <thead>
                                        <tr class="background-color">
                                            <th class="pl-4">Line Items</th>
                                            <th class="pl-4">Sku</th>
                                            <th class="text-center">Quantity</th>
                                            <th id="delete_item_head" class="d-none text-center">Delete Item</th>
                                            <th class="text-center">Item Price</th>
                                            <th class="text-center">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="order-detail-tbody">
                                        @php
                                            // $tax=0;
                                            // $tax_rate = 0;
                                            // $subtotal = 0;
                                            // $tax_without_discount = 0;
                                            // $subtotal = $order->total;
                                            // $discount_amount = $order->discount_amount;
	                                        // if (isset($discount_variation_value) && !empty($discount_variation_value) && $discount_amount > 0) {
                                            //     $discount_variation_value = $discount_variation_value;
                                            //     if (!empty($tax_class)) {
                                            //         $tax_rate = $tax_class->rate;
                                            //         $tax_without_discount = $subtotal * ($tax_rate / 100);
                                            //         if (!empty($discount_variation) && $discount_variation == 'percentage') {
                                            //             $tax = $tax_without_discount - ($tax_without_discount * ($discount_variation_value / 100));
                                            //         } else {
                                            //             $tax = $tax_without_discount - $discount_variation_value;
                                            //         }
                                            //     }

                                            // } else {
                                            //     if (!empty($tax_class)) {
                                            //         $tax_rate = $tax_class->rate;
                                            //         $tax = $subtotal * ($tax_rate / 100);
                                            //     }
                                            // }  
                                        @endphp
                                        @foreach ($orderitems as $item)
                                            @foreach($item->product->options as $option)
                                                <tr class="border-bottom order_items_row" id="row_{{$item->id}}">
                                                    <td class="align-middle">
                                                        <div class="d-flex mb-2">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ $item->product->images }}" alt=""
                                                                    width="35" class="img-fluid">
                                                            </div>
                                                            <div class="d-flex align-items-center pl-3">
                                                                <h6 class="small mb-0"><a href="{{url('product-detail/'.$item->product->id.'/'.$option->option_id.'/'.$item->product->slug)}}" class="p_name_order">{{ $item->Product->name }}</a></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="sku">{{ $item->product->code }}</span>
                                                    </td>
                                                    <td class="ms-2 align-middle d-flex justify-content-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="33" class="itemQuantityText mt-2" height="32" viewBox="0 0 33 32" fill="none">
                                                            <circle cx="16.5752" cy="15.8466" r="15.8466" fill="#E3F5F5"/>
                                                            <text x="50%" y="50%" text-anchor="middle" class="order-item-quantity itemQuantityText" stroke="#131313" stroke-width="" dy=".3em" id="itemQuantityText_{{$item->id}}">{{ $item->quantity }}</text>
                                                        </svg>
                                                        <div class="itemQuantityDiv d-none">
                                                            <button class="border-add mt-1 border-right-btn" type="button" onclick="increaseQuantity('{{ $item->id }}')">
                                                                <i class="fa fa-angle-up"></i>
                                                            </button>
                                                            <input type="text" min="1" class="itemQuantity form-control form-control-sm w-25 h-auto p-1 mt-1 input_qty text-center" value="{{ $item->quantity}}" data-id="{{$item->id}}" id="itemQuantity_number_{{$item->id}}" onchange="change_quantity('{{$item->id}}')">
                                                            <button class="border-add mt-1 border-left-btn" type="button" onclick="decreaseQuantity('{{ $item->id }}')">
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="delete_item_body d-none text-center align-middle">
                                                        <button class="btn btn-danger btn-sm border-0 rounded-circle delete-item-button" onclick="deleteItem({{ $item->id }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                                <g clip-path="url(#clip0_801_438)">
                                                                <path d="M3.33301 5.83325H16.6663" stroke="#DC4E41" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M8.33301 9.16675V14.1667" stroke="#DC4E41" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M11.667 9.16675V14.1667" stroke="#DC4E41" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M4.16699 5.83325L5.00033 15.8333C5.00033 16.2753 5.17592 16.6992 5.48848 17.0118C5.80104 17.3243 6.22496 17.4999 6.66699 17.4999H13.3337C13.7757 17.4999 14.1996 17.3243 14.5122 17.0118C14.8247 16.6992 15.0003 16.2753 15.0003 15.8333L15.8337 5.83325" stroke="#DC4E41" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M7.5 5.83333V3.33333C7.5 3.11232 7.5878 2.90036 7.74408 2.74408C7.90036 2.5878 8.11232 2.5 8.33333 2.5H11.6667C11.8877 2.5 12.0996 2.5878 12.2559 2.74408C12.4122 2.90036 12.5 3.11232 12.5 3.33333V5.83333" stroke="#DC4E41" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </g>
                                                                <defs>
                                                                <clipPath id="clip0_801_438">
                                                                <rect width="20" height="20" fill="white"/>
                                                                </clipPath>
                                                                </defs>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="order-item-price item_prices" id="itemPrice_{{$item->id}}">${{ number_format($item->price, 2) }}</span>
                                                        <input type="text" value="{{ number_format($item->price , 2) }}" class="item_price_class form-control form-control-sm mx-auto w-75 h-auto p-1 text-center d-none" id="itemPrice_number_{{$item->id}}">
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="item_total" id="itemTotal_{{$item->id}}">${{number_format($item->price * $item->quantity , 2)}} </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-bottom">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">Subtotal</span></td>
                                            <td class="text-center"><span class="order-item-price" id="subtotal_text">${{ number_format($order->total, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">Discount</span></td>
                                            <td class="text-center">
                                                <span class="order-item-price" id="subtotal_text">
                                                    {{-- ${{ number_format($order->discount_amount, 2) }} --}}
                                                    @if (!empty($order->buylist_id))
														${{!empty($order->buylist_discount) ?  number_format($order->buylist_discount, 2) : '0.00' }}
													@else
														${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}
													@endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">Shipping</span></td>
                                            <td class="text-center"><span class="order-item-price">${{ number_format($order->shipment_price, 2) }}</span></td>
                                        </tr>
                                        {{-- <tr class="border-bottom">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">ParcelGuard</span></td>
                                            <td class="text-center"><span class="order-item-price">${{ number_format($order->parcel_guard, 2) }}</span></td>
                                        </tr> --}}
                                        <tr class="border-bottom">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">Added Tax</span></td>
                                            <td class="text-center"><span class="order-item-price" id="tax_text">${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td colspan="4" class="add_colspan"><span class="summary-head mx-2">GRAND TOTAL</span></td>
                                            <input type="hidden" name="grand_total_value" class="grand_total_value" id="grand_total_value" value="{{ number_format($order->total_including_tax, 2) }}">
                                            <td class="text-center"><span class="order-grand-total" id="grand_total_text">${{ number_format($order->total_including_tax, 2) }}</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                {{-- <div class="add_product_row dropdown-order col-md-4 d-none">
                                    <input type="text" placeholder="Add Products" id="myInput-order" name="search_products" onkeyup="searchProducts()">
                                    <div class="product-list">
                                        <ul class="prd_ul">

                                        </ul>
                                    </div>
                                </div> --}}
                                <div class="add_product_row dropdown-order col-md-4 d-none mx-2 mb-2">
                                    <div class="has-search ">
                                        <span class="fa fa-search form-control-feedback"></span>
                                            <input type="text" class="form-control" id="myInput-order" name="search_products"
                                                placeholder="Add Products" onkeyup="searchProducts()" />
                                            <ul class="prd_ul p-0 d-none">
    
                                            </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="h6 summary-head">Delivery Method</h3>
                                        <span class="delivery">
                                            @if (!empty($order->logisticsCarrier ))
                                                    @if (strtolower($order->logisticsCarrier) === 'delivery')
                                                        <span class="badge badge-success p-1">Delivery</span>
                                                    @elseif (strtolower($order->logisticsCarrier) === 'pickup order')
                                                        <span class="badge badge-warning p-1">Pickup Order</span>
                                                    @else
                                                        {{ $order->logisticsCarrier }}
                                                    @endif
                                                @else
                                                {{ $order->logisticsCarrier }}
                                            @endif
                                        </span>

                                        <span class="shipping_service_code"> 
                                            @if (!empty($order->shipping_service_code ))
                                                @if (strtolower($order->logisticsCarrier) !== 'pickup order')
                                                    @if (strtolower($order->shipping_service_code) == 'standard')
                                                        <span class="badge badge-primary p-1">Seko LTL (Standard)</span>
                                                    @else
                                                        <span class="badge badge-primary p-1">{{ strtoupper(str_replace('_', ' ', $order->shipping_service_code)) }}</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </span> <br/>

                                        {{-- add tracking url --}}

                                        @if (!empty($order->tracking_number) && $order->is_shipped == 1 && $order->label_created == 1 && $order->shipstation_orderId != null && $order->shipping_carrier_code == 'ups_walleted')
                                            <a href="https://www.ups.com/track?HTMLVersion=5.0&Requester=NES&AgreeToTermsAndConditions=yes&loc=en_US&tracknum={{ $order->tracking_number }}" target="_blank" class="text-primary">Track Order</a>
                                        @endif



                                    </div>
                                    <div class="col-lg-6">
                                        <h3 class="h6 summary-head">Billing address</h3>
                                        <address>
                                            @if (!empty($order->BillingCompany))
                                                Company:<strong>{{ ucfirst($order->BillingCompany) }}</strong><br>
                                            @elseif (!empty($customer->contact->company ))
                                            Company:<strong>{{ ucfirst($customer->contact->company) }}</strong><br>
                                            @endif

                                            {{!empty($order->BillingFirstName) ? $order->BillingFirstName : $customer->contact->firstName }}&nbsp;{{ !empty($order->BillingLastName) ? $order->BillingLastName : $customer->contact->lastName }}<br>
                                            @if (!empty($order->BillingAddress1))
                                                {{ $order->BillingAddress1 . ',' }}
                                            @elseif(!empty($customer->contact->postalAddress1))
                                                {{$customer->contact->postalAddress1 . ','}} 
                                            @endif

                                            @if (!empty($order->BillingAddress2))
                                                {{ $order->BillingAddress2 . ',' }}<br>
                                            @elseif(!empty($customer->contact->postalAddress2))
                                                {{$customer->contact->postalAddress2 . ','}} <br>
                                            @endif

                                            @if (!empty($order->BillingCity))
                                                {{ $order->BillingCity . ',' }}<br>
                                            @elseif(!empty($customer->contact->postalCity))
                                                {{$customer->contact->postalCity . ','}}
                                            @endif

                                            @if (!empty($order->BillingState))
                                                {{ $order->BillingState . ',' }}<br>
                                            @elseif(!empty($customer->contact->postalState))
                                                {{$customer->contact->postalState . ','}}
                                            @endif

                                            @if (!empty($order->BillingZip))
                                                {{ $order->BillingZip }}<br>
                                            @elseif(!empty($customer->contact->postalPostCode))
                                                {{$customer->contact->postalPostCode}}
                                            @endif
                                            @if (!empty($order->BillingPhone))
                                                <p title="Phone" class="mb-0">P: {{ '('. $order->BillingPhone . ')' }}</p>
                                            @else
                                            <p title="Phone" class="mb-0">P: {{ !empty($customer->contact->mobile) ? '('. $customer->contact->mobile . ')' : '('. $customer->contact->phone . ')' }}</p>
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Customer Notes -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h3 class="h6" style="margin-bottom: 0px;"><strong>Order Notes</strong></h3>
                                    @if (!empty($order->order_id))
                                        <a href="{{url('/admin/get-cin7-payment-logs?search='.$order->order_id)}}" class="btn btn-primary btn-sm float-end mb-2 text-white">Payment Logs</a>
                                    @endif
                                </div>
                                @foreach ($orderComment as $comment)
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    <p>{{ $user->first_name }} {{ $user->last_name }} {{ $comment->comment }}</p>
                                    <p class="mb-0 text-bold">Date : 
                                        <i>
                                            {{ $comment->created_at }}
                                        </i>
                                    </p>
                                @endforeach

                            </div>
                        </div>
                        {{-- <div class="col-lg-12">
                            <form method="POST" id="order_notes" name="order_notes">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1" class="ms-2">Add Order Notes</label>
                                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                                    <input class="btn btn-primary mt-2" type="button" value="Add Notes"
                                        onclick="addComment(1)">
                                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                                </div>

                            </form>
                        </div> --}}
                        {{-- <div class="col-lg-12">
                            <form method="POST" id="order_notes" name="order_notes">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1" class="ms-2">Fullfil</label>
                                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                                    <input class="btn btn-primary mt-2" type="button" value="Add Notes"
                                        onclick="fullFillOrder()">
                                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                                </div>

                            </form>
                        </div> --}}

                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="h6 summary-head"><strong>Internal Comments</strong></h3>
                                @php
                                    $pick_disclaimer = 'Customer must present the exact physical credit card used during purchase ' . 
                                    (!empty($order->card_number) ? $order->card_number : '') . 
                                    ' and must show a physical ID card of the owner of the credit card to pick up the order. No exceptions.
                                    As an extra layer of fraud prevention, call the customer to see if they actually placed an order for the item being picked up, just to see how they respond to the inquiry or even if they pick up the call.';
                                @endphp
                                <span class="delivery">
                                    {{ !empty($order->logisticsCarrier) && $order->is_stripe == 1 &&  strtolower($order->logisticsCarrier) === 'pickup order' 
                                        ? $order->internal_comments . ' ' . $pick_disclaimer 
                                        : $order->internal_comments }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card  mb-3">
                            <div class="card-body">
                                <h3 class="h6 summary-head"><strong>Delivery Instructions</strong></h3>
                                <p><span class="delivery">{{ $order->date }}</span></p>
                                <p><span class="delivery">{{ $order->memo }}</span></p>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <!-- Shipping information -->
                            <div class="card-body">
                                <h3 class="h6">Shipping Information</h3>

                                <hr>
                                <address>
                                    @if (!empty($order->DeliveryCompany))
                                        Company:<strong>{{ ucfirst($order->DeliveryCompany) }}</strong><br>
                                    @elseif (!empty($customer->contact->company ))
                                        Company:<strong>{{ ucfirst($customer->contact->company) }}</strong><br>
                                    @endif
                                        
                                    {{!empty($order->DeliveryFirstName) ? $order->DeliveryFirstName : $customer->contact->firstName }}&nbsp;{{ !empty($order->DeliveryLastName) ? $order->DeliveryLastName : $customer->contact->lastName }}<br>
                                    
                                    @if (!empty($order->DeliveryAddress1))
                                        {{ $order->DeliveryAddress1 . ',' }}
                                    @elseif(!empty($customer->contact->address1))
                                        {{$customer->contact->address1 . ','}} 
                                    @endif

                                    @if (!empty($order->DeliveryAddress2))
                                        {{ $order->DeliveryAddress2 . ',' }}<br>
                                    @elseif(!empty($customer->contact->address2))
                                        {{$customer->contact->address2 . ','}}<br>
                                    @endif

                                    @if (!empty($order->DeliveryCity))
                                        {{ $order->DeliveryCity . ',' }}
                                    @elseif(!empty($customer->contact->city))
                                        {{$customer->contact->city . ','}}
                                    @endif

                                    @if (!empty($order->DeliveryState))
                                        {{ $order->DeliveryState . ',' }}
                                    @elseif(!empty($customer->contact->state))
                                        {{$customer->contact->state . ','}}
                                    @endif

                                    @if (!empty($order->DeliveryZip))
                                        {{ $order->DeliveryZip }}<br>
                                    @elseif(!empty($customer->contact->postCode))
                                        {{$customer->contact->postCode}}
                                    @endif

                                    @if (!empty($order->DeliveryPhone))
                                        <p title="Phone" class="mb-0">P: {{ '('. $order->DeliveryPhone . ')' }}</p>
                                    @else
                                        <p title="Phone" class="mb-0">P: {{ !empty($customer->contact->mobile) ? '('. $customer->contact->mobile . ')' : '('. $customer->contact->phone . ')' }}</p>
                                    @endif
                                    <p title="Phone">{{ $customer->contact->email }}</p>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="partial_refund" tabindex="-1" role="dialog" aria-labelledby="partial_refundLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="partial_refundLabel">Partial Refund</h5>
              {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> --}}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="pop_up_grand_total" class="">Grand Total</label>
                    <input type="text" value="" class="form-control" name="grand_popup_total_text" id="grand_popup_total_text" readonly>
                    
                </div>
                <div class="form-group">
                    <label for="pop_up_grand_total" class="">Refund Value</label>
                    <input type="number" name="" class="form-control" id="pop_up_grand_total" step="any" value="" max="" min="0">
                    <div class="error_message_parial_refund">
                        <span class="text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="spinner-border text-warning partial-order-status-spinner d-none mx-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" class="btn btn-secondary mx-2" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="partial_refund_ftn('{{$order->id}}')">Save changes</button>
                </div>
            </div>
          </div>
        </div>
    </div>

    {{-- full refund  adding a reason--}}
    <div class="modal fade" id="refundReasonModal" tabindex="-1" aria-labelledby="refundReasonModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="refundReasonModalLabel">Refund Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> --}}
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Please provide a reason for the refund:</label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" rows="4" required></textarea>
                        <div class="error_message_refund">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="full_refund_btn('{{$order->id}}')">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- full refund  end--}}

    <div class="modal fade" id="send_po_box_wholesale_order_to_shipstation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="send_po_box_wholesale_order_to_shipstation" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('send_po_box_wholesale_order_to_shipstation') }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Send Order To Shipstation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <div class="modal-body">
                    <input type="hidden" id="wholesale_po_box_order_id" name="order_id" value="">
                    <input type="hidden" name="carrier_code" id="carrier_code_po_box" value="{{ !empty($po_box_carrier_code) && !empty($po_box_carrier_code->option_value)  ? $po_box_carrier_code->option_value : '' }}">
                    <input type="hidden" name="service_code" id="service_code_po_box" value="{{ !empty($po_box_service_code) && !empty($po_box_service_code->option_value)  ? $po_box_service_code->option_value : '' }}">
                    
                    @if (empty($po_box_carrier_code) || empty($po_box_service_code))
                        <div class="alert alert-danger" role="alert">
                            PO Box shipping method is not configured in the system. Please contact the administrator.
                        </div>
                    @else
                        <div class="form-group">
                            <p>
                                @if (!empty($po_box_order_shipping_text) && !empty($po_box_order_shipping_text->option_value))
                                    {{ $po_box_order_shipping_text->option_value }}
                                @endif
                            </p>
                        </div>
                        <div id="shipstation_loader_PO" class="text-center mt-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Sending...</span>
                            </div>
                            <div>Sending to ShipStation...</div>
                        </div>
                    @endif
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </div>
          </form>
        </div>
    </div>


    {{-- added wholesale buylist  --}}
    <div class="modal fade" id="send_wholesale_order_to_shipstation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="send_wholesale_order_to_shipstation" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('send_wholesale_order_to_shipstation') }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Send Wholesale Order To Shipstation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="wholesale_order_id" name="order_id" value="">
                <input type="hidden" name="carrier_code" id="carrier_code_wholesale" value="">
                <input type="hidden" name="service_code" id="service_code_wholesale" value="">
                <div class="form-group">
                  <label for="shipping_method">Select Shipping Method</label>
                  <select class="form-control" name="shipping_method_wholesale" required>
                    @if(count($shipping_quotes) > 0)
                        <option value="">Select Shipping Method</option>
                        @foreach($shipping_quotes as $quote)
                            <option value="{{ $quote->service_code . ' _and_ ' . $quote->carrier_code}}">{{ $quote->service_name }}</option>
                        @endforeach
                        <option value="{{ 'standard' . ' _and_ ' . 'seko_ltl_walleted'}}">Seko ltl Walleted</option>
                    @else
                        <option value="">No Shipping Method Available</option>
                    @endif
                    <!-- Add more options if needed -->
                  </select>
                </div>
                <div id="shipstation_loader" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                      <span class="sr-only">Sending...</span>
                    </div>
                    <div>Sending to ShipStation...</div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </div>
          </form>
        </div>
    </div>

    {{-- buy_list --}}
    <div class="modal fade" id="send_buy_list_order_to_shipstation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="send_buy_list_order_to_shipstation" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('send_buy_list_order_to_shipstation') }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Send Order To Shipstation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="buylist_order_id" name="order_id" value="">
                <input type="hidden" name="carrier_code" id="carrier_code_buy_list" value="">
                <input type="hidden" name="service_code" id="service_code_buy_list" value="">
                <div class="form-group">
                  <label for="shipping_method">Select Shipping Method</label>
                  <select class="form-control" name="shipping_method_buy_list" required>
                    @if(count($shipping_quotes) > 0)
                        <option value="">Select Shipping Method</option>
                        @foreach($shipping_quotes as $quote)
                            <option value="{{ $quote->service_code . ' _and_ ' . $quote->carrier_code}}">{{ $quote->service_name }}</option>
                        @endforeach
                        <option value="{{ 'standard' . ' _and_ ' . 'seko_ltl_walleted'}}">Seko ltl Walleted</option>
                    @else
                        <option value="">No Shipping Method Available</option>
                    @endif
                    <!-- Add more options if needed -->
                  </select>
                </div>
                <div id="shipstation_loader" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                      <span class="sr-only">Sending...</span>
                    </div>
                    <div>Sending to ShipStation...</div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </div>
          </form>
        </div>
    </div>

    
@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">

    <style type="text/css">
        .order-status-select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        } 
        .order-status-select:focus-visible {
            outline: none;
        }
        .itemQuantityDiv {
            display: flex;
            justify-content: center;
        }
        .input_qty {
            border-radius: 0px !important;
        }
        .border-add {
            border: 1px solid #DDE2E4 !important;
        }
        .border-left-btn {
            border-left: none !important;
            border-top-right-radius:4px !important;
            border-bottom-right-radius:4px !important;
        }
        .border-right-btn {
            border-right: none !important;
            border-top-left-radius:4px !important;
            border-bottom-left-radius:4px !important;
        }
       .all-products {
            cursor: pointer;
       }
        /* The search field */
        #myInput-order {
        box-sizing: border-box;
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 16px;
        padding: 0.5rem 2rem;
        border: none;
        border-bottom: 1px solid #ddd;
        width: 100%;
        }

        /* The search field when it gets focus/clicked on */
        #myInput-order:focus {outline: 3px solid #ddd;}

        /* The container <div> - needed to position the dropdown content */
        .dropdown-order {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .prd_ul {
            background-color: #f6f6f6;
            min-width: 230px;
            border: 1px solid #ddd;
            z-index: 1;
            left: 35%;
            top: 1%;
            overflow-y: auto;
            max-height: 15rem;
        }

        /* Links inside the dropdown */
        .prd_ul li{
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
           
        }

        /* Change color of dropdown links on hover */
        .prd_ul li:hover {background-color: #f1f1f1}

        /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
        .show-order-result {display:block;}
        #search_product_result {
            border: 1px solid #ced4da;
        }
        .suggestion_box {
            list-style: none;
            text-decoration: none
        }
        .delete-item-button {
            background: rgba(220, 78, 65, 0.13);
        }
        .delete-item-button:hover {
            background: rgba(220, 78, 65, 0.13);
        }

        .delete-item-button:active {
            background-color: rgba(220, 78, 65, 0.13) !important;
        }

        .delivery {
            color: #242424;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 26.55px; /* 189.643% */
        }
        .border-bottom {
            border-bottom: 1px solid #E1E1E1;
        }
        .order-head {
            color: #7D7D7D;
            font-size: 12px;
            font-style: normal;
            font-weight: 400;
            line-height: 19.606px; /* 163.385% */
        }
        .background-color {
            background-color: #FAFAFA;
        }
        .summary-head {
            color: #242424;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .order-grand-total {
            color: #7BC533;
            font-size: 15.847px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }
        .order-item-quantity {
            color: #131313;
            font-size: 12.677px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .order-item-price {
            color: #7D7D7D;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .sku {
            color: #7D7D7D;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .p_name_order {
            color: #008BD3 !important;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .order_number {
            color: #008BD3;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: 19.606px;
        }
        .edit_admin_order {
            background-color: rgba(0, 139, 211, 0.05) !important;
            color: #008BD3;
        }
        
        .edit_admin_order:hover {
            background-color: rgba(0, 139, 211, 0.05) !important;
            color: #008BD3;
        }
        .cancel_order_changes {
            background-color: rgba(0, 139, 211, 0.05) !important;
            color: #008BD3;
        }
        
        .cancel_order_changes:hover {
            background-color: rgba(0, 139, 211, 0.05) !important;
            color: #008BD3;
        }
        .has-search-products .form-control {
            padding-left: 2.375rem;
        }

        .has-search-products .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
    </style>
    
@stop

@section('js')
    <script>
        // on click increase qty 

        $(document).ready(function() {
            console.log(time_left);
            var time_left = $('#timeSpanToCancel').val();
            var sec = $('#seconds').val();
            time_left = 3 - time_left;
            var timer2 = time_left + ":" + sec;
            var interval = setInterval(function() {
                var timer = timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval);

                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                minutes = (minutes < 10) ? minutes : minutes;
                $('#cancel_order').val('Cancel Order in ' + minutes + ':' + seconds);
                if (minutes == 0 && seconds == '00' || time_left < 1) {
                    $('#cancel_order').addClass('disabled');
                    //$('#full_fill').addClass('disabled');
                    $('#cancel_order').val('Cancel Order');

                    minutes = 0;
                    seconds = 0;
                    timer.lap();
                }
                timer2 = minutes + ':' + seconds;
            }, 1000);


            // po box order


            $(document).on('click', '.send_po_box_wholesale_order_to_shipstation', function () {
                const orderId = $(this).data('id');
                $('#wholesale_po_box_order_id').val(orderId);
            });

            // Before submitting the form, split the selected shipping method
            $('form[action="{{ route('send_po_box_wholesale_order_to_shipstation') }}"]').on('submit', function (e) {
                
                // Show loader
                $('#shipstation_loader_PO').show();

                // Optional: disable the submit button to prevent multiple clicks
                $(this).find('button[type="submit"]').prop('disabled', true);
            });



            // wholesale order



            $(document).on('click', '.send_wholesale_order_to_shipstation', function () {
                const orderId = $(this).data('id');
                $('#wholesale_order_id').val(orderId);
            });

            // Before submitting the form, split the selected shipping method
            $('form[action="{{ route('send_wholesale_order_to_shipstation') }}"]').on('submit', function (e) {
                const shippingValue = $('select[name="shipping_method_wholesale"]').val();
                const parts = shippingValue.split(' _and_ ');

                if (parts.length !== 2) {
                    // alert('Invalid shipping method selected.');
                    e.preventDefault(); // prevent submission
                    return false;
                }

                $('#carrier_code_wholesale').val(parts[1]); // carrier_code
                $('#service_code_wholesale').val(parts[0]); // service_code

                // Show loader
                $('#shipstation_loader').show();

                // Optional: disable the submit button to prevent multiple clicks
                $(this).find('button[type="submit"]').prop('disabled', true);
            });


            // buylist

            $(document).on('click', '.send_buy_list_order_to_shipstation', function () {
                const orderId = $(this).data('id');
                $('#buylist_order_id').val(orderId);
            });

            // Before submitting the form, split the selected shipping method
            $('form[action="{{ route('send_buy_list_order_to_shipstation') }}"]').on('submit', function (e) {
                const shippingValue = $('select[name="shipping_method_buy_list"]').val();
                const parts = shippingValue.split(' _and_ ');

                if (parts.length !== 2) {
                    // alert('Invalid shipping method selected.');
                    e.preventDefault(); // prevent submission
                    return false;
                }

                $('#carrier_code_buy_list').val(parts[1]); // carrier_code
                $('#service_code_buy_list').val(parts[0]); // service_code

                // Show loader
                $('#shipstation_loader').show();

                // Optional: disable the submit button to prevent multiple clicks
                $(this).find('button[type="submit"]').prop('disabled', true);
            });

        });
        //on click increase quantity
        function increaseQuantity(item_id) {
            var quantity = parseInt($('#itemQuantity_number_' + item_id).val());
            var itemPrice = parseFloat($('#itemPrice_number_' + item_id).val());
            var qty_value = $('#itemQuantity_number_' + item_id).val(quantity + 1);
            var quantityValue = parseInt($('#itemQuantity_number_' + item_id).val());
            var itemTotal = quantityValue * itemPrice;
            $('#itemTotal_' + item_id).html('');
            $('#itemTotal_' + item_id).html('$' + itemTotal.toFixed(2));
        }
        // on click descrease quantity
        function decreaseQuantity(item_id) {
            var quantity = parseInt($('#itemQuantity_number_' + item_id).val());
            if (quantity > 1) {
                var itemPrice = parseFloat($('#itemPrice_number_' + item_id).val());
                var qty_value = $('#itemQuantity_number_' + item_id).val(quantity - 1);
                var quantityValue = parseInt($('#itemQuantity_number_' + item_id).val());
                var itemTotal = quantityValue * itemPrice;
                $('#itemTotal_' + item_id).html('');
                $('#itemTotal_' + item_id).html('$' + itemTotal.toFixed(2));
            }
        } 

        // onchange qutantity update item price
        function change_quantity(item_id) {
            var quantity = parseInt($('#itemQuantity_number_' + item_id).val());
            var itemPrice = parseFloat($('#itemPrice_number_' + item_id).val());
            var qty_value = $('#itemQuantity_number_' + item_id).val(quantity);
            var quantityValue = parseInt($('#itemQuantity_number_' + item_id).val());
            var itemTotal = quantityValue * itemPrice;
            $('#itemTotal_' + item_id).html('');
            $('#itemTotal_' + item_id).html(itemTotal.toFixed(2));
        }

        //edit order
        function edit_order(id) {
            var order_items  = $('.itemQuantity');
            $('.edit_order_div').removeClass('d-flex');
            $('.edit_order_div').addClass('d-none');
            $('.edit-order-butttons').removeClass('d-none');
            $('.edit-order-butttons').addClass('d-flex');
            $('.itemQuantityText').addClass('d-none');
            $('.item_prices').addClass('d-none');
            $('.itemQuantityDiv').removeClass('d-none');
            $('.item_price_class').removeClass('d-none');
            $('#delete_item_head').removeClass('d-none');
            $('.delete_item_body').removeClass('d-none');
            $('.add_product_row').removeClass('d-none');
            $('.add_colspan').attr('colspan', '5');
            $('.order-status-drop-down').removeClass('d-none');
        }
        //cancel order changes
        function cancel_order_changes(id) {
            $('.edit-order-butttons').addClass('d-none');
            $('.edit-order-butttons').removeClass('d-flex');
            $('.edit_order_div').addClass('d-flex');
            $('.edit_order_div').removeClass('d-none');
            $('.itemQuantityText').removeClass('d-none');
            $('.item_prices').removeClass('d-none');
            $('.itemQuantityDiv').addClass('d-none');
            $('.item_price_class').addClass('d-none');
            $('#delete_item_head').addClass('d-none');
            $('.delete_item_body').addClass('d-none');
            $('.add_colspan').attr('colspan', '4');
            $('.add_product_row').addClass('d-none');
            $('.order-status-drop-down').addClass('d-none');
        }
        //prevent input from starting with 0
        $(".itemQuantity").on("input", function() {
            if (/^0/.test(this.value)) {
                this.value = this.value.replace(/^0/, "1");
            }
        });

        
        function searchProducts() {
            
            var order_id = $('#orderID').val();
            var tax_rate = $('#tax_rate').val();
            var search_value = $('#myInput-order').val();
            console.log(search_value);
            jQuery.ajax({
                url: "{{ url('admin/order/search-product') }}",
                method: 'get',
                data: {
                    order_id: order_id,
                    search_value: search_value,
                    tax_rate: tax_rate
                },
                
                success: function(response) {
                    if(response.success == true && response.data.length > 0){
                        $('.prd_ul').removeClass('d-none');
                        var stringify = JSON.stringify(response.data);
                        data = JSON.parse(stringify);
                        data.forEach(function(product, element , value) {
                            product.options.forEach(function(index , value) {
                                var span = '<li class="all-products" onclick="add_product(' + index.product_id + ' , ' + index.option_id + ')">'+product.name+'</li>';
                                $(".prd_ul").append(span);
                            });
                        })
                    }
                }
            });

        }

        // function filterFunction() {
        //     var input, filter, ul, li, a, i;
        //     input = document.getElementById("myInput-order");
        //     filter = input.value.toUpperCase();
        //     div = document.getElementById("product_dropdown");
        //     a = div.getElementsByTagName("span");
        //     var p = div.getElementsByTagName("p");
        //     for (i = 0; i < a.length; i++) {
        //         txtValue = a[i].textContent || a[i].innerText;
        //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //         a[i].style.display = "";
        //         } else {
        //             a[i].style.display = "none";
        //         }
        //     }
        // }
        

        function add_product(productId , option_id) {
            var order_id = $('#orderID').val();
            var tax_rate = $('#tax_rate').val();
            var product_id = productId;
            var option_id = option_id;
            jQuery.ajax({
                url: "{{ url('admin/order/add-product') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "product_id": product_id,
                    "tax_rate": tax_rate,
                    'order_id': order_id,
                    'option_id': option_id

                },
                success: function(response) {
                    if(response.success == true){
                        $('#fullfill_success').removeClass('d-none');
                        $('#fullfill_success').text(response.message);
                        window.location.reload();
                    }
                }
            });
        }

        //delete item from order
        function deleteItem(itemId) {
            var item_id = itemId;
            var order_id = $('#orderID').val();
            var tax_rate = $('#tax_rate').val();
            jQuery.ajax({
                url: "{{ url('admin/order/delete-item') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "item_id": item_id,
                    "tax_rate": tax_rate,
                    'order_id': order_id

                },
                success: function(response) {
                    console.log(response);
                    if (response.item_count > 1) {
                        if(response.success == true){
                            $('#fullfill_success').removeClass('d-none');
                            $('#fullfill_success').text(response.message);
                            window.location.reload();
                        }
                        else if(response.success == false){
                            $('#fullfill_success').removeClass('d-none');
                            $('#fullfill_failed').text(response.message);
                            window.location.reload();
                        }
                    } else {

                        Swal.fire({
                                toast: true,
                                icon: 'warning',
                                title: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'Yes',
                                showCancelButton: true,
                                position: 'top',
                                
                        }).then((result) => {
                            if (result.isConfirmed) {
                                jQuery.ajax({
                                    url: "{{ url('admin/order/item/delete') }}",
                                    method: 'post',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "item_id": item_id,
                                        "tax_rate": tax_rate,
                                        'order_id': order_id
        
                                    },
                                    success: function(response) {
                                        if(response.success == true){
                                            $('#fullfill_success').removeClass('d-none');
                                            $('#fullfill_success').text(response.message);
                                            window.location = '/admin/orders';
                                        }
                                    }
                                });
                            }
                        });
                        
                    }
                }
            });
        }

        // update order 
        function update_order(order_id) {
            var tax_rate = $('#tax_rate').val();
            var itemsqtys =  $('.itemQuantity');
            var itemsprices =  $('.item_price_class');
            var product_total = 0;
            var total_qty = 0;
            var join_id_qty_price = '';
            var item_data = [];
            itemsqtys.each(function (index, element) {
                var itemPrice = parseFloat(itemsprices[index].value);
                var itemQty = parseInt(element.value);
                product_total += itemPrice * itemQty;
                total_qty += itemQty;
                
                item_data[index] = {
                    item_id : $(this).attr('data-id'), 
                    item_quantity : itemQty, 
                    item_price : itemPrice
                };

            });
            var calculated_tax = tax_rate /100 * product_total;
            var total_included_tax = product_total + calculated_tax;
            $('#subtotal_text').text('$'+product_total.toFixed(2));
            $('#tax_text').text('$'+calculated_tax.toFixed(2));
            $('#grand_total_text').text('$'+total_included_tax.toFixed(2));
            var orderID = order_id;
           
            jQuery.ajax({
                url: "{{ url('admin/order/update') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'order_id': orderID,
                    "tax_rate": tax_rate,
                    'item_data': item_data,
                    'subtotal' : product_total,
                    'total_including_tax':total_included_tax

                },
                success: function(response) {
                    if(response.success == true){
                        $('#fullfill_success').removeClass('d-none');
                        $('#fullfill_success').text(response.message);
                        window.location.reload();
                    }
                    else{
                        $('#fullfill_failed').removeClass('d-none');
                        $('#fullfill_failed').text('Something went wrong!');
                        window.location.reload();
                    }
                }
            });
        }

        function addComment(isUserAdded) {
            if (isUserAdded == 1) {
                var comment = $("#comment").val();
                var order_id = $("#order_id").val();
            } else {
                var comment = 'order cancel';
                var order_id = $("#order_id").val();
            }
            jQuery.ajax({
                url: "{{ url('admin/order-comments') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "comment": comment,
                    "order_id": order_id
                },
                success: function(response) {
                    //  window.location.reload();
                }
            });
        }

        function updateStatus() {
            var status = $("#status").val();
            var order_id_status = $("#order_id_status").val();
            jQuery.ajax({
                url: "{{ url('admin/order-status') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "status": status,
                    "order_id_status": order_id_status
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

        function cancelOrder() {
            var order_id = $("#order_id").val();
            $.ajax({
                url: "{{ url('admin/order-cancel') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(response) {
                    console.log(response);
                    window.location.reload();
                }
            })
        }

        function fullFillOrder() {
            var status = $("#status").val();
            var order_id = $("#orderID").val();
            var delay = 7000;
            $('#progress-bar').removeClass('d-none');
            
            jQuery(".progress-bar").each(function(i) {
                jQuery(this).delay(delay * i).animate({
                    width: $(this).attr('aria-valuenow') + '%'
                }, delay);

                jQuery(this).prop('Counter', 1).animate({
                    Counter: $(this).text()
                }, {
                    duration: delay,
                    // easing: 'swing',
                    step: function(now) {
                        jQuery(this).text(Math.ceil(100) + '%');
                    }
                });
            });
            jQuery.ajax({
                url: "{{ url('admin/order-full-fill') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(response) {
                    if (response.status === 'success') {
                        jQuery.ajax({
                            url: "{{ url('admin/check-status') }}",
                            method: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "order_id": order_id
                            },
                            success: function(response) {
                                console.log(response.status);
                                if (response.status === 'Order fullfilled successfully') {
                                    $('#fullfill_success').html(response.status);
                                } else {
                                    $('#fullfill_failed').html(response.status);
                                }
                                $('#progress-bar').addClass('d-none');
                                setInterval('location.reload()', 10000);
                            }
                        });
                    }
                    else if (response.status === 'failed') {
                        alert('Order fullfill failed');
                        location.reload();
                    }
                }
            });
        }

        function update_order_status (order_id , paid) {
            $('.order-status-spinner').removeClass('d-none');
            $('.update_order').attr('disabled' , true);
            var order_status_id = $('#order_status_id').val();
            var payment_status = paid;
            check_status_name = $('#order_status_id option:selected').text();

            if (check_status_name == 'Partial Refund') {
                $('.order-status-spinner').addClass('d-none');
                $('#partial_refund').modal('show');
                $('#grand_popup_total_text').val($('#grand_total_value').val());
                $('#pop_up_grand_total').val($('#grand_total_value').val());
                $('#pop_up_grand_total').attr('max', $('#grand_total_value').val());
            } else if (check_status_name == 'Refunded') {
                $('.order-status-spinner').addClass('d-none');
                $('#refundReasonModal').modal('show');
            }
            else {
                $('#partial_refund').modal('hide');
                $('#refundReasonModal').modal('hide');
                var order_status_type = 'null';
                order_status_updating_by_admin(order_id ,order_status_id, payment_status , order_status_type , refund_value = 0 , refund_reason = null);
            }
        }

        function order_status_updating_by_admin(order_id ,order_status_id, payment_status, order_status_type , refund_value, refund_reason) {
            jQuery.ajax({
                url: "{{ url('admin/order/update-order-status') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id,
                    "order_status_id": order_status_id,
                    "payment_status": payment_status,
                    "order_status_type": order_status_type,
                    "refund_value": refund_value,
                    "refund_reason": refund_reason,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        $('.order-status-spinner').addClass('d-none');
                        $('.success_text_div').removeClass('d-none');
                        $('.success_text').text(response.message);
                        cancel_order_changes(order_id);
                        window.location.href="/admin/order-detail/" + order_id;

                    } else {
                        $('.order-status-spinner').addClass('d-none');
                        $('.success_text_div').removeClass('d-none');
                        $('.success_text').text(response.message);
                        $('.error_message_refund').children('span').text(response.message);        
                    }
                }
            });
        }

        // check if refund_value is greater than grand_total

        function partial_refund_ftn(order_id) {
            var grand_total = parseFloat($('#grand_total_value').val());
            var refund_value = parseFloat($('#pop_up_grand_total').val());
            if ($('#pop_up_grand_total').val() == null || $('#pop_up_grand_total').val() == '') {
                $('.error_message_parial_refund').children('span').text('Patial refund value is required');
                return false;
            }
            $('.partial-order-status-spinner').removeClass('d-none');
            if (refund_value >= grand_total) {
                $('.partial-order-status-spinner').addClass('d-none');
                $('.error_message_parial_refund').children('span').text('Partial Refund value should be less than grand total');
                return false;
            } else if(refund_value <= 0 || refund_value === '') {
                $('.partial-order-status-spinner').addClass('d-none');
                $('.error_message_parial_refund').children('span').text('Partial Refund value should be greater than 0');
                return false;
            }

            else {
                var order_status_id = $('#order_status_id').val();
                var payment_status = 'partially refunded';
                var order_status_type = 'partial_refund';
                order_status_updating_by_admin(order_id ,order_status_id, payment_status , order_status_type , refund_value , refund_reason = null);
            }       
            
        }

        function full_refund_btn(order_id) {
            var order_status_id = $('#order_status_id').val();
            var payment_status = 'refunded';
            var order_status_type = 'full_refund';
            var refund_value = $('#grand_total_value').val();
            var refund_reason = $('#refund_reason').val();
            if (refund_reason == null || refund_reason == '') {
                $('.error_message_refund').children('span').text('Refund reason is required');
                return false;
            }
            order_status_updating_by_admin(order_id ,order_status_id, payment_status , order_status_type , refund_value , refund_reason);
        }

    </script>
@stop
