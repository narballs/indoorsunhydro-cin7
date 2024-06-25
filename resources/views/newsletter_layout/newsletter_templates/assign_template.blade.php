@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assign Templates to List</h3>
                <a href="{{ route('view_assigned_templates') }}" class="btn btn-primary float-right">Back</a>
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

                <form action="{{ route('assign.templates') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subscriber_email_list_id">Select List</label>
                                <select name="subscriber_email_list_id" id="subscriber_email_list_id" class="form-control" required>
                                    <option value="">-- Select List --</option>
                                    @foreach($subscriber_email_lists as $subscriber_email_list)
                                        <option value="{{ $subscriber_email_list->id }}">{{ $subscriber_email_list->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="template_id">Select Template</label>
                                <select name="template_id" id="template_id" class="form-control" required>
                                    <option value="">-- Select Template --</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Assign Template</button> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection