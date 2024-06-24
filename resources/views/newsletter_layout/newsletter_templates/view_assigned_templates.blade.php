
@extends('newsletter_layout.dashboard')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">View Assigned Newsletter</h3>
                    <a href="{{ route('assign_template_form') }}" class="btn btn-primary float-right">Assign Template</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Subscriber Email</th>
                                <th>Template Name</th>
                                <th>Sent Status</th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i =1;
                            @endphp
                            @if(count($assigned_templates) > 0 )
                            @foreach ($assigned_templates as $template)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $template->subscriber->email }}</td>
                                <td>{{ $template->template->name }}</td>
                                <td>{{ $template->sent ? 'Sent' : 'Not Sent' }}</td>
                                <td>
                                    <form action="{{ route('delete_assigned_template', $template->id) }}" method="POST" style="display: inline-block;">
                                        <a href="{{ route('edit_assigned_template', $template->id) }}" class="btn btn-primary">Edit</a>
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                        
                                    </form>
                                    <form action="{{ route('send_newspaper', $template->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="subscriber_id" value="{{ $template->subscriber->id }}">
                                        <input type="hidden" name="template_id" value="{{ $template->template->id }}">
                                        <button type="submit" class="btn btn-info">Send Newspaper</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">No Assigned Template list Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection>