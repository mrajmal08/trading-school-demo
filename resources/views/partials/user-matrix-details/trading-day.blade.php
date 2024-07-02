<div class="card-box matrix-details-card trading-day">
  <div class="card-header d-flex justify-content-between mb-4">
     <div class="header-item d-flex gap-3">
        <img src="{{ asset('assets/images/trading-green.png') }}" alt="" class="rounded-circl" style="width: 24px; height: 24px;">
        <h5 class="ml-2">Trading Day</h5>
     </div>
     <h5>
        @php
           $trading_day = 100 - ((30 - $progress['trading_day']) / 30) * 100;
        @endphp
        {{!empty($progress['trading_day']) ? number_format($trading_day, 2) : 0}}%
     </h5>
  </div>
  <div class="card-body">
     <div class="row gx-md-5">
        <div class="col-md-12">
            <div class="reports">
               <h4>Day <?= !empty($progress) ? $progress['trading_day']: 0 ?></h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="reports d-flex justify-content-between mt-3">
               <?php
                  if (!empty($progress)) {
                     $remainingDays = 30 - $progress['trading_day'];
                     if ($remainingDays > 1) {
                        $remainDays = $remainingDays." days are";
                     } else {
                        $remainDays = $remainingDays." day is";
                     }
                  } else {
                     $remainDays = "30 Days";
                  }
               ?>
               <p class="text-light-white ">Of 30 Days ({{ $remainDays }} left)</p>
               <p class="fw-semibold text-white fs-6">
                  Minimum Days:
                  <span class="text-light-white"><?= !empty($progress) ? $progress['minimum_days']: 0 ?> Days</span>
               </p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="reports">
               <div class="progress rounded-pill progress-utility" role="progressbar" aria-label="Trading Day" aria-valuenow="{{$trading_day}}" aria-valuemin="0" aria-valuemax="100">
                  @if ($progress)
                     <div class="progress-bar {{ $progress['trading_day'] > 15 ? 'bg-success' : 'bg-danger' }}" style="width: {{$trading_day}}%"></div>
                  @else
                     <div class="progress-bar" style="width: 0%"></div>
                  @endif
                </div>
            </div>
        </div>
     </div>
  </div>
</div>