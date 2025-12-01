const listDataUrl = window.appConfig.listDataUrl;
const logoUrl     = window.appConfig.logoUrl;
const editUrl     = window.appConfig.editUrl;


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
        success: function(response) {

            let rows = "";
            let start = 1;

            response.users.forEach((user, index) => {
                rows += `
                <tr>
                    <td>${start + index}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone ?? '-'}</td>
                    <td>${user.address ?? '-'}</td>
                    <td><img src="${logoUrl}${user.profile_image}" width="50"></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="${editUrl}${user.id}" class="btn btn-sm btn-outline-primary rounded-circle">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <button onclick="deleteUser(${user.id})" class="btn btn-sm btn-outline-danger rounded-circle">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });

            $("#userBody").html(rows);
        }
    });
}

loadUsers();
