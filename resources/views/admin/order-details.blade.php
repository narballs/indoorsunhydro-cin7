@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Order Detail</h1>
@stop
@section('content')
    <!-- sdfkjlsdkfjsdlkfk -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-8 d-flex justify-content-end mb-3">
            <form>
                @csrf
                <input type="hidden" value="{{ $time_diff }}" id="timeSpanToCancel">
                <input type="hidden" value="{{ $time_difference_seconds }}" id="seconds">
                @if ($order->isApproved == 2)
                    <button type="button" class="btn btn-danger btn-sm" disabled>Cancel
                        Order</button>
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
            <form>
                @csrf
                @if ($order->isApproved == 1 )
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary btn-sm" disabled>
                            Fullfilled
                        </button>
                    </div>
                @elseif ($order->isApproved == 2)
                    <div class="col-md-12"
                        style="margin-left: 122px;
                margin-top: -29px;">
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
                {{-- <div class="d-flex justify-content-between align-items-center py-3">
                    <h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Order #{{ $order->id }}</h2>
                </div> --}}
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="d-flex card-header">
                               <div class="col-md-12">
                                    <div class="row">
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
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="order-head">Order</span>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="order_number">#{{ $order->id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($order->isApproved == 0 && $order->isVoid == 0)
                                        <div class="col-md-4 d-flex align-items-center justify-content-end edit_order_div">
                                            <button class="btn btn-light btn-sm edit_admin_order" type="button" onclick="edit_order('{{ $order->id }}')">
                                                    Edit Order
                                            </button>
                                        </div>
                                        @endif

                                        <div class="col-md-4 d-none edit-order-butttons align-items-center justify-content-end">
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
                            {{-- <div class="row mb-5">
                                <div class="col-md-2">
                                    <form>
                                        @csrf
                                        <input type="hidden" value="{{ $time_diff }}" id="timeSpanToCancel">
                                        <input type="hidden" value="{{ $time_difference_seconds }}" id="seconds">
                                        @if ($order->isApproved == 2)
                                            <button type="button" class="btn btn-danger btn-sm" disabled>Cancel
                                                Order</button>
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
                                <div class="col-md-6">
                                    <form>
                                        @csrf
                                        @if ($order->isApproved == 1 )
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    Fullfilled
                                                </button>
                                            </div>
                                        @elseif ($order->isApproved == 2)
                                            <div class="col-md-12"
                                                style="margin-left: 122px;
                                        margin-top: -29px;">
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    Fullfilled
                                                </button>
                                            </div>

                                        @elseif ($order->isApproved == 0 && $auto_fullfill == true )
                                            <div class="col-md-12" style="margin-left: 50px;">
                                                <input id="full_fill" class="btn btn-primary btn-sm " type="button"
                                                    value="Fullfill Order" disabled>
                                            </div>
                                            <div class="spinner-border d-none" role="status" id="spinner">
                                                <span class="sr-only" style="margin-left: 227px">Activating...</span>
                                            </div>
                                         @elseif ($order->isApproved == 0 && $auto_fullfill == false )
                                            <div class="col-md-12" style="margin-left: 50px;">
                                                <input id="full_fill" class="btn btn-primary btn-sm" type="button"
                                                    value="Fullfill Order" onclick="fullFillOrder()">
                                            </div>
                                            <div class="spinner-border d-none" role="status" id="spinner">
                                                <span class="sr-only" style="margin-left: 227px">Activating...</span>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                    role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                            </div>
                            <div class="bg-success text-white text-center" id="fullfill_success"></div>
                            <div class="bg-warning text-white text-center" id="fullfill_failed"></div> --}}
                            <div class="">
                                <table class="table">
                                    <thead>
                                        <tr class="background-color">
                                            <th class="pl-4">Line Items</th>
                                            <th class="text-center">Quantity</th>
                                            <th id="delete_item_head" class="d-none text-center">Delete Item</th>
                                            <th class="text-center">Totals</th>
                                        </tr>
                                    </thead>
                                    <tbody class="order-detail-tbody">
                                        @php
                                            $tax=0;
                                            if (!empty($tax_class)) {
                                                $tax = $order->total * ($tax_class->rate / 100);
                                            }
                                            $total_including_tax = $tax + $order->total;
                                        @endphp
                                        @foreach ($orderitems as $item)
                                            <tr class="border-bottom order_items_row" id="row_{{$item->id}}">
                                                <td class="align-middle">
                                                    <div class="d-flex mb-2">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ $item->product->images }}" alt=""
                                                                width="35" class="img-fluid">
                                                        </div>
                                                        <div class="d-flex align-items-center pl-3">
                                                            <h6 class="small mb-0"><a href="#" class="p_name_order">{{ $item->Product->name }}</a></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ms-2 align-middle d-flex justify-content-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="33" class="itemQuantityText" height="32" viewBox="0 0 33 32" fill="none">
                                                        <circle cx="16.5752" cy="15.8466" r="15.8466" fill="#E3F5F5"/>
                                                        <text x="50%" y="50%" text-anchor="middle" class="order-item-quantity itemQuantityText" stroke="#131313" stroke-width="" dy=".3em" id="itemQuantityText_{{$item->id}}">{{ $item->quantity }}</text>
                                                    </svg>
                                                    <input type="text" min="1" class="itemQuantity form-control form-control-sm w-50 h-auto p-1 text-center d-none" value="{{ $item->quantity}}" data-id="{{$item->id}}" id="itemQuantity_number_{{$item->id}}">
                                                </td>
                                                <td class="delete_item_body d-none text-center">
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
                                                <td class="text-center">
                                                    <span class="order-item-price item_prices" id="itemPrice_{{$item->id}}">${{ number_format($item->price, 2) }}</span>
                                                    <input type="text" value="{{ $item->price }}" class="item_price_class form-control form-control-sm mx-auto w-50 h-auto p-1 text-center d-none" id="itemPrice_number_{{$item->id}}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-bottom">
                                            <td colspan="2" class="add_colspan"><span class="summary-head mx-2">Subtotal</span></td>
                                            <td class="text-center"><span class="order-item-price" id="subtotal_text">${{ number_format($order->total, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td colspan="2" class="add_colspan"><span class="summary-head mx-2">Shipping</span></td>
                                            <td class="text-center"><span class="order-item-price">$0.00</span></td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td colspan="2" class="add_colspan"><span class="summary-head mx-2">Add Tax</span></td>
                                            <td class="text-center"><span class="order-item-price" id="tax_text">${{ number_format($tax, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td colspan="2" class="add_colspan"><span class="summary-head mx-2">GRAND TOTAL</span></td>
                                            <td class="text-center"><span class="order-grand-total" id="grand_total_text">${{ number_format($total_including_tax, 2) }}</span></td>
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
                                @if(!empty($item->order->primary_contact))
                                <input type="hidden" id="primary_contact" name="price_column" value="{{$item->order->primary_contact->priceColumn}}">
                                @elseif(!empty($item->order->secondary_contact))
                                <input type="hidden" id ="secondary_contact"name="price_column" value="{{$item->order->secondary_contact->priceColumn}}">
                                @endif
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
                                        <h3 class="h6 summary-head">Payment Method</h3>
                                        <span class="delievery">{{ $order->logisticsCarrier }}</span></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h3 class="h6 summary-head">Billing address</h3>
                                        <address>
                                            @if (!empty($customer->firstName && $customer->lastName))
                                                <strong>{{ $customer->firstName }}&nbsp;{{ $customer->lastName }}</strong><br>
                                            @endif
                                            {{ $customer->postalAddress1 }}<br>
                                            {{ $customer->postalAddress2 }}
                                            {{ $customer->postalCity }}, {{ $customer->state }}
                                            <p title="Phone" class="m-0">P:({{ $customer->mobile }})</p>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Customer Notes -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="h6" style="margin-bottom: 0px;"><strong>Order Notes</strong></h3>
                                @foreach ($orderComment as $comment)
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    <p>{{ $user->first_name }} {{ $user->last_name }} {{ $comment->comment }}</p>
                                    <p style="margin-bottom: 0px;">Date</p>
                                    <p>
                                        <i>
                                            {{ $comment->created_at }}
                                        </i>
                                    </p>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <form method="POST" id="order_notes" name="order_notes">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1" class="ms-2">Add Order Notes</label>
                                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                                    <input class="btn btn-primary" type="button" value="Add Notes"
                                        onclick="addComment(1)">
                                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                                </div>

                            </form>
                        </div>
                        <div class="col-lg-12">
                            <form method="POST" id="order_notes" name="order_notes">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1" class="ms-2">Fullfil</label>
                                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                                    <input class="btn btn-primary" type="button" value="Add Notes"
                                        onclick="fullFillOrder()">
                                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                                </div>

                            </form>
                        </div>
                        <div class="card mb-4">
                            <!-- Shipping information -->
                            <div class="card-body">
                                <h3 class="h6">Shipping Information</h3>

                                <hr>
                                <h3 class=" h6">Address</h3>
                                <address>
                                    <strong>{{ $customer->firstName }} {{ $customer->lastName }}</strong><br>
                                    {{ $customer->address1 }}, {{ $customer->address2 }}<br>
                                    {{ $customer->city }},
                                    <p title="Phone" class="mb-0">P: ({{ $customer->mobile }})</p>
                                    <p title="Phone">{{ $customer->email }}</p>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">

    <style type="text/css">

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
        .delievery {
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
        });
        //edit order
        function edit_order(id) {
            var order_items  = $('.itemQuantity');
            $('.edit_order_div').removeClass('d-flex');
            $('.edit_order_div').addClass('d-none');
            $('.edit-order-butttons').removeClass('d-none');
            $('.edit-order-butttons').addClass('d-flex');
            $('.itemQuantityText').addClass('d-none');
            $('.item_prices').addClass('d-none');
            $('.itemQuantity').removeClass('d-none');
            $('.item_price_class').removeClass('d-none');
            $('#delete_item_head').removeClass('d-none');
            $('.delete_item_body').removeClass('d-none');
            $('.add_product_row').removeClass('d-none');
            $('.add_colspan').attr('colspan', '3');
        }
        //cancel order changes
        function cancel_order_changes(id) {
            $('.edit-order-butttons').addClass('d-none');
            $('.edit-order-butttons').removeClass('d-flex');
            $('.edit_order_div').addClass('d-flex');
            $('.edit_order_div').removeClass('d-none');
            $('.itemQuantityText').removeClass('d-none');
            $('.item_prices').removeClass('d-none');
            $('.itemQuantity').addClass('d-none');
            $('.item_price_class').addClass('d-none');
            $('#delete_item_head').addClass('d-none');
            $('.delete_item_body').addClass('d-none');
            $('.add_colspan').attr('colspan', '2');
            $('.add_product_row').addClass('d-none');
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
            var price_col = '';
            if($('#primary_contact').val() != '' || $('#primary_contact').val() != null) {
                price_col = $('#primary_contact').val();
            }
            if($('#secondary_contact').val() != '' || $('#secondary_contact').val() != null) {
                price_col = $('#secondary_contact').val();
            }

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
                    'price_col': price_col,
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
            var order_id = $("#order_id").val();
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
            })
            jQuery.ajax({
                url: "{{ url('admin/order-full-fill') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(response) {
                    console.log(response);
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
                            setInterval('location.reload()', 7000);
                        }
                    });
                }
            });
        }
    </script>
@stop
