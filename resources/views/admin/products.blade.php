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
                                Products
                            </p>
                        </div>
                        <div class="col-md-2 pt-3">
                            <button type="button" class="btn create_new_product_btn">
                                Create New proudct +
                            </button>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 product_search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/products" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <table class="table border rounded-2 mb-5 mt-4 table-product" id="table">
                    <thead>
                        <tr class="table-header-background">
                            <th> <input type="checkbox" name="test" class="checkbox-table"> #</th>
                            <th>Name <i class="fa fa-sort"></i></th>
                            <th>Code <i class="fa fa-sort"></i></th>
                            <th>fulfillment<i class="fa fa-sort"></i></th>
                            <th>Retail Price <i class="fa fa-sort"></i></th>
                            <th>Actions <i class="fa fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="searched">
                        <?php $count = 0; ?>
                        @foreach ($products as $key => $product)
                            <?php $count++; ?>
                            <tr id="row-{{ $product->id }}" class="product-row border-bottom">
                                <td>
                                    <input type="checkbox" name="test" class="checkbox-table">
                                    {{ $count }}
                                </td>
                                <td class="product_name">
                                    <span class="product_name_slg">{{ $product->name }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $product->code }}</span>
                                </td>
                                <td>
                                    {{ $product->status }}
                                </td>
                                <td>
                                    <span class="product_retail_price">
                                        ${{ $product->retail_price }}
                                    </span>
                                </td>
                                <td class="product_action">
                                    {{-- <a href="" class="view a_class" title="" data-toggle="tooltip"
                                        data-original-title="View">
                                        <i class="icon-style  fas fa-eye fa-border i_class"></i>
                                    </a>
                                    <a href="#" class="edit a_class" title="" data-toggle="tooltip"
                                        data-original-title="Edit"><i class="icon-style fas fa-edit fa-border "></i>
                                    </a>
                                    <a href="#" class="delete deleteIcon a_class" id="{{ $product->id }}"
                                        title="" data-toggle="tooltip" data-original-title="Delete"><i
                                            class="icon-style fas fa-trash-alt fa-border "></i>
                                    </a> --}}

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
                                    {{ $products->appends(Request::all())->links() }}
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
                background: rgba(124, 198, 51, 0.2);
                color: #7CC633;
                padding: 7px !important;
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

            .badge-danger {
                color: #fff;
                background-color: #f1abb2;
                color: #f14f4f;
                padding: 6px !important;
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
        </script>

    @endsection
