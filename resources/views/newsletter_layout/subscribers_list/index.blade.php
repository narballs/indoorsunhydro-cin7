@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center jusfy-content-between">
                    <div class="col-md-8">
                        <h3 class="card-title">Lists</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{route('subscribers_list_create')}}" class="btn btn-info">Create List</a>
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
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i =1;
                        @endphp
                        @if(count($subscribers_list) > 0 )
                            @foreach ($subscribers_list as $list)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>
                                        <form action="{{route('subscribers_list_delete' , $list->id)}}" method="post">
                                            @csrf
                                            <a href="{{ route('subscribers_list_edit', $list->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ route('subscribers_list_show_users', $list->id) }}" class="btn btn-info">Show User(s)</a>
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this list?');">Delete</button>
                                            <button class="btn btn-default add-user-to-list-btn" type="button" data-toggle="modal" data-target="#add_user_list" data-id="{{ $list->id }}">
                                                Add User To List
                                            </button>
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
                        {{ $subscribers_list->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<div class="modal fade" id="add_user_list" tabindex="-1" role="dialog" aria-labelledby="add_user_list_label" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_user_list_label">Add User To List</h5>
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
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_user_to_list_btn">Add User</button>
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

            // Handle form submission
            $('#add_user_to_list_btn').click(function() {
                var formData = new FormData($('#add_user_to_list')[0]);
                $.ajax({
                    url: "{{ route('add_subscriber_to_list') }}", // Update this URL to match your route
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
                                    $('#add_user_list').modal('hide');
                                    setTimeout(function() {
                                        window.location.href = '/subscribers/list/index'; // Redirect or update as necessary
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
        });
    </script>
@endpush