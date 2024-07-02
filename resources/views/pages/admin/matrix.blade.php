@extends('layout.admin.app')
@section('admin.app-content')
   <?php
      use Carbon\Carbon;
      use Carbon\CarbonInterface;
   ?>
   <!-- Matrix Page -->
   <div class="matrix-page">
      <div class="row align-items-center mb-4">
         <div class="col-sm-2 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         @include('partials.user-matrix-details.headline')
      </div>

      <div class="row mb-4">
         <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.user-matrix-details.trading-day')
         </div>
         <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.user-matrix-details.account-balance')
         </div>
         <div class="col-md-6 col-lg-4">
            @include('partials.user-matrix-details.rule-risk')
         </div>
      </div>

      <!-- Trade Performance Statistics -->
      <div class="row mb-2">
         <div class="col-md-12">
            <div class="card-box matrix-details-card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-8">
                        <h4>Trade Performance</h4>
                     </div>
                     {{-- <div class="col-md-4">
                        <form action="" method="get">
                           <select name="filter" id="filter" class="form-select select-control">
                              <option value="">Search</option>
                              <option value="all">All</option>
                              <option value="1">Week 1</option>
                              <option value="2">Week 2</option>
                              <option value="3">Week 3</option>
                              <option value="4">Week 4</option>
                           </select>
                        </form>
                     </div> --}}
                  </div>
               </div>
            </div>
         </div>
      </div>
      {{-- <input type="hidden" id="package" name="package" value="{{ !empty($userDetail) ? $userDetail->id : '' }}"> --}}
      <div class="row g-sm-4 g-3 mb-4">
         <div class="col-lg-4">
            <div class="card-box matrix-details-card trade-details-card">
               <div class="card-header d-flex justify-content-between border-bottom border-dark mb-4">
                  <div class="header-item d-flex gap-3">
                     <h5 class="ml-2">All Trades</h5>
                  </div>
               </div>
               <div class="card-body allTrades">
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Gross P/L</p>
                     {{-- @dd($allTrade)  --}}
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($allTrade->totalPl) ? number_format($allTrade->totalPl, 2) : 0.0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Average Trade P&L</p>
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($allTrade->avgPlTrade) ? number_format($allTrade->avgPlTrade, 2) : 0.0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Trades</p>
                     <p class="w-50 px-2 text-start">{{ !empty($allTrade->numberTrades) ? $allTrade->numberTrades : 0 }}
                     </p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Contracts</p>
                     <p class="w-50 px-2 text-start">
                        {{ !empty($allTrade->numberContracts) ? $allTrade->numberContracts : 0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Avg Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                           if (!empty($allTrade->avgTradingTime)) {
                              $seconds = $allTrade->avgTradingTime / 1000;
                              echo Carbon::now()
                                 ->subSeconds($seconds)
                                 ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                           } else {
                              echo 0;
                           }
                        ?>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Longest Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                        if (!empty($allTrade->longestTradingTime)) {
                            $seconds = $allTrade->longestTradingTime / 1000;
                            echo Carbon::now()
                                ->subSeconds($seconds)
                                ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                        } else {
                            echo 0;
                        }
                        ?>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">% of Profitable Trades</p>
                     <p class="w-50 px-2 text-start">
                        @if (!empty($allTrade->percentProfitable))
                           {{  round($allTrade->percentProfitable * 100) }}
                        @else
                           {{ '0' }}
                        @endif
                     </p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Expectancy</p>
                     <p class="w-50 px-2 text-start">{{ !empty($allTrade->expectancy) ? $allTrade->expectancy : 0 }}</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-4">
            <div class="card-box matrix-details-card trade-details-card">
               <div class="card-header d-flex justify-content-between border-bottom border-dark mb-4">
                  <div class="header-item d-flex gap-3">
                     {{-- <img src="{{ asset('assets/images/trading-red.png') }}" alt="" class="rounded-circl" style="width: 24px; height: 24px;"> --}}
                     <h5 class="ml-2">Winning Trades</h5>
                  </div>
               </div>
               <div class="card-body winningTrade">
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Gross P/L</p>
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($profitTrades->totalPl) ? number_format($profitTrades->totalPl, 2) : 0.0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Average Trade P&L</p>
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($profitTrades->avgPlTrade) ? number_format($profitTrades->avgPlTrade, 2) : 0.0 }}
                     </p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Trades</p>
                     <p class="w-50 px-2 text-start">
                        {{ !empty($allTrade->numberTrades) ? $profitTrades->numberTrades : 0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Contracts</p>
                     <p class="w-50 px-2 text-start">
                        {{ !empty($allTrade->numberContracts) ? $profitTrades->numberContracts : 0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Avg Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                           if (!empty($profitTrades->avgTradingTime)) {
                              $seconds = $profitTrades->avgTradingTime / 1000;
                              echo Carbon::now()
                                 ->subSeconds($seconds)
                                 ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                           } else {
                              echo 0;
                           }
                        ?>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Longest Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                        if (!empty($profitTrades->longestTradingTime)) {
                            $seconds = $profitTrades->longestTradingTime / 1000;
                            echo Carbon::now()
                                ->subSeconds($seconds)
                                ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                        } else {
                            echo 0;
                        }
                        ?>
                     </p>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-4">
            <div class="card-box matrix-details-card trade-details-card">
               <div class="card-header d-flex justify-content-between border-bottom border-dark mb-4">
                  <div class="header-item d-flex gap-3">
                     <h5 class="ml-2">Losing Trades</h5>
                  </div>
               </div>
               <div class="card-body losingTrades">
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Gross P/L</p>
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($losingTrades->totalPl) ? number_format($losingTrades->totalPl, 2) : 0.0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Average Trade P&L</p>
                     <p class="w-50 px-2 text-start">
                        ${{ !empty($losingTrades->avgPlTrade) ? number_format($losingTrades->avgPlTrade, 2) : 0.0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Trades</p>
                     <p class="w-50 px-2 text-start">
                        {{ !empty($allTrade->numberTrades) ? $losingTrades->numberTrades : 0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end"># of Contracts</p>
                     <p class="w-50 px-2 text-start">
                        {{ !empty($allTrade->numberContracts) ? $losingTrades->numberContracts : 0 }}</p>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Avg Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                           if (!empty($losingTrades->avgTradingTime)) {
                              $seconds = $losingTrades->avgTradingTime / 1000;
                              echo Carbon::now()
                                 ->subSeconds($seconds)
                                 ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                           } else {
                              echo 0;
                           }
                        ?>
                  </div>
                  <div class="trade_performance__text d-flex">
                     <p class="w-50 px-2 text-end">Longest Trading Time</p>
                     <p class="w-50 px-2 text-start">
                        <?php
                           if (!empty($losingTrades->longestTradingTime)) {
                              $seconds = $losingTrades->longestTradingTime / 1000;
                              echo Carbon::now()
                                 ->subSeconds($seconds)
                                 ->diffForHumans(now(), Carbon::DIFF_ABSOLUTE, true, 3);
                           } else {
                              echo 0;
                           }
                        ?>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Live Positions -->
      <div class="row mb-4">
         <div class="col-sm-12">
            @include('partials.user-matrix-details.live-positions')
         </div>
      </div>

      <!-- Charts -->
      @include('partials.user-matrix-details.chart')
      {{-- <div class="row mt-4">
        <div class="col-sm-12">
           <div class="card card-box history-table chart-box">
              <div class="cardbox-header">
                 <h3 class="cardbox-title">Matrix Graph</h3>
                 <ul class="chart-legend">
                    <li>
                       <label class="balance">
                          <input type="checkbox" checked value="Drawdown" />
                          Drawdown
                       </label>
                    </li>
                    <li>
                       <label class="equity">
                          <input type="checkbox" checked value="ProfitTarget" />
                          Profit Target
                       </label>
                    </li>
                 </ul>
              </div>
              <div class="cardbox-body">
                 <div id="chart"></div>
              </div>
           </div>
        </div>
      </div> --}}
      <!-- Table Data -->
      @include('partials.user-matrix-details.trade-history')
   </div>
@endsection

@push('matrix-script')
   <script>
      $(document).ready(function() {
         // chart js options
         let options = {
            grid: {
               xaxis: {
                  lines: {
                     show: true,
                  },
               },
               yaxis: {
                  lines: {
                     show: true,
                  },
               },
            },
            legend: {
               show: false,
            },
            colors: ["#F7931A", "#403BEC", "#02A42F"],
            series: [{
                  name: "Drawdown",
                  data: <?= $graphHistory->pluck('eodDrawdown') ?>,
                  type: 'line',
               },
               {
                  name: "ProfitTarget",
                  data: <?= $graphHistory->pluck('eodProfitTarget') ?>,
                  type: 'area',
               },
               {
                  name: "Day",
                  data: <?= $graphHistory->pluck('eodNetLiq') ?>,
                  type: 'area',
               },
            ],
            chart: {
               type: "line",
               height: 370,
               toolbar: {
                  tools: false
               },
            },
            markers: {
               discrete: [{
                     seriesIndex: 0,
                     dataPointIndex: 6,
                     fillColor: '#039A1B',
                     strokeColor: '#fff',
                     size: 5,
                     shape: 'circle', // "circle" | "square" | "rect"
                  },
                  {
                     seriesIndex: 3,
                     dataPointIndex: 4,
                     fillColor: '#FD0100',
                     strokeColor: '#fff',
                     size: 5,
                     shape: 'circle', // "circle" | "square" | "rect"
                  },
               ],
            },
            // plotOptions: {
            //    bar: {
            //       horizontal: false,
            //       borderRadius: 4,
            //    },
            // },
            dataLabels: {
               enabled: false,
            },
            stroke: {
               curve: 'smooth',
               width: 2,
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
               type: 'solid',
               opacity: [1, 0.1, 0.1, 1],
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
                     '<li><img src="/assets/images/equity.png" /> <strong>Account Value</strong> <span class="rate">' +
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
                        name: "Drawdown",
                        data: [55000, 90000, 48000, 20000, 60000, 70000, 10000],
                     },
                     {
                        name: "Profit Target",
                        data: [60000, 12000, 52000, 80000, 12000, 25000, 7000],
                     }
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
            console.log(allLegends);
            for (var i = 0; i < allLegends.length; i++) {
               if (!allLegends[i].checked) {
                  console.log(allLegends[i].value);
                  chart.toggleSeries(allLegends[i].value);
               }
            }
         }

         $(".chart-legend input[type='checkbox']").on("click", function(e) {
            console.log($(this).val());
            $(this).parent().toggleClass("disabled");
            chart.toggleSeries($(this).val());
         });
      });
   </script>


   <script>
      $('#filter').on('change', function() {
         var selectVal = $("#filter option:selected").val();
         var url = "{{ url('admin/ajax/matrix') }}";
         var pacageid = $("#package").val()
         var fullUrl = url + "/" + pacageid + "/" + selectVal;
         console.log(fullUrl);

         const formatNumber = (arg, decimal = 2) => {
            const param = typeof arg === 'string' ? parseFloat(arg).toFixed(decimal) : `${arg}`;
            return parseFloat(parseFloat(param).toFixed(decimal)).toLocaleString('en-US', {
               useGrouping: true,
               minimumFractionDigits: decimal,
            });
         };

         const dollarFormat = (amount, _scale = '') => {
            if (_scale) {
               console.log('SCALE', _scale, _scale.split('.')[1].length);
               const decimalLength = _scale.split('.')[1].length;
               return `$${formatNumber(amount, decimalLength)}`;
            }
            return `$${formatNumber(amount)}`;
         };

         $.ajax({
            url: fullUrl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
               console.log(data);
               if (data.allTrades) {
                  let trades = data.allTrades;
                  let tradeList = '';
                  Object.keys(trades).map((key) => {
                     tradeList += '<div class="trade_performance__text d-flex">';
                     if (key === 'totalPl')
                        tradeList += '<p class="w-50 px-2 text-end">Gross P/L</p>';
                     if (key === 'avgPlTrade')
                        tradeList += '<p class="w-50 px-2 text-end">Average Trade P&L</p>';
                     if (key === 'numberTrades')
                        tradeList += '<p class="w-50 px-2 text-end"># of Trades</p>';
                     if (key === 'numberContracts')
                        tradeList += '<p class="w-50 px-2 text-end"># of Contracts</p>';
                     if (key === 'avgTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Avg Trading Time</p>';
                     if (key === 'longestTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Longest Trading Time</p>';
                     if (key === 'percentProfitable')
                        tradeList += '<p class="w-50 px-2 text-end">% of Profitable Trades</p>';
                     if (key === 'expectancy')
                        tradeList += '<p class="w-50 px-2 text-end">Expectancy</p>';

                     if (key === 'totalPl' || key === 'avgPlTrade') {
                        tradeList += '<p class="w-50 px-2 text-start">' + dollarFormat(trades[key]) +
                           '</p>';
                     } else {
                        tradeList += '<p class="w-50 px-2 text-start">' + trades[key] + '</p>';
                     }
                     tradeList += '</div>';
                  });
                  $('.allTrades').html(tradeList);
               }

               if (data.profitTrades) {
                  let trades = data.profitTrades;
                  let tradeList = '';
                  Object.keys(trades).map((key) => {
                     tradeList += '<div class="trade_performance__text d-flex">';
                     if (key === 'totalPl')
                        tradeList += '<p class="w-50 px-2 text-end">Gross P/L</p>';
                     if (key === 'avgPlTrade')
                        tradeList += '<p class="w-50 px-2 text-end">Average Trade P&L</p>';
                     if (key === 'numberTrades')
                        tradeList += '<p class="w-50 px-2 text-end"># of Trades</p>';
                     if (key === 'numberContracts')
                        tradeList += '<p class="w-50 px-2 text-end"># of Contracts</p>';
                     if (key === 'avgTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Avg Trading Time</p>';
                     if (key === 'longestTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Longest Trading Time</p>';

                     if (key !== 'percentProfitable' && key !== 'expectancy') {
                        if (key === 'totalPl' || key === 'avgPlTrade') {
                           tradeList += '<p class="w-50 px-2 text-start">' + dollarFormat(trades[
                              key]) + '</p>';
                        } else {
                           tradeList += '<p class="w-50 px-2 text-start">' + trades[key] + '</p>';
                        }
                     }
                     tradeList += '</div>';
                  });
                  $('.winningTrade').html(tradeList);
               }

               if (data.losingTrades) {
                  let trades = data.losingTrades;
                  let tradeList = '';
                  Object.keys(trades).map((key) => {
                     tradeList += '<div class="trade_performance__text d-flex">';
                     if (key === 'totalPl')
                        tradeList += '<p class="w-50 px-2 text-end">Gross P/L</p>';
                     if (key === 'avgPlTrade')
                        tradeList += '<p class="w-50 px-2 text-end">Average Trade P&L</p>';
                     if (key === 'numberTrades')
                        tradeList += '<p class="w-50 px-2 text-end"># of Trades</p>';
                     if (key === 'numberContracts')
                        tradeList += '<p class="w-50 px-2 text-end"># of Contracts</p>';
                     if (key === 'avgTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Avg Trading Time</p>';
                     if (key === 'longestTradingTime')
                        tradeList += '<p class="w-50 px-2 text-end">Longest Trading Time</p>';

                     if (key !== 'percentProfitable' && key !== 'expectancy') {
                        if (key === 'totalPl' || key === 'avgPlTrade') {
                           tradeList += '<p class="w-50 px-2 text-start">' + dollarFormat(trades[
                              key]) + '</p>';
                        } else {
                           tradeList += '<p class="w-50 px-2 text-start">' + trades[key] + '</p>';
                        }
                     }
                     tradeList += '</div>';
                  });

                  $('.losingTrades').html(tradeList);
               }
            }
         });
      });
   </script>
@endpush
