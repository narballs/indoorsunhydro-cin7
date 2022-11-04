@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        SEARCHED PRODUCTS
    </p>
</div>
<?php $count = 0; ?>
<div class="container">
    <div class="row">
        @foreach ($products as $key => $product)
        @foreach($product->options as $option)
        <?php $count ++; ?>
        @include('product_row')
        @endforeach
        @endforeach
    </div>
</div>
<script>
    function showdetails(id) {
                window.location.href = '/product-detail/'+ id;

            }
            // function updateCart(id) {
            //     jQuery.ajax({
            //    url: "{{ url('/add-to-cart/') }}",
            //    method: 'post',
            //    data: {
            //      "_token": "{{ csrf_token() }}",
            //       p_id: jQuery('#p_'+id).val(),
            //       quantity: 1
            //    },
            //    success: function(result){
            //             console.log(result);
            //             jQuery('.alert').html(result.success);
            //             //window.location.reload();
            //    }});

            //     return false;
            // }
       
            function updateCart(id, option_id) {
                jQuery.ajax({
               url: "{{ url('/add-to-cart/') }}",
               method: 'post',
               data: {
                 "_token": "{{ csrf_token() }}",
                  p_id: jQuery('#p_'+id).val(),
                  quantity: 1,
                  option_id: option_id
               },
               success: function(response){
                    if(response.status == 'success'){
                        var cart_items = response.cart_items;
                        var cart_total = 0;
                        var total_cart_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseInt(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$'+subtotal);
                            console.log(item.name);
                            var product_name = document.getElementById("product_name_"+jQuery('#p_'+id).val()).innerHTML;
                        }
                        
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: jQuery('#quantity').val() + ' X ' + product_name + ' added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
               }});

                return false;
            }
         jQuery(document).ready(function(){
            jQuery('.ajaxSubmit11').click(function(e){ alert('xxxxxxxxxx')
               e.preventDefault();
                
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });

               jQuery.ajax({
                  url: "{{ url('/add-to-cart/') }}",
                  method: 'post',
                  data: {
                    "_token": "{{ csrf_token() }}",
                     p_id: jQuery('#p_id').val(),
                     quantity: jQuery('#quantity').val(),
                  },
                  success: function(result){
                     console.log(result);
                      jQuery('.alert').html(result.success);
                        // window.location.reload();
                  }});
               });
            });
</script>
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')