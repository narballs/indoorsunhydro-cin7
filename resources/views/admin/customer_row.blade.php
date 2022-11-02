<tr>
    <td>{{ $count }}</td>
    <td>{{$contact->firstName}}</td>
    <td>
    	@if($contact->status == '1')
    		<span class="badge bg-success">Active</span>
    	@else 
    		<span class="badge bg-danger">Inactive</span>
    	@endif
    </td>
    <td>{{$contact->priceColumn}}</td>
    <td>{{$contact->company}}</td>
    <td>{{$contact->email}}</td>
    <td>{{$contact->notes}}</td>
    <td>
        <a href="{{ url('admin/customer-detail/'.$contact->id) }}" class="view" title="" data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
        <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pen"></i></a>
        <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash-alt"></i></a>
    </td>
</tr>