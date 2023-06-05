<?php
$categories = NavHelper::getCategories();
?>
<div class="col-xl-12 col-lg-12 col-md-6  col-sm-6 col-xs-6 p-0 header-top mb-2">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent pb-0 justify-content-start">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNav">
            <div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNavDarkDropdown">
                <div class="collapse navbar-collapse justify-content-center collapse show" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav d-flex">
                        <li class="nav-item dropdown mx-4">
                            <a class="nav-link dropdown-toggle product-mega-menu font_style_menu" href="#"
                                id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false" >
                                Products
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-dark mt-0 pr-4 pl-4"
                                aria-labelledby="navbarDarkDropdownMenuLink" style="width: 346px;">
                                <li><a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
                                        href="{{ url('products') }}" style="font-size: 12px !important"><b>All
                                            Products</b></a></li>
                                @foreach ($categories as $category)
                                    @if ($category->parent_id == 0)
                                        <li>
                                            <a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
                                                id="category_{{ $category->id }}"
                                                href="{{ url('products/' . $category->id . '/' . $category->slug) }}"
                                                style="font-size: 12px !important">
                                                {{ $category->name }}
                                            </a>
                                    @endif
                                    <?php $count = count($category->children); ?>
                                    @if (isset($category->children) && $count > 0)
                                        <ul class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center w-100 ms-2"
                                            style="background-color: none !important;">
                                            @if ($count > 10)
                                                <ul class="dd-horizontal border p-0"
                                                    style="width:900px !important; background-color:white !important; margin:0px !important;">
                                                @else
                                                    <ul class="dd-horizontal pl-0 pr-0"
                                                        style="width:100% !important; background-color:white !imporatnt">
                                            @endif
                                            <div class="row pl-3 pt-0" style="width:100%">
                                                @foreach ($category->children->sortBy('name') as $key => $cat)
                                                    @if ($cat->is_active == 1)
                                                        @if ($count > 10)
                                                            <div class="col-md-2 p-0 w-100"
                                                                style="width:100% !important; background-color:white !important">
                                                            @else
                                                                <div class="col-md-12 pl-0 pr-0"
                                                                    style="width:100% !imporant; background-color:white !important">
                                                        @endif
                                                        @if ($count > 0)
                                                            <li class="dropdown-item p-0"
                                                                id="category_{{ $cat->id }}"
                                                                href="{{ url('products/' . $category->id) }}" ">

                <a class=" link-dark text-decoration-none nav-link product-mega-menu" id="category_{{ $category->id }}"
                href="{{ url('products/' . $cat->id . '/' . $category->slug . '-' . $cat->slug) }}"
                style="font-size: 12px !important">
                {{ $cat->name }}
                </a>
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
                <li class=" nav-item me-3 mx-4">
                    <a class="nav-link text-uppercase nav-item-links font_style_menu" href="#">
                        About
                    </a>
                </li>

                <li class="nav-item me-4 mx-4">
                    <a class="nav-link text-uppercase nav-item-links font_style_menu" href="{{ url('contact-us') }}">
                        Contact
                    </a>
                </li>
                
                </ul>
            </div>
            <!-- here -->
        </div>
