@extends('layout.app')

@section('app-content')
    <div>
        <form method="post" action="{{ route('teacher.web.setting.store') }}" enctype='multipart/form-data'>
            @csrf
            <div class="row gy-3 mb-24">
                <div class="col-sm-6">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Logo</span>
                    </label>
                    <input class="form-control input-control" type="file" name="logo" placeholder="Logo">
                    @if($errors->has('logo'))
                        <div class="error">{{ $errors->first('logo') }}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Dark Logo</span>
                    </label>
                    <input class="form-control input-control" type="file" name="dark_logo" placeholder="Logo">
                    @if($errors->has('dark_logo'))
                        <div class="error">{{ $errors->first('dark_logo') }}</div>
                    @endif
                </div>
            </div>
            <div class="row gy-3 mb-24">
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Footer Logo Description</span>
                    </label>
                    <input class="form-control input-control" type="text" name="footer_logo_description">
                    @if($errors->has('footer_logo_description'))
                        <div class="error">{{ $errors->first('footer_logo_description') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Term of Service</span>
                    </label>
                    <input class="form-control input-control" type="text" name="term_of_service">
                    @if($errors->has('term_of_service'))
                        <div class="error">{{ $errors->first('term_of_service') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Privacy Policy</span>
                    </label>
                    <input class="form-control input-control" type="text" name="privacy_policy">
                    @if($errors->has('privacy_policy'))
                        <div class="error">{{ $errors->first('privacy_policy') }}</div>
                    @endif
                </div>
            </div>
            <div class="row gy-3 mb-24">
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Subscribe Title</span>
                    </label>
                    <input class="form-control input-control" type="text" name="subscribe_title">
                    @if($errors->has('subscribe_title'))
                        <div class="error">{{ $errors->first('subscribe_title') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Subscribe Description</span>
                    </label>
                    <input class="form-control input-control" type="text" name="subscribe_description">
                    @if($errors->has('subscribe_description'))
                        <div class="error">{{ $errors->first('subscribe_description') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Footer Copyright</span>
                    </label>
                    <input class="form-control input-control" type="text" name="site_footer_copyright">
                    @if($errors->has('site_footer_copyright'))
                        <div class="error">{{ $errors->first('site_footer_copyright') }}</div>
                    @endif
                </div>
            </div>
            <div class="row gy-3 mb-24">
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Linkedin</span>
                    </label>
                    <input class="form-control input-control" type="text" name="linkedin">
                    @if($errors->has('linkedin'))
                        <div class="error">{{ $errors->first('linkedin') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Instagram</span>
                    </label>
                    <input class="form-control input-control" type="text" name="instagram">
                    @if($errors->has('instagram'))
                        <div class="error">{{ $errors->first('instagram') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Facebook</span>
                    </label>
                    <input class="form-control input-control" type="text" name="facebook">
                    @if($errors->has('facebook'))
                        <div class="error">{{ $errors->first('facebook') }}</div>
                    @endif
                </div>

                <div class="col-sm-6">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Term of Service Detail</span>
                    </label>
                    <textarea name="terms_service_detail" id="" cols="30" rows="5" class="form-control input-control"></textarea>
                    @if($errors->has('terms_service_detail'))
                        <div class="error">{{ $errors->first('terms_service_detail') }}</div>
                    @endif
                </div>

                <div class="col-sm-6">
                    <label for="account" class="ts-label"><span class="text-danger required">*</span>
                        <span>Privacy Policy</span>
                    </label>
                    <textarea name="privacy_policy_detail" id="" cols="30" rows="5" class="form-control input-control"></textarea>
                    @if($errors->has('privacy_policy_detail'))
                        <div class="error">{{ $errors->first('privacy_policy_detail') }}</div>
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
        .error{
            color: #a94442;
        }
    </style>
@endsection
