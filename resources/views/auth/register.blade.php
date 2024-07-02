@extends('layout.guest')

@section('guest-content')
  <div class="card card-box register-card">
    <div class="logo-bar">
      <?php $logo = \App\Models\WebSetting::pluck('logo')->first(); ?>
      @empty($logo)
        <img class="logo" src="{{ asset('assets/images/logo.png') }}" alt="logo" loading="lazy"
          style="max-width: 100%; height: 100px;" />
      @else
        <img class="logo" src="{{ asset('/assets/images/') }}/{{ $logo }}" alt="logo" loading="lazy" style="max-width: 100%; height: 100px;" />
        @endif
        <h4 class="brand-name"><span>Trading</span> <span class="down">School</span></h4>
      </div>

      <h3 class="auth-page-title">Sign up</h3>
      <p class="auth-page-subtitle">Create your account. No credit card is required.</p>

      <form class="row g-3" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="col-md-6">
          <input type="text" class="form-control input-control" placeholder="First name" aria-label="*First name">
        </div>
        <div class="col-md-6">
          <input type="text" class="form-control input-control" placeholder="Last name" aria-label="*Last name">
        </div>

        <div class="col-sm-12">
          <input type="email" class="form-control input-control" id="inputEmail3" placeholder="*Email Address" required>
          <div class="valid-feedback"></div>
        </div>

        <div class="col-sm-12 input-wrapper">
          <input type="password" class="form-control input-control" id="password" placeholder="*Password" required>
          <span class="togglePass"><i class="bi bi-eye-slash"></i></span>
        </div>

        <div class="col-sm-12 input-wrapper">
          <input type="password" class="form-control input-control" id="password_confirmation"
            placeholder="Confirm Password" required>
          <span class="togglePass"><i class="bi bi-eye-slash"></i></span>
        </div>

        <div class="col-sm-12 registerCheckbox">
          <label for="isEighteen">
            <input type="checkbox" name="isEighteen" id="isEighteen">
            Yes, I am 18+ years old *
          </label>
          <label for="isAgreed">
            <input type="checkbox" name="isAgreed" id="isAgreed">
            I have read and agree to the <a href="" class="text-primary">Teams of Use</a> and <a href=""
              class="text-primary">Privacy Policy</a>.
          </label>
          <label for="isSubscribed">
            <input type="checkbox" name="isSubscribed" id="isSubscribed">
            Sign up for the ‘website name’ Newsletter.
          </label>
        </div>

        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary w-100 btn-submit mb-2">Sign up</button>
          <a href="{{ route('login') }}" class="forgot-pass">Already have an account?</a>
        </div>
      </form>
    </div>
  @endsection
