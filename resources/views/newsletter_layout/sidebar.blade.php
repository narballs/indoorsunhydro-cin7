<aside class="main-sidebar sidebar-light-primary elevation-4">

	<i class="brand-image mx-4 fa fa-user fw-4 fs-2 my-3"></i>
	<br/>
	<span class="brand-text font-weight-light fw-6 mx-3 px-2"><b>{{strtoupper(auth()->user()->first_name . ' ' .auth()->user()->last_name)}}</b></span>
	<!-- Sidebar -->
	<div class="sidebar">

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
				{{-- <li class="nav-item  ">
					<a href="{{route('newsletter_dashboard')}}" class="nav-link ">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>
				<li class="nav-item  ">
					<a href="#" class="nav-link ">
						<i class="nav-icon fas fa-book"></i>
						<p>
							Newsletter Campaigns
						</p>
					</a>
				</li> --}}
				@if (auth()->user()->hasRole(['Sale Payments']))
				<li class="nav-item  ">
					<a href="{{route('sale_payments')}}" class="nav-link ">
						<i class="nav-icon fas fa-list"></i>
						<p>
							Sale Payments
						</p>
					</a>
				</li>
				@endif
				@if (auth()->user()->hasRole(['Newsletter']))
					<li class="nav-item  ">
						<a href="{{route('newsletter_subscriptions')}}" class="nav-link ">
							<i class="nav-icon fas fa-user"></i>
							<p>
								Subscribers
							</p>
						</a>
					</li>
					{{-- <li class="nav-item  ">
						<a href="{{route('all_contacts')}}" class="nav-link ">
							<i class="nav-icon fas fa-user"></i>
							<p>
								Contacts
							</p>
						</a>
					</li> --}}
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link ">
							<i class="nav-icon fas fa-copy"></i>
							<p>
								Lists
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item  ">
								<a href="{{route('subscribers_list')}}" class="nav-link ">
									<i class="nav-icon fas fa-file"></i>
									<p>
										Show Lists
									</p>
								</a>
							</li>
							<li class="nav-item  ">
								<a href="{{route('subscribers_list_create')}}" class="nav-link ">
									<i class="nav-icon fas fa-book"></i>
									<p>
										Create List
									</p>
								</a>
							</li>
						</ul>
					</li>

					<li class="nav-item has-treeview">
						<a href="#" class="nav-link ">
							<i class="nav-icon fas fa-copy"></i>
							<p>
								Newsletters
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item  ">
								<a href="{{route('newsletter-templates.index')}}" class="nav-link ">
									<i class="nav-icon fas fa-file"></i>
									<p>
										Newsletter Templates List
									</p>
								</a>
							</li>
							<li class="nav-item  ">
								<a href="{{route('newsletter-templates.create')}}" class="nav-link ">
									<i class="nav-icon fas fa-book"></i>
									<p>
										Create Newsletter
									</p>
								</a>
							</li>
							{{-- <li class="nav-item  ">
								<a href="{{route('assign_template_form')}}" class="nav-link ">
									<i class="nav-icon far fa-plus-square"></i>
									<p>
										Assign Newsletter
									</p>
								</a>
							</li>
							<li class="nav-item  ">
								<a href="{{route('view_assigned_templates')}}" class="nav-link ">
									<i class="nav-icon fas fa-th"></i>
									<p>
										Send Newsletters
									</p>
								</a>
							</li> --}}
						</ul>
					</li>
					{{-- <li class="nav-item  ">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-envelope"></i>
							<p>
								Sms Campaigns
							</p>
						</a>
					</li> --}}
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link ">
							<i class="nav-icon fas fa-copy"></i>
							<p>
								Sms Lists
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item  ">
								<a href="{{route('sms_list')}}" class="nav-link ">
									<i class="nav-icon fas fa-file"></i>
									<p>
										Show Sms Lists
									</p>
								</a>
							</li>
							<li class="nav-item  ">
								<a href="{{route('sms_list_create')}}" class="nav-link ">
									<i class="nav-icon fas fa-book"></i>
									<p>
										Create Sms List
									</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link ">
							<i class="nav-icon fas fa-copy"></i>
							<p>
								Sms Templates
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item  ">
								<a href="{{route('list_sms_templates')}}" class="nav-link ">
									<i class="nav-icon fas fa-file"></i>
									<p>
										Show Sms Templates
									</p>
								</a>
							</li>
							<li class="nav-item  ">
								<a href="{{route('create_sms_templates')}}" class="nav-link ">
									<i class="nav-icon fas fa-book"></i>
									<p>
										Create Sms Templates
									</p>
								</a>
							</li>
						</ul>
					</li>
				@endif
				<li class="nav-item  ">
					<a href="{{url('admin/logout')}}" class="nav-link">
						<i class="nav-icon fas fa-sign-out-alt"></i>
						<p>
							Logout
						</p>
					</a>
				</li> 
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>