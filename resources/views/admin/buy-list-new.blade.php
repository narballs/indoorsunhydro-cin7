@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Add New Buylist</h1>
@stop

@section('content')
    <style>
        nav svg {
            max-height: 20px !important;
        }
    </style>
    <div class="row">
        <div class="col-md-5 card">
            <div class="alert alert-success d-none" role="alert" id="success_msg"></div>
            <div class="row mt-5">
                @if ($list)
                    <div class="form-group col-md-12">
                        <label for="list_name">Title</label>
                        <input type="text" class="form-control" value={{ $list->title }} id="title"
                            aria-describedby="titleHelp" name="title" placeholder="Buy List Title">
                        <div class="text-danger" id="title_errors"></div>
                    </div>
                @else
                    <div class="form-group col-md-12">
                        <label for="list_name">Title</label>
                        <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                            name="title" placeholder="Buy List Title">
                        <div class="text-danger" id="title_errors"></div>
                    </div>
                @endif
                @if (!empty($list->status))
                    {{-- <div class="form-group col-md-6 mb-0">
                        <label for="type" name="type">Status</label>
                        <select class="form-control" name="type" id="status">
                            <option value="{{ $list->status }}">{{ $list->status }}</option>
                        </select> --}}
                        <input type="hidden" name="type" id="status" value="{{ $list->status }}">
                        {{-- <div id="status_errors" class="text-danger"></div> --}}
                    {{-- </div> --}}
                @else
                    {{-- <div class="form-group col-md-6 mb-0">
                        <label for="type" name="type">Status</label>

                        <select class="form-control" name="type" id="status">
                            <option value="Public">Public</option>
                            <option value="Private">Private</option>
                            <option value="Shareable">Shareable</option>
                        </select>
                        <div id="status_errors" class="text-danger"></div>
                    </div> --}}
                    <input type="hidden" name="type" id="status" value="Public">
                @endif
                @if (!empty($list->description))
                    <?php //dd($list);
                    ?>
                    <div class="col-md-12 card mt-5">
                        <div class="card-body">
                            <h4>Description</h4>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="mobile"></label>
                            <textarea class="form-control" onfocus="this.select()" type="text" rows="10" name="notes" id="description">{{ $list->description }}</textarea>
                            <div id="description_errors" class="text-danger"></div>
                        </div>
                    </div>
                @else
                    <div class="col-md-12 card mt-5">
                        <div class="card-body">
                            <h4>Description</h4>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="mobile"></label>
                            <textarea class="form-control" rows="10" name="notes" id="description"></textarea>
                            <div id="description_errors" class="text-danger"></div>
                        </div>
                    </div>
                @endif
                @if (!empty($list->id))
                    <div class="d-flex justify-content-center my-2 col-12"
                        style="">
                        <button type="button" class="ms-2 btn btn-primary" onclick="createList()">
                            Update List
                        </button>
                    </div>
                @else
                    <div class="d-flex justify-content-center my-2 col-12"
                        style="">
                        <button type="button" class="ms-2 btn btn-primary" onclick="createList()">
                            Create List
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7 card">
            @livewire('filter')
        </div>
    </div>
    @if (!empty($list->list_products))
        <div class="row w-100 pl-2 pr-0">
            <div class="card col-md-12">
                <div class="card-body w-100" id="list">
                    <div id="list_title">
                        <h4></h4>
                    </div>
                    <input type="hidden" id="list_id" value="{{ $list->id }}">
                    <input type="hidden" id="is_update" value="1">
                    <table id="product_list" class="table">
                        <tr>
                            <td style="width:373px !important">Product Title</td>
                            <td>Sku</td>
                            <td>Image</td>
                            <td>Price</td>
                            <td>Quantity</td>
                            <td>Subtotal</td>
                            <td>Remove</td>
                        </tr>
                        <?php //dd($list);
                        ?>
                        @foreach ($list->list_products as $list_product)
                            @foreach ($list_product->product->options as $option)
                            <input type="hidden" name="product_buy_list_stock" id="product_buy_list_stock_{{ $product->product_id }}" value="{{ $option->stockAvailable }}">
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
                                <!-- <tr id="product_row_{{ $list_product->product_id }}"> -->
                                <tr id="product_row_{{ $list_product->product_id }}"
                                    class="product-row-{{ $list_product->product_id }} admin-buy-list">
                                    <td>
                                        {{ $list_product->product->name }}
                                    </td>
                                    <td>
                                        {{ $list_product->product->code }}
                                    </td>
                                    <td>
                                        <input type="hidden" id="option_id_{{ $list_product->product_id }}"
                                            value="{{ $option->option_id }}">
                                        <img src="{{ $option->image }}" alt="Product 1"
                                            class="img-circle img-size-32 mr-2">
                                    </td>
                                    <td>
                                        $<span id="retail_price_{{ $list_product->product_id }}">
                                            {{ number_format($retail_price , 2) }} </span></td>
                                    <td>
                                        <input type="number" min="1"
                                            id="quantity_{{ $list_product->product_id }}"
                                            value="{{ $list_product->quantity }}"
                                            onchange="handleQuantity({{ $list_product->product_id }})">
                                    </td>
                                    <td>
                                        $<span id="subtotal_{{ $list_product->product_id }}">
                                            {{ number_format(floatval($retail_price * $list_product->quantity), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a class="cursor-pointer delete" title="" data-toggle="tooltip"
                                            data-original-title="Delete">
                                            <i class="fas fa-trash-alt cursor-pointer"
                                                onclick="deleteProduct({{ $list_product->product_id }})"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                    </table>
                    <div class="row align-items-center border-top">
                        <div class="col-md-3 my-2"><strong>Shipping</strong></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <label for="shipping_price">Shipping Price</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="shipping_price_value" name="shipping_price" onchange="add_shipping(this)" class="form-control" value="{{ isset($list->shipping_and_discount) ? $list->shipping_and_discount->shipping_cost : 0.00 }}" step="any">  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center border-top">
                        <div class="col-md-3 my-2"><strong>Discount</strong></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="discount_type">Discount Type</label>
                                    <div class="form-group">
                                        <select class="form-control" name="discount_type" id="discount_type" onchange="discount_type(this)">
                                            <option value="percentage" {{ isset($list->shipping_and_discount) && $list->shipping_and_discount->discount_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                            <option value="fixed" {{ isset($list->shipping_and_discount) && $list->shipping_and_discount->discount_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="discount_value">Discount</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="discount_value" name="discount_type_value" onchange="add_discount(this)" class="form-control" value="{{ isset($list->shipping_and_discount) ? $list->shipping_and_discount->discount : 0}}" step="any">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="discount_value">Discount Calculated</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="discount_calculated" name="discount_calculated" readonly class="form-control" value="{{ isset($list->shipping_and_discount) ? $list->shipping_and_discount->discount_calculated : 0}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center border-top">
                        <div class="col-md-10 my-2"><strong>Grand Total</strong></div>
                        <div class="col-md-2 ">Amount :$ 
                            <span id="grand_total">
                                {{ !empty($list_product) ? $list_product->grand_total : 0.00 }}
                            </span>
                        </div>
                    </div>
                    <div class="row align-items-center  border-top">
                        <div class="col-md-10 my-2"><button type="button" class="ms-2 btn btn-primary"
                                onclick="generatList()">Update List</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row w-100 pl-2 pr-0">
            <div class="card col-md-12">
                <div class="card-body w-100 d-none" id="list">
                    <div id="list_title">
                        <h4></h4>
                    </div>
                    <input type="hidden" id="list_id" value="">
                    <table id="product_list" class="table">
                        <tr>
                            <td style="width:373px !important">Product Title</td>
                            <td>Sku</td>
                            <td>Image</td>
                            <td>Price</td>
                            <td>Quantity</td>
                            <td>Subtotal</td>
                            <td>Remove</td>
                        </tr>
                    </table>
                    
                    <div class="row align-items-center border-top">
                        <div class="col-md-3 my-2"><strong>Shipping</strong></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <label for="shipping_price">Shipping Price</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="shipping_price_value" name="shipping_price" onchange="add_shipping(this)" class="form-control" value="0.00" step="any">  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center border-top">
                        <div class="col-md-3 my-2"><strong>Discount</strong></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="discount_type">Discount Type</label>
                                    <div class="form-group">
                                        <select class="form-control" name="discount_type" id="discount_type" onchange="discount_type(this)">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="discount_value">Discount</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="discount_value" name="discount_type_value" onchange="add_discount(this)" class="form-control" value="0" step="any">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="discount_value">Discount Calculated</label>
                                    <div class="form-group">
                                        <input type="number" min="0" id="discount_calculated" readonly name="discount_calculated" readonly class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top">
                        <div class="col-md-10 py-3 ">Grand Total</div>
                        <div class="col-md-2">Amount : $<span id="grand_total">0</span></div>
                    </div>
                    <div class="row border-top">
                        <div class="col-md-10 py-3"><button type="button" class="ms-2 btn btn-primary"
                                onclick="generatList()">Create List</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
    @livewireScripts
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.css">
@stop
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('js')
    <script>
        function checkListId() {
            var list_id = $("#list_id").val();
            if (list_id === '') {
                $(".btn-add-to-cart").prop('disabled', true);
            } else {
                $(".btn-add-to-cart").prop('disabled', false);
            }
        }
        $(document).ready(function() {
            
            $('body').on('click', '.btn-add-to-cart', function() {
                var id = $(this).attr('id');
                var product_id = id.replace('btn_', '');
                var row = $('#product_row_' + product_id).length;

                if (row > 0) {
                    var retail_price = parseFloat($('#retail_price_' + product_id).html());
                    var quantity = parseInt($('#quantity_' + product_id).val());

                    let stockAvailable = parseInt($('#product_buy_list_stock_' + product_id).val());
                    // alert(stockAvailable);
                    if (quantity + 1 > stockAvailable) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Stock Limit Exceeded',
                            icon: 'error',
                            showConfirmButton: true,
                            allowEscapeKey: false
                        });
                    } else {
                        quantity++;
                    }

                    

                    var subtotal = retail_price * quantity;

                    $('#quantity_' + product_id).val(quantity);
                    $('#subtotal_' + product_id).html(parseFloat(subtotal).toFixed(2));

                    recalculateGrandTotal();
                    return false;
                }

                jQuery.ajax({
                    url: "{{ url('admin/add-to-list') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        product_id: product_id,
                    },
                    success: function(response) {
                        $('#product_list').append(response);
                        recalculateGrandTotal();
                    }
                });
            });


        });

        function generatList() {
            var is_update = $('#is_update').val();
            var listItems = [];
            var list_id = $('#list_id').val();
            var grand_total = $('#grand_total').html();
            var shipping_price = $('#shipping_price_value').val() != '' ? $('#shipping_price_value').val() : 0.00;
            var discount_value = $('#discount_value').val() != '' ? $('#discount_value').val() : 0.00;
            var discount_type = $('#discount_type').val();
            var discount_calculated = $('#discount_calculated').val() != '' ? parseFloat($('#discount_calculated').val()) : 0.00;
            console.log(grand_total);
            $('.admin-buy-list').each(function() {
                var product_id = this.id;
                product_id = product_id.replace('product_row_', '');
                var retail_price = $('#retail_price_' + product_id).html();
                var option_id = $('#option_id_' + product_id).val();
                var quantity = $('#quantity_' + product_id).val();
                var subtotal = $('#subtotal_' + product_id).html();
                console.log(subtotal);
                listItems.push({
                    product_id: product_id,
                    option_id: option_id,
                    quantity: quantity,
                    subtotal: subtotal,
                    grand_total: grand_total,
                });
            });
            console.log(listItems);
            jQuery.ajax({
                url: "{{ url('admin/generate-list') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    listItems: listItems,
                    listId: list_id,
                    is_update: is_update,
                    shipping_price: shipping_price,
                    discount_value: discount_value,
                    discount_type: discount_type,
                    discount_calculated: discount_calculated,
                },
                success: function(response) {
                    window.location.href = "{{ route('buy-list.index') }}";
                }
            });
        }

        function deleteProduct(product_id) {
            // var row = $('#product_row_' + product_id).length;
            // if (row < 1) {
            //     $('#grand_total').html(0.00);
            // }
            // var subtotal_to_remove = parseFloat($('#subtotal_' + product_id).html());
            // var grand_total = parseFloat($('#grand_total').html());
            // var updated_total = 0;
            // updated_total = parseFloat(grand_total) - parseFloat(subtotal_to_remove);
            // $('#subtotal_' + product_id).val();
            // $('#product_row_' + product_id).remove();
            // $('#grand_total').html(updated_total.toFixed(2));

            // Remove the row first
            $('#product_row_' + product_id).remove();

            // Recalculate the total using existing logic
            recalculateGrandTotal();
        }


        function createList() {
            var title = $('#title').val();
            var description = $('#description').val();
            var status = $('#status').val();
            jQuery.ajax({
                url: "{{ route('buy-list.store') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    title: title,
                    description: description,
                    status: status
                },
                success: function(response) {
                    $("#list_title").append("<h4>" + title + "</h4>");
                    $("#list_id").val(response.list_id);
                    $("#title_errors").html('');
                    $("#status_errors").html('');
                    $("#description_errors").html('');
                    console.log(response);
                    $("#success_msg").html(response.success);
                    $("#success_msg").removeClass('d-none');
                    $(".btn-add-to-cart").prop('disabled', false);
                    $("#list").removeClass('d-none');

                },
                error: function(response) {
                    console.log(response.responseJSON.errors);
                    if (response.responseJSON.errors.title) {

                        $("#title_errors").html(response.responseJSON.errors.title);
                    } else {
                        $("#title_errors").html('');
                    }

                    if (response.responseJSON.errors.status) {
                        $("#status_errors").html(response.responseJSON.errors.status);
                    } else {
                        $("#status_errors").html('');
                    }

                    if (response.responseJSON.errors.description) {
                        $("#description_errors").html(response.responseJSON.errors.description);
                    } else {
                        $("#description_errors").html('');
                    }
                }
            });
        }

        

        function recalculateGrandTotal() {
            let subtotalSum = 0;
            $('[id^=subtotal_]').each(function () {
                let cleanValue = $(this).text().replace(/[^0-9.-]+/g, '');
                subtotalSum += parseFloat(cleanValue);
            });

            let shipping_price = parseFloat($('#shipping_price_value').val() || 0);
            let discount_value = parseFloat($('#discount_value').val() || 0);
            let discount_type = $('#discount_type').val();

            let grand_total = subtotalSum + shipping_price;

            if (discount_type === 'fixed') {
                grand_total -= discount_value;
                $('#discount_calculated').val(discount_value.toFixed(2));
            } else if (discount_type === 'percentage') {
                let discount_amount = (grand_total * discount_value) / 100;
                grand_total -= discount_amount;
                $('#discount_calculated').val(discount_amount.toFixed(2));
            }

            $('#grand_total').html(grand_total.toFixed(2));
        }



        // function handleQuantity(product_id) {
        //     let retail_price = parseFloat($('#retail_price_' + product_id).html());
        //     let quantity = parseInt($('#quantity_' + product_id).val());
        //     let subtotal = retail_price * quantity;

        //     let stock_available = parseInt($('#product_buy_list_stock_' + product_id).val());
        //     if (quantity > stock_available) {
        //         Swal.fire({
        //             title: 'Error',
        //             text: 'Stock Limit Exceeded',
        //             icon: 'error',
        //             showConfirmButton: true,
        //             allowEscapeKey: false
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 quantity = 1;
        //                 $('#quantity_' + product_id).val(1);
        //                 recalculateTotal(product_id, quantity); // Call your calculation logic here
        //             }
        //         });
        //     }
        //     else {
        //         recalculateTotal(product_id, quantity); // Call your calculation logic here
        //     }


        //     $('#quantity_' + product_id).val(quantity);
        //     $('#subtotal_' + product_id).html(subtotal.toFixed(2));

        //     recalculateGrandTotal();
        // }

        function handleQuantity(product_id) {
            let retail_price = parseFloat($('#retail_price_' + product_id).html());
            let quantity = parseInt($('#quantity_' + product_id).val());
            let stock_available = parseInt($('#product_buy_list_stock_' + product_id).val());

            if (quantity > stock_available) {
                Swal.fire({
                    title: 'Error',
                    text: 'Stock Limit Exceeded',
                    icon: 'error',
                    showConfirmButton: true,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        quantity = 1;
                        $('#quantity_' + product_id).val(quantity);

                        let subtotal = retail_price * quantity;
                        $('#subtotal_' + product_id).html(subtotal.toFixed(2));

                        // recalculateTotal(product_id, quantity);
                        recalculateGrandTotal();
                    }
                });
            } else {
                $('#quantity_' + product_id).val(quantity);

                let subtotal = retail_price * quantity;
                $('#subtotal_' + product_id).html(subtotal.toFixed(2));

                // recalculateTotal(product_id, quantity);
                recalculateGrandTotal();
            }
        }


        function add_shipping(el) {
            console.log('Shipping Price => ' + $(el).val());
            recalculateGrandTotal();
        }

        function add_discount(el) {
            recalculateGrandTotal();
        }

        function discount_type(el) {
            recalculateGrandTotal();
        }

        document.addEventListener('livewire:load', function () {
            checkListId(); // Run on initial load

            Livewire.hook('message.processed', () => {
                checkListId(); // Run after each DOM update
            });
        });

    </script>

@stop
