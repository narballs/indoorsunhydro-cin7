<tr id="row-{{ $contact->id }}" class="customer-row border-bottom">
    <td class="d-flex customer-table-items">
        <span class="tabel-checkbox-user">
            <input type="checkbox" name="test" class="checkbox-table">
        </span>
        <span class="table-row-heading-user">
            {{ $key + 1 }}
        </span>
    </td>
    <td>
        {{ $contact->id }}
    </td>
    @if (!$contact->contact_id)
        <td><span class="badge bg-info">empty</span></td>
    @else
        <td>{{ $contact->contact_id }}</td>
    @endif
    <td class="customer_name">
        <a class="customer_full_name" href="{{ url('admin/customer-detail/' . $contact->id) }}">
            <span>{{ $contact->firstName }} {{ $contact->lastName }}</span>
        </a>
    </td>
    <td>
        @if ($contact->status == '1')
            <span class="badge badge-success">Active</span>
        @else
            <span class="badge badge-warning">Inactive</span>
        @endif
    </td>
    <td>
        @if ($contact->user_id == '')
            <span class="badge badge-danger">Unmerged</span>
        @else
            <span class="badge badge-success">Merged</span>
        @endif
    </td>

    <td class="customer-row">
        <span>
            {{ $contact->priceColumn }}
        </span>
    </td>
    <td>
        <span>
            {{ $contact->company }}
        </span>
    </td>
    <td>
        <a href="{{ url('admin/customer-detail/' . $contact->id) }}">
            {{ $contact->email }}
        </a>
    </td>
    <td title="{{ $contact->notes }}">
        <span>
            {{ Illuminate\Support\Str::limit($contact->notes, 30) }}
        </span>
    </td>
    <td class="created_by toggleClass">
        <div class="btn-group">
            <button type="button" class="btn p-0 btn-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-ellipsis-h" style="color: #CBCBCB !important;"></i>
            </button>
            <div class="dropdown-menu dropdonwn_menu">
                <a class="dropdown-item" href="{{ url('admin/customer-detail/' . $contact->id) }}" class="view a_class"
                    title="" data-toggle="tooltip" data-original-title="View">Previews
                </a>
                <a class="dropdown-item delete deleteIcon a_class"
                    href="{{ url('admin/customer-delete/' . $contact->id) }}" class="" id="{{ $contact->id }}"
                    title="" data-toggle="tooltip" data-original-title="Delete">Delete
                </a>
                <a class="dropdown-item"href="{{ url('admin/customer-edit/' . $contact->id) }}" class="edit a_class"
                    title="" data-toggle="tooltip" data-original-title="Edit">Edit
                </a>
            </div>
        </div>
    </td>
</tr>
