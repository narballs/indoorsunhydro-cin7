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
                                Users Management
                            </p>
                        </div>
                        <div class="col-md-6 text-right create_bnt">
                            <a href="{{ route('users.create') }}" class="btn create-new-order-btn">
                                Create new user +
                            </a>
                        </div>
                    </div>
                    <form method="get" action="/admin/users" class="mb-2 w-100">
                        <div class="row search_row_admin-interface align-items-end my-2">
                            <div class="col-md-7">
                                <div class="row align-items-end">
                                    <div class="col-md-4 product_search">
                                        <div class="has-search">
                                            <span class="fa fa-search form-control-feedback"></span>
                                            
                                                <input type="text" class="form-control border-0" id="search" name="search"
                                                    placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <label for="">
                                                    From Date
                                                </label>
                                                <input type="date" name="from_date" id="user_from_date" class="form-control"
                                                    value="{{ isset($from_date) ? $from_date : '' }}" placeholder="From Date">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">
                                                    To Date
                                                </label>
                                                <input type="date" name="to_date" id="user_to_date" class="form-control"
                                                    value="{{ isset($to_date) ? $to_date : '' }}" placeholder="From Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row filter-row-mobile-secreen">
                                    <div class="col-md-3 fillter-mobile-screen">
                                        <select name="usersData" id="users" class="form-control"
                                            style="height: 39px;margin-top: -7px;">
                                            <option value="all" class="form-control">All</option>
                                            <option value="admin-user" class="form-control"
                                                {{ isset($usersData) && $usersData == 'admin-user' ? 'selected="selected"' : '' }}>
                                                Admin Users </option>
                                            <option value="cin7-merged" class="form-control"
                                                {{ isset($usersData) && $usersData == 'cin7-merged' ? 'selected="selected"' : '' }}>
                                                Cin7 Merged</option>
                                            <option value="not-merged" class="form-control"
                                                {{ isset($usersData) && $usersData == 'not-merged' ? 'selected="selected"' : '' }}>
                                                Not Merged</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 fillter-mobile-screen">
                                        <select name="secondary_user" id="secondary-user" 
                                            class="form-control select-primary-users" style="">
                                            <option value="all" class="form-control" {{ isset($secondary_user) && $secondary_user == 'all' ? 'selected="selected"' : '' }}>Secondary/Primary</option>
                                            <option value="secondary-user" class="form-control"
                                                {{ isset($secondary_user) && $secondary_user == 'secondary-user' ? 'selected="selected"' : '' }}>
                                                Secondary Users
                                            </option>
                                            <option value="primary-user" class="form-control"
                                                {{ isset($secondary_user) && $secondary_user == 'primary-user' ? 'selected="selected"' : '' }}>
                                                Primary Users</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 fillter-mobile-screen">
                                        <button type="submit" class="btn btn-sm btn-primary btn-sm">Filter</button>

                                        @if (request()->has('search') || request()->has('from_date') || request()->has('to_date') || request()->has('usersData') || request()->has('secondary_user'))
                                            <a href="{{ url('/admin/users') }}" class="btn btn-info btn-sm text-white ml-2">Reset</a>
                                        @endif
                                        <a href="{{ request()->fullUrlWithQuery(['download_csv' => '1']) }}">
                                            <button type="button" class="btn btn-sm btn-danger ml-2">Download CSV</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div id="admin-users"></div>
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table bg-white table-users mb-0" id="user-table">
                        <tr>
                            <thead>
                                <tr class="table-header-background">
                                    <td class="d-flex table-row-item align-items-center">
                                        <span class="mt-0">
                                            <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                        </span>
                                        <span class="table-row-heading mt-0">
                                            <input type="hidden" class="" name="sort_by_desc" id="sort_by_desc" value="Desc">
                                            <input type="hidden" class="ml-1" name="sort_by_asc" id="sort_by_asc" value="Asc">
                                            <i class="fas fa-arrow-up  sm-d-none" style="font-size:14.5px ;" onclick="sortbyDesc()"></i>
                                            <i class="fas fa-arrow-down  sm-d-none ml-1" style="font-size:14.5px ;" onclick="sortbyAsc()"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Full Name
                                            <span class="ml-1 d-flex">
                                                <input type="hidden" class="" name="sort_by_name" id="sort_name_by_desc" value="Desc">
                                                <input type="hidden" class="ml-1" name="sort_by_name" id="sort_name_by_asc" value="Asc">
                                                <i class="fas fa-arrow-up  sm-d-none text-dark" style="font-size:14.5px ;" onclick="sortnamebyDesc()"></i>
                                                <i class="fas fa-arrow-down  sm-d-none text-dark ml-1" style="font-size:14.5px ;" onclick="sortnamebyAsc()"></i>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Email
                                            <span class="ml-1">
                                                <input type="hidden" class="" name="sort_by_email" id="sort_email_by_desc" value="Desc">
                                                <input type="hidden" class="ml-1" name="sort_by_email" id="sort_email_by_asc" value="Asc">
                                                <i class="fas fa-arrow-up  sm-d-none text-dark" style="font-size:14.5px ;" onclick="sortemailbyDesc()"></i>
                                                <i class="fas fa-arrow-down  sm-d-none text-dark ml-1" style="font-size:14.5px ;" onclick="sortemailbyAsc()"></i>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Cin7 User-ID </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Company (Account aka Parent) </span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Secondary Contact Company</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Type</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Created Date</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"> Roles</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item"></span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $user)
                                    @foreach ($user->contact as $contact)
                                        @php
                                            $contact_switch_id;
                                            if ($contact->contact_id) {
                                                $contact_switch_id = $contact->contact_id;
                                            } else {
                                                $contact_switch_id = $contact->secondary_id;
                                            }

                                            if($contact->type == 'Supplier'){
                                                continue;
                                            }
                                        @endphp
                                        <tr id="row-{{ $user->id }}" data-deleted="{{$contact->deleted_at}}" title="{{!empty($contact->deleted_at) ? 'deleted on ' . $contact->deleted_at : ''}}" class="user-row border-bottom {{!empty($contact->deleted_at) ? 'delete_grey' : ''}}">
                                            <td class="d-flex user-table-items">
                                                <span class="tabel-checkbox-user">
                                                    <input type="checkbox" name="test" class="checkbox-table"
                                                        id="selectAll">
                                                </span>
                                                <span class="table-row-heading-user sm-d-none">
                                                    {{ $key + 1 }}
                                                </span>
                                            </td>
                                            <td class="user_name">
                                                @if ($contact)
                                                    <span> {{ $contact->firstName }} {{ $contact->lastName }}</span>
                                                @elseif ($user->first_name)
                                                    <span> {{ $user->first_name }} {{ $user->last_name }} </span>
                                                @else
                                                    <span class="badge badge-info w-100">empty</span>
                                                @endif
                                            </td>
                                            <td class="user_table_items">
                                                {{ $user->email }}</td>
                                            <td class="user_table_items">
                                                @if ($contact)
                                                    @if ($contact->contact_id)
                                                        {{ $contact->contact_id }}
                                                    @else
                                                        {{ $contact->parent_id }}
                                                    @endif
                                                @else
                                                    <span class="badge badge-info w-100">empty</span>
                                                @endif
                                            </td>
                                            <td class="is_parent user_table_items">
                                                @if ($contact)
                                                    @if ($contact->is_parent == 1)
                                                        <span>{{ $contact->company }}</span>
                                                    @else
                                                        <span class="badge badge-secondary is_parent_1">empty</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary  is_parent_0">empty</span>
                                                @endif
                                            </td>
                                            <td class="is_parent user_table_items">
                                                @if ($contact)
                                                    @if ($contact->is_parent == 0)
                                                        <span> {{ $contact->company }}</span>
                                                    @else
                                                        <span class="badge badge-secondary  is_parent_1">empty</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary  is_parent_0">empty</span>
                                                @endif
                                            </td>
                                            <td class="background_contact_id user_table_items">
                                                @if ($contact)
                                                    @if (!empty($contact->contact_id))
                                                        <span class="badge badge-primary  background_primary_1">primary</span>
                                                        <small class="{{!empty($contact->status && $contact->status != 0) ? 'badge badge-success' : "badge badge-danger"}}">
                                                            {{!empty($contact->status && $contact->status != 0) ? 'Active' : "Inactive"}}
                                                        </small>
                                                    @else
                                                        <span class="badge badge-secondary  background_secondary_1">secondary</span>
                                                        <small class="{{!empty($contact->status && $contact->status != 0) ? 'badge badge-success' : "badge badge-danger"}}">
                                                            {{!empty($contact->status && $contact->status != 0) ? 'Active' : "Inactive"}}
                                                        </small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="user_table_items"> 
                                                {{$contact->created_at->format('m/d/Y') }}
                                            </td>
                                            <td class="background_success user_table_items">
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $role)
                                                        <label
                                                            class="badge badge-success  background_success_1">{{ $role }}</label>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="user_action ">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-white dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"
                                                            style="color: #CBCBCB !important;"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdonwn_menu drop_down_items_user">
                                                        <a class="dropdown-item remove_padding"
                                                            href="{{ route('users.show', $user->id) }}"
                                                            class="view a_class" title="" data-toggle="tooltip"
                                                            data-original-title="View">Previews
                                                        </a>
                                                        <a class="dropdown-item remove_padding delete deleteIcon a_class"
                                                            href="{{ route('users.destroy', $user->id) }}" class=""
                                                            id="{{ $user->id }}" title=""
                                                            data-toggle="tooltip" data-original-title="Delete">Delete
                                                        </a>
                                                        <a class="dropdown-item remove_padding"href="{{ route('users.edit', $user->id) }}"
                                                            class="edit a_class" title="" data-toggle="tooltip"
                                                            data-original-title="Edit">Edit
                                                        </a>
                                                        @if ((empty($contact->is_deleted)) && ($contact->status != 0) && (!empty($contact->contact_id) || !empty($contact->secondary_id)))
                                                            <a class="dropdown-item remove_padding" href="{{ url('admin/user-switch/' . $user->id . '/' . $contact_switch_id . '/' . auth()->user()->id) }}"
                                                                class="edit a_class" title="" data-toggle="tooltip" 
                                                                data-original-title="Edit">Switch User
                                                            </a>
                                                        @endif
                                                        @if ($contact)
                                                            @if ($contact->secondary_contact)
                                                                <button type="button" class="btn"
                                                                    data-id="{{ $user->id }}" data-toggle="modal"
                                                                    onclick="assignParent('{{ $user->id }}')">Set
                                                                    Parent</button>
                                                                <input type="hidden" value='{{ $user->id }}'
                                                                    id='{{ $user->id }}'>
                                                            @endif
                                                        @endif
                                                        @if ($user->is_updated == 0)
                                                            <a class="dropdown-item remove_padding"href="{{ url('admin/send-password/' . $user->id) }}"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Send Password
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item remove_padding disabled"href="{{ url('admin/send-password/' . $user->id) }}"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Send Password
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    {{-- <td colspan="10" class="mobile-screen">
                                        {{ $data->links('pagination.custom_pagination') }}
                                    </td> --}}

                                    <td colspan="10" class="mobile-screen">
                                        {{ $data->appends(['search' => request('search'), 'usersData' => request('usersData'), 'secondaryUser' => request('secondaryUser'), 'sort_by_desc' => request('sort_by_desc'), 'sort_by_asc' => request('sort_by_asc'), 'sort_by_name' => request('sort_by_name'), 'sort_by_email' => request('sort_by_email')])->links('pagination.custom_pagination') }}

                                        {{-- {{ $data->links('pagination.custom_pagination') }} --}}
                                    </td>
                                </tr>
                            </tfoot>
                    </table>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Search Parent</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="secondary_id">
                                    <input type="select" name="primary_contact" id="primary_contact"
                                        class="form-control" value="" onkeyup="suggestion()">
                                    <select id="child" class="form-control">

                                    </select>
                                    <input type="text" name="child_id" value="" id="child_id">
                                    <div class="spinner-border d-none" role="status"
                                        style="left: 50% !important; margin-left: -16em !important;" id="spinner2">
                                        <span class="sr-only">Activating...</span>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="assign()">Save
                                    changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
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
    </style>
