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
                        <div class="col-md-10">
                            <p class="product_heading">
                                Customer
                            </p>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end create_bnt">
                            <a href="{{ 'customer/create' }}" class="btn create-new-order-btn">
                                Create New Customer +
                            </a>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface justify-content-between"
                        style="margin-top: 12px !important;">
                        <div class="col-md-2 product_search">
                            <div class="has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/customers" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="active_customer" id="active_customer" onchange="perPage()" class="form-control"
                                style="    margin-left: -7px;">
                                <option value="" class="form-control">Active/Disabled </option>
                                <option value="active-customer" class="form-control"
                                    {{ isset($activeCustomer) && $activeCustomer == 'active-customer' ? 'selected="selected"' : '' }}>
                                    Active </option>
                                <option value="disable-customer" class="form-control"
                                    {{ isset($activeCustomer) && $activeCustomer == 'disable-customer' ? 'selected="selected"' : '' }}>
                                    Disabled </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <table class="table border table-customer" id="table">
                    <thead>
                        <tr class="table-header-background">
                            <td class="d-flex table-row-item">
                                <span class="tabel-checkbox-user">
                                    <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                </span>
                                <span class="customer-table-row-heading ">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
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
                                <span class="d-flex table-row-item"> Status</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item"> Action</span>
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
                </table>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            {{ $contacts->links('pagination.custom_pagination') }}
                        </td>
                    </tr>
                </tfoot>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
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
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
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
