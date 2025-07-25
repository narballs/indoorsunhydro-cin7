@extends('adminlte::page')

@section('title', 'All Admins')

@section('content')
@if (\Session::has('success'))
    <div class="alert alert-success">{{ \Session::get('success') }}</div>
@endif

@if (\Session::has('error'))
    <div class="alert alert-danger">{{ \Session::get('error') }}</div>
@endif

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Admin Notification Preferences</h3>
    </div>
    <div class="card-body">
        <button class="btn btn-primary select_specific_admins mb-3" onclick="select_specific_admins()" style="display: none;">Submit</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Receive Order Notifications</th>
                        <th>Receive Label Notifications</th>
                        <th>Receive Accounting Reports</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($admins as $admin)
                        @php
                            $record = $specific_admin_notifications->firstWhere('user_id', $admin->id);
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $admin->email }}</td>

                            <!-- Order Notification -->
                            <td>
                                <div class="form-group mb-0">
                                    <label class="switch">
                                        <input type="checkbox"
                                               class="switch-input admin_users"
                                               name="admin_users[]"
                                               value="{{ $admin->id }}"
                                               onclick="select_user()"
                                               {{ $record && $record->receive_order_notifications ? 'checked' : '' }}>
                                        <span class="switch-label" data-on="Yes" data-off="No"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </td>

                            <!-- Label Notification -->
                            <td>
                                <div class="form-group mb-0">
                                    <label class="switch">
                                        <input type="checkbox"
                                               class="switch-input label_admin_users"
                                               name="label_admin_users[]"
                                               value="{{ $admin->id }}"
                                               onclick="select_user()"
                                               {{ $record && $record->receive_label_notifications ? 'checked' : '' }}>
                                        <span class="switch-label" data-on="Yes" data-off="No"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </td>

                            <!-- Accounting Notification -->
                            <td>
                                <div class="form-group mb-0">
                                    <label class="switch">
                                        <input type="checkbox"
                                               class="switch-input accounting_admin_users"
                                               name="accounting_admin_users[]"
                                               value="{{ $admin->id }}"
                                               onclick="select_user()"
                                               {{ $record && $record->receive_accounting_reports ? 'checked' : '' }}>
                                        <span class="switch-label" data-on="Yes" data-off="No"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style type="text/css">
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

        .bg_success {
            /* background: rgb(186 235 137 / 20%) !important; */
            color: #319701 !important;
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
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_danger {
            color: #DC4E41 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 70px;
            user-select: none;
        }
        .switch-input {
            display: none;
        }
        .switch-label {
            display: block;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid #eceeef;
            border-radius: 20px;
        }
        .switch-label:before, .switch-label:after {
            content: attr(data-off);
            display: block;
            width: 50%;
            float: left;
            text-align: center;
            line-height: 30px;
            font-size: 14px;
            color: white;
            background-color: #eceeef;
            transition: 0.3s;
        }
        .switch-input:checked + .switch-label:before {
            background-color: #28a745;
            content: attr(data-on);
        }
        .switch-input:checked + .switch-label:after {
            background-color: #28a745;
        }
        .switch-handle {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 30px;
            height: 28px;
            background: #fff;
            border-radius: 50%;
            transition: left 0.3s;
        }
        .switch-input:checked + .switch-label + .switch-handle {
            left: 38px;
        }
    </style>

@stop


@section('js')
{{-- <script>
    $(document).ready(function() {
        $('.select_specific_admins').hide();
        $('.select_user_div').hide();
    });
    function select_user(){
        var admin_users = [];
        $.each($("input[name='admin_users[]']:checked"), function(){
            admin_users.push($(this).val());
        });
        if (admin_users.length > 0){
            $('.select_specific_admins').show();
        }   else{
            $('.select_specific_admins').hide();
        }
    }
    function select_specific_admins(){
        $('.select_specific_admins').hide();
        var admin_users = [];
        $.each($("input[name='admin_users[]']:checked"), function(){
            admin_users.push($(this).val());
        });
        if(admin_users.length > 0){
            $.ajax({
                url: "{{ route('send_email_to_specific_admin') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "admin_users": admin_users,
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == true){
                        $('.select_user_div').show()
                        $('.select_user_text').html(response.msg)
                        setTimeout(function(){ 
                            window.location.href = "{{ route('all_admins') }}";
                        }, 3000);
                    }
                    else{
                        $('.select_user_div').show()
                        $('.select_user_text').html('Something went wrong');
                        setTimeout(function(){ 
                            window.location.href = "{{ route('all_admins') }}";
                        }, 3000);

                    }
                }
            });
        }
        else{
            alert('Please select atleast one admin');
        }
    }
</script> --}}
<script>
    $(document).ready(function() {
        $('.select_specific_admins').hide();
        $('.select_user_div').hide();
    });

    function select_user() {
        let checked = $('input[type="checkbox"]:checked').length > 0;
        if (checked) {
            $('.select_specific_admins').show();
        } else {
            $('.select_specific_admins').hide();
        }
    }

    function select_specific_admins() {
        $('.select_specific_admins').hide();

        let admin_users = [];
        $("input.admin_users:checked").each(function() {
            admin_users.push($(this).val());
        });

        let label_admin_users = [];
        $("input.label_admin_users:checked").each(function() {
            label_admin_users.push($(this).val());
        });

        let accounting_admin_users = [];
        $("input.accounting_admin_users:checked").each(function() {
            accounting_admin_users.push($(this).val());
        });

        if (admin_users.length || label_admin_users.length || accounting_admin_users.length) {
            $.ajax({
                url: "{{ route('send_email_to_specific_admin') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    admin_users: admin_users,
                    label_admin_users: label_admin_users,
                    accounting_admin_users: accounting_admin_users
                },
                success: function(response) {
                    Swal.fire({
                        icon: response.status ? 'success' : 'error',
                        title: response.status ? 'Success' : 'Error',
                        text: response.msg,
                        confirmButtonColor: '#28a745',
                    }).then((result) => {
                        if (response.status) {
                            location.reload();
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while processing your request.',
                        confirmButtonColor: '#dc3545',
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one admin.',
                confirmButtonColor: '#ffc107',
            });
        }
    }

</script>
@stop