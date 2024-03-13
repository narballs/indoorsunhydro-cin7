@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{-- <div class="mb-4 mt-2">
    <p style="line-height: 95px;"
        class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle pbtn_mbl">
        PRODUCTS
    </p>
</div> --}}
<div class="w-100 mx-0 row justify-content-center align-items-center" style="background-color: #008BD3;height:70px;">
    <p class="fw-bold fs-2 my-auto border-0 text-white text-center align-middle">
       Buy Again
    </p>
</div>

<div class="container desktop-view">
    @if (session('error'))
        <div class="alert alert-danger mt-2">
            {{ session('error') }}
        </div>
    @endif
   
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
    </div>
    <div class="row">
        <div class="container">
            <div class="col-md-6 m-auto">
                {{ $products->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- moible view --}}
<div class="container mobile-view">
    
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
        <div class="w-100 justify-content-center p-2 mt-3">
            {{ $products->appends(Request::all())->onEachSide(1)->links('pagination.front_custom_pagination') }}
        </div>
    </div>
    
    {{-- <div class="row mobile-view w-100"> --}}
        
    {{-- </div> --}}
</div>
{{-- mobile view end --}}

{{-- ipad view start --}}
<div class="container ipad-view">
    
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
    </div>
    <div class="row ipad-view">
        <div class="container">
            <div class="col-sm-6 m-auto">
                {{ $products->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
</div>
{{-- ipid view end --}}


<script>
    function showdetails(id, option_id, slug) {
        window.location.href = '/product-detail/' + id + '/' + option_id + '/' + slug;
    }
    function updateCart(id, option_id) {
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: 1
            },
            success: function(response) {
                if (response.status == 'error') {
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
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
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
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: jQuery('#quantity').val() + ' X ' + product_name +
                            ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });

        return false;
    }
</script>
<script>
    jQuery('#brand').select2({
        width: '100%',
        placeholder: "Select an Option",
        allowClear: true
    });
    
</script>
@include('partials.product-footer')

@include('partials.footer')

<script>
    $(document).ready(function() {
        $('.pagination').addClass('pagination-sm');
    });
</script>


    
    
    
