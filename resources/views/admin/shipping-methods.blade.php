@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2 product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2 mobile_heading">
                            <p class="order_heading">
                                Shipping Methods
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
                                        Create new method +
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/shipping-methods" class="mb-2">
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
                    <table class="table bg-white  mb-0 table-shipping-method">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item mt-0">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label ml-4"></label>

                                        <span class="table-row-heading-order">
                                            <i class="fas fa-arrow-up mt-1 sm-d-none " style="font-size:14.5px ;"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Name</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Cost</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Status</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"></span>
                                </td>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shippingmethods as $key => $shippingmethod)
                                @if (!empty($shippingmethod))
                                    <tr id="row-{{ $shippingmethod->id }}" class="shipping-method-row border-bottom">
                                        <td class="d-flex table-items">
                                            <div class="custom-control custom-checkbox tabel-checkbox">
                                                <input class="custom-control-input custom-control-input-success sub_chk"
                                                    data-id="{{ $shippingmethod->id }}" type="checkbox"
                                                    id="separate_check_{{ $shippingmethod->id }}">
                                                <label for="separate_check_{{ $shippingmethod->id }}"
                                                    class="custom-control-label ml-4"></label>
                                            </div>
                                            <span class="table-row-heading-order sm-d-none ">
                                                {{ $key + 1 }}
                                            </span>
                                        </td>
                                        <td class="shipping_name">
                                            <span class="d-flex table-items-title">{{ $shippingmethod->title }}</span>
                                        </td>
                                        <td class="d-flex table-items">{{ $shippingmethod->cost }}</td>
                                        @if ($shippingmethod->status == 1)
                                            <td>
                                                <span class="badge badge-success status-disabled"> Enabled</span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-danger status-disabled"> Disabled</span>
                                            </td>
                                        @endif
                                        <td class="created_by toggleClass td_padding_row">
                                            <div class="d-flex aling-items-center order-table-actions">
                                                <span>
                                                    <a href="{{ url('admin/shipping-details/' . $shippingmethod->id) }}"
                                                        class="view a_class" title="" data-toggle="tooltip"
                                                        data-original-title="View">
                                                        <img src="/theme/img/view.png" alt="" class="img-fluid">
                                                    </a>
                                                </span>
                                                <span>

                                                    <a href="{{ url('admin/shipping-method/' . $shippingmethod->id) }}"
                                                        class="edit a_class" title="" data-toggle="tooltip"
                                                        data-original-title="Edit"><img src="/theme/img/edit.png"
                                                            alt="" class="img-fluid">
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="#" class="delete deleteIcon a_class"
                                                        id="{{ $shippingmethod->id }}" title="" data-toggle="tooltip"
                                                        data-original-title="Delete">
                                                        <img src="/theme/img/delete.png" alt="" class="img-fluid">
                                                    </a>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No Shipping Methods Found</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    {{ $shippingmethods->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
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
                padding-top: 8px !important;
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
                margin-left: -34px !important;
                color: #242424 !important;
                margin-bottom: 0.5rem !important;
                margin-top: 20px !important;
                padding-left: 37px !important;
            }
        }

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
        $('.shipping-method-row-none').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.shipping_name').children('span').addClass('text-successs');
            let tet = $(this).children('.shipping_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.shipping-method-row-none').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.shipping_name').children('span').removeClass('text-successs');
            let tet = $(this).children('.shipping_action').children('a');
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
