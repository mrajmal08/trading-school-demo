@extends('layout.app')

@section('app-content')
<!-- Matrix Page -->
<div class="matrix-page">
   <!-- Radial and Report Cards -->
   <div class="row g-sm-4 g-3">
      <div class="col-lg-6">
         <div class="card-box matrix-details-card">
            <div class="card-body">
               <div class="row gx-md-5">
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-red">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Number Trades</span>
                           <h5><?= !empty($allTrade) ? $allTrade->numberTrades: 0 ?></h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-red">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Total P/L</span>
                           <h5><?= !empty($allTrade) ? $allTrade->totalPl: 0 ?></h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-blue">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Average Trading Time</span>
                           <h5><?= !empty($allTrade) ? $allTrade->avgTradingTime: 0 ?></h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-blue">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Longest Trading Time</span>
                           <h5><?= !empty($allTrade) ? $allTrade->longestTradingTime: 0 ?></h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-yellow">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Percent Profitable</span>
                           <h5><?= !empty($allTrade) ? $allTrade->percentProfitable: 0 ?></h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="matrix-content">
                        <div class="card-sm card-yellow">
                           <img src="{{ asset('assets/images/plug.png') }}" alt="">
                        </div>

                        <div class="reports">
                           <span>Expectancy</span>
                           <h5><?= !empty($allTrade) ? $allTrade->expectancy: 0 ?></h5>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-6">
         <div class="card-box progress-card">
            <div class="row">
               <div class="col-sm-6 col-lg-12 col-xl-6 space-bottom">
                  <div class="winning-progress text-center">
                     <svg class="radial-progress winning-circle" data-percentage="{{ $winningRate }}" viewBox="0 0 80 80">
                        <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                        <circle class="complete" cx="40" cy="40" r="35"
                           style="stroke-dashoffset: 39.58406743523136;">
                        </circle>
                        <text class="percentage" x="50%" y="57%"
                           transform="matrix(0, 1, -1, 0, 80, 0)">{{ $winningRate }}%</text>
                     </svg>
                     <h5>Winning Rate</h5>
                  </div>
               </div>
               <div class="col-sm-6 col-lg-12 col-xl-6">
                  <div class="losing-progress text-center">
                     <svg class="radial-progress winning-circle" data-percentage="{{ $losingrate }}" viewBox="0 0 80 80">
                        <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                        <circle class="complete" cx="40" cy="40" r="35"
                           style="stroke-dashoffset: 39.58406743523136;">
                        </circle>
                        <text class="percentage" x="50%" y="57%"
                           transform="matrix(0, 1, -1, 0, 80, 0)">{{ $losingrate }}%</text>
                     </svg>
                     <h5>Losing Rate</h5>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Charts -->
   <div class="row mt-4">
      <div class="col-sm-12">
         <div class="card card-box history-table chart-box">
            <div class="cardbox-header">
               <h3 class="cardbox-title">Matrix Graph</h3>
               <ul class="chart-legend">
                  <li>
                     <label class="balance">
                        <input type="checkbox" checked value="Balance" />
                        Balance
                     </label>
                  </li>
                  <li>
                     <label class="equity">
                        <input type="checkbox" checked value="Equity" />
                        Equity
                     </label>
                  </li>
               </ul>
            </div>
            <div class="cardbox-body">
               <div id="chart"></div>
            </div>
         </div>
      </div>
   </div>
   <!-- Table Data -->
   <div class="row mt-4">
      <div class="col-md-12">
         <div class="matrix-table">
            <div class="card card-box history-table">
               <div class="cardbox-header">
                  <h3 class="cardbox-title">Trade History</h3>
                  <!-- <ul class="header-filter">
                     <li class="active"><a href="">Positions</a></li>
                     <li><a href="">Fills</a></li>
                  </ul> -->
               </div>
               <div class="table-responsive">
                  <table class="table-borderless trade-table table">
                     <thead>
                        <tr>
                           <!-- <th scope="col">Exchange</th>
                           <th scope="col">Symbol</th>
                           <th scope="col">Expiry</th>
                           <th scope="col">Volume</th>
                           <th scope="col">Direction</th>
                           <th scope="col">Avg. Open File Price</th>
                           <th scope="col">Open PnL</th>
                           <th scope="col">Closed PnL</th>
                           <th scope="col">Total PnL</th> -->
                           <th scope="col">Account</th>
                           <th scope="col">Symbol</th>
                           <th scope="col">Expiry</th>
                           <th scope="col">Buy</th>
                           <th scope="col">Sale</th>
                           <!-- <th scope="col">Quantity</th> -->
                           <th scope="col" style="text-align: left;">Price</th>
                           <!-- <th scope="col">Challanges Id</th>  -->
                        </tr>
                     </thead>
                     <tbody>

                        @forelse ($tradeRecord as $record)
                           <tr>
                              <td sscope="row">{{ $record->account }}</td>
                              <td scope="row">{{ $record->symbol }}</td>
                              <td scope="row">{{ $record->timestamp }}</td>
                              <td scope="row">${{ $record->buys }}</td>
                              <td scope="row">${{ $record->sells }}</td>
                              {{-- <td scope="row">{{ $record->quantity }}</td> --}}
                              <td scope="row">{{ $record->price }}</td>
                              {{-- <td scope="row">{{ @$record->packagePurchaseAccountDetail->cardChallenge->title}}</td> --}}
                           </tr>
                        @empty
                        <tr>
                           <td style="text-align: center;" colspan="6">No Data Found</td>
                        </tr>
                        @endforelse

                     </tbody>
                  </table>
               </div>
            </div>
            {{-- <div class="tr-pagination">
               {!! $tradeRecord->links() !!}
            </div> --}}
         </div>
      </div>
   </div>
