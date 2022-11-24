@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

{{session()->forget('cart');}}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5>card header</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="billing-address bg-light p-3 ms-4 mt-5 mb-5">
								<div class="bg-light">
									<div style="font-weight: 600;font-size: 20px;">Billing Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name">{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}</div>
										<div class="col-md-6 name">{{$order->user->contact->company}}</div>
									</div>
								</div>
								<div class="address-line bg-light name">
									{{$order->user->contact->postalAddress1}}
								</div>

								<div class="address-line bg-light name">
									{{$order->user->contact->postalAddress2}}
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
							<div class="billing-address bg-light p-3 ms-4 mt-5 mb-5">
								<div class="bg-light">
									<div style="font-weight: 600;font-size: 20px;">Shipping Address</div>
									<div class="row mt-2">
										<div class="col-md-6 name">{{$order->user->contact->firstName}}
											{{$order->user->contact->lastName}}</div>
										<div class="col-md-6 name">{{$order->user->contact->company}}</div>
									</div>
								</div>
								<div class="address-line bg-light name">
									{{$order->user->contact->postalAddress1}}
								</div>


								<div class="address-line bg-light name">
									{{$order->user->contact->postalAddress2}}
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
							<p class="fs-1">Items Purchased</p>
						</div>
						@foreach($order->apiOrderItem as $item)
						<div class="row">
							<div class="col-md-2">
								1
								<img src="/theme/img/price.php" alt="">
							</div>
							<div class="col-md-6">
								<a class="thnak-you-page-product-name"
									href="{{ url('product-detail/'. $item->product->id) }}">{{$item->product->name}}</a>
							</div>
							<div class="col-md-2">
								<p>{{$item->quantity}}</p>
							</div>
							<div class="col-md-2">
								<p class="thank-you-page-product-price">${{$item->product->retail_price}}</p>
							</div>
						</div>
						@endforeach

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@include('partials.product-footer')
@include('partials.footer')