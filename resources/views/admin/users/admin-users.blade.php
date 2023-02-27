@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<table class="table table-bordered mt-5">
  <tr>
    <th>No</th>
    <th>Name</th>
    <th>last Name</th>
    <th>Email</th>
    <th>Roles</th>
    <th width="280px">Action</th>
  </tr>

  @foreach ($data as $key => $user)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $user->first_name }}</td>
    <td>{{ $user->last_name }}</td>
    <td>{{ $user->email}}</td>
    <td>
      @if(!empty($user->getRoleNames()))
      @foreach($user->getRoleNames() as $v)
      <label class="badge badge-success">{{ $v }}</label>
      @endforeach
      @endif
    </td>
    <td>

      <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>

      @can('user-edit')
      <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
      @endcan
      @can('user-delete')
      {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
      {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
      {!! Form::close() !!}
      @endcan
    </td>
  </tr>
  @endforeach
</table>