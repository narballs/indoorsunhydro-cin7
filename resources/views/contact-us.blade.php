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

<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
<div class="container-fluid pl-0 pr-0">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 pr-0">
		<div class="row" style="background-image: url('/theme/img/img_12.png');">
			<div class="thankyou bg-light mt-5 mb-5 contact-row" id="thanks">
				<div class="col-md-12 text-center m-auto pt-5">
					<div id="success" class="col-md-10 d-none offset-1 text-white alert-success text-center my-2"></div>
					<h2 style="font-size:30px" class="text-center pt-5">CONTACT US</h2>
				</div>
				<div class="form-signup-secondary mt-5">
						<!-- <div class="spinner-border text-success" role="status" id="contact_us_spinner" style="display: block;">
					<span class="sr-only">Loading...</span>
				</div> -->
					<div class="px-3">
						<form id="contact-us" class="w-100" name="contact-us"
						>
							<div class="row contact-form px-5">
								<div class="col-md-6 ">
									<div class="form-login">
										<div class="input-placeholder mt-3">
											<input type="text" name="name" required="" id="name">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Name
											</div>
											<div id="name-error" class="text-danger contact-us-error"></div>
										</div>
										<div class="input-placeholder mt-3 ">
											<input class="contact-us-field" type="text" name="email" required="" id="email">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Email
											</div>
											<div id="email-error" class="text-danger contact-us-error"></div>
										</div>
										<div class="input-placeholder mt-3">
											<input type="subject" name="subject" required="" id="subject">
											<div class="placeholder pl-3 mt-4 fontAwesome">
												 Subject
											</div>
											<div id="subject-error" class="text-danger contact-us-error"></div>
											
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="input-placeholder mt-3">
										<textarea type="text" name="message" required="" id="message"
											class="message fontAwesome pl-4" placeholder=" Message"></textarea>
											<div id="message-error" class="text-danger contact-us-error"></div>
									
										<button type="button" name="save" class="btn-login mt-3" id="save" onclick="contactUs()">SEND</button>
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
<script>
	function contactUs(){
		var email = $('#email').val();
		var name = $('#name').val();
		var subject = $('#subject').val();
		var message = $('#message').val();

		//$('#contact_us_spinner').show();
		$("#spinner-global").removeClass('hide_default')
		$('#save').prop('disabled', true);
		$('.contact-us-error').html('');
		
	
		jQuery.ajax({
			method: 'post',
           	url: "{{ url('/contact-us-store/') }}",
			data: {
        		"_token": "{{ csrf_token() }}",
        		"name" : name,
        		"email": email,
        		"subject" : subject,
        		"message" : message,
    		},
    		success: function(response) {
    			//$('#contact_us_spinner').hide();
    			$("#spinner-global").addClass('hide_default')
    			$('#save').prop('disabled', false);
      			if (response.success == true) {
      				$('#success').html(response.msg);
      				$('#success').removeClass('d-none');
      				const name = document.getElementById('name');
      				const email = document.getElementById('email');
      				const subject = document.getElementById('subject');
      				const message = document.getElementById('message');
      				name.value = '';
      				subject.value	= '';
      				email.value = '';
      				message.value = '';
				}
   			},
   			error: function (response) {
   				//$('#contact_us_spinner').hide();
   				$("#spinner-global").addClass('hide_default')
   				$('#save').prop('disabled', false);
   				var error_message = response.responseJSON;
   				for (const field in error_message.errors) {
					$(`#${field}-error`).html(`${error_message.errors[field]}`);
				}
   			}
       });
		
	}
</script>

@include('partials.footer')
@include('partials.product-footer')