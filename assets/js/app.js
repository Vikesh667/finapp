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

let page = 1;
let search = "";
let clientPage = 1;
let clientSearch = "";

function loadUsers() {
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
    data: { page, search },
    dataType: "json",

    success: function (response) {
      let rows = "";
      let start = (response.current_page - 1) * response.per_page + 1;

      if (response.users.length === 0) {
        $("#userBody").html(`
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    No data found
                </td>
            </tr>
        `);
        $("#pagination").html("");
        return;
      }

      response.users.forEach((user, index) => {
        rows += `
          <tr>
            <td>${start + index}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.phone ?? "-"}</td>
            <td>${user.address ?? "-"}</td>
            <td><img src="${logoUrl}${user.profile_image}" width="50"></td>
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

      // PAGINATION WITH FIRST, PREVIOUS, NUMBERS, NEXT, LAST
      let pag = `<div class="d-flex justify-content-center flex-wrap gap-1">`;

      // First
      if (page > 1) {
        pag += `
          <button class="btn btn-sm btn-outline-primary"
            onclick="changePage(1)">First</button>`;
      }

      // Previous
      if (page > 1) {
        pag += `
          <button class="btn btn-sm btn-outline-primary"
            onclick="changePage(${page - 1})">Previous</button>`;
      }

      // Page numbers
      for (let i = 1; i <= response.total_pages; i++) {
        pag += `
          <button class="btn btn-sm ${
            i == page ? "btn-primary" : "btn-outline-primary"
          }"
            onclick="changePage(${i})">${i}</button>`;
      }

      // Next
      if (page < response.total_pages) {
        pag += `
          <button class="btn btn-sm btn-outline-primary"
            onclick="changePage(${page + 1})">Next</button>`;
      }

      // Last
      if (page < response.total_pages) {
        pag += `
          <button class="btn btn-sm btn-outline-primary"
            onclick="changePage(${response.total_pages})">Last</button>`;
      }

      pag += `</div>`;
      $("#pagination").html(pag);
    },
  });
}

function changePage(p) {
  page = p;
  loadUsers();
}

$("#searchInput").on("keyup", function () {
  search = $(this).val();
  page = 1;
  loadUsers();
});

$(document).ready(function () {
  loadUsers();
});

function deleteUser(id) {
  Swal.fire({
    toast: true,
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
            toast: true,
            position: "top",
            icon: "success",
            title: "Deleted!",
            text: result.message,
            timer: 2000,
            showConfirmButton: false,
          });
          loadUsers();
        } else {
          Swal.fire({
            toast: true,
            position: "top",
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
    data: { page: clientPage, search: clientSearch },
    dataType: "json",
    success: function (response) {
      let rows = "";
      let start = (response.current_page - 1) * response.per_page + 1;

      if (response.clients.length === 0) {
        $("#clientBody").html(`
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">
              No data found
            </td>
          </tr>
        `);
        $("#clientPagination").html("");
        return;
      }

      response.clients.forEach((client, index) => {
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
                <a href="${viewClientProductUrl}${
          client.id
        }" class="btn btn-sm btn-warning rounded-pill px-3">
                  View
                </a>

                <a href="${editClientUrl}${
          client.id
        }" class="btn btn-sm btn-outline-primary rounded-circle action-btn">
                  <ion-icon name="create-outline"></ion-icon>
                </a>

                <button onclick="deleteClient(${
                  client.id
                })" class="btn btn-sm btn-outline-danger rounded-circle action-btn">
                  <ion-icon name="trash-outline"></ion-icon>
                </button>

                <button class="btn btn-sm btn-outline-secondary rounded-pill d-flex align-items-center gap-1 px-3"
                  data-bs-toggle="modal" data-bs-target="#userModal" data-client-id="${
                    client.id
                  }">
                  <i class="bi bi-person-plus"></i> Assign
                </button>
              </div>
            </td>
          </tr>
        `;
      });

      $("#clientBody").html(rows);

      /* PAGINATION — FIRST / PREVIOUS / NUMBERS / NEXT / LAST */
      let pag = `<div class="d-flex justify-content-center flex-wrap gap-1">`;

      // First
      if (clientPage > 1) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeClientPage(1)">First</button>`;
      }

      // Previous
      if (clientPage > 1) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeClientPage(${
          clientPage - 1
        })">Previous</button>`;
      }

      // Page numbers
      for (let i = 1; i <= response.total_pages; i++) {
        pag += `
          <button class="btn btn-sm ${
            i == clientPage ? "btn-primary" : "btn-outline-primary"
          }"
            onclick="changeClientPage(${i})">${i}</button>`;
      }

      // Next
      if (clientPage < response.total_pages) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeClientPage(${
          clientPage + 1
        })">Next</button>`;
      }

      // Last
      if (clientPage < response.total_pages) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeClientPage(${response.total_pages})">Last</button>`;
      }

      pag += `</div>`;
      $("#clientPagination").html(pag);
    },
  });
}

$("#clientSearchInput").on("keyup", function () {
  clientSearch = $(this).val();   
  clientPage = 1;                 
  loadClients();                 
});
function changeClientPage(p) {
  clientPage = p;
  loadClients();
}

