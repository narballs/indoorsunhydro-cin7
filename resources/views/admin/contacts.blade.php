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
                                Suppliers
                            </p>
                        </div>
                        <div class="col-md-2 pt-3">
                            <a href="#" class="btn create_new_shipping_btn">
                                Create New Supplier +
                            </a>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-2 product_search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="admin/admin/contacts" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <table class="table border mb-5 table-contact-method">
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
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Name</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Status</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Price Tier</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Company</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Notes</span>
                            </td>
                            <td>
                                <span class="d-flex table-row-item">Action</span>
                            </td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $key => $contact)
                            <tr id="row-{{ $contact->id }}" class="supplier-row border-bottom">
                                <td class="d-flex table-items">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success sub_chk"
                                            data-id="{{ $contact->id }}" type="checkbox"
                                            id="separate_check_{{ $contact->id }}">
                                        <label for="separate_check_{{ $contact->id }}"
                                            class="custom-control-label ml-4"></label>
                                    </div>
                                    <span class="table-row-heading-order">
                                        {{ $key + 1 }}
                                    </span>
                                </td>
                                <td class="supplier_name pb-0 pt-3">
                                    <span>
                                        {{ $contact->firstName }}
                                    </span>
                                </td>
                                <td class="supplier-status pb-0 pt-3">
                                    @if ($contact->status == '1')
                                        <span class="badge badge-success  supplier_status_1">Active</span>
                                    @else
                                        <span class="badge badge-warning supplier_status_0">Inactive</span>
                                    @endif
                                </td>
                                <td class="pb-0 pt-3">
                                    @if (!empty($contact->priceColunm))
                                        {{ $contact->priceColumn }}
                                    @else
                                        <span class="badge badge-danger supplier_status_2">empty</span>
                                    @endif
                                </td>
                                <td class="pb-0 pt-3">
                                    <span>
                                        {{ $contact->company }}
                                    </span>
                                </td>
                                <td class="pb-0 pt-3">
                                    <span>
                                        {{ $contact->notes }}
                                    </span>
                                </td>
                                {{-- <td class="created_by toggleClass">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h" style="color: #CBCBCB !important;"></i>
                                        </button>
                                        <div class="dropdown-menu dropdonwn_menu">
                                            <a class="dropdown-item" href="{{ url('admin/order-detail/' . $contact->id) }}"
                                                class="view a_class" title="" data-toggle="tooltip"
                                                data-original-title="View">Previews
                                            </a>
                                            <a class="dropdown-item"href="#" class="edit a_class" title=""
                                                data-toggle="tooltip" data-original-title="Edit">Edit
                                            </a>
                                            <a class="dropdown-item delete deleteIcon a_class" href="#" class=""
                                                id="{{ $contact->id }}" title="" data-toggle="tooltip"
                                                data-original-title="Delete">Delete
                                            </a>
                                        </div>
                                    </div>
                                </td> --}}
                                <td class="created_by toggleClass">
                                    <div class="d-flex aling-items-center order-table-actions">
                                        <span>
                                            <a href="{{ url('admin/order-detail/' . $contact->id) }}" class="view a_class"
                                                title="" data-toggle="tooltip" data-original-title="View">
                                                <img src="/theme/img/view.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                        <span>

                                            <a href="javascript:void(0)" data-id="{{ $contact->id }}" class="edit a_class"
                                                title="" data-toggle="tooltip" data-original-title="Edit"><img
                                                    src="/theme/img/edit.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                        <span>
                                            <a href="javascript:void(0)" data-id="{{ $contact->id }}"
                                                class="delete deleteIcon a_class" title="" data-toggle="tooltip"
                                                data-original-title="Delete">
                                                <img src="/theme/img/delete.png" alt="" class="img-fluid">
                                            </a>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                {{ $contacts->links('pagination.custom_pagination') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css?v2">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css?v2') }}">
        <style type="text/css">
            .text-successs {
                color: #7CC633 !important;
                font-family: 'Poppins', sans-serif !important;
            }

            .badge-success {
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
                background-color: rgba(220, 78, 65, 0.12);
                color: #DC4E41;
                padding: 6px !important;
                font-style: normal;
                font-weight: 500;
                font-size: 11.3289px;
            }
        </style>
    @stop

    @section('js')
        <script>
            $('.supplier-row-none').hover(function() {
                let id = $(this).attr('id');
                children = $(this).children('.supplier_name').children('span').addClass('text-successs');


                bg_success = $(this).children('.supplier-status').children('.supplier_status_1').addClass(
                    'background-success');
                bg_success = $(this).children('.supplier-status').children('.supplier_status_0').addClass(
                    'background-warning');
                bg_success = $(this).children('.supplier-status').children('.supplier_status_2').addClass(
                    'background-danger');


                let tet = $(this).children('.supplier_action').children('a');
                let get_class = tet.each(function(index, value) {
                    let test = tet[index].children[0];
                    test.classList.add('bg-icon');
                });
            });


            $('.supplier-row-none').mouseleave(function() {
                let id = $(this).attr('id');
                children = $(this).children('.supplier_name').children('span').removeClass('text-successs');

                bg_success = $(this).children('.supplier-status').children('.supplier_status_1').removeClass(
                    'background-success');
                bg_success = $(this).children('.supplier-status').children('.supplier_status_0').removeClass(
                    'background-warning');
                bg_success = $(this).children('.supplier-status').children('.supplier_status_2').removeClass(
                    'background-danger');

                let tet = $(this).children('.supplier_action').children('a');
                let get_class = tet.each(function(index, value) {
                    let test = tet[index].children[0];
                    test.classList.remove('bg-icon');
                });
            });

            $(document).on('click', '#selectAll', function(e) {
                var table = $(e.target).closest('table');
                $('td input:checkbox', table).prop('checked', this.checked);
            });
        </script>
    @stop
