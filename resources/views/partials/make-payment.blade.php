<div class="modal ts-modal" id="payment-modal" tabindex="-1" area-labelledby="payment-modal">
   <div class="modal-dialog modal-dialog-centered payment-modal-dialog">
      <div class="modal-content modalContent">
         <div class="modal-header modalHeader">
            <div class="row w-100 gx-1">
               <div class="col-md-8 plan-text text-start">
                  <h4>Payment Information</h4>
               </div>
            </div>
         </div>
         <div class="modal-body modalBody">
            <form method="post" id="payment-form">
               <div class="row gx-2 gy-3">
                  <div class="col-sm-12">
                     <label for="card-holder-name" class="form-label mb-1">*Card Holder Name</label>
                     <input type="text" id="card-holder-name" class="discount-input w-100" name="card_holder_name"
                        placeholder="Card Holder Name">
                  </div>
                  <div class="col-sm-12">
                     <label for="card-number" class="form-label mb-1">*Card Number</label>
                     <input type="text" id="card-number" class="discount-input w-100" name="card_number"
                        placeholder="5555 5555 5555 5555">
                  </div>
               </div>

               <div class="row gx-1 mt-3">
                  <div class="col-sm-9">
                     <label for="expiry-date" class="form-label mb-1">*Expiry Date</label>
                     <input type="text" id="expiry-date" class="discount-input w-100" name="expiry_date"
                        placeholder="MM/YYYY">
                  </div>
                  <div class="col-sm-3">
                     <label for="card-holder-name" class="form-label mb-1">*CVC</label>
                     <input type="text" id="card-holder-name" class="discount-input w-100" name="cvc"
                        placeholder="CVC">
                  </div>
               </div>

               <div class="row mt-3">
                  <div class="col-sm-12">
                     <p>Your card will be charged immediately. All features included in your plan will be enabled.</p>
                  </div>
                  <div class="col-sm-12 registerCheckbox">
                     <label for="isAgreed">
                        <input type="checkbox" name="isAgreed" id="isAgreed">
                        I Accept Trading School <a href="" class="text-primary">Teams of Use</a> and <a
                           class="text-primary">Refund Policy</a>.
                     </label>
                     <label for="isSubscribed">
                        <input type="checkbox" name="isSubscribed" id="isSubscribed">
                        Save the Credit Card Information for Future Transcitions
                     </label>
                  </div>
               </div>
               <div class="row gx-1 mt-3">
                  <div class="col-sm-12">
                     <button type="button" id="proccedToPay" class="payment-btn" data-bs-dismiss="modal"
                        aria-label="Close">Make Payment</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
