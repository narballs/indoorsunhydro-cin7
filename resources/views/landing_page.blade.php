@include('partials.header')
<body>
    <main style="overflow-x: hidden;">
        @include('partials.top-bar')
        <div class="bg-white mb-2 mt-2">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <a class="navbar-brand" href="/">
                                <img class="logo_image_main" src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}">
                            </a>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-end">
                                <button class="btn landing_see_all_inquiry w-50">(213) 410-5912</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('landing_page_partials.banner')
        
        <div class="bg-white">
            <div class="row justify-content-center mt-5">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <div class="card border-0" style="width: 20rem;min-height:200px !important;">
                                    <img class="card-img-top" src="{{asset('/theme/img/landing_page/landing_page_frame_1.png')}}" alt="Card image cap">
                                    
                                </div>
                                <div class="card border-0" style="min-height:200px !important;">
                                    <div class="card-body text-center">
                                        <h5>Expert-Curated Range</h5>
                                        <p class="text-center card-text">
                                            Dive into hydroponic excellence: elite grow tech, precision irrigation, and eco-friendly climate control. We set the gold standard for sustainability!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <div class="card border-0" style="width: 20rem;min-height:200px !important;">
                                    <img class="card-img-top" src="{{asset('/theme/img/landing_page/landing_page_frame_2.png')}}" alt="Card image cap">
                                    
                                </div>
                                <div class="card border-0" style="min-height:200px !important;">
                                    <div class="card-body text-center">
                                        <h5 class="product_name">Optimized Nutrient Solutions</h5>
                                        <p class="text-center card-text">
                                            Unlock vibrant cannabis growth with our tailored nutrient mixes. Meticulously researched for robust plants, ensuring bountiful yields and unmatched quality.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <div class="card border-0" style="width: 20rem;min-height:200px !important;">
                                    <img class="card-img-top" src="{{asset('/theme/img/landing_page/landing_page_frame_3.png')}}" alt="Card image cap">
                                    
                                </div>
                                <div class="card border-0" style="min-height:200px !important;">
                                    <div class="card-body text-center">
                                        <h5>Customer-Centric Approach</h5>
                                        <p class="text-center card-text">
                                            We're committed to lasting relationships, personalized support, and adding value to every interaction. Our expert hydroponics team is here to guide you to success.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pe-0 ps-0">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-center p-2 mt-3">We stock all superior brands!</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row pl-4 pr-4">
                                                <div class="col thumb">
                                                    <a class="thumbnail">
                                                        <img class="img-responsive img-fluid" src="theme/img/nutrients.png"
                                                            width="102px" height="86px">
                                                    </a>
                                                </div>
                                                <div class="col p-0 my-4 thumb">
                                                    <a class="thumbnail">
                                                        <img class="img-fluid" src="theme/img/mills.png"
                                                            class="img-responsive img-fluid" width="142px"
                                                            height="59px"></a>
                                                </div>
                                                <div class="col thumb my-5">
                                                    <a class="thumbnail">
                                                        <img src="theme/img/troll.png" width="165px" height="24px"
                                                            class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col thumb p-0">
                                                    <a class="thumbnail">
                                                        <img src="theme/img/elite.png" width="79px" height="88px"
                                                            class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col thumb my-5 p-0">
                                                    <a class="thumbnail">
                                                        <img src="theme/img/lux.png" width="107px" height="33px"
                                                            class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row  pl-4 pr-4">
                                                <div class="col thumb">
                                                    <a class="thumbnail">
                                                        <img class="img-responsive img-fluid" src="theme/img/titan.png"
                                                            width="130px" height="65px">
                                                    </a>
                                                </div>
                                                <div class="col p-0 thumb">
                                                    <a class="thumbnail">
                                                        <img class="img-fluid" src="theme/img/quest_small.png"
                                                            width="143px" height="44px"
                                                            class="img-responsive img-fluid"></a>
                                                </div>
                                                <div class="col thumb">
                                                    <a class="thumbnail">
                                                        <img class="img-fluid" src="theme/img/hydroponics.png"
                                                            width="167px" height="72px" class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col thumb p-0">
                                                    <a class="thumbnail">
                                                        <img class="img-fluid" src="theme/img/botanicare.png" width="200px"
                                                            height="64px" class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row  pl-4 pr-1">
                                                <div class="col thumb p-0">
                                                    <a class="thumbnail" href="#">
                                                        <img class="img-fluid" class="img-responsive img-fluid"
                                                            src="theme/img/godan.png" width="201px" height="84px">
                                                    </a>
                                                </div>
                                                <div class="col p-0 thumb">
                                                    <a class="thumbnail" href="#">
                                                        <img class="img-fluid" src="theme/img/gavita.png" width="117px"
                                                            height="63px" class="img-responsive img-fluid"></a>
                                                </div>
                                                <div class="col thumb">
                                                    <a class="thumbnail" href="#">
                                                        <img class="img-fluid" src="theme/img/can-filter.png" width="125px"
                                                            height="57px" class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col thumb p-0 my-3">
                                                    <a class="thumbnail" href="#">
                                                        <img class="img-fluid" src="theme/img/clonex.png" width="156px"
                                                            height="28px" class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col thumb p-0 mr-4">
                                                    <a class="thumbnail" href="#">
                                                        <img class="img-fluid" src="theme/img/hand.png" width="59px"
                                                            height="67px" class="img-responsive img-fluid">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- our advantages  --}}
        <div class="bg-white mb-3">
            <div class="row justify-content-center ">
                <div class="col-md-4 mt-5">
                    <h5 class="text-center landing_page_advantage_header">
                        OUR ADVANTAGES
                    </h5>
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6  col-sm-6 col-xs-12 col-lg-4 col-xl-4 landing_adv_div border-0 d-flex justify-content-center">
                            <div class="card landing_adv_card w-75">
                                <div class="card-body mt-5">
                                    <img class="card-img-top" style="height: 13rem;" src="{{asset('theme/img/landing_page/advantage_Frame_1.png')}}" alt="Card image cap">
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5 class="card-title text-center ">Nationwide Shipping</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="landing_advantage_card_text card-text">Free Shipping from $10000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6  col-sm-6 col-xs-12 col-lg-4 col-xl-4 landing_adv_div border-0 d-flex justify-content-center">
                            <div class="card  landing_adv_card w-75">
                                <div class="card-body mt-5">
                                    <img class="card-img-top" style="height: 13rem;" src="{{asset('theme/img/landing_page/advantage_Frame_2.png')}}"  alt="Card image cap">
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5 class="card-title text-center ">Lowest prices on the market</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="landing_advantage_card_text card-text">We ensure that we offer the lowest prices on the market.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6  col-sm-6 col-xs-12 col-lg-4 col-xl-4 landing_adv_div border-0 d-flex justify-content-center">
                            <div class="card landing_adv_card w-75">
                                <div class="card-body mt-5">
                                    <img class="card-img-top" style="height: 13rem;" src="{{asset('theme/img/landing_page/advantage_Frame_3.png')}}"  alt="Card image cap">
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5 class="card-title text-center ">Quality Products</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="landing_advantage_card_text card-text">more than 3000 references available</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center ">
                <div class="col-md-4 mt-5 d-flex justify-content-center">
                    <a class="btn landing_see_all_products w-50" href="{{url('/products')}}">See All Products</a>
                </div>
            </div>
        </div>
        {{-- new products --}}
        @if (!empty($products) && count($products) > 0)
        <div class="product_slider_div">
            <div class="row justify-content-center ">
                <div class="col-md-4 mt-5">
                    <h5 class="text-center text-white landing_page_product_header">
                        NEW PRODUCTS
                    </h5>
                </div>
            </div>
            <div class="row">
                <div class="w-100">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="owl-carousel owl-theme" id="owl-carousel-landing-page">
                                @foreach($products as $product)
                                    @foreach ($product->options as $option)
                                        <?php
                                            $retail_price = 0;
                                            $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                            foreach ($option->price as $price) {
                                                $retail_price = $price->$user_price_column;
                                            }
                                        ?>
                                        <div class="item">
                                            <div class="card border-0 d-flex align-items-center justify-content-center" style="height: 298px;">
                                                @if ($product->images != '')
                                                    <a href="{{ url('/products') }}">
                                                        <img class="card-img-top" style="max-height: 250px; max-width:300px;" src="{{ $product->images }}" alt="Card image cap">
                                                    </a>
                                                @else
                                                    <a href="{{ url('/products') }}">
                                                        <img class="card-img-top" style="max-height: 250px; max-width:300px;" src=" {{ asset('theme/img/image_not_available.png') }}" alt="Card image cap">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="row mt-3 mb-5">
                                                <div class="col-md-12">
                                                    <a href="{{ url('/products') }}"><h5 class="text-center landing_product_name" title="{{$product->name}}">{{ \Illuminate\Support\Str::limit($product->name, 20) }}</h5></a>
                                                    <h5 class="text-center landing_product_price">${{ number_format($retail_price, 2) }}</h5>
                                                    @php
                                                        $monthNum = date("m",strtotime($product->created_at));
                                                        $day = date("l",strtotime($product->created_at));
                                                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                                        $monthName = $dateObj->format('F');
                                                        $day = date('d', strtotime($product->created_at)); 
                                                        $year = date('Y', strtotime($product->created_at)); 
                                                    @endphp
                                                    <p class="text-center landing_product_created">{{'Added on '}} {{$day .' '. $monthName .' '.$year}} </p>
                                                </div>
                                                <div class="col-md-12 text-center">
                                                    <a href="{{url('/user')}}" class="btn landing_add_to_cart w-75">Add to Cart</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- best sellers --}}
        @if (count($top_sellers) > 0)
        <div class="bg-white pb-5">
            <div class="row justify-content-center ">
                <div class="col-md-4 mt-5">
                    <h5 class="text-center landing_page_seller_header">
                       Top Sellers
                    </h5>
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-md-9">
                    <div class="row">
                        @foreach ($top_sellers as $top_seller)
                            @php
                                $product = $top_seller->product;
                            @endphp
                            @foreach ($product->options as $option)
                                <div class="col-md-6 col-xl-3 col-lg-6 col-xs-12 col-sm-12 mt-2 pt-1 justify-content-center d-flex">
                                    <div class="p-2 m-2 shadow-sm w-100 " style="background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: 0.25rem;">
                                        @if ($product->images != '')
                                            <a href="{{ url('/products') }}">
                                                <div class="image-height-mbl" style="height: 300px;">
                                                    <span class="d-flex justify-content-center align-items-center">
                                                        <img src="{{ $product->images }}" style="max-height: 300px; max-width:300px;" class="img_responsive_mbl col-md-10 .image-body mt-2"
                                                            style="" />
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ url('/products') }}">
                                                <div class="image-height-mbl"  style="height: 300px;">
                                                    <span class="d-flex justify-content-center align-items-center">
                                                        <img src=" {{ asset('theme/img/image_not_available.png') }}" style="max-height: 300px; max-width:300px;" class="img_responsive_mbl_not_available col-md-10 .image-body mt-2"
                                                        style="" />
                                                    </span>
                                                </div>
                                            </a>
                                        @endif
                                        <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy">
                                            <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                                                <a class="product-row-product-title" href="{{ url('/products') }}">
                                                    {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                                                    <div class="tooltip-product-text bg-white text-primary">
                                                        <div class="tooltip-arrow"></div>
                                                        <div class="tooltip-inner bg-white text-primary">
                                                            <span class="">{{$product->name}}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </h5>
                                            <div class="col-md-12 p-1">
                                                <?php
                                                $retail_price = 0;
                                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                                foreach ($option->price as $price) {
                                                    $retail_price = $price->$user_price_column;
                                                }
                                                ?>
                                                <h4 text="{{ $retail_price }}" class="text-uppercase mb-0 text-center p_price_resp mt-0">
                                                    ${{ number_format($retail_price, 2) }}</h4>
                                                <p class="category-cart-page  mt-3 mb-0">
                                                    {{'No of purchases: ' . $top_seller->selling_count}}
                                                </p>
                                            </div>
                                            <div class="col-md-12 add-to-cart-button-section mt-2">
                                                <a href="{{url('/user')}}" class="btn landing_page_top_seller_add_to_cart w-100">Add to Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>
    <style>

        .hover_effect:hover {
            background-color: #FFFFFF !important;
            color: #7BC533 !important;
            border: 1px solid #7BC533 !important;
        }
        .product_buys_count {
            font-size: 11px;
        }
        .margin-for-empty {
            margin-bottom: 1.4rem ;
        }
    
        .price-category-view-section {
            min-height: 7.7rem;
        }
    
    
        /* addign tooltip for title of product */
        /* .tooltip-product {
          position: relative;
          display: inline-block;
        } */
        .tooltip-product-text {
            display: none;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 500;
            position: absolute;
            top: 47%;
            text-align: center;
            font-size: 12px;
            /* box-shadow: 1px 1px 4px 4px rgba(0, 0, 0, 0.25); */
            box-shadow: 0px 4px 4px rgba(146, 130, 130, 0.25);
        }
    
        .tooltip-arrow {
            width: 0; 
            height: 0; 
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
            border-top: 7px solid #fff !important;
            position: absolute;
            top: 100%;
            left: 10%;
        }
        .tooltip-product:hover .tooltip-product-text {
          display: block;
        }
        .landing_page_get_pricing:hover {
            background-color: #7BC533 !important;
            color: #FFFFFF !important;
        }
        
        @media screen and (max-width:350px)  and (min-width: 280px){
            .add-to-cart-button-section {
                padding: 0px !important;
            }
        }
        @media screen and (max-width:550px)  and (min-width: 280px){
            .product_buys_count {
                font-size: 7.5px !important;
            }
            .margin-for-empty {
                margin-bottom: 0.95rem !important;
            }
    
            .ft-size {
                font-size: 0.5rem;
            }
    
            .price-category-view-section {
                min-height: 5.7rem;
            }
        }
    </style>
    @include('partials.product-footer')
    @include('partials.footer')
    
</body>
<script>
    $("#superior_brands").on('click', function() {
        window.location.href = '/products/';
    });
</script>
