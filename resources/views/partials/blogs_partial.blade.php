@php
	$blogs = NavHelper::getBlogs();
@endphp
<style>
	.blog_title {
        font-size: 22px;
    }
    
    @media screen and (max-width: 768px) {
        .blog_title {
            font-size: 15px;
        }
    }
</style>
<div class="row justify-content-center mt-3">
	<div class="col-md-10">
		@if (count($blogs) > 0)
			<div class="row">
				@foreach ($blogs as $blog)
				<div class="col-md-6 col-xl-4 col-lg-6 col-12 mb-3">
					<div class="card rounded">
						@if (!empty($blog->image))
							<a href="{{route('blog_detail' , $blog->slug)}}">
								<img class="card-img-top rounded-top" alt="100%x280"  src="{{asset('pages/blogs/thumbnails/' . $blog->thumbnail)}}" alt="Blog Image">
							</a>
						@endif
						<div class="card-body blog-card-body-height">
							<h5 class="card-title blog_title">{!! \Illuminate\Support\Str::limit($blog->title, 15) !!}</h5>
							<p class="card-text mb-0"  style="max-height: 7rem;min-height:7rem;">{!! \Illuminate\Support\Str::limit(strip_tags($blog->description), 130) !!}</p>
							<div class="row justify-content-between">
								<div class="col-md-5 col-5 col-xl-5 col-lg-5">
									<small class="text-muted">{{date('Y-m-d', strtotime($blog->created_at))}}</small>
								</div>
								<div class="col-md-5 col-5 col-xl-5 col-lg-5">
									<small class="text-muted"><a href="{{route('blog_detail' , $blog->slug)}}">Read More ..</a></small>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
				{{ $blogs->links('pagination.custom_pagination') }}
			</div>
		@else
			<div class="row">
				<h4 class="text-center font-weight-bold">
					No Blogs Found !!
				</h4>
			</div>
		@endif
	</div>
</div>