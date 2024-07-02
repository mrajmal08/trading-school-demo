<div class="modal ts-modal" id="user-modify-modal" tabindex="-1" area-labelledby="discount-modal">
   <div class="modal-dialog modal-dialog-centered discount-modal-dialog">
      <div class="modal-content modalContent">
         <div class="modal-header modalHeader">
            <div class="row w-100 gx-1">
               <div class="col-md-8 plan-text text-start">
                  <h4>Account: <span></span></h4>
               </div>
               <div class="col-md-4 text-end">
                  <h6 class="plan-price-sm" id="plan-price"></h6>
               </div>
            </div>
         </div>
         <div class="modal-body modalBody">
            <form method="post" id="amount-form" action="{{ route('teacher.account.balance.update') }}">
                @csrf()
               <div class="row gx-1">
                  <label for="amount" class="form-label">Amount</label>
                  <div class="col-sm-12">
                     <input type="text" id="amount" class="discount-input w-100" name="amount"
                        placeholder="amount" min="1">
                    <input type="hidden" id="addWithdraw" class="addwithdraw-input w-100" name="addwithdraw">
                    <input type="hidden" id="userid" class="addwithdraw-input w-100" name="userid" value="{{$allUser['id']}}">
                    <input type="hidden" id="currentbalance" class="addwithdraw-input w-100" name="currentbalance">
                  </div>
               </div>
                <div class="invoice-details-wrapper mt-4">
                </div>
                <div class=" modalFooter mt-4">
                     <div class="row text-center">
                        <div class="col-md-12">
                            <button id="proccedToPay" class="add payment-btn">Add</button>
                        </div>
                    </div>
                </div>
            </form>
         </div>
      </div>
   </div>
</div>
