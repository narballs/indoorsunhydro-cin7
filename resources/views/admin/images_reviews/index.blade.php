
@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content')
    
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible mt-2 ml-4">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('error')}}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible mt-2 ml-4">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('success')}}
                    </div>
                @endif
            </div>
            <div class="row product_section_header">
                <div class="col-md-12">
                    <h3 class="order_heading">Image Reviews</h3>
                </div>
                
            </div>
            <div class="row search_row_admin-interface">
                <!-- Search Input -->
                <div class="col-md-4">
                    <div class="has-search">
                        <form method="get" action="/admin/images-requests" class="mb-2">
                            <div class="input-group">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                    placeholder="Search by Amount" value="{{ request('search') }}" />
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            
            
            <div class="card-body product_table_body">
                <div class="col-md-12 p-0">
                    <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                        <table class="table  bg-white  table-customer mb-0 mobile-view">
                            <thead>
                                <tr class="table-header-background">
                                    <td><span class="d-flex table-row-item"> S.No</span></td>
                                    <td><span class="d-flex table-row-item"> Product Name</span></td>
                                    <td><span class="d-flex table-row-item"> Sku</span></td>
                                    <td><span class="d-flex table-row-item"> Image</span></td>
                                    <td><span class="d-flex table-row-item"> Status</span></td>
                                    <td><span class="d-flex table-row-item"> Action</span></td>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($ai_image_generations) == 0)
                                    <tr>
                                        <td colspan="10" class="text-center">No Images for review found Found</td>
                                    </tr>
                                @endif

                                @foreach ($ai_image_generations as $ai_image_generation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ai_image_generation->product->name }}</td>
                                        <td>{{ $ai_image_generation->product->sku }}</td>
                                        <td>
                                            <img src="{{ $ai_image_generation->image }}" alt="Product Image" style="width: 100px; height: 100px;">
                                        </td>
                                        <td>
                                            @if ($ai_image_generation->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($ai_image_generation->status == 1)
                                                <span class="badge badge-success">Approved</span>
                                            @elseif ($ai_image_generation->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ai_image_generation->status == 1)
                                                <a href="{{ route('images_requests_approve', $ai_image_generation->id) }}" class="btn btn-primary btn-sm">Approved</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <td colspan="10">
                                        {{ $payouts->links('pagination.custom_pagination') }}
                                    </td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css?v3">
    <link rel="stylesheet" href="{{ asset('/admin/admin_lte.css?v4') }}">

    <style type="text/css">
        /* mobile responsive admin panel */

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
                width: 50%;

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
                padding-left: 11px !important;
                padding-right: 7px !important;
            }

            .select-row-items {
                padding-left: 2px !important;
                justify-content: start;
                align-items: center !important;
                color: #222222 !important;
                font-style: normal !important;
                font-weight: 500 !important;
                font-size: 0.826rem !important;
                padding-top: 0px !important;
            }

            .order_heading {
                color: #242424 !important;
                font-size: 18px !important;
                font-family: Poppins, sans-serif !important;
                font-weight: 500 !important;
                line-height: 24px !important;
                letter-spacing: 0.252px !important;
                margin-left: 17px !important;
                margin-left: 17px !important;
                margin-top: 29px !important;
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

        .create_label {
            font-size:12px;
        }

        /* mobile responsive admin panel end */
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

        .badge-secondary {
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_success {
            /* background: rgb(186 235 137 / 20%) !important; */
            color: #319701 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
        }

        .bg_secondary {
            color: #383231 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
            padding-left: 16px !important;
        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 5px !important;
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

        .badge-info {
            color: #fff;
            background-color: #e1eff1;
            color: #17a2b8;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_danger {
            color: #DC4E41 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
        }
        .bg_info {
            color: #17a2b8 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
        }
        .unprocess_alert .close {
            padding: 2px !important;
            right: 10px !important;
        }

        /* @media only screen and (max-width: 425px) {
            thead tr {
                display: none;
            }

            td, th {
                display: block;
                font-size: 14px;
                font-weight: 500;
            }

            table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
            table td{
               text-align: right;
            }
            .td_padding_row  {
                padding: 0.50rem !important;
            }
        } */
    </style>
@stop

@section('js')
@stop
@section('plugins.Sweetalert2', true)

