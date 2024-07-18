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
                               Discounts
                            </p>
                        </div>
                        <div class="col-md-6 mobile_heading text-right">
                            <p class="product_heading">
                                <a class="btn btn-primary text-white" href="{{ route('discounts.create') }}"> Create Discount</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body  mt-5">
            <div class="col-md-12 shadow border">
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            {{-- <th>Name</th> --}}
                            <th>Type</th>
                            <th>Mode</th>
                            <th>Code</th>
                            <th>Variation</th>
                            <th>Value</th>
                            {{-- <th>Min Purchase Requirements</th> --}}
                            {{-- <th>Min Purchase Items Quantity</th> --}}
                            {{-- <th>Min Purchase Amount</th> --}}
                            <th>Eligibility</th>
                            <th>Max Uses</th>
                            <th>Max Uses Count</th>
                            <th>Usage Count</th>
                            <th>User Limit</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i =1; 
                        @endphp
                        @if(count($discounts) > 0 )
                        @foreach ($discounts as $discount)
                        <tr>
                            <td>{{ $i++ }}</td>
                            {{-- <td>{{ $discount->name }}</td> --}}
                            <td>{{ $discount->type }}</td>
                            <td>{{ $discount->mode }}</td>
                            <td>{{ $discount->discount_code }}</td>
                            <td>{{ucfirst( $discount->discount_variation) }}</td>
                            <td>{{ $discount->discount_variation_value }}</td>
                            {{-- <td>{{ $discount->minimum_purchase_requirements }}</td> --}}
                            {{-- <td>{{ $discount->minimum_quantity_items }}</td> --}}
                            {{-- <td>{{ $discount->minimum_purchase_amount }}</td> --}}
                            <td>{{ $discount->customer_eligibility }}</td>
                            <td>{{ $discount->max_discount_uses }}</td>
                            <td>{{ $discount->max_usage_count }}</td>
                            <td>{{ $discount->usage_count }}</td>
                            <td>{{ $discount->limit_per_user }}</td>
                            <td>{{ $discount->start_date }}</td>
                            <td>{{ $discount->end_date }}</td>
                            <td>
                                @if ($discount->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                {{-- <div class="row mb-2 justify-content-center">
                                    
                                </div> --}}
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('discounts.edit', $discount->id) }}" class="border-0 btn-sm text-dark" style="background: #f0f0f0;"><i class="fa fa-edit"></i></a>
                                    <form action="{{route('discounts_duplicate')}}" method="POST">
                                        
                                        @csrf
                                        <input type="hidden" name="discount_id" value="{{ $discount->id }}">
                                        <button type="submit" class="btn-sm bg-none border-0" onclick="return confirm('Are you sure you want to duplicate this discount?');">
                                            <i class="fa fa-clone"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" class="">
                                        
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm bg-none border-0" onclick="return confirm('Are you sure you want to delete this discount?');">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3">No Discounts found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10">
                    {{ $discounts->links('pagination.custom_pagination') }}
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