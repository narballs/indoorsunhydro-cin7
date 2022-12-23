<form id="form-filter desktop-view">
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
               <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria) &&
                  $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
               <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria) &&
                  $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
               <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                  $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
               <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                  $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
            </select>
         </div>
         <div class="col">
            <?php //dd($category_id);?>
            <label>Categories</label>
            <select class="form-select" id="selected_cat" name="selected_cat" onchange="handleSelectChange('category')">

               @foreach($categories as $category)

               <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) && $category_id==$category->
                  id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
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
               <option value="0">Select Brand</option>
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
               <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"' : '' }}>
                  10</option>
               <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : '' }}>
                  20</option>
               <option value="30" {{ $per_page }} {{ isset($per_page) && $per_page==30 ? 'selected="selected"' : '' }}>
                  30</option>
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
   {{-- mobile-view start --}}
   <form id="form-filter mobile-view">
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
               </select>
            </div>
            <div class="col-md-12">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">

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
                  <option value="0">Select Brand</option>
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

               <div id="stock" style="padding-left: 139px ">
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
   {{-- mobile-view end --}}
   {{-- ipad-view start --}}
   <form id="form-filter ipad-view">
      <div class="col-md-12">
         <div class="row  pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <input type="hidden" id="category_id" value="{{ $category_id }}">
            <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
            <div class="col-md-6">
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
               </select>
            </div>
            <div class="col-md-6">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">

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

            <div class="col-md-6">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange('brand')">
                  <option value="0">Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)
                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col-md-6">
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
            <div class="col-md-6 d-flex mt-3">
               <label>Show Only</label>

               <div id="stock" style="padding-left: 139px ">
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
   {{-- ipad-view end --}}