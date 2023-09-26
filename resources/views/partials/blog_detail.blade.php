@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($blog_detail))
        <div class="bg-white pb-5">
            <div class="row">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-5">
                            <img src="{{asset('/pages/blogs/' . $blog_detail->image)}}" class="banner-img img-fluid w-100" alt="..." style="max-height:450px">
                            <h2 class="position-absolute top-50 start-50 translate-middle">
                                <div class="banner-title">
                                    <span class="text-uppercase font-weight-bold text-white">{{$blog_detail->title}}</span>
                                </div>
                            </h2>
                        </div>

                        <div class="col-xl-10 col-md-10 col-sm-10 col-xs-10">
                            {!! $blog_detail->description !!}
                        </div>
                    </div>
                    <div class="text-center mt-4 mb-4">
                        <h2 class="text-uppercase font-weight-bold text-dark">
                            Related Blogs
                        </h2>
                    </div>
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
</style>
