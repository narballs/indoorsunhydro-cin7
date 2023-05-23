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
        {{-- <div class="table-title">
            
        </div> --}}
        <div class="card-body mt-2">
            <div class="row mb-3">
                <div class="col-md-12 mt-3">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="order_heading">
                                Users Management
                            </p>
                            <p class="order_description">
                                In the Users Management section, you can review and manage all users with their details. You
                                can view and edit information <br> such as user IDs, usernames, email addresses, passwords,
                                and
                                permissions. Access to this area is restricted to administrators <br>and team leaders. Any
                                changes you make will require approval after being verified for accuracy.
                            </p>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('users.create') }}" class="btn create-new-order-btn">
                                + Create new user
                            </a>
                        </div>
                    </div>
                    <div class="row p-3 search_row_admin_user">
                        <div class="col-md-6 order-search">
                            <div class="form-group has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/users" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3 d-flex">
                            <select name="users" id="users" onchange="userFilter()" class="form-control"
                                style="    height: 45px;">
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
                            <select name="secondary_user" id="secondary-user" onchange="userFilter()" class="form-control"
                                style="height: 45px;">
                                <option value="all" class="form-control">Secndary/Primary</option>
                                <option value="secondary-user" class="form-control"
                                    {{ isset($secondaryUser) && $secondaryUser == 'secondary-user' ? 'selected="selected"' : '' }}>
                                    Secondary Users
                                </option>
                                <option value="primary-user" class="form-control"
                                    {{ isset($secondaryUser) && $secondaryUser == 'primary-user' ? 'selected="selected"' : '' }}>
                                    Primary Users</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="admin-users"></div>
            <table class="table table-hover table-users" id="user-table">
                <tr>
                    <thead>
                        <th>#</th>
                        <th>Full Name <i class="fa fa-sort"></th>
                        <th>Email <i class="fa fa-sort"></th>
                        <th>Cin7 User-ID <i class="fa fa-sort"></th>
                        <th>Company (Account aka Parent) <i class="fa fa-sort"></th>
                        <th>Secondary Contact Company <i class="fa fa-sort"></th>
                        <th>Type <i class="fa fa-sort"></th>
                        <th>Roles <i class="fa fa-sort"></th>
                        <th>Action <i class="fa fa-sort"></th>
                    </thead>
                </tr>
                <tbody>
                    @foreach ($data as $key => $user)
                        @foreach ($user->contact as $contact)
                            <tr id="row-{{ $user->id }}" class="user-row">
                                <td>{{ ++$i }}</td>
                                <td class="user_name">
                                    @if ($contact)
                                        <span> {{ $contact->firstName }} {{ $contact->lastName }}</span>
                                    @elseif ($user->first_name)
                                        <span> {{ $user->first_name }} {{ $user->last_name }} </span>
                                    @else
                                        <span class="badge badge-info w-100">empty</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
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
                                <td class="is_parent">
                                    @if ($contact)
                                        @if ($contact->is_parent == 1)
                                            <span>{{ $contact->company }}</span>
                                        @else
                                            <span class="badge badge-secondary w-50 is_parent_1">empty</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary w-50 is_parent_0">empty</span>
                                    @endif
                                </td>
                                <td class="is_parent">
                                    @if ($contact)
                                        @if ($contact->is_parent == 0)
                                            <span> {{ $contact->company }}</span>
                                        @else
                                            <span class="badge badge-secondary w-50 is_parent_1">empty</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary w-50 is_parent_0">empty</span>
                                    @endif
                                </td>
                                <td class="background_contact_id">
                                    @if ($contact)
                                        @if (!empty($contact->contact_id))
                                            <span class="badge badge-primary w-100 background_primary_1">primary</span>
                                        @else
                                            <span
                                                class="badge badge-secondary w-100 background_secondary_1">secondary</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="background_success">
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $role)
                                            <label
                                                class="badge badge-success w-100 background_success_1">{{ $role }}</label>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="user_action">
                                    <a href="{{ route('users.show', $user->id) }}" class="view a_class" title=""
                                        data-toggle="tooltip" data-original-title="View">
                                        <i class="icon-style  fas fa-eye fa-border i_class"></i>
                                        @can('user-edit')
                                            <a href="{{ route('users.edit', $user->id) }}"class="edit a_class" title=""
                                                data-toggle="tooltip" data-original-title="Edit"><i
                                                    class="icon-style fas fa-edit fa-border "></i>
                                            </a>
                                        @endcan
                                        @can('user-delete')
                                            {{-- {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn']) !!}
                                                {!! Form::close() !!} --}}
                                            <a href="{{ route('users.destroy', $user->id) }}"class="edit a_class"
                                                title="" data-toggle="tooltip" data-original-title="Delete"><i
                                                    class="icon-style fas fa-trash-alt fa-border "></i>
                                            </a>
                                        @endcan
                                        <a href="{{ url('admin/user-switch/' . $user->id) }}"class="edit a_class"
                                            title="" data-toggle="tooltip" data-original-title="Switch"><i
                                                class="icon-style fas fa-toggle-on fa-border ">
                                            </i>
                                        </a>
                                        @if ($contact)
                                            @if ($contact->secondary_contact)
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-id="{{ $user->id }}" data-toggle="modal"
                                                    onclick="assignParent('{{ $user->id }}')">Set
                                                    Parent</button>
                                                <input type="hidden" value='{{ $user->id }}'
                                                    id='{{ $user->id }}'>
                                            @endif
                                        @endif
                                        @if ($user->is_updated == 0)
                                            <a href="{{ url('admin/send-password/' . $user->id) }}"> <i
                                                    class="icon-style fas fa-lock fa-border" aria-hidden="true"></i></a>
                                        @else
                                            <a class="disabled" href="{{ url('admin/send-password/' . $user->id) }}" <i
                                                class="icon-style fas fa-lock fa-border" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                                <input type="select" name="primary_contact" id="primary_contact" class="form-control"
                                    value="" onkeyup="suggestion()">
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
                            <button type="button" class="btn btn-primary" onclick="assign()">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2 border-top">
                    {{ $data->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    @endsection

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
            .text-successs {
                color: #7CC633 !important;
                font-family: 'Poppins', sans-serif !important;
            }

            .badge-success {
                background: rgba(124, 198, 51, 0.2);
                color: #7CC633;
                padding: 7px !important;
            }

            .badge-secondary {
                color: #8e8b8b !important;
                background-color: #d0dce6 !important;
                padding: 7px !important;
                border-radius: 6px;
            }

            .badge-primary {
                background-color: #c6e6f3 !important;
                color: #339AC6 !important;
                padding: 5px;
                border-radius: 6px !important;
            }

            .badge-warning {
                background-color: #fce9a9;
                color: #ffc107 !important;
                padding: 5px;
            }

            .badge-danger {
                background-color: #f1abb2;
                color: #f14f4f !important;
                padding: 6px !important;
            }
        </style>
    @stop
    @section('js')
        <script>
            $('.user-row').hover(function() {
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


            $('.user-row').mouseleave(function() {
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
        </script>
    @stop
