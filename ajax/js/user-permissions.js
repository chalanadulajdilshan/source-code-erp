// Handle user selection change
$('#selectUser').on('change', function () {
    const userId = $(this).val();
    const $specialPermissionBtn = $('#BtnSpecialPermissionModal');

    if (userId) {
        $specialPermissionBtn.show();
        fetchPermissions(userId);
        loadSpecialPermissions(userId);
    } else {
        $specialPermissionBtn.hide();
        $('#permissionsTable').hide();
        $('#permissionsTableBody').empty();
        $('#special_permissionTableBody').empty();
    }
});

// Initially hide the special permission button on page load
$(document).ready(function () {
    $('#BtnSpecialPermissionModal').hide();
});

// Fetch and display regular permissions
function fetchPermissions(userId) {
    $('.someBlock').preloader();

    $.ajax({
        url: 'ajax/php/get-permissions.php',
        method: 'GET',
        data: { userId: userId },
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
            $('.someBlock').preloader('remove');
            console.error('Error fetching permissions:', error);
            swal({
                title: "Error!",
                text: "Failed to load permissions.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Save regular permissions
$('#create').on('click', function (e) {
    e.preventDefault();

    // Check if a user is selected
    const selectedUser = $('#selectUser').val();
    if (!selectedUser) {
        swal({
            title: "Error!",
            text: "Please select a user first.",
            type: 'error',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

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
                    text: response.message || "Something went wrong.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function () {
            $('.someBlock').preloader('remove');
            swal("Error", "Something went wrong while saving permissions.", "error");
        }
    });
});


// Load special permissions for the selected user
function loadSpecialPermissions(userId) {
    if (!userId) return;

    $.ajax({
        url: 'ajax/php/special-user-permission.php',
        method: 'GET',
        data: { userId: userId },
        dataType: 'json',
        success: function (response) {
            const tbody = $('#special_permissionTableBody');
            tbody.empty();

            if (response.status === 'success' && response.data && response.data.length > 0) {
                response.data.forEach(permission => {
                    const row = `
                        <tr data-permission-id="${permission.id}">
                            <td>${permission.id}</td>
                            <td>${permission.permission_name}</td>
                            <td class="text-center">
                                <div class="form-check form-switch form-switch-md">
                                    <input type="checkbox" class="form-check-input status-toggle" 
                                        data-permission-id="${permission.id}"
                                        ${permission.status === 'active' ? 'checked' : ''}>
                                    <label class="form-check-label"></label>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="3" class="text-center">No special permissions found</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading special permissions:', error);
            $('#special_permissionTableBody').html('<tr><td colspan="3" class="text-center text-danger">Error loading special permissions</td></tr>');
        }
    });
}

// Handle status toggle for special permissions
$(document).on('change', '.status-toggle', function () {
    const permissionId = $(this).data('permission-id');
    const isActive = $(this).is(':checked') ? 'active' : 'inactive';

    $('.someBlock').preloader();

    $.ajax({
        url: 'ajax/php/special-user-permission.php',
        method: 'POST',
        data: {
            action: 'update_status',
            permission_id: permissionId,
            status: isActive
        },
        dataType: 'json',
        success: function (response) {
            $('.someBlock').preloader('remove');
            if (response.status !== 'success') {
                swal('Error', response.message || 'Failed to update permission status', 'error');
                // Revert the toggle on error
                $(`[data-permission-id="${permissionId}"] .status-toggle`).prop('checked', !$(this).is(':checked'));
            }
        },
        error: function () {
            $('.someBlock').preloader('remove');
            swal('Error', 'An error occurred while updating permission status', 'error');
            // Revert the toggle on error
            $(`[data-permission-id="${permissionId}"] .status-toggle`).prop('checked', !$(this).is(':checked'));
        }
    });
});

// Handle add new special permission
$(document).on('click', '#add_special_permission', function () {
    const permissionName = $('#newPermissionName').val().trim();
    const status = $('#newPermissionStatus').val();
    const selectedUser = $('#selectUser').val();

    if (!permissionName) {
        swal('Error', 'Please enter a permission name', 'error');
        return;
    }

    if (!selectedUser) {
        swal({
            title: "Error!",
            text: "Please select a user first.",
            type: 'error',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    $('.someBlock').preloader();

    $.ajax({
        url: 'ajax/php/special-user-permission.php',
        method: 'POST',
        data: {
            user_id: selectedUser,
            permission_name: permissionName,
            status: status
        },
        dataType: 'json',
        success: function (response) {
            $('.someBlock').preloader('remove');
            if (response.status === 'success') {
                $('#newPermissionName').val('');
                loadSpecialPermissions(selectedUser);
                swal('Success', 'Special permission added successfully', 'success');
            } else {
                swal('Error', response.message || 'Failed to add permission', 'error');
            }
        },
        error: function () {
            $('.someBlock').preloader('remove');
            swal('Error', 'An error occurred while processing your request', 'error');
        }
    });
});