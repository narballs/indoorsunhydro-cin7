@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('addresses-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
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
                {{-- <div class="col-md-6 py-3 text-right">
                    <button type="button" class="btn my_account_add_a_new_address"> Add a new address +</button>
                </div> --}}
            </div>
            <div class="col-md-12 p-0">
                <div class="card">
                    <div class="card-header bg-white">
                        <p class="my_account_default_address mb-0">
                            Default Addresses
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row my-5">
                            <div class="col-md-6">
                                <p class="default_billing_address">
                                    Default Billing Address
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user['firstName'] }}
                                    {{ $address_user['lastName'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ !empty($address_user['postalAddress1']) ? $address_user['postalAddress1'] . "," : '' . ","}}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user['postalAddress2'] ?  $address_user['postalAddress2'] . "," : '' . ","}}
                                </p>
                                <p class="my_account_address_items">
                                    {{ $address_user['postalCity'] ?  $address_user['postalCity'] . "," :  '' . "," }}
                                    {{ $address_user['postalState'] ?  $address_user['postalState'] . "," :  '' . "," }}
                                    {{ $address_user['postalPostCode'] ?  $address_user['postalPostCode'] :  '' }}
                                </p>
                                <p class="my_account_address_items">
                                    <span class="my_account_address_items">
                                        Tel:
                                    </span>
                                    <span class="mobileFormat">
                                        <span>
                                            @if ($address_user['phone'])
                                                {{ $address_user['phone'] }}
                                            @else
                                                {{ $address_user['mobile'] }}
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
                                    {{ $address_user['firstName'] }}
                                    {{ $address_user['lastName'] }}
                                </p>
                                <p class="my_account_address_items">
                                    {{ !empty($address_user['address1']) ? $address_user['address1'] . "," : ''}}
                                </p>
                                <p class="my_account_address_items">
                                    {{ !empty($address_user['address2']) ?  $address_user['address2'] . "," : ''}}
                                </p>
                                <p class="my_account_address_items">
                                    {{ !empty($address_user['city']) ?  $address_user['city'] . "," :  '' }}
                                    {{ !empty($address_user['state']) ?  $address_user['state'] . "," :  '' }}
                                    {{ !empty($address_user['postCode']) ?  $address_user['postCode']: '' }}
                                </p>
                                <p class="my_account_address_items">
                                    <span class="my_account_address_items">
                                        Tel:
                                    </span>
                                    <span class="mobileFormat">
                                        @if ($address_user['phone'])
                                            {{ $address_user['phone'] }}
                                        @else
                                            {{ $address_user['mobile'] }}
                                        @endif
                                    </span>
                                </p>
                                <p>
                                    <button type="button" class="btn p-0 change_billing_address_btn"
                                        data-bs-toggle="modal" data-bs-target="#address_modal_id_shipping">
                                        Change Shipping address
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-12 my-4 p-0">
                <div class="card">
                    <div class="card-header">
                        <p class="additional_address_entries py-4 mb-0">
                            Additional Address Entries
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table address-table-items-data m-0 ">
                                    <thead>
                                        <tr class="table-header-background">
                                            <td class="table-row-item">
                                                <div class="custom-control custom-checkbox tabel-checkbox d-flex align-items-center">
                                                    <input
                                                        class="custom-control-input custom-control-input-success checkbox-table"
                                                        type="checkbox" id="selectAll" value="">
                                                    <label for="selectAll" class="custom-control-label"></label>
    
                                                    <span class="table-row-heading-order">
                                                        <i class="fas fa-arrow-up" style="font-size:14.5px ;"></i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="my_account_addresses">Name</td>
                                            <td class="my_account_addresses">Address</td>
                                            <td class="my_account_addresses">City</td>
                                            <td class="my_account_addresses">State</td>
                                            <td class="my_account_addresses">Zip Code</td>
                                            <td class="my_account_addresses">Phone Number</td>
                                            <td class="my_account_addresses">Action</td>
                                        </tr>
                                    </thead>
                                    @php
                                        $ids_array = [];
                                        $contacts_data = $secondary_contacts_data->toArray();
                                        $data = json_encode($contacts_data);
                                    @endphp
                                    <tbody>
                                        @foreach ($secondary_contacts as $key => $contact)
                                            
                                            <tr class="py-5">
                                                <td class="table-items align-middle pt-0 pb-0">
                                                    <div class="custom-control custom-checkbox tabel-checkbox d-flex align-items-center">
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
                                                <td class="my_account_address_items align-middle">
                                                    @if (!empty($contact['firstName'] && $contact['lastName']))
                                                        {{ $contact['firstName'] . ' ' . $contact['lastName'] }}
                                                    @else
                                                    {{ $contact['first_name'] . ' ' . $contact['last_name'] }}
                                                    @endif
                                                </td>
                                                <td class="my_account_all_items align-middle">
                                                    @if (!empty($contact['postalAddress1']))
                                                        {{ $contact['postalAddress1'] }}
                                                    @else
                                                        {{ $contact['address1'] }}
                                                    @endif
                                                </td>
                                                <td class="my_account_all_items align-middle">
                                                    @if (!empty($contact['postalCity']))
                                                        {{ $contact['postalCity'] }}
                                                    @else
                                                    {{ $contact['city'] }}
                                                    @endif
                                                </td>
                                                <td class="my_account_all_items align-middle">
                                                    @if (!empty($contact['postalState']))
                                                        {{ $contact['postalState'] }}
                                                    @else
                                                    {{ $contact['state'] }}
                                                    @endif
                                                </td>
                                                <td class="my_account_all_items align-middle">
                                                    @if (!empty($contact['postalPostCode']))
                                                        {{ $contact['postalPostCode'] }}
                                                    @else
                                                    {{ $contact['postCode'] }}
                                                    @endif
                                                </td>
                                                <td class="my_account_all_items align-middle">
                                                    @if (!empty($contact['phone']))
                                                        {{ $contact['phone'] }}
                                                    @elseif(!empty($contact['mobile']))
                                                        {{ $contact['mobile'] }}
                                                    @else
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($contact->is_default == 1)
                                                        <button type="button" class="btn btn-success btn-sm">Default</button>
                                                    @else
                                                        <form action="{{route('make_address_default')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$contact->id}}">
                                                            <input type="hidden" name="contacts" value="{{$data}}">

                                                            <button type="submit" class="btn btn-primary btn-sm">Make Default</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
            </div> --}}
        </div>
    </div>
</div>

@include('my-account.my-account-scripts')
@include('partials.product-footer')
@include('partials.footer')
