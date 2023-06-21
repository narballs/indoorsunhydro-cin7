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
       PRODUCTS
    </p>
</div>
<?php //dd($category_id);
?>
<div class="container desktop-view">
    <form id="form-filter">
        <div class="col-md-12">
            <div class="row pl-5 pr-5 pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
                <div class="col">
                    <label>Sort by</label>
                    <select class="form-select" id="search_price" onchange="handleSelectChange()">
                        <option value="0">Select Option</option>
                        <option class="form-group" value="best-selling" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'best-selling' ? 'selected="selected"' : '' }}>
                            Best Selling</option>
                        <option class="form-group" value="price-low-to-high" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'price-low-to-high' ? 'selected="selected"' : '' }}>
                            Price Low to High</option>
                        <option class="form-group" value="price-high-to-low" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'price-high-to-low' ? 'selected="selected"' : '' }}>
                            Price High to Low</option>
                        <option class="form-group" value="brand-a-to-z" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'brand-a-to-z' ? 'selected="selected"' : '' }}>
                            Brand A to Z</option>
                        <option class="form-group" value="brand-z-to-a" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'brand-z-to-a' ? 'selected="selected"' : '' }}>
                            Brand Z to A</option>
                    </select>
                </div>
                <div class="col">
                    <?php //dd($brand_id);
                    ?>
                    <label>Categories</label>
                    <select class="form-select" id="selected_cat" name="selected_cat"
                        onchange="handleSelectChange('category')">
                        <option value="0">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($category_id) && $category_id == $category->id ? 'selected="selected"' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col">
                    <label>Sub Category</label>
                    <select class="form-select" id="childeren" name="childeren[]"
                        onchange="handleSelectChange('childeren')">
                        <option value="">Sub Category</option>
                        @foreach ($childerens as $key => $childeren)
                            <option value="{{ $childeren->id }}"
                                {{ isset($childeren_id) && $childeren_id == $childeren->id ? 'selected="selected"' : '' }}>
                                {{ $childeren->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Brand</label>
                    <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                        <option value="0">Select Brand</option>
                        @foreach ($brands as $_brand_id => $brand_name)
                            <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id == $_brand_id ? 'selected="selected"' : '' }}>{{ $brand_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Result per page</label>
                    <select id="per_page" class="form-select" onchange="handleSelectChange()">
                        <option value="20" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 20 ? 'selected="selected"' : '' }}>20</option>
                        <option value="40" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 40 ? 'selected="selected"' : '' }}>40</option>
                        <option value="60" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 60 ? 'selected="selected"' : '' }}>60</option>
                    </select>
                </div>
                <div class="col">
                    <label>Inventory</label>
                    <select class="form-select" name="inventory" id="inventory" onchange="handleSelectChange()">
                        <option value="in-stock">In stock</option>
                        <option value="out-of-stock"
                            {{ isset($stock) && $stock == 'out-of-stock' ? 'selected="selected"' : '' }}>Out of Stock
                        </option>
                        <option value="all-items"
                            {{ isset($stock) && $stock == 'all-items' ? 'selected="selected"' : '' }}>All Items
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </form>
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
    <div class="row mt-3">
        <p class="d-flex justify-content-start align-items-center mb-0">
            <button class="filler-and-sort btn  filler-and-sort p-0 filterMblbtn" type="button" data-bs-toggle="modal"
                data-bs-target="#filter_model" aria-expanded="false" aria-controls="" style="border: none !important;">
                {{-- <i class="fa fa-sliders filterIco_mbl"></i> --}}
                <img src="/theme/img/icons/filter_mobile_icon.png" alt="">
                <span class="search_filter_text">Search Filter </span>
                {{-- <img src="/theme/img/filler-icon.png" alt=""></span> --}}
            </button>
        </p>
    </div>
    <div class="collapse mt-3" id="collapseExample">
        <div class="card card-body p-0">
            <form id="form-filter">
                <div class="col-md-12">
                    <div class="row pb-4 pt-3 border-0" style="border: 1px solid rgba(0,0,0,.125);padding-bottom: 10px">
                        <div class="col-md-12">
                            <label class="filter_heading_mbl">Sort by</label>
                            <select class="form-select form-select-mbl" id="search_price"
                                onchange="handleSelectChange()">
                                <option value="0">Select Option</option>
                                <option class="form-group" value="best-selling" {{ $price_creteria }}
                                    {{ isset($price_creteria) && $price_creteria == 'best-selling' ? 'selected="selected"' : '' }}>
                                    Best Selling</option>
                                <option class="form-group" value="price-low-to-high" {{ $price_creteria }}
                                    {{ isset($price_creteria) && $price_creteria == 'price-low-to-high' ? 'selected="selected"' : '' }}>
                                    Price Low to High</option>
                                <option class="form-group" value="price-high-to-low" {{ $price_creteria }}
                                    {{ isset($price_creteria) && $price_creteria == 'price-high-to-low' ? 'selected="selected"' : '' }}>
                                    Price High to Low</option>
                                <option class="form-group" value="brand-a-to-z" {{ $price_creteria }}
                                    {{ isset($price_creteria) && $price_creteria == 'brand-a-to-z' ? 'selected="selected"' : '' }}>
                                    Brand A to Z</option>
                                <option class="form-group" value="brand-z-to-a" {{ $price_creteria }}
                                    {{ isset($price_creteria) && $price_creteria == 'brand-z-to-a' ? 'selected="selected"' : '' }}>
                                    Brand Z to A</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <?php //dd($category_id);
                            ?>
                            <label>Categories</label>
                            <select class="form-select form-select-mbl" id="selected_cat" name="selected_cat"
                                onchange="handleSelectChange('category')">
                                <option value="0">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ isset($category_id) && $category_id == $category->id ? 'selected="selected"' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-12">
                            <label>Brand</label>
                            <select class="form-select form-select-mbl" id="brand" name="brands[]"
                                onchange="handleSelectChange()">
                                <option value="0">Select Brand</option>
                                @foreach ($brands as $_brand_id => $brand_name)
                                    <option value="{{ $_brand_id }}"
                                        {{ isset($brand_id) && $brand_id == $_brand_id ? 'selected="selected"' : '' }}>
                                        {{ $brand_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Result per page</label>
                            <select id="per_page" class="form-select form-select-mbl"
                                onchange="handleSelectChange()">
                                <option value="20" {{ $per_page }}
                                    {{ isset($per_page) && $per_page == 20 ? 'selected="selected"' : '' }}>
                                    20</option>
                                <option value="40" {{ $per_page }}
                                    {{ isset($per_page) && $per_page == 40 ? 'selected="selected"' : '' }}>
                                    40</option>
                                <option value="60" {{ $per_page }}
                                    {{ isset($per_page) && $per_page == 60 ? 'selected="selected"' : '' }}>
                                    60</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2 d-flex">
                            <label>Show Only</label>
                            <span id="stock" style="padding-left: 145px !important;">
                                <?php if (empty($stock) || $stock == 'in-stock') {
                                    $text = 'In stock';
                                    $danger = '';
                                    $stock = 'in-stock';
                                } else {
                                    $text = 'Out of Stock';
                                    $danger = 'bg-danger';
                                    $stock = 'out-of-stock';
                                }
                                ?>
                                <button
                                    class="justity-content-end aling-items-end {{ $stock ? $stock : 'in-stock' }} {{ $danger }} in-stock"
                                    type="button" id="in-stock"
                                    onclick="inStockOutstock('instock'), handleSelectChange()"
                                    value="{{ $stock }}">{{ $text }}</button>
                                <!--  <button class="button-cards bg-danger d-none" type="button" id="out-stock" onclick="inStockOutstock('outstock')" style="width:133px !important; height:34px !important;" value="outstock">Out of Stock</button> -->

                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
    <form id="form-filter">
        <div class="col-md-12">
            <div class="row pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);padding-bottom: 10px">
                <div class="col-md-6">
                    <label>Sort by</label>
                    <select class="form-select" id="search_price" onchange="handleSelectChange()">
                        <option value="0">Select Option</option>
                        <option class="form-group" value="best-selling" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'best-selling' ? 'selected="selected"' : '' }}>
                            Best Selling</option>
                        <option class="form-group" value="price-low-to-high" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'price-low-to-high' ? 'selected="selected"' : '' }}>
                            Price Low to High</option>
                        <option class="form-group" value="price-high-to-low" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'price-high-to-low' ? 'selected="selected"' : '' }}>
                            Price High to Low</option>
                        <option class="form-group" value="brand-a-to-z" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'brand-a-to-z' ? 'selected="selected"' : '' }}>
                            Brand A to Z</option>
                        <option class="form-group" value="brand-z-to-a" {{ $price_creteria }}
                            {{ isset($price_creteria) && $price_creteria == 'brand-z-to-a' ? 'selected="selected"' : '' }}>
                            Brand Z to A</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <?php //dd($category_id);
                    ?>
                    <label>Categories</label>
                    <select class="form-select" id="selected_cat" name="selected_cat"
                        onchange="handleSelectChange('category')">
                        <option value="0">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($category_id) && $category_id == $category->id ? 'selected="selected"' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Brand</label>
                    <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                        <option value="0">Select Brand</option>
                        @foreach ($brands as $_brand_id => $brand_name)
                            <option value="{{ $_brand_id }}"
                                {{ isset($brand_id) && $brand_id == $_brand_id ? 'selected="selected"' : '' }}>
                                {{ $brand_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Result per page</label>
                    <select id="per_page" class="form-select" onchange="handleSelectChange()">
                        <option value="20" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 20 ? 'selected="selected"' : '' }}>20</option>
                        <option value="40" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 40 ? 'selected="selected"' : '' }}>40</option>
                        <option value="60" {{ $per_page }}
                            {{ isset($per_page) && $per_page == 60 ? 'selected="selected"' : '' }}>60</option>
                    </select>
                </div>
                <div class="col-md-12 mt-2 d-flex">
                    <label>Show Only</label>
                    <span id="stock" style="padding-left: 488px  !important;">
                        <?php if (empty($stock) || $stock == 'in-stock') {
                            $text = 'In stock';
                            $danger = '';
                            $stock = 'in-stock';
                        } else {
                            $text = 'Out of Stock';
                            $danger = 'bg-danger';
                            $stock = 'out-of-stock';
                        }
                        ?>
                        <button
                            class="justity-content-end aling-items-end {{ $stock ? $stock : 'in-stock' }} {{ $danger }} in-stock"
                            type="button" id="in-stock" onclick="inStockOutstock('instock'), handleSelectChange()"
                            value="{{ $stock }}">{{ $text }}</button>
                        <!--  <button class="button-cards bg-danger d-none" type="button" id="out-stock" onclick="inStockOutstock('outstock')" style="width:133px !important; height:34px !important;" value="outstock">Out of Stock</button> -->

                    </span>
                </div>
            </div>
        </div>
    </form>
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


