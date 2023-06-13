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
                    <div class="additional-users pr-2" id="additional-users">
                        <div class="row mt-3 detail-heading ms-2 mr-0 ml-0 p-0" id="detail-heading">

                            <div class="col-md-12 border-bottom border-4 p-0 mr-3">
                                <div class="row mb-4 mt-3">
                                    <div class="col-md-4 ">
                                        <img src="/theme/img/account_details.png" style="margin: -1px 2px 1px 1px;">
                                        @if ($parent)
                                            <span class="pt-1 my-account-content-heading">Primary Contact</span>
                                        @else
                                            <span class="pt-1 my-account-content-heading">Secondary Contact</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        @if (!$parent)
                                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                Create Secondary User
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0 pr-0 mr-0 ml-0 w-100">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>
                                            Company
                                        </th>
                                        <th>
                                            First Name
                                        </th>
                                        <th>
                                            Last Name
                                        </th>
                                        <th>
                                            Job Title
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th>
                                            Phone
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="secondary_user">
                                    <?php $secondary_contacts; ?>
                                    @foreach ($secondary_contacts as $childeren)
                                        <tr id="row-{{ $childeren->id }}">
                                            @if ($childeren->company)
                                                <td>
                                                    {{ $childeren->company }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            @if ($childeren->firstName)
                                                <td>
                                                    {{ $childeren->firstName }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            @if ($childeren->lastName)
                                                <td>
                                                    {{ $childeren->lastName }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            @if ($childeren->jobTitle)
                                                <td>
                                                    {{ $childeren->jobTitle }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            @if ($childeren->email)
                                                <td>
                                                    {{ $childeren->email }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            @if ($childeren->phone)
                                                <td>
                                                    {{ $childeren->phone }}
                                                </td>
                                            @elseif ($childeren->mobile)
                                                <td>
                                                    {{ $childeren->mobile }}
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn btn-success btn-sm">empty</span>
                                                </td>
                                            @endif
                                            <td>
                                                @php
                                                @endphp
                                                <a href="{{ url('send-password/fornt-end/' . $childeren->user_id) }}"
                                                    class="btn btn-secondary btn-sm">send password</a>
                                            </td>
                                            <td>
                                                @if ($childeren->contact_id != null)
                                                    <span class="btn btn-primary btn-sm">primary contact</span>
                                                @else
                                                    <span class="btn btn-secondary btn-sm">secondary contact</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row ms-2 mb-5 d-none" id="address_row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9 pl-1">
                                <div class="row mt-3 " style="margin:auto;">
                                    <div class="col bg-white mr-3" style="border-radius: 10px !important;">
                                        <div class="mt-4 mb-4"><img src="/theme/img/user_address.png"><span
                                                class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order
                                                Details</span>
                                        </div>
                                        <div class="border-bottom"></div>
                                        <div id="address_table" class="mt-3 mb-4"></div>
                                    </div>
                                    <div class="col pl-1 bg-white"
                                        style="border-radius: 10px; border: 1px solid #008AD0!important;">
                                        <div class="mt-4 mb-4 ms-3"><img src="/theme/img/shipping_address2.png"><span
                                                class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order
                                                Details</span>
                                        </div>
                                        <div class="border-bottom ms-3"></div>
                                        <div class="ms-3">
                                            <div id="shipping_table" class="mt-3 mb-4"></div>
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
</div>
@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
