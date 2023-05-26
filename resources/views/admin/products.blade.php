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
                        <div class="col-md-10">
                            <p class="product_heading">
                                Products
                            </p>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn create_new_product_btn">
                                Create New proudct +
                            </button>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-2 product_search">
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
                <table class="table border mb-5 table-product" id="table">
                    <thead>
                        <tr class="table-header-background">
                            <td class="d-flex table-row-item">
                                <span class="tabel-checkbox">
                                    <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                </span>
                                <span class="table-row-heading">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
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
                                <span class="d-flex table-row-item"> Action</span>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="searched">
                        <?php $count = 0; ?>
                        @foreach ($products as $key => $product)
                            <?php $count++; ?>
                            <tr id="row-{{ $product->id }}" class="product-row border-bottom">
                                <td class="d-flex table-items">
                                    <span class="tabel-checkbox">
                                        <input type="checkbox" name="test" class="checkbox-table">
                                    </span>
                                    <span class="table-row-heading">
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
                                <td class="product_action">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h" style="color: #CBCBCB !important;"></i>
                                        </button>
                                        <div class="dropdown-menu dropdonwn_menu">
                                            <a class="dropdown-item" href="{{ url('admin/products/' . $product->id) }}"
                                                class="view a_class" title="" data-toggle="tooltip"
                                                data-original-title="View">Previews
                                            </a>
                                            <a class="dropdown-item delete deleteIcon a_class" href="#" class=""
                                                id="{{ $product->id }}" title="" data-toggle="tooltip"
                                                data-original-title="Delete">Delete
                                            </a>
                                            <a class="dropdown-item"href="#" class="edit a_class" title=""
                                                data-toggle="tooltip" data-original-title="Edit">Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                <div class="col-md-12">
                                    {{ $products->links('pagination.custom_pagination') }}
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
            .text-successs {
                color: #7CC633 !important;
                font-family: 'Poppins', sans-serif !important;
            }

            .badge-success {
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

            .badge-warning {
                color: #1f2d3d;
                background-color: #fce9a9;
                color: #ffc107 !important;
                padding: 5px;
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
