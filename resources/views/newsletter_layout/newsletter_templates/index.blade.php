
@extends('newsletter_layout.dashboard')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter Templates List</h3>
                    <a href="{{ route('newsletter-templates.create') }}" class="btn btn-primary float-right">Create New</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                {{-- <th>Add Subscriber</th> --}}
                                {{-- <th>View Subscribers</th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i =1;
                            @endphp
                            @if(count($templates) > 0 )
                            @foreach ($templates as $template)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $template->name }}</td>
                                {{-- <td>
                                    <a href="{{ route('email_lists.addSubscriberForm', $emailList) }}" class="btn btn-primary">Add
                                        Subscriber</a>
                                        
                                </td>
                                <td>
                                    <a href="{{ url('email_lists/' . $emailList->id) }}" class="btn btn-primary">View Subscribers</a>

                                </td> --}}
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">No Newsletter Template list Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            {{-- {{ $emailLists->links() }} --}}
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