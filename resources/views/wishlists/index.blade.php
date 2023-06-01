<?php $i = 1; //dd($lists); ?>
@foreach ($lists as $list)
    <div class="my-account-content-heading text-center mb-4">{{ $list->title }}</div>
    {{-- <div class="wrapper2">
            <?php
            $images = [];
            foreach ($list->list_products as $list) {
                foreach ($list->product->options as $image) {
                    array_push($images, $image->image);
                }
            }
            ?>
            @if (!empty($images[0]))
                <img class="img2" src="{{$images[0]}}"  style="border-radius: 15px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
            @else
                <img class="img2" src="theme/img/greenback.png"  style="border-radius: 15px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
            @endif
            @if (!empty($images[1]))
                <img class="img2" src="{{$images[1]}}" style="border-radius: 0px 15px 0px 0px;max-height:193px !important ; min-height:193px !important ;position: relative ;"/>
            @else
                <img class="img2" src="theme/img/greenback.png" style="border-radius: 0px 15px 0px 0px;max-height:193px !important ; min-height:193px !important ;"/>
            @endif
            @if (!empty($images[2]))
                <img class="img2 box-height" src="{{$images[2]}}" style="border-radius: 0px 0px 0px 0px"/>
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 0px 0px"/>
            @endif
            @if (!empty($images[3]))
                <img class="img2 box-height" src="{{$images[3]}}" />
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" />
            @endif
            @if (!empty($images[4]))
                <img class="img2 box-height" src="{{$images[4]}}" />
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" />
            @endif
            @if (!empty($images[5]))
                <img class="img2 box-height" src="{{$images[5]}}" style="border-radius: 0px 0px 15px 0px"/>
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 0px 0px"/>
            @endif
        </div> --}}
@endforeach
@foreach ($lists as $list)
    @foreach ($list->list_products as $product)
        @foreach ($product->product->options as $option)
            <div>
                @foreach ($option->price as $price)
                    <table class="table">
                        <tbody>
                            <input type="hidden" value="{{ $product->id }}" id="prd_{{ $product->id }}">
                            <input type="hidden" value="{{ $product->product->name  }}" id="prd_name_{{ $product->id }}">
                            <tr style="border-bottom :1px solid lightgray;" id="p_{{ $product->id }}">
                                <td style="border:none;vertical-align: middle;">
                                    {{ $i++ }}
                                </td>
                                <td style="width:400px; border:none;vertical-align: middle;">
                                    <a
                                        href="{{ url('product-detail/' . $product->id . '/' . $product->option_id . '/' . $product->product->slug) }}">{{ $product->product->name }}
                                    </a>
                                </td>
                                <td style="border:none;">
                                    @if ($product->product->images)
                                        <img src="{{ $option->image }}" class="" width="50px" height="50px">
                                    @else
                                        <img src="/theme/image_not_available.png" class="" width="50px"
                                            height="50px">
                                    @endif
                                </td>
                                <td style="border:none; vertical-align: middle;">
                                    ${{ $product->sub_total }}
                                </td>

                                <td style="border:none; vertical-align: middle;">
                                    <button type="submit" style="border:none; background:none;" onclick="add_favorite_to_cart('{{$product->id}}', '{{$product->option_id}}')">
                                        <i class="fa fa-cart-plus"></i>
                                    </button>
                                </td>

                                <td style="border:none; vertical-align: middle;">
                                    <button type="button" style="background: none; border:none;"
                                        onclick="remove_from_favorite('{{ $product->id }}')"
                                        data-option="{{ $product->option_id }}" data-contact="{{ $list->contact_id }}"
                                        data-user="{{ $list->user_id }}" data-list="{{ $product->list_id }}"
                                        data-title="{{ $list->title }}">
                                        <i class="fa fa-times-circle mt-1" type="button"
                                            style="color: red;font-size: 18px;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        @endforeach
    @endforeach
@endforeach
@endforeach
<script>
    function remove_from_favorite(id) {
        var product_buy_list_id = id;
        var option_id = $(this).attr('data-option');
        var contact_id = $(this).attr('data-contact');
        var user_id = $(this).attr('data-user');
        var list_id = $(this).attr('data-list');
        var title = $(this).attr('data-title');
        $.ajax({
            url: "{{ url('/delete/favorite/product') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_buy_list_id,
                option_id,
                contact_id,
                user_id,
                list_id,
                title
            },
            success: function(response) {
                if (response.status == 'success') {
                    wishLists();
                    Swal.fire('Success!', 'Product removed Successfully!');
                }
            }
        });
    }

    function add_favorite_to_cart(id, option_id) {
        jQuery.ajax({
        url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                quantity: 1
            },
        success: function(response) {
                if (response.status == 'success') {
                var cart_items = response.cart_items;
                var cart_total = 0;
                var total_cart_quantity = 0;

                for (var key in cart_items) {
                    var item = cart_items[key];

                    var product_id = item.prd_id;
                    var price = parseFloat(item.price);
                    var quantity = parseFloat(item.quantity);

                    var subtotal = parseFloat(price * quantity);
                    var cart_total = cart_total + subtotal;
                    var total_cart_quantity = total_cart_quantity + quantity;
                    $('#subtotal_' + product_id).html('$'+subtotal);
                    var product_name = document.getElementById('prd_name_'+ id).value;
                }
                
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: quantity + ' X ' + document.getElementById('prd_name_'+ id).value + ' added to your cart',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top',
                    timerProgressBar: true
                });
            }
            $('#top_cart_quantity').html(total_cart_quantity);
            
            $('#cart_items_quantity').html(total_cart_quantity);
            $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
            var total = document.getElementById('#top_cart_quantity');
        }});
    }
</script>
