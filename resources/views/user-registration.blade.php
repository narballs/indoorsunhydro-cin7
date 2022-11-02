@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@include('partials.nav')
@if ($message = Session::get('message'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>{{ $message }}</strong>
</div>
@endif
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
<div class="container">
	<div class="row bg-light" style="background-image: url('/theme/img/img_12.png');">
		<div class="login-reg-panel" style="margin-top:300px;">
			<div class="row">
				<div class="cols">
					<div class="login-info-box text-white">
						<h2 class="text-center">Have an account?</h2>
						<p class="text-center" id="account">Your personal data will be used to support your experience
							throughout this website, to manage access to your account, and for other purposes described
							in
							our privacy policy.</p>
						<label id="label-register" for="log-reg-show">Login</label>
						<input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
					</div>
				</div>
			</div>


			<div class="row company-row">
				<div class="col-md-6">

					<h2 class="text-white company d-none  text-center" id="company" style="margin-top:150px">Please tell
						us
						some information about your company and website</h2>
				</div>

				<div class="col-md-6 d-none login-form-section" id="login-form-section">
					<!-- <form method="POST" class="form-group"  action="{{ route('login') }}">
					@csrf -->
					<div style="margin-top:20px; background:white;" class="col-md-12">
						<div style="margin-left: -294px">
							<label for="company_website" style="maring-left:-81px"><strong class="text-dark">Company
								</strong><strong class="text-danger">Website</strong></label>
						</div>
						<div>

							<input type="text" placeholder="Company Website" id="company_website" name="company_website"
								class="form-control bg-light mt-2 company-info" required>
						</div>
						<div>&nbsp;
						</div>
					</div>
					<div style="margin-top:10px; background:white" class="w-100 col-md-12">
						<div style="margin-left: -294px">
							<label for="company_name" style="maring-left:-81px"><strong class="text-dark">Company
								</strong><strong class="text-danger">Name</strong></label>
						</div>
						<div>

							<input type="text" placeholder="Company Name" id="company_name" name="company_name"
								class="form-control bg-light mt-2 company-info" required>
						</div>
						<div>&nbsp;
						</div>
					</div>
					<div style="margin-top:10px; background:white" class="w-100 col-md-12">
						<div style="margin-left: -367px;">
							<label for="phone" style="maring-left:-85px"><strong
									class="text-dark">Phone</strong></label>
						</div>
						<div>

							<input type="text" placeholder="Phone" name="phone" id="phone"
								class="form-control bg-light mt-2 company-info" required>
						</div>
						<div>&nbsp;
						</div>
					</div>


					<!-- <input type="submit" value="Login" class="btn-login" onclick="loadAddress()"> -->
					<!-- </form> -->
					<!-- <a href="">Forgot password?</a> -->
				</div>

			</div>

			<div class="row">
				<div class="col-md-5">

					<h2 class="text-white address text-center d-none" id="address" style="margin-top:150px">Knowing
						where
						you are located help us to approve your account</h2>
				</div>
				<div class="col-md-7 d-none company-address" id="address-form-section">
					<!-- 	<form method="POST" class="form-group"  action="{{ route('login') }}"> -->
					@csrf
					<div style="margin-top:20px; background:white; " class="w-100 mt-1">
						<div style="margin-left: -386px" class="mt-1">
							<label for="street_address"><strong class="text-dark">Street Address </strong></label>
						</div>
						<div class="mt-3">
							<input type="text" placeholder="House No and street address" name="street_address"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;" required id="street_address">
							<input type="text" placeholder="Appartment Suite etc" name="suit_apartment"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;" required>
						</div>
						<div style="margin-left: -424px" class="mt-3">
							<label for="town_city"><strong class="text-dark">Town/City </strong></label>

						</div>
						<div>
							<input type="text" placeholder="Enter your town" name="town_city_address"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;">
						</div>
						<div class="row">
							<div class="col-md-6 mt-3">
								<label for="town_city" style="margin-left: -173px"><strong class="text-dark">State
									</strong></label>
								<input type="text" placeholder="Enter your state" name="state"
									class="form-control bg-light mt-2" style="width: 95%;margin-left: 20px;">
							</div>
							<div class="col-md-6 mt-3">
								<label for="town_city" style="margin-left: -190px"><strong class="text-dark">Zip
									</strong></label>
								<input type="text" placeholder="Enter your toen" name="zip"
									class="form-control bg-light mt-2" id="zip" style="width: 83%;margin-left: 20px;">
							</div>
						</div>


						<div class="mt-3">
							<strong class="text-dark" style="margin-left:-327px">Country : </strong>United Stated
						</div>




						<div>&nbsp;
						</div>
					</div>

					<!-- <input type="submit" value="save_info" class="btn-login" onclick="thankYou()"> -->
					<!-- 	</form> -->
					<!-- <a href="">Forgot password?</a> -->
				</div>

			</div>

			<div class="row">
				<div class="col-md-5">

					<h2 class="text-white address text-center d-none" id="address" style="margin-top:150px">Knowing
						where
						you are located help us to approve your account</h2>
				</div>
				<div class="col-md-7 d-none company-address" id="address-form-section">
					<!-- 	<form method="POST" class="form-group"  action="{{ route('login') }}"> -->
					@csrf
					<div style="margin-top:20px; background:white; " class="w-100 mt-1">
						<div style="margin-left: -386px" class="mt-1">
							<label for="street_address"><strong class="text-dark">Street Address </strong></label>
						</div>
						<div class="mt-3">
							<input type="text" placeholder="House No and street address" name="street_address"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;">
							<input type="text" placeholder="Appartment Suite etc" name="street_address"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;">
						</div>
						<div style="margin-left: -424px" class="mt-3">
							<label for="town_city"><strong class="text-dark">Town/City </strong></label>

						</div>
						<div>
							<input type="text" placeholder="Enter your town" name="town_city_address"
								class="form-control bg-light mt-2" style="width: 92%;
    						margin-left: 20px;">
						</div>
						<div class="row">
							<div class="col-md-6 mt-3">
								<label for="town_city" style="margin-left: -173px"><strong class="text-dark">State
									</strong></label>
								<input type="text" placeholder="Enter your state" name="state"
									class="form-control bg-light mt-2" style="width: 95%;margin-left: 20px;">
							</div>
							<div class="col-md-6 mt-3">
								<label for="town_city" style="margin-left: -190px"><strong class="text-dark">Zip
									</strong></label>
								<input type="text" placeholder="Enter your toen" name="town_city_address"
									class="form-control bg-light mt-2" style="width: 83%;margin-left: 20px;">
							</div>
						</div>


						<div class="mt-3">
							<strong class="text-dark" style="margin-left:-327px">Country : </strong>United Stated
						</div>
						<div>&nbsp;
						</div>
					</div>
				</div>

			</div>
			<div class="d-none thankyou col-md-10 ms-5 mt-5" id="thanks">
				<h2 class="text-white">Thank you for registering, please allow us sometime to review your subbmission.
				</h2>
				<p class="text-white display-6 mt-5">Please continuw bulding you cart while wait for account approval
				</p>
				<div id="label-login" class="w-50 text-center"
					style="margin-left: 188px; text-decoration: none; color:#7BC533"><a href="/">Continue Shopping</a>
				</div>
			</div>

			<div class="register-info-box text-center">
				<h2 class="text-white">Don't have an account?</h2>
				<p class="text-white">Your personal data will be used to support your experience throughout this
					website, to
					manage access to your account, and for other purposes described in our privacy policy.</p>
				<label id="label-login" for="log-login-show" onclick="signup()">Sign up</label>
				<input type="radio" name="active-log-panel" id="log-login-show">
			</div>

			<div class="white-panel">
				<div class="login-show">
					<h2>LOGIN</h2>
					<form method="POST" action="{{ route('login') }}">
						@csrf
						<input type="text" placeholder="Email" name="email">
						<input type="password" placeholder="Password" name="password">
						<input type="submit" value="Login" class="btn-login">
					</form>
					<!-- <a href="">Forgot password?</a> -->
				</div>
				<div class="register-show">
					<form id="registration">
						@csrf
						<h2>REGISTER</h2>
						<div class="row">
							<div class="col-md-6">
								<input type="text" placeholder="First Name" name="first_name">
							</div>
							<div class="col-md-6">
								<input type="text" placeholder="Last Name" name="last_name">
							</div>
						</div>
						<input type="text" name="email" id="email" placeholder="Email">
						<input type="password" id="password" placeholder="Password" name="password">
						<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
						<button type="button" name="save" class="btn-login" id="save"
							onclick="replaceText()">Register</button>
					</form>

				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		// $('body').on('keyup', '#company_website', function() {
		// 	var company_website = $(this).val();
		// 	if(company_website.length == 50) {
		// 		$('#company_name').focus();
		// 	}
		// });
		// $('body').on('keyup', '#company_name', function() {
		// 	var company_name = $(this).val();
		// 	if(company_name.length == 25) {
		// 		$('#phone').focus();
		// 	}
		// });
		$('body').on('keyup', '#phone', function() {
			var phone = $(this).val();
			if(phone.length == 8) {
				loadAddress();
			}
		});

		$('body').on('keyup', '#zip', function() {
			var zip = $(this).val();
			if(zip.length == 6) {
				thankYou();
			}
		});
	});

	function replaceText(){
		// $(document.body).append(registration);
		var first_name = $('input[name=first_name]').val();
		var last_name = $('input[name=last_name]').val();
		var email = $('#email').val();
		var password = $('#password').val();
		console.log(password);
		jQuery.ajax({
			method: 'post',
           	url: "{{ url('/register/basic/create') }}",
			data: {
        		"_token": "{{ csrf_token() }}",
        		"first_name" : first_name,
        		"last_name": last_name,
        		"email": email,
        		"password": password

    		},
       });
	
		$(".login-info-box").hide();
		$( ".white-panel" ).remove();
		$(".company").show();
		$( ".white-panel" ).add();
		$("#login-form-section").removeClass('d-none');
		$("#company").removeClass('d-none');
	}

	function signup() {
		$(".company").hide();
	}
	function loadAddress() {
		
				var company_website = $('input[name=company_website]').val();
				var company_name = $('input[name=company_name]').val();
				var phone = $('input[name=phone').val();

				// alert(company_website);
				// alert(company_name);
				// alert(phone);
				// alert('sdsds');
				jQuery.ajax({
					method: 'post',
		           	url: "{{ url('/user-contact/') }}",
					data: {
		        		"_token": "{{ csrf_token() }}",
		        		"company_website" : company_website,
		        		"company_name": company_name,
		        		"phone": phone
		    		},
		       });
				$(".company").hide();
				$(".login-form-section").hide();
				$( ".white-panel" ).removeClass();
				$("#address").removeClass('d-none');
				$("#address-form-section").removeClass('d-none');
			}

	function thankYou() {
		var street_address = $('input[name=street_address]').val();
		var suit_apartment = $('input[name=suit_apartment]').val();
		var town_city_address = $('input[name=town_city_address').val();
		var state = $('input[name=state').val();
		var zip = $('input[name=zip').val();
		jQuery.ajax({
			method: 'post',
	        url: "{{ url('/user-contact/') }}",
			data: {
	        	"_token": "{{ csrf_token() }}",
	        	"street_address" : street_address,
	        	"suit_apartment": suit_apartment,
	        	"town_city_address": town_city_address,
	        	"state": state,
	        	"zip" : zip
	    	},
	    });
		alert('sdsds');
		$(".address").hide();
		$(".company-address").hide();
		$( ".white-panel" ).removeClass();
		$("#thanks").removeClass('d-none');
		// $("#address-form-section").removeClass('d-none');
	}

</script>
@include('partials.footer')
@include('partials.product-footer')