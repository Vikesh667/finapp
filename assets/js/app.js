document.addEventListener("DOMContentLoaded", function () {

    const role = "<?= $role ?>";
    const userId = "<?= $userId ?>";

    /** =============================
     *  🔹 UNIVERSAL LOADERS (API based)
     ============================== */

    async function loadData(url) {
        try {
            const response = await fetch(url);
            return await response.json();
        } catch (err) {
            console.error("Fetch error:", err);
            return [];
        }
    }

    async function loadUsers(selectEl) {
        const users = await loadData(`/api/users`);
        selectEl.innerHTML = `<option value="">-- Select User --</option>`;
        users.forEach(u => {
            selectEl.innerHTML += `<option value="${u.id}">${u.name}</option>`;
        });
    }

    async function loadClients(userId, selectEl) {
        const clients = await loadData(`/api/clients/${userId}`);
        selectEl.innerHTML = `<option value="">-- Select Client --</option>`;
        clients.forEach(c => {
            selectEl.innerHTML += `<option value="${c.id}">${c.name ?? c.company_name}</option>`;
        });
    }

    async function loadCustomers(clientId, selectEl) {
        const customers = await loadData(`/api/customers/${clientId}`);
        selectEl.innerHTML = `<option value="">-- Select Customer --</option>`;
        customers.forEach(c => {
            selectEl.innerHTML += `<option value="${c.id}">${c.name}</option>`;
        });
    }

    async function loadCountries(selectEl) {
        const data = await loadData(`/api/countries`);
        selectEl.innerHTML = `<option value="">Select Country</option>`;
        data.forEach(c => selectEl.innerHTML += `<option value="${c.id}">${c.name}</option>`);
    }

    async function loadStates(countryId, selectEl) {
        const data = await loadData(`/api/states/${countryId}`);
        selectEl.innerHTML = `<option value="">Select State</option>`;
        data.forEach(s => selectEl.innerHTML += `<option value="${s.id}">${s.name}</option>`);
    }

    async function loadCities(stateId, selectEl) {
        const data = await loadData(`/api/cities/${stateId}`);
        selectEl.innerHTML = `<option value="">Select City</option>`;
        data.forEach(c => selectEl.innerHTML += `<option value="${c.id}">${c.name}</option>`);
    }


    /** ==================================================
     *  🔹 TRANSACTION MODAL HANDLER (Admin & User Safe)
     =================================================== */

    document.querySelectorAll('.edit-transaction').forEach(btn => {
        btn.addEventListener('click', async function () {
            const transactionId = this.dataset.id;
            const url = role === 'admin'
                ? `/admin/transaction/getTransaction/${transactionId}`
                : `/user/transaction/getTransaction/${transactionId}`;

            const data = await loadData(url);

            if (data.transaction) {
                const t = data.transaction;

                document.getElementById('customerName').value = t.customer_name;
                document.getElementById('totalAmount').value = t.total_amount;
                document.getElementById('remainingAmount').value = t.remaining_amount;
                document.getElementById('transactionId').value = t.id;

                document.getElementById("payNowForm").action =
                    role === "admin"
                        ? "/admin/transaction/payNow"
                        : "/user/transaction/payNow";

                new bootstrap.Modal(document.getElementById('payNowModal')).show();
            }
        });
    });


    /** ==================================================
     *  🔹 ASSIGN DATA WHEN ADD TRANSACTION MODAL OPENED
     =================================================== */

    $('#addtransactionModal').on('show.bs.modal', async function () {
        const userSelect = document.getElementById('userSelect');
        const clientSelect = document.getElementById('clientSelectt');
        const customerSelect = document.getElementById('customerSelect');

        // Admin: load all users
        if (role === 'admin') {
            await loadUsers(userSelect);

            userSelect.addEventListener('change', async function () {
                await loadClients(this.value, clientSelect);
                customerSelect.innerHTML = `<option value="">Select Client First</option>`;
            });
        }

        // User: load only their clients
        if (role === 'user') {
            await loadClients(userId, clientSelect);
        }

        clientSelect.addEventListener('change', async function () {
            await loadCustomers(this.value, customerSelect);
        });
    });

    /** ==================================================
     *  🔹 CUSTOMER MODAL (COUNTRY → STATE → CITY)
     =================================================== */

    $('#addCustomerModal').on('show.bs.modal', async function () {
        const userSelect = document.getElementById('userSelect');
        const clientSelect = document.getElementById('clientSelect');
        const countrySelect = document.getElementById('countrySelect');
        const stateSelect = document.getElementById('stateSelect');
        const citySelect = document.getElementById('citySelect');

        if (role === 'admin') {
            await loadUsers(userSelect);
            userSelect.addEventListener('change', async function () {
                await loadClients(this.value, clientSelect);
            });
        } else {
            await loadClients(userId, clientSelect);
        }

        await loadCountries(countrySelect);

        countrySelect.addEventListener('change', async function () {
            await loadStates(this.value, stateSelect);
        });

        stateSelect.addEventListener('change', async function () {
            await loadCities(this.value, citySelect);
        });
    });


    /** ==================================================
     *  🔹 CALCULATE TOTAL AMOUNT
     =================================================== */

    window.calculateTotalAmount = function () {
        const noOfCodes = +document.getElementById('noOfCodes').value;
        const rate = +document.getElementById('ratePerCode').value;
        const paid = +document.getElementById('paidAmounts').value;
        const extra = +document.getElementById('extraCodes').value;

        const total = noOfCodes * rate;
        const remaining = total - paid;
        const finalCodes = noOfCodes + extra;

        document.getElementById('totalAmounts').value = total.toFixed(2);
        document.getElementById('remainingAmounts').value = remaining.toFixed(2);
        document.getElementById('totalCodes').value = finalCodes;
    };

    const downloadBtn = document.getElementById("downloadReceipt");

if (downloadBtn) {
    downloadBtn.addEventListener("click", async () => {

        // Select the modal content
        const modal = document.querySelector("#invoiceModal .modal-content .modal-body");

        if (!modal) {
            alert("Modal not found!");
            return;
        }

        const invoiceNumber = document.getElementById("transaction_Id")?.innerText.trim() || "INV-UNKNOWN";

        // Show generating status
        downloadBtn.innerText = "Generating...";
        downloadBtn.disabled = true;

        const { jsPDF } = window.jspdf;

        // Capture content as image
        const canvas = await html2canvas(modal, {
            scale: 2,
            useCORS: true,
        });

        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);

        // Save file
        pdf.save(`${invoiceNumber}_Invoice.pdf`);

        // Reset button
        downloadBtn.innerText = "Download PDF";
        downloadBtn.disabled = false;
    });
} else {
    console.warn("⚠️ Download button not found in DOM.");
}

});

