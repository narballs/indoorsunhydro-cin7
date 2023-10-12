@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop
@section('content')
    

    <div class="table-wrapper">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible mt-2">
                <a href="#" class="close text-white" data-dismiss="alert" aria-label="close">&times;</a>
                {{ $message }}
            </div>
        @endif
        <div class="card-body product_secion_main_body">
            
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                DAILY API LOGS
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                    role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div id="admin-users"></div>
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table  bg-white mb-0 table-users" id="user-table">
                        <tr>
                            <thead>
                                <tr class="table-header-background">
                                    <td width="250">
                                        <span class="d-flex table-row-item">Date</span>
                                    </td>
                                    <td width="250">
                                        <span class="d-flex table-row-item">Endpoint</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Count</span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_count = 0; ?>
                                @foreach ($daily_api_logs as $key => $daily_api_log)
                                    <tr id="row-{{ $daily_api_log->id }}" class="user-row border-bottom">
                                        <td class="user_name">
                                            {{ date('F j, Y', strtotime($daily_api_log->date)) }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ strtoupper(str_replace('_', ' ', $daily_api_log->api_endpoint)) }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ $daily_api_log->count }}
                                            <?php $total_count += $daily_api_log->count; ?>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="user-row border-bottom">
                                    <td colspan="2" class="text-right text-bold align-middle">Total Count:</td>
                                    <td class="text-left text-bold align-middle">
                                        {{ number_format($total_count) }}
                                        <a href="{{ route('update-all-products') }}" class="btn btn-primary text-white ml-3">Update All Products</a>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        @endsection
    </div>
</div>
@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
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
                padding-top: 9px !important;
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

            .order_heading {
                font-size: 18px !important;
                font-family: Poppins, sans-serif !important;
                font-weight: 500 !important;
                line-height: 24px !important;
                letter-spacing: 0.252px;
                color: #242424 !important;
                margin-top: 24px !important;
                margin-bottom: 0.5rem !important;
                margin-left: -8px !important;
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
    </style>
@stop