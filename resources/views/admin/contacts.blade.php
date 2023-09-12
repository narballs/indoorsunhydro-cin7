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
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                Suppliers
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                    role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-4 create_bnt d-flex justify-content-end mobile_fulfill_div">
                            <div class="d-flex">
                                <span class="create_new_btn_mbl">
                                    <button type="button" class="btn create_new_product_btn">
                                        Create New Supplier +
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/contacts" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table bg-white  mb-0 table-contact-method">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item mt-0">
                                    <div class="custom-control custom-checkbox tabel-checkbox d-flex">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label ml-4"></label>
                                        
                                        <span class="table-row-heading-order d-flex">
                                            <input type="hidden" class="" name="sort_by_desc" id="sort_by_desc" value="Desc">
                                            <input type="hidden" class="ml-1" name="sort_by_asc" id="sort_by_asc" value="Asc">
                                            <i class="fas fa-arrow-up  sm-d-none" style="font-size:14.5px ;" onclick="sortbyDesc()"></i>
                                            <i class="fas fa-arrow-down  sm-d-none ml-1" style="font-size:14.5px ;" onclick="sortbyAsc()"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item">Name
                                        <span class="ml-1 d-flex">
                                            <input type="hidden" class="" name="sort_by_name" id="sort_name_by_desc" value="Desc">
                                            <input type="hidden" class="ml-1" name="sort_by_name" id="sort_name_by_asc" value="Asc">
                                            <i class="fas fa-arrow-up  sm-d-none text-dark" style="font-size:14.5px ;" onclick="sortnamebyDesc()"></i>
                                            <i class="fas fa-arrow-down  sm-d-none text-dark ml-1" style="font-size:14.5px ;" onclick="sortnamebyAsc()"></i>
                                        </span>
                                    </span>
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
                                    <span class="d-flex table-row-item"></span>
                                </td>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $key => $contact)
                                <tr id="row-{{ $contact->id }}" class="supplier-row border-bottom">
                                    <td class="d-flex table-items" style="  padding-top: 14px !important; ">
                                        <div class="custom-control custom-checkbox tabel-checkbox">
                                            <input class="custom-control-input custom-control-input-success sub_chk"
                                                data-id="{{ $contact->id }}" type="checkbox"
                                                id="separate_check_{{ $contact->id }}">
                                            <label for="separate_check_{{ $contact->id }}"
                                                class="custom-control-label ml-4"></label>
                                        </div>
                                        <span class="table-row-heading-order sm-d-none ">
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
                                    <td class="created_by toggleClass">
                                        <div class="d-flex aling-items-center order-table-actions">
                                            <span>
                                                <a href="{{ url('admin/order-detail/' . $contact->id) }}"
                                                    class="view a_class" title="" data-toggle="tooltip"
                                                    data-original-title="View">
                                                    <img src="/theme/img/view.png" alt="" class="img-fluid">
                                                </a>
                                            </span>
                                            <span>

                                                <a href="javascript:void(0)" data-id="{{ $contact->id }}"
                                                    class="edit a_class" title="" data-toggle="tooltip"
                                                    data-original-title="Edit"><img src="/theme/img/edit.png" alt=""
                                                        class="img-fluid">
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
                                <td colspan="7" class="p-0">
                                    {{ $contacts->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css?v2">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css?v2') }}">
        <style type="text/css">
            @media(min-width:280px) and (max-width: 425px) {
                .main-header {
                    border-bottom: none;
                    width: 25%;
                    height: 0px !important;
                    margin-top: 20px !important;
                }

                .mobile_heading {
                    position: absolute;
                    left: 10rem;
                    top: -3rem;

                }

                .search_row_admin-interface {
                    position: absolute;
                    top: 1rem;
                    left: 1rem;
                    width: 95%;
                }

                .fullfill_btn_mbl {
                    position: absolute;
                    left: 3.3rem;
                }

                .fullfill_btn_mbl {
                    position: absolute;
                    left: 3.3rem;
                }

                .create_new_btn_mbl {
                    margin-right: 0.5rem;
                }

                .product_section_header {
                    border-bottom: none !important;
                }

                .sm-d-none {
                    display: none !important;
                }

                .bx-mobile {
                    display: flex !important;
                    justify-content: space-around !important;
                    align-items: center !important;
                }

                .mobile-screen-selected {
                    width: 30%;
                }

                .mobile-screen-ordrs-btn {
                    width: 70%;
                }

                .product_table_body {
                    padding-left: 11px !important;
                    padding-right: 7px !important;
                    padding-top: 9px !important;
                }

                .select-row-items {
                    padding-left: 12px !important;
                    display: flex;
                    justify-content: start;
                    align-items: center !important;
                    color: #222222 !important;
                    font-style: normal !important;
                    font-weight: 500 !important;
                    font-size: 0.826rem !important;
                    padding-top: 0px !important;
                }

                .order_heading {
                    font-size: 18px !important;
                    font-weight: 500 !important;
                    line-height: 24px !important;
                    letter-spacing: -0.252px !important;
                    font-family: 'Poppins', sans-serif !important;
                    margin-left: 37px !important;
                    color: #242424 !important;
                    margin-top: 20px !important;
                }

                .mobile_screen_Previous_btn {
                    width: 25% !important;
                }

                .mobile_screen_pagination_number {
                    width: 50% !important;
                }

                .mobile_screen_Previous_next {
                    width: 25% !important;
                    margin-top: 11px !important;
                }

                .main-sidebar {
                    background-color: #fff !important;
                    box-shadow: none !important;
                    border-right: 1px solid #EAECF0 !important;
                    top: -21px !important;
                }

            }

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
            // main sorting
            function sortbyDesc() {
                var search = $('#search').val();
                var sort_by = $('#sort_by_desc').val();
                var basic_url = '/admin/contacts?&search=' + search;
                if (sort_by != '') {
                    basic_url = basic_url + `&sort_by_desc=${sort_by}`;
                }
                window.location.href = basic_url
            }
            function sortbyAsc() {
                var search = $('#search').val();
                var sort_by = $('#sort_by_asc').val();
                var basic_url = '/admin/contacts?&search=' + search;
                if (sort_by != '') {
                    basic_url = basic_url+`&sort_by_asc=${sort_by}`;
                }
                window.location.href = basic_url
            }
            // sort by name
            function sortnamebyDesc() {
                var search = $('#search').val();
                var sort_by = $('#sort_name_by_desc').val();
                var basic_url = '/admin/contacts?&search=' + search;
                if (sort_by != '') {
                    basic_url = basic_url + `&sort_by_name=${sort_by}`;
                }
                window.location.href = basic_url
            }
            function sortnamebyAsc() {
                var search = $('#search').val();
                var sort_by = $('#sort_name_by_asc').val();
                var basic_url = '/admin/contacts?&search=' + search;
                if (sort_by != '') {
                    basic_url = basic_url+`&sort_by_name=${sort_by}`;
                }
                window.location.href = basic_url
            }
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
