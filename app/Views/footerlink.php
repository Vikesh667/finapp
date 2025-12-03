        <?php
        $role = session()->get('role');
        $userId = session()->get('user_id');
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // 🔹 Carousel Single → 3 cards per view
                new Splide('.carousel-single', {
                    type: 'loop',
                    perPage: 3,
                    autoplay: true,
                    interval: 3000,
                    pauseOnHover: true,
                    arrows: false,
                    pagination: true,
                    gap: '15px',
                    padding: {
                        left: '15px',
                        right: '15px'
                    },
                    breakpoints: {
                        992: {
                            perPage: 2
                        },
                        600: {
                            perPage: 1
                        },
                    },
                }).mount();


                // 🔹 Carousel Small → 10 cards per view
                new Splide('.carousel-small', {
                    type: 'loop',
                    perPage: 9,
                    perMove: 1,
                    autoplay: true,
                    gap: '1rem',
                    arrows: false,
                    pagination: false,
                    breakpoints: {
                        1200: {
                            perPage: 8
                        },
                        992: {
                            perPage: 6
                        },
                        768: {
                            perPage: 4
                        },
                        576: {
                            perPage: 4
                        },
                    },
                }).mount();


                // 🔹 Carousel Multiple → 4 cards per view (for both)
                document.querySelectorAll('.carousel-multiple').forEach(function(el) {
                    new Splide(el, {
                        type: 'loop',
                        perPage: 4,
                        autoplay: true,
                        interval: 2500,
                        pauseOnHover: true,
                        arrows: false,
                        pagination: false,
                        gap: '15px',
                        padding: {
                            left: '15px',
                            right: '15px'
                        },
                        breakpoints: {
                            992: {
                                perPage: 3
                            },
                            768: {
                                perPage: 2
                            },
                            576: {
                                perPage: 1
                            },
                        },
                    }).mount();
                });

            });
            document.addEventListener("DOMContentLoaded", function() {
                <?php if (session()->getFlashdata('error')): ?>
                    var myModal = new bootstrap.Modal(document.getElementById('depositActionSheet'));
                    myModal.show();
                <?php endif; ?>
            });
            document.addEventListener("DOMContentLoaded", function() {
                const switchBtn = document.getElementById("darkmodeSwitch");
                const body = document.body;

                // Default: light mode (no dark-mode class)
                // If user had dark mode before, apply it
                if (localStorage.getItem("darkMode") === "enabled") {
                    body.classList.add("dark-mode");
                    switchBtn.checked = true;
                }

                // Toggle dark mode on switch change
                switchBtn.addEventListener("change", function() {
                    if (this.checked) {
                        body.classList.add("dark-mode");
                        localStorage.setItem("darkMode", "enabled");
                    } else {
                        body.classList.remove("dark-mode");
                        localStorage.setItem("darkMode", "disabled");
                    }
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                new DataTable('#example', {
                    searchable: true,
                });
            });

            document.addEventListener("DOMContentLoaded", function() {

                const role = "<?= $role ?>";
                const userId = "<?= $userId ?>";

                /* ==========================
                   🔹 Reusable functions
                ========================== */
                function loadClients(userId, target) {
                    const url = role === "admin" ?
                        `<?= base_url('admin/get-clients/') ?>${userId}` :
                        `<?= base_url('user/get-clients/') ?>${userId}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(list => {
                            target.innerHTML = '<option value="">-- Select Client --</option>';
                            list.forEach(c => {
                                target.innerHTML += `<option value="${c.id}">${c.company_name}</option>`;
                            });
                        });
                }

                function loadCustomers(clientId, userId, target) {
                    let url;

                    if (role === "admin") {
                        url = `<?= base_url('admin/get-customers') ?>?client_id=${clientId}&user_id=${userId}`;
                    } else {
                        url = `<?= base_url('user/get-customers') ?>?client_id=${clientId}`;
                    }

                    fetch(url)
                        .then(res => res.json())
                        .then(list => {
                            target.innerHTML = '<option value="">-- Select Customer --</option>';
                            list.forEach(ct => {
                                target.innerHTML += `<option value="${ct.id}">${ct.name}</option>`;
                            });
                        });
                }



                function loadCountries(target) {
                    let url = (role === "admin") ?
                        "<?= base_url('admin/get-countries') ?>" :
                        "<?= base_url('user/get-countries') ?>";

                    fetch(url)
                        .then(res => res.json())
                        .then(list => {
                            target.innerHTML = '<option value="">Select Country</option>';
                            list.forEach(c => target.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                        });
                }

                function loadStates(countryId, target) {
                    let stateUrl = (role === "admin") ?
                        "<?= base_url('admin/get-states') ?>" :
                        "<?= base_url('user/get-states') ?>";

                    fetch(`${stateUrl}/${countryId}`)
                        .then(res => res.json())
                        .then(list => {
                            target.innerHTML = '<option value="">Select State</option>';
                            list.forEach(s => target.innerHTML += `<option value="${s.id}">${s.name}</option>`);
                        });
                }

                function loadCities(stateId, target) {
                    let citiesUrl = (role === "admin") ?
                        "<?= base_url('admin/get-cities') ?>" :
                        "<?= base_url('user/get-cities') ?>";

                    fetch(`${citiesUrl}/${stateId}`)
                        .then(res => res.json())
                        .then(list => {
                            target.innerHTML = '<option value="">Select City</option>';
                            list.forEach(ct => target.innerHTML += `<option value="${ct.id}">${ct.name}</option>`);
                        });
                }

                /* =====================================================
                   🔥 1) ADD TRANSACTION MODAL
                   (NO COUNTRY/STATE/CITY — BUT LOAD COMPANIES + HSN)
                ===================================================== */
                $('#addtransactionModal').on('show.bs.modal', function() {

                    const modal = this;

                    const userSel = modal.querySelector("#userSelect_transaction");
                    const clientSel = modal.querySelector("#clientSelect_transaction");
                    const customerSel = modal.querySelector("#customerSelect_transaction");
                    const companySelect = modal.querySelector("#companySelect");
                    const hsnSelect = modal.querySelector("#hsnSelect");

                    /* Load Companies (same for admin and user) */
                    /* ⭐ Load Companies for both admin & user */
                    const companyUrl = role === "admin" ?
                        "<?= base_url('admin/get-companies') ?>" :
                        "<?= base_url('user/get-companies') ?>";

                    fetch(companyUrl)
                        .then(res => res.json())
                        .then(list => {
                            companySelect.innerHTML = '<option value="">Select Company</option>';
                            list.forEach(c => {
                                companySelect.innerHTML += `<option value="${c.id}">${c.company_name}</option>`;
                            });
                        })
                        .catch(() => {
                            companySelect.innerHTML = '<option value="">Error loading companies</option>';
                        });


                    /* ⭐ Load HSN Code for both admin & user */
                    const hsnUrl = role === "admin" ?
                        "<?= base_url('admin/get-hsncode') ?>" :
                        "<?= base_url('user/get-hsncode') ?>";

                    fetch(hsnUrl)
                        .then(res => res.json())
                        .then(list => {
                            hsnSelect.innerHTML = '<option value="">Select HSN Code</option>';
                            list.forEach(h => {
                                hsnSelect.innerHTML += `<option value="${h.id}">${h.hsn_code}(${h.description})</option>`;
                            });
                        })
                        .catch(() => {
                            hsnSelect.innerHTML = '<option value="">Error loading HSN</option>';
                        });

                    /* ------------------ ADMIN LOGIN FLOW ------------------ */
                    if (role === "admin") {
                        // Load all users
                        fetch("<?= base_url('admin/get-users') ?>")
                            .then(res => res.json())
                            .then(users => {
                                userSel.innerHTML = '<option value="">-- Select User --</option>';
                                users.forEach(u => userSel.innerHTML += `<option value="${u.id}">${u.name}</option>`);
                            });

                        // When admin selects user → load clients
                        userSel.onchange = () => loadClients(userSel.value, clientSel);
                    }

                    /* ------------------ USER LOGIN FLOW ------------------ */
                    if (role === "user") {
                        // Load only logged-in user's clients automatically
                        loadClients(userId, clientSel);
                    }

                    /* Client → Customer (common for both admin & user) */
                    if (role === "admin") {
                        clientSel.onchange = () => loadCustomers(clientSel.value, userSel.value, customerSel);
                    }

                    if (role === "user") {
                        clientSel.onchange = () => loadCustomers(clientSel.value, userId, customerSel);
                    }


                });


                /* =====================================================
                   🔥 2) ADD CUSTOMER MODAL (HAS COUNTRY / STATE / CITY)
                ===================================================== */
                $('#addCustomerModal').on('show.bs.modal', function() {

                    const modal = this;

                    const userSel = modal.querySelector("#userSelect_customer");
                    const clientSel = modal.querySelector("#clientSelect_customer");
                    const customerSel = modal.querySelector("#customerSelect_customer");

                    const country = modal.querySelector("#countrySelect");
                    const state = modal.querySelector("#stateSelect");
                    const city = modal.querySelector("#citySelect");

                    /* COUNTRY → STATE → CITY */
                    loadCountries(country);
                    country.onchange = () => loadStates(country.value, state);
                    state.onchange = () => loadCities(state.value, city);

                    /* USER → CLIENT → CUSTOMER */
                    if (role === "admin") {
                        fetch("<?= base_url('admin/get-users') ?>")
                            .then(res => res.json())
                            .then(users => {
                                userSel.innerHTML = '<option value="">-- Select User --</option>';
                                users.forEach(u => userSel.innerHTML += `<option value="${u.id}">${u.name}</option>`);
                            });

                        userSel.onchange = () => loadClients(userSel.value, clientSel);
                    }

                    if (role === "user") {
                        loadClients(userId, clientSel);
                    }

                    clientSel.onchange = () => loadCustomers(clientSel.value, customerSel);
                });

                $('#addUserModel').on('show.bs.modal', function() {
                    const modal = this;
                    const country = modal.querySelector("#countrySelect");
                    const state = modal.querySelector("#stateSelect");
                    const city = modal.querySelector("#citySelect");

                    loadCountries(country);
                    country.onchange = () => loadStates(country.value, state);
                    state.onchange = () => loadCities(state.value, city);
                })
            });




            function calculateTotalAmount() {
                const noOfCodes = parseInt($('#noOfCodes').val()) || 0;
                const ratePerCode = parseFloat($('#ratePerCode').val()) || 0;
                const extraCodes = parseInt($('#extraCodes').val()) || 0;
                const paidAmounts = parseFloat($('#paidAmounts').val()) || 0;
                const gstApplied = parseInt($('#gstApplied').val()); // 0 or 1

                // Base amount = number of codes * rate
                const totalAmounts = (noOfCodes * ratePerCode);

                // Total codes count
                const totalCodes = noOfCodes + extraCodes;
                // GST calculation
                let gstAmount = 0;
                let grandTotal = totalAmounts;

                if (gstApplied === 1) {
                    gstAmount = totalAmounts * 0.18; // 18% GST
                    grandTotal = totalAmounts + gstAmount;
                }

                // Remaining balance
                const remainingAmount = grandTotal - paidAmounts;

                // Update form fields
                $('#totalCodes').val(totalCodes);
                $('#totalAmounts').val(totalAmounts.toFixed(2));
                $('#gstAmount').val(gstAmount.toFixed(2));
                $('#grandTotal').val(grandTotal.toFixed(2));
                $('#remainingAmounts').val(remainingAmount.toFixed(2));

                // Show / hide GST display row live
                if (gstApplied === 1) {
                    $('#gstRow').show();
                    $('#gstNumber').show();
                } else {
                    $('#gstRow').hide();
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const role = "<?= $role ?>"; // ✅ Make sure $role is available in your view

                // when edit button is clicked
                $(document).on("click", ".edit-transaction", function(e) {
                    e.preventDefault();

                    const transactionId = $(this).data("id");

                    const fetchUrl = (role === 'admin') ?
                        `<?= base_url('admin/transaction/getTransaction/') ?>${transactionId}` :
                        `<?= base_url('user/transaction/getTransaction/') ?>${transactionId}`;

                    fetch(fetchUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.transaction) {
                                const t = data.transaction;

                                $("#customerName").val(t.customer_name);
                                $("#totalAmount").val(t.total_amount);
                                $("#remainingAmount").val(t.remaining_amount);
                                $("#transactionId").val(t.id);

                                const payForm = document.getElementById("payNowForm");
                                payForm.action = (role === 'admin') ?
                                    "<?= base_url('admin/transaction/payNow') ?>" :
                                    "<?= base_url('user/transaction/payNow') ?>";

                                new bootstrap.Modal(document.getElementById("payNowModal")).show();
                            } else {
                                console.error("Transaction not found:", data);
                            }
                        })
                        .catch(error => console.error("Fetch Error:", error));
                });


                $(document).on("click", ".view-transaction", function(e) {
                    e.preventDefault();
                    const transactionId = $(this).data("view-id");

                    const fetchUrl = (role === 'admin') ?
                        `<?= base_url('admin/transaction/getTransaction/') ?>${transactionId}` :
                        `<?= base_url('user/transaction/getTransaction/') ?>${transactionId}`;

                    fetch(fetchUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.transaction) {
                                const t = data.transaction;
                                const history = data.history || [];

                                $("#customer_Name").text(t.customer_name || 'N/A');
                                $("#transaction_Id").text(t.recipt_no || 'N/A');
                                $("#transactionCode").text(t.code || 'N/A');
                                $("#total_Amount").text(t.total_amount || '0');
                                $("#paidAmount").text(t.paid_amount || '0');
                                $("#remaining_Amount").text(t.remaining_amount || '0');
                                $("#transactionDate").text(t.created_at || 'N/A');
                                $("#extraCode").text(t.extra_code || 'N/A');
                                $("#totalCode").text(t.total_code || 'N/A');

                                const historyContainer = $("#transactionHistory");
                                historyContainer.html("");

                                if (history.length > 0) {
                                    history.forEach((h, index) => {
                                        historyContainer.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${h.before_paid_amount || '0'}</td>
                                <td>${h.amount || '0'}</td>
                                <td>${h.after_paid_amount || '0'}</td>
                                <td>${h.created_at || 'N/A'}</td>
                                <td>${h.remark || 'N/A'}</td>
                            </tr>
                        `);
                                    });
                                } else {
                                    historyContainer.html(
                                        `<tr><td colspan="6" class="text-center">No history available</td></tr>`
                                    );
                                }

                                new bootstrap.Modal(document.getElementById("invoiceModal")).show();
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });

                const downloadBtn = document.getElementById("downloadReceipt");

                if (downloadBtn) {
                    downloadBtn.addEventListener("click", async () => {

                        const modal = document.querySelector("#invoiceModal .modal-content");

                        // Hide elements with no-print class
                        document.querySelectorAll(".no-print").forEach(el => el.style.display = "none");

                        const invoiceNumber = document.getElementById("transaction_Id")?.innerText.trim() || "INV-UNKNOWN";
                        downloadBtn.disabled = true;
                        downloadBtn.innerText = "Generating...";

                        const {
                            jsPDF
                        } = window.jspdf;

                        const canvas = await html2canvas(modal, {
                            scale: 2,
                            useCORS: true,
                        });

                        const imgData = canvas.toDataURL("image/png");
                        const pdf = new jsPDF("p", "mm", "a4");
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                        pdf.save(`${invoiceNumber}_Invoice.pdf`);

                        // Restore footer visibility
                        document.querySelectorAll(".no-print").forEach(el => el.style.display = "");

                        downloadBtn.disabled = false;
                        downloadBtn.innerHTML = `<ion-icon name="download-outline"></ion-icon> Download PDF`;

                    });
                }


            });
        </script>


        <script src="<?= base_url('assets/js/lib/bootstrap.bundle.min.js'); ?>"></script>
        <!-- Ionicons -->
        <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
        <!-- Splide -->

        <script src="<?= base_url('assets/js/plugins/splide/splide.min.js') ?>"></script>
        <!-- ✅ jQuery (optional, not required for v2) -->
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

        <!-- ✅ DataTables v2 -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <?php
        $role = session()->get('role');
        $customerListUrl = ($role === 'admin')
            ? base_url('admin/customer-list')
            : base_url('user');
        ?>

        <script>
            $(document).ready(function() {
                $('#clientSelect').on('change', function() {
                    let clientId = $(this).val();
                    let baseUrl = "<?= $customerListUrl ?>";
                    if (clientId) {
                        window.location.href = baseUrl + '?client_id=' + clientId;
                    } else {
                        window.location.href = baseUrl; // reload full list
                    }
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const userSelect = document.getElementById("userSelect_transaction");
                const clientSelect = document.getElementById("clientForTransactionsSelect");
                const customerSelect = document.getElementById("customerTransactionsSelect");

                const role = "<?= session()->get('role') ?>"; // admin / user
                const loggedUserId = "<?= session()->get('user_id') ?>";

                // -------------- ADMIN LOGIC -------------- //

                // 1️⃣ Admin selects USER → Load CLIENTS of that user
                if (role === "admin" && userSelect) {
                    userSelect.addEventListener("change", function() {
                        let userId = this.value;
                        clientSelect.innerHTML = '<option>Loading...</option>';
                        customerSelect.innerHTML = '<option>Select Customer</option>';

                        fetch(`<?= base_url('admin/get-clients/') ?>${userId}`)
                            .then(res => res.json())
                            .then(list => {
                                clientSelect.innerHTML = '<option value="">Select Client</option>';
                                list.forEach(c => {
                                    clientSelect.innerHTML += `<option value="${c.id}">${c.company_name}</option>`;
                                });
                            });
                    });
                }

                // 2️⃣ Admin selects CLIENT → Load CUSTOMERS
                if (role === "admin" && clientSelect) {
                    clientSelect.addEventListener("change", function() {
                        let clientId = this.value;
                        let userId = userSelect.value;

                        customerSelect.innerHTML = '<option>Loading...</option>';

                        fetch(`<?= base_url('admin/get-customers') ?>?client_id=${clientId}&user_id=${userId}`)
                            .then(res => res.json())
                            .then(list => {
                                customerSelect.innerHTML = '<option value="">Select Customer</option>';
                                list.forEach(c => {
                                    customerSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                                });
                            });
                    });
                }


                // -------------- USER LOGIN LOGIC -------------- //

                // User login → directly load customers (skip user & client dropdowns)
                if (role === "user" && customerSelect) {
                    customerSelect.innerHTML = '<option>Loading...</option>';

                    fetch(`<?= base_url('user/customer-by-user/') ?>${loggedUserId}`)
                        .then(res => res.json())
                        .then(list => {
                            customerSelect.innerHTML = '<option value="">Select Customer</option>';
                            list.forEach(c => {
                                customerSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                            });

                            if (list.length === 0) {
                                customerSelect.innerHTML = '<option>No Customers Found</option>';
                            }
                        })
                        .catch(err => {
                            console.error("Error loading customers:", err);
                            customerSelect.innerHTML = '<option>Error Loading</option>';
                        });
                }


            });


            document.getElementById("dateFilter").addEventListener("change", function() {
                const show = this.value === "custom";
                document.getElementById("fromDateBox").style.display = show ? "block" : "none";
                document.getElementById("toDateBox").style.display = show ? "block" : "none";
            });
        </script>
        <script>
            document.addEventListener("click", async function(event) {
                const button = event.target.closest(".reassign-btn");
                if (!button) return;

                const customerId = button.dataset.customerId;
                const customerName = button.dataset.customerName;
                const clientId = button.dataset.clientId;
                const currentUserId = button.dataset.currentUserId; // ⭐ added

                document.getElementById("modalCustomerId").value = customerId;
                document.getElementById("modalCustomerName").value = customerName;

                const userSelect = document.getElementById("modalUserSelect");
                userSelect.innerHTML = `<option value="">Loading...</option>`;

                try {
                    const response = await fetch(`<?= base_url('admin/get-client-users/') ?>${clientId}`);
                    const result = await response.json();
                    userSelect.innerHTML = `<option value="">-- Select User --</option>`;

                    if (Array.isArray(result) && result.length > 0) {
                        result.forEach(user => {
                            userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                        });

                        // ⭐ auto-select assigned user
                        if (currentUserId) {
                            userSelect.value = currentUserId;
                        }

                    } else {
                        userSelect.innerHTML += `<option value="">No users for this client</option>`;
                    }

                } catch (error) {
                    console.error("Error loading users:", error);
                    userSelect.innerHTML = `<option value="">Error loading users</option>`;
                }
            });
        </script>

        <?php if (session()->getFlashdata('success')): ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: "top",
                    icon: "success",
                    text: "<?= esc(session()->getFlashdata('success')) ?>",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            </script>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: "top-center",
                    icon: "error",
                    text: `<?= esc(session()->getFlashdata('error')) ?>`.replace(/\\n/g, "\n"),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            </script>
        <?php endif; ?>
        <script>
            document.querySelectorAll(".submenu-toggle").forEach(toggle => {
                toggle.addEventListener("click", () => {
                    const parent = toggle.closest(".has-submenu");
                    const submenu = parent.querySelector(".submenu");

                    // Close other submenus (optional)
                    document.querySelectorAll(".has-submenu").forEach(item => {
                        if (item !== parent) {
                            item.classList.remove("active");
                            item.querySelector(".submenu").style.display = "none";
                        }
                    });

                    // Toggle current submenu
                    if (parent.classList.contains("active")) {
                        parent.classList.remove("active");
                        submenu.style.display = "none";
                    } else {
                        parent.classList.add("active");
                        submenu.style.display = "block";
                    }
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>
        <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
        <script>
            const pusher = new Pusher("<?= getenv('pusher.key') ?>", {
                cluster: "<?= getenv('pusher.cluster') ?>",
                encrypted: true
            });

            const channel = pusher.subscribe("notifications");
            channel.bind("new-notification", function(data) {
                // Popup alert
                Swal.fire(data.title, data.message, "info");
                // Refresh dropdown + count
                loadNotifications();
            });
        </script>

        </body>

        </html>