</div>
@push('matrix-script')
   <script>
      $(document).ready(function() {
         // chart js options
         let options = {
            grid: {
               borderColor: "#5D6588",
               strokeDashArray: 3,
            },
            legend: {
               show: false,
            },
            colors: ["#F7931A", "#403BEC", "#02A42F"],
            series: [{
                  name: "Drawdown",
                  data: <?= $graphHistory->pluck('eodDrawdown'); ?>,
               },
               {
                  name: "ProfitTarget",
                  data: <?= $graphHistory->pluck('eodProfitTarget'); ?>,
               },
               {
                  name: "Day",
                  data: <?= $graphHistory->pluck('dayIndex'); ?>,
               },
            ],
            chart: {
               type: "bar",
               height: 370,
               toolbar: {
                  tools: {
                     download: false,
                  },
               },
            },
            plotOptions: {
               bar: {
                  horizontal: false,
                  borderRadius: 4,
               },
            },
            dataLabels: {
               enabled: false,
            },
            stroke: {
               show: false,
            },
            xaxis: {
               categories: <?= $graphHistory->pluck('dayIndex') ?>,
               labels: {
                  style: {
                     colors: "#5D6588",
                     fontSize: "14px",
                     fontFamily: "Graphik",
                  },
               },
            },
            yaxis: {
               labels: {
                  style: {
                     colors: ["#5D6588"],
                     fontSize: "14px",
                     fontFamily: "Graphik",
                  },
                  formatter: (value) => {
                     return `$${value}`;
                  },
               },
            },
            fill: {
               opacity: 1,
            },
            tooltip: {
               style: {
                  fontSize: "16px",
                  fontFamily: "Graphik",
               },
               theme: "dark",
               custom: ({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
               }) => {
                  let balance = w.globals.initialSeries[0].data[dataPointIndex];
                  let equity = w.globals.initialSeries[1].data[dataPointIndex];
                  let day = w.globals.initialSeries[2].data[dataPointIndex];
                  return (
                     '<ul class="chart-tooltip">' +
                     '<li> <img src="/assets/images/balance.png" /> <strong>Drawdown</strong><span class="rate">' +
                     balance +
                     "</span></li>" +
                     '<li><img src="/assets/images/equity.png" /> <strong>Profit Target</strong> <span class="rate">' +
                     equity +
                     "</span></li>" +
                     '<li><img src="/assets/images/equity.png" /> <strong>Day</strong> <span class="rate">' +
                     day +
                     "</span></li>" +
                     "</ul>"
                  );
               },
            },
            responsive: [{
               breakpoint: 575,
               options: {
                  series: [{
                        name: "Balance",
                        data: [
                           55000, 90000, 48000, 20000, 60000, 70000, 10000,
                        ],
                     },
                     {
                        name: "Equity",
                        data: [
                           60000, 12000, 52000, 80000, 12000, 25000, 7000,
                        ],
                     },
                  ],
               },
            }, ],
         };

         var chart = new ApexCharts(document.querySelector("#chart"), options);
         chart.render();

         checkLegends();

         function checkLegends() {
            var allLegends = document.querySelectorAll(
               ".chart-legend input[type='checkbox']"
            );
            for (var i = 0; i < allLegends.length; i++) {
               if (!allLegends[i].checked) {
                  chart.toggleSeries(allLegends[i].value);
               }
            }
         }

         $(".chart-legend input[type='checkbox']").on("click", function(e) {
            $(this).parent().toggleClass("disabled");
            chart.toggleSeries($(this).val());
         });
      });
   </script>
@endpush
@endsection
