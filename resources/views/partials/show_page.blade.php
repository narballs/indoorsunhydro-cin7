@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($page))
        <div class="bg-white pb-5">
            <div class="bg-white">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                            <img src="{{asset('/pages/banners/' . $page->banner_image)}}" class="banner-img img-fluid w-100" alt="...">
                            <h2 class="position-absolute top-50 start-50 translate-middle">
                                <div class="banner-title">
                                    <span class="text-uppercase font-weight-bold text-white">{{$page->title}}</span>
                                </div>
                            </h2>
                            @if (strtolower($page->name) == 'blogs')
                                <div class="position-absolute top-60 start-50 translate-middle w-25">
                                    <form method="post" action="{{route('blog_search')}}">
                                        @csrf
                                        <div class="form-group has-search">
                                            <span class="fa fa-search form-control-feedback"></span>
                                            <input type="text" class="form-control" placeholder="Search" name="search_blog" id="search_blog"  value="{{!empty($search_value) ? $search_value : ''}}">
                                            <input type="hidden" name="page_slug" id="" value="{{$page->slug}}">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="container-sm">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                                @if (strtolower($page->name) == 'faqs')
                                    @include('partials.faqs_partial')
                                @elseif (strtolower($page->name) == 'blogs')
                                    @include('partials.blogs_partial')
                                @else
                                    <div class="card border-0 ">
                                        <div class="card-body ">
                                            {!! $page->description !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(strtolower($page->name) == 'about')
                            <div class="col-md-12 mt-5">
                                <h2 class="text-center">
                                    <div class="banner-title">
                                        <span class="text-uppercase font-weight-bold">Blogs</span>
                                    </div>
                                </h2>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (strtolower($page->name) == 'about')
        <div class="pb-5 pt-5" style="background:#F8FCF6;">
            <div class="container-fluid px-0 overflow-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-sm">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card border-0 ">
                                        <div class="card-body" style="background:#F8FCF6;">
                                            @include('partials.blog_slider')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </main>
    @include('partials.product-footer')
    @include('partials.footer')
</body>
<style>
    .start-30 {
        left: 30%;
    }
    .top-60 {
        top: 60%;
    }
    .start-10 {
        left: 10%;
    }
</style>