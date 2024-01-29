
<div class="card-body" id="products-container">
    @if(!empty($similar_products))
        <div class="row">
            <div class="col-md-12">
                <p class="buy_again_heading">Similar Products</p>
            </div>
        </div>
        @foreach($similar_products as $similar_product)
            @if(!empty($similar_product->categories) && $similar_product->categories->is_active === 1)
                @if(!empty($similar_product->options))
                    @foreach ($similar_product->options as $option)
                    @php
                        $product = $similar_product; 
                    @endphp
                    <div class="row mt-4 mb-3">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 image-div image-div-account d-flex justify-content-center">
                                    @if(!empty($product->images))
                                        <img src="{{ $product->images }}" alt="" class="buy_again_product_image">
                                    @else
                                        <img src="{{ asset('/theme/img/image_not_available.png') }}" alt="" class="buy_again_product_image">
                                    @endif
                                </div>
                                <div class="col-md-8 data-div data-div-account">
                                    <div class="row">
                                        <div class="col-md-10">
                                            
                                            <p class="product_name mb-1">
                                                @if(!empty($product->name))
                                                    <a class="product_name" id="prd_name_{{$product->id }}" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">{{$product->name }}</a>
                                                @endif
                                            </p>
                                            
                                        </div>
                                        <?php
                                            $retail_price = 0;
                                            $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                            foreach ($option->price as $price) {
                                                $retail_price = $price->$user_price_column;
                                            }
                                        ?>
                                        <div class="col-md-10">
                                            <p class="product_price mb-1">
                                                ${{ number_format($retail_price, 2) }}
                                            </p>
                                        </div>
                                        <div class="col-md-10">
                                            <p class="category_name mb-1">Category:
                                                @if(!empty($product->categories))
                                                <a class="category_name" href="{{ url('products/' . $product->categories->id . '/' .$product->categories->slug) }}"> 
                                                    {{$product->categories->name}}
                                                </a> 
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-10">
                                    <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="similar_product_add_to_cart('{{ $product->id }}', '{{ $option->option_id }}')">Add to Cart</button>
                                </div>
                                <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endif
        @endforeach
    @else
        <div class="row">
            <div class="col-md-12">
                <p class="buy_again_heading">No Similar products to show</p>
            </div>
        </div>
    @endif
    {{-- {{ $similar_products->links() }} --}}
</div>

<div id="pagination-container">
    <ul class="pagination mt-1" id="pagination-list">
        <!-- Pagination links will be loaded here dynamically -->
    </ul>
</div>




<style>
    .pagination-item {
        margin: 0 1px;
    }

    .pagination-link {
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        color: #333;
    }

    .pagination-link:hover {
        background-color: #f5f5f5;
    }

    .pagination-link.active {
        background-color: #7BC533;
        color: #fff;
    }
</style>



