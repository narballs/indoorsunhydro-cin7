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
                        <div class="col-md-10">
                            <p class="product_heading">
                                Api Logs
                            </p>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface justify-content-between" style="margin-top: 12px !important;">
                        <div class="col-md-2 product_search">
                            <div class="has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/users" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div id="admin-users"></div>
                <table class="table border table-users" id="user-table">
                    <tr>
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item">
                                    <span class="tabel-checkbox-user">
                                        <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                    </span>
                                    <span class="table-row-heading">
                                        <i class="fas fa-arrow-up"></i>
                                    </span>
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
                                    <td class="d-flex user-table-items">
                                        <span class="tabel-checkbox-user">
                                            <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                        </span>
                                        <span class="table-row-heading-user">
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
