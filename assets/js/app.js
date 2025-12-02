const listDataUrl = window.appConfig.listDataUrl;
const logoUrl = window.appConfig.logoUrl;
const editUrl = window.appConfig.editUrl;
const deleteUserUrl = window.appConfig.deleteUserUrl;

const clientListDataUrl = window.appConfig.clientListDataUrl;
const logoUrlc = window.appConfig.logoUrl;
const editClientUrl = window.appConfig.editClientUrl;
const deleteClientUrl = window.appConfig.deleteClientUrl;
const viewClientProductUrl = window.appConfig.viewClientProductUrl;
const isAdmin = Number(window.appConfig.isAdmin);

const customerListDataUrl = window.appConfig.customerListDataUrl; // JSON list API
const editCustomerUrl = window.appConfig.editCustomerUrl;
const deleteCustomerUrl = window.appConfig.deleteCustomerUrl;
const transactionHistoryUrl = window.appConfig.transactionHistoryUrl;
const detailViewUrl = window.appConfig.detailViewUrl;

function loadUsers() {
  // Loader inside tbody
  $("#userBody").html(`
        <tr>
            <td colspan="7" class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Loading data...</p>
            </td>
        </tr>
    `);

  $.ajax({
    url: listDataUrl,
    method: "GET",
    dataType: "json",
    success: function (response) {
      let rows = "";
      let start = 1;

      response.users.forEach((user, index) => {
        rows += `
                <tr>
                    <td>${start + index}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone ?? "-"}</td>
                    <td>${user.address ?? "-"}</td>
                    <td><img src="${logoUrl}${
          user.profile_image
        }" width="50"></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="${editUrl}${
          user.id
        }" class="btn btn-sm btn-outline-primary rounded-circle">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <button onclick="deleteUser(${
                              user.id
                            })" class="btn btn-sm btn-outline-danger rounded-circle">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>`;
      });

      $("#userBody").html(rows);
    },
  });
}
function deleteUser(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "This user will be deleted and clients transferred to admin.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(deleteUserUrl + id, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: result.message,
            timer: 2000,
            showConfirmButton: false,
          });
          loadUsers();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error!",
            text: result.message,
          });
        }
      })
      .catch(() => Swal.fire("Error!", "Something went wrong", "error"));
  });
}

function loadClients() {
  $("#clientBody").html(`
    <tr>
      <td colspan="8" class="text-center py-4">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2">Loading clients...</p>
      </td>
    </tr>
  `);

  $.ajax({
    url: clientListDataUrl,
    method: "GET",
    dataType: "json",
    success: function (response) {
      let rows = "";
      let start = 1;

      response.clients.forEach((client, index) => {
        let actions = `
          <a href="${viewClientProductUrl}${client.id}"
            class="btn btn-sm btn-warning rounded-pill px-3"
            title="View Client Products">
            View
          </a>

          <a href="${editClientUrl}${client.id}"
            class="btn btn-sm btn-outline-primary rounded-circle action-btn"
            title="Edit Client">
            <ion-icon name="create-outline"></ion-icon>
          </a>

          <button onclick="deleteClient(${client.id})"
            class="btn btn-sm btn-outline-danger rounded-circle action-btn"
            title="Delete Client">
            <ion-icon name="trash-outline"></ion-icon>
          </button>

          <button class="btn btn-sm btn-outline-secondary rounded-pill d-flex align-items-center gap-1 px-3"
            data-bs-toggle="modal"
            data-bs-target="#userModal"
            data-client-id="${client.id}"
            title="Assign User">
            <i class="bi bi-person-plus"></i> Assign
          </button>
        `;

        rows += `
          <tr>
            <td>${start + index}</td>
            <td>${client.name}</td>
            <td>${client.email}</td>
            <td>${client.company_name ?? "-"}</td>
            <td><a href="${client.url}" target="_blank">${client.url}</a></td>
            <td><img src="${logoUrlc}${client.logo}" width="50"></td>
            <td class="text-center">
              <div class="d-flex justify-content-center flex-wrap gap-2">
                ${actions}
              </div>
            </td>
          </tr>
        `;
      });

      $("#clientBody").html(rows);
    },
  });
}
function deleteClient(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "Once deleted, this client cannot be recovered!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(deleteClientUrl + id, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire("Deleted!", data.message, "success");
          loadClients(); // refresh table without reloading the page
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
  });
}
function loadCustomers() {
  // loader
  $("#customerBody").html(`
  <tr>
    <td colspan="10" class="text-center py-4">
      <div class="spinner-border text-primary"></div>
      <p class="mt-2">Loading customers...</p>
    </td>
  </tr>
`);

  // filter values
  let client = $("#filterClient").val();
  let service = $("#filterService").val();
  let search = $("#searchCustomer").val();

  $.ajax({
    url: customerListDataUrl,
    method: "GET",
    dataType: "json",
    data: {
      client_id: client || "",
      service_id: service || "",
      search: search || "",
    },
    success: function (response) {
      let rows = "";
      let start = 1;
      let customers = response.customers;

      customers.forEach((cust, index) => {
        let actions = `
          <a href="${transactionHistoryUrl}${cust.id}"
             class="btn btn-sm btn-outline-secondary rounded-circle"
             title="Transaction History">
            <ion-icon name="document-text-outline"></ion-icon>
          </a>

          <a href="${detailViewUrl}${cust.id}"
             class="btn btn-sm btn-outline-info rounded-circle"
             title="Customer Details">
            <ion-icon name="eye-outline"></ion-icon>
          </a>
        `;

        // Admin — extra privileges (edit / delete / reassign)
        if (isAdmin === 1) {
          actions += `
          
            <button onclick="deleteCustomer(${cust.id})"
              class="btn btn-sm btn-outline-danger rounded-circle"
              title="Delete Customer">
              <ion-icon name="trash-outline"></ion-icon>
            </button>

           <button class="btn btn-sm btn-warning reassign-btn"
             data-bs-toggle="modal"
             data-bs-target="#reassignCustomerModal"
             data-customer-id="${cust.id}"
             data-customer-name="${cust.name}"
             data-client-id="${cust.client_id}"
             data-current-user-id="${cust.user_id}">
            <ion-icon name="swap-horizontal-outline"></ion-icon> Reassign
             </button>

          `;
        }

        rows += `
          <tr>
            <td>${start + index}</td>
            <td>${cust.created_by_name}</td>
            <td>${cust.client_name}</td>
            <td>${cust.shop_name}</td>
            <td>${cust.name}</td>
            <td>${cust.device_type}</td>
            <td class="text-center">
              <div class="d-flex justify-content-center flex-wrap gap-2">
                ${actions}
              </div>
            </td>
          </tr>
        `;
      });

      $("#customerBody").html(rows);
    },
    error: () => {
      $("#customerBody").html(`
        <tr><td colspan="10" class="text-center text-danger">Failed to load customers.</td></tr>
      `);
    },
  });
}
function reassignCustomer() {
  const customerId = document.getElementById("modalCustomerId").value;
  const newUserId = document.getElementById("modalUserSelect").value;

  if (!newUserId) {
    Swal.fire(
      "Select User",
      "Please choose a user before reassigning.",
      "warning"
    );
    return;
  }

  const formData = new FormData();
  formData.append("customer_id", customerId);
  formData.append("new_user_id", newUserId);

  fetch(window.appConfig.reassignCustomerUrl, {
    method: "POST",
    headers: { "X-Requested-With": "XMLHttpRequest" },
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Customer Reassigned",
          html: `
            <span style="color: green;">New customer reassigned to: <b>${data.new_user_name}</b></span>
        `,
        });

        $("#reassignCustomerModal").modal("hide");
        loadCustomers();
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
}

function deleteCustomer(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "Once deleted, this customer cannot be recovered!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (!result.isConfirmed) return;
    fetch(deleteCustomerUrl + id, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire("Deleted!", data.message, "success");
          loadCustomers(); // refresh table without reloading the page
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
  });
}

loadUsers();
loadClients();
loadCustomers();
