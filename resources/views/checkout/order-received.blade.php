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
						<div class="col-md-5">
							<h5 class="fw-bold fs-1" style="font-family: 'Poppins'">Order Confirmed</h5>
						</div>
						<div class="col-md-6 mt-1">
							<p style="font-family: 'Poppins'"> <span><img src="/theme/img/star.png" alt=""></span> Thank
								You
								<span class="fw-bold">
									{{$order->user->contact->firstName}}
									{{$order->user->contact->lastName}}
								</span>
								Your Order has been resolved!
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
							<div class="billing-address bg-light p-3 ms-4 mt-5 mb-5">
								<div class="bg-light">
									<div style="font-weight: 600;font-size: 20px;">Billing Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name">
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}<br>
										</div>
										<div class="col-md-6 name">
											{{$order->user->contact->company}}
										</div>
									</div>
								</div>
								<div class="address-line bg-light name col-md-6">
									{{$order->user->contact->postalAddress1}}<br>
									{{$order->user->contact->postalAddress2}}
								</div>
								<div class="row">
									<div class="col-md-4 mb-0">
										<p class="name mb-0">City</p>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalCity}}
										</div>

									</div>
									<div class="col-md-4 mb-0">
										<P class="name mb-0">State</P>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalState}}
										</div>

									</div>
									<div class="col-md-4 mb-0">
										<p class="name mb-0">Zip</p>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalPostCode}}</div>

									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="billing-address bg-light p-3 ms-4 mt-5 mb-5">
								<div class="bg-light">
									<div style="font-weight: 600;font-size: 20px;">Billing Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name">
											{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}<br>
										</div>
										<div class="col-md-6 name">
											{{$order->user->contact->company}}
										</div>
									</div>
								</div>
								<div class="address-line bg-light name col-md-6">
									{{$order->user->contact->postalAddress1}}<br>
									{{$order->user->contact->postalAddress2}}
								</div>
								<div class="row">
									<div class="col-md-4">
										<p class="name mb-0">City</p>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalCity}}
										</div>

									</div>
									<div class="col-md-4">
										<P class="name mb-0">State</P>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalState}}
										</div>

									</div>
									<div class="col-md-4 ">
										<p class="name mb-0">Zip</p>
										<div class="address-line bg-light name">
											{{$order->user->contact->postalPostCode}}</div>

									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<h5 class="fw-bold ps-1" style="font-family: 'Poppins'">Item Purchased</h5>
						</div>

						@foreach($order->apiOrderItem as $item)
						@foreach($item->product->options as $option)

						<div class="row ps-5">
							<div class="col-md-2">
								<div class="mt-4">
									<img src="{{ $item->product->images}}" alt="" width="70px;">
								</div>
							</div>
							<div class="col-md-6 mt-5">
								<a class="thnak-you-page-product-name"
									href="{{ url('product-detail/'. $item->product->id.'/'.$option->option_id.'/'.$item->product->slug) }}">
									{{$item->product->name}}
								</a>
							</div>
							<div class="col-md-2 mt-5" style="border-radius: 2px solid">
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
				<card-footer class="ps-5">
					<div class="row">
						<div class="col-md-12">
							<table class="mb-5 thnak-you-page-top-section">
								<tr class="table-heading">
									<th>Order number</th>
									<th>Date</th>
									<th>Email</th>
									<th>phone</th>

								</tr>
								<tr class="table-items">
									<td>{{$order->id}}</td>
									<td>{{$formatedDate}}</td>
									<td>{{$order->user->email}}</td>
									<td>{{$order->user->contact->phone}}</td>

								</tr>
								<tr class="table-heading">
									<th>Shipping</th>
									<th>Text</th>
									<th>Total</th>
									<th>Payment Method</th>
								</tr>
								<tr class="table-items">
									<td></td>
									<td></td>
									<td>${{number_format($item->product->retail_price * $item->quantity, 2)}}</td>
									<td>{{$order->paymentTerms}}</td>
								</tr>
							</table>
						</div>
					</div>
				</card-footer>
			</div>
		</div>
	</div>
</div>
@include('partials.product-footer')
@include('partials.footer')