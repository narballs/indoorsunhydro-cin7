@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

@if (\Session::has('success'))
<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
	{!! \Session::get('success') !!}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
</div>
@elseif (\Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
	{!! \Session::get('error') !!}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
	{{ Session::get('success') }}
	@php
		Session::forget('success');
	@endphp
</div>
@endif


<form action="{{url('recover-password')}}" method="POST">
   	@csrf
	<div class="form-signup-secondary">
		<div class="user-info">
			<div class="row mt-3">
				<div class="col-md-12">
					<input type="text" placeholder="&#xf007;  Email" id="email"
						name="email" class="form-control mt-3 fontAwesome" value="">
                        @if ($errors->has('email'))
                          <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif

						
				</div>
				<center class="mt-3">
					<button type="submit" class="btn btn sing-up-continue text-center">Send Email</button>
				</center>
			</div>
		</div>
	
	</div>
</form>
@include('partials.footer')
@include('partials.product-footer')