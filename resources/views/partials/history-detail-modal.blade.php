<div class="modal" id="details-modal-{{ $user["id"] }}" tabindex="-1" area-labelledby="details-modal">
   <div class="modal-dialog modal-dialog-centered detail-modal-dialog">
      <div class="modal-content modalContent">
         <div class="modal-header modalHeader">
            <h3>{{ $user['account'] }}</h3>
            <button type="button" class="btn-close-modal text-white" data-bs-dismiss="modal" aria-label="Close">
               <img src="{{ asset('assets/images/cross.png') }}"
                  alt="close the details modal image" />
            </button>
         </div>
         <div class="modal-body modalBody">
            <form action="">
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Select
                           account</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="Account One">Account One</option>
                        <option value="Account Two">Account Two</option>
                        <option value="Account Three">Account Three</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Select
                           account</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="Account One">Account One</option>
                        <option value="Account Two">Account Two</option>
                        <option value="Account Three">Account Three</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 d-flex align-items-end">
                     <label for="readOnly" class="ts-checkbox">
                        <input class="" id="readOnly" type="checkbox" name="readOnly" />
                        Read Only
                     </label>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Organization</span>
                     <h4>SpeedUP</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Clearing
                           House</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="DOR">DOR</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Risk
                           Catagory</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="Abx Company">Abx Company</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Risk
                           Catagory</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select Value</option>
                        <option value="Category 1">Category 1</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 d-flex align-items-end">
                     <label for="approve" class="ts-checkbox">
                        <input class="" id="approve" type="checkbox" name="approve" />
                        Approve for ACH
                     </label>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Account
                           Type</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="Customer">Customer</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Margine
                           Account
                           Type</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="Speculator">Speculator</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Legal
                           Status</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option value="">Individual</option>
                     </select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <label for="account" class="ts-label"><span
                           class="text-danger required">*</span><span>Archived</span></label>
                     <select class="form-select select-control" required name="account" id="account">
                        <option value="">Select</option>
                        <option>No</option>
                     </select>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Customer
                           Sales
                           Code</span></label>
                     <input class="form-control input-control" name="accountType" placeholder="Customer" required>
                  </div>
                  <div class="col-sm-6">
                     <label for="account" class="ts-label"><span class="text-danger required">*</span><span>Customer
                           Sales
                           Code</span></label>
                     <input class="form-control input-control" name="accountType" placeholder="Customer" required>
                  </div>
               </div>
               <div class="divider"></div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Cash Amount</span>
                     <h4>$49.748,78</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dollar Open P’L</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dollar Total P’L</span>
                     <h4>($275.25)</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Net LiQ</span>
                     <h4>$49,748.78</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Initial Margin</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Maintenance Margin</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Margin Equity</span>
                     <h4>0.00%</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Day Margin</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Total Used Margin</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Total available Margin</span>
                     <h4>$49,748.78</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Exposed POS</span>
                     <h4>0</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>LiQ only Level</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dist to LiQ only</span>
                     <h4>$49,748.78</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Auto LiQ Level</span>
                     <h4>$0.00</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dist to Auto LiQ</span>
                     <h4>$49,748.78</h4>
                  </div>
               </div>
               <div class="divider"></div>
               <div class="row gy-3 gy-3">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Admin Action</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Admin Action Reason Code</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Auto LiQ Stopped</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Dist to Auto LiQ</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Liquidate Only</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Auto LiQ Started</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <p>Auto LiQ Counter</p>
                  </div>
               </div>
               <div class="divider"></div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>User</span>
                     <h4>SUTAPI</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Email</span>
                     <h4>sdci@trae.com</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Full Name</span>
                     <h4>SpeedUP Trader</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>User Status</span>
                     <h4>Active</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 d-flex align-items-end">
                     <label for="professional" class="ts-checkbox">
                        <input class="" id="professional" type="checkbox" name="professional" />
                        Professional
                     </label>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Auto Renewal</span>
                     <h4>Trial</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Membership</span>
                     <h4>SUT_TRIAL</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Membership Expiration</span>
                     <h4>12/31/2022</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Account Status</span>
                     <h4>Open</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Daily Profit Limit</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Weekly Profit Limit</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Daily Loss Limit</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dist to Daily Loss Limit</span>
                     <h4>$2,724.75</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Drawdown Auto LiQ</span>
                     <h4>$2,724.75</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>% To Daily Loss Limit</span>
                     <h4>9.18%</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Week Loss</span>
                     <h4>$275.75</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Weekly Loss Limit</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Drawdown LiQ Level</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>% To Weekly Loss Limit</span>
                     <h4>0.00%</h4>
                  </div>
               </div>
               <div class="row gy-3 mb-24">
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Trading Max Drawdown</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Max Net LiQ</span>
                     <h4>$ - -</h4>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3 form-model-content">
                     <span>Dist Drawdown Net LiQ</span>
                     <h4>$ - -</h4>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer modalFooter">
            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal"
               aria-label="Close">Cancel</button>
            <button class="btn-save-modal ml-3">Save</button>
         </div>
      </div>
   </div>
</div>
