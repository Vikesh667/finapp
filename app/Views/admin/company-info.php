<?= view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="<?= base_url('assets/img/logo.png'); ?>" alt="logo" class="logo">
        </div>
        <div class="right">
            <ion-icon class="icon" name="settings-outline"></ion-icon>
        </div>
    </div>

    <div id="appCapsule">

        <!-- Title -->
        <div class="section mt-3 text-center">
            <h3>Company Information</h3>
            <p class="text-muted small">Add & View all company addresses</p>
        </div>

        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success m-2 text-center">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>


        <!-- FORM -->
        <div class="section p-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/save-company-info') ?>" enctype="multipart/form-data">

                        <div class="form-group mb-2">
                            <label class="form-label">Company Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*" onchange="previewLogo(event)">
                            <img id="logoPreview" class="mt-2" src="" style="width:80px; display:none; border-radius:10px;">
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>

                        <!-- Dropdown Country -->
                        <div class="form-group mb-2">
                            <label class="form-label">Country</label>
                            <select id="countrySelect" name="country" class="form-control" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>

                        <!-- Dropdown State -->
                        <div class="form-group mb-2">
                            <label class="form-label">State</label>
                            <select id="stateSelect" name="state" class="form-control" required>
                                <option value="">Select Country First</option>
                            </select>
                        </div>

                        <!-- Dropdown City -->
                        <div class="form-group mb-3">
                            <label class="form-label">City</label>
                            <select id="citySelect" name="city" class="form-control" required>
                                <option value="">Select State First</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">GST Number</label>
                            <input type="text" class="form-control" name="gst_number" required>
                        </div>

                        <button class="btn btn-primary btn-block">
                            <ion-icon name="save-outline"></ion-icon> Save Company
                        </button>
                    </form>


                    <script>
                        function previewLogo(event) {
                            let preview = document.getElementById('logoPreview');
                            preview.src = URL.createObjectURL(event.target.files[0]);
                            preview.style.display = 'block';
                        }

                        document.addEventListener('DOMContentLoaded', function() {

                            const role = "<?= session()->get('role') ?>";
                            const userId = "<?= session()->get('user_id') ?>";
                            const countrySelect = document.getElementById('countrySelect');
                            const stateSelect = document.getElementById('stateSelect');
                            const citySelect = document.getElementById('citySelect');

                            let baseUrl = role === 'admin' ? "<?= base_url('admin') ?>" : "<?= base_url('user') ?>";

                            // Load Countries
                            fetch(baseUrl + '/get-countries')
                                .then(res => res.json())
                                .then(countries => {
                                    countrySelect.innerHTML = '<option value="">Select Country</option>';
                                    countries.forEach(c => {
                                        countrySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                                    });
                                });

                            // When Country Changes → Load States
                            countrySelect.addEventListener('change', function() {
                                const id = this.value;
                                if (!id) {
                                    stateSelect.innerHTML = '<option value="">Select Country First</option>';
                                    citySelect.innerHTML = '<option value="">Select State First</option>';
                                    return;
                                }

                                fetch(baseUrl + '/get-states/' + id)
                                    .then(res => res.json())
                                    .then(states => {
                                        stateSelect.innerHTML = '<option value="">Select State</option>';
                                        states.forEach(s => {
                                            stateSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                                        });
                                    });
                            });

                            // When State Changes → Load Cities
                            stateSelect.addEventListener('change', function() {
                                const id = this.value;
                                if (!id) {
                                    citySelect.innerHTML = '<option value="">Select State First</option>';
                                    return;
                                }

                                fetch(baseUrl + '/get-cities/' + id)
                                    .then(res => res.json())
                                    .then(cities => {
                                        citySelect.innerHTML = '<option value="">Select City</option>';
                                        cities.forEach(c => {
                                            citySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                                        });
                                    });
                            });

                        });
                    </script>

                </div>
            </div>
        </div>



        <!-- COMPANY LIST -->
        <div class="section p-2 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5>Saved Company Details</h5>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>GST No.</th>
                                <th>Logo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($companies)): ?>
                                <?php foreach ($companies as $index => $c): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($c['company_name']) ?></td>
                                        <td><?= nl2br(esc($c['address'])) ?></td>
                                        <td><?= esc($c['city']) ?></td>
                                        <td><?= esc($c['state']) ?></td>
                                        <td><?= esc($c['country']) ?></td>
                                        <td><?= esc($c['gst_number']) ?></td>
                                        <td>
                                            <?php if (!empty($c['logo'])): ?>
                                                <img src="<?= base_url('assets/uploads/company/' . $c['logo']) ?>" alt="Logo" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
                                            <?php else: ?>
                                                <span class="text-muted">No Logo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <?= view('sidebar'); ?>
    <?= view('bottomMenu'); ?>
    <?= view('footerlink'); ?>
</body>