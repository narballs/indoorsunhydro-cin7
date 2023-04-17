<tr>
    <td>{{ $contact->id }}</td>
    @if (!$contact->contact_id)
    <td><span class="badge bg-info">empty</span></td>
    @else
    <td>{{ $contact->contact_id}}</td>
    @endif
    <td><a href="{{ url('admin/customer-detail/'.$contact->id) }}">{{$contact->firstName}}</a></td>
    <td><a href="{{ url('admin/customer-detail/'.$contact->id) }}">{{$contact->lastName}}</a></td>
    <td>
        <?php //dd($contact->status);?>
        @if($contact->status == '1')
        <span class="badge bg-success">Active</span>
        @else
        <span class="badge bg-warning">Inactive</span>
        @endif
    </td>
    <td>
        @if($contact->user_id == '')
        <span class="badge bg-danger">Unmerged</span>
        @else
        <span class="badge bg-success">Merged</span>
        @endif
    </td>

    <td>{{$contact->priceColumn}}</td>
    <td>{{$contact->company}}</td>
    <td><a href="{{ url('admin/customer-detail/'.$contact->id) }}">{{$contact->email}}</a></td>
    <td>{{$contact->notes}}</td>
    <td>
        <a href="{{ url('admin/customer-detail/'.$contact->id) }}" class="view" title="" data-toggle="tooltip"
            data-original-title="View"><i class="fas fa-eye"></i></a>
        @can('edit-profile')
        <a href="{{ url('admin/customer-edit/'.$contact->id)}}" class="edit" title="" data-toggle="tooltip"
            data-original-title="Edit"><i class="fas fa-pen"></i>
        </a>
        @endcan
        <a href="{{ url('admin/customer-delete/'.$contact->id)}}" class="delete" title="" data-toggle="tooltip"
            data-original-title="Delete"><i class="fas fa-trash-alt"></i></a>
    </td>
</tr>