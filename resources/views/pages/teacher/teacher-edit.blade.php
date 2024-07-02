@extends('layout.app')

@section('app-content')
   <div class="teachers-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end"></div>
      </div>

      <div class="row">
         <div class="col-sm-12">
            <form method="post" action="{{ route('staff.update', $row->id) }}">
               @csrf
               <div class="row gy-3 mb-24">
                  <div class="col-sm-4">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>First Name</span>
                     </label>
                     <input class="form-control input-control" name="first_name" placeholder="First Name"
                        value="{{ $row->first_name }}">
                     @if ($errors->has('first_name'))
                        <div class="error">{{ $errors->first('first_name') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-4">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Last Name</span>
                     </label>
                     <input class="form-control input-control" name="last_name" placeholder="Last Name"
                        value="{{ $row->last_name }}">
                     @if ($errors->has('last_name'))
                        <div class="error">{{ $errors->first('last_name') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-4">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Email</span>
                     </label>
                     <input class="form-control input-control" type="email" name="email" placeholder="email"
                        value="{{ $row->email }}">
                     @if ($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                     @endif
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Country</span>
                     </label>
                     <select name="country" id="country" class="select-control" style="width: 100%;">
                        <option value="">Select Country</option>
                        @foreach($countryList as $country)
                           <option <?= ($country->country_name == $row->country) ? 'selected':'' ?> value="{{ $country->country_name }}" data-id="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6" id="stateSection">
                     <label for="account" class="ts-label">
                        <span>State</span>
                     </label>
                     <select name="state" id="state" class="select-control" style="width: 100%;">
                     </select>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Company</span>
                     </label>
                     <input class="form-control input-control" type="text" name="organisation"
                        placeholder="Organisation" value="{{ $row->organisation }}">
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Title</span>
                     </label>
                     <input class="form-control input-control" type="text" name="designation" placeholder="Designation"
                        value="{{ $row->designation }}">
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Password</span>
                     </label>
                     <input class="form-control input-control" type="password" name="password" placeholder="Password">
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Password</span>
                     </label>
                     <input class="form-control input-control" type="password" name="password_confirmation"
                        placeholder="Confirm password">
                     @if ($errors->has('password_confirmation'))
                        <div class="error">{{ $errors->first('password_confirmation') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Status</span>
                     </label>
                     <select name="status" class="form-control input-control">
                        <option <?= ($row->status == '1')?'selected':'' ?> value="1">Active</option>
                        <option <?= ($row->status == '0')?'selected':'' ?> value="0">Inactive</option>
                     </select>
                  </div>
               </div>
               <div class="row gy-3 justify-content-center mb-24">
                  <div class="col-sm-6">
                     <input class="btn-teacher-submit btn-submit w-100" type="submit" value="Submit">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <style>
      .error {
         color: red;
      }
   </style>

@push('scripts')
<script>
      $(document).ready(function(){
         $('#stateSection').hide();
         $("#country").on('change', function() {
            var countryId = $("#country").find(':selected').attr('data-id');
            var actionUrl = "<?= url('') ?>/teacher/select-state";
            var request = $.ajax({
            url: actionUrl,
            type: "get",
            data: {countryId : countryId},
            });
            request.done(function(responce) {
               var states = [];
               for (let i = 0; i < responce.length; i++) {
                  states += `<option>`+responce[i].state_name+`</option>`;
                  }
                  console.log(states);
               if(states.length > 0){
                  $('#stateSection').show();
                  $('#state').html(states);
               }else{
                  $('#stateSection').hide();
               }
            });
         });

         editState();
         function editState(){
               var countryId = $("#country").find(':selected').attr('data-id');
               var actionUrl = "<?= url('') ?>/admin/select-state";
               var request = $.ajax({
               url: actionUrl,
               type: "get",
               data: {countryId : countryId},
               });
               request.done(function(responce) {
                  var states = [];
                  for (let i = 0; i < responce.length; i++) {
                     states += `<option value="`+responce[i].state_name+`">`+responce[i].state_name+`</option>`;
                     }
                     console.log(states);
                  if(states.length > 0){
                     $('#stateSection').show();
                     $('#state').html(states);
                     $("#state").val("<?= $row->state ?>").change();
                  }else{
                     $('#stateSection').hide();
                  }
               });
            }
      });
   </script>
@endpush
@endsection
