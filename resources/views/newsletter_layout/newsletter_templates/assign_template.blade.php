@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assign Templates to Subscribers</h3>
                <a href="{{ route('view_assigned_templates') }}" class="btn btn-primary float-right">Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('assign.templates') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="subscriber_id">Select Subscriber</label>
                        <select name="subscriber_id" id="subscriber_id" class="form-control" required>
                            <option value="">-- Select Subscriber --</option>
                            @foreach($subscribers as $subscriber)
                                <option value="{{ $subscriber->id }}">{{ $subscriber->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="template_id">Select Template</label>
                        <select name="template_id" id="template_id" class="form-control" required>
                            <option value="">-- Select Template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Assign Template</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection