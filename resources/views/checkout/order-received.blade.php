@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
<style>
	.order-confirmation-page-table-data-heading,
	thead,
	tbody,
	tfoot,
	tr,
	td,
	th {
		border-color: inherit;
		border-style: solid;
		border-width: 0;
		border-color: #8C8C8C;
	}
	.media-1440 {
		display:none;
	}
	.small-media-view  {
		display:none;
	}
	.media_mobile  {
		display:none;
	}
	.prod-name-img {
		margin-left: -48px !important;
		margin-top:3px;
	}
	@media  screen and (min-width : 1406px ) and (max-width:2194px) {
		.media-1440 {
			display:flex;
		}

		.small-media-view  {
			display:none;
		}
		.main-view {
			display:none;
		}
		.media_mobile  {
			display:none;
		}
		.prod-name-img {
			margin-left: 0px !important;
		}
	}

	@media  screen and (min-width : 360px ) and (max-width:425px) { 
		.m_chechout_image {
			width: 57.08px !important;
			height: 57.08px !important;
		}
		.purchaseTable {
			margin: 0rem !important;
    		padding: 0rem !important;
		}
		.for_desktop {
			display:none;
		}
		.for_mobile {
			display:block !important;
		}
		.innerTable_mobile,tr, td, th {
			border:0px !important;
		}
		.border_bottom_mb {
			border-bottom:1px solid #DFDFDF !important;
		}
		.mobile_b_bottom {
			border-bottom:1px solid #DFDFDF !important;
		}
		.order-confirmation-page-view-invoice-button {
			margin-top: 10px !important;
		}
	}
	@media  screen and (min-width : 768px ) and (max-width:1405px) {
		.media-1440 {
			display:none;
		}

		.main-view {
			display:none;
		}

		.small-media-view  {
			display:flex;
		}
		.media_mobile  {
			display:none;
		}
		.prod-name-img {
			margin-left: 0px !important;
		}
	}
	
	/* mobile view  */

	@media  screen and (min-width : 375px ) and (max-width:767px) {
		.media-1440 {
			display:none;
		}

		.main-view {
			display:none;
		}

		.small-media-view  {
			display:none;
		}

		.media_mobile  {
			display:flex;
		}
		.prod-name-img {
			margin-left: 0px !important;
		}
	}
	@media  screen and (min-width : 375px ) and (max-width:700px) {
		.div_increase_mobile  {
			margin-left:0rem !important;
			margin-right:0rem !important;
			padding: 0rem !important;
			padding-left:0rem !important;
			padding-right:0rem !important;
		}

		.add_border {
			border:1px dashed #000 !important;
 		}
		
	}

	@media  screen and (min-width : 375px ) and (max-width:650px) {
		.mobile-font {
			font-weight: 600 !important;
			font-size: 20px !important;
		}
		.mobile_class {
			padding-left: 0px !important;
		}

		.main-thankyou-div {
			width:100% !important;
			padding: 0px !important;
			margin: 0px !important;
		}
		.thank-you-card-body {
			padding-left: 0.1rem !important;
			padding-right: 0.1rem !important;
			margin-top: 0rem !important;
			padding-top: 0rem !important;
		}
		.thank-you-card {
			margin-top: 0px !important;
		}
		.thank-you-title-top {
			margin-top: 0px !important;
		}
		.order-confirmation-page-title {
			margin-bottom: 0px !important;
			font-weight: 700 !important;
			font-size: 16px !important;
		}
		.mobile-font-part {
			font-weight:400 !important;
			font-size: 16px !important;
		}
		
		.shipping_mobile {
			margin-top: 2rem !important;
		}
		.order-confirmation-page-shipping-address {
			margin-bottom: 0px !important;
			padding-bottom: 20px !important;
		}
		.order-confirmation-page-billing-address {
			margin-bottom: 0px !important;
			padding-bottom: 20px !important;
		}

		.div_increase_mobile  {
			margin-left:0rem !important;
			margin-right:0.5rem !important;
			padding: 0rem !important;
			padding-left:0rem !important;
			padding-right:0rem !important;
		}

		.order-confirmation-page-second-row {
			padding-top: 2rem !important;
		}
		.order-confirmation-page-order-number-title {
			font-weight: 400 !important;
			font-size: 10px !important;
			margin-bottom: 5px !important;                                                                                        
		}
		.order-confirmation-page-order-number-item {
			font-weight: 600 !important;
			font-size: 30px !important;
		}
		.order-confirmation-page-date-title {
			font-weight: 400 !important;
			font-size: 16px !important;
			margin-bottom: 5px !important;
		}
		.order-confirmation-page-date-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}
		.order-confirmation-page-mobile-title {
			margin-bottom: 5px !important;
			font-weight: 400 !important;
			font-size: 16px !important;
		}
		.order-confirmation-page-mobile-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-email-title {
			margin-bottom: 5px !important;
			font-weight: 400 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-email-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-payment-method-title {
			margin-bottom: 5px !important;
			font-weight: 400 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-payment-method-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}


		.order-confirmation-page-shipping-title {
			margin-bottom: 5px !important;
			font-weight: 400 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-shipping-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-tax-title {
			margin-bottom: 5px !important;
			font-weight: 400 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-tax-item {
			font-weight: 600 !important;
			font-size: 16px !important;
		}

		.order-confirmation-page-total-title {
			font-size: 10px !important;
			margin-bottom: 5px !important;
			font-weight: 400 !important;
		}

		.order-confirmation-page-total-item {
			font-weight: 600 !important;
			font-size: 30px !important;
		}

		.order-confirmation-page-top-heading{
			margin-bottom: 0px !important;
		}
		.order-confirmation-page-first-name-last-name-user-name {
			font-size: 13.35px !important;
		}
		.order-confirmation-page-address-line-one-title {
			font-size: 13.35px !important;	
		}
		.order-confirmation-page-address-line-one-item {
			font-size: 13.35px !important;	
		}

		.order-confirmation-page-address-line-tow-title , .order-confirmation-page-city-name-title , .order-confirmation-page-state-name-title , .order-confirmation-page-zip-name-title  {
			font-size: 13.35px !important;	
		}
		.order-confirmation-page-address-line-tow-item , .order-confirmation-page-city-name-item ,  .order-confirmation-page-state-name-item, .order-confirmation-page-zip-name-item{
			font-size: 13.35px !important;	
		}
		.order-confirmation-page-product-category-name {
			font-size: 12px !important;
		}
		.order-confirmation-page-product-price {
			font-size: 16px !important;
		}
		.purchase-title-mobile {
			margin-top: 0rem !important;
		}
		.order-confirmation-page-view-invoice-button {
			font-family: 'Roboto' !important;
			font-style: normal !important;
			font-weight: 500 !important;
			font-size: 16px !important;
			line-height: 19px !important;
			letter-spacing: 0.01em !important;
		}
		
	}

	
</style>
{{session()->forget('cart');}}

<div class="container-fluid main-thankyou-div" style="width:89%;">
	<div class="">
		<div class="col-md-12">
			<div class="card mt-5 border-0 thank-you-card">
				<div class="card-body ps-5 mt-5  thank-you-card-body">
					<div class="row ps-5 mobile_class">
						<div class=" col-xl-12 col-lg-12 col-md-12 col-sm-12">
							<p class="order-confirmation-page-top-heading mobile-font">Order Confirmation</p>
						</div>
						<div class="col-md-12 mt-4 thank-you-title-top">
							<p class="order-confirmation-page-title">
								{{$order_contact->firstName}}
								{{$order_contact->lastName}}
								<span class="order-confirmation-page-user-name mobile-font mobile-font-part">Your order has been received.</span>
							</p>
						</div>
					</div>
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row main-view " style=" padding-top: 50px !important;">
						<div class=" col-xl-1 col-lg-4 col-md-6 col-sm-12" style="margin-left: 32px;">
							<p class="order-confirmation-page-order-number-title">Order Number</p>
							<p class="order-confirmation-page-order-number-item">
								{{$order->apiOrderItem[0]['order_id']}}
							</p>
						</div>
						<div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 ps-4 ms-3">
							<p class="order-confirmation-page-date-title">Date</p>
							<p class="order-confirmation-page-date-item">
								{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
							</p>
						</div>
						<div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 pe-2 ms-3">
							<p class="order-confirmation-page-mobile-title">
								Mobile
							</p>
							<p class="order-confirmation-page-mobile-item">
								{{$order_contact->phone}}
							</p>
						</div>
						<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 ps-5">
							<p class="order-confirmation-page-email-title">
								Email
							</p>
							<p class="order-confirmation-page-email-item">
								{{$order->user->email}}
							</p>
						</div>
						<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
							<p class="order-confirmation-page-payment-method-title">Payment Method</p>
							<p class="order-confirmation-page-payment-method-item">{{$order->paymentTerms}}</p>
						</div>
						<div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 ps-0">
							<p class="order-confirmation-page-shipping-title">Shipping</p>
							<p class="order-confirmation-page-shipping-item">$</p>
						</div>
						<div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 ms-4">
							<p class="order-confirmation-page-tax-title">Tax</p>
							<p class="order-confirmation-page-tax-item">
								$
							</p>
						</div>
						<div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 ms-4">
							<p class="order-confirmation-page-total-title">Total</p>
							<p class="order-confirmation-page-total-item">
								${{number_format($order->total, 2)}}
							</p>
						</div>
					</div>
					{{-- for media screen 1440px --}}
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row media-1440" style="padding-top: 50px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-date-title">Date</p>
									<p class="order-confirmation-page-date-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-mobile-title">
										Mobile
									</p>
									<p class="order-confirmation-page-mobile-item">
										{{$order_contact->phone}}
									</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-email-title">
										Email
									</p>
									<p class="order-confirmation-page-email-item">
										{{$order->user->email}}
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3">
									<p class="order-confirmation-page-payment-method-title">Payment Method</p>
									<p class="order-confirmation-page-payment-method-item">{{$order->paymentTerms}}</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-shipping-title">Shipping</p>
									<p class="order-confirmation-page-shipping-item">$</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-tax-title">Tax</p>
									<p class="order-confirmation-page-tax-item">
										$
									</p>
								</div>
								<div class="col-md-3">
									<p class="order-confirmation-page-total-title">Total</p>
									<p class="order-confirmation-page-total-item">
										${{number_format($order->total, 2)}}
									</p>
								</div>
							</div>
						</div>
					</div>
					{{-- for media screen 1440px end --}}

					{{-- for media screen 768 1406 --}}
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row small-media-view " style="padding-top: 50px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-date-title">Date</p>
									<p class="order-confirmation-page-date-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-mobile-title">
										Mobile
									</p>
									<p class="order-confirmation-page-mobile-item">
										{{$order_contact->phone}}
									</p>
								</div>
								
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<p class="order-confirmation-page-email-title">
										Email
									</p>
									<p class="order-confirmation-page-email-item">
										{{$order->user->email}}
									</p>
								</div>

								<div class="col-md-4">
									<p class="order-confirmation-page-payment-method-title">Payment Method</p>
									<p class="order-confirmation-page-payment-method-item">{{$order->paymentTerms}}</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-shipping-title">Shipping</p>
									<p class="order-confirmation-page-shipping-item">$</p>
								</div>
								
								
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								
								<div class="col-md-4">
									<p class="order-confirmation-page-tax-title">Tax</p>
									<p class="order-confirmation-page-tax-item">
										$
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-total-title">Total</p>
									<p class="order-confirmation-page-total-item">
										${{number_format($order->total, 2)}}
									</p>
								</div>
							</div>
						</div>
					</div>
					{{-- for media screen 768 above end --}}

					{{-- for media screen mobile --}}
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row media_mobile div_increase_mobile add_border">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-6">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="col-sm-6">
									<p class="order-confirmation-page-date-title">Date</p>
									<p class="order-confirmation-page-date-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								
								
							</div>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-6">
									<p class="order-confirmation-page-mobile-title">
										Mobile
									</p>
									<p class="order-confirmation-page-mobile-item">
										{{$order_contact->phone}}
									</p>
								</div>
								<div class="col-sm-6">
									<p class="order-confirmation-page-email-title">
										Email
									</p>
									<p class="order-confirmation-page-email-item">
										{{$order->user->email}}
									</p>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-6">
									<p class="order-confirmation-page-payment-method-title">Payment Method</p>
									<p class="order-confirmation-page-payment-method-item">{{$order->paymentTerms}}</p>
								</div>
								<div class="col-sm-6">
									<p class="order-confirmation-page-shipping-title">Shipping</p>
									<p class="order-confirmation-page-shipping-item">$</p>
								</div>
								
							</div>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-6">
									<p class="order-confirmation-page-tax-title">Tax</p>
									<p class="order-confirmation-page-tax-item">
										$
									</p>
								</div>
								<div class="col-sm-6">
									<p class="order-confirmation-page-total-title">Total</p>
									<p class="order-confirmation-page-total-item">
										${{number_format($order->total, 2)}}
									</p>
								</div>
							</div>
						</div>
					</div>
					{{-- for media mobile  end --}}
					<div class="row">
						<div class="col-md-12">
							<div class="row  ms-5 p-4 me-5 order-confirmation-page-second-row div_increase_mobile" style="padding-top: 5rem!important;">
								<div class="col-md-6">
									<p class="order-confirmation-page-billing-address mobile-font">
										Billing Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<p class="order-confirmation-page-first-name-last-name-user-name pt-3">
												{{$order_contact->firstName}}
												{{$order_contact->lastName}}
											</p>
											<div class="row">
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-one-title">
														Address line 1
													</p>
													@if (!$order_contact->address1)
													<p class="order-confirmation-page-address-line-one-item">
														Addressline1 empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-one-item">
														{{$order_contact->address1}}
													</p>
													@endif

													<p class="order-confirmation-page-address-line-tow-title">
														Address line 2
													</p>
													@if ($order_contact->address2)
													<p class="order-confirmation-page-address-line-tow-item">
														{{$order_contact->address2}}
													</p>
													@endif
													<p class="order-confirmation-page-address-line-tow-item">
														Addressline2 empty
													</p>
													
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-city-name-title">City</p>
															@if (!$order_contact->city)
															<p class="order-confirmation-page-city-name-item">
																City empty
															</p>
															@else
															<p class="order-confirmation-page-city-name-item">
																{{$order_contact->city}}
															</p>
															@endif
														</div>
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-state-name-title">State
															</p>
															@if (!$order_contact->state)
															<p class="order-confirmation-page-state-name-item">
																State empty
															</p>
															@else
															<p class="order-confirmation-page-state-name-item">
																{{$order_contact->state}}
															</p>
															@endif
														</div>
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-zip-name-title">Zip</p>

															@if (!$order_contact->postCode)
															<p class="order-confirmation-page-zip-name-item">
																zip empty
															</p>
															@else
															<p class="order-confirmation-page-zip-name-item">

																{{$order_contact->postCode}}
															</p>
															@endif
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 shipping_mobile">
									<p class="order-confirmation-page-shipping-address mobile-font">
										Shipping Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<p class="order-confirmation-page-first-name-last-name-user-name pt-3">
												{{$order_contact->firstName}}
												{{$order_contact->lastName}}
											</p>
											<div class="row">
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-one-title">
														Address line 1
													</p>
													@if (!$order_contact->postalAddress1)
													<p class="order-confirmation-page-address-line-one-item">
														Address Line 1 Empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-one-item">
														{{$order_contact->postalAddress1}}
													</p>
													@endif

													<div class="row mt-4">
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-city-name-title">City</p>
															@if (!$order_contact->postalCity)
															<p class="order-confirmation-page-city-name-item">
																City empty
															</p>
															@else
															<p class="order-confirmation-page-city-name-item">
																{{$order_contact->postalCity}}
															</p>
															@endif
														</div>
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-state-name-title">State
															</p>
															@if (!$order_contact->postalState)
															<p class="order-confirmation-page-state-name-item">
																State empty
															</p>
															@else
															<p class="order-confirmation-page-state-name-item">
																{{$order_contact->postalState}}
															</p>
															@endif
														</div>
														<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
															<p class="order-confirmation-page-zip-name-title">Zip</p>
															@if (!$order_contact->postalPostCode)
															<p class="order-confirmation-page-zip-name-item">
																zip empty
															</p>
															@else
															<p class="order-confirmation-page-zip-name-item">
																{{$order_contact->postalPostCode}}
															</p>
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-tow-title">
														Address line 2
													</p>
													@if (!$order_contact->postalAddress2)
													<p class="order-confirmation-page-address-line-tow-item">
														Address Line 2 Empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-tow-item">
														{{$order_contact->postalAddress2}}
													</p>
													@endif

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row ps-5 mt-5 pe-5 div_increase_mobile purchase-title-mobile">
						<div class="col-md-12 mt-5 mobile_b_bottom">
							<p class="order-confirmation-page-item-purchased-title mobile-font ">Item Purchased </p>
						</div>
						<div class="col-md-12 mt-5 for_desktop">
							<table class="table">
								<tr>
									<th class="order-confirmation-page-table-data-heading">Name</th>
									<th class="order-confirmation-page-table-data-heading"
										style="padding-left: 0px; !important">
										Quantity</th>
									{{-- <th class="order-confirmation-page-table-data-heading">Shipping</th> --}}
									<th class="order-confirmation-page-table-data-heading">Price</th>
								</tr>
								<tbody class="border-0">
									@foreach($order->apiOrderItem as $item)
									@foreach($item->product->options as $option)
									<tr>
										<td>
											<div class="row">
												<div class="col-md-2 py-2 mobile_thankyou_img_div">
													@if ($option->image)
													<img class="img-fluid img-thumbnail m_chechout_image" src="{{$option->image}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" alt="" width="80px">
													@endif
												</div>
												<div class="col-md-5 py-2 ps-0 prod-name-img mobile_text_class" style="">
													<a class="order-confirmation-page-product-category-name pb-3"
														href=" {{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
														{{$item->product->name}}
													</a>
													<br>
													<p class="order-confirmation-page-product-title">Title:<span
															class="order-confirmation-page-product-item">
															{{$item->product->name}}</span>
													</p>
												</div>
											</div>
										</td>
										{{-- <td>Shipping</td> --}}
										<td>
											<div class="row">
												<div class="col-md-12">
													<p class="pt-4 order-confirmation-page-product-quantity">
														{{$item->quantity}}</p>
												</div>
											</div>
										</td>
										<td>
											<p class="pt-4 order-confirmation-page-product-price">
												${{number_format($item->price,2)}}</p>
										</td>
									</tr>
									@endforeach
									@endforeach
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-2 m-auto">
									<a href="#" class="order-confirmation-page-view-invoice-button btn w-100">VIEW
										INVOICE</a>
								</div>
							</div>
						</div>

						{{-- data show for mobile --}}
						<div class="col-md-12 mt-5 purchaseTable for_mobile d-none">
							<table class="table main_table_mobile">
								{{-- <tr>
									<th class="order-confirmation-page-table-data-heading">Image</th>
									<th class="order-confirmation-page-table-data-heading"
										style="padding-left: 0px; !important">Name</th>
										<th class="order-confirmation-page-table-data-heading"
										style="padding-left: 0px; !important">
										Quantity</th>
									<th class="order-confirmation-page-table-data-heading">Price</th>
								</tr> --}}
								<tbody class="border-0">
									@foreach($order->apiOrderItem as $item)
									
										@foreach($item->product->options as $option)
										
										<tr class="border_bottom_mb">
											<td style="width: 20% !important;">
												<div class="py-2 mobile_thankyou_img_div">
													@if ($option->image)
													<img class="img-fluid img-thumbnail m_chechout_image" src="{{$option->image}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" class="m_chechout_image" alt="" width="80px">
													@endif
												</div>
											</td>
											<td style="width:80%;">
												<div class="ps-0 mobile_text_class mt-1" style="">
													<p class="order-confirmation-page-product-title">
														<a class="order-confirmation-page-product-category-name pb-3"
															href=" {{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
															{{$item->product->name}}
														</a>
													</p>
												</div>
												<p class=" mb-0 order-confirmation-page-product-price text-right"> ${{number_format($item->price,2)}}</p>
											</td>
										</tr>
										@endforeach
									@endforeach
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-2 m-auto">
									<a href="#" class="order-confirmation-page-view-invoice-button btn w-100 text-uppercase">View Invoice</a>
								</div>
							</div>
						</div>
						{{-- data show for mobile end --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('partials.product-footer')
@include('partials.footer')