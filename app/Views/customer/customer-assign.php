 <div class="modal fade" id="reassignCustomerModal" tabindex="-1" role="dialog" aria-labelledby="reassignModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <form id="reassignForm" onsubmit="event.preventDefault();">

                 <input type="hidden" name="customer_id" id="modalCustomerId">

                 <div class="modal-header bg-primary text-white">
                     <h5 class="modal-title" id="reassignModalLabel">Reassign Customer</h5>
                     <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                 </div>

                 <div class="modal-body">
                     <div class="mb-3">
                         <label class="form-label">Customer Name</label>
                         <input type="text" id="modalCustomerName" class="form-control" readonly>
                     </div>

                     <div class="mb-3">
                         <label class="form-label">Select New User (Same Client)</label>
                         <select name="new_user_id" id="modalUserSelect" class="form-control" required>
                             <option value="">-- Select User --</option>
                         </select>
                     </div>
                 </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="button" class="btn btn-primary" onclick="reassignCustomer()">Reassign</button>

                 </div>
             </form>
         </div>
     </div>
 </div>

 <div class="modal fade" id="bulkReassignModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog-centered">
         <div class="modal-content" style="border-radius: 14px;">

             <!-- Header -->
             <div class="modal-header bg-primary text-white" style="border-radius: 14px 14px 0 0;">
                 <h5 class="modal-title d-flex align-items-center gap-2">
                     <ion-icon name="swap-horizontal-outline" style="font-size:22px;"></ion-icon>
                     Bulk Reassign Customers
                 </h5>
                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
             </div>

             <!-- Body -->
             <div class="modal-body">
                 <form action="<?= base_url('admin/bulk-reassign-customers') ?>" method="post" class="row g-3">

                     <!-- Select Client -->
                     <div class="col-md-12">
                         <label class="form-label fw-semibold">Select Client</label>
                         <select name="client_id" class="form-select" required>
                             <option value="">Select Client</option>
                             <?php foreach ($clients as $c): ?>
                                 <option value="<?= $c['id'] ?>"><?= $c['company_name'] ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>

                     <!-- From User -->
                     <div class="col-md-6">
                         <label class="form-label fw-semibold">From User</label>
                         <select name="from_user_id" class="form-select" required>
                             <option value="">From User</option>
                             <?php foreach ($users as $u): ?>
                                 <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>

                     <!-- To User -->
                     <div class="col-md-6">
                         <label class="form-label fw-semibold">To User</label>
                         <select name="to_user_id" class="form-select" required>
                             <option value="">To User</option>
                             <?php foreach ($users as $u): ?>
                                 <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>

                     <!-- Submit -->
                     <div class="col-12 d-flex justify-content-end mt-3">
                         <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                             <ion-icon name="swap-horizontal-outline" style="font-size:18px;"></ion-icon>
                             Reassign All Customers
                         </button>
                     </div>

                 </form>
             </div>

         </div>
     </div>
 </div>