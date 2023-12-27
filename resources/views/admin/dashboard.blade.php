@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@endsection
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
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                Dashboard
                            </p>
                        </div>
                        <div class="col-md-3 mobile-screen-flash-message">
                            <div class="row">
                                <div class="col-md-6 m-auto" id="div_message"></div>
                                <div class="col-md-1">
                                    <div id="div_import_contacts" class="spinner-border hide" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 create_bnt d-flex justify-content-end mobile_fulfill_div">
                            <div class="d-flex">
                                <span class="create_new_btn_mbl">
                                    <button type="button" class="btn create_new_product_btn btn-import-contacts">
                                        Import Contacts +
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="d-flex align-items-center ">
                                <span class="">
                                    <a href="{{route('google.authorize')}}" type="button" class="btn btn-info text-white">
                                        Google Sync +
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/orders" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css?v2">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css?v2') }}">
    <style>
        @media(min-width:280px) and (max-width: 425px) {
            .go_to_site{
                position: absolute;
                top: -0.3rem;
                width: 7rem;
            }
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

            .mobile-screen-flash-message {
                top: 64px !important;
                width: 386px !important;
                left: 42px !important;
            }

            .spinner-border {
                display: inline-block;
                width: 2rem;
                height: 2rem;
                vertical-align: -0.125em;
                border: 0.25em solid currentColor;
                border-right-color: transparent;
                border-radius: 50%;
                -webkit-animation: .75s linear infinite spinner-border;
                animation: .75s linear infinite spinner-border;
                display: flex;
                justify-content: center;
                align-items: center;
                margin-left: 163px !important;
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
            color: #fff;
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
            padding: 7px !important;
            border-radius: 6px;
        }

        .badge-primary {
            background-color: #339AC6;
            color: #339AC6 !important;
            padding: 5px;
        }
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#div_import_contacts').hide();

            $('body').on('click', '.btn-import-contacts', function() {
                var btn = $(this);
                btn.prop('disabled', true);
                $('#div_import_contacts').show();
                $('#div_message').html('Importing Contacts... Please do not close this window.');

                $.ajax({
                    url: "{{ url('admin/commands/import_contacts') }}",
                    method: 'get',
                    data: {},
                    success: function(response) {
                        btn.prop('disabled', false);
                        $('#div_import_contacts').hide();

                        if (response.status == 'success') {
                            $('#div_message').html(
                                '<div class="alert alert-success" role="alert">' +
                                response.message + '</div>');
                        } else {
                            $('#div_message').html(
                                '<div class="alert alert-danger" role="alert">' + response
                                .message + '</div>');
                        }
                    }
                });

            });
        });
    </script>
@endsection
