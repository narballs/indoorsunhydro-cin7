@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@endsection
@section('content')
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="product_heading">
                                Products
                            </p>
                        </div>
                        <div class="col-md-1">
                            <div id="div_import_contacts" class="spinner-border hide" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end create_bnt">
                            <button type="button" class="btn create_new_product_btn btn-import-contacts">
                                Import Contacts +
                            </button>
                        </div>
                        <div class="col-md-6 m-auto" id="div_message"></div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-2 product_search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/dashboard" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-import-contacts create-new-order-btn">
                                Import Contacts
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css?v2">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css?v2') }}">
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
                            $('#div_message').html(
                                '<div class="alert alert-success" role="alert">' +
                                response.message + '</div>');
                        } else {
                            $('#div_message').html(
                                '<div class="alert alert-danger" role="alert">' + response
                                .message + '</div>');
                        }
                    }
                });

            });
        });
    </script>
@endsection
