<header class="bg-white  text-dark top-bar-height w-100 header-top">
    <div class="container-fluid my-1">
        <div class="row justify-content-center">
            
            <div class="col-md-2 col-lg-5 col-xl-5"></div>
            <div class="col-md-10 col-lg-7 col-xl-7">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="row mx-1">
                        <div class="col-md-5 col-xl-3 col-lg-3 d-flex justify-content-around">
                            <div class="mt-2">
                                @if (session('logged_in_as_another_user'))
                                    <a href="{{ url('admin/go-back') }}" class="top-bar-logout mt-3 top-header-items">Go
                                        Back</a>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if(auth()->user())
                                    @if(auth()->user()->hasRole('Admin'))
                                        <a href="{{ url('admin/go-back') }}" class="top-bar-logout mt-3 top-header-items">Return to Admin</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @php
                            $enable_wholesale_registration = App\Models\AdminSetting::where('option_name', 'enable_wholesale_registration')->first();
                        @endphp
                        <div class="col-md-7 col-xl-9 col-lg-9 mx-auto">
                            <a href="{{ '/user/' }}" class="text-white d-flex align-items-end">
                                @if (Auth::user())
                                    <div>
                                        @php
                                            $session_contact_company = Session::get('company');
                                            $wholesale_application_status = App\Models\WholesaleApplicationInformation::where('email' , Auth::user()->email)->first();
                                        @endphp
                                        <form style="display:none;" id="frm-logout" action="{{ route('logout') }}"
                                            method="POST">
                                            {{ csrf_field() }}
                                            <input class="btn btn-link text-white" type="submit" value="logout">
                                        </form>
                                        <nav class="navbar navbar-expand-sm navbar-light company-nav-bar-conatainer p-0"
                                            style="">
                                            <!-- Toggle button -->
                                            <button class="navbar-toggler px-0 text-light" type="button"
                                                data-mdb-toggle="collapse" data-mdb-target="#navbarExample1"
                                                aria-controls="navbarExample1" aria-expanded="false"
                                                aria-label="Toggle navigation">
                                                <i class="fas fa-bars"></i>
                                            </button>
                                            <!-- Collapsible wrapper -->
                                            <div class="collapse navbar-collapse row" id="navbarExample1">
                                                <!-- Left links -->
                                                <ul class="navbar-nav me-auto ps-lg-0 row" style="">
                                                    <!-- Navbar dropdown -->
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12 col-xl-12 d-flex align-items-center">
                                                            <li
                                                                class="nav-item dropdown position-static width_fix adjust_arrow_color">
                                                                @if (!empty($session_contact_company))
                                                                    <a class="select_company_hover nav-link dropdown-toggle select_company_top remove_focus"
                                                                        href="#" id="navbarDropdown"
                                                                        role="button" data-mdb-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <span class="top-header-items hover_it">
                                                                            {!! \Illuminate\Support\Str::limit($session_contact_company, 14) !!}
                                                                        </span>
                                                                        <i
                                                                            class="fa fa-angle-down arrow_icon text-dark"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="select_company_hover nav-link dropdown-toggle select_company_top remove_focus"
                                                                        href="#" id="navbarDropdown"
                                                                        role="button" data-mdb-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <span class="top-header-items hover_it">Select
                                                                            Company</span>
                                                                        <i
                                                                            class="fa fa-angle-down arrow_icon text-dark"></i>
                                                                    </a>
                                                                @endif
                                                                <!-- Dropdown menu -->
                                                                @php
                                                                    $companies = Session::get('companies');
                                                                    
                                                                @endphp
                                                                <div class="dropdown-menu hover-menu d-Menu mt-0"
                                                                    aria-labelledby="navbarDropdown"
                                                                    style="box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);border-bottom: 0px !important;border-left: 0px;border-right: 0px;border-top: 2px solid #7bc533;border-radius:0px 0px 5px 5px !important;">
                                                                    <div class="container">
                                                                        <div class="row">
                                                                            <div class="col-md-12"
                                                                                style="padding-left:0px !important;">
                                                                                <div
                                                                                    class="list-group list-group-flush top_select_menu_items top-header-items hover_it">
                                                                                    @if (Auth::user())
                                                                                        @if ($companies)
                                                                                            @foreach ($companies as $company)
                                                                                                @php
                                                                                                    if ($company->contact_id) {
                                                                                                        $contact_id = $company->contact_id;
                                                                                                        $primary = '(primary)';
                                                                                                    } else {
                                                                                                        $contact_id = $company->secondary_id;
                                                                                                        $primary = '(secondary)';
                                                                                                    }
                                                                                                    if ($company->status == 0) {
                                                                                                        $disabled = 'disabled';
                                                                                                        $disable_text = '(Disabled)';
                                                                                                        $muted = 'text-muted';
                                                                                                    } else {
                                                                                                        $disabled = '';
                                                                                                        $disable_text = '';
                                                                                                        $muted = '';
                                                                                                    }
                                                                                                    
                                                                                                @endphp
                                                                                                @if($company->type != "Supplier")
                                                                                                    <a type="button"
                                                                                                        class="multiple_company_hover select_company_top list-group-item list-group-item-action {{ $disabled }} {{ $muted }}"
                                                                                                        onclick="switch_company_user({{ $contact_id }})">{{ $company->company }}
                                                                                                        <span
                                                                                                            style="font-size: 9px;font-family: 'Poppins';"
                                                                                                            class="{{ $muted }}">{{ $primary }}
                                                                                                        </span>
                                                                                                    </a>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="d_menu_company">
                                                                <a href="{{ url('my-account') }}"
                                                                    class="login-in-register top-header-items">
                                                                    <span>Account</span>
                                                                </a>
                                                            </li>

                                                            <li class="d_menu_company">
                                                                <a class="login-in-register top-header-items"
                                                                    href="{{ route('logout') }}"
                                                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                                                    <span>Logout</span>
                                                                </a>
                                                            </li>
                                                            <li class="d_menu_company">
                                                                @if(strtolower($enable_wholesale_registration->option_value) == 'yes')
                                                                    @if (!empty($wholesale_application_status) && ($wholesale_application_status->status == 0)) 
                                                                        <a href="{{route('create_wholesale_account')}}" class="login-in-register top-header-items" title="Continue Wholesale Application" >{!! \Illuminate\Support\Str::limit('Continue Wholesale Application', 14) !!}</a>
                                                                    @elseif (!empty($wholesale_application_status) && ($wholesale_application_status->status == 1))
                                                                        <a href="{{route('create_wholesale_account')}}" class="login-in-register top-header-items" title="View Wholesale Application" >{!! \Illuminate\Support\Str::limit('View Wholesale Application', 14) !!}</a>
                                                                    @endif
                                                                @endif
                                                            </li>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                        </nav>
                                    </div>
                                @else
                                    <div class="register-counter-details login_link d-flex">
                                        <p class="mb-0 p-2 login-in-register top-header-items ">Login or Register</p>
                                        
                                        @if(strtolower($enable_wholesale_registration->option_value) == 'yes')
                                            <a href="{{route('create_wholesale_account')}}" class="mb-0 p-2 login-in-register top-header-items" >Apply for Wholesale Account</a>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
<script>
    function switch_company_user(contact_id) {
        var company = contact_id;
        jQuery.ajax({
            url: "{{ url('/switch-company/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'companyId': company
            },
            success: function(response) {
                window.location.reload();
            }
        });
    }
</script>
