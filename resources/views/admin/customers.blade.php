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
        <div class="card-body mt-2">
            <div class="table-title">
                <span>
                    <h1>Customer</h1>
                </span>
                <div class="row justify-content-between mb-2">
                    <div class="col-sm-2">
                        <div class="search-box">
                            <a href="{{ 'customer/create' }}"><input type="button" value="Create New Customer"
                                    class="form-control btn btn-primary" placeholder="Create New">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="active_customer" id="active_customer" onchange="perPage()" class="form-control">
                            <option value="" class="form-control">Active/Disabled </option>
                            <option value="active-customer" class="form-control"
                                {{ isset($activeCustomer) && $activeCustomer == 'active-customer' ? 'selected="selected"' : '' }}>
                                Active </option>
                            <option value="disable-customer" class="form-control"
                                {{ isset($activeCustomer) && $activeCustomer == 'disable-customer' ? 'selected="selected"' : '' }}>
                                Disabled </option>
                        </select>

                    </div>
                    <div class="col-md-4">
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <span class="input-group-btn">
                                    <button class="btn btn-info btn-lg" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                                <form method="get" action="/admin/customers">
                                    <input type="text" class="form-control input-lg" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <table class="table border table-customer" id="table">
                    <thead>
                        <tr>
                            <th>QcomID <i class="fa fa-sort"></th>
                            <th>Cin7ID <i class="fa fa-sort"></th>
                            <th>Full Name <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa fa-sort"></i></th>
                            <th>Merged<i class="fa fa-sort"></i></th>
                            <th>Price Tier<i class="fa fa-sort"></i></th>
                            <th>Company<i class="fa fa-sort"></i></th>
                            <th>Email<i class="fa fa-sort"></i></th>
                            <th class="">Notes<i class="fa fa-sort"></i></th>
                            <th class="">Actions <i class="fa fa-sort"></i></th>
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
                <div class="row">
                    <div class="col-md-12 mt-3 border-top">
                        {{ $contacts->appends(Request::all())->links() }}
                    </div>
                    {{-- <div class="col-md-2">
                        <select name="per_page" id="per_page" onchange="perPage()">
                            <option value="10" {{ isset($perPage) && $perPage == 10 ? 'selected="selected"' : '' }}>10
                            </option>
                            <option value="20" {{ isset($perPage) && $perPage == 20 ? 'selected="selected"' : '' }}>20
                            </option>
                            <option value="30" {{ isset($perPage) && $perPage == 30 ? 'selected="selected"' : '' }}>30
                            </option>
                            <option value="30">30</option>
                        </select>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            color: #fff;
            /* background-color: #28a745; */
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 7px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-warning {
            color: #1f2d3d;
            background-color: #fce9a9;
            color: #ffc107 !important;
            padding: 5px;
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

        .badge-warning {
            color: #1f2d3d;
            background-color: #fce9a9;
            color: #ffc107 !important;
            padding: 5px;
        }
    </style>
@stop

@section('js')
    <script>
        $('.customer-row').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.customer_name').children('a').addClass('text-successs');
            let tet = $(this).children('.customer_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });

        $('.customer-row').mouseleave(function() {
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
                var basic_url = 'customers?perPage=' + perPage + '&search=' + search;
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
    </script>
@stop
