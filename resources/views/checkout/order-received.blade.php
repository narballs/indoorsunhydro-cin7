@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{session()->forget('cart');}}

<div class="container mt-5">
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

						<div class="row ps-5 mt-3">
							<div class="col-md-2">
								<div class="mt-4 order-page-prdoct-img ps-1 pt-1 pb-1">
									<img class="order-page-product-image" src="{{ $item->product->images}}" alt="">
								</div>
							</div>
							<div class=" col-md-6 mt-5">
								<a class="thnak-you-page-product-name"
									href="{{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
									{{$item->product->name}}
								</a>
							</div>
							<div class="col-md-2 mt-5 ">
								{{-- <p class="order-page-prduct-quantity">{{$item->quantity}}</p> --}}
								<p>{{$item->quantity}}</p>
							</div>
							<div class="col-md-2 mt-5">
								<p class="thank-you-page-product-price">${{
									number_format($item->product->retail_price,2)}}</p>
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
									<td class="table-items-sub-item">${{number_format($item->product->retail_price *
										$item->quantity, 2)}}</td>
									<td class="table-items-sub-item">{{$order->paymentTerms}}</td>

								</tr>
							</table>
							<div class="col-md-12 justify-content-center align-items-center d-flex">
								<button class="view-invoice" type="button">View invoice</button>
							</div>
						</div>
					</div>
				</card-footer>
			</div>
		</div>
	</div>
</div>
@include('partials.product-footer')
@include('partials.footer')