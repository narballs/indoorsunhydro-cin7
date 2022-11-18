@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
		Checkout
	</p>
</div>
   
<?php
    $cart_total = 0;
    $cart_price = 0;
?>
@if(Session::get('cart'))
    @foreach(Session::get('cart') as $cart)
        <?php 
            $total_quatity =  $cart['quantity'];
            $total_price = $cart['price'] * $total_quatity;
            $cart_total  = $cart_total + $total_price ;
        ?>
    @endforeach
@endif
<div class="container">
	 @if(session('message'))
        <div class="alert alert-danger">
          {{ session('message') }}
        </div> 
    @endif
      	<div class="row">
        	<div class="col-md-5 order-md-2 mb-4">
	          	<!-- <h4 class="d-flex justify-content-between align-items-center mb-3"> -->
	            <div class="cart-headings">Cart Total</div>
	          <!-- 	</h4> -->
	          	<div class="border-bottom"></div>
                <div class="row mt-4 max-width">
                    <div class="col-md-10">
                        <img src="theme/img/dollar.png">
                        <span class="cart-subtitles">Subtotal</span>
                    </div>
                    <div class="col-md-2">
                       <span class="totals">${{$cart_total}}</span> 
                    </div>
                </div>
                 <div class="border-bottom mt-4"></div>
              <!--   <div class="mt-4">
                    <span class="cart-subtitles"><img src="theme/img/shipping.png">&nbsp;Shipping</span>
                </div>
                <div class="justify-content-between mt-1">     
                    <span class="shipping-calculator">Enter your address to calculate shipping</span>
                </div>
                <div class="d-flex mt- justify-content-between">
                    <input type="text" name="email" class="bg-light form-control fontAwesome shipping-calculator" placeholder="&#xf0e0; Your Email" required>
                </div>
                <div class="mt-1">
                    <img src="theme/img/calculator.png">
                    <span class=" mt-2 text-dark shipping-calculator"><a class="text-dark" href="#">Calculate Shipping</a></span>
                </div>
                  <div class="border-bottom mt-3">
                </div> -->
                 <div class="row mt-4 max-width">
                    <div class="col-md-10">
                        <img src="theme/img/pricing_tag.png">
                        <span class="totals">Total</span>
                    </div>
                    <div class="col-md-2 text-danger totals">${{$cart_total}}</div>
                </div>
                <div class="border-bottom mt-4"></div>
              
         
            <div>
                <div class="mt-4 payment-option">Delivery Options</div>
                @foreach($payment_methods as $payment_method)
                <form class="p-2" action="{{url('order')}}" method="POST" id="order_form" name="order_form">
                	@csrf
                	   	@foreach($payment_method->options as $payment_option)
                	  		<div class="row"> 
                        		<div class="ms-3">
                        			<input type="hidden" value="{{$payment_method->name}}" name="method_name">
                        			<input type="radio" id="local_delivery_{{$payment_option->id}}" name="method_option" value="{{$payment_option->option_name}}">
                        		 	<label for="local_delivery payment-option-label">{{$payment_option->option_name}}</label>
                        	    </div>
                    	   </div>
                           
                        @endforeach
       				
            	
            	@endforeach
            </div>
      
       <!--                  <div class="col-md-12 ">
            kklklkl
        </div> -->

   <!--        	<div class="col-md-12 p-0">
          		<table cellpadding="4">
                    <tr>
                        <td>
                           <img src="theme/img/visa.png" height="40px">
                        </td>
                        <td>
          			       <img src="theme/img/master.png">
          		        </td>
                       
                        <td>
                           <img src="theme/img/dinner.png">
                        </td>
                        <td>
                           <img src="theme/img/xxx.png">
                        </td>
                        <td>
                           <img src="theme/img/american_express.png">
                        </td>
                        <td>
                           <img src="theme/img/discover.png" height="40px">
                        </td>
                    </tr>
                </table>

          	</div> -->

   <!--        	<form class="card p-2">
          	    <div class="fw-bold">{{$payment_method->name}}</span>
                </div>
                <div class="col-md-7 mb-3">
                	<label for="cc-expiration" class="fw-bold">Expiration</label>
                	    <input type="text" class="form-control bg-light" id="cc-expiration" placeholder="Expiration" required>
                	    <div class="invalid-feedback bg-light">
                  		Expiration date required
                	    </div>
              	</div>
            	<div class="col-md-5 mb-3">
                	<label for="cc-expiration" class="fw-bold">CVV</label>
                	<input type="text" class="form-control bg-light" id="cc-cvv" placeholder="CVV" required>
                	<div class="invalid-feedback">
                  			Security code required
                	</div>
            	</div> -->
        
<!-- 
        </form> -->
    </div>
        
        <div class="col-md-7 order-md-1">
            <div class="cart-headings border-bottom">Items in Cart</div>

            <div class="row  mt-4">
                <div class="col-md-10"><img src="theme/img/box.png"><span class="ms-1 cart-subtitles">Products</span></div>
                <div class="col-md-2"><span class="ms-3 cart-subtitles">Quantity</span></div>
            </div>
            <?php
                $cart_total = 0;
                $cart_price = 0;
            ?>
            @if(Session::get('cart'))
                 @foreach(Session::get('cart') as $cart)
                <?php 
                    $total_quatity =  $cart['quantity'];
                    $total_price = $cart['price'] * $total_quatity;
                    $cart_total  = $cart_total + $total_price ;
                ?>
                <li class="d-flex justify-content-between border-bottom">
                    <div class="mt-4 mb-4">
                        <h6 class="my-0" style="color: #008BD3 !important;"><a href="" >{{$cart['name']}}</a></h6>
<!--                          <div class="mt-3 border-width"></div>
 -->                    </div>
                   
                    <div class="text-muted rounded-circle mt-4" id="circle" >{{$cart['quantity']}}</div>
                </li>
                @endforeach
            @endif
        </div>
    </div>
    @include('checkout.modals.address-modal')
    <div class="row">
        <div class="col-md-7">
            <div class="billing-address bg-light p-3">
                <div class="bg-light" >
                    <div style="font-weight: 600;
font-size: 20px;">Billing Address</div>
                <div class="row mt-2">
                    <div class="col-md-6 name">{{$user_address->firstName}} {{$user_address->lastName}}</div>
                    <div class="col-md-6 name">{{$user_address->company}}</div>
                </div>
                </div> 
                <div class="address-line bg-light">
                    Address line 1
                </div>

                <div class="bg-light name">
                    {{$user_address->postalAddress1}} 
                </div>
                 <div class="address-line bg-light">
                    Address line 2
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress2}}
                </div>
                <div class="row m-0 bg-light">
                    <div class="col p-0 address-line">
                        City
                    </div>
                     <div class="col p-0 address-line">
                        State
                    </div>
                     <div class=" col p-0 address-line">
                        Zip
                    </div>
                </div>
                <div class="billing-address bg-light">
                     <div class="row m-0">
                        <div class="col p-0 name">
                            {{$user_address->postalCity}}
                        </div>
                         <div class="col p-0 name">
                            {{$user_address->postalState}}
                        </div>
                         <div class="col p-0 name">
                            {{$user_address->postalPostCode}}
                        </div>
                    </div>
                </div>
              <!--   <div class="bg-light">
                    <strong>{{$user_address->postalState}}</strong> 
                </div> -->
            </div>
        </div>

        <div class="col-md-5" id="shipping_address">

               <div class="billing-address bg-light p-3">
                <div class="bg-light" >
                    <div style="font-weight: 600;
font-size: 20px;">Shipping Address</div>
                <div class="row mt-2">
                    <div class="col-md-6 name">{{$user_address->firstName}} {{$user_address->lastName}}</div>
                    <div class="col-md-6 name">{{$user_address->company}}</div>
                </div>
                </div> 
                <div class="address-line bg-light">
                    Address line 1
                </div>

                <div class="bg-light name">
                    {{$user_address->postalAddress1}} 
                </div>
                 <div class="address-line bg-light">
                    Address line 2
                </div>
                <div class="bg-light name">
                    {{$user_address->postalAddress2}}
                </div>
                <div class="row m-0 bg-light">
                    <div class="col p-0 address-line">
                        City
                    </div>
                     <div class="col p-0 address-line">
                        State
                    </div>
                     <div class=" col p-0 address-line">
                        Zip
                    </div>
                </div>
                <div class="billing-address bg-light">
                     <div class="row m-0">
                        <div class="col p-0 name">
                            {{$user_address->postalCity}}
                        </div>
                         <div class="col p-0 name">
                            {{$user_address->postalState}}
                        </div>
                         <div class="col p-0 name">
                            {{$user_address->postalPostCode}}
                        </div>
                    </div>
                </div>
              <!--   <div class="bg-light">
                    <strong>{{$user_address->postalState}}</strong> 
                </div> -->
            </div>
        </div>
    </div>

    <div class="col-md-6" style="margin-top: 118px !important;margin:auto; !important; max-width:600px !important;"> 
        <button type="submit" class="button-cards w-100" id="proceed_to_checkout" onclick="submit()">Proceed to checkout</button>
    </div>
    </form>
</div>
</div>
<div class="row mt-5 pt-5">
  @include('partials.product-footer')
</div>      
          	<!-- <h4 class="mb-3">Billing address</h4> -->
<!-- 
          	<div class="border-bottom"></div>

            <div class="row mt-4 fs-5">
                <div>
                    {{$user_address->firstName}} {{$user_address->lastName}}
                </div>
                <div>
                    {{$user_address->postalAddress1}}
                </div>
                <div>
                    {{$user_address->postalAddress2}}
                </div>
                <div>
                    {{$user_address->postalCity}}
                </div>
                <div>
                    {{$user_address->postalstate}}
                </div>
                <div>
                    {{$user_address->postalPostCode}}
                </div>
                   <div>
                    {{$user_address->phone}}
                </div>
                 <div class="col-md-5 h-50 mt-4"><button class="btn-login" onclick="updateAddress()">Update Address</button></div>
            </div> -->



<!--             <div class="update-address-section d-none" id="address-form-update">
 -->
                <form class="needs-validation mt-4 novalidate" style="display:none" action="{{url('order')}}" method="POST">
                @csrf
                <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" >First name</label>
                        <input type="text" class="form-control bg-light" name="firstName" placeholder="First name" value="{{$user_address->firstName}}" required>
                     <div id="error_first_name" class="text-danger">

                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control bg-light" name="lastName" placeholder="" value="{{$user_address->lastName}}" required>
                        <div id="error_last_name" class="text-danger">

                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="company">Company Name(optional)</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name" value="{{$user_address->company}}" required>
                       
                    </div>
                     <div id="error_company" class="text-danger">

                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Country</label>&nbsp;<span>United States</span>
                    <input type="hidden" name="country" value="United States">
                </div>


                <div class="mb-3">
                    <label for="address">Street Address</label>
                    <input type="text" class="form-control bg-light" name="address" value="{{$user_address->postalAddress1}}" placeholder="House number and street name" required>
                 
                </div>
                <div id="error_address1" class="text-danger">

                </div>

                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="address2" value="{{$user_address->postalAddress2}}" placeholder="Apartment, suite, unit etc (optional)">
                </div>
                <div id="error_address2" class="text-danger">

                </div>
                <div class="mb-3">
                    <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="town_city" value="{{$user_address->postalCity}}" placeholder="Enter your town">
                </div>
                <div id="error_city" class="text-danger">

                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="state">State</label>

                        <select class="form-control bg-light" name="state" id="state">
                            @foreach($states as $state)
                                <?php 
                                    if($user_address->postalState == $state->name){
                                            $selected = 'selected';

                                    }
                                    else
                                    {
                                         $selected = '';
                                    }
                                
                                ?>
                                <option value="{{$state->name}}" <?php echo  $selected;?>>{{$state->name}}</option>
                            @endforeach
                        </select>
                     <!--    <input type="text" class="form-control bg-light" name="state" value="{{$user_address->postalState}}" placeholder="Enter State" value="" required> -->
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
    <!--                 </div>
                    <div class="col-md-6 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control bg-light" name="zip" placeholder="Enter zip code" value="{{$user_address->postalPostCode}}" required>
                        <div id="error_zip" class="text-danger">
                           
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control bg-light" name="phone" placeholder="Enter your phone" value="{{$user_address->phone}}" required>
                       <div id="error_phone" class="text-danger"></div>
                    
                    
                
                </div>
            
                <div>
                    <button calss="btn btn-primary" onclick="updateContact('{{auth()->user()->id}}')">Update</button>
                </div>
            </div>
        </form>
            </div> -->


      

  
      
          
<script>
// function payment_method_validation() {
//     const radios = document.querySelectorAll('input[name="payment_method"]');
//     const selected = [...radios].some(radio => radio.checked);
//     if (!radios) {
//         let gErrorMsg = "Please choose delivery option";
//         alert(gErrorMsg);
//         return false;
//     }
//     return true;
// }

     function submit(){   
        if ( ! $("input[name=method_option]").is(':checked') ) {
            const inputOptions = new Promise((resolve) => {
                setTimeout(() => {
                    resolve({
                        'Local Delivery': 'Local Delivery',
                        'Pickup Order': 'Pickup Order'
                    })
                }, 1000)
            })
            console.log(inputOptions);
            Swal.fire({
                title: 'Please choose delivery option',
                input: 'radio',
                imageUrl: "theme/img/delivery.png",
                inputOptions: inputOptions,
                showCancelButton: false,
                confirmButtonColor: '#8282ff',
                confirmButtonText: 'Continue',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
            if (result.value !== null) {
                if (result.value == 'Local Delivery') {
                    $("#local_delivery_1").attr('checked', 'checked');
                } 
                else {
                   $("#local_delivery_2").attr('checked', 'checked'); 
                }
                $("#order_form").submit();
            }
            });
        }

        else {
        $("#order_form").submit(); // Submit the for
        }
    }
    function updateAddress() {
        // alert('here')
        $('#address-form-update').toggle();
        $('#address-form-update').removeClass('d-none');

    }
    function updateContact(user_id) {

        //$('#address-form-update').removeClass('d-none');
        var first_name = $('input[name=firstName]').val();
        var last_name = $('input[name=lastName]').val();
        var company_name = $('input[name=company]').val();
        var phone = $('input[name=phone]').val();
        var address = $('input[name=address]').val();
        var address2 = $('input[name=address2]').val();
        var town_city = $('input[name=town_city]').val();
        var state = document.getElementById("state").value;
        var zip = $('input[name=zip]').val();
        var email = $('input[name=email]').val();
       

        jQuery.ajax({
                method: 'GET',
                url: "{{ url('/user-addresses/') }}",

                data: {
                	"_token": "{{ csrf_token() }}",
                    "user_id": user_id,
                    "first_name" : first_name,
                    "last_name" : last_name,
                    "company_name" : company_name,
                    "phone" : phone,
                    "address" : address,
                    "address2" : address2,
                    "town_city" : town_city,
                    "state" : state,
                    "zip" : zip,
                    "email" : email
                },
            success: function(response) {

                if(response.success == true) {

                    $('.modal-backdrop').remove()
                     $('#success_msg').removeClass('d-none');
                    $('#success_msg').html(response.msg);
                    window.location.reload();
                }
            },
            error: function (response) {
      ;
                // });
                // var error = Error(response)
                //$('#address_modal_id').modal('show');
                console.log(response)
                
                var error_message = response.responseJSON;
                console.log(error_message);
                var error_text = '';
                if (typeof error_message.errors.first_name != 'undefined') {
                    error_text = error_message.errors.first_name;
                    $('#error_first_name').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_first_name').html(error_text);
                }
                if (typeof error_message.errors.last_name != 'undefined') {
                    var error_text = error_message.errors.last_name;
                    $('#error_last_name').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_last_name').html(error_text);
                }
                if (typeof error_message.errors.company_name != 'undefined') {
                    var error_text = error_message.errors.company_name;
                    $('#error_company').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_company').html(error_text);
                }
                if (typeof error_message.errors.address != 'undefined') {
                    var error_text = error_message.errors.address;
                    $('#error_address1').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_address1').html(error_text);
                }
                // if (typeof error_message.errors.address2 != 'undefined') {
                //     var error_text = error_message.errors.address2;
                //     $('#error_address2').html(error_text);
                // }
                // else {
                //     error_text = '';
                //     $('#error_address2').html(error_text);
                // }
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.errors.zip;
                    $('#error_zip').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.town_city != 'undefined') {
                    var error_text = error_message.errors.town_city;
                    $('#error_city').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_city').html(error_text);
                }
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.zip;
                    $('#error_zip').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.phone != 'undefined') {
                    var error_text = error_message.errors.phone;
                    $('#error_phone').html(error_text);
                }
                else {
                    error_text = '';
                    $('#error_phone').html(error_text);
                }

            }
        });
    }
</script>


           

      

          


            
            

  

   

          
       
<!-- 
            <hr class="mb-4">

           <hr class="mb-4">
          
        </div>
      </div> -->

@include('partials.footer')