@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                Products
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
                                        Create New Product +
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/products" class="mb-2">
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
                    <table class="table mb-0 bg-white table-product" id="table">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label ml-4"></label>

                                        <span class="table-row-heading-order">
                                            <i class="fas fa-arrow-up mt-1 sm-d-none" style="font-size:14.5px ;"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Name</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Code</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Fulfillment</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Retail Price</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"></span>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="searched">
                            <?php $count = 0; ?>
                            @foreach ($products as $key => $product)
                                <?php $count++; ?>
                                <tr id="row-{{ $product->id }}" class="product-row border-bottom">
                                    <td class="d-flex table-items">
                                        <div class="custom-control custom-checkbox tabel-checkbox">
                                            <input class="custom-control-input custom-control-input-success sub_chk"
                                                data-id="{{ $product->id }}" type="checkbox"
                                                id="separate_check_{{ $product->id }}">
                                            <label for="separate_check_{{ $product->id }}"
                                                class="custom-control-label ml-4"></label>
                                        </div>
                                        <span class="table-row-heading-order sm-d-none">
                                            {{ $key + 1 }}
                                        </span>
                                    </td>
                                    <td class="product_name">
                                        <span class="product_name_slg d-flex table-items-title">
                                            {{ $product->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="product_name_slg d-flex table-items-title">{{ $product->code }}</span>
                                    </td>
                                    <td>
                                        @if ($product->status == 'Public')
                                            <span class="badge badge-success">
                                                {{ $product->status }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                {{ $product->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="product_retail_price">
                                            <span class="d-flex table-items-title"> ${{ $product->retail_price }}</span>
                                        </span>
                                    </td>
                                    <td class="created_by toggleClass td_padding_row">
                                        <div class="d-flex aling-items-center order-table-actions">
                                            <span>
                                                <a href="{{ url('admin/products/' . $product->id) }}" class="view a_class"
                                                    title="" data-toggle="tooltip" data-original-title="View">
                                                    <img src="/theme/img/view.png" alt="" class="img-fluid">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="#" class="edit a_class" title="" data-toggle="tooltip"
                                                    data-original-title="Edit"><img src="/theme/img/edit.png" alt=""
                                                        class="img-fluid">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="#" class="delete deleteIcon a_class"
                                                    id="{{ $product->id }}" title="" data-toggle="tooltip"
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
                                <td colspan="10">
                                    {{ $products->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @stop

        @section('css')
            <link rel="stylesheet" href="/theme/css/admin_custom.css">
            <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
            <style>
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
                        width: 50%;

                    }

                    .search_row_admin-interface {
                        position: absolute;
                        top: 1rem;
                        left: 1rem;
                        width: 95%;
                    }

                    .mobile_fulfill_div {
                        margin-top: 3.563rem
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
                        font-family: Poppins, sans-serif !important;
                        font-weight: 500 !important;
                        line-height: 24px !important;
                        letter-spacing: 0.252px;
                        color: #242424 !important;
                        margin-top: 24px !important;
                        margin-bottom: 0.5rem !important;
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

                /* mobile responsive admin panel end */
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
                    color: #b58903 !important padding: 6px !important;
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
                    padding: 6px !important;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 11.3289px;
                }

                .badge-primary {
                    background-color: #339AC6;
                    color: #339AC6 !important;
                    padding: 6px !important;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 11.3289px;
                }
            </style>
        @stop

        @section('js')
            <script>
                $('.product-row').hover(function() {
                    let id = $(this).attr('id');
                    children = $(this).children('.product_name').childern('a').children('span').addClass(
                        'text-successs');
                    console.log(children);
                    let tet = $(this).children('.product_action').children('a');
                    let get_class = tet.each(function(index, value) {
                        let test = tet[index].children[0];
                        test.classList.add('bg-icon');
                    });
                });


                $('.product-row').mouseleave(function() {
                    let id = $(this).attr('id');
                    children = $(this).children('.product_name').children('span').removeClass('text-successs');
                    let tet = $(this).children('.product_action').children('a');
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

        @endsection
