<ul class="nav order_detail_tabs">
    <li class="text-center my-account-list-items-li" style="width: 109px;">
        <a class="nav-link my-account-list-items order_left_border @yield('order-active')" href="{{ url('my-account') }}">Orders</a>
    </li>
    <li class="text-center my-account-list-items-li" style="width: 109px;">
        <a class="nav-link my-account-list-items   @yield('account-active')" href="{{ route('account_profile') }}">Profile</a>
    </li>
    <li class="text-center my-account-list-items-li" style="width: 123px;">
        <a class="nav-link my-account-list-items  @yield('addresses-active')" href="{{ route('my_account_address') }}">Addresses</a>
    </li>
    <li class="text-center my-account-list-items-li" style="width: 109px;">
        <a class="nav-link my-account-list-items  @yield('my-favorites-active')" href="{{ route('my_favorites') }}">Favorites</a>
    </li>

    <li class="text-center my-account-list-items-li" style="width: 109px;">
        <a class="nav-link my-account-list-items  @yield('users-active')" href="{{ route('additional_users') }}">Users</a>
    </li>
</ul>