</div>
</nav>
</div>
{{-- mobile view start --}}
<div class="container-fluid mobile-view p-0">
    <div class="row">
        <div class="bg-white d-flex" style="border-bottom:1px solid #E9E9E9;padding:0.1rem;">
            @if (Auth::user())
                <div class="mbl_drop_cmpny mx-3">
                    @php
                        $session_contact_company = Session::get('company');
                    @endphp
                    <form style="display:none;" id="frm-logout" action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        <input class="btn btn-link text-white" type="submit" value="logout">
                    </form>
                    <div class="dropdown">
                        @if (!empty($session_contact_company))
                            <a class="btn btn-secondary dropdown-toggle mbl_cmp_drp p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mbl_cmp_drp">{!! \Illuminate\Support\Str::limit($session_contact_company, 14) !!}</span>
                                <i class="fa fa-angle-down" style="color:#242424;"></i>
                            </a>
                        @else
                            <a class="btn btn-secondary dropdown-toggle mbl_cmp_drp p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mbl_cmp_drp">Select Company</span>
                                <i class="fa fa-angle-down" style="color:#242424;"></i>
                            </a>
                        @endif
                        @php
                            $companies = Session::get('companies');
                        @endphp
                        <div class="dropdown-menu mb_item_mnu" aria-labelledby="dropdownMenuButton">
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
                                        <a class="mb_item" {{ $disabled }} {{ $muted }} type="button" onclick="switch_company_user({{ $contact_id }})">
                                            {{ $company->company }}
                                            <span
                                                style="font-size: 9px;font-family: 'Poppins';"
                                                class="{{ $muted }}">{{ $primary }}
                                            </span>
                                        </a>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mbl_drop_othr">
                    <div class="d-flex justify-content-between">
                        <div class="col-sm-6">
                            <a class="font-mobile-class" href="{{ url('my-account') }}">Account</a>
                        </div>
                        <div class="col-sm-6">
                            <a class="font-mobile-class" href="{{ route('logout') }}"onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-sm-6">
                    <a class="font-mobile-class float-right" href="{{ '/user/' }}">Login or Register</a>
                </div>
            @endif
        </div>
        <div class="col-sm-12 my-0">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <div class="container-fluid mobile_nav_items mt-2">
                    
                    <div class="mobile_menu_bar_togle">
                        <button style="" class="navbar-toggler mobile_nav_btn mt-1 p-0 ml-1" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars" aria-hidden="true" style="color:#242424;"></i>
                        </button>
                    </div>
                    
                    <div class="mobile_menu_bar_logo">
                        <a class="navbar-brand" href="/">
                            <img class="top-img mx-0" src="/theme/img/indoor_sun.png" style="height:35px !important;">
                        </a>
                    </div>
                    <div class="mobile_menu_bar_cart">
                        <a href="{{ '/cart/' }}">
                            <img class="basket-icon mt-2" src="{{asset('/theme/img/icons/Cart-icon.svg')}}">
                            <span class="cartQtymbl cart-basket d-flex align-items-center justify-content-center float-sm-end cart-counter rounded-circle " id="top_cart_quantity">
                               {{ $total_quantity }}
                            </span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle product-mega-menu ps-1" href="#"
                                    id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" style="width: 346px">
                                    Products
                                </a>
                                <ul class="dropdown-menu l dropdown-menu-dark mt-0 pr-4 pl-4"
                                    aria-labelledby="navbarDarkDropdownMenuLink" style="width: 346px;">
                                    <li>
                                        <a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
                                            href="{{ url('products') }}"><b>All Products</b>
                                        </a>
                                    </li>
                                    @foreach ($categories as $category)
                                    @if ($category->parent_id == 0)
                                    <li>
                                        <a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu "
                                            id="category_{{ $category->id }}"
                                            href="{{ url('products/' . $category->id . '/' . $category->slug) }}">
                                            {{ $category->name }}
                                        </a>
                                        @endif
                                        <?php $count = count($category->children); ?>
                                        @if (isset($category->children) && $count > 0)
                                        <ul class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center">
                                            @if ($count > 10)
                                                <ul class="dd-horizontal border p-0 pr-4" style="width:800px">
                                                    @else
                                                    <ul class="dd-horizontal pl-0 pr-0" style="width:100%">
                                                        @endif
                                                        <div class="row pl-4 pt-0 pr-4">
                                                            @foreach ($category->children as $key => $cat)
                                                                @if ($cat->is_active == 1)
                                                                    @if ($count > 10)
                                                                        <div class="col-md-3 pl-0 pr-0" style="width:600px">
                                                                    @else
                                                                        <div class="col-md-12 pl-0 pr-0" style="width:100%">
                                                                    @endif
                                                                    @if ($count > 0)
                                                                        <li class="dropdown-item"
                                                                            id="category_{{ $cat->id }}"
                                                                            href="{{ url('products/' . $category->id) }}">

                                                                            <a class="link-dark text-decoration-none nav-link product-mega-menu"
                                                                                id="category_{{ $category->id }}"
                                                                                href="{{ url('products/' . $cat->id . '/' . $category->slug . '-' . $cat->slug) }}">{{ $cat->name }}</a>
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
                                <a class="nav-link text-uppercase nav-item-links ps-1" href="#">
                                    About
                                </a>
                            </li>
                            <li class="nav-item me-4">
                                <a class="nav-link text-uppercase nav-item-links ps-1" href="{{ url('contact-us') }}">
                                    Contact
                                </a>
                            </li>
                            {{-- <li class="nav-item me-3">
                                <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ url('my-account') }} ">My
                                    account
                                </a>
                            </li> --}}
                            @if (session('logged_in_as_another_user'))
                                <li class="nav-item me-3">
                                    <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ url('admin/go-back') }} ">Go Back
                                    </a>
                                </li>
                            @endif
                            {{-- @if (Auth::user())
                                @php
                                    $session_contact_company = Session::get('company');
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link text-uppercase nav-item-links p-0 ps-4" href="{{ '/user/' }}">
                                        <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                            <span class="menu-title">Logout</span>
                                        </a>
                                        <form style="display:none;" id="frm-logout" action="{{ route('logout') }}"
                                            method="POST">
                                            {{ csrf_field() }}
                                            <input class="btn btn-link text-white" type="submit" value="logout">
                                        </form>
                                    </a>
                                </li>
                                <li class="nav-item dropdown position-static">
                                    @if (!empty($session_contact_company))
                                        <a style="margin-left: 14px;" class="nav-link dropdown-toggle" href="#"
                                            id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
                                            aria-expanded="false">
                                            <span class="select-comapny-top-bar-mobile d-flex justify-content-lg-start">
                                                ({{ $session_contact_company }})</span>
                                        </a>
                                    @else
                                        <a class="nav-link dropdown-toggle p-0  text-white" href="#"
                                            id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
                                            aria-expanded="false">
                                            <span class="text-dark">Select
                                                Company</span>
                                        </a>
                                    @endif
                                    @php
                                        $companies = Session::get('companies');
                                        
                                    @endphp
                                    <div class="dropdown-menu w-100 mt-0" aria-labelledby="navbarDropdown"
                                        style="border-top-left-radius: 0;
                                        border-top-right-radius: 0;
                                        margin-left: 13px !important;
                                        margin-top: -6px !important;">
                                        <div class="container">
                                            <div class="row my-4">
                                                <div class="col-md-12">
                                                    <div class="list-group list-group-flush">
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
                                                                    <a type="button"
                                                                        class="list-group-item list-group-item-action p-0 {{ $disabled }} {{ $muted }}"
                                                                        onclick="switch_company_user({{ $contact_id }})">{{ $company->company }}
                                                                        <span style="font-size: 9px;font-family: 'Poppins';"
                                                                            class="{{ $muted }}">{{ $primary }}
                                                                        </span>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li class="nav-item me-3">
                                    <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ url('my-account') }} ">
                                        login or register
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="col-sm-11 mb-2 mt-2">
            <form class="" method="get" action="{{ route('product_search') }}">
                <input type="hidden" id="is_search" name="is_search" value="1">
                <div class="input-group top-search-group w-100">
                    <input type="text" class="form-control" placeholder="What are you searching for"
                        aria-label="Search" aria-describedby="basic-addon2" id="search" name="value"
                        value="{{ isset($searched_value) ? $searched_value : '' }}" style="border:2px solid #7bc533;">
                    <span class="input-group-text" id="search-addon" style="border:2px solid #7bc533;">
                        <button class="btn-info" type="submit" id="search"
                            style="background: transparent;border:none">
                            <i class="text-white" data-feather="search"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- mobile view end --}}

