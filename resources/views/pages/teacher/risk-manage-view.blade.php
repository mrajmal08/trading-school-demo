@extends('layout.app')

@section('app-content')
   <div class="history-page">
      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Risk Management View</h3>
            <div class="filter-wrapper">
               <form action="{{ route('teacher.risk_manage') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-2">
                        <?php
                        $account_status = [
                            'In-Progress' => 'Active',
                            'Success' => 'Success',
                            'Fail' => 'Fail',
                        ];
                        ?>
                        {{-- <label for="status" class="form-label dark-th-color">Account Status</label> --}}
                        <select name="status" id="status" class="select-control">
                           <option value="">Select Status</option>
                           @foreach ($account_status as $key => $value)
                              <option value="{{ $key }}" {{ $key === $status ? 'selected' : '' }}>{{ $value }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-sm-2">
                        <input type="search" class="input-control" name="search" placeholder="Search...">
                     </div>
                     <div class="col-sm-2">
                        <input type="text" class="input-control" name="dates" id="date-range"
                           placeholder="Choose Duration" autocomplete="off"/>
                     </div>
                     <div class="col-sm-2">
                        <input type="submit" class="filter-submit" name="date" value="Filter">
                     </div>
                  </div>
               </form>
            </div>
            <button class="btn btn-dark window-reload" style="background-color: #000;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reload the window">
               <i class="bi bi-arrow-clockwise"></i>
            </button>
         </div>
         <div class="table-responsive">
            <?php
               $sort_alpha = $sort_order === 'asc' ? '<i class="bi bi-sort-alpha-down"></i>' : '<i class="bi bi-sort-alpha-down-alt"></i>';
               $sort_numeric = $sort_order === 'asc' ? '<i class="bi bi-sort-numeric-down"></i>' : '<i class="bi bi-sort-numeric-down-alt"></i>';
            ?>
            <table class="table-borderless trade-table table">
               <thead>
                  <tr>
                     <th scope="col" style="text-align: left">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'users.userDetail.name', 'sort_order' => $sort_by == 'users.userDetail.name' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Name
                           {!! $sort_alpha !!}
                        </a>
                     </th>
                     <th scope="col">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'users.email', 'sort_order' => $sort_by == 'users.email' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Email
                           {!! $sort_alpha !!}
                        </a>
                     </th>
                     <th scope="col">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'trader_id', 'sort_order' => $sort_by == 'trader_id' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Trader
                           {!! $sort_alpha !!}
                        </a>
                     </th>
                     <th scope="col">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'account_name', 'sort_order' => $sort_by == 'account_name' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Account
                           {!! $sort_alpha !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'trading_day', 'sort_order' => $sort_by == 'trading_day' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Trading Day
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'open_contracts', 'sort_order' => $sort_by == 'open_contracts' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Open Contracts
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'net_liq_value', 'sort_order' => $sort_by == 'net_liq_value' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Net Liq Value
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'sodbalance', 'sort_order' => $sort_by == 'sodbalance' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           SOD Balance
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'current_daily_pl', 'sort_order' => $sort_by == 'current_daily_pl' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Current Daily P&L
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     {{-- Rule 1 Value (of Rule 1 Maximum) --}}
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'rule_1_value', 'sort_order' => $sort_by == 'rule_1_value' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Number of Contracts
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'rule_2_value', 'sort_order' => $sort_by == 'rule_2_value' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Daily Loss Limit
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-end">
                        <a href="{{ route('teacher.risk_manage', ['sort_by' => 'rule_3_value', 'sort_order' => $sort_by == 'rule_3_value' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                           Max Drawdown
                           {!! $sort_numeric !!}
                        </a>
                     </th>
                     <th scope="col" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($risks_manage as $risk)
                     <tr>
                        <td scope="row" style="white-space: nowrap">{{ $risk->user->userDetail->fullName() }}</td>
                        <td scope="row">{{ $risk->user->email }}</td>
                        <td scope="row">{{ $risk->trader_name }}</td>
                        <td scope="row">{{ $risk->account_name }}</td>
                        <td scope="row" class="text-end">{{ $risk->trading_day }}</td>
                        <td scope="row" class="text-end">{{ $risk->open_contracts }}</td>
                        <td scope="row" class="text-end">${{ number_format($risk->net_liq_value, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($risk->sodbalance, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($risk->current_daily_pl, 2) }}</td>
                        <td scope="row" class="text-end">{{ number_format($risk->rule_1_value, 2) }} / {{ number_format($risk->rule_1_maximum, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($risk->rule_2_value, 2) }} / ${{ number_format($risk->rule_2_maximum, 2) }}</td>
                        <td scope="row" class="text-end">${{ number_format($risk->rule_3_value, 2) }} / ${{ number_format($risk->rule_3_maximum, 2) }}</td>
                        <td scope="row">
                           <div class="action-cell">
                              <button class="raw-btn edit-btn" data-bs-toggle="modal" id=""
                                 data-bs-target="#position-modal-{{ $risk->id }}">
                                 View Position
                              </button>
                              @includeWhen($risk->id, 'partials.position-modal', ['risk' => $risk])
                              <a class="raw-btn edit-btn" href="{{ route('teacher.risk_details', $risk->id) }}">
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
         {!! $risks_manage->links() !!}
      </div>
   </div>
@endsection
@push('scripts')
      <script>
         $(document).ready(function() {
            $(".window-reload").on("click", function(){
               window.location.reload();
            })
         });
      </script>
   @endpush