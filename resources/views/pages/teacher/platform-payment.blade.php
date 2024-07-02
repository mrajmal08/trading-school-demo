@extends('layout.app')

@section('app-content')
   <div class="payment-page">

      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Revenue Management</h3>
            <div class="filter-wrapper">
               <form action="{{ route('teacher.platform.payment.index') }}" class="filter-form">
                  <div class="row justify-content-end">
                     <div class="col-sm-2">
                        <input type="text" class="input-control" name="dates" id="date-range"
                           placeholder="Choose Duration" />
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
                     <th scope="col">Payment Month</th>
                     <th scope="col" class="text-end">Total Revenue</th>
                     <th scope="col" class="text-end">Market Data Fees</th>
                     <th scope="col" class="text-end">Payment Processing Fees</th>
                     <th scope="col" class="text-end">Misc Fee</th>
                     <th scope="col" class="text-end">Net Income</th>
                     <th scope="col" class="text-end">Licensing Fee</th>
                     <th scope="col" class="text-end">School Payout</th>
                     <th scope="col" class="text-end">Total User</th>
                     <th scope="col" class="text-end">Total Purchase</th>
                     <th scope="col">Status</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($platfromPayment as $kye => $payment )
                  <tr>
                     <td>{{  $payment->created_at->format('F Y')}}</td>
                     <td class="text-end">${{ number_format($payment->challenges_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->market_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->payment_getaway_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->other_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->total_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->system_cut_amount, 2) }}</td>
                     <td class="text-end">${{ number_format($payment->your_cut_amount, 2) }}</td>

                     <td class="text-end">{{ $payment->total_user }}</td>
                     <td class="text-end">{{ $payment->total_purchase }}</td>
                     <td>
                        @if ($payment->status == 1)
                        <span class="badge text-bg-success">Success</span>
                        @endif
                        @if ($payment->status == 0)
                        <span class="badge text-bg-warning">Pending</span>
                        @endif
                     </td>
                     <td>
                        <div class="action-cell">
                        @if ($payment->status == 1)
                        @endif
                        @if (($payment->status == 0) && ((int)date('n') > (int)$payment->created_at->format('n')))
                           <a href="{{ route('teacher.platform.recievePlatformPayment',$payment->uuid) }}">
                              <button class="raw-btn payment-details">
                                 Receive Now
                              </button>
                           </a>
                        @endif
                        </div>
                     </td>
                  </tr>
                  @empty
                     <tr>
                        <td colspan="10" scope="row" class="text-center">Data not found</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>

         <div class="tr-pagination">
            {!! $platfromPayment->links() !!}
         </div>
      </div>
   </div>

@endsection

@push('payment-script')
   <script>
      $('#discount-form').on("submit", function(e) {
         e.preventDefault();
         let discount = $("#discount-code").val().trim();
         if (discount) {
            $("#proccedToPay").removeAttr("disabled");
         }
      });

      $("#proccedToPay").on("click", function(e) {
         $("#discount-code").val("")
         alert("Hello I am porcced to payment");
      });
   </script>
@endpush
