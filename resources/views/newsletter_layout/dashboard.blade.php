 @include('newsletter_layout.header')
 @include('newsletter_layout.navbar')
 @include('newsletter_layout.sidebar')

 <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
         </div>
        <!--**********************************
            Content body end
        ***********************************-->
@include('newsletter_layout.footer')
@stack('scripts')
@include('newsletter_layout.footerscripts')
