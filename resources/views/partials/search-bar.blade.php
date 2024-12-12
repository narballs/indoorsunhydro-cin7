<div class="container-fluid mt-3 header-top">
    <div class="row header-top justify-content-center align-items-center align-items-md-start">
        <div class="col-md-3 col-xl-4 col-lg-4 d-flex align-items-center align-items-md-start justify-content-center">
            <a class="navbar-brand d-flex justify-content-center" href="/">
                <img class="logo_image_main" src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}" ;>
            </a>
        </div>
        <div class="col-md-5 col-lg-5 col-xl-5 top-reach-bar d-flex align-items-center justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <form class="content_class" method="get" action="{{route('product_search')}}">
                        <input type="hidden" id="is_search" name="is_search" value="1">
                        <div class="col-md-12">
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
                        </div>
                    
                        <div class="col-md-12">
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 custom-col-6 col-xl-4 d-flex-align-items-center">
                                    <input type="radio" class="main_search_filter ml-1" name="main_search_filter" value="title" {{(!empty($filter_value_main) || empty($filter_value_main) || ($filter_value_main != 'description' && $filter_value_main != 'title')) ? 'checked' : ''}}>
                                    <label class="main_filter_label mb-0" for="">Search Product Title</label>
                                </div>
                                <div class="col-lg-12 col-md-12 custom-col-6 col-xl-4 d-flex-align-items-center">
                                    <input type="radio" class="main_search_filter ml-1" name="main_search_filter" value="description" {{!empty($filter_value_main) && ($filter_value_main == 'description') ? 'checked' : ''}}>
                                    <label class="main_filter_label mb-0" for="">Search Description</label>
                                </div>
                                <div class="col-lg-12 col-md-12 custom-col-6 col-xl-4 d-flex-align-items-center">
                                    <input type="radio" class="main_search_filter ml-1" name="main_search_filter" value="title_description" {{!empty($filter_value_main) && ($filter_value_main == 'title_description') ? 'checked' : ''}}>
                                    <label class="main_filter_label mb-0" for="">Search Title & Description</label>
                                </div>
                            </div>
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
                                $cart_detail = App\Helpers\UtilHelper::cart_detail($total_quantity, $grand_total);
                                ?>
                                {{-- @if (Session::get('cart'))
                                    @foreach (Session::get('cart') as $cart)
                                        <?php
                                        // $total_q[] = $cart['quantity'];
                                        // $total_quantity = array_sum($total_q);
                                        // $total_price[] = $cart['price'] * $cart['quantity'];
                                        // $grand_total = array_sum($total_price);
                                           
                                        ?>
                                    @endforeach
                                @endif --}}
                                {{ !empty($cart_detail) && !empty($cart_detail['total_quantity']) ? $cart_detail['total_quantity'] : $total_quantity}}
                            </span>
                        </div>
                        <div class="d-flex ml-1" style="flex-direction: column;">
                            <span class="shopping_cart_text" style="margin-bottom:-7px;color:#212121 !important;">Shopping Cart</span>
                            {{-- <a style="" class="p-0 cart-price btn btn-secondary mt-0" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="add <strong class='text-success'>$2500.00</strong> more to your cart and get <span class='text-danger'>5% off </span>"> --}}
                                
                            <a>
                                <span id="top_cart_total"><a href="{{ '/cart/' }}"  class="d-flex align-items-center" style="color: #7bc533">
                                <span id="topbar_cart_total" class="cart-counter-details"> ${{!empty($cart_detail) && !empty($cart_detail['grand_total']) ? number_format($cart_detail['grand_total'], 2) : $grand_total }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-8 col-xxl-6 col-lg-10 col-md-12 col-sm-6 col-xs-6 mt-2 top-header-navigation p-0">
            @include('partials.nav')
        </div>
    </div>
</div>

<div class="container-fluid mobile-view ">
    <div class="w-100">
        @include('partials.nav')
    </div>
</div>
<div class="w-100 mb-2">
    <div class="alert alert-success alert-dismissible d-none mb-0 text-center notify_user_div">
        <a href="#" onclick="hide_notify_user_div()" class="close" aria-label="close">&times;</a>
        <span class="notify_text"></span>
    </div>
</div>


<div class="w-100 mb-2 newsletter_div d-none ">
    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2 text-center d-none" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif (\Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 text-center d-none" role="alert">
            {!! \Session::get('error') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
<style>
    .main_search_filter{
        /* accent-color: #7BC533; */
    }
    .content_class {
        display: contents;
    }
    .main_filter_label {
        font-size: 15px !important;
        font-weight: 600 !important;
    }
    @media screen and  (min-width: 1200px) and (max-width : 1752px) {
        .custom-col-6 {
            -webkit-box-flex: 0;
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>