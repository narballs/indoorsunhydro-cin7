@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2 product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="product_heading">
                                Roles
                            </p>
                        </div>
                        <div class="col-md-2 pt-3">
                            <a href="{{ route('roles.create') }}" class="btn create_new_shipping_btn">
                                Create new method +
                            </a>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-2 product_search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="admin/roles" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <table class="table border mb-5 table-shipping-method">
                    <thead>
                        <tr class="table-header-background">
                            <td class="d-flex table-row-item mt-0">
                                <div class="custom-control custom-checkbox tabel-checkbox">
                                    <input class="custom-control-input custom-control-input-success checkbox-table"
                                        type="checkbox" id="selectAll" value="">
                                    <label for="selectAll" class="custom-control-label ml-4"></label>

                                    <span class="table-row-heading-order">
                                        <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                    </span>
                                </div>
                            <td>
                                <span class="d-flex table-row-item"> Name</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item"> Action</span>
                            </td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                            <tr id="row-{{ $role->id }}" class="shipping-method-row border-bottom">
                                <td class="d-flex table-items">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success sub_chk"
                                            data-id="{{ $role->id }}" type="checkbox"
                                            id="separate_check_{{ $role->id }}">
                                        <label for="separate_check_{{ $role->id }}"
                                            class="custom-control-label ml-4"></label>
                                    </div>
                                    <span class="table-row-heading-order">
                                        {{ $key + 1 }}
                                    </span>
                                </td>
                                <td class="shipping_name">
                                    <span class="d-flex table-items-title">{{ $role->name }}</span>
                                </td>
                                <td class="created_by toggleClass td_padding_row">
                                    <div class="d-flex aling-items-center order-table-actions">
                                        <span>
                                            <a href="{{ route('roles.show', $role->id) }}" class="view a_class"
                                                title="" data-toggle="tooltip" data-original-title="View">
                                                <img src="/theme/img/view.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                        <span>
                                            <a href="{{ route('roles.edit', $role->id) }}" class="edit a_class"
                                                title="" data-toggle="tooltip" data-original-title="Edit"><img
                                                    src="/theme/img/edit.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                        <span>
                                            <a href="{{ route('roles.destroy', $role->id) }}}}"
                                                class="delete deleteIcon a_class" id="{{ $role->id }}" title=""
                                                data-toggle="tooltip" data-original-title="Delete">
                                                <img src="/theme/img/delete.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                    </tbody>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                {{ $roles->links('pagination.custom_pagination') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            color: #fff;
            /* background-color: #28a745; */
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 7px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-warning {
            color: #1f2d3d;
            background-color: #fce9a9;
            color: #ffc107 !important;
            padding: 5px;
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
    </style>
@stop
@section('js')
    <script>
        $(document).on('click', '#selectAll', function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });
    </script>
@stop
