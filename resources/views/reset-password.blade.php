@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
        @endif


   <form action="{{url('reset-password')}}" method="POST">
   	@csrf
	<div class="form-signup-secondary">
		<div class="user-info">
			<div class="row mt-3">
				<div class="col-md-12">
					<input type="text" placeholder="&#xf007;  email" id="email"
						name="email" class="form-control mt-3 fontAwesome disable" value="{{$user->email}}">
                        @if ($errors->has('email'))
                          <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif

						@if(!empty($secondary))
					     <input type="hidden" placeholder="&#xf007;  email" id="is_secondary"
						name="is_secondary" class="form-control mt-3 fontAwesome disable" value="{{$secondary}}">
						@endif
				</div>
				<div class="col-md-12">
					<input type="password" placeholder="&#xf023;  Password" id="company_name"
						name="password" class="form-control mt-2 company-info fontAwesome mt-3" value="{{ old('password') }}">
					@if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
				</div>
				<div class="col-md-12">
					<input type="password" placeholder="&#xf023;  Confirm Password"
						id="confirm_password" name="confirm_password"
						class="form-control mt-3 company-info fontAwesome">
				</div>
				  @if ($errors->has('confirm_password'))
                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                  @endif
				<center class="mt-3">
					<button type="submit" class="btn btn sing-up-continue text-center">Reset Password</button>
				</center>
			</div>
		</div>
	
	</div>
</form>
@include('partials.footer')
@include('partials.product-footer')