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

	@media  screen and (min-width : 280px ) and (max-width:425px) { 
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

	@media screen and (max-width:425px) and (min-width: 376px) {
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
			padding-top: 2rem !important;
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
			left: 1.5%;
			top: 16%;
		}
	}
	
	@media only screen and (max-width: 1920px) and (min-width: 1441px) {
		.confirmation_check {
			position: absolute;
			top: 16%;
			left: 2.1%;
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
			left: 4.1%;
			top: 16%;
		}
	}
	@media only screen and (max-width: 768px) and (min-width: 426px) {
		.confirmation_check {
			position: absolute;
			left: 6%;
			top: 18%;
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
{{-- @php
	$tax=0;
	$tax_rate = 0;
	$subtotal = 0;
	$tax_without_discount = 0;
	$subtotal = $order->total;
	$tax_class = App\Models\TaxClass::where('name', $order_contact->tax_class)->first();
	$discount_amount = $order->discount_amount;
	if (isset($discount_variation_value) && !empty($discount_variation_value) && $discount_amount > 0) {
		$discount_variation_value = $discount_variation_value;
		if (!empty($tax_class)) {
			$tax_rate = $tax_class->rate;
			$tax_without_discount = $subtotal * ($tax_rate / 100);
			if (!empty($discount_variation) && $discount_variation == 'percentage') {
				$tax = $tax_without_discount - ($tax_without_discount * ($discount_variation_value / 100));
			} else {
				$tax = $tax_without_discount - $discount_variation_value;
			}
		}

	} else {
		if (!empty($tax_class)) {
			$tax_rate = $tax_class->rate;
			$tax = $subtotal * ($tax_rate / 100);
		}
	} 
@endphp --}}
<div class="container-fluid main-thankyou-div" style="width:89%;">
	<div class="">
		<div class="col-md-12">
			<div class="card mt-5 border-0 thank-you-card">
				<div class="card-body ps-5 mt-5  thank-you-card-body">
					<div class="row ps-5 mobile_class">
						{{-- <div class=" col-xl-12 col-lg-12 col-md-12 col-sm-12">
							<p class="order-confirmation-page-top-heading mobile-font">Order Confirmation</p>
						</div> --}}
						<div class="row mb-3">
							<div class="col-md-12 d-flex main-logo-confirm-div">
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
											{{$order_contact->firstName}}
											{{$order_contact->lastName}}
											{{-- <span class="order-confirmation-page-user-name mobile-font mobile-font-part">Your order has been received.</span> --}}
										</p>
									</div>
								</div>
							</div>
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
									${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}
								</p>
							</div>
							<div class="col-md-3">
								<input type="hidden" name="getorderTotal" value="{{number_format($order->total_including_tax, 2)}}" class="getorderTotal" id="">
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
											{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price, 2) }} --}}
											${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
										</p>
									</div>
									<div class="col-md-3">
										<p class="order-confirmation-page-order-number-title">Discount</p>
										<p class="order-confirmation-page-order-number-item">
											{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price, 2) }} --}}
											${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}
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
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Order Number</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['order_id']}}
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Date</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">
										Mobile
									</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order_contact->phone}}
									</p>
								</div>
								
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">
										Email
									</p>
									<p class="order-confirmation-page-order-number-item">
										{{$order_contact->email}}
									</p>
								</div>

								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Payment Method</p>
									<p class="order-confirmation-page-order-number-item">{{$order->logisticsCarrier}}</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Shipping</p>
									<p class="order-confirmation-page-order-number-item">${{number_format($order->shipment_price , 2)}}</p>
								</div>
								
								
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Tax</p>
									<p class="order-confirmation-page-order-number-item">
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price  , 2) }} --}}
										${{ !empty($order->tax_rate) ? number_format($order->tax_rate , 2) : number_format($tax, 2) }}
									</p>
								</div>
								<div class="col-md-4">
									<p class="order-confirmation-page-order-number-title">Discount</p>
									<p class="order-confirmation-page-order-number-item">
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price  , 2) }} --}}
										${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}
									</p>
								</div>
								<div class="col-md-4">
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
										{{-- ${{ number_format(($order->total_including_tax - $order->productTotal) - $order->shipment_price , 2) }} --}}
										${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}
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
							<div class="row  ms-5 p-4 me-5 order-confirmation-page-second-row div_increase_mobile" style="padding-top: 5rem!important;">
								<div class="col-md-6">
									<p class="order-confirmation-page-billing-address mobile-font">
										Billing Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<p class="order-confirmation-page-address-line-one-title mb-1">
														{{$order_contact->postalAddress1 ? $order_contact->postalAddress1 . ',' : ''}}
													</p>
													<p class="order-confirmation-page-address-line-one-title mb-1">
														{{$order_contact->postalAddress2 ? $order_contact->postalAddress2 . ',' : ''}}
													</p>
													<p class="order-confirmation-page-address-line-one-title">
														{{$order_contact->postalCity ? $order_contact->postalCity . ',' : ''}}
														{{$order_contact->postalState ? $order_contact->postalState . ',' : ''}}
														{{$order_contact->postalPostCode ? $order_contact->postalPostCode : ''}}
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
														{{$order_contact->address1 ? $order_contact->address1 . ',' : ''}}
													</p>
													<p class="order-confirmation-page-address-line-one-title mb-1">
														{{$order_contact->address2 ? $order_contact->address2: ''}}
													</p>
													<p class="order-confirmation-page-address-line-one-title">
														{{$order_contact->city ? $order_contact->city . ',' : ''}}
														{{$order_contact->state ? $order_contact->state . ',' : ''}}
														{{$order_contact->postCode ? $order_contact->postCode : ''}}
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
									{{-- <th class="order-confirmation-page-table-data-heading">Shipping</th> --}}
									<th class="order-confirmation-page-table-data-heading">Price</th>
								</tr>
								<tbody class="border-0">
									@foreach($order->apiOrderItem as $item)
									@foreach($item->product->options as $option)
									<tr>
										<td>
											<div class="row align-items-center">
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
													{{-- <br>
													<p class="order-confirmation-page-product-title">Title:<span
															class="order-confirmation-page-product-item">
															{{$item->product->name}}</span>
													</p> --}}
												</div>
											</div>
										</td>
										<td class="align-middle">
											<div class="row">
												<div class="col-md-12">
													<p class="mb-0 order-confirmation-page-product-quantity">
														{{$item->product->code}}</p>
												</div>
											</div>
										</td>
										{{-- <td>Shipping</td> --}}
										<td class="align-middle">
											<div class="row">
												<div class="col-md-12">
													<p class="mb-0 order-confirmation-page-product-quantity">
														{{$item->quantity}}</p>
												</div>
											</div>
										</td>
										<td class="align-middle">
											<p class="mb-0 order-confirmation-page-product-price">
												${{number_format($item->price,2)}}</p>
										</td>
									</tr>
									@endforeach
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
											
											<td style="width:75%;">
												<div class="ps-0 mobile_text_class mt-1" style="">
													<p class="order-confirmation-page-product-title">
														<a class="order-confirmation-page-product-category-name pb-3"
															href=" {{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
															{{$item->product->name}}
														</a>
													</p>
												</div>
												<p class=" mb-0 order-confirmation-page-product-price"> ${{number_format($item->price,2)}}</p>
											</td>
											<td style="width:5%;">
												<div class="ps-0 mobile_text_class mt-1" style="">
													<p class="order-confirmation-page-product-title">
														{{$item->product->code}}
													</p>
												</div>
											</td>
										</tr>
										@endforeach
									@endforeach
								</tbody>
							</table>
							
							<div class="w-100 orderSummarymbl p-2">
								<div class="mb-2">
									<h3 class="delievery_options_mbl mb-4">
										Delivery Options
									</h3>
									<div class="d-flex">
										@if($order->logisticsCarrier == 'Local Delivery')
										<div class="w-50 local_order p-1 d-flex align-items-center justify-content-evenly">
											<span class="radio_delievery radio_selected"></span>
											<span class="label_delievery">
												Local Delievery
											</span>
										</div>
										<div class="w-50 text-right p-1 d-flex align-items-center justify-content-evenly">
											<span class="radio_delievery radio_not_selected"></span>
											<span class="label_delievery">
												Pickup Order
											</span>
										</div>
										@endif
										@if($order->logisticsCarrier == 'Pickup Order')
										<div class="w-50  p-1 d-flex align-items-center justify-content-evenly">
											<span class="radio_delievery radio_not_selected"></span>
											<span class="label_delievery">
												Local Delievery
											</span>
										</div>
										<div class="w-50 text-right local_order p-1 d-flex align-items-center justify-content-evenly">
											<span class="radio_delievery radio_selected"></span>
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
												<span class="summary_sub_total_price text-right">${{!empty($order->discount_amount) ?  number_format($order->discount_amount, 2) : '0.00' }}</span>
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

@include('partials.product-footer')

@include('partials.footer')

