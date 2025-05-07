@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mobile_heading">
                            <p class="product_heading">
                                Cin7 Payment Information Logs
                            </p>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface" style="margin-top: 12px !important;">
                        <div class="col-md-4 product_search">
                            <div class="has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/get-cin7-payment-logs" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search By Order Reference" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div id="admin-users"></div>
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table bg-white table-users mb-0" id="user-table">
                        <tr>
                            <thead>
                                <tr class="table-header-background">
                                    <td>
                                        <span class="d-flex table-row-item"> Payment ID </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Order Id </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Order Reference </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Method</span>
                                    </td>
                                    {{-- <td>
                                        <span class="d-flex table-row-item"> Amount</span>
                                    </td> --}}
                                    <td>
                                        <span class="d-flex table-row-item"> Order Type</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Payment Date</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Created Date</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Status</span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($payment_information_logs) == 0)
                                    <tr class="user-row border-bottom">
                                        <td colspan="8" class="text-center">No Payment Information Logs Found</td>
                                    </tr>
                                @endif
                                @foreach ($payment_information_logs as  $payment_information_log)
                                    <tr class="user-row border-bottom">
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->payment_id }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->order_id }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->order_reference }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->method }}</span>
                                        </td>
                                        {{-- <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ !empty($payment_information_log->amount) ? number_format($payment_information_log->amount , 2) : 0 }}</span>
                                        </td> --}}
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->order_type }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->payment_date }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">{{ $payment_information_log->created_date }}</span>
                                        </td>
                                        <td class="remove_padding">
                                            <span class="d-flex table-row-item">
                                                @if ($payment_information_log->status == 'success')
                                                    <span class="badge badge-success">Success</span>
                                                @elseif ($payment_information_log->status == 'failed')
                                                    <span class="badge badge-danger">Failed</span>
                                                @elseif ($payment_information_log->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-secondary">Unknown</span>
                                                @endif
                                            </span>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="mobile-screen">
                                        {{ $payment_information_logs->links('pagination.custom_pagination') }}
                                    </td>
                                </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endsection
</div>
</div>
@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
        .delete_grey {
            background-color: #e7e7e7;
        }
        .text-dark {
            color: #000 !important;
        }
        .remove_padding {
            padding: 0.05rem 1rem;
        }
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
                width: 50% !important;
            }

            .filter-row-mobile-secreen {
                margin-top: 14px !important;
                padding-left: 30px !important;
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

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: #f1eaea;
            color: #B42318;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-secondary {
            color: #8e8b8b !important;
            background-color: #d0dce6 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-primary {
            background-color: #d9eff8;
            color: #339AC6 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
        .drop_down_items_user {
            padding: 0rem !important;
            margin: 0rem !important;
        }
        .shortText_font_size {
            font-size: 14px;
        }
    </style>
@stop
@section('js')
@stop
