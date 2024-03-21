<div class="alert alert-success d-none alert-block mb-3" id="message_flash_div">
    <button type="button" class="close remove_alert" data-dismiss="alert" onclick="remove_flash_message()">×</button>
    <strong id="success_message_div"></strong>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="" id="thanks">
            <form id="contact-us" class="w-100 " name="contact-us">
                <div class="form-group">
                    <label class="contact-us-label" for="name"> Name </label>
                    <input type="text" class="form-control contact-us-inputs-fields" name="name" required="" id="name" placeholder="Enter your name">
                    <div id="name-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group ">
                    <label class="contact-us-label" for="email"> Email </label>
                    <input class="form-control contact-us-inputs-fields" placeholder="you@company.com" type="email" name="email" required="" id="email">
                    <div id="email-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group">
                    <label class="contact-us-label" for="subject"> Subject </label>
                    <input type="text"  class="form-control contact-us-inputs-fields" name="subject" required="" id="subject" placeholder="Subject">
                    <div id="subject-error" class="text-danger contact-us-error"></div>
                </div>
                <div class="form-group">
                    <label class="contact-us-label" for="message"> Message </label>
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
    <div class="col-md-6">
        <div class="p-4 address_div">
            <h3 class="address_header"> Contact Information </h3>
            {{-- <p class="address_flag">Say something to start a live chat!</p> --}}
            {{-- <div class="mb-4">
                <img src="{{asset('/theme/img/address_map.png')}}" alt="" class="map_image">
            </div> --}}
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
                    <p class="mb-0 address_bar">Indoor Sun Hydro is a business located at {{ \App\Helpers\SettingHelper::getSetting('store_address_line_1') }} , {{ \App\Helpers\SettingHelper::getSetting('store_address_line_2') }}</p>
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
                    $('#message_flash_div').removeClass('d-none');
      				$('#success_message_div').html(response.msg);
      				const name = document.getElementById('name');
      				const email = document.getElementById('email');
      				const subject = document.getElementById('subject');
      				const message = document.getElementById('message_user');
      				name.value = '';
      				subject.value	= '';
      				email.value = '';
      				message.value = '';
      				message.html = '';
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
		line-height: 24.029px; /* 133.492% */
	}
	.contact-us-inputs-fields {
		color: #828282;
		font-family: 'Poppins';
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: 24.029px; /* 133.492% */
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