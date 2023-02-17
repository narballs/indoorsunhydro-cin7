@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop
@section('content')
<div class="row">
    <div class="col-md-12 margin-tb">
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}"> Create New User</a>
            </div>
            {{-- <div class="col-md-3">
                <a class="btn btn-info btn-sm" href="{{ route('users.create') }}"> Create New User</a>
            </div> --}}
        </div>
        {{-- <div class="pull-left d-flex justify-contend-end text-aling-end">

        </div> --}}
    </div>

</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="row mt-2">
    <div class="col-md-12">
        <span onclick="adminUsers()"> Admins({{$count}})</span>
    </div>
</div>
<div id="admin-users"></div>
<table class="table table-bordered mt-5" id="user-table">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th width="280px">Action</th>
    </tr>

    @foreach ($data as $key => $user)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $user->first_name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
            <label class="badge badge-success">{{ $v }}</label>
            @endforeach
            @endif
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
            @can('user-edit')
            <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
            @endcan
            @can('user-delete')
            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
            {!! Form::close() !!}
            @endcan
            <a class="btn btn-success btn-sm" href="{{ url('admin/user-switch/'.$user->id) }}">Switch User</a>
        </td>
    </tr>
    @endforeach
</table>

<div id="pageination">
    {!! $data->render() !!}
</div>

@endsection


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
</script>