@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')

@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mobile_heading">
                            <p class="product_heading">
                                Customer
                            </p>
                        </div>
                        <div class="col-md-6 text-right create_bnt">
                            <a href="{{ 'customer/create' }}" class="btn create-new-order-btn">
                                Create New Customer +
                            </a>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface" style="margin-top: 12px !important;">
                        <div class="col-md-4 product_search">
                            <div class="has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/customers" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" value="pending-approval" id="pending_approval" name="pending_approval">
                            <button class="btn-sm btn-primary border-0 p-1 px-2 rounded-pill" type="button" onclick="pending_approval()">New User Pending Approval</button>
                        </div>
                        <div class="col-md-5">
                            <div class="row filter-row-mobile-secreen">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 fillter-mobile-screen">
                                    <select name="active_customer" id="active_customer" onchange="perPage()"
                                        class="form-control" style="    margin-left: -7px;">
                                        <option value="" class="form-control">Active/Disabled </option>
                                        <option value="active-customer" class="form-control"
                                            {{ isset($activeCustomer) && $activeCustomer == 'active-customer' ? 'selected="selected"' : '' }}>
                                            Active </option>
                                        <option value="disable-customer" class="form-control"
                                            {{ isset($activeCustomer) && $activeCustomer == 'disable-customer' ? 'selected="selected"' : '' }}>
                                            Disabled </option>
                                        <option value="pending-approval" class="form-control"
                                            {{ isset($activeCustomer) && $activeCustomer == 'pending-approval' ? 'selected="selected"' : '' }}>
                                            Pending Approval </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table bg-white mb-0 table-customer" id="table">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item mt-0">
                                    <div class="custom-control custom-checkbox tabel-checkbox d-flex">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label ml-4"></label>

                                        <span class="table-row-heading-order d-flex">
                                            <input type="hidden" class="" name="sort_by_desc" id="sort_by_desc" value="Desc">
                                            <input type="hidden" class="ml-1" name="sort_by_asc" id="sort_by_asc" value="Asc">
                                            <i class="fas fa-arrow-up  sm-d-none" style="font-size:14.5px ;" onclick="sortbyDesc()"></i>
                                            <i class="fas fa-arrow-down  sm-d-none ml-1" style="font-size:14.5px ;" onclick="sortbyAsc()"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> QcomID</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Cin7ID</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Full Name </span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Merged</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Price Tier</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Company</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Email</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Notes</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Created Date</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Status</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> </span>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="searched">
                            <?php $count = 0; ?>
                            @foreach ($contacts as $key => $contact)
                                <?php $count++; ?>
                                @include('admin.customer_row')
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="11">
                                    {{ $contacts->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

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
@stop

@section('js')
    <script>
        function sortbyDesc() {
            var search = $('#search').val();
            var sort_by = $('#sort_by_desc').val();
            var basic_url = 'customers?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url + `&sort_by_desc=${sort_by}`;
            }
            window.location.href = basic_url
        }
        function sortbyAsc() {
            var search = $('#search').val();
            var sort_by = $('#sort_by_asc').val();
            var basic_url = 'customers?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url+`&sort_by_asc=${sort_by}`;
            }
            window.location.href = basic_url
        }
        $('.customer-row-none').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.customer_name').children('a').addClass('text-successs');
            let tet = $(this).children('.customer_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });

        $('.customer-row-none').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.customer_name').children('a').removeClass('text-successs');
            let tet = $(this).children('.customer_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });

        function perPage() {
            var perPage = $('#per_page').val();
            var search = $('#search').val();
            var activeCustomer = $('#active_customer').val();
            if (perPage != '') {
                var basic_url = 'customers?&search=' + search;
            }
            if (activeCustomer != '') {
                basic_url = basic_url + `&active-customer=${activeCustomer}`;
            }
            window.location.href = basic_url;
        }
        function pending_approval() {
            var perPage = $('#per_page').val();
            var search = $('#search').val();
            var pending_approval = $('#pending_approval').val();
            if (perPage != '') {
                var basic_url = 'customers?&search=' + search;
            }
            if (pending_approval != '') {
                basic_url = basic_url + `&pending-approval=${pending_approval}`;
            }
            window.location.href = basic_url;
        }

        function search() {
            var $rows = $('#table tr');
            $('#search').keyup(function() {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                $rows.show().filter(function() {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });
        }

        function customer_search() {
            var typingTimer;
            var doneTypingInterval = 1000;
            var $input = $('#search');
            $input.on('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input.on('keydown', function() {
                clearTimeout(typingTimer);
            });

            function doneTyping() {
                var val = $('#search').val();
                jQuery.ajax({
                    url: "{{ url('admin/customersearch') }}",
                    method: 'GET',
                    data: {
                        "value": val,
                    },
                    cache: false,
                    success: function(response) {
                        $('.table-customer tbody').html(response);
                    }
                });
            }
        }

        $(document).on('click', '#selectAll', function(e) {
            // var table = $(e.target).closest('table');
            // $('td input[class="all_checkboxes"]', table).prop('checked', this.checked);
            $('.all_checkboxes').prop('checked', this.checked);
        });

        function disableSecondary(secondary_id) {
            jQuery.ajax({
                url: "{{ url('admin/disable-secondary') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contactId": secondary_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.msg == 'success') {
                        window.location.reload();
                    }
                }
            });
        }
    </script>
@stop
