@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-import-contacts badge-success">Import Contacts</button>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            color: #fff;
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: #f1eaea;
            color: #B42318;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-secondary {
            color: #8e8b8b !important;
            background-color: #d0dce6 !important;
            padding: 7px !important;
            border-radius: 6px;
        }

        .badge-primary {
            background-color: #339AC6;
            color: #339AC6 !important;
            padding: 5px;
        }
    </style>
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
