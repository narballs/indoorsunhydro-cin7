@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
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
</style>
{{session()->forget('cart');}}

<div class="container-fluid" style="width:89%;">
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-5 border-0">
				<div class="card-body ps-5 mt-5 ">
					<div class="row ps-5">
						<div class="col-md-12">
							<p class="order-confirmation-page-top-heading">Order Confirmation</p>
						</div>
						<div class="col-md-12 mt-4">
							<p class="order-confirmation-page-title">
								{{$order->user->contact->firstName}}
								{{$order->user->contact->lastName}}.
								<span class="order-confirmation-page-user-name">Your order has been received.</span>
							</p>
						</div>
					</div>
					<div class="row ms-5 p-4 me-5 order-confirmation-page-invoice-row"
						style=" padding-top: 50px !important;">
						<div class="col-md-1 p-0" style="margin-left: 32px;">
							<p class="order-confirmation-page-order-number-title">Order Number</p>
							<p class="order-confirmation-page-order-number-item">
								{{$order->apiOrderItem[0]['order_id']}}
							</p>
						</div>
						<div class="col-md-2 ps-4 ms-3">
							<p class="order-confirmation-page-date-title">Date</p>
							<p class="order-confirmation-page-date-item">
								{{$order->apiOrderItem[0]['created_at']->format('F '.'d, Y, '.'g:i A')}}
							</p>
						</div>
						<div class="col-md-1 pe-2 ms-3">
							<p class="order-confirmation-page-mobile-title">
								Mobile
							</p>
							<p class="order-confirmation-page-mobile-item">
								{{$order->user->contact->phone}}
							</p>
						</div>
						<div class="col-md-2 ps-5">
							<p class="order-confirmation-page-email-title">
								Email
							</p>
							<p class="order-confirmation-page-email-item">
								{{$order->user->email}}
							</p>
						</div>
						<div class="col-md-2">
							<p class="order-confirmation-page-payment-method-title">Payment Method</p>
							<p class="order-confirmation-page-payment-method-item">{{$order->paymentTerms}}</p>
						</div>
						<div class="col-md-1 ps-0">
							<p class="order-confirmation-page-shipping-title">Shipping</p>
							<p class="order-confirmation-page-shipping-item">$</p>
						</div>
						<div class="col-md-1 ms-4">
							<p class="order-confirmation-page-tax-title">Tax</p>
							<p class="order-confirmation-page-tax-item">
								$
							</p>
						</div>
						<div class="col-md-1 ms-4">
							<p class="order-confirmation-page-total-title">Total</p>
							<p class="order-confirmation-page-total-item">
								${{number_format($order->total, 2)}}
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row  ms-5 p-4 me-5 order-confirmation-page-second-row"
								style="padding-top: 5rem!important;">
								<div class="col-md-6">
									<p class="order-confirmation-page-billing-address">
										Billing Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<p class="order-confirmation-page-first-name-last-name-user-name pt-3">
												{{$order->user->contact->firstName}}
												{{$order->user->contact->lastName}}
											</p>
											<div class="row">
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-one-title">
														Address line 1
													</p>
													@if (!$order->user->contact->address1)
													<p class="order-confirmation-page-address-line-one-item">
														Addressline1 empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-one-item">
														{{$order->user->contact->address1}}
													</p>
													@endif
													<div class="row mt-4">
														<div class="col-md-4">
															<p class="order-confirmation-page-city-name-title">City</p>
															@if (!$order->user->contact->city)
															<p class="order-confirmation-page-city-name-item">
																City empty
															</p>
															@else
															<p class="order-confirmation-page-city-name-item">
																{{$order->user->contact->city}}
															</p>
															@endif
														</div>
														<div class="col-md-4">
															<p class="order-confirmation-page-state-name-title">State
															</p>
															@if (!$order->user->contact->state)
															<p class="order-confirmation-page-state-name-item">
																State empty
															</p>
															@else
															<p class="order-confirmation-page-state-name-item">
																{{$order->user->contact->state}}
															</p>
															@endif
														</div>
														<div class="col-md-4">
															<p class="order-confirmation-page-zip-name-title">Zip</p>

															@if (!$order->user->contact->postCode)
															<p class="order-confirmation-page-zip-name-item">
																zip empty
															</p>
															@else
															<p class="order-confirmation-page-zip-name-item">

																{{$order->user->contact->postCode}}
															</p>
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-tow-title">
														Address line 1
													</p>
													@if ($order->user->contact->address2)
													<p class="order-confirmation-page-address-line-tow-item">
														{{$order->user->contact->address2}}
													</p>
													@endif
													<p class="order-confirmation-page-address-line-tow-item">
														Addressline empty
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<p class="order-confirmation-page-shipping-address">
										Shipping Address
									</p>
									<div class="row">
										<div class="col-md-12">
											<p class="order-confirmation-page-first-name-last-name-user-name pt-3">
												{{$order->user->contact->firstName}}
												{{$order->user->contact->lastName}}
											</p>
											<div class="row">
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-one-title">
														Address line 1
													</p>
													@if (!$order->user->contact->postalAddress1)
													<p class="order-confirmation-page-address-line-one-item">
														Address Line 1 Empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-one-item">
														{{$order->user->contact->postalAddress1}}
													</p>
													@endif

													<div class="row mt-4">
														<div class="col-md-4">
															<p class="order-confirmation-page-city-name-title">City</p>
															@if (!$order->user->contact->postalCity)
															<p class="order-confirmation-page-city-name-item">
																City empty
															</p>
															@else
															<p class="order-confirmation-page-city-name-item">
																{{$order->user->contact->postalCity}}
															</p>
															@endif
														</div>
														<div class="col-md-4">
															<p class="order-confirmation-page-state-name-title">State
															</p>
															@if (!$order->user->contact->postalState)
															<p class="order-confirmation-page-state-name-item">
																State empty
															</p>
															@else
															<p class="order-confirmation-page-state-name-item">
																{{$order->user->contact->postalState}}
															</p>
															@endif
														</div>
														<div class="col-md-4">
															<p class="order-confirmation-page-zip-name-title">Zip</p>
															@if (!$order->user->contact->postalPostCode)
															<p class="order-confirmation-page-zip-name-item">
																zip empty
															</p>
															@else
															<p class="order-confirmation-page-zip-name-item">
																{{$order->user->contact->postalPostCode}}
															</p>
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<p class="order-confirmation-page-address-line-tow-title">
														Address line 2
													</p>
													@if (!$order->user->contact->postalAddress2)
													<p class="order-confirmation-page-address-line-tow-item">
														Address Line 2 Empty
													</p>
													@else
													<p class="order-confirmation-page-address-line-tow-item">
														{{$order->user->contact->postalAddress2}}
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
					<div class="row ps-5 mt-5 pe-5">
						<div class="col-md-12 mt-5">
							<p class="order-confirmation-page-item-purchased-title">Item Purchased </p>
						</div>
						<div class="col-md-12 mt-5">
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
												<div class="col-md-2 py-2">
													@if ($option->image)
													<img class="img-fluid img-thumbnail" src="{{$option->image}}" alt=""
														width="90px" style="max-height: 90px">
													@else
													<img src="/theme/img/image_not_available.png" alt="" width="80px">
													@endif
												</div>
												<div class="col-md-5 py-2 ps-0"
													style="margin-left: -48px !important; margin-top:3px;">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- <div class="container mt-5 ">
	<div class="row">
		<div class="col-md-12">
			<div class="card  border-0">
				<div class="card-header bg-white border-0 mt-3">
					<div class="row">
						<div class="col-md-4">
							<p class="fw-bold fs-1 order-page-order-confiremd">Order Confirmed.</p>
						</div>
						<div class="col-md-6 mt-3 ps-5 pe-2">
							<p class="order-page-user-name"> <span><img src="/theme/img/star.png" alt=""></span> Thank
								You
								<span class="fw-bold">
									{{$order->user->first_name}} {{$order->user->last_name}},
								</span>
								Your Order has been received!
							</p>
						</div>
						<div class="col-md-1 pb-2">
							<img src="/theme/img/crose.png" alt="">
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="billing-address bg-light p-3">
								<div class="bg-light">
									<div class="billing-address-heading">Billing Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name mt-4">
											<div class="address-line bg-light mb-1">
												Your Name
											</div>
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}
										</div>
										<div class="col-md-6 name mt-4">
											<div class="address-line bg-light mb-1">
												Your Company Name
											</div>
											{{$order->user->contact->company}}
										</div>
									</div>
								</div>
								<div class="address-line bg-light mt-2">
									Address line 1
								</div>

								<div class="bg-light name">
									{{$order->user->contact->postalAddress1}}
								</div>
								<div class="address-line bg-light">
									Address line 2
								</div>
								<div class="bg-light name">
									{{$order->user->contact->postalAddress2}}
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
											{{$order->user->contact->postalCity}}
										</div>
										<div class="col p-0 name">
											{{$order->user->contact->postalState}}
										</div>
										<div class="col p-0 name">
											{{$order->user->contact->postalPostCode}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="billing-address bg-light p-3">
								<div class="bg-light">
									<div class="shipping-address-heading">Shipping Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name mt-4">
											<div class="address-line bg-light mb-1">
												My Name
											</div>
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}
										</div>
										<div class="col-md-6 name mt-4">
											<div class="address-line bg-light mb-1">
												My Company Name
											</div>
											{{$order->user->contact->company}}

										</div>
									</div>
								</div>
								<div class="address-line bg-light mt-2">
									Address line 1
								</div>

								<div class="bg-light name">
									{{$order->user->contact->postalAddress1}}
								</div>
								<div class="address-line bg-light">
									Address line 2
								</div>
								<div class="bg-light name">
									{{$order->user->contact->postalAddress2}}
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
											{{$order->user->contact->postalCity}}
										</div>
										<div class="col p-0 name">
											{{$order->user->contact->postalState}}
										</div>
										<div class="col p-0 name">
											{{$order->user->contact->postalPostCode}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<h5 class="fw-bold ps-1 item-purchased">Item Purchased
							</h5>
						</div>
						@foreach($order->apiOrderItem as $item)
						@foreach($item->product->options as $option)
						{{
						if($pricing == 'WholesaleUSD') {
						$retail_prices = $option->wholesalePrice;
						}
						else {
						$retail_prices = $option->retailPrice;
						}
						}}

						<div class="row ps-5 mt-3">
							<div class="col-md-2">
								@if($option->image)
								<div class="mt-4 order-page-prdoct-img ps-1 pt-1 pb-1">
									<img class="order-page-product-image" src="{{ $option->image}}" alt=""
										style="    background: #fff !important;">
								</div>
								@else
								<div class="mt-4 order-page-prdoct-img ps-1 pt-1 pb-1">
									<img class="order-page-product-image" src="/theme/img/image_not_available.png"
										alt="" style="    background: #fff !important;">
								</div>
								@endif
							</div>
							<div class=" col-md-6 mt-5">
								<a class="thnak-you-page-product-name"
									href="{{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
									{{$item->product->name}}
								</a>
							</div>
							<div class="col-md-2 mt-5">
								<p class="d-flex justify-content-between align-items-center order-page-prduct-quantity">
									{{$item->quantity}}</p>
							</div>
							<div class="col-md-2 mt-5">
								<p class="thank-you-page-product-price">${{
									number_format($retail_prices,2)}}</p>
							</div>
						</div>
						@endforeach
						@endforeach
					</div>
				</div>
				<card-footer class="ps-5 mt-5">
					<div class="row">
						<div class="col-md-12">
							<table class="mb-5 thnak-you-page-top-section">
								<tr class="table-heading">
									<td class="table-heading-subheading">Order number</td>
									<td class="table-heading-subheading">Date</td>
									<td class="table-heading-subheading">Email</td>
									<td class="table-heading-subheading">phone</td>
								</tr>
								<tr class="table-items">
									<td class="table-items-sub-item">{{$order->id}}</td>
									<td class="table-items-sub-item">{{$formatedDate}}</td>
									<td class="table-items-sub-item">{{$order->user->email}}</td>
									<td class="table-items-sub-item">{{$order->user->contact->phone}}</td>
								</tr>
								<tr class="table-heading">
									<td class="table-heading-subheading">Shipping</td>
									<td class="table-heading-subheading">Tax</td>
									<td class="table-heading-subheading">Total</td>
									<td class="table-heading-subheading">Payment Method</td>
								</tr>
								<tr class="table-items">
									<td class="table-items-sub-item">$</td>
									<td class="table-items-sub-item">$</td>
									<td class="table-items-sub-item">${{number_format($order->total, 2)}}</td>
									<td class="table-items-sub-item">{{$order->paymentTerms}}</td>

								</tr>
							</table>
							<div class="col-md-12 justify-content-center align-items-center d-flex">
								<button class="view-invoice mt-5" type="button">View invoice</button>
							</div>
						</div>
					</div>
				</card-footer>
			</div>
		</div>
	</div>
</div> --}}

@include('partials.product-footer')
@include('partials.footer')