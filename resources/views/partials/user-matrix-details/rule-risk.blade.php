<?php
$ruleThreeProgressValue = $progress['rule_three_value'] ? ($progress['rule_three_value'] / $progress['rule_three_maximum']) * 100 : 0;
$ruleTwoProgressValue = $progress['rule_two_value'] ? ($progress['rule_two_value'] / $progress['rule_two_maximum']) * 100 : 0;
$ruleOneProgressValue = $progress['rule_one_value'] ? ($progress['rule_one_value'] / $progress['rule_one_maximum']) * 100 : 0;
function getStatus($ruleOneProgressValue, $ruleTwoProgressValue, $ruleThreeProgressValue): string
{
    if ($ruleOneProgressValue > 100 || $ruleTwoProgressValue > 100 || $ruleThreeProgressValue > 100) {
        return 'Challenge Failed';
    }

    if (($ruleOneProgressValue > 80 && $ruleOneProgressValue <= 100) || ($ruleTwoProgressValue > 80 && $ruleTwoProgressValue <= 100) || ($ruleThreeProgressValue > 80 && $ruleThreeProgressValue <= 100)) {
        return 'Warning';
    }

    if (($ruleOneProgressValue >= 50 && $ruleOneProgressValue <= 80) || ($ruleTwoProgressValue >= 50 && $ruleTwoProgressValue <= 80) || ($ruleThreeProgressValue >= 50 && $ruleThreeProgressValue <= 80)) {
        return 'Careful';
    }

    return 'Keep Going';
}
$riskStatus = getStatus($ruleOneProgressValue, $ruleTwoProgressValue, $ruleThreeProgressValue);

function getProgressBarColor ($ruleProgressValue) {
   if ( $ruleProgressValue > 80 ) {
      return 'bg-danger';
   }

   if ( $ruleProgressValue >= 50 && $ruleProgressValue <= 80) {
      return 'bg-warning';
   }

   return 'bg-success';
}
?>
<div class="card-box matrix-details-card rule-risk">
   <div class="card-header d-flex justify-content-between mb-4">
      <div class="header-item d-flex gap-3">
         <img src="{{ asset('assets/images/trading-red.png') }}" alt="" class="rounded-circl"
            style="width: 24px; height: 24px;">
         <h5 class="ml-2">Status</h5>
      </div>
      <h5 class="text-end">
         <span
            class="d-inline-block {{ optional($userDetail)['account_status'] == 'Fail' ? 'text-danger' : 'text-success' }} mb-2">
            {{ optional($userDetail)['account_status'] }}
            {{-- {{ $riskStatus }} --}}
         </span>
         @if (optional($userDetail)['account_status'] == 'Success - Pending Verification')
            <button class="raw-btn edit-btn btn-md" data-bs-toggle="modal"
               data-bs-target="#packageLive{{ $userDetail['uuid'] }}">Live</button>
            @include('partials.user-matrix-details.rule-modal')
         @endif
      </h5>
   </div>

   <div class="card-body">
      <div class="row gx-md-5">
         @if ($progress['rule_one_enable'])
            <div class="col-md-12">
               <div class="reports mt-3">
                  <div class="d-flex justify-content-between">
                     <p class="text-light-white mb-1">{{ $progress['rule_one_key'] }}</p>
                     <p class="fw-semibold fs-6 text-white mb-1">
                        <span class="text-light-white">{{ $progress['rule_one_maximum'] }} Max</span>
                     </p>
                  </div>
                  <div class="progress rounded-pill progress-utility" role="progressbar" aria-label="{{ $progress['rule_one_key'] }}"
                     aria-valuenow="{{ $ruleOneProgressValue }}" aria-valuemin="0" aria-valuemax="100">
                     @if ($progress)
                        <div class="progress-bar {{ getProgressBarColor($ruleOneProgressValue) }}" style="width: {{ $ruleOneProgressValue }}%"></div>
                     @else
                        <div class="progress-bar" style="width: 0%"></div>
                     @endif
                  </div>
                  <p class="text-light-white text-center mb-1">{{ $progress['rule_one_value'] }}</p>
               </div>
            </div>
         @endif
            {{-- {{ dd($progress) }} --}}
         @if ($progress['rule_two_enable'])
            <div class="col-md-12">
               <div class="reports mt-2">
                  <div class="d-flex justify-content-between">
                     <p class="text-light-white mb-1">{{ $progress['rule_two_key'] }}</p>
                     <p class="fw-semibold fs-6 text-white mb-1">
                        <span class="text-light-white">${{ number_format($progress['rule_two_maximum'], 2) }} Max</span>
                     </p>
                  </div>

                  <div class="progress rounded-pill progress-utility" role="progressbar" aria-label="{{ $progress['rule_two_key'] }}"
                     aria-valuenow="{{ $ruleTwoProgressValue }}" aria-valuemin="0" aria-valuemax="100">
                     @if ($progress)
                        <div class="progress-bar {{ getProgressBarColor($ruleTwoProgressValue) }}" style="width: {{ $ruleTwoProgressValue }}%"></div>
                     @else
                        <div class="progress-bar" style="width: 0%"></div>
                     @endif
                  </div>
                  <p class="text-light-white text-center mb-1">${{ number_format($progress['rule_two_value'], 2) }}</p>
               </div>
            </div>
         @endif

         @if ($progress['rule_three_enable'])
            <div class="col-md-12">
               <div class="reports mt-2">
                  <div class="d-flex justify-content-between">
                     <p class="text-light-white mb-1" style="width: 40%">{{ $progress['rule_three_key'] }}</p>
                     <p class="fw-semibold fs-6 text-white mb-1">
                        <span class="text-light-white">${{ number_format($progress['rule_three_maximum'], 2) }} Max</span>
                     </p>
                  </div>

                  <div class="progress rounded-pill progress-utility" role="progressbar" aria-label="{{ $progress['rule_three_key'] }}"
                     aria-valuenow="{{ $ruleThreeProgressValue }}" aria-valuemin="0" aria-valuemax="100">
                     @if ($progress)
                        <div class="progress-bar {{ getProgressBarColor($ruleThreeProgressValue) }}" style="width: {{ $ruleThreeProgressValue }}%"></div>
                     @else
                        <div class="progress-bar" style="width: 0%"></div>
                     @endif
                  </div>
                  <p class="text-light-white text-center mb-1">${{ number_format($progress['rule_three_value'], 2) }}</p>
               </div>
            </div>
         @endif
      </div>
   </div>
</div>