jQuery(document).ready(function () {
    // Load pages into table when modal opens
    function loadPagesTable() {
        $.getJSON('ajax/php/non_permission_pages.php?action=list', function (data) {
            let tbody = '';
            $.each(data, function (i, row) {
                tbody += `<tr>
                    <td>${i + 1}</td>
                    <td>${row.page}</td>
                    <td><input type="checkbox" disabled ${row.is_active == 1 ? 'checked' : ''}></td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-page" 
                            data-id="${row.id}" 
                            data-page="${row.page}" 
                            data-is_active="${row.is_active}">
                            Edit
                        </button>
                    </td>
                </tr>`;
            });
            $('#pagesTable tbody').html(tbody);
        });
    }

    // Open modal for adding new page
    $("#newPageBtn").click(function (e) {
        e.preventDefault();
        $("#nonPermissionForm")[0].reset();
        $("#page_id").val('');
        $("#saveBtn").show();
        $("#updateBtn").hide();
        $('#non-permissionModal').modal('show');
    });

    // Load items when modal is shown
    $('#non-permissionModal').on('show.bs.modal', function () {
        loadPagesTable();
    });

    // Save new page
    $('#nonPermissionForm').on('submit', function (e) {
        e.preventDefault();
        let page = $('#page').val().trim();
        let is_active = $('#is_active').is(':checked') ? 1 : 0;

        if (page.length === 0) {
            swal("Error!", "Please enter a Page Name", "error");
            return;
        }

        $.post('ajax/php/non_permission_pages.php?action=add', { page, is_active }, function (result) {
            if (result.status === 'success') {
                swal("Success!", "Page added successfully!", "success");
                $('#nonPermissionForm')[0].reset();
                loadPagesTable();
            } else {
                swal("Error!", "Something went wrong.", "error");
            }
        }, 'json');
    });

    // Open edit form
    $(document).on('click', '.edit-page', function () {
        let id = $(this).data('id');
        let page = $(this).data('page');
        let is_active = $(this).data('is_active');

        $('#page_id').val(id);
        $('#page').val(page);
        $('#is_active').prop('checked', is_active == 1);

        $("#saveBtn").hide();
        $("#updateBtn").show();
        $('#non-permissionModal').modal('show');
    });

    // Update page
    $('#updateBtn').click(function (e) {
        e.preventDefault();
        let id = $('#page_id').val();
        let page = $('#page').val().trim();
        let is_active = $('#is_active').is(':checked') ? 1 : 0;

        if (page.length === 0) {
            swal("Error!", "Please enter a Page Name", "error");
            return;
        }

        $.post('ajax/php/non_permission_pages.php?action=update', { id, page, is_active }, function (result) {
            if (result.status === 'success') {
                swal("Success!", "Page updated successfully!", "success");
                $('#nonPermissionForm')[0].reset();
                $("#saveBtn").show();
                $("#updateBtn").hide();
                loadPagesTable();
            } else {
                swal("Error!", "Something went wrong.", "error");
            }
        }, 'json');
    });

});
