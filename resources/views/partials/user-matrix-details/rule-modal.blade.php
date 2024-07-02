<div class="modal ts-modal" id="packageLive{{ $userDetail?->uuid }}" tabindex="-1" area-labelledby="discount-modal">
   <div class="modal-dialog modal-dialog-centered discount-modal-dialog model-xl">
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

            <div class="row gx-1">
               <h4 class="text-center">
                  Are you sure you wish to approve this trader for live trading? This will start the trader with a new
                  sim account and you will be responsible for ensuring sim trades are mirrored appropriately into the
                  live trading environment
               </h4>
            </div>

            <div class="modalFooter mt-4">
               <div class="row">




                  <div class="col-md-6">
                     {{--  <button type="submit" id="proccedToPay"  class="payment-btn">Yes</button>  --}}

                     <a href="{{ route('account.activeLiveAccount', $userDetail['uuid']) }}">
                        <button class="payment-btn">
                           Live
                        </button>
                     </a>
                  </div>
                  <div class="col-md-6">
                     <button type="button" class="payment-btn modal_close" data-bs-dismiss="modal"
                        style="background-color: #0B5ED7;">No</button>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
