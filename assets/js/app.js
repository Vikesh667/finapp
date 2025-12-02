const listDataUrl = window.appConfig.listDataUrl;
const logoUrl = window.appConfig.logoUrl;
const editUrl = window.appConfig.editUrl;
const deleteUserUrl = window.appConfig.deleteUserUrl;
const clientListDataUrl = window.appConfig.clientListDataUrl;
const logoUrlc = window.appConfig.logoUrl;
const adminEditClientUrl = window.appConfig.adminEditClientUrl;
const userEditClientUrl = window.appConfig.userEditClientUrl;
const adminDeleteClientUrl = window.appConfig.adminDeleteClientUrl;
const userDeleteClientUrl = window.appConfig.userDeleteClientUrl;
const viewClientProductUrl = window.appConfig.viewClientProductUrl;
const isAdmin = Number(window.appConfig.isAdmin);

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
  // show spinner inside tbody only
  $("#clientBody").html(`
        <tr>
            <td colspan="8" class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Loading clients...</p>
            </td>
        </tr>
    `);

  $.ajax({
    url: clientListDataUrl, // from window.appConfig
    method: "GET",
    dataType: "json",
    success: function (response) {
      let rows = "";
      let start = 1;

      response.clients.forEach((client, index) => {
        let editUrl =
          client.role === "admin"
            ? `${adminEditClientUrl}${client.id}`
            : `${userEditClientUrl}${client.id}`;

        let deleteUrl =
          client.role === "admin"
            ? `${adminDeleteClientUrl}${client.id}`
            : `${userDeleteClientUrl}${client.id}`;

        rows += `
                <tr>
                    <td>${start + index}</td>
                    <td>${client.name}</td>
                    <td>${client.email}</td>
                    <td>${client.company_name ?? "-"}</td>
                    <td><a href="${client.url}" target="_blank">${
          client.url
        }</a></td>
                    <td><img src="${logoUrlc}${client.logo}" width="50"></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center flex-wrap gap-2">

                            <a href="${editUrl}" class="btn btn-sm btn-outline-primary rounded-circle action-btn" title="Edit Client">
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

                            <a href="${viewClientProductUrl}${client.id}"
                                class="btn btn-sm btn-warning rounded-pill px-3"
                                title="View Client Products">
                                View
                            </a>

                        </div>
                    </td>
                </tr>`;
      });

      $("#clientBody").html(rows);
    },
  });
}

loadUsers();
loadClients();
