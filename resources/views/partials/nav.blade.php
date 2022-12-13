<?php 
	$categories = NavHelper::getCategories();
?>
<div class="col-xl-12 col-lg-12 col-md-6  col-sm-6 col-xs-6 p-0 header-top">
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
								<li>
									<a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
										id="category_{{$category->id}}"
										href="{{ url('products/'.$category->id.'/'.$category->slug) }}">
										{{$category->name}}
									</a>
									@endif
									<?php $count = count($category->children);?>
									@if(isset($category->children) && $count > 0)
									<ul
										class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center">
										@if($count > 10)
										<ul class="dd-horizontal border p-0 pr-4" style="width:800px">
											@else
											<ul class="dd-horizontal pl-0 pr-0" style="width:100%">
												@endif
												<div class="row pl-4 pt-0 pr-4">

													@foreach($category->children as $key=>$cat)
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
							<a class="nav-link text-uppercase nav-item-links " href="#">
								About
							</a>
						</li>

						<li class="nav-item me-4">
							<a class="nav-link text-uppercase nav-item-links" href="{{url('contact-us')}}">
								Contact
							</a>
						</li>

						<li class="nav-item me-3">
							<a class="nav-link text-uppercase nav-item-links" href="{{ url('my-account') }} ">My
								account
							</a>
						</li>
					</ul>
				</div>
				<!-- here -->
			</div>
		</div>
	</nav>
</div>


<div class="container mobile-view">
	<div class="row">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="#"><img class="top-img" src="/theme/img/indoor_sun.png" ;></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
					aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
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
								<li>
									<a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
										id="category_{{$category->id}}"
										href="{{ url('products/'.$category->id.'/'.$category->slug) }}">
										{{$category->name}}
									</a>
									@endif
									<?php $count = count($category->children);?>
									@if(isset($category->children) && $count > 0)
									<ul
										class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center">
										@if($count > 10)
										<ul class="dd-horizontal border p-0 pr-4" style="width:800px">
											@else
											<ul class="dd-horizontal pl-0 pr-0" style="width:100%">
												@endif
												<div class="row pl-4 pt-0 pr-4">

													@foreach($category->children as $key=>$cat)
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
							<a class="nav-link text-uppercase nav-item-links " href="#">
								About
							</a>
						</li>

						<li class="nav-item me-4">
							<a class="nav-link text-uppercase nav-item-links" href="{{url('contact-us')}}">
								Contact
							</a>
						</li>

						<li class="nav-item me-3">
							<a class="nav-link text-uppercase nav-item-links" href="{{ url('my-account') }} ">My
								account
							</a>
						</li>
					</ul>
					<form class="d-flex mt-3" method="get" action="{{route('product_search')}}">
						<input type="hidden" id="is_search" name="is_search" value="1">
						<div class="input-group top-search-group">
							<input type="text" class="form-control" placeholder="What are you searching for"
								aria-label="Search" aria-describedby="basic-addon2" id="search" name="value"
								value="{{ isset($searched_value) ? $searched_value : '' }}">
							<span class="input-group-text" id="search-addon">
								<button class="btn-info" type="submit" id="search"
									style="background: transparent;border:none">
									<i class="text-white" data-feather="search"></i>
								</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</nav>
	</div>
</div>