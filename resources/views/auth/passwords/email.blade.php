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
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="row mb-3">
            <label for="email" class="col-md-12 col-form-label">{{ __('Email Address') }}</label>
            <div class="col-md-12">
                <input id="email" type="email" class="form-control input-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
