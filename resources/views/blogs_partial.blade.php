@php
$blogs = NavHelper::getBlogs();
@endphp
<div class="col-md-12">
	@if (count($blogs) > 0)
	<div class="row">
		@foreach ($blogs as $blog)
		<div class="col-md-4">
			<div class="card" style="width: 18rem;">
				<img class="card-img-top" src="{{asset('pages/blogs/' . $blog->image)}}" alt="Blog Image">
				<div class="card-body">
					<h5 class="card-title">{{$blog->title}}</h5>
					<p class="card-text">{!! \Illuminate\Support\Str::limit($blog->description, 70) !!}</p>
					<a href="{{route('blog_detail' , $blog->id)}}" class="btn btn-primary">Read More</a>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	@endif
</div>