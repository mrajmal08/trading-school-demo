@extends('layout.app')

@section('app-content')
   <div class="history-page">
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Challenges Purchased</h3>
            <div class="filter-wrapper">
               <form action="{{ route('teacher.account.list') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-2">
                        <?php
                        $account_status = [
                              'Live' => 'Live',
                              'Waiting' => 'Waiting',
                        ];
                        ?>
                        {{-- <label for="status" class="form-label dark-th-color">Account Status</label> --}}
                        <select name="status" id="status" class="select-control dark-th-color" autocomplete="off">
                           <option value="">Select Status</option>
                           @foreach ($account_status as $key => $value)
                              <option value="{{ $value }}" {{ $key === $status ? 'selected' : '' }}>{{ $value }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-sm-2">
                        <input type="search" class="input-control" name="search" placeholder="Search..." />
                     </div>
                     <div class="col-sm-2">
                        <input type="text" class="input-control" name="dates" id="date-range"
                           placeholder="Choose Duration" autocomplete="off" />
                     </div>
                     <div class="col-sm-2">
                        <input type="submit" class="filter-submit" name="date" value="Filter">
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table-borderless trade-table table">
               <thead>
                  <tr>
                     <th scope="col" style="text-align: left">Trader Name</th>
                     <th scope="col">Trader Email</th>
                     <th scope="col">Challenge Name</th>
                     <th scope="col" style="text-align: right">Challenge Price</th>
                     <th scope="col" style="text-align: left">Challenge Status</th>
                     <th scope="col">CQG Logon</th>
                     <th scope="col">CQG Account</th>
                     <th scope="col">
                        Developer Info<br>
                        (CQG Account ID / CQG Trader ID)
                     </th>
                     <th scope="col" class="text-center">Operational Status</th>
                  </tr>
               </thead>
               <tbody>
                  @if (count($allAccounts) > 0)
                     @foreach ($allAccounts as $account)
                        <tr>
                           <td scope="row">{{ $account?->user?->userDetail?->fullName() }}</td>
                           <td scope="row">{{ $account?->user?->email }}</td>
                           <td scope="row">{{ $account?->cardChallenge?->title }}</td>
                           <td scope="row" class="text-end">${{ number_format($account?->cardChallenge?->price, 2) }}</d>
                           <td scope="row">
                              <?php
                              if ($account->account_activation_status == 1) {
                                  $status = 'success';
                                  $label = 'Live';
                              } else {
                                  $status = 'danger';
                                  $label = 'Waiting';
                              }
                              ?>
                              <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                           </td>
                           <td scope="row">{{ $account->trader_name }}</td>
                           <td scope="row" class="text-end">{{ $account->account_id }}</td>
                           <td scope="row">
                              @if ($account->account_id && $account->trader_id)
                                 {{ $account->account_id }} / {{ $account->trader_id }}
                              @endif
                           </td>

                           <td scope="row">
                              <div class="action-cell">
                                 @if ($account->account_activation_status == 0)
                                    <a
                                       href="{{ route('teacher.account.packagePurchaseAccountActivate', $account->uuid) }}" class="process-btn">
                                       <button class="raw-btn edit-btn">
                                          process
                                       </button>
                                    </a>
                                    <button class="raw-btn pay-now modify" data-id="{{ $account->pId }}"
                                       data-bs-toggle="modal" id="" data-bs-target="#market-data-log-modal">
                                       Log
                                    </button>
                                 @endif

                                 @if (empty($account->account_id) || empty($account->trader_id) || empty($account->amp_id))
                                    <a href="{{ route('teacher.account.forcedAccount', $account->uuid) }}" class="force-link-{{ $account->id }} d-none">
                                       <button class="raw-btn edit-btn">
                                          force
                                       </button>
                                    </a>
                                 @endif

                                 @if ($account->account_status == 'Success - Pending Verification')
                                    <a href="{{ route('teacher.account.activeLiveAccount', $account->uuid) }}">
                                       <button class="raw-btn edit-btn bg-info">
                                          live
                                       </button>
                                    </a>
                                 @endif
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  @else
                     <tr>
                        <td colspan="10" scope="row" class="text-center">Data not found</td>
                     </tr>
                  @endif
               </tbody>
            </table>
         </div>
      </div>
      <div class="tr-pagination">
         {!! $allAccounts->withQueryString()->links() !!}
      </div>
      @include('partials.teacher.account-list-data-log-modal')
   </div>

   @push('scripts')
      <script>
         $(document).ready(function() {
            if (localStorage.getItem('teacher-process')) {
               $('.' + localStorage.getItem('teacher-process')).removeClass('d-none');
            }

            $('.process-btn').on("click", function(){
               let siblings = $(this).siblings(".d-none").removeClass('d-none');
               let forceEle = siblings[0].className;
               localStorage.setItem("teacher-process", forceEle);
            });

            $(".modify").on('click', function() {
               $("#amount").val("");
               var userId = $(this).attr('data-id');
               $("#userid").val(userId);

               // var actionUrl = "<?= url('') ?>/admin/account-list-log";
               var actionUrl = "{{ route('teacher.account.list.log') }}";

               var request = $.ajax({
                  url: actionUrl,
                  type: "get",
                  data: {
                     userId: userId
                  },
               });
               request.done(function(responce) {
                  console.log(JSON.stringify(responce));
                  $("#accountLog p").text(JSON.stringify(responce));
               });
            });
         });
      </script>
   @endpush
@endsection
