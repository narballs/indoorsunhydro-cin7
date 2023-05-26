@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header border-0">
                <div class="card-title">
                    <h4>
                        Edit List {{ $list->title }}</h4>
                </div>
                <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list->list_products as $list_product)
                            @foreach ($list_product->product->options as $option)
                                <tr id="product_row_{{ $list_product->product_id }}">
                                    <td>
                                        <img src="{{ $option->image }}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                        {{ $list_product->product->name }}
                                    </td>
                                    <td>${{ $list_product->product->retail_price }}</td>
                                    <td class="jsutify-content-middle">
                                        <!--   <small class="text-success mr-1">
                                                                <i class="fas fa-arrow-up"></i>
                                                            12%
                                                            </small> -->
                                        <input id="quantity_{{ $list_product->product_id }}" type="number"
                                            value="{{ $list_product->quantity }}"
                                            onchange="quantityChange({{ $list_product->product->product_id }})">
                                    </td>
                                    <td id="subtotal_{{ $list_product->product_id }}">
                                        {{ $list_product->sub_total }}
                                    </td>
                                    <td>
                                        <a class="cursor-pointer delete" title="" data-toggle="tooltip"
                                            data-original-title="Delete">
                                            <i class="fas fa-trash-alt cursor-pointer"
                                                onclick="deleteProduct({{ $list_product->product->product_id }})"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
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
            color: #fff;
            /* background-color: #28a745; */
            background: rgba(124, 198, 51, 0.2);
            color: #7CC633;
            padding: 7px !important;
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
        function deleteProduct(product_id) {
            console.log(product_id);
            //$('#product_row_'+ product_id).remove();
            $(`#product_row_${product_id}`).remove();
            // var row = $('#product_row_' + product_id);
            // console.log(row);
            //   var row = $('#product_row_' + product_id).length;

            //   if (row < 1) {
            //           $('#grand_total').html(0.00);
            //   }
            //   var subtotal_to_remove = parseFloat($('#subtotal_'+ product_id).html());
            //   var grand_total = parseFloat($('#grand_total').html());
            //   var updated_total = parseFloat(grand_total) - parseFloat(subtotal_to_remove);
            //   $('#subtotal_'+ product_id).val();
            //   $('#product_row_'+ product_id).remove();
            //   $('#grand_total').html(updated_total);
        }

        function quantityChange(product_id) {
            var difference = 0;
            var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
            console.log('difference => ' + difference);
            console.log('sub total before update  => ' + subtotal_before_update);

            var retail_price = parseFloat($('#retail_price_' + product_id).html());
            var quantity = parseFloat($('#quantity_' + product_id).val());
            var subtotal = parseFloat($('#subtotal_' + product_id).html());


            subtotal = retail_price * quantity;
            difference = subtotal_before_update - subtotal;

            console.log('difference => ' + difference);

            var grand_total = $('#grand_total').html();
            grand_total = parseFloat(grand_total);

            console.log('Grand Total => ' + grand_total);


            grand_total = grand_total - difference;
            $('#grand_total').html(grand_total);

            console.log('Grand Total => ' + grand_total);

            $('#quantity_' + product_id).val(quantity);
            $('#subtotal_' + product_id).html(subtotal);

        }
    </script>
@stop
