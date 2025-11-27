<div class="modal fade action-sheet" id="addCustomerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="action-sheet-content">
                        <?php
                        $role = session()->get('role');
                        $userId = session()->get('user_id');

                        $actionUrl = ($role === 'admin')
                            ? base_url('admin/customer/add')
                            : base_url('user/customer/add');
                        ?>

                        <form action="<?= $actionUrl ?>" method="post" id="addCustomerForm">

                            <!-- ADMIN ROLE -->
                            <?php if ($role === 'admin'): ?>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select User</label>
                                    <div class="input-group">
                                        <select name="user_id" id="userSelect_customer" class="form-control" required>
                                            <option value="">Loading users...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_customer" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!--  USER ROLE -->
                                <!-- Hidden input for logged-in user -->
                                <input type="hidden" name="user_id" value="<?= $userId ?>">

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_customer" class="form-control" required>
                                            <option value="">Loading clients...</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- ✅ COMMON FIELDS -->
                            <div class="form-group basic mb-3">
                                <label class="label">Client Name</label>
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control" placeholder="Enter client name" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Email</label>
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Phone</label>
                                <div class="input-group">
                                    <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Shop Name</label>
                                <div class="input-group">
                                    <input type="text" name="shop_name" class="form-control" placeholder="Enter shop name" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Services</label>
                                <div class="input-group">
                                    <select name="device_type" id="" class="form-control  ccc" required>
                                        <option value="">--Select Device--</option>
                                        <?php foreach ($services as $service): ?>
                                            <option><?= esc($service['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Country</label>
                                <div class="input-group">
                                    <select name="country" id="countrySelect" class="form-control  ccc" required>
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">State</label>
                                <div class="input-group">
                                    <select name="state" id="stateSelect" class="form-control" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">City</label>
                                <div class="input-group">
                                    <select name="city" id="citySelect" class="form-control" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Address</label>
                                <div class="input-group">
                                    <textarea name="address" class="form-control" placeholder="Enter address" required></textarea>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">GST Number</label>
                                <div class="input-group">
                                    <input name="gst_number" class="form-control"
                                        placeholder="27AAPFU0939F1ZV">

                                </div>
                            </div>
                            <div class="form-group basic mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <ion-icon name="person-add-outline"></ion-icon> Add Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>