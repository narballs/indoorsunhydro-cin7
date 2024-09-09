@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
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
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mobile_heading">
                            <p class="product_heading">
                                Sale Payments
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body  mt-5">
            <form action="{{url('/admin/sale-payments')}}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>
                            <strong>
                                Filter By : 
                            </strong>
                            @if (!empty($search) || !empty($payment_method) || !empty($date_from) || !empty($date_to))
                                <a href="{{url('/admin/sale-payments')}}" class="btn btn-sm btn-primary mx-2 text-white">
                                    Reset Filter
                                </a>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="row align-items-center">
                            
                            <div class="col-md-12">
                                <label>
                                    Name / Email / Company 
                                </label>
                                <input type="text" class="form-control" id="search" name="search_by_name_email" placeholder="Search" value="{{!empty($search) ? $search : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <label>
                                    Payment Method
                                </label>
                                <select name="payment_method" id="" class="form-control">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{!empty($payment_method) && ($payment_method == 'cash') ? 'selected' : ''}}>Cash</option>
                                    <option value="card" {{!empty($payment_method) && ($payment_method == 'card') ? 'selected' : ''}}>Card</option>
                                    <option value="on account" {{!empty($payment_method) && ($payment_method == 'on account') ? 'selected' : ''}}>On Account</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <label for="">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="{{!empty($date_from) ? $date_from : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <label for="">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="{{!empty($date_to)  ? $date_to : ''}}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-md-12 shadow border">
                <div class="table-responsive">
                    <table class="table table-border">
                        <thead>
                            <tr>
                                {{-- <th>S.No</th> --}}
                                <th>Order Id</th>
                                <th>Company</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>PO No</th>
                                <th>Invoice No</th>
                                <th>Order Reference</th>
                                <th>Payment Method</th>
                                <th>Order Type</th>
                                <th>Payment Date</th>
                                <th>Created Date</th>
                                {{-- <th>Modifie Date</th> --}}
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i =1; 
                            @endphp
                            @if(count($sale_payments) > 0 )
                                @foreach ($sale_payments as $sale_payment)
                                <tr>
                                    {{-- <td>{{ $i++ }}</td> --}}
                                    <td>{{$sale_payment->orderId }}</td>
                                    <td>{{$sale_payment->company }}</td>
                                    <td>{{$sale_payment->customer_first_name .' '. $sale_payment->customer_last_name }}</td>
                                    <td>{{$sale_payment->email }}</td>
                                    <td>{{$sale_payment->po_number }}</td>
                                    <td>{{$sale_payment->invoice_number }}</td>
                                    <td>{{$sale_payment->orderRef}}</td>
                                    <td>{{strtoupper($sale_payment->method)}}</td>
                                    <td>{{$sale_payment->orderType}}</td>
                                    <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->paymentDate)}}</td>
                                    <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->createdDate)}}</td>
                                    {{-- <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->modifiedDate)}}</td> --}}
                                    <td>
                                        <a href="{{ route('sale-payments.show', $sale_payment->orderId) }}" class="btn btn-info btn-sm text-white" >Order Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Sales Payments found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    {{ $sale_payments->links('pagination.custom_pagination') }}
                </div>
            </div>
        </div>
    @stop


    @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style type="text/css">
        @media(min-width:280px) and (max-width: 425px) {
            .main-header {
                border-bottom: none;
                width: 25%;
                height: 0px !important;
                margin-top: 20px !important;
            }

            .mobile_heading {
                position: absolute;
                left: 10rem;
                top: -3rem;
                width: 0px !important;

            }

            .search_row_admin-interface {
                position: absolute;
                top: 1rem;
                left: 1rem;
                width: 95%;
            }

            .mobile_fulfill_div {
                margin-top: 3.563rem
            }

            .fullfill_btn_mbl {
                position: absolute;
                left: 3.3rem;
            }

            .create_new_btn_mbl {
                margin-right: 0.5rem;
            }

            .product_section_header {
                border-bottom: none !important;
            }

            .sm-d-none {
                display: none !important;
            }

            .bx-mobile {
                display: flex !important;
                justify-content: space-around !important;
                align-items: center !important;
            }

            .mobile-screen-selected {
                width: 30%;
            }

            .mobile-screen-ordrs-btn {
                width: 70%;
            }

            .product_table_body {
                padding-right: 13px !important;
                margin-top: -17px;
                padding-left: 0px !important;
            }

            .select-row-items {
                padding-left: 12px !important;
                display: flex;
                justify-content: start;
                align-items: center !important;
                color: #222222 !important;
                font-style: normal !important;
                font-weight: 500 !important;
                font-size: 0.826rem !important;
                padding-top: 0px !important;
            }

            .product_heading {
                color: #242424 !important;
                font-size: 18px !important;
                font-weight: 500;
                line-height: 24px;
                letter-spacing: 0.252px;
                font-family: 'Poppins', sans-serif !important;
                margin-left: -5px !important;
                margin-top: 26px !important;
            }

            .create_bnt {
                padding: 9px 24px !important;
                margin-top: 114px !important;
            }

            .fillter-mobile-screen {
                width: 100% !important;
            }

            .filter-row-mobile-secreen {
                margin-top: 7px !important;
                padding-left: 41px !important;

            }

            .product_search {
                background: #FFFFFF !important;
                border-radius: 7.25943px !important;
                margin-top: -7px;
                margin-left: 32px !important;
                padding-right: 16px !important;
            }

            .mobile-screen {
                widows: 100% !important;
            }

            .mobile_screen_Previous_btn {
                width: 25% !important;
            }

            .mobile_screen_pagination_number {
                width: 50% !important;
            }

            .mobile_screen_Previous_next {
                width: 25% !important;
                margin-top: 11px !important;
            }

            .main-sidebar {
                background-color: #fff !important;
                box-shadow: none !important;
                border-right: 1px solid #EAECF0 !important;
                top: -21px !important;
            }
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

        .bg_success {
            /* background: rgb(186 235 137 / 20%) !important; */
            color: #319701 !important;
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

        .bg_danger {
            color: #DC4E41 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
        .custom-checkbox {
            min-height: 1rem;
            padding-left: 0;
            margin-right: 0;
            cursor: pointer;
        }

        .custom-checkbox .custom-control-indicator {
            content: "";
            display: inline-block;
            position: relative;
            width: 30px;
            height: 10px;
            background-color: #818181;
            border-radius: 15px;
            margin-right: 10px;
            -webkit-transition: background .3s ease;
            transition: background .3s ease;
            vertical-align: middle;
            margin: 0 16px;
            box-shadow: none;
        }

        .custom-checkbox .custom-control-indicator:after {
            content: "";
            position: absolute;
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #f1f1f1;
            border-radius: 21px;
            box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
            left: -2px;
            top: -4px;
            -webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
            transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator {
            background-color: #28a745;
            background-image: none;
            box-shadow: none !important;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator:after {
            background-color: #28a745;
            left: 15px;
        }

        .custom-checkbox .custom-control-input:focus~.custom-control-indicator {
            box-shadow: none !important;
        }

    </style>
@stop


@section('js')
@stop