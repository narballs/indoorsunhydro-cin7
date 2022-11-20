@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @yield('content')
</div>
<div class="row bg-light">

    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row ms-0">
                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <div class="images">
                                @if($productOption->image)
                                <div class="text-center mt-5">
                                    <img id="main-image" src="{{$productOption->image}}" class="img-fluid" />
                                </div>
                                @else
                                <div class="text-center mt-5">
                                    <img id="main-image" src="/theme/img/image_not_available.png"
                                        class="img-fluid w-75 h-75" />
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-xs-12 product-detail-content">
                            <div class="product pr-1 pt-4 product-detail-content1">
                                <div class="d-flex row">
                                    <?php $retail_prices = $productOption->retailPrice;?>
                                    <div class="product-detail-heading col-xl-12 col-lg-12 col-md-12 col-xs-12"
                                        id="product_name">
                                        <h3 class="product-detail-heading">{{$productOption->products->name}}</h3>
                                    </div>

                                    <div class="col-md-12 d-flex">
                                        <span class="text-danger product-detail-price">
                                            ${{number_format($retail_prices,2)}}</span>
                                    </div>
                                </div>


                                <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand"></span>

                                    <div class="price d-flex flex-row align-items-center">
                                        @if ($productOption->stockAvailable > 0)
                                        <span
                                            class="rounded-pill product-detail-quantity d-flex justify-content-center align-items-center"><span
                                                class="">{{$productOption->stockAvailable}}</span></span>
                                        <div>
                                            <small class="dis-price">&nbsp;</small> <span class="instock-label">IN
                                                STOCK</span>
                                        </div>
                                        @else
                                        <div>
                                            <small class="dis-price">&nbsp;</small><span class="text-danger">OUT OF
                                                STOCK</span>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                                <form id="cart">
                                    @csrf
                                    <div class="cart row mt-4 align-items-center">
                                        <div class="col-md-3">
                                            <div class="quantity" style="width:144px">
                                                <input type="number" name="quantity" id="quantity" min="1"
                                                    max="{{$productOption->stockAvailable}}" step="1" value="1">
                                                <input type="hidden" name="p_id" id="p_id"
                                                    value="{{$productOption->products->id}}">
                                                <input type="hidden" name="p_id" id="option_id"
                                                    value="{{$productOption->option_id}}">
                                                <div class="quantity-nav">
                                                    <div class="quantity-div quantity-up"></div>
                                                    <div class="quantity-div quantity-down"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 d-flex justify-content-end" style="">
                                            @if($productOption->stockAvailable > 0)
                                            <button class=" button-cards product-detail-button-cards text-uppercase"
                                                style="" type="button" id="ajaxSubmit"><a class="text-white">Add to
                                                    cart</a></button>
                                            @else
                                            <button
                                                class="button-cards product-detail-button-cards opacity-50 text-uppercase"
                                                type="submit"><a class="text-white">Add to cart</a></button>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-uppercase text-muted brand"></span>

                                </form>

                                <div>
                                    <div class="price">
                                        <div class="row mt-5">
                                            <div class="col-md-8">
                                                <span class="category-title-heading">Category</span> :
                                                @if($pname)
                                                <span class="category-title">{{$pname}}</span>
                                                @endif
                                            </div>

                                            <div class="col-md-4 d-flex justify-content-end pr-0">
                                                <span class="category-title-heading">SKU</span> : <span
                                                    class="category-title">{{$productOption->code}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-detail-content-dec">
                                        <div class="category-title mt-3"><strong>Description</strong></div>
                                        <p class="about product-details-description mt-3">
                                            {{ strip_tags( $productOption->products->description ) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.product-footer')
    @include('partials.footer')
    <script>
        jQuery(document).ready(function(){
            jQuery('#ajaxSubmit').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
              });
               jQuery.ajax({
                  url: "{{ url('add-to-cart') }}",
                  method: 'post',
                  data: {
                    "_token": "{{ csrf_token() }}",
                    p_id: jQuery('#p_id').val(),
                    option_id: jQuery('#option_id').val(),
                    quantity: jQuery('#quantity').val()
                  },
                  success: function(response){
                    //console.log(response);
                    if(response.status == 'success'){
                        var cart_items = response.cart_items;
                        var cart_total = 0;
                        var total_cart_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var code =parseFloat(item.code)
                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseInt(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$'+subtotal);
                           
                        }
                        var product_name = document.getElementById("product_name").innerHTML;
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: jQuery('#quantity').val() + ' X ' + product_name + 'added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                     
                     //alert('llll');
                     
                  }});

               });
              jQuery('<div class="quantity-nav"><div class="quantity-div quantity-up">&#xf106;</div><div class="quantity-div quantity-down">&#xf107</div></div>').insertAfter('.quantity input');
              jQuery('.quantity').each(function () {
                var spinner = jQuery(this),
                    input = spinner.find('input[type="number"]'),
                    btnUp = spinner.find('.quantity-up'),
                    btnDown = spinner.find('.quantity-down'),
                    min = input.attr('min'),
                    max = input.attr('max');

                btnUp.click(function () {
                  var oldValue = parseFloat(input.val());
                  if (oldValue >= max) {
                    var newVal = oldValue;
                  } else {
                    var newVal = oldValue + 1;
                  }
                  spinner.find("input").val(newVal);
                  spinner.find("input").trigger("change");
                });

                btnDown.click(function () {
                  var oldValue = parseFloat(input.val());
                  if (oldValue <= min) {
                    var newVal = oldValue;
                  } else {
                    var newVal = oldValue - 1;
                  }
                  spinner.find("input").val(newVal);
                  spinner.find("input").trigger("change");
                });

              });
            });
    </script>