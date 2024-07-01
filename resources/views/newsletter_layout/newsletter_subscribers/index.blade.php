@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6 d-flex align-items-center">
                        <h3 class="card-title">Subscribers</h3>
                        <button class="btn btn-info mx-3 d-none list_pop_up_btn" type="button" data-toggle="modal" data-target="#email_list_pop_up">
                            Add To List
                        </button>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#upload_file_modal">Upload CSV or Excel</a>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#bulk_upload_pop_up">
                                    Bulk Upload
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <form method="get" class="">
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                        </form>
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
                @if (count($newsletter_subscriptions) > 0 )
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th>Email</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i =1;
                            @endphp
                            @foreach ($newsletter_subscriptions as $newsletter_subscription)
                            <tr>
                                <td>
                                    <input type="checkbox" name="select_one" id="select_one_{{$newsletter_subscription->id}}" data-email="{{$newsletter_subscription->email}}" class="select_one">
                                </td>
                                <td>{{ $newsletter_subscription->email }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row mt-3 justify-content-center align-items-center">
                        <div class="col-md-12">
                            {{ $newsletter_subscriptions->links() }}
                        </div>
                    </div>
                @else
                    <h5>
                        No Subscribers Found
                    </h5>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<div class="modal fade" id="email_list_pop_up" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Lists</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="alertContainer mb-3"></div>
                    <div class="col-md-12">
                        <label for="exampleFormControlSelect1">Select List</label>
                    </div>
                    <div class="col-md-12">
                        <select class="form-control get_list_ids" id="exampleFormControlSelect1" multiple="multiple" name="list_ids[]">
                            @if (count($subscribers_list) > 0)
                                @foreach ($subscribers_list as $list)
                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                @endforeach
                            @else
                                <option value="">No List Found</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_list_users">Save changes</button>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" data-role="tagsinput" class="form-control"  name="tags" id="bulk_tags">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="">Bulk Upload (Enter email one per line)</label>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" id="bulk_upload_emails" name="bulk_upload_emails" rows="5"></textarea>
                        </div>
                    </div>
                </div>
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
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" data-role="tagsinput" class="form-control"  name="tags">
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
<style>
    /* Custom styles for Select2 tags */
    .select2-container {
        width: 100% !important;    /* Adjust width */
    } 
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;  /* Background color of tags */
        border: 1px solid #007bff;  /* Border color of tags */
        color: #ffffff;             /* Text color of tags */
    }
    .select2-container .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff;             /* Text color for the remove button (x) */
        cursor: pointer;
    }
    .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ff0000;             /* Hover color for the remove button (x) */
    }

    .select2-container--default .select2-selection--multiple {
        /* min-height: 40px;  */
        /* padding: 8px;  */
        border: 1px solid #ced4da; /* Border color */
        border-radius: 4px; /* Border radius */
    }

    .select2-container .select2-selection--multiple .select2-selection__rendered li {
        list-style: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;  /* Background color of tags */
        border: 1px solid #007bff;  /* Border color of tags */
        color: #ffffff;             /* Text color of tags */
        padding: 3px 10px;          /* Padding inside the tags */
        border-radius: 4px;         /* Rounded corners */
        font-size: 14px;            /* Font size */
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff;             /* Text color for the remove button (x) */
        margin-right: 8px;          /* Space before the remove button */
        cursor: pointer;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ff0000;             /* Hover color for the remove button (x) */
    }

    
</style>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#select_all').on('click', function() {
            if ($(this).is(':checked')) {  // Add missing parentheses here
                $('.select_one').prop('checked', true);
                $('.list_pop_up_btn').removeClass('d-none');
            } else {
                $('.select_one').prop('checked', false);
                $('.list_pop_up_btn').addClass('d-none');
            }
        });

        $('.select_one').on('click', function() {
            if ($('.select_one:checked').length > 0) {
                $('.list_pop_up_btn').removeClass('d-none');
            } else {
                $('.list_pop_up_btn').addClass('d-none');
            }
        });

        // save list users

       

       
    });
    $(document).ready(function() {
        $('#exampleFormControlSelect1').select2({
            placeholder: 'Select List',
            allowClear: true
        });
    });
