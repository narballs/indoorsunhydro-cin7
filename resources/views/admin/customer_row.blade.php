<tr id="row-{{ $contact->id }}"  data-deleted="{{$contact->deleted_at}}" title="{{!empty($contact->deleted_at) ? $contact->deleted_at : ''}}" class="customer-row main_contacts_row border-bottom {{!empty($contact->deleted_at) ? 'delete_grey' : ''}}">
    <td class="d-flex table-items">
        <div class="custom-control custom-checkbox tabel-checkbox">
            <input class="custom-control-input custom-control-input-success sub_chk all_checkboxes" data-id="{{ $contact->id }}"
                type="checkbox" id="separate_check_{{ $contact->id }}">
            <label for="separate_check_{{ $contact->id }}" class="custom-control-label ml-4"></label>
        </div>
        <span class="table-row-heading-order sm-d-none">
            {{ $key + 1 }}
        </span>
    </td>
    <td class="customer-items-row">
        {{ $contact->id }}
    </td>
    @if (!$contact->contact_id)
        <td class="customer-items-row"><span class="badge bg-info">empty</span></td>
    @else
        <td class="customer-items-row">{{ $contact->contact_id }}</td>
    @endif
    <td class="customer_name customer-items-row">
        <a class="customer_full_name" href="{{ url('admin/customer-detail/' . $contact->id) }}">
            <span>{{ $contact->firstName }} {{ $contact->lastName }}</span>
        </a>
    </td>

    <td class="customer-items-row">
        @if ($contact->contact_id == '' || $contact->user_id == '')
            <span class="badge badge-danger">Unmerged</span>
        @else
            <span class="badge badge-success">Merged</span>
        @endif
    </td>

    <td class="customer-row customer-items-row">
        <span>
            {{ $contact->priceColumn }}
        </span>
    </td>
    <td class="customer-items-row">
        <span>
            {{ $contact->company }}
        </span>
    </td>
    <td class="customer-items-row">
        <a href="{{ url('admin/customer-detail/' . $contact->id) }}">
            {{ $contact->email }}
        </a>
    </td>
    <td class="customer-items-row">
        <span class="text-bold text-dark">{{ $contact->type }}</span>        
    </td>
    <td title="{{ $contact->notes }}" class="customer-items-row">
        <span>
            {{ Illuminate\Support\Str::limit($contact->notes, 30) }}
        </span>
    </td>
    <td class="customer-items-row">
        {{$contact->created_at->format('m/d/Y') }}
    </td>
    @if ($contact && $contact->status == 1 && $contact->type != 'Supplier')
        <td class="customer-items-row">
            <span class="d-flex">
                <span class="badge badge-success">Active</span>
                <label class="custom-control custom-checkbox ">
                    <input type="checkbox" id="{{ $contact->id }}" value="{{ $contact->status }}"
                        class="custom-control-input general_switch" onchange="disableSecondary({{ $contact->id }})"
                        {{ isset($contact->status) && $contact->status == 1 ? 'checked="checked"' : '' }}>
                    <span class="custom-control-indicator"></span>
                </label>
            </span>
        </td>
    @else
        <td class="customer-items-row">
            <span class="d-flex">
                <span class="badge badge-warning">Inactive</span>
                <label class="custom-control custom-checkbox ">
                    <input type="checkbox" id="{{ $contact->id }}" value="{{ $contact->status }}"
                        class="custom-control-input general_switch" onchange="disableSecondary({{ $contact->id }})"
                        {{ isset($contact->status) && $contact->type != 'Supplier' && $contact->status == 1 ? 'checked="checked"' : '' }}>
                    <span class="custom-control-indicator"></span>
                </label>
            </span>
        </td>
    @endif
    <td class="created_by toggleClass td_padding_row pl-0">
        <div class="d-flex aling-items-center order-table-actions">
            <span class="ml-0">
                <a href="{{ url('admin/customer-detail/' . $contact->id) }}" class="view a_class" title=""
                    data-toggle="tooltip" data-original-title="View">
                    {{-- <img src="/theme/img/view.png" alt="" class="img-fluid"> --}}
                    <i class="fas fa-eye" style="color: lightgray"></i>
                </a>
            </span>
            <span class="ml-1">
                <a href="{{ url('admin/customer-edit/' . $contact->id) }}" class="edit a_class" title=""
                    data-toggle="tooltip" data-original-title="Edit">
                    {{-- <img src="/theme/img/edit.png" alt="" class="img-fluid"> --}}
                    <i class="fas fa-pen" style="color: lightgray"></i>
                </a>
            </span>
            <span class="ml-1">
                <a href=" {{ url('admin/customer-delete/' . $contact->id) }}" class="delete deleteIcon a_class"
                    id="{{ $contact->id }}" title="" data-toggle="tooltip" data-original-title="Delete">
                    {{-- <img src="/theme/img/delete.png" alt="" class="img-fluid" onclick="return confirm('Are you sure you want to delete this Contact?');"> --}}
                    <i class="fas fa-trash" style="color: lightgray" onclick="return confirm('Are you sure you want to delete this Contact?');"></i>
                </a>
            </span>
        </div>
    </td>
</tr>
