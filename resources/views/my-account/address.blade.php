@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('addresses-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 py-3">
                    @include('my-account.my-account-side-bar')
                </div>
                <div class="col-md-5 py-3 text-right">
                    <button type="button" class="btn my_account_add_a_new_address"> Add a new address +</button>
                </div>
            </div>
            <div class="col-md-12 p-0">
                <div class="card me-xxl-5 me-lx-5 me-lg-5">
                    <div class="card-header bg-white ps-5">
                        <p class="my_account_default_address">
                            Default Addresses
                        </p>
                    </div>
                    <div class="card-boday">
                        <div class="row ps-5 my-5">
                            <div class="col-md-6">
                                <p class="default_billing_address">
                                    Default Billing Address
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['firstName'] }}
                                    {{ $address_user->contact[0]['lastName'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalAddress1'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalAddress2'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalState'] }}
                                </p>
                                <p class="my_account_address_items">
                                    <span class="my_account_address_items">
                                        Tel:
                                    </span>
                                    <span class="mobileFormat">
                                        <span>
                                            @if ($address_user->contact[0]['phone'])
                                                {{ $address_user->contact[0]['phone'] }}
                                            @else
                                                {{ $address_user->contact[0]['mobile'] }}
                                            @endif
                                        </span>
                                </p>
                                <p>
                                    <button type="button" class="btn p-0 change_billing_address_btn"
                                        data-bs-toggle="modal" data-bs-target="#address_modal_id">
                                        Change billing address
                                    </button>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="default_billing_address">
                                    Default Shipping Address
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['firstName'] }}
                                    {{ $address_user->contact[0]['lastName'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalAddress1'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalAddress2'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user->contact[0]['postalState'] }}
                                </p>
                                <p class="my_account_address_items">
                                    <span class="my_account_address_items">
                                        Tel:
                                    </span>
                                    <span class="mobileFormat">
                                        @if ($address_user->contact[0]['phone'])
                                            {{ $address_user->contact[0]['phone'] }}
                                        @else
                                            {{ $address_user->contact[0]['mobile'] }}
                                        @endif
                                    </span>
                                </p>
                                <p>
                                    <button type="button" class="btn p-0 change_billing_address_btn"
                                        data-bs-toggle="modal" data-bs-target="#address_modal_id">
                                        Change Shipping address
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 my-4 p-0">
                <div class="card me-xxl-5 me-lx-5 me-lg-5">
                    <p class="additional_address_entries px-5 py-4 mb-0">
                        Additional Address Entries
                    </p>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table address-table-items-data m-0 ">
                                <thead>
                                    <tr class="table-header-background">
                                        <td class="table-row-item">
                                            <div class="custom-control custom-checkbox tabel-checkbox">
                                                <input
                                                    class="custom-control-input custom-control-input-success checkbox-table"
                                                    type="checkbox" id="selectAll" value="">
                                                <label for="selectAll" class="custom-control-label"></label>

                                                <span class="table-row-heading-order">
                                                    <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="my_account_addresses">Name</td>
                                        <td class="my_account_addresses">Address</td>
                                        <td class="my_account_addresses">City</td>
                                        <td class="my_account_addresses">Country</td>
                                        <td class="my_account_addresses">State</td>
                                        <td class="my_account_addresses">Zip Code</td>
                                        <td class="my_account_addresses">Phone Number</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($secondary_contacts as $key => $contact)
                                        <tr class="py-5">
                                            <td class="table-items">
                                                <div class="custom-control custom-checkbox tabel-checkbox">
                                                    <input
                                                        class="custom-control-input custom-control-input-success sub_chk"
                                                        data-id="{{ $contact->id }}" type="checkbox"
                                                        id="separate_check_{{ $contact->id }}">
                                                    <label for="separate_check_{{ $contact->id }}"
                                                        class="custom-control-label"></label>
                                                    <span>
                                                        {{ $key + 1 }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="my_account_address_items">
                                                @if (!empty($contact['firstName'] && $contact['lastName']))
                                                    {{ $contact['firstName'] . ' ' . $contact['lastName'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['postalAddress1']))
                                                    {{ $contact['postalAddress1'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['postalCity']))
                                                    {{ $contact['postalCity'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['Country']))
                                                    {{ $contact['Country'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['postalState']))
                                                    {{ $contact['postalState'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['postalCode']))
                                                    {{ $contact['postalCode'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td class="my_account_all_items">
                                                @if (!empty($contact['phone']))
                                                    {{ $contact['phone'] }}
                                                @elseif(!empty($contact['mobile']))
                                                    {{ $contact['mobile'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td>
                                                <img src="/theme/img/dots_icons.png" alt="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        {{ $secondary_contacts->links('pagination.custom_pagination') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('my-account.my-account-scripts')
@include('partials.product-footer')
@include('partials.footer')
