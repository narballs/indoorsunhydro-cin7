@php
	$blogs = NavHelper::getBlogs();
@endphp
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
</div>