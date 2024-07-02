<div class="modal ts-modal" id="discount-modal" tabindex="-1" area-labelledby="discount-modal">
   <div class="modal-dialog modal-dialog-centered discount-modal-dialog">
      <div class="modal-content modalContent">
         <div class="modal-header modalHeader">
            <div class="row w-100 gx-1">
               <div class="col-md-8 plan-text text-start">
                  <h4>Monthly Payment</h4>
               </div>
               <div class="col-md-4 text-end">
                  <h3 class="plan-price-lg">$500</h3>
               </div>
            </div>
         </div>
         <div class="modal-body modalBody">
            <form method="post" id="discount-form">
               <div class="row gx-1">
                  <label for="discount" class="form-label">Discount Code</label>
                  <div class="col-sm-10">
                     <input type="text" id="discount-code" class="discount-input w-100" name="discount-code"
                        placeholder="Discount Code">
                  </div>
                  <div class="col-sm-2">
                     <button type="submit" class="payment-btn apply-discount-btn">Apply</button>
                  </div>
               </div>
            </form>
            <div class="invoice-details-wrapper mt-4">
               <h5>Invoice Details</h5>
               <ul class="invoice-details-list">
                  <li><span>Subtotal</span><span>$10</span></li>
                  <li><span>Discount</span><span> -$0</span></li>
                  <li><span>Tax</span><span>$1</span></li>
                  <li class="grand-total"><span>Grand Total </span><span>$11</span></li>
               </ul>
            </div>
         </div>
         <div class="modal-footer modalFooter">
            <button disabled type="button" id="proccedToPay" class="payment-btn disabled" data-bs-toggle="modal" data-bs-target="#payment-modal">
               Procced to Payment
               <i class="bi bi-arrow-right px-3"></i>
            </button>
         </div>
      </div>
   </div>
</div>
