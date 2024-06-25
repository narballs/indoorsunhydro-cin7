
@extends('newsletter_layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center jusfy-content-between">
                    <div class="col-md-8">
                        <h3 class="card-title">Update List</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('subscribers_list') }}" class="btn btn-primary">Back</a>
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
                <form action="{{ route('subscribers_list_update' , $subscribers_list->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{!empty($subscribers_list->name) ? $subscribers_list->name : ''}}" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Description:</label>
                        <textarea id="description" name="description" class="form-control">{{!empty($subscribers_list->description) ? $subscribers_list->description : ''}}
                        </textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>


@endsection