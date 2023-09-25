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
                    </div>
                    <div class="container-sm">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row justify-content-center mt-3">
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
                                                                <small class="text-muted"><a href="{{route('blog_detail' , $blog->id)}}">Read More ..</a></small>
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
                                </div>
                            </div>
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
.top-60 {
    top: 60%;
}
.start-10 {
    left: 10%;
}
</style>