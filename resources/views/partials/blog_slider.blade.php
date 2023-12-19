@php
$blogs = NavHelper::getBlogs();
@endphp
<style>
    .carousel-control-prev {
        display: none;
    }
    .carousel-control-next {
        display: none;
    }
    #similar_products_owl_carasoul_blog .owl-nav.disabled {
        display: block;
    }
</style>

@if (count($blogs) > 0)
<div class="w-100  mt-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="owl-carousel similar_products_owl_carasoul_blog owl-theme mt-4" id="similar_products_owl_carasoul_blog">
                @foreach($blogs as $blog)
                <div class="item mt-2  pt-1">
                    <div class="card">
                        <div class="">
                            <a href="{{route('blog_detail' , $blog->slug)}}"><img class="img-fluid" style="height: 12.5rem;" alt="100%x280" src="{{asset('pages/blogs/thumbnails/' . $blog['thumbnail'])}}"></a>
                        </div>
                        <div class="card-body pb-2 blog-card-body-height">
                            <h4 class="card-title">{{$blog->title}}</h4>
                            <p class="card-text">{!! \Illuminate\Support\Str::limit($blog->description, 130) !!}</p>
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
            </div>
        </div>
    </div>
</div>
@endif