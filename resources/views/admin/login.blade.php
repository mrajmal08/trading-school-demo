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
        <div class="row">
            <p style="margin: 0;text-align: center;margin-top: -31px;font-size: 18px;">Admin Login</p>
        </div>
        <h3 class="auth-page-title">Sign in</h3>
        <form class="row g-3" method="POST" action="{{ route('admin.logins') }}">
            <!-- is-invalid, is-valid for input field -->
            <!-- valid-feedback, invalid-feedback for message div -->
            @csrf
            <div class="col-sm-12">
                <input id="email" type="email" class="form-control input-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-sm-12 input-wrapper">
                <input type="password" class="form-control input-control @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" required>
                <span class="togglePass"><i class="bi bi-eye-slash"></i></span>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary w-100 btn-submit mb-2">Log in</button>
                {{-- <a href="#" class="forgot-pass">Forgot your password?</a> --}}
            </div>

        </form>
    </div>
@endsection
