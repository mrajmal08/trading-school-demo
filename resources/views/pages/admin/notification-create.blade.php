@extends('layout.admin.app')

@section('admin.app-content')
   <div class="teachers-page">
      <div class="row mb-4">
         <div class="col-md-12">
            <h4>Send Notifications</h4>
         </div>
      </div>
      <div class="row justify-content-center">
         <div class="col-md-12">
            <form method="post" action="{{ route('notification.store') }}" enctype='multipart/form-data'>
               @csrf
               <div class="row gy-3 mb-24">
                  {{-- <div class="col-sm-6">
                     <label for="account" class="ts-label">
                        <span>Users</span>
                     </label>
                     <select name="user" id="user" class="form-control input-control">
                        <option value="">Select User</option>
                        <option value="all">All User</option>
                        @foreach ($userList as $user)
                           <option value="{{ $user->id }}">{{ optional($user->userDetail)->first_name}} {{ optional($user->userDetail)->last_name}}</option>
                        @endforeach
                     </select>
                  </div> --}}
                  <div class="col-sm-6">
                     <label for="multiple_user" class="ts-label">
                        <span class="text-danger required">*</span><span>Select Traders</span>
                     </label>
                     <select name="user[]" id="multiple_user" class="select2 form-control input-control" multiple required>
                        <option value="all">All Traders</option>
                        @foreach ($userList as $user)
                           <option value="{{ $user->id }}">{{ optional($user->userDetail)->first_name }}
                              {{ optional($user->userDetail)->last_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Image</span>
                     </label>
                     <input class="form-control input-control" type="file" name="image">
                     @if ($errors->has('image'))
                        <div class="error">{{ $errors->first('image') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Subject</span>
                     </label>
                     <input class="form-control input-control" name="head_title" placeholder="Subject">
                     @if ($errors->has('head_title'))
                        <div class="error">{{ $errors->first('head_title') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Date</span>
                     </label>
                     <input class="form-control input-control" type="date" name="date">
                     @if ($errors->has('date'))
                        <div class="error">{{ $errors->first('date') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-12">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Message</span>
                     </label>
                     <textarea class="form-control input-control" name="message" placeholder="Message"></textarea>
                     @if ($errors->has('message'))
                        <div class="error">{{ $errors->first('message') }}</div>
                     @endif
                  </div>
               </div>

               <div class="row gy-3 justify-content-center my-4">
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
         color: #a94442;
      }
   </style>
@endsection
