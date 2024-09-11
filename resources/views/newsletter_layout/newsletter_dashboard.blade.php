@extends('newsletter_layout.dashboard')
@section('content')
    @if (auth()->user()->hasRole(['Newsletter']))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title
                        ">Newsletter Templates List</h3>
                        <a href="{{ route('newsletter-templates.create') }}" class="btn btn-primary float-right">Create New</a>
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
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Created at</th>
                                    <th>
                                        Action
                                    </th>
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
                                    <td>{{ $template->created_at }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <form action="{{ route('delete_newsletter_template', $template->id) }}" method="POST" style="display: inline-block;">
                                                <a href="{{ route('newsletter_templates_detail', $template->id) }}" class="btn btn-info">View</a>
                                                <a href="{{ route('edit_newsletter_template', $template->id) }}" class="btn btn-primary">Edit</a>
                                                @csrf
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Template?');">Delete</button>
                                            </form>
                                            <form action="{{ route('duplicate_newsletter_template', $template->id) }}" method="POST" class="mx-1">
                                                @csrf
                                                <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure you want to duplicate this Template?');">Duplicate</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">No Newsletter Template list Found</td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    @endif
@endsection