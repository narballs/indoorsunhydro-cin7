@php
$blogs = NavHelper::getBlogs();
@endphp
@include('partials.header')
<style>
    @media only screen and (max-width: 768px) {
        .about_us_page {
            font-size: 14px; /* Decrease font size for smaller screens */
            line-height: 1.5; /* Adjust line height for readability */
            max-width: 100%;
        }
        .about_us_page h2 {
            font-size: 20px; /* Decrease font size for smaller screens */
        }
        .about_us_page h3 {
            font-size: 18px; /* Decrease font size for smaller screens */
        }
        .about_us_page h4 {
            font-size: 16px; /* Decrease font size for smaller screens */
        }
        .about_us_page h5 {
            font-size: 14px; /* Decrease font size for smaller screens */
        }
        .about_us_page h6 {
            font-size: 12px; /* Decrease font size for smaller screens */
        }
        .about_us_page p {
            font-size: 14px; /* Decrease font size for smaller screens */
        }
        .about_us_page ul {
            font-size: 14px; /* Decrease font size for smaller screens */
        }
        .about_us_page ol {
            font-size: 14px; /* Decrease font size for smaller screens */
        }
        .about_us_page a {
            font-size: 14px; /* Decrease font size for smaller screens */
        }
        .about_us_page img {
            max-width: 100% !important;
            height: auto;
        }
    }
</style>
<body>
    <main style="overflow: hidden;">
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($page))
        <div class="bg-white pb-5">
            <div class="bg-white">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                            @if (strtolower($page->name) != 'blogs')
                                @if (!empty($page->banner_image))
                                    <img src="{{asset('/pages/banners/' . $page->banner_image)}}" class="banner-img img-fluid w-100" alt="...">
                                    <h2 class="position-absolute top-50 start-50 translate-middle page-title-head">
                                        <div class="banner-title">
                                            <span class="text-uppercase font-weight-bold text-white">{{$page->title}}</span>
                                        </div>
                                    </h2>
                                @else
                                    <h2 class="text-center mt-4 mb-0">
                                        <div class="banner-title">
                                            <span class="text-uppercase font-weight-bold">{{$page->title}}</span>
                                        </div>
                                    </h2>
                                @endif
                            @else
                                <div class="container-sm mt-5">
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <div class="row align-items-center blogs_heading_div">
                                                <div class="col-md-1 border-bottom-blog-color col-2">
                                                </div>
                                                <div class="col-md-11 col-10">
                                                    <span class="guide_to_growth">
                                                        Guide to Growth
                                                    </span>
                                                </div>
                                                <h3 class="pl-0 latest_from_blogs">
                                                    Latest From the <span class="blog_separate_head">Blog</span>
                                                </h3>
                                                <p class="pl-0 tips_trends">
                                                    Tips, trends, and innovation in hydroponic gardening.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <form method="post" action="{{route('blog_search')}}" class="w-100">
                                                @csrf
                                                {{-- <div class="form-group has-search mb-0">
                                                    <span class="fa fa-search form-control-feedback"></span>
                                                    <input type="text" class="form-control w-100" placeholder="Search" name="search_blog" id="search_blog"  value="{{!empty($search_value) ? $search_value : ''}}">
                                                    <input type="hidden" name="page_slug" id="" value="{{$page->slug}}">
                                                </div> --}}
                                                <div class="search-bar" id="searchBar">
                                                    <input type="text" placeholder="Search" class="form-control w-100" id="searchBarInput" name="search_blog" id="search_blog"  value="{{!empty($search_value) ? $search_value : ''}}">
                                                    <i class="fas fa-search position-icon"></i>
                                                    <input type="hidden" name="page_slug" id="" value="{{$page->slug}}">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                        <div class="col-12">
                                            <div class="card-body about_us_page ">
                                                {!! $page->description !!}
                                            </div>
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
        @if(count($blogs) > 0)
        <div class="pb-5 pt-5" style="background:#F8FCF6;">
            <div class="container-fluid px-0 overflow-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-sm">
                            <div class="row justify-content-center">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card border-0 ">
                                        <div class="card-body" style="background:#F8FCF6;">
                                            @include('partials.blog_slider')
                                        </div>
                                        
                                        <div class="card-footer border-0 text-center" style="background:#F8FCF6;">
                                            <a type="button" href="{{'/page/blogs'}}" class="btn btn-success text-white border-0 read_more_button">Read more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <h5 class="text-center font-weight-bold"> No Blogs Found !!</h5>
        @endif
        <div class="col-md-12 mt-5">
            <h2 class="text-center">
                <div class="banner-title">
                    <span class="text-uppercase font-weight-bold">Contact Us</span>
                </div>
            </h2>
        </div>
        <div class="">
            <div class="container-fluid px-0 overflow-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-sm">
                            <div class="row justify-content-center">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card border-0 ">
                                        <div class="card-body p-4 border rounded">
                                            @include('partials.contact_partial')
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
    .read_more_button {
        border-radius: 7px;
        border: 1.353px solid #7BC533;
        background: #7BC533;
    }

    .read_more_button:hover {
        border-radius: 7px;
        border: 1.353px solid #7BC533;
        background: #7BC533;
    }
    .search-div-blog {
        width: 25%;
    }

    @media only screen and (max-width: 600px) {
        .search-div-blog {
            width: 50%;
            top: 70%;
        }
        .page-title-head {
            top: 30% !important;
        }
        #search_blog {
            padding-top: 0.25rem!important
        }
    }
</style>