{{-- pop up filter mobile --}}

<div class="modal fade" id="filter_model" tabindex="-1" aria-labelledby="filter_content" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  mobile_filter_header">
                <h5 class="modal-title mobile_filter_title" id="filter_content">Search Filter</h5>
                <button type="button" class="bg-none mbl_filter_btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times mbl_filter_close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-body p-0 border-0">
                    <form id="form-filter-pop">
                        <div class="row pb-4 pt-2 border-0">
                            <div class="col-md-12">
                                <label class="filter_heading_mbl">Sort by</label>
                                <select class="form-select form-select-mbl" id="search_price_mbl">
                                    <option class="filter_drop_down_mbl " value="0">Select Option</option>
                                    <option class="filter_drop_down_mbl form-group" value="best-selling">Best Selling</option>
                                    <option class="filter_drop_down_mbl form-group" value="price-low-to-high">Price Low to High</option>
                                    <option class="filter_drop_down_mbl form-group" value="price-high-to-low">Price High to Low</option>
                                    <option class="filter_drop_down_mbl form-group" value="brand-a-to-z">Brand A to Z</option>
                                    <option class="filter_drop_down_mbl form-group" value="brand-z-to-a">Brand Z to A</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <?php //dd($category_id);
                                ?>
                                <label class="filter_heading_mbl">Categories</label>
                                <select class="form-select form-select-mbl" onchange="get_child_categories()" id="selected_cat_mbl"
                                    name="selected_cat">
                                    <option class="filter_drop_down_mbl" value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option class="filter_drop_down_mbl" value="{{ $category->id }}" {{ isset($category_id) && $category_id == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <?php
                                    $child_cat = App\Models\Category::where('parent_id', $category_id)->get();
                                ?>
                                <label class="filter_heading_mbl">Sub Category</label>
                                <select class="form-select form-select-mbl" id="childeren_mbl" name="childeren_category">
                                    @if(isset($child_cat) && !empty($child_cat))
                                        @foreach ($child_cat as $child)
                                            <option class="filter_drop_down_mbl" value="{{ $child->id }}">{{ $child->name }}</option>
                                        @endforeach
                                        @else
                                        <option class="filter_drop_down_mbl" value=""></option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="filter_heading_mbl">Brand</label>
                                <select class="form-select form-select-mbl" id="brand_mbl" name="brands[]">
                                    <option class="filter_drop_down_mbl" value="0">Select Brand</option>
                                    @foreach ($brands as $_brand_id => $brand_name)
                                        <option class="filter_drop_down_mbl" value="{{ $_brand_id }}">{{ $brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="filter_heading_mbl">Result per page</label>
                                <select id="per_page_mbl" class="form-select form-select-mbl">
                                    <option class="filter_drop_down_mbl" value="20">20</option>
                                    <option class="filter_drop_down_mbl" value="40">40</option>
                                    <option class="filter_drop_down_mbl" value="60">60</option>
                                </select>
                            </div>

                            <div class="col-md-12 mt-2">
                                <?php if (empty($stock) || $stock == 'in-stock') {
                                    $text = 'In stock';
                                    $danger = '';
                                    $stock = 'in-stock';
                                } else {
                                    $text = 'Out of Stock';
                                    $danger = 'bg-danger';
                                    $stock = 'out-of-stock';
                                }
                                ?>
                                <label class="filter_heading_mbl">Show Only</label>
                                <select id="in_stk" class="form-select form-select-mbl">
                                    <option class="filter_drop_down_mbl" value="0">Show Only</option>
                                    <option class="filter_drop_down_mbl" value="in-stock" {{ isset($stock) && $stock == 'in-stock' ? 'selected' : '' }}>In Stock</option>
                                    <option class="filter_drop_down_mbl" value="out-of-stock" {{ isset($stock) && $stock == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button type="button" onclick="handleSelectChangeMbl()" class="btn btn-success filter-done-btn">
                                <span class="filter-done-btn-text">Done</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- pop up filter mobile end --}}


<script>
    function showdetails(id, option_id, slug) {
        window.location.href = '/product-detail/' + id + '/' + option_id + '/' + slug;
    }

    function inStockOutstock() {
        var value = jQuery('#in-stock').val();
        if (value == 'in-stock') {
            jQuery('#in-stock').addClass('bg-danger');
            jQuery('#in-stock').addClass('out-of-stock');
            $("#in-stock").html("Out of Stock");
            $("#in-stock").prop("value", "out-of-stock");

        } else {
            jQuery('#in-stock').removeClass('bg-danger');
            jQuery('#in-stock').removeClass('out-of-stock');
            jQuery('#in-stock').addClass('in-stock');
            $("#in-stock").prop("value", "in-stock");
            $("#in-stock").html("In Stock");

        }
    }

    function handleSelectChange(searchedOption = '') {
        var selected_category = jQuery('#selected_cat').val();
        var brand = jQuery('#brand').val();
        var per_page = jQuery('#per_page').val();
        var stock = jQuery('#in-stock').val();
        var all_items = jQuery('#all-items').val();
        var search_price = jQuery('#search_price').val();
        var category_id = jQuery('#category_id').val();
        var childeren = jQuery('#childeren').val();
        var inventory = jQuery('#inventory').val();

        if (searchedOption == 'category') {
            var brand = '';
            childeren = '';
        }

        if (selected_category != '') {
            basic_url = `?selected_category=${selected_category}`;
        }

        if (childeren != '') {
            basic_url = basic_url + `&childeren_id=${childeren}`;
        }
        if (brand != '') {
            basic_url = basic_url + `&brand_id=${brand}`;
        }

        if (per_page != '') {
            basic_url = basic_url + `&per_page=${per_page}`;
        }
        if (search_price != '') {
            basic_url = basic_url + `&search_price=${search_price}`;
        }
        if (stock != '') {
            basic_url = basic_url + `&stock=${inventory }`;
        }

        window.location.href = basic_url

    }
    
    //mobile filter get child category
    function get_child_categories() {
        var parent_id = jQuery('#selected_cat_mbl').val();
        jQuery.ajax({
            url: '/child/categories/' + parent_id,
            method: 'get',
            success: function(response) {
                console.log(response);
                if(response.status == 'success') {
                    $('#childeren_mbl').html('');
                    $.each(response.child_categories, function(index, option) {
                        $('#childeren_mbl').append('<option value="' + option.id + '">' + option.name + '</option>');
                    });
                }
            }
        });
    } 
    //mobile filter
    function handleSelectChangeMbl(searchedOption = '') {
        var basic_url = '';
        var selected_category = jQuery('#selected_cat_mbl').val();
        var brand = jQuery('#brand_mbl').val();
        var per_page = jQuery('#per_page_mbl').val();
        var search_price = jQuery('#search_price_mbl').val();
        var childeren = jQuery('#childeren_mbl').val();
        var stock = jQuery('#in_stk').val();
        var emptyCategory = 0;
        var emptychildCategory = 0;

       
        if (selected_category != '') {
            basic_url = `&selected_category=${selected_category}`;
        }
        else {
            basic_url = `&selected_category=${emptyCategory}`;
        }

        if (childeren != '') {
            basic_url = basic_url + `&childeren_id=${childeren}`;
        }
        else {
            basic_url = basic_url + `&childeren_id=${emptychildCategory}`;
        }

        if (brand != '') {
            basic_url = basic_url + `&brand_id=${brand}`;
        }

        if (per_page != '') {
            basic_url = basic_url + `&per_page=${per_page}`;
        }
        if (search_price != '') {
            basic_url = basic_url + `&search_price=${search_price}`;
        }
        if (stock != '') {
            basic_url = basic_url + `&stock=${stock }`;

        }
       
        basic_url = "?" + basic_url.slice(1);
        window.location.href = basic_url
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
    // jQuery(document).ready(function() {
    //     jQuery('.ajaxSubmit11').click(function(e) {
    //         alert('xxxxxxxxxx')
    //         e.preventDefault();
    //         //alert('here');
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //             }
    //         });
    //         jQuery.ajax({
    //             url: "{{ url('/add-to-cart/') }}",
    //             method: 'post',
    //             data: {
    //                 "_token": "{{ csrf_token() }}",
    //                 p_id: jQuery('#p_id').val(),
    //                 quantity: jQuery('#quantity').val(),
    //             },
    //             success: function(result) {
    //                 console.log(result);
    //                 jQuery('.alert').html(result.success);
    //                 // window.location.reload();
    //             }
    //         });
    //     });

    // });
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


    
    
    
