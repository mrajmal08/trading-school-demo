@extends('layout.guest')

@section('guest-content')

<div class="card card-box login-card">
    <div class="logo-bar">
        <?php $logo = \App\Models\WebSetting::pluck('logo')->first(); ?>
        @empty($logo)
            <img class="logo" src="{{ asset('assets/images/logo.png') }}" alt="logo" loading="lazy" style="max-width: 100%; height: 100px;" />
        @else
            <img class="logo" src="{{ asset('/assets/images/') }}/{{ ($logo) }}" alt="logo" loading="lazy" style="max-width: 100%; height: 100px;"/>
        @endif
        <h4 class="brand-name"><span>Trading</span> <span class="down">School</span></h4>
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="row mb-3">
            <label for="email" class="col-md-12 col-form-label">{{ __('Email Address') }}</label>

            <div class="col-md-12">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

            <div class="col-md-12">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirm Password') }}</label>

            <div class="col-md-12">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
