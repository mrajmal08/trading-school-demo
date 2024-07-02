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

    <div class="row justify-content-center">
      <div class="col-md-12">
        <form method="post" action="{{ route('teacher.trader.update', $row->id) }}" enctype='multipart/form-data'>
          @csrf
          <div class="row gy-3 mb-24">
            <div class="col-sm-4">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>First Name</span>
              </label>
              <input class="form-control input-control" name="first_name" value="{{ $row->userDetail->first_name }}">
              @if ($errors->has('first_name'))
                <div class="error">{{ $errors->first('first_name') }}</div>
              @endif
            </div>
            <div class="col-sm-4">
              <label for="account" class="ts-label"><span class="text-danger required">*</span>
                <span>Last Name</span>
              </label>
              <input class="form-control input-control" name="last_name" value="{{ $row->userDetail->last_name }}">
              @if ($errors->has('last_name'))
                <div class="error">{{ $errors->first('last_name') }}</div>
              @endif
            </div>
            <div class="col-sm-4">
              <label for="gender" class="ts-label">
                <span>Gender</span>
              </label>
              <select name="gender" id="gender" class="select-control" style="width: 100%;">
                <option value="">Select Gender</option>
                <option value="1">Male</option>
                <option value="2">Female</option>
                <option value="3">Others</option>
              </select>
              @if ($errors->has('gender'))
                <div class="error">{{ $errors->first('gender') }}</div>
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
                  <option <?= $country->country_name == $row->userDetail->country ? 'selected' : '' ?>
                    value="{{ $country->country_name }}" data-id="{{ $country->id }}">
                    {{ $country->country_name }}</option>
                @endforeach
              </select>
              @if ($errors->has('country'))
                <div class="error">{{ $errors->first('country') }}</div>
              @endif
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
              <label for="age" class="ts-label"></span> <span class="text-danger required">*</span>
                <span>Date of Birth</span>
              </label>
              <input class="form-control input-control" type="date" name="age" id="age"
                value="{{ $row->userDetail->age }}">
              @if ($errors->has('age'))
                <div class="error">{{ $errors->first('age') }}</div>
              @endif
            </div>
            <div class="col-sm-6">
              <div class="row align-items-start">
                <div class="col-sm-4">
                  <div class="file-thumb card bg-dark ms-auto" style="height: 150px; width: 150px">
                    @if (optional($row->userDetail)->picture)
                      <img src="{{ optional($row->userDetail)->picture }}" alt="Profile Image"
                        class="img-thumbnail bg-dark h-100">
                    @else
                      <img src="" alt="Profile Image" class="img-thumbnail h-100 bg-dark" style="display: none" />
                    @endif
                  </div>
                </div>

                <div class="col-sm-8">
                  <label for="picture" class="ts-label"></span>
                    <span>Profile Image</span>
                  </label>
                  <input class="form-control input-control" type="file" name="picture" id="thumbnail">
                </div>
              </div>
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

        $("#gender").val("<?= $row->userDetail->gender ?>").change();
        $('#stateSection').hide();
        $("#country").on('change', function() {
          var countryId = $("#country").find(':selected').attr('data-id');
          var actionUrl = "/teacher/select-state";
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

        editState();

        function editState() {
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
              states += `<option value="` + responce[i].state_name + `">` + responce[i].state_name +
                `</option>`;
            }
            console.log(states);
            if (states.length > 0) {
              $('#stateSection').show();
              $('#state').html(states);
              $("#state").val("<?= $row->userDetail->state ?>").change();
            } else {
              $('#stateSection').hide();
            }
          });
        }
      });
    </script>
  @endpush
@endsection
