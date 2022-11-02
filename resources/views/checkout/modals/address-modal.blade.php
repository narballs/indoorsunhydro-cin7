<div class="modal fade" id="address_modal_id" data-dismiss="modal" data-backdrop="false"  aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">Update Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                 <div class="update-address-section" id="address-form-update">

                <form class="needs-validation mt-4 novalidate" action="{{url('order')}}" method="POST">
                @csrf
                <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" >First name</label>
                        <input type="text" class="form-control bg-light" name="firstName" placeholder="First name" value="{{$user_address->firstName}}" required>
                     <div id="error_first_name" class="text-danger">

                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control bg-light" name="lastName" placeholder="" value="{{$user_address->lastName}}" required>
                        <div id="error_last_name" class="text-danger">

                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="company">Company Name(optional)</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name" value="{{$user_address->company}}" required>
                       
                    </div>
                     <div id="error_company" class="text-danger">

                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Country</label>&nbsp;<span>United States</span>
                    <input type="hidden" name="country" value="United States">
                </div>


                <div class="mb-3">
                    <label for="address">Street Address</label>
                    <input type="text" class="form-control bg-light" name="address" value="{{$user_address->postalAddress1}}" placeholder="House number and street name" required>
                 
                </div>
                <div id="error_address1" class="text-danger">

                </div>

                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="address2" value="{{$user_address->postalAddress2}}" placeholder="Apartment, suite, unit etc (optional)">
                </div>
                <div id="error_address2" class="text-danger">

                </div>
                <div class="mb-3">
                    <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="town_city" value="{{$user_address->postalCity}}" placeholder="Enter your town">
                </div>
                <div id="error_city" class="text-danger">

                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="state">State</label>

                        <select class="form-control bg-light" name="state" id="state">
                            @foreach($states as $state)
                                <?php 
                                    if($user_address->postalState == $state->name){
                                            $selected = 'selected';

                                    }
                                    else
                                    {
                                         $selected = '';
                                    }
                                
                                ?>
                                <option value="{{$state->name}}" <?php echo  $selected;?>>{{$state->name}}</option>
                            @endforeach
                        </select>
                     <!--    <input type="text" class="form-control bg-light" name="state" value="{{$user_address->postalState}}" placeholder="Enter State" value="" required> -->
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control bg-light" name="zip" placeholder="Enter zip code" value="{{$user_address->postalPostCode}}" required>
                        <div id="error_zip" class="text-danger">
                           
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control bg-light" name="phone" placeholder="Enter your phone" value="{{$user_address->phone}}" required>
                       <div id="error_phone" class="text-danger"></div>
                    
                    
                
                </div>
            
                <!-- <div>
                    <button calss="btn btn-primary" onclick="updateContact('{{auth()->user()->id}}')">Update</button>
                </div> -->
            </div>
        </form>
            </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn button-cards primary" onclick="updateContact('{{auth()->user()->id}}')">Update</button>
      </div>
    </div>
  </div>
</div>
<a class="" data-bs-toggle="modal" href="#address_modal_id" role="button">Edit</a>