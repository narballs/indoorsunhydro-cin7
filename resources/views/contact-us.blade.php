@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>{{ $message }}</strong>
</div>
@endif

<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
<div class="container-fluid pl-0 pr-0">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 pr-0">
		<div class="row" style="background-image: url('/theme/img/img_12.png');">
			<div class="thankyou bg-light mt-5 mb-5 contact-row" id="thanks">
				<div class="col-md-12 text-center m-auto pt-5">
					<h2 style="font-size:30px" class="text-center pt-5">CONTACT US</h2>
				</div>
				<div class="form-signup-secondary mt-5">
					<div class="px-3">
						<form id="contact-us" class="w-100" name="contact-us" method="POST"
							action="{{ route('contact.us.store') }}">
							@csrf
							<div class="row contact-form px-5">
								<div class="col-md-6 ">
									<div class="form-login">
										<div class="input-placeholder mt-3">
											<input type="text" name="name" required="">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Name
											</div>
										</div>
										<div class="input-placeholder mt-3 ">
											<input type="text" name="email" required="">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Email
											</div>
										</div>
										<div class="input-placeholder mt-3">
											<input type="subject" name="subject" required="">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Subject
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="input-placeholder mt-3">
										<textarea type="text" name="message" required="" id="contact_message"
											class="message fontAwesome pl-4" placeholder=" Message"></textarea>
										{{-- <div class="placeholder pl-3  fontAwesome m-0 mt-2">
											 Message
										</div> --}}
										<button type="submit" name="save" class="btn-login mt-3" id="save">SEND</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="mt-5">
				</div>
			</div>
		</div>
	</div>
</div>

@include('partials.footer')
@include('partials.product-footer')