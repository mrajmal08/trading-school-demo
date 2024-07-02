@extends('layout.admin.app')

@section('admin.app-content')
    <div class="history-page">
        <div class="card card-box history-table">
            <div class="cardbox-header">
                <h3 class="cardbox-title">Market Data Upgrades </h3>
                <div class="filter-wrapper">
                    <form action="{{route('marketDataPurchaseList')}}" class="filter-form">
                        <div class="row justify-content-end">
                            <div class="col-sm-3">
                                <?php
                                $account_status = [
                                    'active' => 'Active',
                                    'fail' => 'Need Attention',
                                ];
                                ?>
                                {{-- <label for="status" class="form-label dark-th-color">Account Status</label> --}}
                                <select name="status" id="status" class="select-control">
                                   <option value="">Choose Status</option>
                                   @foreach ($account_status as $key => $value)
                                      <option value="{{ $key }}" {{ $key === $status ? 'selected' : '' }}>{{ $value }}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="input-control" name="dates" id="date-range" placeholder="Choose Duration" autocomplete="off"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="search" class="input-control" name="search" placeholder="Search...">
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
                        <th scope="col">Trader Name</th>
                        <th scope="col">Trader Email</th>
                        <th scope="col">Market Data Feed</th>
                        <th scope="col" style="text-align: left">CQG Trader</th>
                        <th scope="col" style="text-align: left">CQG Account</th>
                        <th scope="col" style="text-align: right">Price</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" style="text-align: left">Date</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ( $marketDataDetails as $marketVal )
                        <tr>
                            <td scope="row">{{$marketVal?->user?->userDetail?->first_name}} {{$marketVal?->user?->userDetail?->last_name}}</td>
                            <td scope="row">{{$marketVal?->user?->email }}</td>
                            <td scope="row">{{$marketVal?->marketData?->name }}</td>

                            <td scope="row">{{$marketVal?->packagePurchaseAccountDetail?->trader_name; }}</td>

                            {{-- <td scope="row">{{$marketVal?->amp_id }}</td>--}}

                            <td scope="row">{{$marketVal?->account_name }}</td>
                            <td scope="row" class="text-end">${{ number_format($marketVal?->package_price, 2) }}</td>

                            <td scope="row" class="text-center">
                                <?php
                                if ( $marketVal?->account_activation_status == 1) {
                                    $status = 'success';
                                    $label = 'Active';

                                } else  {
                                    $status = 'danger';
                                    $label = 'Needs Attention';
                                }
                                ?>
                                <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                            </td>

                            <td scope="row">{{$marketVal?->created_at->format('Y-m-d'); }}</td>
                            <td scope="row">
                                <div class="action-cell">
                                   @if ($marketVal?->account_activation_status == 0)
                                   <a href="{{ route('reProcessMarketData', $marketVal?->uuid) }}">
                                        <button class="raw-btn edit-btn">
                                        process
                                        </button>
                                    </a>
                                    <button class="raw-btn pay-now modify" data-id="{{$marketVal->user->id}}" data-bs-toggle="modal" id="" data-bs-target="#market-data-log-modal">
                                        Log
                                    </button>
                                   @endif
                                </div>
                             </td>

                        </tr>
                        @empty

                        @endforelse
                    {{--  @if(count($allAccounts) > 0)
                        @foreach ($allAccounts as $account)
                            <tr>

                                <td scope="row">{{$account->first_name}} {{$account->last_name}}</td>
                                <td scope="row">{{$account->email }}</td>
                                <td scope="row">{{$account->title}}</td>
                                <td scope="row">{{ $account->account_name }}</td>
                                <td scope="row">{{ $account->account_id }}</td>
                                <td scope="row">{{ $account->amp_id }}</td>
                                <td scope="row">{{ $account->trader_id }}</td>
                                <td scope="row">{{ $account->trader_name }}</td>
                                <td scope="row">{{ $account->account_status }}</td>
                                <td scope="row">{{ $account->package_price }}</td>

                                <td scope="row">
                                    <?php
                                    if ( $account->account_activation_status == 1) {
                                        $status = 'success';
                                        $label = 'Active';

                                    } else  {
                                        $status = 'danger';
                                        $label = 'In-Active';
                                    }
                                    ?>
                                    <span class="badge text-bg-{{ $status }}">{{ $label }}</span>
                                </td>
                                <td scope="row">
                                    <div class="action-cell">
                                       @if ($account->account_activation_status == 0)

                                       <a href="{{ route('account.packagePurchaseAccountActivate', $account->uuid) }}">
                                            <button class="raw-btn edit-btn">
                                            process
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
                    @endif  --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tr-pagination">
         {!! $marketDataDetails->withQueryString()->links() !!}
        </div>

        @include('partials.market-data-log-modal')
    </div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $(".modify").on('click',function(){
                $("#amount").val("");
                var userId = $(this).attr('data-id');
                $("#userid").val(userId);
                var actionUrl = "<?= url('') ?>/admin/market-data-purchase-log";
                var request = $.ajax({
                    url: actionUrl,
                    type: "get",
                    data: {userId : userId},
                });
                request.done(function(responce) {
                    $(".modalBody").html(responce.log);
                });
            });
        });
    </script>
@endpush
@endsection
