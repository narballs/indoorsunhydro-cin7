<header class="p-2  text-white top-bar-height w-100 header-top">
    <div class="container" style=" max-width: 1468px !important;">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-2">
                <div class="d-grid gap-2 my-2">
                    @if (session('logged_in_as_another_user'))
                        <a href="{{ url('admin/go-back') }}" class="btn text-white top-bar-logout">Go Back</a>
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-center align-items-center mt-2">
                <div style="font-family: 'Poppins';">
                    <img class="basket-icon" src="/theme/img/Bascket.png">
                    <span
                        class="cart-basket d-flex align-items-center justify-content-center float-sm-end cart-counter rounded-circle"
                        id="top_cart_quantity">
                        <?php
                        $total_quantity = 0;
                        $grand_total = 0;
                        
                        ?>
                        @if (Session::get('cart'))
                            @foreach (Session::get('cart') as $cart)
                                <?php
                                $total_q[] = $cart['quantity'];
                                $total_quantity = array_sum($total_q);
                                $total_price[] = $cart['price'] * $cart['quantity'];
                                $grand_total = array_sum($total_price);
                                ?>
                            @endforeach
                        @endif
                        {{ $total_quantity }}
                    </span>
                </div>
                <div>
                    <a class="p-0 cart-price btn btn-secondary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="right"
                        data-bs-content="add <strong class='text-success'>$2500.00</strong> more to your cart and get <span class='text-danger'>5% off </span>"><span
                            id="top_cart_total"><a href="{{ '/cart/' }}"
                                class="text-white d-flex align-items-end ms-3"
                                style="    margin-top: -18px !important;"><span id="topbar_cart_total"
                                    class="ms-2 cart-counter-details">
                                    {{ number_format($grand_total, 2) }}</span>&nbsp;(<span id="cart_items_quantity"
                                    class="cart-counter-details">{{ $total_quantity }}</span>&nbsp;<span
                                    class="cart-counter-details">items</span> )
                            </a>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-end">
                <a href="{{ '/user/' }}" class="text-white d-flex align-items-end mt-2">
                    <div>
                        <img src="/theme/img/users-icons-top-bar (2).png" class="img-fluid w-100">
                    </div>
                    @if (Auth::user())
                        <div>
                            @php
                                $session_contact_company = Session::get('company');
                            @endphp
                            <form style="display:none;" id="frm-logout" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                                <input class="btn btn-link text-white" type="submit" value="logout">
                            </form>
                            <nav class="navbar navbar-expand-lg navbar-light company-nave-bar-conatainer"
                                style="margin-top: -12px !important">
                                <!-- Toggle button -->
                                <button class="navbar-toggler px-0 text-light" type="button" data-mdb-toggle="collapse"
                                    data-mdb-target="#navbarExample1" aria-controls="navbarExample1"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <!-- Collapsible wrapper -->
                                <div class="collapse navbar-collapse" id="navbarExample1">
                                    <!-- Left links -->
                                    <ul class="navbar-nav me-auto ps-lg-0" style="padding-left: 0.15rem">
                                        <!-- Navbar dropdown -->
                                        <li class="nav-item dropdown position-static">
                                            @if (!empty($session_contact_company))
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                                    role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                                                    <span class="top-bar-logout text-light">
                                                        ({{ $session_contact_company }})</span>
                                                </a>
                                            @else
                                                <a class="nav-link dropdown-toggle p-0  text-white" href="#"
                                                    id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <img src="/theme/img/users-icons-top-bar (2).png" class="img-fluid">
                                                    <span class="text-white">Select
                                                        Company</span>
                                                </a>
                                            @endif
                                            <!-- Dropdown menu -->
                                            @php
                                                $companies = Session::get('companies');

                                            @endphp
                                            <div class="dropdown-menu w-100 mt-0" aria-labelledby="navbarDropdown"
                                                style="
                                       border-top-left-radius: 0;
                                       border-top-right-radius: 0;">
                                                <div class="container">
                                                    <div class="row my-4">
                                                        <div class="col-md-12">
                                                            <div class="list-group list-group-flush">
                                                                @if (Auth::user())
                                                                    @if($companies)
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
                                                                                }
                                                                                else {
                                                                                    $disabled = '';
                                                                                    $disable_text = '';
                                                                                    $muted = '';
                                                                                }

                                                                            @endphp
                                                                            <a type="button" 
                                                                                class="list-group-item list-group-item-action {{$disabled}} {{$muted}}" 
                                                                                onclick="switch_company_user({{ $contact_id }})">{{ $company->company }}
                                                                                <span
                                                                                    style="font-size: small;" class="{{$muted}}">{{ $primary }} </span></a>
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li style="margin: auto !important;">
                                            <a class="text-white top-bar-logout" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                                <span class="menu-title">
                                                    <img src="/theme/img/back-last-icons.png" alt=""
                                                        class="img-fluid">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Logout
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    @else
                        <div class="register-counter-details ms-2">
                            <p class="mb-2 login-in-register">Login or Register</p>
                        </div>
                    @endif
                </a>
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
