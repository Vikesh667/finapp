 <div class="modal fade action-sheet" id="addUserModel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="action-sheet-content">
            <?php $errors = session()->getFlashdata('error'); ?>
            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('admin/user/add') ?>" id="addUserForm">
              <?= csrf_field() ?>

              <!-- Name -->
              <div class="form-group basic mb-3">
                <label class="label">Full Name</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="name" value="<?= old('name') ?>" placeholder="Enter full name" required>
                </div>
                <?php if (isset($errors['name'])): ?>
                  <span class="text-danger"><?= esc($errors['name']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Email -->
              <div class="form-group basic mb-3">
                <label class="label">Email Address</label>
                <div class="input-group">
                  <input type="email" class="form-control" name="email" value="<?= old('email') ?>" placeholder="Enter email address" required>
                </div>
                <?php if (isset($errors['email'])): ?>
                  <span class="text-danger"><?= esc($errors['email']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Phone -->
              <div class="form-group basic mb-3">
                <label class="label">Mobile Number</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="phone" value="<?= old('phone') ?>" placeholder="Enter mobile number" required>
                </div>
                <?php if (isset($errors['phone'])): ?>
                  <span class="text-danger"><?= esc($errors['phone']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Address -->
              <div class="form-group basic mb-3">
                <label class="label">Address</label>
                <div class="input-group">
                  <textarea class="form-control" name="address" placeholder="House no., street, etc." rows="2" required><?= old('address') ?></textarea>
                </div>
                <?php if (isset($errors['address'])): ?>
                  <span class="text-danger"><?= esc($errors['address']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Country -->

              <div class="form-group basic mb-3">
                <label class="label">Country</label>
                <div class="input-group">
                  <select name="country" id="countrySelect" class="form-control" required>
                    <option value="">Select Country</option>
                  </select>

                </div>
                <?php if (isset($errors['country'])): ?>
                  <span class="text-danger"><?= esc($errors['country']) ?></span>
                <?php endif; ?>
              </div>

              <!-- State -->
              <div class="form-group basic mb-3">
                <label class="label">State</label>
                <div class="input-group">
                  <select name="state" id="stateSelect" class="form-control" required>
                    <option value="">Select State</option>
                  </select>
                </div>
                <?php if (isset($errors['state'])): ?>
                  <span class="text-danger"><?= esc($errors['state']) ?></span>
                <?php endif; ?>
              </div>

              <!-- City -->
              <div class="form-group basic mb-3">
                <label class="label">City</label>
                <div class="input-group">
                  <select name="city" id="citySelect" class="form-control" required>
                    <option value="">Select City</option>
                  </select>
                </div>
                <?php if (isset($errors['city'])): ?>
                  <span class="text-danger"><?= esc($errors['city']) ?></span>
                <?php endif; ?>
              </div> 
              <!-- Password -->
              <div class="form-group basic mb-3">
                <label class="label">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                  <span class="text-danger"><?= esc($errors['password']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Buttons -->
              <div class="form-group basic mt-4">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                  <ion-icon name="person-add-outline"></ion-icon> Add User
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>