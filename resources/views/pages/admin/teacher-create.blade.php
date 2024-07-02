@extends('layout.admin.app')

@section('admin.app-content')
  <div class="teachers-page">
    <div class="row mb-4">
      <div class="col-sm-6 text-start">
        <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
          <i class="bi bi-arrow-left"></i> <span>Back</span>
        </a>
      </div>
      <div class="col-sm-6 text-end"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-12">
        <form method="post" action="{{ route('teacher.store') }}">
          @csrf
          <div class="row gy-3 mb-24">
            <div class="col-sm-4">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>First Name</span>
              </label>
              <input class="form-control input-control" name="first_name" placeholder="First Name">
              @if ($errors->has('first_name'))
                <div class="error">{{ $errors->first('first_name') }}</div>
              @endif
            </div>
            <div class="col-sm-4">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>Last Name</span>
              </label>
              <input class="form-control input-control" name="last_name" placeholder="Last Name">
              @if ($errors->has('last_name'))
                <div class="error">{{ $errors->first('last_name') }}</div>
              @endif
            </div>
            <div class="col-sm-4">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>Email</span>
              </label>
              <input class="form-control input-control" type="email" name="email" placeholder="Email"
                autocomplete="off">
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
                @foreach ($countryList as $country)
                  <option value="{{ $country->country_name }}" data-id="{{ $country->id }}">{{ $country->country_name }}
                  </option>
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
              <input class="form-control input-control" type="text" name="organisation" placeholder="Organization">
            </div>
            <div class="col-sm-6">
              <label for="account" class="ts-label">
                <span>Title</span>
              </label>
              <input class="form-control input-control" type="text" name="designation" placeholder="Designation">
            </div>
          </div>
          <div class="row gy-3 mb-24">
            <div class="col-sm-6">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>Password</span>
              </label>
              <input class="form-control input-control" type="password" name="password" placeholder="Password"
                id="password" required>
              @if ($errors->has('password'))
                <div class="error">{{ $errors->first('password') }}</div>
              @endif
              <div id="passwordMessage"></div>
            </div>
            <div class="col-sm-6">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>Confirm Password</span>
              </label>
              <input class="form-control input-control" type="password" name="password_confirmation"
                id="password_confirmation" placeholder="Confirm Password" required>
              @if ($errors->has('password_confirmation'))
                <div class="error">{{ $errors->first('password_confirmation') }}</div>
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
    </div>
  </div>
  <style>
    .error {
      color: red;
    }
  </style>

  @push('scripts')
    <script>
      $(document).ready(function() {
        $('#stateSection').hide();
        $("#country").on('change', function() {
          var countryId = $("#country").find(':selected').attr('data-id');
          var actionUrl = "<?= url('') ?>/admin/select-state";
          var request = $.ajax({
            url: actionUrl,
            type: "get",
            data: {
              countryId: countryId
            },
          });
          request.done(function(responce) {
            var states = [];
            for (let i = 0; i < responce.length; i++) {
              states += `<option>` + responce[i].state_name + `</option>`;
            }
            console.log(states);
            if (states.length > 0) {
              $('#stateSection').show();
              $('#state').html(states);
            } else {
              $('#stateSection').hide();
            }
          });
        });

        function checkPasswordStrength() {
          var password = document.getElementById("password").value;
          var confirmPassword = document.getElementById("password_confirmation").value;
          var passwordMessage = document.getElementById("passwordMessage");
          var strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
          // '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
          // var strongPasswordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,16}$/;
          
          if (strongPasswordRegex.test(password)) {
            if (password === confirmPassword) {
              passwordMessage.innerHTML = '<span style="color:green;">Strong Password</span>';
            } else {
              passwordMessage.innerHTML = '<span style="color:red;">Passwords do not match.</span>';
            }
          } else {
            passwordMessage.innerHTML =
              '<span style="color:red;">Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one number, and one special character.</span>';
          }
        }

        // Attach the checkPasswordStrength function to the input fields' 'input' events
        document.getElementById("password").addEventListener("input", checkPasswordStrength);
        document.getElementById("password_confirmation").addEventListener("input", checkPasswordStrength);
      });
    </script>
  @endpush
@endsection