</script>

{{-- adding scripts on pop ups --}}

<script>
    $(document).ready(function() {
        $('#save_list_users').on('click', function() {
            var get_list_ids = $('.get_list_ids').val();
            var user_emails = [];
            $('.select_one:checked').each(function() {
                user_emails.push($(this).data('email'));
            });

            if (get_list_ids.length > 0 && user_emails.length > 0) {
                $.ajax({
                    url: "{{ route('save_users_to_list') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        list_ids: get_list_ids,
                        user_emails: user_emails.join(',') // Send user_emails as comma-separated string
                    },
                    success: function(response) {
                        if (response.status == 'success') {

                            Swal.fire({
                                title: `<div style="display: flex; align-items: center;font-size:20px"><i class="fas fa-check-circle mr-2"></i>${response.message}</div>`,
                                customClass: {
                                    popup: 'swal2-small', // Apply custom CSS class to make the dialog smaller
                                },
                                timer: 3000,
                                showConfirmButton: false,
                                position: 'top',
                                timerProgressBar: true
                            });

                            // Uncheck all selected users
                            $('.select_one:checked').prop('checked', false);
                            $('#select_all').prop('checked', false);

                            // Clear the select dropdown
                            $('.get_list_ids').val(null).trigger('change');
                            $('#email_list_pop_up').modal('hide');
                        } else if (response.status == 'error') {
                            Swal.fire({
                                title: `<div style="display: flex; align-items: center;font-size:20px"><i class="fas fa-exclamation-circle mr-2"></i>${response.error}</div>`,
                                customClass: {
                                    popup: 'swal2-small', // Apply custom CSS class to make the dialog smaller
                                },
                                timer: 3000,
                                showConfirmButton: false,
                                position: 'top',
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire({
                                title: `<div style="display: flex; align-items: center;font-size:20px"><i class="fas fa-exclamation-circle mr-2"></i>Failed to add user(s). User(s) already exists.</div>`,
                                customClass: {
                                    popup: 'swal2-small', // Apply custom CSS class to make the dialog smaller
                                },
                                timer: 3000,
                                showConfirmButton: false,
                                position: 'top',
                                timerProgressBar: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: `<div style="display: flex; align-items: center;font-size:20px"><i class="fas fa-exclamation-circle mr-2"></i>Failed to add user(s). User already exists.</div>`,
                            customClass: {
                                popup: 'swal2-small', // Apply custom CSS class to make the dialog smaller
                            },
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                });
            } else {
                
                Swal.fire({
                    title: `<div style="display: flex; align-items: center;font-size:20px"><i class="fas fa-exclamation-circle mr-2"></i>Please select user(s) and list</div>`,
                    customClass: {
                        popup: 'swal2-small', // Apply custom CSS class to make the dialog smaller
                    },
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top',
                    timerProgressBar: true
                });
            }
        });

        // bulk upload emails
        $('#save_bulk_upload').click(function() {
            var emails = $('#bulk_upload_emails').val();
            var tags = $('#bulk_tags').val();

            $.ajax({
                url: "{{ route('subscribers_bulk_upload') }}",
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    bulk_upload_emails: emails,
                    tags: tags
                }),
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
                                $('#bulk_upload_pop_up').modal('hide');
                                $('#bulk_upload_emails').val(''); // Clear the textarea
                                setTimeout(function() {
                                    window.location.href = '/newsletter/subscribers';
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
    });
   
</script>
<script>
    $('#upload_file_button').click(function() {
        var formData = new FormData($('#upload_file_form')[0]);
        $.ajax({
            url: "{{ route('subscribers.import') }}",
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
                                window.location.href = '/newsletter/subscribers';
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
                    text: 'Failed to upload emails. Your data is invalid.',
                    icon: 'error'
                });
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.bootstrap-tagsinput input[type=text]').on('keydown', function(e) {
            if (event.which == 13) {
                $(this).append(',');
                //return false;
            }
        });
    });
</script>
@endpush
