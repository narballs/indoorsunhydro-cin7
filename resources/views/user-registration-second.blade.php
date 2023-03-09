@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')


@if ($message = Session::get('message'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>{{ $message }}</strong>
</div>
@endif
<style>
	select.form-control:not([size]):not([multiple]) {
		height: calc(2.25rem + 2px);
		width: 100%;
		background-color: lightgray !important;
		padding: 12px !important;
		border-radius: 2px !important;
		height: 49px;
	}
</style>
<div class="container-fluid pl-0 pr-0">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 pr-0">
		<div class="row">
			<div class="col-md-12 bg-light text-center d-none">
				<h2 style="color:#25529F; font-weight:700" class="text-center pt-5 ">Have an account</h2>
				<div class="col-md-4">
					<p class="text-center pb-5" style="font-size: 16px">Your personal data will be used to support your
						experience throughout this website, to manage access to your account, and for other purposes
						described
						in our privacy policy.</p>
				</div>
			</div>
			<div class="col-md-12 col-xs-6 mt-5 d-none" id="icons">
				<div class="icon-container d-flex">
					<figure>
						<img class="img-fluid" src="/theme/img/round-solid.png" id="sign-up">
						<img class="img-fluid" src="/theme/img/white-arrow.png" style="margin-left: -41px;" id="arrow">
						<img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
						<figcaption id="sigup-bold" class="mt-3">Signup</figcaption>
					</figure>
					<figure>
						<img class="img-fluid" src="/theme/img/round-border.png" id="company-round">
						<img class="img-fluid" src="/theme/img/company.png" style="margin-left: -39px" id="building">
						<img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
						<figcaption id="company-bold" class="mt-3">Company</figcaption>
					</figure>
					<figure>
						<img class="img-fluid" src="/theme/img/round-border.png" id="timer">
						<img class="img-fluid" src="/theme/img/location.png" style="margin-left: -37px" id="timer-main">
						<img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
						<figcaption id="address-bold" class="mt-3">Location</figcaption>
					</figure>
					<figure>
						<img src="/theme/img/round-border.png" id="finish-round">
						<img class="img-fluid" src="/theme/img/finish.png" style="margin-left: -36px" id="tick">
						<figcaption id=thankyou-bold class="mt-3">Finish</figcaption>
					</figure>
				</div>
			</div>
		</div>
		<div class="row ml-0 pr-0 w-100 " style="background-image: url('/theme/img/img_12.png');">
			<div class="login-reg-panel col-xs-6">
				<div class="register-info-box text-center">
					<h2 class=" dont-have-an-account">Don't have an account?</h2>
					<p class=" dont-have-an-account-pra">Your personal data will be used to support your experience
						throughout
						this website, to
						manage access to your account, and for other purposes described in our privacy policy.</p>
					<label id="label-login" for="log-login-show"
						class="sing-up-label d-flex justify-content-center align-items-center"><span
							class="sign-up">SIGN
							UP</span>
					</label>
					<input type="radio" name="active-log-panel" id="log-login-show">
				</div>
				<div class="white-panel">
					<div class="login-show">
						<h2 class="text-center login-title">LOG IN</h2>
						<form method="POST" action="{{ route('login') }}">
							@csrf
							<div class=" form-login">
								<div class="input-placeholder">
									<input type="text" name="email" required>
									<div class="placeholder pl-3 fontAwesome">
										&#xf0e0; Email
									</div>
								</div>
								<div class="input-placeholder">
									<input type="password" name="password" required>
									<div class="placeholder pl-3 fontAwesome">
										&#xf023; Password
									</div>
								</div>

							</div>
							<button type="submit" class="btn-login info login-button">LOG IN</button>
						</form>
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4 remember-me">
								<div class="form-check checkbox-lg form-login">
									<input class="form-check-input aling-items-center justify-conent-center d-flex"
										type="checkbox" value="" id="checkbox-2" />&nbsp;
									<label class="formemail-registration-check-label" for="checkbox-2">Remember
										me</label>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-3">
								<a href="#" class="btn-lost-password d-flex">Lost your password?</a></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
								<p class="required-field"><span class="req">*</span> Required field</p>
							</div>
						</div>
					</div>
					<div class="register-show" style="margin-top: 56px;">
						<form id="email-registration" class="form-signup">
							@csrf
							<h2 class="d-flex justify-content-center align-items-center sing-up-label">SIGN UP</h2>
							<div class="input-placeholder">
								<input type="text" id="email" name="email" required>
								<div class="placeholder pl-3 fontAwesome">
									&#xf0e0; Email
								</div>
							</div>
							<button type="button" name="save" id="save" onclick="signUp2()" class="btn-login">Sign
								up</button>
						</form>
						<div class="row">
							<div class="col-md-6 mt-5">
								<p class="required-field"><span class="req">*</span> Required field</p>
							</div>
						</div>
						<div id="signup_error" class="text-danger"></div>
					</div>
				</div>
				<div class="login-info-box text-white">
					<h2 class=" dont-have-an-account">Have an account?</h2>
					<p class=" dont-have-an-account-pra" id="account">Your
						personal data will be used to support
						your experience
						throughout
						this website, to manage access to your account, and for other purposes described in our privacy
						policy.
					</p>
					<label id="label-register" for="log-reg-show"
						class="d-flex justify-content-center align-items-center  sing-up-label">LOG IN</label>
					<input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
				</div>

				<div class="row company-row bg-light">
					<div class="col-md-12 d-none company-detail" id="company-detail"
						style="margin: auto; height: 504px;">
						<div class="col-md-12 bg-light text-center">
							<h2 class="text-center mt-5" style="color:#25529F; font-size:30px">
								Company
								Detail</h2>
						</div>
						<p class="d-flex justify-content-center align-items-center text-dark" class="signup-intro mt-2">
							Please
							tell us some information about
							your
							company and website</p>
						<div class="form-signup-secondary">
							<div class="row col-md-12 user-info mt-3">
								<div class="col-md-12 mt-5">
									<input type="text" placeholder="&#xf1ad; Company Name" id="company_name"
										name="company_name" class="form-control mt-2 company-info fontAwesome">
									<div class="text-danger" id="company_name_errors"></div>
								</div>
								<div class="col-md-12 mt-3">
									<input type="text" placeholder="&#xf0ac; Company Website" id="company_website"
										name="company_website" class="form-control mt-2 company-info fontAwesome"
										required width="520px">
									<div class="text-danger" id="company_website_errors"></div>
								</div>
								<div class="col-md-12 mt-3">
									<input type="text" placeholder="&#xf095;  Phone" id="phone" name="phone"
										class="form-control mt-2 company-info fontAwesome">
									<div class="text-danger" id="phone_errors"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 mb-5 mt-5">
							<input type="submit" value="SAVE AND CONTINUE" style="width:15rem" class="btn-login mb-5"
								onclick="loadAddress()">
						</div>
					</div>

					<div class="col-md-12 ms-2 company-loc d-none login-form-section" id="login-form-section">
						<div class="col-md-12 bg-light text-center">
							<h2 style="color:#25529F; font-size:30px" class="text-center pt-5">SIGN
								UP
							</h2>
						</div>
						<div class="col-md-12 signup-intro p-0 ">
							<p class="please-tell-us d-flex justify-content-center align-items-center">Please tell us
								about
								yourself so we can get to know your better.
								Your data will be used to create an
								account so that you can make purchases through our system.</p>
						</div>
						<div class="form-signup-secondary">
							<div class="user-info">
								<div class="row mt-3">
									<div class="col-md-6">
										<input type="text" placeholder="&#xf007;  First Name" id="company_website"
											name="first_name" class="form-control mt-3 fontAwesome">
										<div class="text-danger" id="first_name_errors"></div>
									</div>
									<div class="col-md-6">
										<input type="text" placeholder="&#xf007;  Last Name" id="company_website"
											name="last_name" class="form-control fontAwesome mt-3">
										<div class="text-danger" id="last_name_errors"></div>
									</div>
									<div class="col-md-12">
										<input type="password" placeholder="&#xf023;  Password" id="company_name"
											name="password" class="form-control mt-2 company-info fontAwesome mt-3">
										<div class="text-danger" id="password_errors"></div>
									</div>
									<div class="col-md-12">
										<input type="password" placeholder="&#xf023;  Confirm Password"
											id="confirm_password" name="confirm_password"
											class="form-control mt-3 company-info fontAwesome" required>
									</div>
									<div class="text-danger" id="confirm_password_errors"></div>
								</div>
							</div>
						</div>
						<div id="user-info-error" class="text-danger"></div>
						<div class="col-md-12 mb-5 mt-5 text-center">
							<button type="submit" value="" class="btn-login sing-up-continue" onclick="signup()">
								SIGN UP & CONTINUE</button>
						</div>
					</div>
				</div>

				<div class="row business-row bg-light d-none" id="business-row" style="margin-top: -100px">
					<div class="col-md-12 text-center">
						<h2 style="color:#25529F; font-size:30px" class="text-center pt-5">Your Business Location</h2>
					</div>
					<div class="col-md-12 signup-intro">
						<p class="text-center text-dark">Knowing where you are located is also helpful to approve your
							account
							faster.
						</p>
					</div>
					<div class="col-md-12 company-address" id="address-form-section">
						@csrf
						<div class="ms-4">
							<div class="form-signup-secondary">
								<div class="row user-info mt-3">
									<div class="col-md-12 mt-3">
										<input type="text" placeholder="&#xf601;  Street Address, House no, Street Name"
											id="street_address" name="street_address"
											class="form-control mt-2 company-info fontAwesome" required>
										<div class="text-danger" id="street_address_errors"></div>
									</div>
									<div class="col-md-12 mt-3">
										<input type="text" placeholder="&#xf015;  Apartment, Suit, unit etc"
											id="street_address" name="suit_apartment"
											class="form-control mt-2 company-info fontAwesome" required>
										<div class="text-danger" id="suit_apartment_errors"></div>
									</div>
									<div class="col-md-12 mt-3">
										<select id="state-dd" placeholder="&#xf276;   State" name="state"
											class="form-control mt-1 fontAwesome">
											<option value="" class="form-control mt-2 company-info fontAwesome"
												placeholder=" &#xf276;   State"> &#xf276; State</option>
											@foreach ($states as $data)
											<option value="{{$data->id}}">
												{{$data->state_name}}
											</option>
											@endforeach
										</select>
										{{-- <input type="text" placeholder="&#xf5a0;  Town/City" name="town_city"
											class="form-control mt-2 company-info fontAwesome" required>
										<div class="text-danger" id="town_city_errors"></div> --}}
									</div>
									<div class="col-md-6 mt-3">
										<select id="city-dd" placeholder="&#xf5a0;  Town/City" name="city"
											class="form-control mt-2 company-info fontAwesome"> &#xf5a0;
											Town/City
											<option value="" placeholder="&#xf5a0;  Town/City" name="town_city"
												class="form-control mt-2 company-info fontAwesome"> &#xf5a0; Town/City
											</option>
										</select>
										{{-- <input type="text" placeholder="&#xf276;   State" id="company_website"
											name="state" class="form-control mt-1 fontAwesome" required>
										<div class="text-danger" id="state_errors"></div> --}}
									</div>
									<div class="col-md-6 mt-3">
										<input type="text" placeholder="&#xf041;  Zip" id="company_website" name="zip"
											class="form-control mt-1 fontAwesome" required>
										<div class="text-danger" id="zip_errors"></div>
									</div>
								</div>
							</div>
							<div id="address-info-error" class="text-danger"></div>
							<div class="col-md-12 mt-5 m-2">
								<input type="button" value="SAVE AND CONTINUE" style="width:15rem; height: 44px;"
									class="btn-login mb-5" onclick="thankYou()">
							</div>
						</div>
					</div>
				</div>
				<div class="d-none thankyou row bg-light" id="thanks">
					<div class="col-md-12 text-center">
						<h2 style="color:#25529F; font-size:30px" class="text-center pt-5">Finish</h2>
					</div>
					<div class="col-md-12">
						<p class="text-center col-md-12 pb-2 text-dark">Thank you for registering, please allow us
							sometime
							to
							review your subbmission.</p>
					</div>
					<div>
						<img class="img-fluid" src="/theme/img/thanksyou.png">
					</div>
					<div class="col-md-12 text-center mt-5">
						<a href="{{ url('/')}}">
							<input type="button" value="CONTINUE SHOPPING" style="width:15rem; height:50px"
								class="btn-login mb-5"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('partials.footer')
@include('partials.product-footer')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
	var input = document.getElementById("email");
	input.addEventListener("keypress", function(event) {
	  if (event.key === "Enter") {
	    event.preventDefault();
	    document.getElementById("save").click();
	  }
	});

	function signUp2(){
		
		$("#icons").removeClass('d-none');
		$('#sigup-bold').css( 'font-weight', '700' );
		var email = $('#email').val();
		jQuery.ajax({
			method: 'post',
           	url: "{{ url('/register/basic/create') }}",
			data: {
        		"_token": "{{ csrf_token() }}",
        		"email": email,
    		},
    		success: function(response) {
      			console.log(response.success);
      			if (response.success == true) {
      				$(".login-info-box").addClass('d-none');
	      			$( ".white-panel" ).remove();
					$(".company").show();
					$( ".white-panel" ).add();
					$("#login-form-section").removeClass('d-none');
					$("#company").removeClass('d-none');
				}
   			},
   			error: function (response) {
   				console.log(response)

   				var error_message = response.responseJSON;

   				var error_text = '';
   				error_text += error_message.message;

   				error_text += '<br />' + error_message.errors.email[0];

   				$('#signup_error').html(error_text);
   			}
       });
		
	}

	function Error(error) {
		console.log(error)
	    let errorMessages = [];
	    let detailedMessages = [];
	    let errorMessage = error.responseJSON;
	    errorMessages.push(errorMessage);
	    if (error.errors) {
	        detailedMessages = [].concat.apply(
	            [],
	            Object.values(error.errors)
	        );
	        errorMessages = errorMessages.concat(detailedMessages);
	    }

	    let _messages = ''

	    errorMessages.forEach(message => {
	        _messages += message.message + "<br />"
	    });

	    return _messages.replace('The given data was invalid.', '')
	}

	function signup() {
		$('#sigup-bold').css( 'font-weight', 'normal' );
		$('#company-bold').css( 'font-weight', '700' );
		var first_name = $('input[name=first_name]').val();
		var last_name = $('input[name=last_name]').val();
		var password = $('input[name=password]').val();
		var confirm_password = $('input[name=confirm_password]').val();
		jQuery.ajax({
			method: 'post',
           	url: "{{ url('/register/basic/create') }}",
			data: {
        		"_token": "{{ csrf_token() }}",
        		"first_name" : first_name,
        		"last_name": last_name,
        		"password": password,
        		"confirm_password" : confirm_password

    		},
    	success: function(response) {
      			console.log(response.success);
      			if (response.success == true) {
      				$('#sign-up').attr('src','/theme/img/round-border.png');
					$('#arrow').attr('src','/theme/img/arrow.png');
					$('#company-round').attr('src','/theme/img/round-solid.png');
					$('#building').attr('src','/theme/img/building-white.png');
					$(".company-loc").hide();
					$("#company-detail").removeClass('d-none');
				}
   			},
   		error: function (response) {
   				// var error = Error(response)

   				console.log(response)

   				var error_message = response.responseJSON;

   				var error_text = '';
   				//error_text += error_message.message;
   				if (typeof error_message.errors.first_name != 'undefined') {
   					error_text = error_message.errors.first_name;
   					$('#first_name_errors').html(error_text);
   				}
   				else {
   					error_text = '';
   					$('#first_name_errors').html(error_text);
   				}
   				if (typeof error_message.errors.last_name != 'undefined') {
   					var error_text2 = error_message.errors.last_name;
   					$('#last_name_errors').html(error_text2);
   				}
   				else {
   					error_text2 = '';
   					$('#last_name_errors').html(error_text2);
   				}
   				if (typeof error_message.errors.password != 'undefined') {
   					var error_text3 =  error_message.errors.password;
   					$('#password_errors').html(error_text3);
   				}
   				else {
   					error_text3 = ''
   					$('#password_errors').html(error_text3);
   				}
   				if (typeof error_message.errors.confirm_password != 'undefined') {
   					var error_text4 = error_message.errors.confirm_password;
   					$('#confirm_password_errors').html(error_text4);
   				}
   				else {
   					$('#confirm_password_errors').html(error_text4);
   				}
   				
   			},

       });

	
	}
	function loadAddress() {
		$('#company-bold').css( 'font-weight', 'normal' );
		$('#address-bold').css( 'font-weight', '700' );
		var company_website = $('input[name=company_website]').val();
		var company_name = $('input[name=company_name]').val();
		var phone = $('input[name=phone]').val();
		
		jQuery.ajax({
				method: 'post',
		        url: "{{ url('/user-contact/') }}",
				data: {
		        	"_token": "{{ csrf_token() }}",
		        	"company_website" : company_website,
		        	"company_name": company_name,
		        	"phone": phone
		    	},
		    	success: function(response) {
		    		if (response.code == 201) {
		    			$("#thanks").removeClass('d-none');
		    			$(".address").hide();
		    			$("#company-detail").addClass('d-none');

		    		}
		    		else if (response.success == true) {
		    			$('#company-round').attr('src','/theme/img/round-border.png');
						$('#building').attr('src','/theme/img/building.png');
						$('#timer').attr('src','/theme/img/round-solid.png');
						$('#timer-main').attr('src','/theme/img/timer-white.png');
						$(".company-detail").hide();
						$(".login-form-section").hide();
						$( ".white-panel" ).removeClass();
						
						$("#business-row").removeClass('d-none');
		    		}
		    	},
		    	error: function (response) {
   					var error_message = response.responseJSON;
   					var error_text = '';
   					if (typeof error_message.errors.company_name != 'undefined') {
   						error_text = error_message.errors.company_name;
   						$('#company_name_errors').html(error_text);
   					}
   					else {
   						error_text = '';
   						$('#company_name_errors').html(error_text);
   					}
   					if (typeof error_message.errors.phone != 'undefined') {
   						var error_text3 = error_message.errors.phone;
   						$('#phone_errors').html(error_text3)
   					}
   					else {
   						error_text3 = '';
   					}
   				$('#company-info-error').html(error_text);
   			},
		});		
	}

	function thankYou() {
		$('#address-bold').css( 'font-weight', 'normal');
		$('#thankyou-bold').css( 'font-weight', '700' );

		var street_address = $('input[name=street_address]').val();
		var suit_apartment = $('input[name=suit_apartment]').val();
		var state = $('#state-dd').val();
		var town_city_address = $('#city-dd').val();
		var zip = $('input[name=zip]').val();
		jQuery.ajax({
			method: 'post',
	        url: "{{ url('/user-contact/') }}",
			data: {
	        	"_token": "{{ csrf_token() }}",
	        	"street_address" : street_address,
	        	"suit_apartment": suit_apartment,
	        	"city_id": town_city_address,
	        	"state_id": state,
	        	"zip" : zip
	    	},
	    		success: function(response) {
		    		if (response.success == true) {
		    			$('#timer').attr('src','/theme/img/round-border.png');
						$('#timer-main').attr('src','/theme/img/location.png');
						$('#finish-round').attr('src','/theme/img/round-solid.png');
						$('#tick').attr('src','/theme/img/white-tick.png');
						$(".business-row").hide();
						$("#thanks").removeClass('d-none');
						$(".address").hide();
						$(".company-address").hide();
						$( ".white-panel" ).removeClass();
						$("#thanks").removeClass('d-none');
					}

		    	},
		    	error: function (response) {
   					var error_message = response.responseJSON;
   					console.log(error_message);
   					var error_text = '';
   					error_text += error_message.message;
   					if (typeof error_message.errors.street_address != 'undefined') {
   						error_text =  error_message.errors.street_address;
   						$('#street_address_errors').html(error_text);
   					}
   					else {
   						error_text = '';
   						$('#street_address_errors').html(error_text);
   					}
   					if (typeof error_message.errors.suit_apartment != 'undefined') {
   						var error_text2 = error_message.errors.suit_apartment;
   						$('#suit_apartment_errors').html(error_text2);
   					}
   					else {
   						error_text2 = '';
   						$('#suit_apartment_errors').html(error_text2);
   					}
   				
   					if (typeof error_message.errors.town_city != 'undefined') {
   						var error_text3 = error_message.errors.town_city;
   						$('#town_city_errors').html(error_text3);
   					}
   					else {
   						error_text3 = '';
   						$('#town_city_errors').html(error_text3);
   					}
   					if (typeof error_message.errors.state != 'undefined') {
   						var error_text4 = error_message.errors.state;
   						$('#state_errors').html(error_text4);
   					}
   					else {
   						error_text4 = '';
   						$('#state_errors').html(error_text4);
   					}
   					if (typeof error_message.errors.zip != 'undefined') {
   					    var error_text5 = error_message.errors.zip;
   						$('#zip_errors').html(error_text5);
   					}
   					else {
   						error_text5 = '';
   						$('#zip_errors').html(error_text5);
   					}

   				},
	    });
	}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$(document).ready(function () {
            $('#state-dd').on('change', function () {
				$('#city-dd').on('change', function () {
					var cityId = this.value;
					// alert(cityId);
				});
                var idState = this.value;
                $("#city-dd").html('');
                $.ajax({
                    url: "{{url('api/fetch-cities')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
						// city_id: cityId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#city-dd').html('<option value="" placeholder="&#xf5a0;  Town/City" name="town_city"class="form-control mt-2 company-info fontAwesome"> &#xf5a0; Town/City</option>');
                        $.each(result.cities, function (key, value) {
                            $("#city-dd").append('<option value="' + value
                                .id + '">' + value.city + '</option>');
                        });
                        // $('#zip-dd').html('<option value="">Select Zip</option>');
                    }
                });
            });
            // $('#city-dd').on('change', function () {
            //     var idZip = this.value;
            //     $("#zip-dd").html('');
            //     $.ajax({
            //         url: "{{url('api/fetch-zip')}}",
            //         type: "POST",
            //         data: {
            //             city_id: ididZip,
            //             _token: '{{csrf_token()}}'
            //         },
            //         dataType: 'json',
            //         success: function (res) {
            //             $('#zip-dd').html('<option value="">Select City</option>');
            //             $.each(res.zip, function (key, value) {
            //                 $("#zip-dd").append('<option value="' + value
            //                     .id + '">' + value.name + '</option>');
            //             });
            //         }
            //     });
            // });
        });

</script>