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

            document.addEventListener('DOMContentLoaded', function() {
                const role = "<?= $role ?>";
                const userId = "<?= $userId ?>";
                const userSelect = document.getElementById('userSelect');
                const clientSelect = document.getElementById('clientSelectt');
                const customerSelect = document.getElementById('customerSelect');
                $('#addtransactionModal').on('show.bs.modal', function() {

                    //  For Admin - Load Users
                    if (role === 'admin') {
                        fetch("<?= base_url('admin/get-users') ?>")
                            .then(res => res.json())
                            .then(users => {
                                userSelect.innerHTML = '<option value="">-- Select User --</option>';
                                users.forEach(user => {
                                    userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                                });
                            })
                            .catch(() => {
                                userSelect.innerHTML = '<option value="">Error loading users</option>';
                            });

                        // When Admin selects a User → Load their Clients
                        userSelect.addEventListener('change', function() {
                            const selectedUserId = this.value;
                            if (!selectedUserId) {
                                clientSelect.innerHTML = '<option value="">Select user first</option>';
                                customerSelect.innerHTML = '<option value="">Select client first</option>';
                                return;
                            }

                            fetch(`<?= base_url('admin/get-clients/') ?>${selectedUserId}`)
                                .then(res => res.json())
                                .then(clients => {
                                    clientSelect.innerHTML = '<option value="">-- Select Client --</option>';
                                    clients.forEach(client => {
                                        clientSelect.innerHTML += `<option value="${client.id}">${client.name}</option>`;
                                    });
                                })
                                .catch(() => {
                                    clientSelect.innerHTML = '<option value="">Error loading clients</option>';
                                });
                        });

                        // When Admin selects a Client → Load their Customers
                        clientSelect.addEventListener('change', function() {
                            const selectedClientId = this.value;

                            if (!selectedClientId) {
                                customerSelect.innerHTML = '<option value="">Select client first</option>';
                                return;
                            }

                            fetch(`http://localhost/finapp/admin/get-customers/${selectedClientId}`)
                                .then(res => res.json())
                                .then(customers => {
                                    console.log(customers);
                                    customerSelect.innerHTML = '<option value="">-- Select Customer --</option>';
                                    customers.forEach(customer => {
                                        customerSelect.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
                                    });
                                })
                                .catch(() => {
                                    customerSelect.innerHTML = '<option value="">Error loading customers</option>';
                                });
                        });
                    }

                    // 👤 For User - Load only their Clients
                    if (role === 'user') {
                        console.log('Fetching clients for user:', userId); // ✅ check if this appears

                        fetch(`<?= base_url('user/get-clients/') ?>${userId}`)

                            .then(res => res.json())
                            .then(clients => {
                                clientSelect.innerHTML = '<option value="">-- Select Client --</option>';
                                clients.forEach(client => {
                                    clientSelect.innerHTML += `<option value="${client.id}">${client.name}</option>`;
                                });
                            })
                            .catch(() => {
                                clientSelect.innerHTML = '<option value="">Error loading clients</option>';
                            });

                        // When User selects a Client → Load their Customers
                        clientSelect.addEventListener('change', function() {
                            const selectedClientId = this.value;
                            if (!selectedClientId) {
                                customerSelect.innerHTML = '<option value="">Select client first</option>';
                                return;
                            }

                            fetch(`<?= base_url('user/get-customers/') ?>${selectedClientId}`)
                                .then(res => res.json())
                                .then(customers => {
                                    customerSelect.innerHTML = '<option value="">-- Select Customer --</option>';
                                    customers.forEach(customer => {
                                        customerSelect.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
                                    });
                                })
                                .catch(() => {
                                    customerSelect.innerHTML = '<option value="">Error loading customers</option>';
                                });
                        });
                    }

                });
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
                document.querySelectorAll('.edit-transaction').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const transactionId = this.getAttribute('data-id');

                        //  Use correct route based on role
                        const fetchUrl = (role === 'admin') ?
                            `<?= base_url('admin/transaction/getTransaction/') ?>${transactionId}` :
                            `<?= base_url('user/transaction/getTransaction/') ?>${transactionId}`;

                        // Fetch transaction data from backend
                        fetch(fetchUrl)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.transaction) {
                                    const t = data.transaction; // ✅ Shortcut for easier use

                                    //  Fill modal fields with transaction data
                                    document.getElementById('customerName').value = t.customer_name || '';
                                    document.getElementById('totalAmount').value = t.total_amount || '';
                                    document.getElementById('remainingAmount').value = t.remaining_amount || '';
                                    document.getElementById('transactionId').value = t.id || '';

                                    // ✅ Set correct form action dynamically
                                    const payForm = document.getElementById('payNowForm');
                                    payForm.action = (role === 'admin') ?
                                        "<?= base_url('admin/transaction/payNow') ?>" :
                                        "<?= base_url('user/transaction/payNow') ?>";

                                    // ✅ Show modal
                                    const payNowModal = new bootstrap.Modal(document.getElementById('payNowModal'));
                                    payNowModal.show();
                                } else {
                                    console.error('Transaction data not found:', data);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });

                document.querySelectorAll('.view-transaction').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const transactionId = this.getAttribute('data-view-id');

                        const fetchUrl = (role === 'admin') ?
                            `<?= base_url('admin/transaction/getTransaction/') ?>${transactionId}` :
                            `<?= base_url('user/transaction/getTransaction/') ?>${transactionId}`;

                        fetch(fetchUrl)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.transaction) {
                                    const t = data.transaction;
                                    const history = data.history || [];


                                    document.getElementById('customer_Name').textContent = t.customer_name || 'N/A';
                                    document.getElementById('transaction_Id').textContent = t.recipt_no || 'N/A';
                                    document.getElementById('transactionCode').textContent = t.code || 'N/A';
                                    document.getElementById('total_Amount').textContent = t.total_amount || '0';
                                    document.getElementById('paidAmount').textContent = t.paid_amount || '0';
                                    document.getElementById('remaining_Amount').textContent = t.remaining_amount || '0';
                                    document.getElementById('transactionDate').textContent = t.created_at || 'N/A';
                                    document.getElementById('extraCode').textContent = t.extra_code || 'N/A';
                                    document.getElementById('totalCode').textContent = t.total_code || 'N/A';

                                    const historyContainer = document.getElementById('transactionHistory');
                                    if (historyContainer) {
                                        historyContainer.innerHTML = ''; // Clear old data

                                        if (history.length > 0) {
                                            history.forEach((h, index) => {
                                                const row = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        
                                        <td>${h.before_paid_amount || '0'}</td>
                                        <td>${h.amount || '0'}</td>
                                        <td>${h.after_paid_amount || '0'}</td>
                                        <td>${h.created_at || 'N/A'}</td>
                                        <td>${h.remark || 'N/A'}</td>
                                    </tr>
                                `;
                                                historyContainer.insertAdjacentHTML('beforeend', row);
                                            });
                                        } else {
                                            historyContainer.innerHTML = `<tr><td colspan="5" class="text-center">No history available</td></tr>`;
                                        }
                                    }

                                    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                                    invoiceModal.show();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
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

            document.addEventListener('DOMContentLoaded', function() {

                const role = "<?= $role ?>";
                const userId = "<?= $userId ?>";
                const userSelect = document.getElementById('userSelect');
                const clientSelect = document.getElementById('clientSelect');
                const countrySelect = document.getElementById('countrySelect');
                const stateSelect = document.getElementById('stateSelect');
                const citySelect = document.getElementById('citySelect');
                const companySelect = document.getElementById('companySelect');
                const hsnSelect = document.getElementById('hsnSelect')
                /* -------------------------
                   🔹 LOAD COMPANIES ALWAYS
                --------------------------*/
                companySelect.innerHTML = `<option value="">Loading...</option>`;
                hsnSelect.innerHTML = `<option value="">Loading...</option>`;
                let companyApi = role === 'admin' ?
                    "<?= base_url('admin/get-companies') ?>" :
                    "<?= base_url('user/get-companies') ?>";

                fetch(companyApi)
                    .then(res => res.json())
                    .then(companies => {

                        if (!companies.length) {
                            companySelect.innerHTML = `<option value="">No companies found</option>`;
                            return;
                        }

                        companySelect.innerHTML = `<option value="">Select Company</option>`;

                        companies.forEach(company => {
                            companySelect.innerHTML += `
                    <option value="${company.id}">
                        ${company.company_name}
                    </option>`;
                        });
                    })
                    .catch(() => {
                        companySelect.innerHTML = `<option value="">Error loading companies</option>`;
                    });

                fetch("<?= base_url('admin/get-hsncode') ?>")
                    .then(res => res.json())
                    .then(hsnCodes => {

                        if (!hsnCodes.length) {
                            hsnSelect.innerHTML = `<option value="">No HSN Code Found</option>`;
                            return;
                        }

                        hsnSelect.innerHTML = `<option value="">Select HSN Code</option>`;

                        hsnCodes.forEach(item => {
                            hsnSelect.innerHTML += `
                <option value="${item.id}">
                    ${item.code}
                </option>`;
                        });
                    })
                    .catch(() => {
                        hsnSelect.innerHTML = `<option value="">Error Loading HSN Code</option>`;
                    });



                /* --------------------------------------------------
                   🔹 The remaining logic should stay inside modal load
                ---------------------------------------------------*/
                $('#addCustomerModal').on('show.bs.modal', function() {

                    if (role === 'admin') {

                        // Load Users
                        fetch("<?= base_url('admin/get-users') ?>")
                            .then(res => res.json())
                            .then(users => {
                                userSelect.innerHTML = '<option value="">-- Select User --</option>';
                                users.forEach(user => {
                                    userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                                });
                            });

                        // Load Countries
                        fetch("<?= base_url('admin/get-countries') ?>")
                            .then(res => res.json())
                            .then(countries => {
                                countrySelect.innerHTML = '<option value="">Select Country</option>';
                                countries.forEach(c => {
                                    countrySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                                });
                            });

                    }

                    if (role === 'user') {

                        // Load clients assigned to logged-in user
                        fetch(`<?= base_url('user/get-clients/') ?>${userId}`)
                            .then(res => res.json())
                            .then(clients => {
                                clientSelect.innerHTML = '<option value="">-- Select Client --</option>';
                                clients.forEach(client => {
                                    clientSelect.innerHTML += `<option value="${client.id}">${client.company_name}</option>`;
                                });
                            });

                        // Load Countries
                        fetch("<?= base_url('user/get-countries') ?>")
                            .then(res => res.json())
                            .then(countries => {
                                countrySelect.innerHTML = '<option value="">Select Country</option>';
                                countries.forEach(c => {
                                    countrySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                                });
                            });

                    }

                });

            });


            <?php if (session()->getFlashdata('success')): ?>
                Swal.fire({
                    position: "center",
                    icon: "success",
                    text: "<?= esc(session()->getFlashdata('success')) ?>",
                    showConfirmButton: false,
                    timer: 2500
                }, 2000);
            <?php endif; ?>
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
        </body>

        </html>