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
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                Api Logs
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                    role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-4 create_bnt d-flex justify-content-end mobile_fulfill_div d-none">
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/api-sync-logs" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
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
                                    <td class="d-flex table-row-item mt-0">
                                        <div class="custom-control custom-checkbox tabel-checkbox">
                                            <input class="custom-control-input custom-control-input-success checkbox-table"
                                                type="checkbox" id="selectAll" value="">
                                            <label for="selectAll" class="custom-control-label ml-4"></label>

                                            <span class="table-row-heading-order">
                                                <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Endpoint</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Description</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Last Synced</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Records updated</span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($api_logs as $key => $log)
                                    <tr id="row-{{ $log->id }}" class="user-row border-bottom">
                                        <td class="d-flex table-items" style="padding-top: 16px !important;">
                                            <div class="custom-control custom-checkbox tabel-checkbox">
                                                <input class="custom-control-input custom-control-input-success sub_chk"
                                                    data-id="{{ $log->id }}" type="checkbox"
                                                    id="separate_check_{{ $log->id }}">
                                                <label for="separate_check_{{ $log->id }}"
                                                    class="custom-control-label ml-4"></label>
                                            </div>
                                            <span class="table-row-heading-order sm-d-none">
                                                {{ $key + 1 }}
                                            </span>
                                        </td>
                                        <td class="user_name">
                                            {{ $log->end_point }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ $log->desription }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ $log->last_synced }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ $log->record_count }}
                                        </td>
                                    </tr>
                                @endforeach
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
                margin-top: 2.563rem;
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
    </style>
@stop
@section('js')
    <script>
        $('.user-row-none').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.user_name').children('span').addClass('text-successs');
            bg_success = $(this).children('.background_success').children('.background_success_1').addClass(
                'background-success');
            bg_success = $(this).children('.background_warning').children('.background_warning_1').addClass(
                'background-warning');
            bg_success = $(this).children('.is_parent').children('.is_parent_1').addClass(
                'background-secondary');
            bg_success = $(this).children('.is_parent').children('.is_parent_0').addClass(
                'background-secondary');

            bg_success = $(this).children('.background_contact_id').children('.background_secondary_1').addClass(
                'background-secondary');
            bg_success = $(this).children('.background_contact_id').children('.background_primary_1').addClass(
                'background-primary');

            let tet = $(this).children('.user_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.user-row-none').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.user_name').children('span').removeClass('text-successs');

            bg_success = $(this).children('.background_success').children('.background_success_1').removeClass(
                'background-success');
            bg_success = $(this).children('.is-approved').children('.is_approded_0').removeClass(
                'background-warning');

            bg_success = $(this).children('.is_parent').children('.is_parent_1').removeClass(
                'background-secondary');
            bg_success = $(this).children('.is_parent').children('.is_parent_0').removeClass(
                'background-secondary');
            bg_success = $(this).children('.background_contact_id').children('.background_secondary_1').removeClass(
                'background-secondary');
            bg_success = $(this).children('.background_contact_id').children('.background_primary_1').removeClass(
                'background-primary');

            let tet = $(this).children('.user_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });

        function adminUsers() {
            $('#user-table').addClass('d-none');
            $('#pageination').addClass('d-none');
            $.ajax({
                url: "{{ url('admin/admin-users') }}",
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#admin-users').html(response);
                }
            });
        }

        function userFilter() {
            var usersData = $('#users').val();
            var search = $('#search').val();
            var secondaryUser = $('#secondary-user').val();
            if (usersData != '') {
                basic_url = `users?usersData=${usersData}`;
            }
            if (secondaryUser != '') {
                basic_url = basic_url + `&secondaryUser=${secondaryUser}`;
            }

            window.location.href = basic_url;
        }

        function assignParent(userid) {
            var user_id = userid;
            $('#exampleModal').modal('show');
            $('#child_id').val(user_id);

        }

        function suggestion() {
            var primary_contact = $('#primary_contact').val();
            console.log(primary_contact);
            var res = '';
            $.ajax({
                url: "{{ url('admin/get-parent') }}",
                method: 'GET',
                data: {
                    term: primary_contact
                },
                success: function(response) {
                    $.each(response, function(key, value) {
                        console.log(value.firstName);
                        res += '<option value=' + value.contact_id + '>' + value.firstName +
                            '</option>';
                        console.log(res);
                    });
                    $('#child').html(res);
                },
            });
        }

        function assign() {
            var primary_name = $('#primary_contact').val();
            var user_id = $('#child_id').val();
            var primary_id = $('#child').val();
            jQuery.ajax({
                url: "{{ url('admin/assign-parent-child') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    primary_name: primary_name,
                    user_id: user_id,
                    primary_id: primary_id,
                },
                success: function(response) {

                    if (response.status == 200) {
                        $('#spinner2').addClass('d-none');

                        $('#exampleModal').modal('hide');
                        $('#secondary_id').trigger("reset");
                        window.location.reload();

                    }
                }

            });
        }

        $(document).on('click', '#selectAll', function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });
    </script>
@stop
