<div class="alert alert-success d-none alert-block mb-3" id="message_flash_div">
    <button type="button" class="close remove_alert" data-dismiss="alert" onclick="remove_flash_message()">×</button>
    <strong id="success_message_div"></strong>
</div>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-5 col-12 mt-2">
        <div class="" id="thanks">
            <form id="contact-us" class="w-100 " name="contact-us">
                <div class="form-group">
                    <span class="contact-us-label" for="name"> Name </span>
                    <input type="text" class="form-control contact-us-inputs-fields" name="name" required="" id="name" placeholder="Enter your name">
                    <div id="name-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group ">
                    <span class="contact-us-label" for="email"> Email </span>
                    <input class="form-control contact-us-inputs-fields" placeholder="you@company.com" type="email" name="email" required="" id="email">
                    <div id="email-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group">
                    <span class="contact-us-label" for="subject"> Subject </span>
                    <input type="text"  class="form-control contact-us-inputs-fields" name="subject" required="" id="subject" placeholder="Subject">
                    <div id="subject-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group">
                    <span class="contact-us-label" for="message"> Message </span>
                    <textarea type="text" name="message" required="" id="message_user" placeholder="Type your message" rows="5" class="form-control contact-us-inputs-fields"></textarea>
                    <div id="message-error" class="text-danger contact-us-error message_error"></div>
                </div>
                <div class="form-group">
                    <div class="d-flex">
                        <div class="">
                            <button type="button" name="save" class="btn btn-success contactUs-btn" id="save" onclick="contactUs()">Submit</button>
                        </div>
                        <div class="spinner-border text-success d-none ml-3" role="status" id="contact_save_loader">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12 col-lg-12 col-xl-7 col-12 mt-2">
        <div class="row justify-content-end">
			<div class="col-lg-12 col-md-12 col-xl-10 col-12">
				<div class="p-4 address_div">
					<h3 class="address_header"> Contact Information </h3>
					<p class="address_flag">Say something to start a live chat!</p>
					<div class="mb-4">
						<img src="{{asset('/theme/bootstrap5/images/Indoorsun_map.png')}}" alt="" class="img-fluid">
						{{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3121.155359408052!2d-121.38823512366841!3d38.53018676828701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x809ac4d01bcd10e1%3A0x6cf5de5a395badf1!2sIndoor%20Sun%20Hydro!5e0!3m2!1sen!2s!4v1725023098432!5m2!1sen!2s" width="100%" height="450" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
					</div>
					
					<div class="row align-items-start">
						<div class="col-md-8">
							<div class="row mb-2">
								<div class="col-md-1 icon_div">
									<img src="{{asset('/theme/img/contact_phone_icon.svg')}}" alt="">
								</div>
								<div class="col-md-10 icon_data_div">
									<p class="mb-0 address_bar ">{{ \App\Helpers\SettingHelper::getSetting('store_phone_number') }}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-1 icon_div">
									<img src="{{asset('/theme/img/contact_map_icon.svg')}}" alt="">
								</div>
								<div class="col-md-10 icon_data_div">
									<p class="mb-0 address_bar">Indoor Sun Hydro is a business <br/> located at {{ \App\Helpers\SettingHelper::getSetting('store_address_line_1') }} ,<br/> {{ \App\Helpers\SettingHelper::getSetting('store_address_line_2') }}</p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="circle-1 " style=""></div>
							<div class="circle-2 " style=""></div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<style>
	.circle-1 {
		background: #fff;
		width: 137;
		height: 137px;
		border: 1px solid #FFF9F9;
		border-radius: 100px;
		position: relative;
		opacity: 13%;
		float: right;
	}
	.circle-2 {
		background: #fff;
		width: 269px;
		height: 269px;
		border: 1px solid #FFFFFF;
		border-radius: 200px;
		opacity: 12%;
		position: absolute;
		top: 15%;
    	left: 30%;
	}

	@media (min-width: 2100px) {
		
		.circle-2 {
			top: 15%;
    		left: 55%;
		}
	}

	@media (max-width: 600px) {
		
		.circle-2 {
    		left: 60%;
		}
	}
	@media (min-width: 1900px) and (max-width: 2099px) {
		
		.circle-2 {
    		left: 40%;
		}
	}
</style>
<script>
	function contactUs(){
		var email = $('#email').val();
		var name = $('#name').val();
		var subject = $('#subject').val();
		var message = $('#message_user').val();
		$('#message_place_holder').addClass('d-none')
		$('#save').prop('disabled', true);
		$('.contact-us-error').html('');
		$('#contact_save_loader').removeClass('d-none');
		
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
    			$('#save').prop('disabled', false);
      			if (response.success == true) {
                    $('#contact_save_loader').addClass('d-none');
                    // $('#message_flash_div').removeClass('d-none');
      				// $('#success_message_div').html(response.msg);
      				const name = document.getElementById('name');
      				const email = document.getElementById('email');
      				const subject = document.getElementById('subject');
      				const message = document.getElementById('message_user');
      				name.value = '';
      				subject.value	= '';
      				email.value = '';
      				message.value = '';
      				message.html = '';
					window.location.href = '/thank-you';
				}
   			},
   			error: function (response) {
                $('#contact_save_loader').addClass('d-none');
   				// $("#spinner-global").addClass('hide_default')
   				$('#save').prop('disabled', false);
   				var error_message = response.responseJSON;
   				for (const field in error_message.errors) {
					$(`#${field}-error`).html(`${error_message.errors[field]}`);
				}
   			}
       });
	}
    function remove_flash_message () {
        $('#message_flash_div').addClass('d-none');
    }
</script>
<style>
	.contactUs-btn {
		border-radius: 7px;
        border: 1.353px solid #7BC533;
        background: #7BC533;
	}
	.contactUs-btn:hover {
		border-radius: 7px;
        border: 1.353px solid #7BC533;
        background: #7BC533;
	}
	.contact-us-label {
		color: #242424;
		font-family: 'Poppins';
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: 24.03px; /* 133.492% */
	}
	.contact-us-inputs-fields {
		color: #E1E1E1;
		font-family: 'Poppins';
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: 24.029px; /* 133.492% */
		margin-top: 8px;
		border-radius : 6px;
		padding : 16px 12px;
		border: 1px solid #E1E1E1;
		

	}
	.address_div {
		background: #7CC633 ;
		border-radius:10px;
	}
	.address_bar {
		color: #FFF;
		font-family: 'Poppins';
		font-size: 16px;
		font-style: normal;
		font-weight: 400;
		line-height: normal;
	}
	.address_flag {
		color: #FFF;
		font-family: 'Poppins';
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: normal;
	}
	.address_header {
		color: #FFF;
		font-family: 'Poppins';
		font-size: 28px;
		font-style: normal;
		font-weight: 600;
		line-height: normal;
	}

    @media and (max-width: 767px)  and (min-width: 280px){
        .map_image {
            width: 100% !important;
        }
    }
</style>