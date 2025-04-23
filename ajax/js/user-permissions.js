$('#userType').on('change', function () {
    const userTypeId = $(this).val();

    // Fetch permissions using AJAX
    fetchPermissions(userTypeId);
});

function fetchPermissions(userTypeId) {
    $('.someBlock').preloader();

    $.ajax({
        url: 'ajax/php/get-permissions.php',
        method: 'GET',
        data: { userTypeId: userTypeId },
        dataType: 'json',
        success: function (data) {
            $('.someBlock').preloader('remove');
            
            const tableBody = $('#permissionsTableBody');
            tableBody.empty(); // Clear the table body
            $('#permissionsTable').show();
            // Loop through each page and generate the table rows dynamically
            $.each(data.pages, function (index, page) {
                const row = `
                <tr> 
                <td>${index + 1}</td>
                <td>${page.pageCategory}</td>
                <td>${page.pageName}</td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="1" ${page.permissions.add ? 'checked' : ''}></td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="2" ${page.permissions.edit ? 'checked' : ''}></td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="3" ${page.permissions.delete ? 'checked' : ''}></td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="4" ${page.permissions.view ? 'checked' : ''}></td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="5" ${page.permissions.print ? 'checked' : ''}></td>
                <td><input type="checkbox" name="permissions[${page.pageId}][]" value="6" ${page.permissions.others ? 'checked' : ''}></td>
                 </tr>

                `;
                tableBody.append(row);
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching permissions:', error);
        }
    });
}

$('#create').on('click', function (e) {
    e.preventDefault();

    $('.someBlock').preloader();

    $.ajax({
        url: 'ajax/php/user-permissions.php',
        type: 'POST',
        data: $('#permissionsForm').serialize(),
        dataType: 'json',
        success: function (response) {

            $('.someBlock').preloader('remove');

            if (response.status === 'success') {
                swal({
                    title: "Success!",
                    text: "user permission added successfully!",
                    type: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                window.setTimeout(function () {
                    window.location.reload();
                }, 2000);

            } else {
                swal({
                    title: "Error!",
                    text: "Something went wrong.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function (xhr) {
            swal("Error", "Something went wrong while saving permissions.", "error");
        }
    });
});

