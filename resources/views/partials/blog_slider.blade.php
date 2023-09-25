
<script src="{{asset('theme/bootstrap5/js/bootstrap_alpha.min.js')}}"></script>
@php
$blogs = NavHelper::getBlogs();
$chunks = $blogs->chunk(3);
$chunks->toArray();
@endphp
<style>
    .carousel-control-prev {
        display: none;
    }
    .carousel-control-next {
        display: none;
    }
</style>
<section class="pt-5 pb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <a class="btn-sm" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                        <i class="fa fa-angle-left arrow_icon text-dark" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
                <div class="col-md-10 p-0">
                    <div id="carouselExampleIndicators2" class="carousel slide">
                        <div class="carousel-inner">
                            @foreach($chunks  as $chunk)
                            <div class="carousel-item all_items">
                                <div class="row">
                                    @foreach($chunk as $blog)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img class="img-fluid" alt="100%x280" src="{{asset('pages/blogs/' . $blog['image'])}}">
                                            <div class="card-body">
                                                <h4 class="card-title">{{$blog->title}}</h4>
                                                <p class="card-text">{!! \Illuminate\Support\Str::limit($blog->description, 130) !!}</p>
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
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-1 text-right">
                    <a class="btn-sm" href="#carouselExampleIndicators2" role="button" data-slide="next">
                        <i class="fa fa-angle-right arrow_icon text-dark" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>