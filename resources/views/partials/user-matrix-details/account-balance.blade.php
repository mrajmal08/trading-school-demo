<div class="card-box matrix-details-card account-balance">
   <div class="card-header d-flex justify-content-between mb-4">
      <div class="header-item d-flex gap-3">
         <img src="{{ asset('assets/images/account-green.png') }}" alt="" class="rounded-circl"
            style="width: 24px; height: 24px;">
         <h5 class="ml-2">Account Balance</h5>
      </div>
      <h5>
         <?php
         if (!empty($progress['net_liq_value']) && !empty($progress['sodbalance'])) {
             $balance = $progress['net_liq_value'] - $progress['sodbalance'];
         } else {
             $balance = 0;
         }
         ?>
         {{ '$' . number_format($balance, 2) }}
      </h5>
   </div>
   <div class="card-body">
      <div class="row gx-md-5">
         <div class="col-md-12">
            <div class="reports">
               <div class="reports">
                  <h4><?= !empty($progress) ? '$' . number_format($progress['net_liq_value'], 2) : 0 ?></h4>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="reports d-flex justify-content-between mt-3">
               <p class="text-light-white">
                  Starting the day:
                  <?= !empty($progress) ? '$' . number_format($progress['sodbalance'], 2) : 0 ?>
               </p>
               <p class="fw-semibold fs-6 text-white">
                  Account Size:
                  <span class="text-light-white">
                     <?= !empty($progress) ? '$' . number_format($progress['account_size'], 2) : 0 ?>
                  </span>
               </p>
            </div>
         </div>
         <div class="col-md-12">
            <div class="reports">
               <div class="progress rounded-pill progress-utility position-relative" role="progressbar"
                  aria-label="Trading Day" aria-valuenow="{{ '30' }}" aria-valuemin="0" aria-valuemax="100">
                  <?php
                  if (!empty($progress)) {

                      if ($balance > 0) {
                        $percentage = ($balance / $progress['sodbalance']) * 100;
                      } elseif ($balance !== 0) {
                        $percentage = (abs($balance) / $progress['sodbalance']) * 100;
                      } else {
                        $graph = 0;
                      }

                      if ($balance !== 0) {
                        $graph = $percentage / 2;
                      }
                  }
                  ?>
                  @if ($progress)
                     @if ($balance < 0)
                        <div class="progress-bar rounded-pill bg-danger position-absolute end-50"
                           style="width: {{ $graph }}%; height: 30px;"></div>
                     @else
                        <div class="progress-bar rounded-pill bg-success position-absolute start-50"
                           style="width: {{ $graph }}%; height: 30px;"></div>
                     @endif
                  @else
                     <div class="progress-bar" style="width: 0%"></div>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



{{-- <div class="col-md-12">
   <div class="matrix-content">
      <div class="card-sm card-blue">
         <img src="{{ asset('assets/images/plug.png') }}" alt="">
      </div>
      <div class="reports">
         <span>Start Of Day Balance</span>
         <h5><?= !empty($progress) ? number_format($progress['sodbalance'], 2) : 0 ?></h5>
      </div>
   </div>
</div>
<div class="col-md-12">
   <div class="matrix-content">
      <div class="card-sm card-red">
         <img src="{{ asset('assets/images/plug.png') }}" alt="">
      </div>

      <div class="reports">
         <span>Account Size</span>
         <h5><?= !empty($progress['account_size']) ? $progress['account_size'] : 0 ?></h5>
      </div>
   </div>
</div>
<div class="col-md-12">
   <div class="matrix-content">
      <div class="card-sm card-blue">
         <img src="{{ asset('assets/images/plug.png') }}" alt="">
      </div>

      <div class="reports">
         <span>Profit Target</span>
         <h5><?= '$' . number_format($profit_target, 2) ?></h5>
      </div>
   </div>
</div> --}}
