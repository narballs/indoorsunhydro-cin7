@php
    $cart_total = 0;
    $subtotal = 0;
    $tax = 0;
    $free_shipping = 0;
    $contact_id = session()->get('contact_id');
    $cart_items = session()->get('cart');
    $user_id = auth()->id();
    $d_none = 'd-none'; 
    $calculate_free_shipping = 0;
    $enable_free_shipping_banner = App\Models\AdminSetting::where('option_name', 'enable_free_shipping_banner')->first();
    $free_shipping_value  = App\Models\AdminSetting::where('option_name', 'free_shipping_value')->first();
    $announcement_banner = App\Models\AdminSetting::where('option_name' , 'enable_announcement_banner')->first();
    $announcement_banner_text = App\Models\AdminSetting::where('option_name' , 'announcement_banner_text')->first();
    $shipment_for_selected_category  = false;
    
    if (!empty($user_id) && !empty($contact_id)) {
        $contact =  App\Models\Contact::where('user_id', $user_id)->where('contact_id', $contact_id)
            ->orWhere('secondary_id', $contact_id)
            ->first();
        
    } else {
        $contact =  App\Models\Contact::where('user_id', $user_id)->first();
    }
    $tax_class =  App\Models\TaxClass::where('name', $contact->tax_class)->first();
    if (!empty($cart_items)) {
       
        foreach ($cart_items as $cart_item) {
            $product = App\Models\Product::where('product_id' , $cart_item['product_id'])->first();
            if (!empty($product) && !empty($product->categories) && $product->category_id != 0) {
                if (strtolower($product->categories->name) === 'grow medium') {
                    $shipment_for_selected_category = true;
                }
                elseif (!empty($product->categories->parent) && !empty($product->categories->parent->name) && strtolower($product->categories->parent->name) === 'grow medium') {
                    $shipment_for_selected_category = true;
                }
                else {
                    $shipment_for_selected_category = false;
                }
            } else {
                $shipment_for_selected_category = false;
            }
            $subtotal += $cart_item['price'] * $cart_item['quantity'];
        }

        if (!empty($tax_class)) {
            $tax_rate = $subtotal * ($tax_class->rate / 100);
            $tax = $tax_rate;
        }

        $cart_total = $subtotal + $tax;

        if (!empty($free_shipping_value)) {
            $free_shipping = $free_shipping_value->option_value;
        } else {
            $free_shipping = 0;
        }
        $calculate_free_shipping = $free_shipping - $cart_total;
    }
    if ($shipment_for_selected_category == true) {
        $d_none = 'd-none';
        $congrats_div_dnone = 'd-none';
    }
    else {
        if ($calculate_free_shipping <= intval($free_shipping) && $calculate_free_shipping >= 0) {
            $d_none = '';
        } else {
            $d_none = 'd-none';
        }

        if ($cart_total >= intval($free_shipping) && $cart_total >= 0) {
            $congrats_div_dnone = '';
        } else {
            $congrats_div_dnone = 'd-none';
        }
    }

@endphp
@if (!empty($enable_free_shipping_banner) && (strtolower($enable_free_shipping_banner->option_value) == 'yes' && Floatval($cart_total) > 0))
    <div class="w-100 promotional_banner_div {{$d_none}}" id="promotional_banner_div" style="">
        <p class="text-center promotional_banner_text mb-0">
            {{-- <i class="fas fa-shipping-fast"></i>  --}}
            <img src="{{asset('theme/bootstrap5/images/shipping_truck_updated.gif')}}" alt="" class="mr-2" style="max-height: 40px;">
            Only <span class="promotional_banner_span">@if($calculate_free_shipping <= intval($free_shipping)) {{'$' . number_format($calculate_free_shipping , 2)}} @endif</span> left to get free shipping in California
        </p>
    </div>
    <div class="w-100 promotional_banner_div_congrats {{$congrats_div_dnone}}" id="promotional_banner_div_congrats" style="">
        <p class="text-center promotional_banner_text_congrats mb-0">
            {{-- <i class="fas fa-shipping-fast"></i>  --}}
            <img src="{{asset('theme/bootstrap5/images/shipping_truck_updated.gif')}}" alt="" class="mr-2" style="max-height: 40px;">
             <span class="promotional_banner_span_congrats">Good news, your cart qualifies for free shipping</span> 
        </p>
    </div>
@endif
@if (!empty($announcement_banner) && strtolower($announcement_banner->option_value) == 'yes')
<div class="row bg-dark">
    <h6 class="text-white text-center top_header_banner mb-0">
        <i class="fa fa-bullhorn text-white mr-2" aria-hidden="true"></i> {{!empty($announcement_banner_text->option_value) ? $announcement_banner_text->option_value : ''}}
    </h6>
</div>
@endif
@if(!empty($tax_class->rate))
    <input type="hidden" value="{{$tax_class->rate}}" id="tax_rate_number" class="tax_rate_number">
@else
    <input type="hidden" value="0" id="tax_rate_number" class="tax_rate_number">
@endif
<input type="hidden" name="" id="initial_free_shipping_value" class="initial_free_shipping_value" value="{{$free_shipping}}">
<header class="bg-white  text-white top-bar-height w-100 header-top">
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
                                                                        <a href="{{route('view_wholesale_account' , $wholesale_application_status->id)}}" class="login-in-register top-header-items" title="View Wholesale Application" >{!! \Illuminate\Support\Str::limit('View Wholesale Application', 14) !!}</a>
                                                                    @elseif (empty($wholesale_application_status))
                                                                        <a href="{{route('create_wholesale_account')}}" class="login-in-register top-header-items" title="Apply for Wholesale Account" >{!! \Illuminate\Support\Str::limit('Apply for Wholesale Account', 14) !!}</a>
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
