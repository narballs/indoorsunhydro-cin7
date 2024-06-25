@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center jusfy-content-between">
                    <div class="col-md-8">
                        <h3 class="card-title">Lists</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{route('subscribers_list_create')}}" class="btn btn-info">Create List</a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('error') }}
                    </div>
                @endif
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>
                                S.No
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i =1;
                        @endphp
                        @if(count($subscribers_list) > 0 )
                            @foreach ($subscribers_list as $list)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>
                                        <form action="{{route('subscribers_list_delete' , $list->id)}}" method="post">
                                            @csrf
                                            <a href="{{ route('subscribers_list_edit', $list->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ route('subscribers_list_show_users', $list->id) }}" class="btn btn-info">Show User(s)</a>
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this list?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3">No List Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="row mt-2 justify-content-center">
                    <div class="col-md-6">
                        {{ $subscribers_list->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection