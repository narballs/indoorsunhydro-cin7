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
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">

    <style type="text/css">
        .input-group-btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #custom-search-input {
            padding: 3px;
            border: solid 1px #E4E4E4;
            border-radius: 6px;
            background-color: #fff;
        }

        #custom-search-input input {
            border: 0;
            box-shadow: none;
            padding-top: 22px !important;
            width: 329px !important;
        }

        #custom-search-input button {
            margin: 2px 0 0 0;
            background: none;
            box-shadow: none;
            border: 0;
            color: #666666;
            padding: 0 8px 0 10px;
            border-right: solid 1px #ccc;
        }

        #custom-search-input button:hover {
            border: 0;
            box-shadow: none;
            border-left: solid 1px #ccc;
        }

        #custom-search-input .glyphicon-search {
            font-size: 23px;
        }

        .text-successs {
            color: #7CC633 !important;
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
