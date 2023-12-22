@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($blog_detail))
        <div class="bg-white pb-5">
            <div class="">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="mt-3 mb-3 fs-2  border-0 text-white text-center align-middle text-uppercase p-2 blog_header">
                                {{$blog_detail->title}}
                            </p>
                        </div>

                        <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12 col-xs-12 blog_detail_description">
                            <div class="mb-5 w-100">
                                @if(!empty($blog_detail->image))
                                    <img src="{{asset('/pages/blogs/' . $blog_detail->image)}}" class="img-fluid-custom" alt="...">
                                @endif
                            </div>
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
    figure {
        text-align: center
    }
    figure img {
        max-width: 100%;
        height: auto;
    }
    .blog_detail_description p,li {
        /* color: #4F4F4F; */
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 35.035px; /* 225.175% */
    }
    .blog_detail_description h1,h2,h3,h4,h5,h6,strong {
        /* color: #242424; */
        font-size: 26px;
        font-style: normal;
        font-weight: 700;
        line-height: 34.923px; /* 140.383% */
    }
    .blog_header {
        font-size: 22px !important;
        background-color:#7BC533;
        font-weight:600;
    }
    .img-fluid-custom {
        width: 100%; /* Make the image fill the entire width of its container */
        height: auto; /* Maintain the image's aspect ratio */
        display: block; 
    }
</style>
