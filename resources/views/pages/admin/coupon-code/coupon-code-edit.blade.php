@extends('layout.admin.app')

@section('admin.app-content')
   <div>
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end"></div>
      </div>
      <form method="post" action="{{ route('coupon-code.update', $couponCode->uuid) }}" enctype='multipart/form-data'>
         @csrf
         <div class="row gy-3 mb-24">
            <div class="col-sm-6">
               <label for="coupon-code" class="ts-label"><span class="text-danger required">*</span>
                  <span>Promotion Name</span>
               </label>
               <input class="form-control input-control" type="text" name="promotion_name"
                  value="{{ old('promotion_name', $couponCode->promotion_name) }}">
               @if ($errors->has('promotion_name'))
                  <div class="error">{{ $errors->first('promotion_name') }}</div>
               @endif
            </div>
            <div class="col-sm-6">
               <label for="coupon-code" class="ts-label"><span class="text-danger required">*</span>
                  <span>Coupon Code</span>
               </label>
               <input class="form-control input-control" type="text" name="coupon_code"
                  value="{{ $couponCode->coupon_code }}">
               @if ($errors->has('coupon_code'))
                  <div class="error">{{ $errors->first('coupon_code') }}</div>
               @endif
            </div>
            <div class="col-sm-6">
               <label for="status" class="ts-label"><span class="text-danger required">*</span>
                  <span>Coupon Amount Policy</span>
               </label>
               <select name="coupon_amount_policy" class="form-control input-control">
                  <option <?= $couponCode->coupon_amount_policy == 'percent_off' ? 'selected' : '' ?> value="percent_off">
                     Percent off</option>
                  <option <?= $couponCode->coupon_amount_policy == 'amount_off' ? 'selected' : '' ?> value="amount_off">
                     Amount off</option>
               </select>
            </div>
            <div class="col-sm-6">
               <label for="amount" class="ts-label"><span class="text-danger required">*</span>
                  <span>Amount</span>
               </label>
               <input readonly class="form-control input-control" type="text" name="amount"
                  value="{{ $couponCode->amount }}">
               @if ($errors->has('amount'))
                  <div class="error">{{ $errors->first('amount') }}</div>
               @endif
            </div>
         </div>

         <div class="row gy-3 mb-24">
            <div class="col-sm-6">
               <label for="status" class="ts-label"><span class="text-danger required">*</span>
                  <span>Select Currency</span>
               </label>
               <select name="currency" class="form-control input-control">
                  <option <?= $couponCode->currency == 'USD' ? 'selected' : '' ?> value="USD">USD</option>
               </select>
               @if ($errors->has('currency'))
                  <div class="error">{{ $errors->first('currency') }}</div>
               @endif
            </div>
            <div class="col-sm-6">
               <label for="date-range" class="ts-label"><span class="text-danger required">*</span>
                  <span>Date</span>
               </label>
               <input readonly class="form-control input-control" type="datetime-local" name="date_range"
                  value="{{ $couponCode->date_range }}">
               @if ($errors->has('date_range'))
                  <div class="error">{{ $errors->first('date_range') }}</div>
               @endif
            </div>
            <div class="col-sm-6">
               <label for="once" class="ts-label"><span class="text-danger required">*</span>
                  <span>Duration</span>
               </label>
               <select name="max_use" class="form-control input-control max_use">
                  <option <?= $couponCode->max_use == 'forever' ? 'selected' : '' ?> value="forever">Unlimited</option>
                  <option <?= $couponCode->max_use == 'once' ? 'selected' : '' ?> value="once">Once</option>
               </select>
               @if ($errors->has('max_use'))
                  <div class="error">{{ $errors->first('max_use') }}</div>
               @endif
            </div>
            <div class="col-sm-6 coupon_count_row">
               <label for="once" class="ts-label"><span class="text-danger required">*</span>
                  <span>Coupon Count</span>
               </label>
               <input type="number" name="total_number" class="form-control input-control"
                  value="{{ old('total_number', $couponCode->total_number) }}">
               @if ($errors->has('total_number'))
                  <div class="error">{{ $errors->first('total_number') }}</div>
               @endif
            </div>
         </div>
         <div class="row gy-3 justify-content-center mb-24">
            <div class="col-sm-6">
               <input class="btn-teacher-submit btn-submit w-100" type="submit" value="Submit">
            </div>
         </div>
      </form>
   </div>
   <style>
      .error {
         color: #a94442;
      }
   </style>
@endsection

@push('coupon-script')
   <script>
      $(document).ready(function() {
         $(".max_use").on("change", function(e) {
            if (e.target.value === 'forever') {
               $('.coupon_count_row').addClass('d-block').removeClass('d-none');
            } else {
               $('.coupon_count_row').removeClass('d-block').addClass('d-none');
            }
         })
      })
   </script>
@endpush
