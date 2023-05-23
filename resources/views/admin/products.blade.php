@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2">
            <div class="row mb-3">
                <div class="col-md-12 mt-3">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="order_heading">
                                Products
                            </p>
                            <p class="order_description">
                                In the product details section, you can review and manage all products with their details.
                                You
                                can view and edit information <br> such as product IDs, product name, description, price,
                                and
                                availability. Access to this area is limited to administrators and <br> team leaders. Any
                                changes you
                                make will be subject to approval after being checked for accuracy.
                            </p>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn create-new-order-btn">
                                + Create new product
                            </button>
                        </div>
                    </div>
                    <div class="row p-3 search_row_admin-interface">
                        <div class="col-md-12 order-search">
                            <div class="form-group has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/products" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <table class="table  table-hover table-product" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
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
                            <tr id="row-{{ $product->id }}" class="product-row">
                                <td>{{ $count }}</td>
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
                                    <a href="{{ url('admin/products/' . $product->id) }}" class="view a_class"
                                        title="" data-toggle="tooltip" data-original-title="View">
                                        <i class="icon-style  fas fa-eye fa-border i_class"></i>
                                    </a>
                                    <a href="#" class="edit a_class" title="" data-toggle="tooltip"
                                        data-original-title="Edit"><i class="icon-style fas fa-edit fa-border "></i>
                                    </a>
                                    <a href="#" class="delete deleteIcon a_class" id="{{ $product->id }}"
                                        title="" data-toggle="tooltip" data-original-title="Delete"><i
                                            class="icon-style fas fa-trash-alt fa-border "></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12 mt-3 border-top">
                        {{ $products->appends(Request::all())->links() }}
                    </div>
                </div>
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
                children = $(this).children('.product_name').children('span').addClass('text-successs');
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
