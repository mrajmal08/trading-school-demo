<div class="card-box matrix-details-card">
   <div class="card-header d-flex justify-content-between border-bottom border-dark mb-4">
      <div class="header-item d-flex gap-3">
         <img src="{{ asset('assets/images/trading-red.png') }}" alt="" class="rounded-circl"
            style="width: 24px; height: 24px;">
         <h5 class="ml-2">Status</h5>
      </div>
   </div>
   <div class="card-body">
      @if (!empty($userDetail['account_status']))
         @if ($userDetail['account_status'] == 'Success - Pending Verification')
            <button class="raw-btn edit-btn btn-sm" data-bs-toggle="modal" data-bs-target="#packageLive{{ $userDetail['uuid'] }}">Live</button>
            @include('partials.user-matrix-details.rule-modal')
         @endif
      @endif
   </div>
</div>
