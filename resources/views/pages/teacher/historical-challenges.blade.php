@extends('layout.app')

@section('app-content')
   <div class="history-page">
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Historical Challenges</h3>
            <div class="filter-wrapper">
               <form action="{{ route('teacher.historical_challenges') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-2">
                        <?php
                        $account_status = [
                            'Live' => 'Live',
                            'Waiting' => 'Waiting',
                        ];
                        ?>
                        {{-- <label for="status" class="form-label dark-th-color">Account Status</label> --}}
                        <select name="status" id="status" class="select-control dark-th-color">
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
                        <input type="submit" class="filter-submit" name="date" value="Filter" />
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table-borderless trade-table table">
               <thead>
                  <tr>
                     <th scope="col" style="text-align: left">Name</th>
                     <th scope="col">Email</th>
                     <th scope="col">Challenge Title</th>
                     <th scope="col" class="text-end">Challenge Price</th>
                     <th scope="col">Challenge Status</th>
                     <th scope="col">CQG Trader</th>
                     <th scope="col">CQG Account</th>
                     {{-- <th scope="col">Trading Day</th>
                     <th scope="col" class="text-end">Open Contracts</th>
                     <th scope="col" class="text-end">Net Liq Value</th>
                     <th scope="col" class="text-end">SOD Balance</th>
                     <th scope="col" class="text-end">Current Daily P&L</th>
                     <th scope="col" class="text-end">Number of Contracts</th>
                     <th scope="col" class="text-end">Daily Loss Limit</th>
                     <th scope="col" class="text-end">Max Drawdown</th> --}}
                     <th scope="col" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($histories as $history)
                     <tr>
                        <td scope="row" style="white-space: nowrap">{{ $history?->user?->userDetail?->fullName() }}</td>
                        <td scope="row">{{ $history?->user?->email }}</td>
                        <td scope="row">{{ $history?->cardChallenge?->title }}</td>
                        <td scope="row" class="text-end">${{ number_format($history?->cardChallenge?->price, 2) }}</td>
                        <td scope="row">
                           <?php
                           if ($history->account_activation_status == 1) {
                               $status = 'success';
                               $label = 'Live';
                           } else {
                               $status = 'danger';
                               $label = 'Waiting';
                           }
                           ?>
                           <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                        </td>
                        <td scope="row">{{ $history->account_name }}</td>
                        <td scope="row">{{ $history->trader_name }}</td>
                        {{-- <td scope="row" class="text-end">{{ $history->trading_day }}</td>
                        <td scope="row" class="text-end">{{ $history->open_contracts }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->net_liq_value, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->sodbalance, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->current_daily_pl, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->rule_1_value, 2) }}/ ${{ number_format($history->rule_1_maximum, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->rule_2_value, 2) }}/ ${{ number_format($history->rule_2_maximum, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($history->rule_3_value, 2) }}/ ${{ number_format($history->rule_3_maximum, 2) }}</td> --}}
                        <td scope="row">
                           <div class="action-cell">
                              <a class="raw-btn edit-btn" href="{{ route('teacher.challenge_history', $history->uuid) }}">
                                 View State
                              </a>
                           </div>
                        </td>
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
         {!! $histories->withQueryString()->links() !!}
      </div>
   </div>
@endsection