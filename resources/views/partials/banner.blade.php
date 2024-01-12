<div class="container-fluid px-0 overflow-hidden">
	<div class="row">
		<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12" id="banner_div">
			{{-- <img src="/theme/img/Main_banner_home.png" class="banner-img img-fluid w-100" alt="..."> --}}
			<div class="row align-items-center justify-content-between my-5">
				<div class="col-md-6">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-7">
							<img src="{{asset('theme/img/banner_images/banner_partial_1.png')}}" class="img-fluid">
						</div>
						<div class="col-md-7">
							<img src="{{asset('theme/img/banner_images/new_banner_partial.png')}}" class="img-fluid"  alt="">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-8">
							<h5 class="text-white cultivate_quality">
								Cultivate Quality
							</h5>
						</div>
						<div class="col-md-8">
							<a href="{{url('/products/')}}" class="btn btn-success show_now_banner">Shop Now</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<style>
#banner_div	{
	background-image:url('/theme/img/banner_images/banner_background.png');
	background-repeat: no-repeat;
	background-size: cover;
	background-position: center;
	/* height: 520px; */
}
.cultivate_quality	{
	color: #FFF;
	font-family: 'Poppins';
	font-size: 40px;
	font-style: normal;
	font-weight: 700;
	line-height: normal;
	letter-spacing: 3.2px;
	text-transform: uppercase;
}
.show_now_banner {
	background-color: #7CC633;
	color: #FFF;
	font-family: 'Poppins';
	font-size: 24px;
	font-style: normal;
	font-weight: 600;
	line-height: normal;
}
.show_now_banner:hover {
	background-color: #7CC633;
	color: #FFF;
	font-family: 'Poppins';
	font-size: 24px;
	font-style: normal;
	font-weight: 600;
	line-height: normal;
}
</style>