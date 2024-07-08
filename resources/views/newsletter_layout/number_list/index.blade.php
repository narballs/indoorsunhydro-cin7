@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center jusfy-content-between">
                    <div class="col-md-8">
                        <h3 class="card-title">Mobile Number Lists</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{route('sms_list_create')}}" class="btn btn-info">Create Mobile Number List</a>
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
                                Created at
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
                        @if(count($number_lists) > 0 )
                            @foreach ($number_lists as $list)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>
                                        <form action="{{route('sms_list_delete' , $list->id)}}" method="post">
                                            @csrf
                                            <a href="{{ route('sms_list_edit', $list->id) }}" class="btn btn-secondary">Edit</a>
                                            <a href="{{ route('show_numbers_from_list', $list->id) }}" class="btn btn-info">Show Mobile Number(s)</a>
                                            
                                            <button class="btn btn-default add-user-to-list-btn" type="button" data-toggle="modal" data-target="#add_number_list" data-id="{{ $list->id }}">
                                                Add Mobile Number To List
                                            </button>
                                            <button class="btn btn-primary bulk_upload_btn" type="button" data-toggle="modal" data-target="#bulk_upload_pop_up" data-id="{{ $list->id }}">
                                                Bulk Upload
                                            </button>
                                            <button type="button" class="btn btn-success csv_upload_btn" data-toggle="modal" data-target="#upload_file_modal" data-id="{{ $list->id }}">Upload CSV or Excel</button>
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
                        {{ $number_lists->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<div class="modal fade" id="add_number_list" tabindex="-1" role="dialog" aria-labelledby="add_number_list_label" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_number_list_label">Add Number To List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_user_to_list" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="list_id" id="list_id">
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" data-role="tagsinput" class="form-control" name="tags">
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" id="mobile_number" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_user_to_list_btn">Add Number</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bulk_upload_pop_up" tabindex="-1" role="dialog" aria-labelledby="bulk_upload_pop_up" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Bulk Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bulk_upload_file_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="list_id" id="bulk_list_id">
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" data-role="tagsinput" class="form-control"  name="tags" id="bulk_tags">
                    </div>
                    <div class="form-group">
                        <label for="">Bulk Upload (Enter Mobile Number one per line)</label>
                        <textarea class="form-control" id="bulk_upload_numbers" name="bulk_upload_numbers" rows="5"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_bulk_upload">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="upload_file_modal" tabindex="-1" role="dialog" aria-labelledby="upload_file_modal_label" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upload_file_modal_label">Upload CSV or Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="upload_file_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="list_id" id="upload_list_id">
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" data-role="tagsinput" class="form-control"  name="tags" id="tags">
                    </div>
                    <div class="form-group">
                        <label for="file">Choose CSV or Excel File</label>
                        <input type="file" name="file" class="form-control" id="file" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="upload_file_button">Upload</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Set the list ID in the modal when "Add User To List" button is clicked
            $('.add-user-to-list-btn').click(function() {
                var listId = $(this).data('id');
                $('#list_id').val(listId);
            });
            $('.bulk_upload_btn').click(function() {
                var listId = $(this).data('id');
                $('#bulk_list_id').val(listId);
            });
            $('.csv_upload_btn').click(function() {
                var listId = $(this).data('id');
                $('#upload_list_id').val(listId);
            });

            // Handle form submission
            $('#add_user_to_list_btn').click(function() {
                var formData = new FormData($('#add_user_to_list')[0]);
                $.ajax({
                    url: "{{ route('add_mobile_numbers_to_list') }}", // Update this URL to match your route
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#add_number_list').modal('hide');
                                    setTimeout(function() {
                                        window.location.href = '/sms/list/index'; // Redirect or update as necessary
                                    }, 1000); // Delay of 1 second
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to add user. Please check your input.',
                            icon: 'error'
                        });
                    }
                });
            });
            // save bulk upload emails
            $('#save_bulk_upload').click(function() {
                var numbers = $('#bulk_upload_numbers').val();
                var tags = $('#bulk_tags').val();
                var listId = $('#bulk_list_id').val();

                $.ajax({
                    url: "{{ route('bulk_upload_to_list') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        bulk_upload_numbers: numbers,
                        tags: tags,
                        list_id: listId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#bulk_upload_pop_up').modal('hide');
                                    $('#bulk_upload_numbers').val(''); // Clear the textarea
                                    setTimeout(function() {
                                        window.location.href = '/sms/list/index';
                                    }, 1000); // Delay of 1 second (1000 milliseconds)
                                }
                            });
                        } else {
                            swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                        swal.fire({
                            title: 'Error',
                            text: 'Failed to upload emails. Your data is invalid.',
                            icon: 'error'
                        });
                    }
                });
            });


            // save import users
            $('#upload_file_button').click(function() {
                var formData = new FormData($('#upload_file_form')[0]);
                var listId = $('#upload_list_id').val(); // Fetch list_id from hidden input
                var tags = $('#tags').val();

                // Append list_id to formData
                formData.append('list_id', listId);
                formData.append('tags', tags);

                $.ajax({
                    url: "{{ route('import_users_to_list') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#upload_file_modal').modal('hide');
                                    setTimeout(function() {
                                        window.location.href = '/sms/list/index';
                                    }, 1000); // Delay of 1 second
                                }
                            });
                        } else {
                            swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Failed to upload file. Your data is invalid.',
                            icon: 'error'
                        });
                    }
                });
            });

        });
    </script>
@endpush