$(document).ready(function () {
  loadClients();
});

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
          Swal.fire({
            toast: true,
            position: "top",
            icon: "success",
            title: `Client deleted successfully`,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });
          loadClients(); // refresh table without reloading the page
        } else {
          Swal.fire({
            toast: true,
            position: "top",
            icon: "error",
            title: data.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });
        }
      })
      .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
  });
}
let customerPage = 1;   // current page number
let customerSearch = ""; // search keyword (optional)

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
  customerSearch = $("#searchCustomer").val();

  $.ajax({
    url: customerListDataUrl,
    method: "GET",
    dataType: "json",
    data: {
      page: customerPage,           // pagination
      search: customerSearch || "", // search
      client_id: client || "",      // filter
      service_id: service || "",    // filter
    },
    success: function (response) {
      let rows = "";
      let customers = response.customers;
      let start = (response.current_page - 1) * response.per_page + 1;

      if (customers.length === 0) {
        $("#customerBody").html(`
          <tr>
            <td colspan="10" class="text-center py-4 text-muted">
              No customers found
            </td>
          </tr>
        `);
        $("#customerPagination").html("");
        return;
      }

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

          <button onclick="deleteCustomer(${cust.id})"
            class="btn btn-sm btn-outline-danger rounded-circle"
            title="Delete Customer">
            <ion-icon name="trash-outline"></ion-icon>
          </button>
        `;

        if (isAdmin === 1) {
          actions += `
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

      // ------- PAGINATION -------
      let pag = `<div class="d-flex justify-content-center flex-wrap gap-1">`;

      // First
      if (customerPage > 1) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeCustomerPage(1)">First</button>`;
      }

      // Previous
      if (customerPage > 1) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeCustomerPage(${customerPage - 1})">Previous</button>`;
      }

      // Page numbers
      for (let i = 1; i <= response.total_pages; i++) {
        pag += `
          <button class="btn btn-sm ${i == customerPage ? 'btn-primary' : 'btn-outline-primary'}"
            onclick="changeCustomerPage(${i})">${i}</button>`;
      }

      // Next
      if (customerPage < response.total_pages) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeCustomerPage(${customerPage + 1})">Next</button>`;
      }

      // Last
      if (customerPage < response.total_pages) {
        pag += `<button class="btn btn-sm btn-outline-primary" onclick="changeCustomerPage(${response.total_pages})">Last</button>`;
      }

      pag += `</div>`;
      $("#customerPagination").html(pag);
    },
    error: () => {
      $("#customerBody").html(`
        <tr><td colspan="10" class="text-center text-danger">Failed to load customers.</td></tr>
      `);
    },
  });
}
function changeCustomerPage(p) {
  customerPage = p;
  loadCustomers();
}
$("#searchCustomer").on("keyup", function () {
  customerSearch = $(this).val();
  customerPage = 1; // reset on search
  loadCustomers();
});

$("#filterClient, #filterService").on("change", function () {
  customerPage = 1;
  loadCustomers();
});

$(document).ready(function () {
  loadCustomers();
});

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
          toast: true,
          position: "top",
          icon: "success",
          title: `Customer reassigned to ${data.new_user_name}`,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
        });

        $("#reassignCustomerModal").modal("hide");
        loadCustomers();
      } else {
        Swal.fire({
          toast: true,
          position: "top-center",
          icon: "error",
          title: data.message,
          showConfirmButton: false,
          timer: 3000,
        });
      }
    })
    .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
}

function deleteCustomer(id) {
  console.log("Deleting customer with ID:", id);
  Swal.fire({
    toast: true,
    position: "top-center",
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
        console.log("Delete response data:", data);
        if (data.status === "success") {
          Swal.fire({
            toast: true,
            position: "top",
            icon: "success",
            title: `Customer deleted successfully`,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });
          loadCustomers(); // refresh table without reloading the page
        } else {
          Swal.fire({
            toast: true,
            position: "top",
            icon: "error",
            title: data.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });
        }
      })
      .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
  });
}

function loadDeleteHistory() {
  $("#deleteHistoryBody").html(`
    <tr>  
      <td colspan="6" class="text-center py-4">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2">Loading deleted customers...</p>
      </td>
    </tr>
  `);
  $.ajax({
    url: window.appConfig.customerDeleteHistoryUrl,
    method: "GET",
    dataType: "json",
    success: function (response) {
      let rows = "";
      let start = 1;
      response.deleted_customers.forEach((cust, index) => {
        console.log("Deleted customer:", cust);
        rows += `
          <tr>
            <td>${start + index}</td>
            <td>${cust.client_names}</td>
            <td>${cust.name}</td>
            <td>${cust.email}</td>
            <td>${cust.shop_name}</td>
            <td>${cust.deleted_at}</td>
            <td>${cust.deleted_by_name}</td>
          </tr>
        `;
      });

      $("#deleteHistoryBody").html(rows);
    },
    error: () => {
      $("#deleteHistoryBody").html(`
        <tr><td colspan="6" class="text-center text-danger">Failed to load deleted customers.</td></tr>
      `);
    },
  });
}
loadUsers();
loadClients();
loadCustomers();
loadDeleteHistory();