{{-- ipad view start --}}
<div class="container-fluid ipad-view p-0 m-0">
    <div class="row">
        <div class="bg-dark ipad_second_row" style="">
            <img class="basket-icon" src="/theme/img/Bascket.png">
            <span
                class="cartQtyipad cart-basket d-flex align-items-center justify-content-center float-sm-end cart-counter rounded-circle"
                id="top_cart_quantity">
                {{ $total_quantity }}
            </span>
            <a class="p-0 cart-price btn btn-secondary" data-bs-container="body" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="add <strong class='text-success'>$2500.00</strong> more to your cart and get <span class='text-danger'>5% off </span>"><span
                    id="top_cart_total">
                    <a href="{{ '/cart/' }}" class="text-white">
                        <span id="topbar_cart_total" class="ms-2 cart-counter-details">
                            {{ number_format($grand_total, 2) }}
                        </span>&nbsp;
                        (<span id="cart_items_quantity"
                            class="cart-counter-details">{{ $total_quantity }}</span>&nbsp;<span
                            class="cart-counter-details">items
                        </span>)
                    </a>
                </span>
            </a>
        </div>
        <div>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid ipad_nav_items">
                    <a class="navbar-brand" href="/"><img class="top-img" src="/theme/img/indoor_sun.png"
                            ;></a>
                    <button style="" class="navbar-toggler p-2 mt-2 text-white text-uppercase ipad_menu_btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        {{-- <span class="navbar-toggler-icon"></span> --}}
                        <i class="fa fa-bars" aria-hidden="true"></i> Menu
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle product-mega-menu ps-4" href="#"
                                    id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Products
                                </a>
                                <ul class="dropdown-menu l dropdown-menu-dark mt-0 pr-4 pl-4"
                                    aria-labelledby="navbarDarkDropdownMenuLink" style="width: 346px;">
                                    <li><a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
                                            href="{{ url('products') }}"><b>All Products</b></a></li>
                                    @foreach ($categories as $category)
                                        @if ($category->parent_id == 0)
                                            <li>
                                                <a class="link-dark dropdown-item text-decoration-none nav-link product-mega-menu"
                                                    id="category_{{ $category->id }}"
                                                    href="{{ url('products/' . $category->id . '/' . $category->slug) }}">
                                                    {{ $category->name }}
                                                </a>
                                        @endif
                                        <?php $count = count($category->children); ?>
                                        @if (isset($category->children) && $count > 0)
                                            <ul
                                                class="dropdown-menu-dark pl-0 pr-0 border mt-0 dropdown-submenu rounded-2 text-center">
                                                @if ($count > 10)
                                                    <ul class="dd-horizontal border p-0 pr-4" style="width:800px">
                                                    @else
                                                        <ul class="dd-horizontal pl-0 pr-0" style="width:100%">
                                                @endif
                                                <div class="row pl-4 pt-0 pr-4">

                                                    @foreach ($category->children as $key => $cat)
                                                        @if ($cat->is_active == 1)
                                                            @if ($count > 10)
                                                                <div class="col-md-3 pl-0 pr-0" style="width:600px">
                                                                @else
                                                                    <div class="col-md-12 pl-0 pr-0"
                                                                        style="width:100%">
                                                            @endif
                                                            @if ($count > 0)
                                                                <li class="dropdown-item"
                                                                    id="category_{{ $cat->id }}"
                                                                    href="{{ url('products/' . $category->id) }}">

                                                                    <a class="link-dark text-decoration-none nav-link product-mega-menu"
                                                                        id="category_{{ $category->id }}"
                                                                        href="{{ url('products/' . $cat->id . '/' . $category->slug . '-' . $cat->slug) }}">{{ $cat->name }}</a>
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
                        <a class="nav-link text-uppercase nav-item-links ps-4" href="#">
                            About
                        </a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ url('contact-us') }}">
                            Contact
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link text-uppercase nav-item-links ps-4" href="{{ url('my-account') }} ">My
                            account
                        </a>
                    </li>
                    @if (Auth::user())
                        <li class="nav-item">
                            <a class="nav-link text-uppercase nav-item-links p-0" href="{{ '/user/' }}">
                                {{-- <img src="/theme/img/User.png" width="35px" height="35px"> --}}
                                <a class="nav-link text-uppercase nav-item-links" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                    <span class="menu-title ps-4">Logout</span>
                                </a>
                                <form style="display:none;" id="frm-logout" action="{{ route('logout') }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    <input class="btn btn-link text-white" type="submit" value="logout">
                                </form>
                            </a>
                        </li>
                        <li class="nav-item dropdown position-static">
                            @if (!empty($session_contact_company))
                                <a style="margin-left: 23px;" class="nav-link dropdown-toggle" href="#"
                                    id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="select-comapny-top-bar-mobile d-flex justify-content-lg-start">
                                        ({{ $session_contact_company }})</span>
                                </a>
                            @else
                                <a class="nav-link dropdown-toggle p-0  text-white" href="#"
                                    id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="text-dark">Select Company</span>
                                </a>
                            @endif
                            <!-- Dropdown menu -->
                            @php
                                $companies = Session::get('companies');
                            @endphp
                            <div class="dropdown-menu w-100 mt-0" aria-labelledby="navbarDropdown"
                                style="border-top-left-radius: 0;
								border-top-right-radius: 0;
								margin-left: 13px !important;
								margin-top: -6px !important;">
                                <div class="container">
                                    <div class="row my-4">
                                        <div class="col-md-12">
                                            <div class="list-group list-group-flush">
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
                                                            <a type="button"
                                                                class="list-group-item list-group-item-action p-0 {{ $disabled }} {{ $muted }}"
                                                                onclick="switch_company_user({{ $contact_id }})">{{ $company->company }}
                                                                <span style="font-size: 9px;font-family: 'Poppins';"
                                                                    class="{{ $muted }}">{{ $primary }}
                                                                </span>
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="register-counter-details ps-4">
                                login or register
                            </div>
                        </li>
                    @endif
                    </ul>
                </div>
        </div>
        </nav>
    </div>
    <div class="col-md-12 mb-4">
        <form class="d-flex mt-3" method="get" action="{{ route('product_search') }}">
            <input type="hidden" id="is_search" name="is_search" value="1">
            <div class="input-group top-search-group w-100">
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
</div>
{{-- ipad view end --}}
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
