@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <div class="card my-5">
        <div class="card-header border-0">
            <div class="card-title">
                <h4>{{ $list->title }}</h4>
            </div>

            <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-bars"></i>
                </a>
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#staticBackdrop">Share</button>
            </div>

        </div>
        <!-- Button trigger modal -->

        <?php //dd($list->list_products->product);
        ?>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //dd($list->list_products);
                    ?>
                    @foreach ($list->list_products as $list_product)
                        @foreach ($list_product->product->options as $option)
                            @php
                                $retail_price = 0;
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumnForBuyList();
                                foreach ($option->price as $price) {
                                    $retail_price = $price->$user_price_column;
                                    if ($retail_price == 0) {
                                        $retail_price = $price->sacramentoUSD;
                                    }
                                    if ($retail_price == 0) {
                                        $retail_price = $price->retailUSD;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $option->image }}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    {{ $list_product->product->name }}
                                </td>
                                <td>
                                    {{ $list_product->product->code }}
                                </td>
                                <td>${{ number_format($retail_price , 2) }}</td>
                                <td class="jsutify-content-middle">
                                    <!--   <small class="text-success mr-1">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                12%
                                                                </small> -->
                                    {{ $list_product->quantity }}
                                </td>
                                <td>
                                    ${{ number_format($list_product->sub_total , 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    @if (!empty($list->shipping_and_discount))
                    <tr colspan="5">
                        <th colspan="4">Expiry Date</th>
                        <td class="">
                            <h6>{{ !empty($list->shipping_and_discount->expiry_date) ? $list->shipping_and_discount->expiry_date : '' }}</h6>
                        </td>
                    </tr>
                    <tr colspan="5">
                        <th colspan="4">Discount Limit</th>
                        <td class="">
                            <h6>{{ !empty($list->shipping_and_discount->discount_limit) ? $list->shipping_and_discount->discount_limit : 0 }}</h6>
                        </td>
                    </tr>
                    <tr colspan="5">
                        <th colspan="4">Discount Used</th>
                        <td class="">
                            <h6>{{ !empty($list->shipping_and_discount->discount_count) ? $list->shipping_and_discount->discount_count : 0 }}</h6>
                        </td>
                    </tr>
                    <tr colspan="5">
                        <th colspan="4">Shipping</th>
                        <td class="">
                            <h6>${{ !empty($list->shipping_and_discount->shipping_cost) ? number_format($list->shipping_and_discount->shipping_cost , 2) : 0.00 }}</h6>
                        </td>
                    </tr>
                    <tr colspan="5">
                        <th colspan="4">Discount</th>
                        <td class="">
                            <h6>
                                {{ !empty($list->shipping_and_discount->discount_type) && $list->shipping_and_discount->discount_type == 'fixed' ? '$' : '' }}
                                {{ !empty($list->shipping_and_discount->discount) ? $list->shipping_and_discount->discount : 0.00 }}
                                {{ !empty($list->shipping_and_discount->discount_type) && $list->shipping_and_discount->discount_type == 'percentage' ? '%' : '' }}                                
                            </h6>
                        </td>
                    </tr>
                    <tr colspan="5">
                        <th colspan="4">Discount Value</th>
                        <td class="">
                            <h6>${{ !empty($list->shipping_and_discount->discount_calculated) ? number_format($list->shipping_and_discount->discount_calculated , 2) : 0.00 }}</h6>
                        </td>
                    </tr>
                    @endif
                    <tr colspan="5">
                        <th colspan="4">Grand Total</th>
                        <td class="">
                            <h6>${{ !empty($list_product) ? number_format($list_product->grand_total , 2) : 0.00 }}</h6>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Share List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="email">Please enter email.</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                    
                        <input type="hidden" id="list_id" name="list_id" value="{{ $list->id }}">
                        <label for="">
                            Copy link to share
                        </label>
                        <div class="form-group d-flex align-items-center">
                            <input type="text" id="copy-link" class="form-control form-control-sm" value="{{ url('/create-cart/' . $list->id) }}" readonly>
                            <button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="copyLink()" title="Copy link">
                                <i class="fas fa-copy"></i>
                            </button>
                            <small id="copy-message" class="text-success ml-2" style="display: none;">Link copied!</small>
                        </div>
                        
                    
                        <div class="text-light bg-success text-center" id="share-success"></div>
                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="sendEmail();">Share</button>
                        </div>
                    </form>
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
        function sendEmail() {
            var email = $('#email').val();
            var list_id = $('#list_id').val();
            
            //alert(list_id);
            jQuery.ajax({
                url: "{{ url('/admin/share-list/') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    email: email,
                    list_id: list_id
                },
                success: function(success) {
                    if (success.success == true) {
                        var msg = success.msg;
                        $('#share-success').html(msg);
                        location.reload();
                    }
                    console.log(success);
                    //jQuery('.alert').html(result.success);
                    // window.location.reload();
                }
            });
        }
        function copyLink() {
            const input = document.getElementById("copy-link");
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            try {
                document.execCommand("copy");
                document.getElementById("copy-message").style.display = 'inline';
                setTimeout(() => {
                    document.getElementById("copy-message").style.display = 'none';
                }, 2000);
            } catch (err) {
                console.error("Copy failed", err);
            }
        }

        // function deleteProduct(product_id) {
        //     console.log(product_id);
        //     //$('#product_row_'+ product_id).remove();
        //     $(`#product_row_${product_id}`).remove();
        //     // var row = $('#product_row_' + product_id);
        //     // console.log(row);
        //     //   var row = $('#product_row_' + product_id).length;
        // 
        //     //   if (row < 1) {
        //     //           $('#grand_total').html(0.00);
        //     //   }
        //     //   var subtotal_to_remove = parseFloat($('#subtotal_'+ product_id).html());
        //     //   var grand_total = parseFloat($('#grand_total').html());
        //     //   var updated_total = parseFloat(grand_total) - parseFloat(subtotal_to_remove);
        //     //   $('#subtotal_'+ product_id).val();
        //     //   $('#product_row_'+ product_id).remove();
        //     //   $('#grand_total').html(updated_total);
        // }
    </script>
@stop
