<div class="modal ts-modal" id="user-reset-modal{{ $user->uuid }}" tabindex="-1" area-labelledby="discount-modal">
   <div class="modal-dialog modal-dialog-centered discount-modal-dialog">
      <div class="modal-content modalContent">
         <!-- <div class="modal-header modalHeader">
            <div class="row w-100 gx-1">
               <div class="col-md-8 plan-text text-start">
                  <h4>Account: <span></span></h4>
               </div>
               <div class="col-md-4 text-end">
                  <h3 class="plan-price-lg"></h3>
               </div>
            </div>
         </div> -->
         <div class="modal-body modalBody">
            <form method="post" id="discount-form" action="{{ route('account.accountReset') }}">
               @csrf()
               <div class="row gx-1">
                  <h4>Resetting the user challenge will clear all data</h4>
               </div>

               <input type="hidden" name="userid" value="{{ $user->uuid }}">

               <div class="modalFooter mt-4">
                  <div class="row">
                     <div class="col-md-6">
                        <button type="submit" id="proccedToPay" class="payment-btn">Yes</button>
                     </div>
                     <div class="col-md-6">
                        <button type="button" class="payment-btn modal_close" data-bs-dismiss="modal"
                           style="background-color: #0B5ED7;">No</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