@stop
@section('js')
    <script>
        // main sorting
        function sortbyDesc() {
            var search = $('#search').val();
            var sort_by = $('#sort_by_desc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url + `&sort_by_desc=${sort_by}`;
            }
            window.location.href = basic_url
        }
        function sortbyAsc() {
            var search = $('#search').val();
            var sort_by = $('#sort_by_asc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url+`&sort_by_asc=${sort_by}`;
            }
            window.location.href = basic_url
        }
        // sort by name
        function sortnamebyDesc() {
            var search = $('#search').val();
            var sort_by = $('#sort_name_by_desc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url + `&sort_by_name=${sort_by}`;
            }
            window.location.href = basic_url
        }
        function sortnamebyAsc() {
            var search = $('#search').val();
            var sort_by = $('#sort_name_by_asc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url+`&sort_by_name=${sort_by}`;
            }
            window.location.href = basic_url
        }
        // sort by email
        function sortemailbyDesc() {
            var search = $('#search').val();
            var sort_by = $('#sort_email_by_desc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url + `&sort_by_email=${sort_by}`;
            }
            window.location.href = basic_url
        }
        function sortemailbyAsc() {
            var search = $('#search').val();
            var sort_by = $('#sort_email_by_asc').val();
            var basic_url = '/admin/users?&search=' + search;
            if (sort_by != '') {
                basic_url = basic_url+`&sort_by_email=${sort_by}`;
            }
            window.location.href = basic_url
        }

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
