@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
   <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
      PRODUCTS
   </p>
</div>
<?php //dd($products);?>
<div class="container desktop-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row pl-5 pr-5 pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <input type="hidden" id="category_id" value="{{ $category_id }}">
            <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
            <div class="col">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange('best_selling')">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
                  <option class="form-group" value="price">Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>

               </select>
            </div>
            <div class="col">
               <?php //dd($category_id);?>
               <label>Categories</label>

               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">
                  <option>Select Category</option>
                  @foreach($categories as $category)

                  <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                     $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                  <!-- <option value="{{$category->id}}/{{$category->slug}}" {{ isset($selected_category_id) && $selected_category_id == $selected_category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option> -->
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange('brand')">
                  <option>Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)

                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange('result_per_page')">
                  <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"' : ''
                     }}>10</option>
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="30" {{ $per_page }} {{ isset($per_page) && $per_page==30 ? 'selected="selected"' : ''
                     }}>30</option>
               </select>
            </div>
            <div class="col">
               <label>Show Only</label>

               <div id="stock">
                  <?php if(empty($stock) || $stock == 'in-stock') {
                     $text = 'In stock';
                     $danger = '';
                     $stock = 'in-stock';
                  }
                  else {
                     $text = 'Out of Stock';
                     $danger = 'bg-danger';
                     $stock = 'out-of-stock';
                  }   
                  ?>
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}}" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>


               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      @foreach($products as $key=>$product)
      @foreach($product->options as $option)
      <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
         <div class="card shadow-sm mb-4 w-100">
            @if($option->image != '')
            <a href="{{ url('product-detail/'.$product->id.'/'.$option->option_id) }}"><img src="{{$option['image']}}"
                  class="col-md-10 offset-1" /></a>
            @else
            <img src="{{ asset('theme/img/image_not_available.png') }}" class="w-100 img-fluid h-75 w-75"
               onclick="showdetails({{$product->id}})" />
            @endif
            <div class="card-body d-flex flex-column text-center">
               <input type="hidden" name="quantity" value="1" id="quantity">
               <input type="hidden" name="p_id" id="p_{{$product->id}}" value="{{$product->id}}">
               <h5 class="card-title" style="font-weight: 500;
                        font-size: 16px;"><a
                     href="{{ url('product-detail/'.$product->id.'/'.$option->option_id.'/'.$product->slug) }}"
                     id=product_name_{{$product->id}}>{{$product->name}}</a></h5>
               <div class="mt-auto">
                  <p class="text-uppercase mb-0 text-center text-danger">${{$product->retail_price}}</p>
                  <button class="button-cards col w-100" style="max-height: 46px;"
                     onclick="updateCart({{$product->id}},{{$option->option_id}})">Add to cart</button>
               </div>
            </div>
         </div>
      </div>
      @endforeach
      @endforeach
   </div>
