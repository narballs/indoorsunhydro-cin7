
@extends('newsletter_layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Assign Templates to List</h3>
                <a href="{{ route('newsletter-templates.index') }}" class="btn btn-primary float-right">Back</a>
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
                <form action="{{ route('update_assigned_template' , $assigned_template->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="list_id">Select List</label>
                                <select name="list_id" id="list_id" class="form-control" required>
                                    @foreach($lists as $list)
                                        <option value="{{ $list->id }}" {{ $list->id == $assigned_template->list_id ? 'selected' : '' }}>
                                            {{ $list->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                
                        <div class="col-md-6">
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
                        </div>
                    </div>
            
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update Assigned Template</button> 
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection