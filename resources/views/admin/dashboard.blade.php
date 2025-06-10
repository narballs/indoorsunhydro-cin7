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
                        <div class="col-md-2 mobile-screen-flash-message">
                            <div class="row">
                                <div class="col-md-11 my-2" id="div_message"></div>
                                <div class="col-md-1">
                                    <div id="div_import_contacts" class="spinner-border hide" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div id="div_import_product_prices" class="spinner-border hide" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 create_bnt mobile_fulfill_div">
                            <div class="d-flex justify-content-between">
                                <span class="create_new_btn_mbl mr-2">
                                    <button type="button" class="btn btn-info rounded btn-import-specific-contact text-white" onclick="importSpecificContact()">
                                        Import Specific Contact
                                    </button>
                                </span>
                                <span class="create_new_btn_mbl mr-2">
                                    <button type="button" class="btn create_new_product_btn reduce_font_size btn-import-contacts text-dark">
                                        Import Contacts +
                                    </button>
                                </span>
                                <span class="create_new_btn_mbl mr-2">
                                    <button type="button" class="btn create_new_product_btn reduce_font_size btn-update-product-prices text-dark">
                                        Update Product Prices
                                    </button>
                                </span>
                                <span class="create_new_btn_mbl mr-2">
                                    <button type="button" class="btn create_new_product_btn reduce_font_size btn-empty-failed-jobs text-dark">
                                        Empty Failed Jobs
                                    </button>
                                </span>

                                <span class="create_new_btn_mbl mr-2">
                                    <a href="{{route('reset_cin7_api_keys')}}" class="btn create_new_product_btn reduce_font_size text-dark">
                                        Reset Cin7Api Keys
                                    </a>
                                </span>

                                <span class="create_new_btn_mbl">
                                    <a href="{{route('send_stock_summary_emails')}}" class="btn create_new_product_btn reduce_font_size text-dark">
                                        Send Stock Notification Summary
                                    </a>
                                </span>

                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="d-flex align-items-center ">
                                <span class="">
                                    {{-- <a href="{{route('google.authorize')}}" type="button" class="btn btn-info text-white">
                                        Google Sync +
                                    </a> --}}
                                   <strong> Last Google Merchant Center Sync: {{ !empty($last_google_sync) ? $last_google_sync->last_updated_at : 'Null'}}</strong>

                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-3 order-search">
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
    {{-- import Specific Contact--}}
    <div class="modal fade" id="importSpecific" tabindex="-1" aria-labelledby="importSpecificContact" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-center text-bold">Import Specific Contact</h5>
                    <div class="mb-3">
                        <div class="email-success">
                            <span class="text-success"></span>
                        </div>
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="contactEmail" class="form-control" placeholder="Enter contact email" required>
                        <div class="email-error">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="spinner-border text-warning import-specific-contact-spinner d-none mx-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <button type="button" class="btn btn-primary mx-2" onclick="importContact()">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- import Specific Contact end--}}
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
        .reduce_font_size {
            font-size: 12px;
        }
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#div_import_contacts').hide();
            $('#div_import_product_prices').hide();

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
                                '<div class="alert alert-success alert-dismissible" role="alert"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                response.message + '</div>');
                        } else {
                            $('#div_message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response
                                .message + '</div>');
                        }
                    }
                });

            });

            $('.btn-update-product-prices').on('click', function () {
                var btn = $(this);
                btn.prop('disabled', true);
                $('#div_import_product_prices').show();
                $('#div_message').html('Updating Product Prices... Please do not close this window.');

                $.ajax({
                    url: "{{ url('admin/commands/update-product-prices') }}",
                    method: 'get',
                    data: {},
                    success: function(response) {
                        btn.prop('disabled', false);
                        $('#div_import_product_prices').hide();
                        if (response.status == 'success') {
                            $('#div_message').html(
                                '<div class="alert alert-success alert-dismissible" role="alert"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                response.message + '</div>');
                        } else {
                            $('#div_message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response
                                .message + '</div>');
                        }
                    }
                });
            });


            $('.btn-empty-failed-jobs').on('click', function () {
                var btn = $(this);
                btn.prop('disabled', true);
                $('#div_message').html('Emptying Failed Jobs... Please do not close this window.');

                $.ajax({
                    url: "{{ url('admin/empty-failed-jobs') }}",
                    method: 'get',
                    data: {},
                    success: function(response) {
                        btn.prop('disabled', false);
                        if (response.status == 'success') {
                            $('#div_message').html(
                                '<div class="alert alert-success alert-dismissible" role="alert"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                response.msg + '</div>');
                        } else {
                            $('#div_message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response
                                .msg + '</div>');
                        }
                    }
                });
            });
        });

        function importSpecificContact() {
            $('#importSpecific').modal('show');
        }

        // function importContact() {
        //     var email = $('#contactEmail').val();
        //     $('.email-error span').text('');
        //     if (email === '') {
        //         $('.email-error span').text('Email is required');
        //         return;
        //     }

        //     $('.import-specific-contact-spinner').removeClass('d-none');

        //     $.ajax({
        //         url: "{{ url('admin/commands/import_specific_contact') }}",
        //         method: 'get',
        //         data: {
        //             email: email,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             // $('.import-specific-contact-spinner').addClass('d-none');
        //             if (response.status === 'success') {
        //                 $('.email-success span').html('contact imported successfully');

        //                 setTimeout(function() {
        //                     $('#importSpecific').modal('hide');
        //                     location.reload();
        //                 }, 5000);

                        
        //             } else {
        //                 $('#email-error span').html(response.message);
        //             }
        //         },
        //         error: function(xhr) {
        //             $('.import-specific-contact-spinner').addClass('d-none');
        //             $('#email-error span').html( xhr.responseText);
        //         }
        //     });
        // }

        function importContact() {
            var email = $('#contactEmail').val();
            $('.email-error span, .email-success span').text('');
            
            if (email === '') {
                $('.email-error span').text('Email is required');
                return;
            }

            $('.import-specific-contact-spinner').removeClass('d-none');

            $.ajax({
                url: "{{ url('admin/commands/import_specific_contact') }}",
                method: 'GET',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.import-specific-contact-spinner').addClass('d-none');
                    
                    if (response.status === 'success') {
                        $('.email-success span').html('Contact imported successfully');

                        setTimeout(function() {
                            $('#importSpecific').modal('hide');
                            location.reload();
                        }, 1000);
                    } else {
                        $('.email-error span').html(response.message || 'An error occurred.');
                    }
                },
                error: function(xhr) {
                    $('.import-specific-contact-spinner').addClass('d-none');
                    let errorMessage = 'An unexpected error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    $('.email-error span').html(errorMessage);
                }
            });
        }


    </script>
@endsection
