@extends('layout.admin.app')

@section('admin.app-content')
   <div class="payment-page">
      <div class="row mb-4">
         <div class="col-md-8">
            <h4>Platform Fees</h4>
         </div>
         <div class="col-md-4">
            <div class="card card-box">
               <div class="card-body">
                  <ul class="payment-summary">
                     {{-- {{ dd($currentMonthPay) }} --}}
                     <li>
                        @if ($currentMonthPay?->status == 1)
                        <span>Status: </span><span class="badge text-bg-success">
                           success
                        </span>
                        @else
                        <span>Status: </span><span class="badge text-bg-warning">
                        Pending
                        </span>
                        @endif
                     </li>
                     <li>
                        <span>Price: </span><span class="price">$500</span></li>
                     <li><span>Due Date: </span><span>5th January 2023</span></li>
                  </ul>


                  @if ($currentMonthPay?->status == 1)
                     <button class="raw-btn pay-now-lg" data-bs-toggle="modal" disabled data-bs-target="#discount-modal">
                        Pay now
                     </button>
                  @else
                     <button class="raw-btn pay-now-lg" data-bs-toggle="modal" data-bs-target="#discount-modal">
                        Pay now
                     </button>
                  @endif


               </div>
            </div>
         </div>
      </div>

      <div class="card card-box history-table">
         <div class="cardbox-header">
            <h3 class="cardbox-title">Platform Fees</h3>
            <div class="filter-wrapper">
               <form action="{{ route('admin.payment') }}" class="filter-form">
                  <div class="row justify-content-end">
                     {{-- <div class="col-sm-2">
                        <input type="search" class="input-control" name="search" placeholder="Search...">
                     </div> --}}
                     <div class="col-sm-2">
                        <input type="text" class="input-control" name="dates" id="date-range" placeholder="Choose Duration" autocomplete="off"/>
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
                     <th scope="col">Payment Date</th>
                     <th scope="col" class="text-end">Payment Amount</th>
                     <th scope="col">Payment Method</th>
                     <th scope="col">Status</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($payments as $kye => $payment )
                  <tr>
                     <td>{{ $payment->created_at->format('F, Y') }}</td>
                     <td>{{ $payment->pay_date }}</td>
                     <td class="text-end">{{ $payment->amount }}</td>
                     <td>Stripe</td>
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
                           @else
                           <button class="raw-btn pay-now" data-bs-toggle="modal" data-bs-target="#monthlyPayment{{ $payment->uuid }}">
                              Pay now
                           </button>
                           @endif
                           <a href="">
                              <button class="raw-btn payment-details">
                                 Payment Details
                              </button>
                           </a>
                        </div>
                     </td>
                  </tr>
                  {{--  @include('partials.payment-discount')  --}}
                  @include('partials.monthlyPayment')
                  @empty

                  @endforelse

               </tbody>
            </table>
         </div>

         <div class="tr-pagination">
            {!! $payments->links() !!}
         </div>
      </div>
   </div>
   @include('partials.current-month-payment')
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
