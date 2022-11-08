<?php 
	$categories = NavHelper::getCategories();
?>

<div class="col-xl-12 col-lg-12 col-md-12  col-sm-12 col-xs-12">
	<nav class="navbar navbar-expand-lg navbar-light bg-transparent pb-0 justify-content-start">

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNav">
			<div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNavDarkDropdown">
				<div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNavDarkDropdown">
					<ul class="navbar-nav d-flex">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle product-mega-menu" href="#"
								id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
								aria-expanded="false">
								Products
							</a>
							<ul class="dropdown-menu l dropdown-menu-dark mt-0 pr-4 pl-4"
								aria-labelledby="navbarDarkDropdownMenuLink" style="width: 346px;">
								<li><a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
										href="{{url('products')}}"><b>All Products</b></a></li>
								@foreach($categories as $category)
								@if ($category->parent_id == 0)
								<li><a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
										id="category_{{$category->id}}"
										href="{{ url('products/'.$category->id.'/'.$category->slug) }}">
										{{$category->name}}
									</a>
									@endif
									<?php $count = count($category->children);?>
									@if(isset($category->children) && $count > 0)
									<ul class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center">
										@if($count > 10)
										<ul class="dd-horizontal border p-0 pr-4" style="width:800px">
											@else
											<ul class="dd-horizontal pl-0 pr-0" style="width:200px">
												@endif
												<div class="row p-4 ">

													@foreach($category->children as $key=>$cat)
													<?php //echo $cat->count();?>
													<!--  -->
													@if($cat->is_active == 1)

													@if($count > 10 )
													<div class="col-md-3 pl-0 pr-0" style="width:600px">
														@else
														<div class="col-md-12 pl-0 pr-0" style="width:100%">
														@endif
														@if ($count > 0)
															<li class="dropdown-item" id="category_{{$cat->id}}"
																href="{{ url('products/'.$category->id) }}">

																<a class="link-dark text-decoration-none nav-link product-mega-menu"
																	id="category_{{$category->id}}"
																	href="{{ url('products/'.$cat->id.'/'.$category->slug.'-'.$cat->slug) }}">{{$cat->name}}</a>

															</li>
															@endif
														</div>


														@endif

														@endforeach
													</div>
											</ul>

										</ul>
										@endif
								</li>

								@endforeach
							</ul>
						</li>

						<li class="nav-item me-3">
							<a class="nav-link text-uppercase " href="#">About</a>
						</li>

						<li class="nav-item me-4">
							<a class="nav-link text-uppercase" href="#">Contact</a>
						</li>

						<li class="nav-item me-3">
							<a class="nav-link text-uppercase " href="{{ url('my-account') }} ">My
								account</a>
						</li>
					</ul>
				</div>
				<!-- here -->
			</div>
		</div>
	</nav>
</div>

{{--
</div>
</div> --}}