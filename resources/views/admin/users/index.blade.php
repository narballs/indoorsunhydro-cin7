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
    <div class="table-title">
        <span>
            <h1>Users Management</h1>
        </span>
        <div class="row justify-content-between mb-2">
            <div class="col-md-1">
                <div class="search-box">
                    <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}"> Create New User</a>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <select name="users" id="users" onchange="userFilter()" class="form-control"
                            style="    height: 45px;">
                            <option value="all" class="form-control">All</option>
                            <option value="admin-user" class="form-control" {{ isset($usersData) &&
                                $usersData=='admin-user' ? 'selected="selected"' : '' }}>Admin Users </option>
                            <option value="cin7-merged" class="form-control" {{ isset($usersData) &&
                                $usersData=='cin7-merged' ? 'selected="selected"' : '' }}>Cin7 Merged</option>
                            <option value="not-merged" class="form-control" {{ isset($usersData) &&
                                $usersData=='not-merged' ? 'selected="selected"' : '' }}>Not Merged</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="secondary_user" id="secondary-user" onchange="userFilter()" class="form-control"
                            style="height: 45px;">
                            <option value="all" class="form-control">Secndary/Primary</option>
                            <option value="secondary-user" class="form-control" {{ isset($secondaryUser) &&
                                $secondaryUser=='secondary-user' ? 'selected="selected"' : '' }}>Secondary Users
                            </option>
                            <option value="primary-user" class="form-control" {{ isset($secondaryUser) &&
                                $secondaryUser=='primary-user' ? 'selected="selected"' : '' }}>Primary Users</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-lg mt-2" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <form method="get" action="/admin/users">
                            <input type="text" class="form-control input-lg" id="search" name="search"
                                placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div id="admin-users"></div>
        <table class="table table-striped table-hover table-bordered" id="user-table">

            <tr>
                <th>No <i class="fa fa-sort"></th>
                <th>Name <i class="fa fa-sort"></th>
                <th>Last Name <i class="fa fa-sort"></th>
                <th>Email <i class="fa fa-sort"></th>
                <th>Status <i class="fa fa-sort"></th>
                <th>Cin7 User-ID <i class="fa fa-sort"></th>
                <th>Company (Account aka Parent) <i class="fa fa-sort"></th>
                <th>Type <i class="fa fa-sort"></th>
                <th>Roles <i class="fa fa-sort"></th>
                <th>Action <i class="fa fa-sort"></th>
            </tr>
            @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->contact)
                    @if($user->contact->contact_id)
                    <span class="badge bg-success">Merged</span>
                    @else
                    <span class="badge bg-danger">UnMered</span>
                    @endif
                    @else
                    <span class="badge bg-danger">UnMered</span>
                    @endif
                </td>
                <td>

                    @if($user->contact)
                    @if($user->contact->contact_id)
                    {{$user->contact->contact_id}}
                    @else
                    <span class="badge bg-info">empty</span>
                    @endif
                    @else
                    <span class="badge bg-info">empty</span>
                    @endif
                </td>
                <td>
                    @if($user->contact)
                    @if($user->contact->company)
                    {{$user->contact->company}}
                    @else
                    <span class="badge bg-secondary">empty</span>
                    @endif
                    @else
                    <span class="badge bg-secondary">empty</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">Simple User</span>
                </td>
                <td>
                    @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $role)
                    <label class="badge badge-success">{{ $role }}</label>
                    @endforeach
                    @endif
                </td>
                <td>
                    <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
                    @can('user-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
                    @endcan
                    @can('user-delete')
                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy',
                    $user->id],'style'=>'display:inline'])
                    !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                    {!! Form::close() !!}
                    @endcan
                    <a class="btn btn-success btn-sm" href="{{ url('admin/user-switch/'.$user->id) }}">Switch User</a>
                    @if($user->contact)
                    @if($user->contact->secondary_contact)
                    <button type="button" class="btn btn-primary btn-sm" data-id="{{$user->id}}" data-toggle="modal"
                        onclick="assignParent('{{$user->id}}')">Set Parent</button>
                    <input type="hidden" value='{{$user->id}}' id='{{$user->id}}'>
                    @endif
                    @endif
                    <a class="btn btn-warning btn-sm" href="{{ url('admin/send-password/'.$user->id) }}">Send password</a>

                </td>
            </tr>
            @endforeach
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
        <div id="pageination">
            {{$data->appends(Request::all())->links()}}
        </div>
    </div>
    @endsection

    @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style type="text/css">
        #custom-search-input {
            padding: 3px;
            border: solid 1px #E4E4E4;
            border-radius: 6px;
            background-color: #fff;
            height: 45px;
        }

        #custom-search-input input {
            border: 0;
            box-shadow: none;
            width: 307px;
        }

        #custom-search-input button {
            margin: 2px 0 0 0;
            background: none;
            box-shadow: none;
            border: 0;
            color: #666666;
            padding: 0 8px 0 10px;
            border-right: solid 1px #ccc;
        }

        #custom-search-input button:hover {
            border: 0;
            box-shadow: none;
            border-left: solid 1px #ccc;
        }

        #custom-search-input .glyphicon-search {
            font-size: 23px;
        }
    </style>
    @stop
    <script>
        function adminUsers() {
            $('#user-table').addClass('d-none');
            $('#pageination').addClass('d-none');
            $.ajax({
                   url: "{{ url('admin/admin-users') }}",
                   method: 'GET',
                   success: function(response){
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
            basic_url = basic_url+`&secondaryUser=${secondaryUser}`;
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
                    data :{
                        term : primary_contact
                    },
                   success: function(response){
                    $.each (response, function (key, value) {
                            console.log(value.firstName);
                            res+= '<option value='+ value.contact_id +'>'+ value.firstName + '</option>';
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
                url: "{{ url('admin/assign-parent-child')}}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    primary_name : primary_name,
                    user_id : user_id,
                    primary_id : primary_id,  
                },
                success: function(response) {

                    if (response.status == 200)
                    {
                        $('#spinner2').addClass('d-none');
                   
                      $('#exampleModal').modal('hide');
                      $('#secondary_id').trigger("reset");
                        window.location.reload();
                        
                    }
                }

            });

    }


    </script>