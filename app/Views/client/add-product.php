 <?php 
 $role=session()->get('role');
 ?>
<div class="modal fade action-sheet" id="addClientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="action-sheet-content">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger" style="padding:10px;margin-bottom:15px;border-radius:6px;">
                                <?= session('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success" style="padding:10px;margin-bottom:15px;border-radius:6px;">
                                <?= session('success') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/client/add') ?>" method="post" enctype="multipart/form-data" id="addClientForm">
                            <!-- Common Fields -->
                            <div class="form-group basic mb-3">
                                <label class="label">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" placeholder="Enter name" required>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Company Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="company_name" placeholder="Enter company name" required>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Email Address</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email" placeholder="Enter client email" required>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Website URL</label>
                                <div class="input-group">
                                    <input type="url" class="form-control" name="url" placeholder="https://www.company.com" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Company Logo</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="logo" accept="image/*" required>
                                </div>
                            </div>

                            <div class="form-group basic mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <ion-icon name="business-outline"></ion-icon> Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!--  JS: Fetch Users for Admin -->
                <?php if ($role === 'admin'): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {

                            const userModal = document.getElementById("userModal");
                            const checkboxContainer = document.getElementById("userCheckboxList");
                            const clientIdField = document.getElementById("clientIdField");

                            userModal.addEventListener("show.bs.modal", async function(event) {
                                const button = event.relatedTarget;
                                const clientId = button.getAttribute("data-client-id");
                                clientIdField.value = clientId;

                                // Loading message
                                checkboxContainer.innerHTML = "<p>Loading users...</p>";

                                try {
                                    const response = await fetch("<?= site_url('admin/client/get-users-for-client/') ?>" + clientId);
                                    const data = await response.json();

                                    if (!data.success) {
                                        checkboxContainer.innerHTML = `<p class="text-danger">Error: ${data.error}</p>`;
                                        return;
                                    }

                                    checkboxContainer.innerHTML = ""; // clear container

                                    data.users.forEach(u => {
                                        let isChecked = data.assigned.includes(u.id) ? "checked" : "";

                                        checkboxContainer.innerHTML += `
                    <label class="d-flex align-items-center mb-2" style="gap: 8px;">
                        <input type="checkbox" name="user_id[]" value="${u.id}" ${isChecked}
                               class="form-check-input" style="cursor:pointer;">
                        <span>${u.name}</span>
                    </label>
                `;
                                    });

                                } catch (error) {
                                    console.error("Fetch error:", error);
                                    checkboxContainer.innerHTML = "<p class='text-danger'>Error loading users</p>";
                                }
                            });

                        });
                    </script>
                <?php endif; ?>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $('#userSelect').on('change', function() {
                            let userId = $(this).val();
                            let baseUrl = "<?= base_url('admin/client-list') ?>";

                            if (userId) {
                                window.location.href = baseUrl + '?user_id=' + userId;
                            } else {
                                window.location.href = baseUrl; // reload full list
                            }
                        });
                    });
                </script>


            </div>
        </div>
    </div>