@extends('layout.app')

@section('app-content')
   <div class="history-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end"></div>
      </div>
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Challenges Reset History</h3>
            <div class="filter-wrapper">
               {{-- <form action="{{ route('admin.challenge_history', $id) }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-2">
                        <input type="search" class="input-control" name="search" placeholder="Search...">
                     </div>
                     <div class="col-sm-2">
                        <input type="text" class="input-control" name="dates" id="date-range"
                           placeholder="Choose Duration" />
                     </div>
                     <div class="col-sm-2">
                        <input type="submit" class="filter-submit" name="date" value="Filter">
                     </div>
                  </div>
               </form> --}}
            </div>
         </div>
         <div class="table-responsive">
            <table class="table-borderless trade-table table">
               <thead>
                  <tr>
                     {{-- <th scope="col" style="text-align: left">Name</th>
                     <th scope="col">Email</th> --}}
                     {{-- <th scope="col">Trader</th> --}}
                     <th scope="col">Account</th>
                     <th scope="col">Trading Day</th>
                     <th scope="col">Open Contracts</th>
                     <th scope="col" class="text-end">Net Liq Value</th>
                     <th scope="col" class="text-end">SOD Balance</th>
                     <th scope="col" class="text-end">Current Daily P&L</th>
                     <th scope="col" class="text-end">Number of  Contracts</th>
                     <th scope="col" class="text-end">Daily Loss Limit</th>
                     <th scope="col" class="text-end">Max Drawdown</th>
                     <th scope="col" class="text-center"></th>
                  </tr>
               </thead>
               <tbody>
                  {{-- {{ dd($histories) }} --}}
                  @forelse ($histories as $history)
                     <tr>
                        {{-- <td scope="row" style="white-space: nowrap">{{ $history->user->userDetail->fullName() }}</td>
                        <td scope="row">{{ $history->user->email }}</td> --}}
                        {{-- <td scope="row">{{ $history->trader_name }}</td> --}}
                        <td scope="row">{{ $history->account_name }}</td>
                        <td scope="row">{{ $history->trading_day }}</td>
                        <td scope="row">{{ $history->open_contracts }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->net_liq_value, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->sodbalance, 2) }}</td>
                        <td scope="row" class="text-end">{{ number_format($history->current_daily_pl, 2) }}</td>
                        <td scope="row">{{ $history->rule_1_value }} / {{ $history->rule_1_maximum }}</td>
                        <td scope="row">{{ $history->rule_2_value }} / {{ $history->rule_2_maximum }}</td>
                        <td scope="row">{{ $history->rule_3_value }} / {{ $history->rule_3_maximum }}</td>
                        <td scope="row"></td>
                        {{-- <td scope="row">
                           <div class="action-cell">
                              <a href="{{ route('account.packagePurchaseAccountActivate', $history->uuid) }}">
                                 <button class="raw-btn edit-btn">
                                    Position
                                 </button>
                              </a>
                           </div>
                        </td> --}}
                     </tr>
                  @empty
                     <tr>
                        <td colspan="10" scope="row" class="text-center">No Data Found</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
      <div class="tr-pagination">
         {!! $histories->links() !!}
      </div>
      {{-- @include('partials.account-list-data-log-modal') --}}
   </div>
@endsection
{{-- @push('scripts')
      <script>
         $(document).ready(function() {
            $(".modify").on('click', function() {
               $("#amount").val("");
               var userId = $(this).attr('data-id');
               $("#userid").val(userId);
               var actionUrl = "<?= url('') ?>/admin/account-list-log";
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
   @endpush --}}