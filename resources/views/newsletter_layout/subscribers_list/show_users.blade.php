@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center jusfy-content-between">
                    <div class="col-md-8">
                        <h3 class="card-title">List Users</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{route('subscribers_list')}}" class="btn btn-info">Back</a>
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
                                Email
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
                        @if(count($subscribers_emails_list) > 0 )
                            @foreach ($subscribers_emails_list as $list)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{ $list->email }}</td>
                                    <td>
                                        <form action="{{route('delete_user_from_list' , $list->id)}}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this User?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3">No List Users Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="row mt-2 justify-content-center">
                    <div class="col-md-6">
                        {{ $subscribers_emails_list->links() }}
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