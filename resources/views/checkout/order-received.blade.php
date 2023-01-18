@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{session()->forget('cart');}}
<div class="container mt-5 ">
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
						{{-- <div class="col-md-1 pb-2">
							<img src="/theme/img/crose.png" alt="">
						</div> --}}
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
						<?php 
			                if($pricing == 'WholesaleUSD') {
			                    $retail_prices = $option->wholesalePrice;
			                }
			                else {
			                    $retail_prices = $option->retailPrice;
			                }
	                	?>
						<?php //dd($option);?>
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
</div>
{{-- <div class="container-fluid" style="width: 1551px !important;">
	<div class="row">
		<div class="col-md-12">
			<div class="card border-0 thank-you-page-background-img">
				<div class="row">
					<div class="col-md-12 thank-you-page-card-row">
						<div class="card m-auto border-0 thank-you-page-first-card">
							<div class="card-boday">
								<div class="col-md-12 card-body-content">
									<p class="thank-page-date">{{$order->user->contact->created_at
										->format('F '.'d, Y, '.'g:i A')}}
									</p>
									<hr class="border">
								</div>
								<div class="row ps-5">
									<div class="col-md-7 mt-4">
										<div class="row">
											<div class="col-md-7">
												<p class="ps-5 thanks-heading">Thanks</p>
												<p class="for-you-order">for your order</p>
											</div>
											<div class="col-md-5">
												<div class="pt-5">
													<img src="/theme/img/thnak-page-user-icon.png" class="img-fluid"
														alt="">
													<span
														class="thank-you-page-user-name pt-4 ps-2">{{$order->user->contact->firstName}}
														{{$order->user->contact->lastName}}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 mt-5">
												<p class="description-thank-you-page ps-5">Lorem ipsum dolor sit amet,
													consectetur adipiscing elit. Praesent
													blandit suscipit felis,<br> at fermentum arcu. Vestibulum molestie
													laoreet eros, id semper magna luctus at.<br> Maecenas at mi sed ex
													ullamcorper viverra vel aliquam ante. Duis ut pulvinar ipsum.<br>
													Morbi
													lectus magna, dictum ut</p>
											</div>
											<div class="mt-5">
												@foreach($order->apiOrderItem as $item)
												@foreach($item->product->options as $option)
												<div class="row ms-5 mt-2 thank-you-page-all-prudct-section">
													@if($option->image)
													<div class="col-md-2 pe-2 ps-0">
														<img class="img-fluid my-2 thank-you-page-product-options-image"
															src="{{ $option->image}}" alt="">
													</div>
													@else
													<div class="col-md-2 p-0">
														<img class="img-fluid my-2 thank-you-page-product-options-image"
															src="/theme/img/image_not_available.png" alt="">
													</div>
													@endif
													<div class=" col-md-7 my-3 ps-1 pe-1">
														<p class="thank-you-sku ps-0">Sku:{{$item->product->code}}</p>
														<p class="thank-page-title">{{$item->product->name}}</p>
													</div>
													<div class="col-md-3">
														<p class="thnak-you-page-price">
															${{number_format($item->product->retail_price,2)}}
														</p>
													</div>
												</div>
												@endforeach
												@endforeach
											</div>
										</div>
									</div>
									<div class="col-md-5 thnak-you-page-box-billing-address mt-5">
										<p class="thank-you-page-billing-address">Billing Address</p>
										<p class="thank-you-page-delivery-address">Delivery Address</p>
										<span class="thank-you-page-user-detais">
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}
										</span><br>
										<span class="thank-you-page-user-detaiss">{{$order->user->contact->email}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalAddress1}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalAddress2}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalPostCode}}
										</span>
										<p class="thank-you-page-delivery-address mt-4">Billing Address</p>
										<span class="thank-you-page-user-detais">
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}
										</span><br>
										<span class="thank-you-page-user-detaiss">{{$order->user->contact->email}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalAddress1}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalAddress2}}
										</span><br>
										<span class="thank-you-page-user-detaiss">
											{{$order->user->contact->postalPostCode}}
										</span>
										<div class="row mt-5 ms-0 py-3 thank-you-page-second-row">
											<div class="col-md-12">
												<p class="thank-you-page-order-summary">Order Summary</p>
												<div class="row">
													<div class="col-md-6">
														<p class="thank-you-page-item-count">Item count</p>
														<span class="thank-you-page-item-counter">{{$count}}</span>
													</div>
													<div class="col-md-6 ps-5">
														<p class="thank-you-page-item-count">Delivery Method</p>
														<span
															class="thank-you-page-item-counter">{{$order->paymentTerms}}</span>
													</div>
													<div class="col-md-6 mt-5">
														<p class="thank-you-page-item-count">Total</p>
														<span class="thank-you-page-item-counter">
															${{number_format($item->product->retail_price *
															$item->quantity, 2)}}
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mt-5 ms-5">
									<div class="col-md-12">
										<hr class="second-border">
										<p class="best-product mt-5"> Best Product
											<span> <img src="/theme/img/thnak-you-best-pruduct-img.png"
													class="img-fluid ps-3" alt=""></span>
										</p>
										<div class="row ps-4">
											@foreach ($best_products as $product)
											<div
												class="col-md-3 d-flex justify-content-between aling-imtes-center ps-0 pe-3">
												<div>
													<div style="background: #FFFFFF;
													border: 1px solid #D3D3D3;
													border-radius: 5px;
													height: 177px;
													">
														@if ($product->images)
														<img src="{{$product->images}}" alt="" class="img-fluid" style="max-width: 62%;
															margin-left: 41px;
															padding-top: 28px;
															max-height: 167px;
															">
														@else
														<img src="/theme/img/image_not_available.png" class="img-fluid"
															alt="">
														@endif
													</div>
													<p class="thank-you-page-product-sku pt-1">Sku:{{$product->code}}
													</p>
													<p class="thank-you-page-product-name">{{$product->name}}</p>
												</div>
											</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-7 m-auto py-4 thank-you-page-card-footer-best-product-section mt-5"
						style="margin-top: -97px !important">
						<p class="thank-you-page-card-footer">Indoorsunhydro isn’t your grandma’s gardening
							store.<br> But you can bring her along
							if you want. <br> Walk-ins welcome anytime — except Sunday. Even gardeners need
							a day
							of rest.</p>
						<p class="thank-you-page-footer-icons mt-5">
							<img src="/theme/img/thank-you-page-icon-3.png" alt="">
							<span><img src="/theme/img/thank-you-page-icon-2.png" alt=""></span>
							<span><img src="/theme/img/thank-you-page-icon-1 (1).png" alt=""></span>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> --}}
@include('partials.product-footer')
@include('partials.footer')