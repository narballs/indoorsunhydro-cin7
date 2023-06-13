<div class="col-xl-2 col-sm-12 col-xs-12 p-0 bg-white" style="border-radius: 10px !important;">
    <div class="d-flex flex-column align-items-center align-items-sm-start pt-2 text-white min-vh-100">
        <a href="/"
            class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5 d-none d-sm-inline">Menu</span>
        </a>
        <ul class="account_navigation nav nav-pills flex-column w-100 mb-sm-auto mb-0 align-items-center align-items-sm-start"
            id="menu">
            <li class="nav-item w-100 text-dark  mb-3" id="">
                <a href="{{route('my_account')}}" class="nav-link align-middle px-0 ms-3">
                    <i class="fs-4 bi-house"></i>
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/home_nav.png" id="home_active"
                                    style="display: none;">
                                <img src="/theme/img/home_unvisited.png" id="home_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            {{-- <span class=" ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0"
                                onclick="dashboard()" id="dashboard">
                                Dashboard
                            </span> --}}
                            <span class=" ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0">
                                Dashboard
                            </span>
                        </div>


                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-3" id="">
                <a href="{{route('myOrders')}}" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-table"></i>
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/order_visited.png" id="order_active"
                                    style="display: none;">
                                <img src="/theme/img/order_unvisited.png" id="order_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            <span
                                {{-- class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
                                onclick="showOrders()"> --}}
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">
                                Orders
                            </span>
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-3" id="">
                <a href="{{route('myFavorites')}}" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-table"></i>
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/heart-icon.png" id="order_active"
                                    style="display: none;" width="30px" height="30px">
                                <img src="/theme/img/heartfilled.png" id="order_inactive" class="mt-1"
                                    width="28px" height="23px">
                            </span>
                        </div>
                        <div class="col-md-10">
                            {{-- <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link" onclick="wishLists()">
                                My Favorites
                            </span> --}}
                            <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">
                                My Favorites
                            </span>
                            
                        </div>
                    </div>

                </a>
            </li>
           
            <li class="nav-item w-100 mb-3" id="">
                <a href="{{route('address')}}" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-bootstrap"></i>
                    <!-- <span class="ms-1 d-none d-sm-inline text-dark fs-5" onclick="edit_address()">Addresses</span> -->
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/address_active.png" id="order_active"
                                    style="display: none;">
                                <img src="/theme/img/address_inactive.png" id="order_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            {{-- <span
                                class="ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
                                onclick="edit_address()">
                                Addresses
                            </span> --}}
                            <span class="ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">Addresses
                            </span>
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-3" id="">
                <a href="{{route('account_details')}}" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-grid"></i>
                    <!-- <span class="ms-1 d-none d-sm-inline text-dark fs-5" onclick="accountDetails()">Account Details</span> -->
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/account_active.png" id="order_active"
                                    style="display: none;">
                                <img src="/theme/img/account_inactive.png" id="order_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            {{-- <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
                                onclick="accountDetails()">
                                Account Details
                            </span> --}}
                            <span class="ms-1 d-none d-sm-inline fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">Account Details
                            </span>
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-3" id="">
                <a href="{{route('additional_users')}}" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-grid"></i>
                    <!-- <span class="ms-1 d-none d-sm-inline text-dark fs-5" onclick="accountDetails()">Account Details</span> -->
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/account_active.png" id="additional_active"
                                    style="display: none;">
                                <img src="/theme/img/account_inactive.png" id="order_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            {{-- <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
                                onclick="additionalUsers()" id="auto_click">
                                Additional Users
                            </span> --}}
                            <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">
                                Additional Users
                            </span>
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-3">
                <a class="text-white nav-link px-0 align-middle  px-0 ms-3 "
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    <div class="row">
                        <div class="col-md-2 mt-1">
                            <span class="">
                                <img src="/theme/img/logout.png" id="order_inactive">
                            </span>
                        </div>
                        <div class="col-md-10">
                            <span
                                class="ms-1  d-sm-inline text-dark fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link">
                                Logout
                            </span>
                        </div>


                    </div>
                </a>

                <form id="frm-logout" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}

                </form>

            </li>
            <li class="nav-item w-100 mb-3 d-none" id="qoutes">
                <a href="#" class="nav-link px-0 align-middle  px-0 ms-3">
                    <i class="fs-4 bi-table"></i>
                    <div class="row">
                        <div class="col-md-2">
                            <span>
                                <img src="/theme/img/heart-icon.png" id="order_active"
                                    style="display: none;" width="30px" height="30px">
                                <img src="/theme/img/quotation-icon.png" id="order_inactive"
                                    class="mt-1" width="28px" height="23px">
                            </span>
                        </div>
                        <div class="col-md-10">
                            <span
                                class="ms-1 d-none d-sm-inline  fs-5 ms-3 mt-1 ml-0 pl-0 nav-items-link"
                                onclick="qoute()">
                                Quotes
                            </span>
                        </div>
                    </div>

                </a>
            </li>
        </ul>
        <div class="dropdown pb-4">
        </div>
    </div>
</div>