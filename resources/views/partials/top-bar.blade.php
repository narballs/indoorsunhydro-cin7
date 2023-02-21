<header class="p-2 bg-dark text-white top-bar-height w-100 header-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-2">
                <div class="d-grid gap-2 my-2">
                    @if (session('logged_in_as_another_user'))
                    <a href="{{url('admin/go-back')}}" class="btn text-white top-bar-logout">Go Back</a>
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-end">
                <a href="{{'/user/'}}" class="text-white d-flex align-items-end mt-2">
                    <div>
                        <img src="/theme/img/User.png" width="35px" height="35px">
                    </div>
                    @if(Auth::user())
                    <div>
                        <a class="text-white top-bar-logout" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            <span class="menu-title">Logout</span>
                        </a>

                        <form style="display:none;" id="frm-logout" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            <input class="btn btn-link text-white" type="submit" value="logout">
                        </form>
                    </div>
                    @else
                    <div class="register-counter-details">Login or Register</div>
                    @endif
                </a>
            </div>

            <div class="col-md-2 d-flex justify-content-center align-items-center mt-2">
                <div style="font-family: 'Poppins';">
                    <img class="basket-icon" src="/theme/img/Bascket.png">
                    <span
                        class="cart-basket d-flex align-items-center justify-content-center float-sm-end cart-counter rounded-circle"
                        id="top_cart_quantity">
                        <?php
      						$total_quantity	 = 0;
      						$grand_total = 0;

      					?>

                        @if(Session::get('cart'))
                        @foreach(Session::get('cart') as $cart)
                        <?php
                            $total_q[] = $cart['quantity'];
                            $total_quantity = array_sum($total_q);
                            $total_price[] = $cart['price'] * $cart['quantity'];
                            $grand_total = array_sum($total_price);
                            ?>
                        @endforeach
                        @endif
                        {{$total_quantity}}
                    </span>
                </div>

                <div>
                    <a class="p-0 cart-price btn btn-secondary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="right"
                        data-bs-content="add <strong class='text-success'>$2500.00</strong> more to your cart and get <span class='text-danger'>5% off </span>"><span
                            id="top_cart_total"><a href="{{'/cart/'}}" class="text-white d-flex align-items-end"><span
                                    id="topbar_cart_total" class="ms-2 cart-counter-details">
                                    {{number_format($grand_total, 2)}}</span>&nbsp;(<span id="cart_items_quantity"
                                    class="cart-counter-details">{{$total_quantity}}</span>&nbsp;<span
                                    class="cart-counter-details">items</span> )
                            </a>
                </div>
            </div>
        </div>
    </div>
</header>