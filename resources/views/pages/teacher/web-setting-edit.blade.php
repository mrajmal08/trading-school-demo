@extends('layout.app')

@section('app-content')
<div class="row mb-4">
   <div class="col-sm-6"><h4>Home Page Setup</h4></div>
</div>
   <div>
      <form method="post" action="{{ route('teacher.web.setting.update', $row->id) }}" enctype='multipart/form-data'>
         @csrf
         <div class="row gy-3 py-4 border-bottom border-dark">
            <div class="col-sm-12">
               <h5>Logo Management</h5>
            </div>
            <div class="col-sm-12 mt-0">
               <div class="row gy-2 align-items-end">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Logo (Light Mode)</span>
                     </label>
                     <input class="form-control input-control" type="file" name="logo" placeholder="Logo"
                        id="thumbnail">
                     @if ($errors->has('logo'))
                        <div class="error">{{ $errors->first('logo') }}</div>
                     @endif
                  </div>
                  <div class="col-sm-2">
                     <div class="file-thumb">
                        <img src="{{ $row->logo }}" alt="Profile Image" class="img-thumbnail">
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-sm-12">
               <div class="row gy-2 align-items-end">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Logo (Dark Mode)</span>
                     </label>
                     <input class="form-control input-control" type="file" name="dark_logo" placeholder="Logo"
                        id="dark-input-field">
                        @if ($errors->has('dark_logo'))
                            <div class="error">{{ $errors->first('dark_logo') }}</div>
                        @endif
                  </div>
                  <div class="col-sm-2">
                     <div class="file-thumb">
                        <img src="{{ $row->dark_logo }}" alt="Profile Image" class="darak-logo img-thumbnail">
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row gy-3 py-4 border-bottom border-dark">
            <div class="col-sm-12">
               <h5>Subscription Content</h5>
            </div>
            <div class="col-sm-6 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Subscription Title</span>
               </label>
               <input class="form-control input-control" type="text" name="subscribe_title"
                  value="{{ $row ? "$row->subscribe_title" : '' }}">
               @if ($errors->has('subscribe_title'))
                  <div class="error">{{ $errors->first('subscribe_title') }}</div>
               @endif
            </div>
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Subscription Details</span>
               </label>
               <input class="form-control input-control" type="text" name="subscribe_description"
                  value="{{ $row ? "$row->subscribe_description" : '' }}">
               @if ($errors->has('subscribe_description'))
                  <div class="error">{{ $errors->first('subscribe_description') }}</div>
               @endif
            </div>
            <div class="col-sm-6"></div>
         </div>

         <div class="row gy-3 py-4 border-bottom border-dark">
            <div class="col-sm-12 mb-0">
               <h5>Footer Content Management</h5>
            </div>
            <div class="col-sm-6 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Logo Tagline</span>
               </label>
               <input class="form-control input-control" type="text" name="footer_logo_description"
                  value="{{ $row ? "$row->footer_logo_description" : '' }}">
               @if ($errors->has('footer_logo_description'))
                  <div class="error">{{ $errors->first('footer_logo_description') }}</div>
               @endif
            </div>
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Copyright</span>
               </label>
               <input class="form-control input-control" type="text" name="site_footer_copyright"
                  value="{{ $row ? "$row->site_footer_copyright" : '' }}">
               @if ($errors->has('site_footer_copyright'))
                  <div class="error">{{ $errors->first('site_footer_copyright') }}</div>
               @endif
            </div>
            <div class="col-sm-6"></div>
         </div>

         <div class="row gy-3 py-4 border-bottom border-dark">
            <div class="col-sm-12">
               <h5>Terms & Privacy Policy Content</h5>
            </div>
            <div class="col-sm-6 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Terms of Service Title</span>
               </label>
               <input class="form-control input-control" type="text" name="term_of_service"
                  value="{{ $row ? "$row->term_of_service" : '' }}">
               @if ($errors->has('term_of_service'))
                  <div class="error">{{ $errors->first('term_of_service') }}</div>
               @endif
            </div>
            <div class="col-sm-6 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Privacy Policy Title</span>
               </label>
               <input class="form-control input-control" type="text" name="privacy_policy"
                  value="{{ $row ? "$row->privacy_policy" : '' }}">
               @if ($errors->has('privacy_policy'))
                  <div class="error">{{ $errors->first('privacy_policy') }}</div>
               @endif
            </div>
            <div class="col-sm-6">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Terms of Service Details</span>
               </label>
               <textarea name="terms_service_detail" id="" cols="30" rows="5"
                  class="form-control input-control">{{ $row->terms_service_detail }}</textarea>
               @if ($errors->has('terms_service_detail'))
                  <div class="error">{{ $errors->first('terms_service_detail') }}</div>
               @endif
            </div>

            <div class="col-sm-6">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Privacy Policy Detail</span>
               </label>
               <textarea name="privacy_policy_detail" id="" cols="30" rows="5"
                  class="form-control input-control">{{ $row->privacy_policy_detail }}</textarea>
               @if ($errors->has('privacy_policy_detail'))
                  <div class="error">{{ $errors->first('privacy_policy_detail') }}</div>
               @endif
            </div>
         </div>

         <div class="row gy-3 py-4">
            <div class="col-sm-12">
               <h5>Social Media Content</h5>
            </div>
            <div class="col-sm-4 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Linkedin</span>
               </label>
               <input class="form-control input-control" type="text" name="linkedin" value="{{ $row->linkedin }}">
               @if ($errors->has('linkedin'))
                  <div class="error">{{ $errors->first('linkedin') }}</div>
               @endif
            </div>
            <div class="col-sm-4 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Instagram</span>
               </label>
               <input class="form-control input-control" type="text" name="instagram"
                  value="{{ $row->instagram }}">
               @if ($errors->has('instagram'))
                  <div class="error">{{ $errors->first('instagram') }}</div>
               @endif
            </div>
            <div class="col-sm-4 mt-0">
               <label for="account" class="ts-label"><span class="text-danger required">*</span>
                  <span>Facebook</span>
               </label>
               <input class="form-control input-control" type="text" name="facebook" value="{{ $row->facebook }}">
               @if ($errors->has('facebook'))
                  <div class="error">{{ $errors->first('facebook') }}</div>
               @endif
            </div>
         </div>

         <div class="row gy-3 justify-content-center mb-24">
            <div class="col-sm-6">
               <input class="btn-teacher-submit btn-submit w-100" type="submit" value="Submit">
            </div>
         </div>
      </form>
   </div>
   <style>
      .error {
         color: red;
      }
   </style>
@endsection

@push('scripts')
   <script>
      $(document).ready(function() {
         $("#thumbnail").change(function(e) {
            const file = e.target.files[0];
            if (file) {
               let reader = new FileReader();
               reader.onload = function(event) {
                  $(".img-thumbnail").attr("src", event.target.result);
               };
               reader.readAsDataURL(file);
            }
         });

         $("#dark-input-field").change(function(e) {
            const file = e.target.files[0];
            if (file) {
               let reader = new FileReader();
               reader.onload = function(event) {
                  $(".darak-logo").attr("src", event.target.result);
               };
               reader.readAsDataURL(file);
            }
         });
      })
   </script>
@endpush
