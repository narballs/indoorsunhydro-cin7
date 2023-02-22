@foreach ($secondary_contacts as $childeren)
<tr>
	@if($childeren->company)
	<td>
		{{$childeren->company}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->firstName)
	<td>
		{{$childeren->firstName}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->lastName)
	<td>
		{{$childeren->lastName}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->jobTitle)
	<td>
		{{$childeren->jobTitle}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->email)
	<td>
		{{$childeren->email}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->phone)
	<td>
		{{$childeren->phone}}
	</td>
	@else
	<td>
		<span class="badge bg-success">empty</span>
	</td>
	@endif
	@if($childeren->hashKey == '' && $childeren->hashUsed == 0)
	<td>
		<button id="invite" type="button" class="btn btn-info btn-sm"
			onclick="	sendInvitation('{{$childeren->email}}')"> Invite
		</button>
	</td>
	@elseif($childeren->hashKey !='' && $childeren->hashUsed == 1)
	<td>
		<span class="badge bg-success">Merged</span>
	</td>
	@else
	<td>
		<span id="invitation_sent" class="badge bg-warning">Invitation
			Sent</span>
	</td>
	@endif
	<td>
		<span class="badge bg-secondary">secondary contact</span>
	</td>
</tr>
@endforeach