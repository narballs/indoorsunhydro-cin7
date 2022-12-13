@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @include('partials.banner')
        <div class="bg-light pb-5">
            <div class="container-sm bg-light">
                <div class="row">
                    <div class="col-sm-8 pr-0">
                        <div class="card mt-5">
                            <div class="card-body p-0 text-center">
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
                                                    <img class="img-fluid" src="theme/img/quest_small.png" width="143px"
                                                        height="44px" class="img-responsive img-fluid"></a>
                                            </div>
                                            <div class="col thumb">
                                                <a class="thumbnail">
                                                    <img class="img-fluid" src="theme/img/hydroponics.png" width="167px"
                                                        height="72px" class="img-responsive img-fluid">
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
                    <div class="col-sm-4">
                        <div class="mt-5">
                            <a class="link-dark text-decoration-none text-white" href="{{ url('products')}}">
                                <img class="img-fluid" src="theme/img/all_products.png">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mt-3">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Trolmaster')}}">
                                    <img class="img-fluid" src="theme/img/troll_master.png">
                                </a>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/253/ac-dehumidification-humidification')}}">
                                    <img class="img-fluid" src="theme/img/quest.png">
                                </a>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Advanced Nutrients')}}">
                                    <img class="img-fluid" src="theme/img/nutrients_box.png">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mt-3 gx-5">
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/7/lighting')}}">
                                    <img class="img-fluid" src="theme/img/lightening.png">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Mills Nutrients')}}">
                                    <img class="img-fluid" src="theme/img/can.png">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/7/lighting')}}">
                                    <img class="img-fluid" src="theme/img/lux_lightening.png">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h1 class="text-center our-advantages">Our advantages</h1>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5 ">
                        <div class="thumbnail-img">
                            <img src="/theme/img/National shipping.png" alt="">
                        </div>

                        <h4 class="thumbnail-items text-center mt-5">Nationwide Shipping</h4>
                        <p class="thumbnail-pra mt-3 ">with multiple warehouses you can get the supplies you need
                            delivered to
                            your door faster</p>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
                        <div class="thumbnail-img">
                            <img src="/theme/img/Icon_lowest_prices.png" alt="">
                        </div>
                        <h4 class="thumbnail-items text-center mt-5">Lowest Prices</h4>
                        <p class="thumbnail-pra mt-3">we purchase in bulk so you don’t have to</p>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
                        <div class="thumbnail-img">
                            <img src="/theme/img/Icon_quality.png" alt="">
                        </div>
                        <h4 class="thumbnail-items text-center mt-5">Quality Products</h4>
                        <p class="thumbnail-pra mt-3">we only carry products that we stand by, we honor all manufacturer
                            warranties</p>
                    </div>
                </div>
            </div>
            {{-- <div class="container-fluid bg-dark mt-5 ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container align-items-sm-center">
                            <div class="row">
                                <div class="col-md-4 main-page-footer-section footer-section-product text-light">
                                    <h5 class="mb-4 contact-us-footer">CONTACT US</h5>
                                    <span><img src="/theme/img/map-pin.png" class="img-fluid"></span><span
                                        class="ms-2 pra-footer">Los
                                        Angeles
                                        Warehouse
                                        (HQ)</span>
                                    <p class="ms-4 ms-4-footer">&nbsp;
                                        13310 S. Figueroa St. Los Angeles, CA 90061
                                    </p>
                                    <span><img src="/theme/img/phone-call.png" class="img-fluid"></span><span
                                        class="ms-2 pra-footer">(916)
                                        281-3090</span>
                                    <p class="ms-4 ms-4-footer">&nbsp;
                                        <span class="text-success sm-4-footer ">
                                            MONDAY-SUNDAY 7 Dayes</span> 9AM-5pm
                                    </p>
                                </div>
                                <div class="col-md-4 main-page-footer-section footer-section-product">
                                    <div class="d-flex">
                                        <div class="justify-content-end">
                                            <h5 class="text-uppercase mb-4 contact-us-footer">Customer Service</h5>
                                            <p class="card-text justify-content-end sm-4-footer">Returns</p>
                                            <p class="card-text justify-content-end sm-4-footer">Customer Support</p>
                                            <p class="card-text justify-content-end sm-4-footer">Hydro Guide and Tips
                                            </p>
                                            <h5 class="text-uppercase mb-4 contact-us-footer">MY ACCOUNTS</h5>
                                            <p class="card-text justify-content-end sm-4-footer">My orders</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 main-page-footer-section footer-section-product">
                                    <h5 class=" text-uppercase mb-4 contact-us-footer"> PAYMENT METHOD</h5>
                                    <img class="img-fluid" src="/theme/img/paypal.png">&nbsp; &nbsp;&nbsp; &nbsp;
                                    <img class="img-fluid" src="/theme/img/discover.png">&nbsp; &nbsp;&nbsp; &nbsp;
                                    <img class="img-fluid" src="/theme/img/curus.png">&nbsp; &nbsp;&nbsp; &nbsp;
                                    <img class="img-fluid" src="/theme/img/visa.png">&nbsp; &nbsp;&nbsp; &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </main>

    </div>
    @include('partials.product-footer')
    @include('partials.footer')
</body>

<script>
    $("#superior_brands").on('click', function(){ 
        window.location.href = '/products/';
    });
</script>