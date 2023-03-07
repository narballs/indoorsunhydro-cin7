@foreach ($secondary_contacts as $childeren)

<tr id="row-{{$childeren->id}}">
	@if($childeren->company)
	<td>
		{{$childeren->company}}
	</td>
	@else
	<td>
		<span class=" badge bg-success">empty</span>
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
	<td style="width: 111px !important;">
		<a href="#"><img src="/theme/img/invite_iccons.png" alt=""></a>
		@php
		$url = URL::to("/");
		$url = $url . '/customer/invitation/' . $childeren->hashKey . '?is_secondary=1';
		@endphp
		<a class="btn text-dark p-0">
			<span id="copyUrl" data-id="{{$url}}">
				<img src="/theme/img/copy_link_iccon.png" class="d-block" alt="">
			</span>

		</a>
		<a href="#" id="{{$childeren->id}}" class="deleteIcon"><img src="/theme/img/delete_iccon.png" alt=""></a>
	</td>
</tr>

@endforeach
<div id="custom_loader" class="spinner-border d-none" style="width: 5rem; height: 5rem;position: absolute;
top: 30%;" role="status">
	<span class="sr-only">Loading...</span>
</div>
{{-- <script>
	function copyUrlLink(i){
		console.log($(this));
		//console.log($('#copyUrl'))
		console.time('time1');
		var temp = $("<input>");
		$("body").append(temp);
		temp.val($('#copyUrl').text()).select();
		document.execCommand("copy");
		temp.remove();
		console.timeEnd('time1');
 }		
</script> --}}



<script>
	// display a modal (small modal)
  
</script>