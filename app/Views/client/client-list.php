<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="assets/img/logo.png" alt="logo" class="logo">
        </div>
        <div class="right">
            <?php
            $session = session();
            $role = $session->get('role');
            $userName = $session->get('user_name');
            $profileImage = $session->get('profile_image'); // optional field from DB
            $firstLetter = strtoupper(substr($userName, 0, 1));
            ?>

            <?php if ($role === 'admin'): ?>
                <!-- Admin: Notifications + Avatar -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>

                <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width:32px; height:32px; object-fit:cover; object-position:center;">
                    <?php else: ?>
                        <div class="avatar-fallback bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; font-weight: 600;">
                            <?= esc($firstLetter) ?>
                        </div>
                    <?php endif; ?>
                </a>

            <?php elseif ($role === 'user'): ?>
                <!-- User: Only Avatar/Profile -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>
                <a href="<?= base_url('user/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos/' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width: 32px; height: 32px; object-fit:cover; object-position:center;">
                    <?php else: ?>
                        <div class="avatar-fallback bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; font-weight: 600;">
                            <?= esc($firstLetter) ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>


    </div>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list">
                <div class="user-list-header">
                    <h5>Product List</h5>
                    <div class='select-client'>
                        <select name="user_id" id="userSelect" required style="width: 300px; padding: 8px; border-radius: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <option value=""> Filter Product By User </option>
                            <?php foreach ($user as $u): ?>
                                <?php if ($u['role'] === 'user'): ?>
                                    <option value="<?= esc($u['id']) ?>">
                                        <?= esc($u['name']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="right-section">
                        <div class="add">
                            <?php if ($role === 'admin'): ?>
                                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                    <ion-icon name="add-outline"></ion-icon>
                                    <span>Add Product</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <?php if (session()->get('role') === 'admin'): ?>
                                    <th>CreatedBy</th>
                                <?php endif; ?>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Url</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clients)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($clients as $index => $client):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <?php if (session()->get('role') === 'admin'): ?>
                                            <td><?= esc($client['created_by_name']) ?></td>
                                        <?php endif; ?>

                                        <td><?= esc($client['name']) ?></td>
                                        <td><?= esc($client['email']) ?></td>
                                        <td><?= esc($client['company_name']) ?></td>

                                        <td><?= esc($client['url']) ?></td>
                                        <td>
                                            <img src="<?= base_url('assets/uploads/logos/' . $client['logo']) ?>" alt="Logo" width="50">
                                        </td>

                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $editUrl = ($role === 'admin')
                                                ? base_url('admin/client/edit-client/' . $client['id'])
                                                : base_url('user/client/edit-client/' . $client['id']);
                                            $deleteUrl = ($role === 'admin')
                                                ? base_url('admin/client/delete/' . $client['id'])
                                                : base_url('user/client/delete/' . $client['id']);
                                            ?>
                                            <a href="<?= $editUrl ?>" class="btn-icon edit" title="Edit Client">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>

                                            <!-- Delete Button -->
                                            <form method="post" action="<?= $deleteUrl ?>" style="display:inline;">
                                                <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userModal"
                                                data-client-id="<?= esc($client['id']) ?>">
                                                <i class="bi bi-person-plus"></i> Assign User
                                            </button>

                                              <a href="<?= base_url('admin/product/'.$client['id']) ?>" class="btn bg-info">view</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($pager && $pager->getPageCount('clients') > 1): ?>
                    <nav aria-label="Custom pagination" class="mt-3">
                        <ul class="pagination justify-content-center">

                            <!-- Previous Button -->
                            <?php if ($pager->getCurrentPage('clients') > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $pager->getPreviousPageURI('clients') ?>">Previous</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            <?php endif; ?>


                            <!-- Page Numbers -->
                            <?php for ($page = 1; $page <= $pager->getPageCount('clients'); $page++): ?>
                                <?php if ($page == $pager->getCurrentPage('clients')): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?= $page ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $pager->getPageURI($page, 'clients') ?>"><?= $page ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>


                            <!-- Next Button -->
                            <?php if ($pager->getCurrentPage('clients') < $pager->getPageCount('clients')): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $pager->getNextPageURI('clients') ?>">Next</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
                                    <ion-icon name="business-outline"></ion-icon> Add Client
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



    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>