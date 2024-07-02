<div class="matrix-table">
  <div class="card card-box history-table">
     <div class="cardbox-header">
        <h3 class="cardbox-title">Live Positions</h3>
     </div>
     <div class="table-responsive">
        <table class="table-borderless trade-table table">
           <thead>
              <tr>
                 <th scope="col" style="text-align: left">Symbol</th>
                 <th scope="col" style="text-align: right">Quantity</th>
                 <th scope="col" style="text-align: right">Avg Price</th>
                 <th scope="col" style="text-align: right">Realized P&L</th>
                 <th scope="col" style="text-align: right">Open P&L</th>
                 <th scope="col" style="text-align: right">Total P&L</th>
              </tr>
           </thead>
           <tbody>
              @forelse ($livePositions as $position)
                 <tr>
                    <td scope="row" style="text-align: left">{{ $position->symbol }}</td>
                    <td scope="row" style="text-align: right">{{ $position->quantity }}</td>
                    <td scope="row" style="text-align: right">${{ number_format($position->avgPrice, 2) }}</td>
                    <td scope="row" style="text-align: right">${{ number_format($position->realizedPl, 2) }}</td>
                    <td scope="row" style="text-align: right">${{ number_format($position->openPl, 2) }}</td>
                    <td scope="row" style="text-align: right">${{ number_format($position->totalPl, 2) }}</td>
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
</div>