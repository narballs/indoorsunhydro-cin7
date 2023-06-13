@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .nav .active {
        background: #F5F5F5;
        /* border-left: none !important; */
        /* color: green !important; */
        color: #008AD0 !important;
    }

    nav svg {
        max-height: 20px !important;
    }

    #spinner-global {
        display: none !important;
    }

    input[type=number]::-webkit-outer-spin-button {

        opacity: 20;

    }
</style>
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid" style="width:1621px  !important;">
    <div class="row bg-light">
        <div class="container-fluid" id="main-row">
            @include('my-account.my-account-top-bar')
            <div class="row flex-xl-nowrap p-0 m-0 mr-3">
                @include('my-account.my-account-side-bar')

                <div class="col-xl-10 col-sm-12 col-xs-12 py-3 bg-white ms-3" style="border-radius: 10px !important;">
                    <div class="edit_address mt-3 mb-3 pr-4" id="edit_address">
                        <div class="col-md-12 border-bottom border-4 p-0 ms-3 mr-3">
                            <div class="row mb-4 mt-3">
                                <div class="col-md-4 ">
                                    <img src="/theme/img/addresses_main.png" style="margin: -1px 2px 1px 1px;">
                                    <span class="pt-1 my-account-content-heading">Addresses</span>
                                </div>
                                <div class="col-md-8">
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue ms-3 mt-3">
                            <span class="billing-address-heading">Billing Address</span>
                        </div>
                        <div class="ms-3 mt-3">
                            <p class="table-row-content">The following addresses will be used on the checkout page
                                by default.</p>
                        </div>
                        <div class="row table-row-content">
                            <div class="col-md-5">
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-md-10 billing-address-heading-subtitle">Billing Address
                                        </div>
                                        <div class="col-md-2">@include('modal.my-account-modal')</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 name">
                                            <span class="user_names">{{ $user_address->firstName }}
                                                {{ $user_address->lastName }}</span>
                                            {{ $user_address->postalAddress1 }}{{ $user_address->postalAddress2 }}
                                        </div>
                                    </div>
                                    <div class="name">

                                    </div>
                                    <div class="row m-0">
                                        {{ $user_address->postalCity }} {{ $user_address->postalState }}
                                        {{ $user_address->postalPostCode }}
                                    </div>

                                </div>
                                <div style="display:none">@include('modal.my-account-modal')</div>
                            </div>
                            <div class="col-md-5 border-start ms-4">
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-md-10 billing-address-heading-subtitle">
                                            Shipping Address
                                        </div>
                                        <div class="col-md-2">@include('modal.my-account-modal')</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 name">
                                            <span class="user_names">{{ $user_address->firstName }}
                                                {{ $user_address->lastName }}</span>
                                            {{ $user_address->postalAddress1 }}{{ $user_address->postalAddress2 }}
                                        </div>
                                    </div>
                                    <div class="name">

                                    </div>
                                    <div class="row m-0">
                                        {{ $user_address->postalCity }} {{ $user_address->postalState }}
                                        {{ $user_address->postalPostCode }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
