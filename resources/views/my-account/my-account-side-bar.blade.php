<ul class="nav order_detail_tabs" style="width:547.62px;border-radius:6px !important;border: 1.29139px solid #E1E1E1;">
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('order-active')" href="{{ url('my-account') }}">Orders</a>
    </li>
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link  @yield('account-active')" href="{{ route('account_profile') }}">Profile</a>
    </li>
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('addresses-active')" href="{{ route('my_account_address') }}">Addresses</a>
    </li>
    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('my-favorites-active')" href="{{ route('my_favorites') }}">Favorites</a>
    </li>

    <li class="text-center" style="width: 109px !important ;">
        <a class="nav-link @yield('users-active')" href="{{ route('additional_users') }}">Users</a>
    </li>
</ul>
