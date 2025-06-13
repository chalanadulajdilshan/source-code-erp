$('#userType').on('change', function () {
    const userTypeId = $(this).val();
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
            tableBody.empty();
            $('#permissionsTable').show();

            $.each(data.pages, function (index, page) {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${page.pageCategory}</td>
                        <td>${page.pageName}</td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][add]" ${page.add_page == 1 ? 'checked' : ''}></td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][edit]" ${page.edit_page == 1 ? 'checked' : ''}></td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][search]" ${page.search_page == 1 ? 'checked' : ''}></td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][delete]" ${page.delete_page == 1 ? 'checked' : ''}></td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][print]" ${page.print_page == 1 ? 'checked' : ''}></td>
                        <td><input type="checkbox" name="permissions[${page.pageId}][other]" ${page.other_page == 1 ? 'checked' : ''}></td>
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
                    text: "User permissions updated successfully!",
                    type: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                setTimeout(() => window.location.reload(), 2000);

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
        error: function () {
            swal("Error", "Something went wrong while saving permissions.", "error");
        }
    });
});