</div>
<div class="container mobile-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row  pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <input type="hidden" id="category_id" value="{{ $category_id }}">
            <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
            <div class="col-md-12">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange('best_selling')">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
                  <option class="form-group" value="price">Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>

               </select>
            </div>
            <div class="col-md-12">
               <?php //dd($category_id);?>
               <label>Categories</label>

               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">
                  <option>Select Category</option>
                  @foreach($categories as $category)

                  <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                     $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                  <!-- <option value="{{$category->id}}/{{$category->slug}}" {{ isset($selected_category_id) && $selected_category_id == $selected_category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option> -->
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col-md-12">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange('brand')">
                  <option>Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)

                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col-md-12">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange('result_per_page')">
                  <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"' : ''
                     }}>10</option>
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="30" {{ $per_page }} {{ isset($per_page) && $per_page==30 ? 'selected="selected"' : ''
                     }}>30</option>
               </select>
            </div>
            <div class="col-md-12 d-flex mt-3">
               <label>Show Only</label>

               <div id="stock" style="padding-left: 139px;">
                  <?php if(empty($stock) || $stock == 'in-stock') {
                     $text = 'In stock';
                     $danger = '';
                     $stock = 'in-stock';
                  }
                  else {
                     $text = 'Out of Stock';
                     $danger = 'bg-danger';
                     $stock = 'out-of-stock';
                  }   
                  ?>
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}}" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      @foreach($products as $key=>$product)
      @foreach($product->options as $option)
      <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
         <div class="card shadow-sm mb-4 w-100">
            @if($option->image != '')
            <a href="{{ url('product-detail/'.$product->id.'/'.$option->option_id) }}"><img src="{{$option['image']}}"
                  class="col-md-10 offset-1" /></a>
            @else
            <img src="{{ asset('theme/img/image_not_available.png') }}" class="w-100 img-fluid h-75 w-75"
               onclick="showdetails({{$product->id}})" />
            @endif
            <div class="card-body d-flex flex-column text-center">
               <input type="hidden" name="quantity" value="1" id="quantity">
               <input type="hidden" name="p_id" id="p_{{$product->id}}" value="{{$product->id}}">
               <h5 class="card-title" style="font-weight: 500;
                        font-size: 16px;"><a
                     href="{{ url('product-detail/'.$product->id.'/'.$option->option_id.'/'.$product->slug) }}"
                     id=product_name_{{$product->id}}>{{$product->name}}</a></h5>
               <div class="mt-auto">
                  <p class="text-uppercase mb-0 text-center text-danger">${{$product->retail_price}}</p>
                  <button class="button-cards col w-100" style="max-height: 46px;"
                     onclick="updateCart({{$product->id}},{{$option->option_id}})">Add to cart</button>
               </div>
            </div>
         </div>
      </div>
      @endforeach
      @endforeach
   </div>
</div>

<!-- Remove the container if you want to extend the Footer to full width. -->
<script>
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
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
               }});

             return false;
         }
            function categoryChange() {
            var categories = jQuery('#categories').val();
            //alert(category_id);
            window.location.href =  window.location.origin+'/products/'+category_id;

         } 

         function inStockOutstock() {
            var value = jQuery('#in-stock').val();
            if (value == 'in-stock') {
               jQuery('#in-stock').addClass('bg-danger');
               jQuery('#in-stock').addClass('out-of-stock');
               $("#in-stock").html("Out of Stock");
               $("#in-stock").prop("value", "out-of-stock");

            }
            else {
               jQuery('#in-stock').removeClass('bg-danger');
               jQuery('#in-stock').removeClass('out-of-stock');
               jQuery('#in-stock').addClass('in-stock');
               $("#in-stock").prop("value", "in-stock");
               $("#in-stock").html("In Stock");
               
            }

             
           
         }

         function handleSelectChange(searchedOption = '') {
            var category_id = jQuery('#categories').val();
            var selected_cat_id = jQuery('#selected_cat').val();
            var price = jQuery('#search_price').val();
            var brand = jQuery('#brand').val();
            var per_page = jQuery('#per_page').val();
            var stock = jQuery('#in-stock').val();
            var search_price = jQuery('#search_price').val();
            var category_id = jQuery('#category_id').val();
            var selected_category_id = jQuery('#categories').val();
            var parent_category_slug = jQuery('#parent_category_slug').val();
            //var chosen = true;

            if (searchedOption == 'category') {
               var brand = '';
            }


            if (selected_cat_id != ''){ 
               //alert(selected_cat_id);
               var slug = selected_cat_id;
              var basic_url = '/products/'+selected_cat_id + '/?';
               //window.location.href = basic_url;
               //var basic_url = `/products/${selected_cat_id}/${slug}`;
             
            }
            else {
            
               var slug = `${category_id}/${parent_category_slug}`
            }
               
                
           

            // alert(selected_category_id);
           
            // alert(selected_category_id);

            // if (categories !='') {

            // }

            if (brand != '') {
               basic_url = `?brand_id=${brand}`;
            }
            if (per_page != '') {
               basic_url = basic_url+`&per_page=${per_page}`;
            }
            if (search_price != '') {
               basic_url = basic_url+`&search_price=${search_price}`;
            }
            if (selected_category_id != '') {
               basic_url = basic_url+`&selected_category_id=${selected_cat_id}`;
            }
            if (stock != '') {
               basic_url = basic_url+`&stock=${stock}`;
            }

          // alert(basic_url);
          
          
            window.location.href = basic_url;

         //}
      }

</script>

@include('partials.product-footer')

<!-- End of .container -->
@include('partials.footer')