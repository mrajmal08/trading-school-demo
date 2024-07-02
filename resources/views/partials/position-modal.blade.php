<div class="modal fade ts-modal modal-xl" id="position-modal-{{ $risk->id }}" tabindex="-1"
   area-labelledby="position-modal">
   <div class="modal-dialog modal-dialog-centered position-modal-dialog">
      <div class="modal-content modalContent">
         <div class="modal-header modalHeader">
            <h4 class="plan-text text-start">Live Positions</h4>
         </div>
         <div class="modal-body modalBody" id="live_positions">
            <div class="table-responsive">
               <table class="table">
                  <thead>
                     <tr>
                        <th>Symbol</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Avg Price</th>
                        <th class="text-end">Realized P&L</th>
                        <th class="text-end">Open P&L</th>
                        <th class="text-end">Total P&L</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if ($risk->tradePosition !== null)
                        @forelse($risk->tradePosition as $position)
                           <tr>
                              <td>{{ $position?->symbol }}</td>
                              <td class="text-end">{{ $position?->quantity }}</td>
                              <td class="text-end">${{ number_format($position?->avgPrice, 2) }}</td>
                              <td class="text-end">${{ number_format($position?->realizedPl, 2) }}</td>
                              <td class="text-end">${{ number_format($position?->openPl, 2) }}</td>
                              <td class="text-end">${{ number_format($position?->totalPl, 2) }}</td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="6" align="center">No Found</td>
                           </tr>
                        @endforelse
                     @else
                        <tr>
                           <td colspan="6" align="center">No Found</td>
                        </tr>
                     @endif

                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
