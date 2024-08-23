@include('partials.header')
<style>
    /* figure.media > div > div {
        position: static !important;
        height: auto !important;
    } */

    .media {
        display: block !important;
    }
    /* .blog_detail_description {
        position: relative;
        padding: 20px;
        background-color: #f8f9fa; 
    } */

    /* .blog_detail_description img, */
    .blog_detail_description iframe {
        display: block;
        width: 100%;
        height: auto; /* Maintain aspect ratio */
        margin-bottom: 20px; /* Space below media elements */
    }

    .blog_detail_description p {
        margin-bottom: 20px;
        font-size: 16px; /* Adjust font size as needed */
    }

    .border-element {
        position: relative;
        padding-left: 15px; /* space for border */
    }

    .border-element::before {
        content: "";
        position: absolute;
        left: 0;
        top: 7px; /* adjust top spacing */
        bottom: 7px; /* adjust bottom spacing */
        width: 5px; /* border width */
        background-color: #7BC533;
    }


</style>
<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @if(!empty($blog_detail))
        <div class="bg-white pb-5">
            <div class="">
                <div class="container-fluid px-0 overflow-hidden">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                            <img src="{{asset('/pages/blogs/thumbnails/covers/' . $blog_detail->image)}}" class="banner-img img-fluid w-100" alt="...">
                        </div>
                        <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
                            <div class="row">
                                <h1 class="page-title-head border-0">
                                    <div class="banner-title">
                                        <div class="text-uppercase font-weight-bold text-dark mx-1 border-element">{{$blog_detail->title}}</div>
                                    </div>
                                </h1>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
                            <div class="row blog_detail_description">
                                {!! $blog_detail->description !!}
                            </div>
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
