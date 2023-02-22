<div class="container desktop-view">
      <form id="form-filter">
         <div class="col-md-12">
            <div class="row pl-5 pr-5 pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
               <div class="col">
                  <label>Sort by</label>
                  <select class="form-select" id="search_price" onchange="handleSelectChange()">
                     <option value="0">Select Option</option>
                     <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                        $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                     <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                        && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                     <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                        && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                     <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                        $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Brand A to Z</option>
                     <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                        $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Brand Z to A</option>
                  </select>
               </div>
               <div class="col">
                  <?php //dd($category_id);?>
                  <label>Categories</label>
                  <select class="form-select" id="selected_cat" name="selected_cat"
                     onchange="handleSelectChange('category')">
                     <option value="0">Select Category</option>
                     @foreach($categories as $category)
                     <option value="{{$category->id}}" {{ isset($category_id) && $category_id==$category->id ?
                        'selected="selected"' : '' }}>{{ $category->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="col">
                  <label>Brand</label>
                  <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                     <option value="0">Select Brand</option>
                     @foreach($brands as $_brand_id => $brand_name)
                     <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                        : '' }}>{{ $brand_name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="col">
                  <label>Result per page</label>
                  <select id="per_page" class="form-select" onchange="handleSelectChange()">
                     <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                        }}>20</option>
                     <option value="40" {{ $per_page }} {{ isset($per_page) && $per_page==40 ? 'selected="selected"' : ''
                        }}>40</option>
                     <option value="60" {{ $per_page }} {{ isset($per_page) && $per_page==60 ? 'selected="selected"' : ''
                        }}>60</option>
                  </select>
               </div>
               <!-- <div class="col">
                  <label>Show Only</label>
                  <div id="stock"> -->
                     <?php if(empty($stock) || $stock == 'in-stock') {
                        $text = 'In stock';
                         $danger = '';
                     }
                     else {
                         $text = 'Out of Stock';
                        $danger = 'bg-danger';
                     }   
                     ?>


               <div class="col">
                  <label>Inventory</label>
                  <select class="form-select" name="inventory" id="inventory" onchange="handleSelectChange()">
                     <option value="">Select Inventory</option>
                     <option value="in-stock">In stock</option>
                     <option value="out-of-stock"  {{ isset($stock) && $stock == 'out-of-stock' ? 'selected="selected"' : ''
                        }}>Out of Stock</option>
                     <option value="all-items"  {{ isset($stock) && $stock == 'all-items' ? 'selected="selected"' : ''
                        }} >All Items</option>
                  </select>
               </div>
               <input type="hidden" id="all-items" value="" />
      </form>
      </div>
   </div>
   <div class="row">
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      <?php $count ++; ?>
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <div class="row">
      <div class="container">
         <div class="col-md-6 m-auto">
            {{$products->appends(Request::all())->links()}}
         </div>
      </div>
   </div>
</div>
</div>
