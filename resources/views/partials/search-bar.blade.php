<div class="container-fluid mt-3 header-top">
    <div class="row header-top justify-content-center align-items-center">
        <div class="col-md-3 col-xl-4 col-lg-4 d-flex align-items-center justify-content-center">
            <a class="navbar-brand d-flex justify-content-center" href="/">
                <img class="logo_image_main" src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}" ;>
            </a>
        </div>
        <div class="col-md-5 col-lg-5 col-xl-5 top-reach-bar d-flex align-items-center justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <form class="" method="get" action="{{route('product_search')}}">
                        <input type="hidden" id="is_search" name="is_search" value="1">
                        <div class="input-group ">
                            <input type="text" style="border:2px solid #7BC533 !important;" class="form-control remove_shadow_search" placeholder="What are you searching for" aria-label="Search"
                                aria-describedby="basic-addon2" id="search" name="value"
                                value="{{ isset($searched_value) ? $searched_value : '' }}">
                            <span class="input-group-text" id="search-addon" style="border:2px solid #7BC533 !important;">
                                <button class="btn-info" type="submit" id="search" style="background: transparent;border:none">
                                    {{-- <i class="text-white" data-feather="search" style="width: 20px; !important;"></i> --}}
                                    <i class="fa fa-search" style="font-size:16px;"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 col-xl-3">
            <a href="{{ '/cart/' }}" style="color:#7BC533; !important;text-decoration:none !important;">
                <div class="col-md-12">
                    <div class="d-flex">
                        <div class="col-md-4" style="font-family: 'Poppins';">
                            <img class="basket-icon mt-2 pt-1" src="{{asset('/theme/img/icons/Cart-icon.svg')}}">
                            <span
                                class="cart-basket d-flex align-items-center justify-content-center float-sm-end cart-counter rounded-circle ml-3"
                                id="top_cart_quantity"  style="border:2.4px solid #FFFFFF">
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
                        <div class="d-flex" style="flex-direction: column;">
                            <span class="shopping_cart_text" style="margin-bottom:-7px;color:#212121 !important;">Shopping Cart</span>
                            {{-- <a style="" class="p-0 cart-price btn btn-secondary mt-0" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="add <strong class='text-success'>$2500.00</strong> more to your cart and get <span class='text-danger'>5% off </span>"> --}}
                                
                            <a>
                                <span id="top_cart_total"><a href="{{ '/cart/' }}"  class="d-flex align-items-center" style="color: #7bc533">
                                <span id="topbar_cart_total" class="cart-counter-details"> ${{ number_format($grand_total, 2) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-8 col-md-12 col-sm-6 col-xs-6 mt-2 top-header-navigation p-0">
            @include('partials.nav')
        </div>
    </div>
</div>

<div class="container-fluid mobile-view ">
    <div class="w-100">
        @include('partials.nav')
    </div>
</div>