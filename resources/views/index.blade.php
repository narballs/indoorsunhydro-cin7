@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        <!-- <div class="container">
				<div class="row"> -->
        @include('partials.search-bar')

        @include('partials.nav')
        @include('partials.banner')

        <!-- 	</div>
			</div> -->
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
                                                <a class="thumbnail" href="#">
                                                    <img class="img-responsive img-fluid" src="theme/img/nutrients.png"
                                                        width="102px" height="86px">
                                                </a>
                                            </div>
                                            <div class="col p-0 my-4 thumb">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/mills.png"
                                                        class="img-responsive img-fluid" width="142px"
                                                        height="59px"></a>
                                            </div>
                                            <div class="col thumb my-5">
                                                <a class="thumbnail" href="#">
                                                    <img src="theme/img/troll.png" width="165px" height="24px"
                                                        class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0">
                                                <a class="thumbnail" href="#">
                                                    <img src="theme/img/elite.png" width="79px" height="88px"
                                                        class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb my-5 p-0">
                                                <a class="thumbnail" href="#">
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
                                                <a class="thumbnail" href="#">
                                                    <img class="img-responsive img-fluid" src="theme/img/titan.png"
                                                        width="130px" height="65px">
                                                </a>
                                            </div>
                                            <div class="col p-0 thumb">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/quest_small.png" width="143px"
                                                        height="44px" class="img-responsive img-fluid"></a>
                                            </div>
                                            <div class="col thumb">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/hydroponics.png" width="167px"
                                                        height="72px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0">
                                                <a class="thumbnail" href="#">
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
                        <div class="free-shipping mt-5">
                            <div class="p-0 mt-0">

                            </div>
                        </div>
                        <div class="mt-3">
                            <img class="img-fluid" src="theme/img/all_products.png">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Trolmaster')}}">
                                    <img class="img-fluid" src="theme/img/troll_master.png">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white" href="{{ url('products/253')}}">
                                    <img class="img-fluid" src="theme/img/quest.png">
                                </a>
                            </div>
                            <div class="col-md-4">
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
                                <a class="link-dark text-decoration-none text-white" href="{{ url('products/7')}}">
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
                                <a class="link-dark text-decoration-none text-white" href="{{ url('products/7')}}">
                                    <img class="img-fluid" src="theme/img/lux_lightening.png">
                                </a>

                            </div>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mt-3">



                        </div>
                    </div>



    </main>
    </div>
    <div class="row">
        <img class="img-fluid" src="theme/img/advantages.png">
    </div>
    </div>

</body>
@include('partials.footer')