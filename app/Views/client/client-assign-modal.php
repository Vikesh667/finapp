 <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">Assign to User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="assignUserForm" action="<?= base_url('admin/client/assign_user') ?>" method="post">
                        <input type="hidden" name="client_id" id="clientIdField">

                        <div class="form-group mb-3">
                            <label class="form-label">Select Users</label>
                           <div id="userCheckboxList" class="border rounded p-3" style="max-height:250px; overflow-y:auto;"></div>

                            <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple users.</small>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Assignment</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>