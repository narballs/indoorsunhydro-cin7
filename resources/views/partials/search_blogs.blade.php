@include('partials.header')
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($page))
        <div class="bg-white pb-5">
            <div class="bg-white">
                <div class="container-fluid px-0 overflow-hidden">
                    {{-- <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                            <img src="{{asset('/pages/banners/' . $page->banner_image)}}" class="banner-img img-fluid w-100" alt="...">
                            <h2 class="position-absolute top-50 start-50 translate-middle">
                                <div class="banner-title">
                                    <span class="text-uppercase font-weight-bold text-white">{{$page->title}}</span>
                                </div>
                            </h2>
                            @if (strtolower($page->name) == 'blogs')
                                <div class="position-absolute top-60 start-50 translate-middle w-25">
                                    <form method="get" action="{{route('blog_search')}}">
                                        <div class="form-group has-search">
                                            <span class="fa fa-search form-control-feedback"></span>
                                            <input type="text" class="form-control" placeholder="Search" name="search_blog" id="search_blog" value="{{!empty($search_value) ? $search_value : ''}}">
                                            <input type="hidden" name="page_slug" id="" value="{{$page->slug}}">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div> --}}
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
                                            <div class="row align-items-center">
                                                <div class="col-md-1 border-bottom-blog-color">
                                                </div>
                                                <div class="col-md-11">
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
                                {{-- <div class="row justify-content-center mt-3">
                                    <div class="col-md-10">
                                        @if (count($blogs) > 0)
                                        <div class="row">
                                            @foreach ($blogs as $blog)
                                            <div class="col-md-4 mb-3">
                                                <div class="card rounded">
                                                    <img class="card-img-top rounded-top" src="{{asset('pages/blogs/' . $blog->image)}}" alt="Blog Image">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{$blog->title}}</h5>
                                                        <p class="card-text mb-0">{!! \Illuminate\Support\Str::limit($blog->description, 130) !!}</p>
                                                        <div class="row justify-content-between">
                                                            <div class="col-md-5">
                                                                <small class="text-muted">{{date('Y-m-d', strtotime($blog->created_at))}}</small>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <small class="text-muted"><a href="{{route('blog_detail' , $blog->slug)}}">Read More ..</a></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            {{ $blogs->links('pagination.custom_pagination') }}
                                        </div>
                                        @endif
                                    </div>
                                </div> --}}
                                @if (count($blogs) == 0)
                                    <div class="row">
                                        <h4 class="text-center font-weight-bold">
                                            No Blogs Found !!
                                        </h4>
                                    </div>
                                @endif
                                @foreach ($blogs as $blog)
                                    <div class="col-md-12 mb-4 ">
                                        <div class="row justify-content-between border rounded py-3">
                                            <div class="col-md-8 col-lg-8 col-xl-9 col-12 order-md-1 order-2 mt-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="blog_date">
                                                            {{ $blog->created_at->format('j M Y') }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <a href="{{route('blog_detail' , $blog->slug)}}" class="text-decoration-none single_blog_heading">
                                                            {!! \Illuminate\Support\Str::limit($blog->title, 100) !!}
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p class="single_blog_description">
                                                            {!! \Illuminate\Support\Str::limit(strip_tags($blog->description), 130) !!}
                                                        </p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <a  href="{{route('blog_detail' , $blog->slug)}}" class="text-decoration-none text-white single_blog_detail_btn btn">
                                                            Read More
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-xl-3 col-lg-4 col-12 order-1 order-md-2 mt-2">
                                                <a href="{{route('blog_detail' , $blog->slug)}}" class="blog_image_container">
                                                    <img class="img-fluid rounded blog_image_link" alt="100%x280"  src="{{asset('pages/blogs/thumbnails/' . $blog->thumbnail)}}" alt="Blog Image">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
	                            {{ $blogs->links('pagination.custom_pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>
@include('partials.product-footer')
@include('partials.footer')
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