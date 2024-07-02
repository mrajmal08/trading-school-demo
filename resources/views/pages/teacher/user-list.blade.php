@extends('layout.app')

@section('app-content')
   <div class="users-page">
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Manage Traders</h3>
            <div class="filter-wrapper">
               <form method="get" action="{{ route('teacher.trader.list') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <input type="search" class="input-control" name="search" placeholder="Search...">
                     </div>
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <input type="text" class="input-control" name="dates" id="date-range" placeholder="Choose Duration" autocomplete="off">
                     </div>
                     <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <input type="submit" class="filter-submit" name="filter" value="Filter">
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table-borderless trade-table table">
               <thead>
                  <tr>
                     <th scope="col">Trader Name</th>
                     <th scope="col">Trader Email</th>
                     <th scope="col">Current Challenge</th>
                     <th scope="col">Challenge Status</th>
                     <th scope="col">CQG Logon</th>
                     <th scope="col">CQG Account</th>
                     <th scope="col">Challenges Purchased</th>
                     <th scope="col" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if (count($allUser) > 0)
                     @foreach ($allUser as $user)
                        <tr>
                           <td scope="row">{{ optional($user->userDetail)->first_name }}
                              {{ optional($user->userDetail)->last_name }}</td>
                           <td scope="row">{{ $user['email'] }}</td>
                           <td scope="row">
                              <?php
                              $title = '';
                              if ($user?->packagePurchaseAccountDetail->isNotEmpty()) {
                                  // $titles = $user?->packagePurchaseAccountDetail->map(function($package){
                                  //    if ($package?->cardChallenge !== null) {
                                  //       return $package?->cardChallenge?->title;
                                  //    }
                                  // });
                                  $title = $user?->packagePurchaseAccountDetail->last()?->cardChallenge?->title;
                                  $service = $user?->packagePurchaseAccountDetail->last()?->cardChallenge?->service?->name;
                              }
                              ?>
                              {!! $title ? $title . ' - ' . "($service)" : '' !!}
                              {{-- {{ (count($user?->packagePurchaseAccountDetail) > 0) ?  $user?->packagePurchaseAccountDetail[0]?->cardChallenge?->title : "" }} {{ (count($user?->packagePurchaseAccountDetail)>0) ? "(".$user?->packagePurchaseAccountDetail[0]?->cardChallenge?->service?->name .")" : "" }} --}}
                           </td>
                           <td scope="row">
                              {{-- @if ($user->packagePurchaseAccountDetail->isNotEmpty())
                                 {{ $user->packagePurchaseAccountDetail[0]->account_status }}
                              @endif --}}
                              @if ($user->packagePurchaseAccountDetail->isNotEmpty())
                              <?php
                                 $status = $user->packagePurchaseAccountDetail->last()->account_status;
                                 if ($status == 'Fail' || $status == 'Failed') {
                                    $badge_bg = 'text-bg-danger';
                                 } else if ($status == 'In-Progress') {
                                    $badge_bg = 'text-bg-primary';
                                 } else if ($status == 'Successed' || $status == 'Success') {
                                    $badge_bg = 'text-bg-success';
                                 }
                              ?>
                              <span class="badge {{ $badge_bg }}">{{ $status }}</span>
                           @endif
                           </td>
                           <td scope="row">
                              @if ($user->packagePurchaseAccountDetail->isNotEmpty())
                                 {{ $user->packagePurchaseAccountDetail->last()->trader_name }}
                              @endif
                           </td>
                           <td scope="row" class="text-end">
                              {{ $user->account_id_number }}
                           </td>
                           <td scope="row" class="text-end">{{ count($user->packagePurchaseAccountDetail) }}</td>
                           <td scope="row">
                              <div class="action-cell">
                                 <a href="{{ route('teacher.reset-history', $user->id) }}">
                                    <button class="raw-btn matrix-btn">View History</button>
                                 </a>
                                 <a href="{{ route('teacher.trader.detail', $user->uuid) }}">
                                    <button class="raw-btn matrix-btn">View Details</button>
                                 </a>
                                 <button class="raw-btn pay-now modify" data-id="{{ $user['id'] }}"
                                    data-bs-toggle="modal" id="" data-bs-target="#user-modify-modal">
                                    Adjust Balance
                                 </button>
                                 <button class="raw-btn edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#user-reset-modal{{ $user->uuid }}">Reset Challenge</button>
                                 <a href="{{ route('teacher.trader.edit', $user['id']) }}">
                                    <button class="raw-btn edit-btn">
                                       edit profile
                                    </button>
                                 </a>
                                 @include('partials.teacher.user-reset-modal')
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
         {!! $allUser->withQueryString()->links() !!}
      </div>
   </div>
   @include('partials.teacher.user-modify-modal')
   @push('scripts')
      <script>
         $(document).ready(function() {
            $(".add").on('click', function(e) {
               $("#addWithdraw").val('add');

               console.log($("#addWithdraw").val());
            });
            $(".withdraw").on('click', function(e) {
               $("#addWithdraw").val('withdraw');
               console.log($("#addWithdraw").val());
            });

            $(".modify").on('click', function() {
               $("#amount").val("");
               var userId = $(this).attr('data-id');
               $("#userid").val(userId);
               var actionUrl = "{{ route('teacher.balance') }}";
               var request = $.ajax({
                  url: actionUrl,
                  type: "get",
                  data: {
                     userId: userId
                  },
               });

               request.done(function(responce) {
                  console.log(formatNumber(responce.balance));
                  $('.text-start h4 span').html(responce.account_name);
                  $('.plan-price-sm').html(dollarFormat(responce.balance));
                  $("#currentbalance").val(responce.balance);
               });
            });

            $('.modal_close').on('click', function() {
               // alert("ok");
               $('#user-reset-modal').modal('hide');
            });
         });
      </script>
   @endpush
@endsection
