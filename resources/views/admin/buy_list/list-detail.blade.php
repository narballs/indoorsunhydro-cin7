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
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
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
                                    ${{ $list_product->product->code }}
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
                                    ${{ $list_product->sub_total }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr colspan="5">
                        <th colspan="4">Grand Total</th>
                        <td class="">
                            <h4>${{ !empty($list_product) ? $list_product->grand_total : 0.00 }}</h4>
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
                            <label for="exampleFormControlTextarea1">Please enter email.</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <input type="hidden" id="list_id" name="list_id" value="{{ $list->id }}">
                        <div class="text-light bg-success text-center " id="share-success">

                        </div>
                </div>
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
                        console.log('ppp');
                        var msg = success.msg;
                        $('#share-success').html(msg);
                    }
                    console.log(success);
                    //jQuery('.alert').html(result.success);
                    // window.location.reload();
                }
            });
        }
    </script>
@stop
