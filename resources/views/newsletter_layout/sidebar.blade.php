<aside class="main-sidebar sidebar-dark-primary elevation-4">


	<!-- Sidebar -->
	<div class="sidebar">

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
				<li class="nav-item  ">
					<a href="{{route('newsletter_dashboard')}}" class="nav-link ">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>
				
				

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-copy"></i>
						<p>
							Newsletters
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item  ">
							<a href="{{route('newsletter_subscriptions')}}" class="nav-link ">
								<i class="nav-icon fas fa-user"></i>
								<p>
									 Subscribers
								</p>
							</a>
						</li>
						<li class="nav-item  ">
							<a href="{{route('newsletter-templates.index')}}" class="nav-link ">
								<i class="nav-icon fas fa-file"></i>
								<p>
									 Template List
								</p>
							</a>
						</li>
						<li class="nav-item  ">
							<a href="{{route('newsletter-templates.create')}}" class="nav-link ">
								<i class="nav-icon fas fa-book"></i>
								<p>
									Create Template
								</p>
							</a>
						</li>
						<li class="nav-item  ">
							<a href="{{route('assign_template_form')}}" class="nav-link ">
								<i class="nav-icon far fa-plus-square"></i>
								<p>
									Assign Template
								</p>
							</a>
						</li>
						<li class="nav-item  ">
							<a href="{{route('view_assigned_templates')}}" class="nav-link ">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Assigned Templates
								</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item  ">
					<a href="{{url('admin/logout')}}" class="nav-link ">
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