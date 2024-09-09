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
<style>
	.contact_page_heading , .text_1 , .text_2 {
		font-family: 'Poppins';
		font-size: 48px;
		line-height: 48px;
		letter-spacing: 1px;
		font-weight:700;
	}
	.contact_page_heading > .text_2 {
		color: #7CC633;
	}
	.contact_page_heading > .text_1 {
		color: #000000;
	}
	.contact_para {
		font-family: 'Poppins';
		font-size: 20px;
		line-height: 23.6px;
		letter-spacing: 1px;
		font-weight:500;
	}
</style>
<div class="bg-white">
	<div class="bg-white">
		{{-- <div class="container-fluid px-0 overflow-hidden"> --}}
			{{-- <div class="row"> --}}
				{{-- <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 mb-3"> --}}
					{{-- <img src="{{asset('/theme/bootstrap5/images/updated_contact_banner.png')}}" class="banner-img img-fluid w-100" alt="..."> --}}
					{{-- <h2 class="position-absolute top-50 start-50 translate-middle"> --}}
						{{-- <div class="banner-title">
							<h3 class="text-uppercase font-weight-bold text-white border-0 contact_page_heading">
								<span class="text_1">We're Here to Help You</span> <span class="text_2">Grow</span>
							</h3>
						</div> --}}
					{{-- </h2> --}}
				{{-- </div>
			</div> --}}
			<div class="row justify-content-center">
				<div class="col-md-10 mt-2">
					<div class="banner-title">
						<h3 class="text-uppercase font-weight-bold text-white border-0 contact_page_heading p-3">
							<span class="text_1">We're Here to Help You</span> <span class="text_2">Grow</span>
						</h3>
						<p class="contact_para p-3">
							Expert Advice and Product Support in hydroponic gardening.
						</p>
					</div>
				</div>
				<div class="col-md-10 p-4">
					@include('partials.contact_partial')
				</div>
			</div>
		{{-- </div> --}}
	</div>
</div>
@include('partials.footer')
@include('partials.product-footer')