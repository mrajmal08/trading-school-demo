<?php
   use Carbon\Carbon;
?>
<div class="row mt-4">
  <div class="col-md-12">
     <div class="matrix-table">
        <div class="card card-box history-table">
           <div class="cardbox-header">
              <h3 class="cardbox-title">Trade History</h3>
           </div>
           <div class="table-responsive">
              <table class="table-borderless trade-table table">
                 <thead>
                    <tr>
                       <th scope="col">Timestamp</th>
                       <th scope="col">Account</th>
                       <th scope="col">Symbol</th>
                       <th scope="col" class="text-end">Buy</th>
                       <th scope="col" class="text-end">Sale</th>
                       <th scope="col" class="text-end">Price</th>
                    </tr>
                 </thead>
                 <tbody>
                    @forelse ($tradeRecord as $record)
                       <tr>
                          <td scope="row">{{ $record->timestamp ? Carbon::parse($record->timestamp)->format('M D Y H:i a') : Carbon::parse($record->trade_time)->format('M d Y H:i a')  }}</td>
                          <td scope="row">{{ $record->account }}</td>
                          <td scope="row">{{ $record->symbol }}</td>
                          <td scope="row" class="text-end">${{ $record->buys ? number_format($record->buys, 2) : number_format($record->buys, 2) }}</td>
                          <td scope="row" class="text-end">${{ $record->sells ? number_format($record->sells, 2) : number_format($record->sells, 2) }}</td>
                          <td scope="row" class="text-end">${{ number_format($record->price, 2) }}</td>
                          {{-- <td scope="row">{{ $record->quantity }}</td> --}}
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
