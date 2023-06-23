<ul class="nav order_detail_tabs">
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link order_left_border @yield('order-active')" href="{{ url('my-account') }}">Orders</a>
    </li>
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link  @yield('account-active')" href="{{ route('account_profile') }}">Profile</a>
    </li>
    <li class="text-center" style="width: 123px !important ;">
        <a class="nav-link @yield('addresses-active')" href="{{ route('my_account_address') }}">Addresses</a>
    </li>
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('my-favorites-active')" href="{{ route('my_favorites') }}">Favorites</a>
    </li>

    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('users-active')" href="{{ route('additional_users') }}">Users</a>
    </li>
</ul>
