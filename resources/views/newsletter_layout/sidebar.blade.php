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
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{{route('newsletter_subscriptions')}}" class="nav-link ">
              <i class="nav-icon fas fa-newsletter"></i>
              <p>
                Newsletter Subscribers
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{{route('newsletter-templates.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-newsletter"></i>
              <p>
                Newsletter Template List
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="{{route('newsletter-templates.create')}}" class="nav-link ">
              <i class="nav-icon fas fa-newsletter"></i>
              <p>
                Create Newsletter Template
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>