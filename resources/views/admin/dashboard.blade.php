@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-import-contacts btn-success">Import Contacts</button>
                            <div id="div_import_contacts" class="spinner-border hide ml-4" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4" id="div_message"></div>
                    </div>


                </div>
            </div>
        </div>

    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#div_import_contacts').hide();

            $('body').on('click', '.btn-import-contacts', function() {
                var btn = $(this);
                btn.prop('disabled', true);
                $('#div_import_contacts').show();
                $('#div_message').html('Importing Contacts... Please do not close this window.');


                $.ajax({
                    url: "{{ url('admin/commands/import_contacts') }}",
                    method: 'get',
                    data: {},
                    success: function(response) {
                        btn.prop('disabled', false);
                        $('#div_import_contacts').hide();

                        if (response.status == 'success') {
                            $('#div_message').html('<div class="alert alert-success">' +
                                response.message + '</div>');
                        } else {
                            $('#div_message').html('<div class="alert alert-danger">' + response
                                .message + '</div>');
                        }
                    }
                });

            });
        });
    </script>
@endsection
