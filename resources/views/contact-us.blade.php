@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('error'))
<!-- <div class="alert alert-denger alert-block text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>{{ $message }}</strong>
</div> -->
@endif
<div class="bg-white">
	<div class="bg-white">
		<div class="container-fluid px-0 overflow-hidden">
			<div class="row">
				<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3">
					<img src="{{asset('/theme/bootstrap5/images/updated_contact_banner.png')}}" class="banner-img img-fluid w-100" alt="...">
					<h2 class="position-absolute top-50 start-50 translate-middle">
						<div class="banner-title">
							<h3 class="text-uppercase font-weight-bold text-white border-0">CONTACT US</h3>
						</div>
					</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-8 p-4 border rounded">
					@include('partials.contact_partial')
				</div>
			</div>
		</div>
	</div>
</div>
@include('partials.footer')
@include('partials.product-footer')