
@extends('newsletter_layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Newsletter Template</h3>
                <a href="{{ route('newsletter-templates.index') }}" class="btn btn-primary float-right">Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('update_assigned_template' , $assigned_template->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="subscriber_id">Select Subscriber</label>
                        <select name="subscriber_id" id="subscriber_id" class="form-control" required>
                            @foreach($subscribers as $subscriber)
                                <option value="{{ $subscriber->id }}" {{ $subscriber->id == $assigned_template->newsletter_subscription_id ? 'selected' : '' }}>
                                    {{ $subscriber->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            
                    <div class="form-group">
                        <label for="template_id">Select Template</label>
                        <select name="template_id" id="template_id" class="form-control" required>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ $template->id == $assigned_template->newsletter_template_id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Update Assigned Template</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection