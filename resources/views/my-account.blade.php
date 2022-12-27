@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="bg-light">
	<div class="mb-5">
		<p style="line-height: 95px;"
			class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
			MY ACCOUNT
		</p>
	</div>

	<?php //dd($user_address);?>
	<div class="container" style="width:1435px !important;">
		<div class="row bg-light">
			<div class="container-fluid" id="main-row">
				<div class="row my-2" style="border-radius: 0.5rem !important;margin:auto">
					<div class="col-md-6 col-xl-6 col-xs-12 col-sm-12">
						<div class="row bg-white">
							<div class="col-md-2">
							</div>
							<div class="col-md-4 text-left mt-2">
								<span class="d-block my-acount-profile text-capitalize">{{$user->first_name}}</span>
								<span class="d-block" style="font-family: Roboto">{{$user->email}}</span>
							</div>
							<div class="col-md-6 col-xl-6 col-xs-12 col-12 col-sm-12">
							</div>
						</div>
					</div>

					<div class="col-md-6 col-xl-6 col-xs-12 col-sm-12 p-0 align-middle d-flex justify-content-center align-items-center"
						style="background: #F4FFEC; color: #7BC743; border-top-right-radius: 0.5rem !important;border-bottom-right-radius: 0.5rem !important">
						<span style="font-family: 'Roboto';font-style: normal;font-weight: 500;font-size: 40px;">
							My Account
						</span>
					</div>
				</div>
				<div class="row flex-xl-nowrap p-0 m-0 mr-3">
					<div class="col-xl-3 col-sm-12 col-xs-12 p-0 bg-white" style="border-radius: 10px !important;">
						<div
							class="d-flex flex-column align-items-center align-items-sm-start pt-2 text-white min-vh-100">
							<a href="/"
								class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
								<span class="fs-5 d-none d-sm-inline">Menu</span>
							</a>
							<ul class="nav nav-pills flex-column w-100 mb-sm-auto mb-0 align-items-center align-items-sm-start"
								id="menu">
								<li class="nav-item w-100 text-dark active mb-3">
									<a href="#" class="nav-link align-middle px-0 ms-3">
										<i class="fs-4 bi-house"></i>
										<div class="row">
											<div class="col-md-2">
												<span>
													<img src="theme/img/home_nav.png" id="home_active"
														style="display: none;">
													<img src="theme/img/home_unvisited.png" id="home_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class=" ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
													onclick="dashboard()" id="dashboard">
													Dashboard
												</span>
											</div>


										</div>
									</a>
								</li>
								<li class="nav-item w-100 mb-3" id="recent_orders">
									<a href="#" class="nav-link px-0 align-middle  px-0 ms-3">
										<i class="fs-4 bi-table"></i>
										<div class="row">
											<div class="col-md-2">
												<span>
													<img src="theme/img/order_visited.png" id="order_active"
														style="display: none;">
													<img src="theme/img/order_unvisited.png" id="order_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
													onclick="showOrders()">
													Orders
												</span>
											</div>


										</div>

									</a>
								</li>
								<li class="nav-item w-100 mb-3" id="wish_lists">
									<a href="#" class="nav-link px-0 align-middle  px-0 ms-3">
										<i class="fs-4 bi-table"></i>
										<div class="row">
											<div class="col-md-2">
												<span>
													<img src="theme/img/order_visited.png" id="order_active"
														style="display: none;">
													<img src="theme/img/order_unvisited.png" id="order_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
													onclick="wishLists()">
													Whishlists
												</span>
											</div>


										</div>

									</a>
								</li>
								<li class="nav-item w-100 mb-3" id="current_address">
									<a href="#" class="nav-link px-0 align-middle  px-0 ms-3">
										<i class="fs-4 bi-bootstrap"></i>
										<!-- <span class="ms-1 d-none d-sm-inline text-dark fs-5" onclick="edit_address()">Addresses</span> -->
										<div class="row">
											<div class="col-md-2">
												<span>
													<img src="theme/img/address_active.png" id="order_active"
														style="display: none;">
													<img src="theme/img/address_inactive.png" id="order_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class="ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
													onclick="edit_address()">
													Addresses
												</span>
											</div>


										</div>
									</a>
								</li>
								<li class="w-100 mb-3" id="account_details">
									<a href="#submenu3" class="nav-link px-0 align-middle  px-0 ms-3">
										<i class="fs-4 bi-grid"></i>
										<!-- <span class="ms-1 d-none d-sm-inline text-dark fs-5" onclick="accountDetails()">Account Details</span> -->
										<div class="row">
											<div class="col-md-2">
												<span>
													<img src="theme/img/account_active.png" id="order_active"
														style="display: none;">
													<img src="theme/img/account_inactive.png" id="order_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
													onclick="accountDetails()">
													Account Details
												</span>
											</div>


										</div>
									</a>

								</li>
								<li class="border-bottom border-4"
									style="width: 240px; font-family: Roboto;margin: auto; "></li>
								<li class="w-100">
									<a class="text-white nav-link px-0 align-middle  px-0 ms-3 "
										href="{{ route('logout') }}"
										onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
										<div class="row">
											<div class="col-md-2 mt-1">
												<span class="">
													<img src="theme/img/logout.png" id="order_inactive">
												</span>
											</div>
											<div class="col-md-10">
												<span
													class="ms-1  d-sm-inline text-dark fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">
													Logout
												</span>
											</div>


										</div>
									</a>

									<form id="frm-logout" action="{{ route('logout') }}" method="POST">
										{{ csrf_field() }}

									</form>

								</li>
							</ul>
							<div class="dropdown pb-4">
							</div>
						</div>
					</div>

					<div class="col-xl-9 col-sm-12 col-xs-12 py-3 bg-white ms-3"
						style="border-radius: 10px !important;">
						<div class="intro" id="intro">
							<div class="col-md-12">
								<div class="row mb-4 mt-3">
									<div class="col-md-4 ">
										<img src="theme/img/home.png" style="margin: -6px 1px 1px 1px;">
										<span class="pt-1 my-account-content-heading">Dashboard</span>
									</div>
									<div class="col-md-8">
									</div>
								</div>
							</div>
							<div class="border-bottom border-4 ms-3 mr-3"></div>
							<div class="row mt-3">
								<div class="col-md-8 ms-3 mt-3">

									<span class="dashboard-heading">Hello <span
											class="text-capitalize user_names_dashboard"><strong>{{$user->first_name}}</strong></span>
								</div>
									<div class="col-md-1">
										<form id="frm-logout" action="{{ route('logout') }}" method="POST">
											{{ csrf_field() }}
										</form>
									</div>
									</span>
								</div>
							
							<div class="col-md-12  mt-4 dashboard-content pl-1 ms-3">
								From your account dashboard you can view your <span
									class="dashboard-link-text text-decoration-underline" onclick="showOrders()">Recent
									orders</span> manage your <span
									class="dashboard-link-text text-decoration-underline"
									onclick="edit_address()">Shipping and billing addresses</span> and <span
									class="dashboard-link-text text-decoration-underline"
									onclick="accountDetails()">Edit your password and account details.</span>
							</div>
						</div>
						<div class="d-none mt-3 mb-3 pr-4 pl-4" id="orders">
							<div class="col-md-12 border-bottom border-4 pb-4 p-0">
								<img src="theme/img/orders_main.png" style="margin: -6px 1px 1px 1px;">
								<span class="pt-1 my-account-content-heading ">Orders</span>
							</div>


							<table cellpadding="10" cellspacing="10" class="w-100" class="mt-3">
								<tr class="order-table-heading border-bottom">
									<td class="pl-0" style="width:90px;">Order</td>
									<td style="width: 200px;">Date</td>
									<td style="width: 185px">Status</td>
									<td style="width:350px">Total</td>
									<td class="text-center pr-0" style="width:103px;">Action</td>
								</tr>
								<!-- <tr class="border-bottom ms-3 mr-3"></tr> -->
								<tbody id="order_table" class="">


								</tbody>
							</table>
						</div>
						<div class="d-none  mt-3 mb-3 pr-0 pl-0" id="whishlist">
							<div class="col-md-12 border-bottom border-4 pb-4 p-0 bg-white">
								<img src="theme/img/orders_main.png" style="margin: -6px 1px 1px 1px;">
								<span class="pt-1 my-account-content-heading  ">Wishlists</span>
							</div>
							<div class="col-md-8 m-auto rounded-end pt-3 pb-3" style="" id="wishlist_content">
					<!-- 			<div class="container p-0">
								    <header class="text-center">
								        <h1>My Favourites</h1>
								    </header>
									<div class="row">
										  <div class="col-md-8 col-sm-12 co-xs-12 gal-item">
											   <div class="row h-50">
													  <div class="col-md-12 col-sm-12 co-xs-12 gal-item">
																<div class="box buy-list-box">
															 <img src="http://fakeimg.pl/758x370/" class="img-ht img-fluid rounded">
																</div>
														</div>
												</div>
										  
										    <div class="row h-50 mt-3">
													 <div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">
													  <div class="box buy-list-box">
														<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">
													</div>
													</div>

													<div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">
													 <div class="box buy-list-box">
														<img src="http://fakeimg.pl/371x370/" class="img-ht img-fluid rounded">
													</div>
													</div>
									            </div>
									      </div>

								           <div class="col-md-4 col-sm-6 co-xs-12 gal-item">
											   <div class="col-md-12 col-sm-6 co-xs-12 gal-item h-25 pl-0 pr-0">
												<div class="box buy-list-box">
													<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">
												</div>
												</div>

												  <div class="col-md-12 col-sm-6 co-xs-12 gal-item h-76 p-0">
												   <div class="box buy-list-box">
													<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">
												</div>
												</div>
								            </div>
									</div>
								<br/>
							</div> -->
						</div>
						</div>
						<div class="" id="order_details">
							<div class="col-md-12 mt-4 d-none order-detail-container pl-4 pr-4"
								id="order-detail-container">
								<div class="row mt-3  detail-heading" id="detail-heading">
									<div class="row mb-4 mt-3 pr-0">
										<div class="col-md-4">
											<img src="theme/img/order_details.png" style="margin: -1px 2px 1px 1px;">
											<span class="pt-1 my-account-content-heading">Order Details
											</span>
										</div>
										<div class="col-md-8 rounded-end" id="order_content">
											<div class="mt-1" id="order_id">dfdfdfdf</div>
										</div>
									</div>
								</div>
								<div class="border-bottom"></div>
								<div class="mt-3">
									<table class="w-100">
										<tr class="border-bottom order-table-heading">
											<td class="address-weight">Products</td>
										</tr>
										<tbody id="lineitems" class="d-none">
										</tbody>
									</table>
								</div>
							</div>
							<div class="d-none" id="orders">
								<div class="col-md-12 border-bottom border-4">
									<h2>Orders</h2>
								</div>

								<table cellpadding="10" cellspacing="10" class="w-100">
									<tr class="border-bottom">
										<th>Ordesdsddr#</th>
										<th>Date</th>
										<th>Status</th>
										<th>Total</th>
										<th class="text-center">Acdddtion</th>
									</tr>
									<tbody id="order_table" class="">
									</tbody>
								</table>
							</div>
							<!-- 				<div class="col-md-9 pl-1">
								<div class="row mt-3 " style="margin:auto;">
									<div class="col bg-white mr-3" style="border-radius: 10px !important;">
		  								<div class="mt-4 mb-4"><img src="theme/img/user_address.png"><span class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order Details</span>
		  								</div>
		  								<div class="border-bottom"></div>
		  								<div id="address_table" class="mt-3 mb-4"></div>
									</div>
									<div class="col pl-1 bg-white" style="border-radius: 10px; border: 1px solid #008AD0!important;">
		  								<div class="mt-4 mb-4 ms-3"><img src="theme/img/shipping_address2.png"><span class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order Details</span>
		  								</div>
		  								<div class="border-bottom ms-3"></div>
		  								<div class="ms-3">
		  									<div id="shipping_table" class="mt-3 mb-4"></div>
		  								</div>
		  							</div>
								</div>
							</div> -->
						</div>
						<div class="edit_address d-none mt-3 mb-3 pr-4" id="edit_address">
							<div class="col-md-12 border-bottom border-4 p-0 ms-3 mr-3">
								<div class="row mb-4 mt-3">
									<div class="col-md-4 ">
										<img src="theme/img/addresses_main.png" style="margin: -1px 2px 1px 1px;">
										<span class="pt-1 my-account-content-heading">Addresses</span>
									</div>
									<div class="col-md-8">
									</div>
								</div>
							</div>
							<div class="bg-blue ms-3 mt-3">
								<span class="billing-address-heading">Billing Address</span>
							</div>
							<div class="ms-3 mt-3">
								<p class="table-row-content">The following addresses will be used on the checkout page
									by default.</p>
							</div>
							<div class="row table-row-content">
								<div class="col-md-5">
									<div class="p-3">
										<div class="row">
											<div class="col-md-10 billing-address-heading-subtitle">Billing Address
											</div>
											<div class="col-md-2">@include('modal.my-account-modal')</div>
										</div>
										<div class="row mt-2">
											<div class="col-md-12 name">
												<span class="user_names">{{$user_address->firstName}}
													{{$user_address->lastName}}</span>
												{{$user_address->postalAddress1}}{{$user_address->postalAddress2}}
											</div>
										</div>
										<div class="name">

										</div>
										<div class="row m-0">
											{{$user_address->postalCity}} {{$user_address->postalState}}
											{{$user_address->postalPostCode}}
										</div>

									</div>
									<div style="display:none">@include('modal.my-account-modal')</div>
									<!-- <div class="ms-3"><button class="edit-button">Edit</button></div> -->
								</div>
								<!-- <div class="col-md-1"></div> -->


								<div class="col-md-5 border-start ms-4">
									<div class="p-3">
										<div class="row">
											<div class="col-md-10 billing-address-heading-subtitle">
												Shipping Address
											</div>
											<div class="col-md-2">@include('modal.my-account-modal')</div>
										</div>
										<div class="row mt-2">
											<div class="col-md-12 name">
												<span class="user_names">{{$user_address->firstName}}
													{{$user_address->lastName}}</span>
												{{$user_address->postalAddress1}}{{$user_address->postalAddress2}}
											</div>
										</div>
										<div class="name">

										</div>
										<div class="row m-0">
											{{$user_address->postalCity}} {{$user_address->postalState}}
											{{$user_address->postalPostCode}}
										</div>
									</div>
								</div>
								<!-- 	<div class="ms-3"><button class="edit-button" style="">Edit</button></div> -->
							</div>
						</div>
						<div class="customer-details d-none pr-2" id="customer-address">
							<div class="row mt-3 detail-heading ms-2 mr-0 ml-0 p-0" id="detail-heading">

								<div class="col-md-12 border-bottom border-4 p-0 mr-3">
									<div class="row mb-4 mt-3">
										<div class="col-md-4 ">
											<img src="theme/img/account_details.png" style="margin: -1px 2px 1px 1px;">
											<span class="pt-1 my-account-content-heading">Account Details</span>
										</div>
										<div class="col-md-8">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 pl-0 pr-0 mr-0 ml-0 w-100">
								<div class="row mt-4  mr-0 ml-0 align-items-center">
									<div class="col-auto">
										<!-- <label for="inputPassword6" class="col-form-label">Password</label> -->
									</div>
									<div class="bg-success text-white" id="updated-success"
										style="background-color: #7BC743 !important;">
									</div>
									@csrf
									<div class="col-md-6 pl-0 mt-2 ">
										<label for="first_name" class="col-form-label dashboard-content">First
											Name</label>
										<input type="text" id="first_name" name="first_name"
											value="{{$user_address->firstName}}" class="bg-light form-control">
									</div>
									<div class="col-md-6 mt-2 pr-0">
										<label for="last_name" class="col-form-label dashboard-content">Last
											Name</label>
										<input type="text" id="last_name" name="last_name"
											value="{{$user_address->lastName}}" class="form-control bg-light">
									</div>
									<div class="col-md-12 mt-2 pl-0 pr-0">
										<label for="last_name" class="col-form-label dashboard-content">Email</label>
										<input type="text" id="email_address" value="{{$user->email}}"
											name="email_address" class="form-control bg-light">
									</div>
								</div>
							</div>
							<div class="border-bottom border-4 mt-3 pt-4"></div>
							<div class="row align-items-center mt-4">
								<div class="col-auto">
									<!-- <label for="inputPassword6" class="col-form-label">Password</label> -->
								</div>
								<div class="billing-address-heading-subtitle ms-3 pl-0">Password Change</div>
								<div class="col-md-12">
									<label for="first_name" class="col-form-label dashboard-content">Current password
										<span class="text-uppercase">(<i class="unchanged-blank">leave blank to leave
												unchanged</i>)</span></label>
									<div class="password-container">


										<input type="password" id="current_password" name="current_password"
											class="fontAwesome form-control bg-light" placeholder="">
										<i class="text-dark eye fa-solid fa-eye"
											onclick="showHidePassword('current_password')" id="eye"></i>
									</div>
								</div>
								<div class="text-danger" id="password-match-fail"></div>
								<div class="col-md-6">
									<label for="first_name" class="col-form-label dashboard-content">New Password (<i
											class="unchanged-blank">LEAVE BLANK TO LEAVE UNCHANGED</i>)</label>
									<div class="password-container">
										<input type="password" id="new_password" name="new_password"
											class="bg-light form-control ms-1">
										<i class="text-dark eye fa-solid fa-eye"
											onclick="showHidePassword('new_password')" id="eye2"></i>
									</div>
								</div>
								<div class="col-md-6">
									<label for="first_name" class="col-form-label dashboard-content">Confirm New
										Password</label>
									<div class="password-container">
										<input type="password" id="new_confirm_password" name="new_confirm_password"
											class="bg-light form-control ms-1">
										<i class="text-dark eye fa-solid fa-eye" id="eye2"
											onclick="showHidePassword('new_confirm_password')"></i>
									</div>
								</div>
								<div class="text-danger" id="errors_password_comfimation"></div>
								<div class="mt-5 ms-2">
									<button type="button" class="btn-save btn col-md-2 text-align-middle p-0"
										value="Save" onclick="change_password()">SAVE CHANGES</button>
								</div>
							</div>
				
				<div class="row ms-2 mb-5 d-none" id="address_row">
					<div class="col-md-3">
					</div>
					<div class="col-md-9 pl-1">
						<div class="row mt-3 " style="margin:auto;">
							<div class="col bg-white mr-3" style="border-radius: 10px !important;">
								<div class="mt-4 mb-4"><img src="theme/img/user_address.png"><span
										class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order
										Details</span>
								</div>
								<div class="border-bottom"></div>
								<div id="address_table" class="mt-3 mb-4"></div>
							</div>
							<div class="col pl-1 bg-white"
								style="border-radius: 10px; border: 1px solid #008AD0!important;">
								<div class="mt-4 mb-4 ms-3"><img src="theme/img/shipping_address2.png"><span
										class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order
										Details</span>
								</div>
								<div class="border-bottom ms-3"></div>
								<div class="ms-3">
									<div id="shipping_table" class="mt-3 mb-4"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	function replaceEye(val) {
        		$('#eye_icon_'+val).attr("src", "theme/img/white_eye.png").css('width' , '20px');
			}
			function replaceEye2(val) {
				$('#eye_icon_'+val).attr("src", "theme/img/eye.png");
			}

			function showHidePassword(val) {
				if (val === "current_password") {
					var current_password = document.getElementById("current_password");
					if (current_password.type === "password" ) {
	    					current_password.type = "text";
	  				} else {
	    				current_password.type = "password";
	  				}
  				}
  				if (val === "new_password") {
  					var new_password = document.getElementById("new_password");
	  				if (new_password.type === "password" ) {
	    					new_password.type = "text";
	  				} else {
	    				new_password.type = "password";
	  				}
  				}
  				if (val === "new_confirm_password") {
  					var new_confirm_password = document.getElementById("new_confirm_password");
	  				if (new_confirm_password.type === "password" ) {
	    					new_confirm_password.type = "text";
	  				} else {
	    				new_confirm_password.type = "password";
	  				}
  				}

			}
			function wishLists() {
				$('#whishlist').removeClass('d-none');
				$('#intro').addClass('d-none');
				$('#edit_address').addClass('d-none');
				$('#address_row').addClass('d-none');
				
				$('.order-detail-container').addClass('d-none');
				$('#customer-address').addClass('d-none')
				$('#orders').addClass('d-none');
				var listitems = '';
					jQuery.ajax({
					url: "{{ url('/get-wish-lists/')}}",
					method: 'GET',
					data: {
						
					
					},
				        success : function (images) {

				        	$('#wishlist_content').html(images);
				        	return;

				        	console.log(images[1]);
							//response.images.slice(-5).forEach(
							// 	function(item, index){
							// 		console.log(item.product.options[0].image)
					
							// });
								listitems += '<div class="container p-0">';
								    listitems +=  '<header class="text-center">'+
								        	'<h1>My Favourites</h1>'+
								    		'</header>'+
						
									'<div class="row">'+
										  '<div class="col-md-8 col-sm-12 co-xs-12 gal-item">'+
											   	'<div class="row h-50">'+
													  '<div class="col-md-12 col-sm-12 co-xs-12 gal-item">'+
																'<div class="box buy-list-box">'+
															 '<img src="' + images[0] + '" class="img-ht img-fluid rounded">'+
																'</div>'+
														'</div>'+
												'</div>'+
										  
										    '<div class="row h-50 mt-3">'+
													 '<div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">'+
													  '<div class="box buy-list-box">'+
														'<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">'+
													'</div>'+
													'</div>'+

													'<div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">'+
													 '<div class="box buy-list-box">'+
														'<img src="http://fakeimg.pl/371x370/" class="img-ht img-fluid rounded">'+
													'</div>'+
													'</div>'+
									            '</div>'+
									      '</div>'+

								           '<div class="col-md-4 col-sm-6 co-xs-12 gal-item">'+
											   '<div class="col-md-12 col-sm-6 co-xs-12 gal-item h-25 pl-0 pr-0">'+
												'<div class="box buy-list-box">'+
													'<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">'+
												'</div>'+
												'</div>'+

												  '<div class="col-md-12 col-sm-6 co-xs-12 gal-item h-76 p-0">'+
												   '<div class="box buy-list-box">'+
													'<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">'+
												'</div>'+
												'</div>'+
								            '</div>'+
									'</div>'+
								'<br/>'+
							'</div>';
							$('#wishlist_content').html(listitems);
				}});
			}

			
			function change_password() {
				var first_name = $('input[name=first_name').val();
				var last_name = $('input[name=last_name').val();
				var email = $('input[name=email_address').val();
				var current_password = $('input[name=current_password]').val();
				var new_password = $('input[name=new_password]').val();
				var new_confirm_password = $('input[name=new_confirm_password').val();
				jQuery.ajax({
					method: 'POST',
				    url: "{{ url('change-password') }}",
					data: {
				        	"_token": "{{ csrf_token() }}",
				        	"first_name" : first_name,
				        	"last_name" : last_name,
				        	"email" : email,
				        	"current_password" : current_password,
				        	"new_password" : new_password,
				        	"new_confirm_password" : new_confirm_password
				    },
				        success : function (response) {
				        	console.log(response);
				        	if (response.success == true) {
				        		$('#password-match-fail').addClass('d-none');
				        		$('#errors_password_comfimation').addClass('d-none');
				        		$('#updated-success').html(response.msg);	
  								$('.user_names').html('');
  								$('.user_names').html(first_name + ' '+ ' ' +last_name);

  								$('.user_names_dashboard').html('');
  								$('.user_names_dashboard').html(first_name);
  								
  								$('#first_modal_name').val('');
  								$('#first_modal_name').val(first_name);
				        		

				        		$('#last_modal_name').val('');
  								$('#last_modal_name').val(last_name);

				        	}
				        	else {
				        		$('#password-match-fail').html('Current password is not valid');
				        	}

	    			},
	    			error: function (response) {
	    				console.log(response);
	    				if (response.responseJSON.errors) {
	    					var errors = response.responseJSON.errors.new_confirm_password[0];
	    				}
	    				$('#errors_password_comfimation').html(errors);

	    			}
				
	    		}


	    		);
	    	
		
			}
			function dashboard() {

				$('#intro').removeClass('d-none');
				$('#edit_address').addClass('d-none');
				$('#address_row').addClass('d-none');
				
				// $('#order_id').hide();
				$('.order-detail-container').addClass('d-none');
				$('#customer-address').addClass('d-none')
				$('#orders').addClass('d-none');
				// $('#orders').hide();
				// $('.intro').show();
			}

			function userOrderDetail(id) {
				$('#address_row').removeClass('d-none');
				$('#order_details').removeClass('d-none');
				$('#lineitems').removeClass('d-none');
				$('#order-detail-container').removeClass('d-none');
				$('#detail-heading').removeClass('d-none');

				// $('.order-detail-container').removeClass('d-none');
				// $('#order-detail-container').removeClass('d-none');
				var id = id;
				$('#orders').addClass('d-none');
				//$('.order')
				jQuery.ajax({
					url: "{{ url('/user-order-detail')}}"+"/"+id,
					method: 'GET',
					data: {
						id : id,
					
					},
					success: function(result){
						console.log(result.user_address)
							var order_id = '';
							order_id += 'Order #'+ '<strong>'+result.user_order.id+'</strong>'+' was placed on '+'<strong>'+result.user_order.createdDate +'</strong>'+' and is currently '+'<strong>'+result.user_order.status+'</strong>';
							$('#order_id').html(order_id);
							var lineitems = '';
							var product_total = '';
							var subtotal = 0;
							var retail_price = 1
							result.order_items.forEach(
								function(item, index){
									var product_total = item.product.retail_price * item.quantity;
									//var product_total = retail_price * item.quantity;
									subtotal = product_total + subtotal;
									lineitems +='<tr class="border-bottom table-row-content" style="height:70px"><td style="width:491px"><a href="">'+item.product.name+'</a>'+'<td class="cart-basket d-flex align-items-center justify-content-center float-sm-end quantity-counter rounded-circle mt-4">'+item.quantity+'</td><td></td><td class="table-order-number text-dark text-end">$'+item.product.retail_price * item.quantity+'</td></tr>';
									
								console.log(item.quantity)
								});

							lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">'+ 'Subtotal'+'</td><td></td><td></td><td class="table-order-number text-dark text-end">$'+subtotal+'</td></tr>';
							// lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">'+'<img src="theme/img/truck.png">'+' Shipping: <span class="shipping-content">Via UPS cround ( Estimated transit time of 3 - 5 business days. )</span>'+'</td><td><td></td></td><td class="table-order-number text-dark text-end">'+'$0.00'+'</td></tr>';
							// lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">'+'<img src="theme/img/arrow_1.png">'+' <span>Free shipping discount </span>'+'</td><td></td><td></td><td class="table-order-number text-dark text-end">'+'$0.00'+'</td></tr>';
							lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">'+'<img src="theme/img/arrow_1.png">'+' <span>Tax </span>'+'</td><td></td><td></td><td class="table-order-number text-dark text-end">'+'$0.00'+'</td></tr>';
						
							lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">'+'<img src="theme/img/arrow_1.png">'+' <span>Payment Method </span>'+'</td><td><td></td></td><td class="table-order-number text-dark text-end">'+result.user_order.paymentTerms+'</td><td class="table-order-number text-dark ">'+' '+'</td></tr>';
							lineitems += '<tr class="border-bottom" style="height:70px"><td class="table-row-content">  '+'   Total'+'</td><td></td><td></td><td class="table-order-number  text-end text-danger">$'+subtotal.toFixed(2)+'</td><td class="table-order-number text-dark">'+' '+'</td></tr>';
							var address = '';
							//address = result.user_address.firstName;
							address = '<span class="address-user-details">'+'<strong>'+result.user_address.firstName+'&nbsp'+result.user_address.lastName+'</strong>'+'</span>';
							address += '<span>  '+result.user_address.postalAddress1+'</span>';
							address += '<span>  '+result.user_address.postalAddress2+'</span>';	
							address += '<span>  '+result.user_address.postalCity+'</span>';
							address += '<span>  '+result.user_address.postalState+'</span><br>';
							address += '<span>' + '&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp '+result.user_address.postalPostCode+'</span><br>';
							address += '<span>  '+'<strong>'+result.user_address.mobile+'</strong>'+'</span>'+'        '+result.user_address.email;
							$('#lineitems').html(lineitems);
							$('#address_table').html(address);
							$('#shipping_table').html(address);

							// $("#detail-heading").slideUp();
							$([document.documentElement, document.body]).animate({
        					scrollTop: $("#main-row").offset().top
    						}, 1000);
						}});

			}
			function showOrders	() {
				$('#address_row').addClass('d-none');

				$('.nav-pills .active').removeClass('active');
				$('.nav-pills #recent_orders').addClass('active');
				//$(this).addClass("active");
		
				$('#customer-address').addClass('d-none');
				$('#order-detail-container').addClass('d-none');
				$('#edit_address').addClass('d-none');
				//let productId = $('#p_'+id).val();
				$('#intro').addClass('d-none');
				$('#orders').show();
				$('#orders').removeClass('d-none');
				$('#lineitems').addClass('d-none');
					 jQuery.ajax({
						url: "{{ url('/my-account/') }}",
						method: 'GET',	
						success: function(data){
							console.log(data);
							var res='';
							var total_items = 0;
						$.each (data, function (key, value) {
							var total_items=0;
							$.each(value.api_order_item, function(key, value) {
								console.log(value.quantity+'-----------'+value.order_id);
								total_items = value.quantity + total_items;
								
							});
							console.log(total_items);
							console.log(total_items);
							var temp = value.createdDate;
            				res +=
            					'<tr class="table-row-content border-bottom">'+
                					'<td class="table-order-number pl-0">#'+value.id+'</td>'+
                					'<td>'+temp+'</td>'+
                					'<td>'+value.status+'</td>'+
                					'<td><strong>'+'$'+value.total.toFixed(2)+'</strong>'+' For ('+total_items+' '+' items)'+'</td>'+
                					'<td class="pr-0">'+'<a onclick=userOrderDetail('+value.id+') onmouseover=replaceEye('+value.id+') onmouseout= replaceEye2('+value.id+');>'+'<button class="btn btn-outline-success view-btn p-0" type="" style="width:100%;height:32px;"><img src="theme/img/eye.png" class="mr-1 mb-1" id="eye_icon_'+value.id+'"></i>View</button>'+'</td></a>'+
           						'</tr>';

   								});
							//console.log(res);
						  $('#order_table').html(res);
						},					
	
					});
			} 

			function accountDetails() {
				$('#orders').addClass('d-none');
				$('#detail-heading').addClass('d-none');
				$('#order_details').addClass('d-none');
				$('#address_row').addClass('d-none');
				$('.nav-pills .active').removeClass('active');
				$('.nav-pills #account_details').addClass('active');
				$('#edit_address').addClass('d-none')
				$('#intro').addClass('d-none');
				$('#customer-address').removeClass('d-none');
				 jQuery.ajax({
						url: "{{ url('/user-addresses/') }}",
						method: 'GET',	
						success: function(data){
							console.log(data);
						},
				})
			}
			function edit_address() {
				$('#address_row').addClass('d-none');
				$('.nav-pills .active').removeClass('active');
				$('.nav-pills #current_address').addClass('active');
				// $('#customer-address').addClass('d-none');
				$('#customer-address').addClass('d-none');
				$('#edit_address').removeClass('d-none');
				$('#orders').addClass('d-none');
				$('#intro').addClass('d-none');
				$('#order-detail-container').addClass('d-none');
			}


			function updateContact(user_id) {

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
                	data: {
                	url: "{{ url('/user-addresses/') }}",

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

                		if (response.success == true) {
		                    $('.modal-backdrop').remove()
		                     $('#success_msg').removeClass('d-none');
		                    $('#success_msg').html(response.msg);
		                    window.location.reload();
                		}
            		},
            		error: function (response) {
  
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
<!-- Remove the container if you want to extend the Footer to full width. -->

@include('partials.product-footer')

<!-- End of .container -->
@include('partials.footer')