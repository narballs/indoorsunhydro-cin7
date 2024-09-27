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
	@if (count($blogs) == 0)
		<div class="row">
			<h4 class="text-center font-weight-bold">
				No Blogs Found !!
			</h4>
		</div>
	@endif
	@foreach ($blogs as $blog)
		<div class="col-md-12 mb-4 ">
			<div class="row justify-content-between rounded py-3 blog_div">
				<div class="col-md-7 col-lg-7 col-xl-5 col-12 order-md-1 order-2 mt-2">
					<div class="row">
						<div class="col-md-12">
							<span class="blog_date">
								{{ $blog->created_at->format('j M Y') }}
							</span>
						</div>
						<div class="col-md-12">
							<a href="{{route('blog_detail' , $blog->slug)}}" class="text-decoration-none single_blog_heading">
								{!! \Illuminate\Support\Str::limit($blog->title, 50) !!}
							</a>
						</div>
						<div class="col-md-12">
							<p class="single_blog_description">
								{!! \Illuminate\Support\Str::limit(strip_tags($blog->description), 100) !!}
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
					<a href="{{route('blog_detail' , $blog->slug)}}">
						<img class="img-fluid rounded" alt="100%x280"  src="{{asset('pages/blogs/thumbnails/' . $blog->thumbnail)}}" alt="Blog Image">
					</a>
				</div>
			</div>
		</div>
	@endforeach
	{{ $blogs->links('pagination.custom_pagination') }}
</div>