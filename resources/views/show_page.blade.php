@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($page))
        <div class="bg-light pb-5">
            <div class="container-sm bg-light">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-5">
                            <img src="{{asset('/pages/banners/' . $page->banner_image)}}" class="banner-img img-fluid w-100" alt="...">
                            <h2 class="position-absolute top-50 start-50 translate-middle">
                                <div class="banner-title">
                                    <span class="text-uppercase font-weight-bold text-white">{{$page->title}}</span>
                                </div>
                            </h2>
                        </div>

                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($page->name == 'Faqs')
                            @include('faqs_partial')
                            @elseif ($page->name == 'Blogs')
                            @include('blogs_partial')
                            @else
                            {!! $page->description !!}
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        @endif
    </main>

    </div>
    @include('partials.product-footer')
    @include('partials.footer')
</body>
<style>
    .start-30 {
        left: 30%;
    }
</style>
<script>
    $("#superior_brands").on('click', function() {
        window.location.href = '/products/';
    });
</script>
