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
	.view-inv-btn {
		width: 311px !important;
		border-radius: 5px !important;
		color: #FFF !important;
		font-family: 'poppins' !important;
		font-size: 18px !important;
		font-style: normal !important;
		font-weight: 600 !important;
		line-height: normal !important;
		letter-spacing: 0.18px !important;
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
	.order-confirmation-page-shipping-address  , .order-confirmation-page-billing-address {
		padding-bottom: 0px !important;
	}
	.order-confirmation-page-second-row {
		background:#F9FAFB;
	}
	.order-confirmation-page-invoice-row {
		border-radius: 6px;
		border: 1px dashed #000;
	}
	@media  screen and (min-width : 1406px ) and (max-width:2194px) {
		.media-1440 {
			display:block;
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

	@media  screen and (min-width : 280px ) and (max-width:550px) { 
		.orderSummarymbl {
			background: #FAFAFA;
			border-top-right-radius: 5.24485px;
			border-top-left-radius: 5.24485px;
		}
		.suborderSummarymbl_main {
			background: #FAFAFA;
		}
		.suborderSummarymbl{
			background: #F7F7F7;
			border-radius: 5.24485px;
		}
		.delievery_options_mbl {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 18.8814px;
			line-height: 22px;

			color: #000000;

		}
		.summary_sub_total_head {
			
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 400;
			font-size: 16.7835px;
			line-height: 20px;
			color: #303030;

		}
		.summary_sub_total_price {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 16.7835px;
			line-height: normal;
			color: #000000;

		}
		.local_order {
			background: #E6F6FF;
			border-radius: 5.24485px;
		}
		.label_delievery {
			font-family: 'Poppins';
			font-style: normal;
			font-weight: 400;
			font-size: 14.6856px;
			line-height: 22px;
			color: #303030;
			vertical-align: middle;
		}
		.radio_delievery {
			width: 14.69px;
			height: 14.69px;
			vertical-align: middle;
		}
		.radio_selected{
			background: #008BD3;
			border-radius: 8px;
		}
		.radio_not_selected{
			background: #D9D9D9;
			border-radius: 8px;
		}
		.summary_total_price {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 600;
			font-size: 24px;
			line-height: normal;
			color: #000000;
		}
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

	@media screen and (max-width:550px) and (min-width: 376px) {
		.logo-confirm-div-1 {
			width: 100%;
			display:flex;
			justify-content: center;
		}
		.logo-confirm-div-2 {
			width: 100%;
			text-align: center;
			margin-left: 0rem !important;
		}
		.main-logo-confirm-div {
			width: 100%;
			display: block !important;
			margin-top: 1.5rem;

		}
		.confirmation_check {
			position: absolute;
			top: 10%;
		}
	}

	@media screen and (max-width:375px) and (min-width: 280px) {
		.logo-confirm-div-1 {
			width: 100%;
			display:flex;
			justify-content: center;
		}
		.logo-confirm-div-2 {
			width: 100%;
			text-align: center;
			margin-left: 0rem !important;
		}
		.main-logo-confirm-div {
			width: 100%;
			display: block !important;
			margin-top: 1.5rem;

		}
		.confirmation_check {
			position: absolute;
			top: 10%;
		}
	}
	
	/* mobile view  */
	@media  screen and (min-width : 280px ) and (max-width:350px) {
		.label_delievery {
			font-size: 11px !important;
		}
		.summary_total_price {
			font-size: 20px !important;
		}
	}
	@media  screen and (min-width : 280px ) and (max-width:767px) {
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
	@media  screen and (min-width : 280px ) and (max-width:700px) {
		.div_increase_mobile  {
			margin-left:0rem !important;
			margin-right:0rem !important;
			padding: 0rem !important;
			padding-left:0rem !important;
			padding-right:0rem !important;
		}

		.add_border {
			border:none;
 		}
		
	}

	@media  screen and (min-width : 280px ) and (max-width:650px) {
		.for_mobile_spacing {
			margin-top: 1rem;
		}
		.add_dashed_border {
			border-bottom: 1px dashed #DFDFDF !important;
		}
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
			font-weight: 600 !important;
			font-size: 24px !important;
			line-height: 32px; /* 133.333% */
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
			margin-right:0rem !important;
			padding: 0rem !important;
			padding-left:0rem !important;
			padding-right:0rem !important;
		}

		.order-confirmation-page-second-row {
			padding-top: 1rem !important;
		}
		.order-confirmation-page-order-number-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;                                                                                    
		}
		.order-confirmation-page-order-number-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;
			margin-top: 0px;  
		}
		.order-confirmation-page-date-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;

		}
		.order-confirmation-page-date-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;
			margin-top: 0px;  
		}
		.order-confirmation-page-mobile-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}
		.order-confirmation-page-mobile-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
		}

		.order-confirmation-page-email-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}

		.order-confirmation-page-email-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
		}

		.order-confirmation-page-payment-method-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}

		.order-confirmation-page-payment-method-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
		}


		.order-confirmation-page-shipping-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}

		.order-confirmation-page-shipping-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
		}

		.order-confirmation-page-tax-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}

		.order-confirmation-page-tax-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
		}

		.order-confirmation-page-total-title {
			font-weight: 400 !important;
			font-size: 13px !important;
			color: #707070;
			font-family: 'Poppins';
			font-size: 13px;
			font-style: normal;
		}

		.order-confirmation-page-total-item {
			font-weight: 500 !important;
			font-size: 13px !important;
			font-family: 'Poppins';
			font-style: normal;
			color: #121212;
			line-height: 18px;  
			margin-top: 0px;
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

	@media only screen and (min-width: 1921px) {
		.confirmation_check {
			position: absolute;
			left: 2%;
			top: 16%;
		}
	}
	
	@media only screen and (max-width: 1920px) and (min-width: 1441px) {
		.confirmation_check {
			position: absolute;
			top: 16%;
			left: 3.5%;
		}
	}
	@media only screen and (max-width: 1440px) and (min-width: 1025px) {
		.confirmation_check {
			position: absolute;
			left: 2.8%;
			top: 16%;
		}
	}
	@media only screen and (max-width: 1024px) and (min-width: 769px) {
		.confirmation_check {
			position: absolute;
			left: 5.5%;
			top: 16%;
		}
	}
	@media only screen and (max-width: 768px) and (min-width: 600px) {
		.confirmation_check {
			position: absolute;
			left: 8%;
			top: 15%;
		}
	}
	@media only screen and (max-width: 599px) and (min-width: 426px) {
		.confirmation_check {
			position: absolute;
			left: 46%;
			top: 10%;
		}
	}
	.name_title {
		padding-bottom: 0px;
	}
	.order-confirm-top {
		color: #474747;
		font-family: 'Poppins';
		font-size: 21.538px;
		font-style: normal;
		font-weight: 400;
		line-height: 32.308px; /* 150% */
	}
	.main-color-div {
		background: #F9FAFB;
		border-top-left-radius: 15px;
		border-top-right-radius: 15px;
	}
	
</style>
{{session()->forget('cart');}}

<div class="container-fluid main-thankyou-div" style="width:89%;">
	<div class="">
		<div class="col-md-12">
			<div class="card mt-5 border-0 thank-you-card">
				<div class="card-body ps-5 mt-5  thank-you-card-body">
					@if (\Session::has('success'))
						<div class="alert alert-success alert-dismissible fade show mb-4 mx-5" role="alert">
							{!! \Session::get('success') !!}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						@elseif (\Session::has('error'))
						<div class="alert alert-danger alert-dismissible fade show mb-4 mx-5" role="alert">
							{!! \Session::get('error') !!}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					@endif
					<div class="row ps-5 mobile_class">
						<div class="row mb-3 align-items-start">
							<input type="hidden" value="{{!empty($order_contact) && !empty($order_contact->email) ? $order_contact->email : ''}}" id="order_contact_email">
							<input type="hidden" value="{{!empty($order) && !empty($order->apiOrderItem) ? $order->apiOrderItem : ''}}" id="order_Items_ty">
							<div class="col-12 col-xl-9 col-md-7 d-flex main-logo-confirm-div">
								<div class="logo-confirm-div-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="76" height="76" viewBox="0 0 76 76" fill="none">
										<circle cx="37.6923" cy="37.923" r="37.6923" fill="#7CC633" fill-opacity="0.12"/>
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 44 44" fill="none" class="confirmation_check">
										<path d="M22.019 3.9743C12.1292 3.9743 4.07025 12.0333 4.07025 21.923C4.07025 31.8128 12.1292 39.8717 22.019 39.8717C31.9087 39.8717 39.9677 31.8128 39.9677 21.923C39.9677 12.0333 31.9087 3.9743 22.019 3.9743ZM30.5985 17.7948L20.4215 27.9717C20.1702 28.223 19.8292 28.3666 19.4703 28.3666C19.1113 28.3666 18.7703 28.223 18.519 27.9717L13.4395 22.8923C12.919 22.3717 12.919 21.5102 13.4395 20.9897C13.96 20.4692 14.8215 20.4692 15.342 20.9897L19.4703 25.1179L28.6959 15.8923C29.2164 15.3717 30.0779 15.3717 30.5985 15.8923C31.119 16.4128 31.119 17.2564 30.5985 17.7948Z" fill="#7BC533"/>
									</svg>
								</div>
								<div class="d-flex mx-4 mt-2 logo-confirm-div-2" style="flex-direction: column;">
									<div class="">
										<span class="order-confirmation-page-user-name mobile-font mobile-font-part order-confirm-top">Order Confirmation</span>
									</div>
									<div class="">
										<p class="order-confirmation-page-title name_title">
											@if (!empty($order->BillingFirstName))
												{{$order->BillingFirstName}} {{$order->BillingLastName}}
											@elseif (!empty($order->BillingLastName)) 
												 {{$order->BillingFirstName}} {{$order->BillingLastName}}	
											@else
												{{$order_contact->firstName}} {{$order_contact->lastName}}
											@endif
										</p>
									</div>
								</div>
							</div>
							@if (!empty($enable_reminders) && strtolower($enable_reminders->option_value) == 'yes')
							<div class="col-12 col-xl-3 col-md-5 text-center my-3 my-md-0">
								<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reminderModal">
									Set Re-Order Reminder
								</button>
							</div>
							@endif
						</div>
					</div>
					<div class="p-3 ms-5 me-5 main-color-div main-view ">
						<div class="row ms-1 p-4 me-1 order-confirmation-page-invoice-row " style=" padding-top: 50px !important;">
							<div class=" col-md-3">
								<p class="order-confirmation-page-order-number-title">Order Number</p>
								<p class="order-confirmation-page-order-number-item getorderID">
									{{$order->apiOrderItem[0]['order_id']}}
								</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">Date</p>
								<p class="order-confirmation-page-order-number-item">
									{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
								</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">
									Mobile
								</p>
								<p class="order-confirmation-page-order-number-item">
									{{$order_contact->phone}}
								</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">
									Email
								</p>
								<p class="order-confirmation-page-order-number-item">
									{{$order_contact->email}}
								</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">Payment Method</p>
								<p class="order-confirmation-page-order-number-item">{{$order->logisticsCarrier}}</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">Shipping</p>
								<p class="order-confirmation-page-order-number-item">${{number_format($order->shipment_price , 2)}}</p>
							</div>
							
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">Tax</p>
								<p class="order-confirmation-page-order-number-item">
									{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price, 2) + $order->discount_amount }} --}}
									${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
								</p>
							</div>
							<div class="col-md-3">
								<p class="order-confirmation-page-order-number-title">Discount</p>
								<p class="order-confirmation-page-order-number-item">
									{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price, 2) + $order->discount_amount }} --}}
									{{-- ${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }} --}}
									@if (!empty($order->buylist_id))
										${{ !empty($order->buylist_discount) ? number_format($order->buylist_discount, 2) : '0.00' }}
									@else
										${{ !empty($order->discount_amount) ? number_format($order->discount_amount, 2) : '0.00' }}
									@endif
								</p>
							</div>
							<div class="col-md-3">
								<input type="hidden" name="getorderTotal" value="{{number_format($order->total_including_tax, 2)}}" class="getorderTotal" id="">
								<input type="hidden" name="is_stripe" value="{{$order->is_stripe}}" class="isStripe" id="isStripe">
								<p class="order-confirmation-page-order-number-title">Total</p>
								<p class="order-confirmation-page-order-number-item">
									${{number_format($order->total_including_tax, 2)}}
								</p>
							</div>
						</div>
					</div>
					{{-- for media screen 1440px --}}
					<div class="p-3 ms-5 me-5 main-color-div media-1440">
						<div class="row ms-1 p-4 me-1 order-confirmation-page-invoice-row " style="padding-top: 50px !important;">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Order Number</p>
										<p class="order-confirmation-page-order-number-item">
											{{$order->apiOrderItem[0]['order_id']}}
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Date</p>
										<p class="order-confirmation-page-order-number-item">
											{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">
											Mobile
										</p>
										<p class="order-confirmation-page-order-number-item">
											{{$order_contact->phone}}
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">
											Email
										</p>
										<p class="order-confirmation-page-order-number-item">
											{{$order_contact->email}}
										</p>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Payment Method</p>
										<p class="order-confirmation-page-order-number-item">{{$order->logisticsCarrier}}</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Shipping</p>
										<p class="order-confirmation-page-order-number-item">${{number_format($order->shipment_price , 2)}}</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Tax</p>
										<p class="order-confirmation-page-order-number-item">
											${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Discount</p>
										<p class="order-confirmation-page-order-number-item">
											@if (!empty($order->buylist_id))
												${{ !empty($order->buylist_discount) ? number_format($order->buylist_discount, 2) : '0.00' }}
											@else
												${{ !empty($order->discount_amount) ? number_format($order->discount_amount, 2) : '0.00' }}
											@endif
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Total</p>
										<p class="order-confirmation-page-order-number-item">
											${{number_format($order->total_including_tax, 2)}}
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{-- for media screen 1440px end --}}

					{{-- for media screen 768 1406 --}}
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row small-media-view " style="padding-top: 50px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Date</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">
										Mobile
									</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order_contact->phone}}
									</p>
								</div>
								
							
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">
										Email
									</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order_contact->email}}
									</p>
								</div>

								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Payment Method</p>
									<p class="order-confirmation-page-order-number-item">{{$order->logisticsCarrier}}</p>
								</div>
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Shipping</p>
									<p class="order-confirmation-page-order-number-item">${{number_format($order->shipment_price , 2)}}</p>
								</div>
								
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Tax</p>
									<p class="order-confirmation-page-order-number-item">
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price  , 2) }} --}}
										${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
									</p>
								</div>
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Discount</p>
									<p class="order-confirmation-page-order-number-item">
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price  , 2) }} --}}
										@if (!empty($order->buylist_id))
											${{ !empty($order->buylist_discount) ? number_format($order->buylist_discount, 2) : '0.00' }}
										@else
											${{ !empty($order->discount_amount) ? number_format($order->discount_amount, 2) : '0.00' }}
										@endif
									</p>
								</div>
								<div class="col-md-6 col-lg-4">
									<p class="order-confirmation-page-order-number-title">Total</p>
									<p class="order-confirmation-page-order-number-item">
										${{number_format($order->total_including_tax, 2)}}
									</p>
								</div>
							</div>
						</div>
					</div>
					{{-- for media screen 768 above end --}}

					{{-- for media screen mobile --}}
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row media_mobile div_increase_mobile add_border">
						<div class="col-sm-12 add_dashed_border">
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-date-title">Date</p>
									<p class="order-confirmation-page-date-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-mobile-title">
										Mobile
									</p>
									<p class="order-confirmation-page-mobile-item">
										{{$order_contact->phone}}
									</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-email-title">
										Email
									</p>
									<p class="order-confirmation-page-email-item">
										{{$order_contact->email}}
									</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-payment-method-title">Payment Method</p>
									<p class="order-confirmation-page-payment-method-item">{{$order->logisticsCarrier}}</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-shipping-title">Shipping</p>
									<p class="order-confirmation-page-shipping-item">${{number_format($order->shipment_price , 2)}}</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-tax-title">Tax</p>
									<p class="order-confirmation-page-tax-item">
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price , 2) }} --}}
										${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
									</p>
								</div>
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-tax-title">Discount</p>
									<p class="order-confirmation-page-tax-item">
										@if (!empty($order->buylist_id))
											${{ !empty($order->buylist_discount) ? number_format($order->buylist_discount, 2) : '0.00' }}
										@else
											${{ !empty($order->discount_amount) ? number_format($order->discount_amount, 2) : '0.00' }}
										@endif
									</p>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="order-confirmation-page-total-title">Total</p>
									<p class="order-confirmation-page-total-item">
										${{number_format($order->total_including_tax, 2)}}
									</p>
								</div>
							</div>
						</div>
					</div>
					{{-- for media mobile  end --}}
					<div class="row for_mobile_spacing">
						<div class="col-md-12">
							<div class="row  ms-5 p-4 me-5 order-confirmation-page-second-row div_increase_mobile">
								<div class="col-md-6">
									<p class="order-confirmation-page-billing-address mobile-font">
										Billing Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<p class="order-confirmation-page-address-line-one-title mb-1">
														@if (!empty($order->BillingAddress1))
															{{$order->BillingAddress1 . ','}}
														@else
															{{$order_contact->postalAddress1 ? $order_contact->postalAddress1 . ',' : ''}}
														@endif
													</p>
													<p class="order-confirmation-page-address-line-one-title mb-1">
														@if (!empty($order->BillingAddress2))
															{{$order->BillingAddress2 . ','}}
														@else
															{{$order_contact->postalAddress2 ? $order_contact->postalAddress2 . ',' : ''}}
														@endif
													</p>
													<p class="order-confirmation-page-address-line-one-title">
														@if (!empty($order->BillingCity))
															{{$order->BillingCity . ','}}
														@else
															{{$order_contact->postalCity ? $order_contact->postalCity . ',' : ''}}
														@endif

														@if (!empty($order->BillingState))
															{{$order->BillingState . ','}}
														@else
															{{$order_contact->postalState ? $order_contact->postalState . ',' : ''}}
														@endif

														@if (!empty($order->BillingZip))
															{{$order->BillingZip}}
														@else
															{{$order_contact->postalPostCode ? $order_contact->postalPostCode : ''}}
														@endif
													</p>
													
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
											<div class="row">
												<div class="col-md-12">
													<p class="order-confirmation-page-address-line-one-title mb-1">
														@if (!empty($order->DeliveryAddress1))
															{{$order->DeliveryAddress1 . ','}}
														@else
															{{$order_contact->address1 ? $order_contact->address1 . ',' : ''}}
														@endif
													</p>
													<p class="order-confirmation-page-address-line-one-title mb-1">
														@if (!empty($order->DeliveryAddress2))
															{{$order->DeliveryAddress2 . ','}}
														@endif
													</p>
													<p class="order-confirmation-page-address-line-one-title">
														@if (!empty($order->DeliveryCity))
															{{$order->DeliveryCity . ','}}
														@endif

														@if (!empty($order->DeliveryState))
															{{$order->DeliveryState . ','}}
														@else
															{{$order_contact->state ? $order_contact->state . ',' : ''}}
														@endif

														@if (!empty($order->DeliveryZip))
															{{$order->DeliveryZip}}
														@else
															{{$order_contact->postCode ? $order_contact->postCode : ''}}
														@endif
													</p>
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
							<p class="order-confirmation-page-item-purchased-title mobile-font ">Item(s) Purchased </p>
						</div>
						<div class="col-md-12 mt-5 for_desktop">
							<table class="table">
								<tr>
									<th class="order-confirmation-page-table-data-heading">Name</th>
									<th class="order-confirmation-page-table-data-heading">Sku</th>
									<th class="order-confirmation-page-table-data-heading"
										style="padding-left: 0px; !important">
										Quantity</th>
									<th class="order-confirmation-page-table-data-heading">Price</th>
								</tr>
								<tbody class="border-0">
									@foreach($orderitems as $orderitem)
									@if (!empty($orderitem->product_option) && !empty($orderitem->product))
									<tr>
										<td>
											<div class="row align-items-center">
												<div class="col-md-2 py-2 mobile_thankyou_img_div">
													{{-- @if ($orderitem->product_option->image)
													<img class="img-fluid img-thumbnail m_chechout_image" src="{{$orderitem->product_option->image}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" alt="" width="80px">
													@endif --}}
													@if (!empty($orderitem->product->images))
														<img class="img-fluid img-thumbnail m_chechout_image" src="{{$orderitem->product->images}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" alt="" width="80px">
													@endif
												</div>
												<div class="col-md-5 py-2 ps-0 prod-name-img mobile_text_class" style="">
													<a class="order-confirmation-page-product-category-name pb-3"
														href=" {{ url('product-detail/'. $orderitem->product->id.'/'.$orderitem->product_option->option_id.'/'.$orderitem->product->slug) }}">
														{{$orderitem->product->name}}
													</a>
												</div>
											</div>
										</td>
										<td class="align-middle">
											<div class="row">
												<div class="col-md-12">
													<p class="mb-0 order-confirmation-page-product-quantity">
														{{$orderitem->product->code}}</p>
												</div>
											</div>
										</td>
										<td class="align-middle">
											<div class="row">
												<div class="col-md-12">
													<p class="mb-0 order-confirmation-page-product-quantity">
														{{$orderitem->quantity}}</p>
												</div>
											</div>
										</td>
										<td class="align-middle">
											<p class="mb-0 order-confirmation-page-product-price">
												${{number_format($orderitem->price,2)}}</p>
										</td>
									</tr>
									@endif
									@endforeach
								</tbody>
							</table>
							<div class="row justify-content-center">
								<div class="col-md-4 d-flex justify-content-center">
									<a href="#" class="order-confirmation-page-view-invoice-button btn view-inv-btn">VIEW
										INVOICE</a>
								</div>
							</div>
						</div>

						{{-- data show for mobile --}}
						<div class="col-md-12 mt-5 purchaseTable for_mobile d-none">
							<table class="table main_table_mobile">
								<tbody class="border-0">
									@foreach($orderitems as $orderitem)
									@if (!empty($orderitem->product_option) && !empty($orderitem->product))
										
										<tr class="border_bottom_mb">
											<td style="width: 20% !important;">
												<div class="py-2 mobile_thankyou_img_div">
													{{-- @if ($orderitem->product_option->image)
													<img class="img-fluid img-thumbnail m_chechout_image" src="{{$orderitem->product_option->image}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" class="m_chechout_image" alt="" width="80px">
													@endif --}}
													@if (!empty($orderitem->product->images))
														<img class="img-fluid img-thumbnail m_chechout_image" src="{{$orderitem->product->images}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" alt="" width="80px">
													@endif
												</div>
											</td>
											
											<td style="width:75%;">
												<div class="ps-0 mobile_text_class mt-1" style="">
													<p class="order-confirmation-page-product-title">
														<a class="order-confirmation-page-product-category-name pb-3"
															href=" {{ url('product-detail/'. $orderitem->product->id.'/'.$orderitem->product_option->option_id.'/'.$orderitem->product->slug) }}">
															{{$orderitem->product->name}}
														</a>
													</p>
												</div>
												<p class=" mb-0 order-confirmation-page-product-price"> ${{number_format($orderitem->price,2)}}</p>
											</td>
											<td style="width:5%;">
												<div class="ps-0 mobile_text_class mt-1" style="">
													<p class="order-confirmation-page-product-title">
														{{$orderitem->product->code}}
													</p>
												</div>
											</td>
										</tr>
									@endif
									@endforeach
								</tbody>
							</table>
							
							<div class="w-100 orderSummarymbl p-2">
								<div class="mb-2">
									<h3 class="delievery_options_mbl mb-4">
										Delivery Options
									</h3>
									<div class="d-flex">
										@if($order->logisticsCarrier == 'Delivery')
										<div class="local_order col-6 col-md-6 d-flex align-items-center ">
											<span class="radio_delievery radio_selected mx-2"></span>
											<span class="label_delievery">
												 Delievery
											</span>
										</div>
										<div class="text-right  col-6 col-md-6 d-flex align-items-center justify-content-end">
											<span class="radio_delievery radio_not_selected mx-2"></span>
											<span class="label_delievery">
												Pickup Order
											</span>
										</div>
										@endif
										@if($order->logisticsCarrier == 'Pickup Order')
										<div class="col-6 col-md-6 d-flex align-items-center">
											<span class="radio_delievery radio_not_selected mx-2"></span>
											<span class="label_delievery">
												 Delievery
											</span>
										</div>
										<div class="text-right local_order d-flex align-items-center justify-content-end">
											<span class="radio_delievery radio_selected mx-2"></span>
											<span class="label_delievery">
												Pickup Order
											</span>
										</div>
										@endif
									</div>
								</div>
							</div>
							<div class="w-100 suborderSummarymbl_main">
								<div class="suborderSummarymbl p-2">
									<div>
										<h3 class="delievery_options_mbl mb-3">
											Total
										</h3>
										<div class="d-flex w-100 mb-2">
											<div class="w-50 p-1">
												<span class="summary_sub_total_head">Subtotal:</span>
											</div>
											<div class="w-50 p-1 text-right">
												<span class="summary_sub_total_price text-right">${{ number_format($order->productTotal, 2) }}</span>
											</div>
										</div>
										<div class="d-flex w-100 mb-2">
											<div class="w-50 p-1">
												<span class="summary_sub_total_head">Rate ({{$order->texClasses->rate . '%' }}) :</span>
											</div>
											<div class="w-50 p-1 text-right">
												<span class="summary_sub_total_price text-right">${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}</span>
											</div>
										</div>
										<div class="d-flex w-100 mb-2">
											<div class="w-50 p-1">
												<span class="summary_sub_total_head">Discount :</span>
											</div>
											<div class="w-50 p-1 text-right">
												<span class="summary_sub_total_price text-right">
													@if (!empty($order->buylist_id))
														${{ !empty($order->buylist_discount) ? number_format($order->buylist_discount, 2) : '0.00' }}
													@else
														${{ !empty($order->discount_amount) ? number_format($order->discount_amount, 2) : '0.00' }}
													@endif
												</span>
											</div>
										</div>
										<div class="d-flex w-100 mb-2">
											<div class="w-50 p-1">
												<span class="summary_sub_total_head">Shipping:</span>
											</div>
											<div class="w-50 p-1 text-right">
												<span class="summary_sub_total_price text-right">${{ number_format($order->shipment_price ,2) }}</span>
											</div>
										</div>
										<div class="d-flex w-100">
											<div class="w-50 p-1 d-flex align-items-center">
												<span class="summary_sub_total_head">Total:</span>
											</div>
											<div class="w-50 p-1 text-right">
												<span class="summary_total_price text-right">${{ number_format($order->total_including_tax, 2) }}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
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


<div class="modal fade" id="reminderModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('store_order_reminder') }}">
      @csrf
      <input type="hidden" name="user_id" value="{{ $order_contact->user_id }}">
      <input type="hidden" name="contact_id" value="{{ Session::get('contact_id') }}">
      <input type="hidden" name="order_id" value="{{ $order->id }}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Set Reminder for Reordering</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label for="reminder_date">Reminder Date</label>
          <input type="date" class="form-control" name="reminder_date" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          	<button id="submitReminderBtn" class="btn btn-primary" type="submit">
				<span class="spinner-border spinner-border-sm me-1 d-none" id="submitReminderSpinner" role="status" aria-hidden="true"></span>
				Save Reminder
			</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
  document.querySelector('#reminderModal form').addEventListener('submit', function () {
    const btn = document.getElementById('submitReminderBtn');
    const spinner = document.getElementById('submitReminderSpinner');

    spinner.classList.remove('d-none'); // show spinner
    btn.setAttribute('disabled', true); // disable button to prevent double click
  });
</script>


@include('partials.product-footer')

@include('partials.footer')

