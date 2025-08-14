jQuery(document).ready(function () {
    // DataTable config
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "ajax/php/item-master.php",
            type: "POST",
            data: function (d) {
                d.filter = true;
                d.status = 1;
                d.stock_only = 1;
                d.department_id = $('#filter_department_id').val();
                d.search_term = $('#search_item').val();
            },
            dataSrc: function (json) {
                return json.data;
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        columns: [
            { data: "key", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "brand", title: "Brand" },
            { data: "category", title: "Category" },
            { data: "list_price", title: "List Price" },
            { data: "invoice_price", title: "Invoice Price" },
            {
                data: "department_stock",
                title: "Available Qty",
                render: function (data, type, row) {
                    // Get the selected department ID
                    const departmentId = $('#filter_department_id').val();
                    // Find the stock for the current department
                    const stock = data ? data.find(s => s.department_id == departmentId) : null;
                    return stock ? parseInt(stock.quantity) : 0;
                }
            },
            { data: "discount", title: "Discount %" },
            {
                data: "status_label",
                title: "Status",
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });

    // Department filter change handler
    $('#filter_department_id').on('change', function () {
        table.ajax.reload();
    });

    // Search item handler with debounce
    let searchTimeout;
    $('#search_item').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            table.ajax.reload();
        }, 500);
    });

    // On row click, load selected item into input fields
    $('#datatable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        $('#item_id').val(data.id);
        $('#itemCode').val(data.code);
        $('#itemName').val(data.name);
        $('#itemQty').val(1);

        const departmentId = $('#department_id').val();
        const itemId = data.id;

        $.ajax({
            url: 'ajax/php/stock-adjustment.php',
            method: 'POST',
            data: {
                action: 'get_available_qty',
                department_id: departmentId,
                item_id: itemId
            },
            success: function (res) {
                if (res.status === 'success') {
                    $('#available_qty').val(res.available_qty);
                } else {
                    $('#available_qty').val(0);
                    swal({
                        title: "Error!",
                        text: res.message || "Failed to load available quantity.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function () {
                $('#available_qty').val(0);
                swal({
                    title: "Error!",
                    text: "Could not load available quantity.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });

        setTimeout(() => $('#itemQty').focus(), 200);
        $('#department_stock').modal('hide');
    });

    // Rest of your existing code...
    $('#department_stock').on('hidden.bs.modal', function () {
        if (focusAfterModal) {
            $('#itemQty').focus();
            focusAfterModal = false;
        }
    });


    //remove all added items department change
    $('#department_id').on('change', function () {
        const table = $('#show_table');
        table.html(`
        <tr id="noItemRow">
            <td colspan="8" class="text-center text-muted">No items added</td>
        </tr>
    `);

        // Clear inputs
        $('#item_id, #itemCode, #itemName, #itemQty, #available_qty').val('');
    });


    document.querySelector('#add_item').addEventListener('click', function () {
        const item_id = document.getElementById('item_id').value.trim();
        const itemCode = document.getElementById('itemCode').value.trim();
        const itemName = document.getElementById('itemName').value.trim();
        const itemQty = document.getElementById('itemQty').value.trim();

        if (!itemCode || !itemName || !itemQty || parseInt(itemQty) <= 0) {
            swal({
                title: "Error!",
                text: "Please enter valid item code, name, and quantity",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });
            return;
        }

        const table = document.getElementById('show_table');

        const existingItems = table.querySelectorAll('input[name="item_codes[]"]');
        for (const input of existingItems) {
            if (input.value === item_id) {
                swal({
                    title: "Duplicate!",
                    text: "This item is already added to the table.",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                });
                return;
            }
        }

        // Remove "No items added" row if it exists
        const noItemRow = document.getElementById('noItemRow');
        if (noItemRow) {
            noItemRow.remove();
        }

        const rowCount = table.querySelectorAll('tr').length;
        const serialKey = rowCount + 1;

        const row = document.createElement('tr');
        row.innerHTML = `
             <td> ${serialKey}</td>
    <td><input type="hidden" name="item_codes[]" value="${item_id}">${itemCode}</td>
    <td><input type="hidden" name="item_names[]" value="${itemName}">${itemName}</td>
    <td><input type="hidden" name="item_qtys[]" value="${itemQty}">${itemQty}</td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
`;

        table.appendChild(row);
        row.querySelector('.remove-row').addEventListener('click', function () {
            row.remove();
            const rows = table.querySelectorAll('tr');
            if (rows.length === 0) {
                table.innerHTML = `
            <tr id="noItemRow">
                <td colspan="8" class="text-center text-muted">No items added</td>
            </tr>`;
            } else {
                // Re-index the serial keys
                rows.forEach((tr, index) => {
                    tr.querySelector('td').textContent = index + 1;
                });
            }
        });
        // Clear input fields
        document.getElementById('itemCode').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemQty').value = '';
        document.getElementById('itemCode').focus();

    });

    document.getElementById('itemQty').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission if inside a form
            document.getElementById('add_item').click(); // Trigger the same logic
        }
    });

    $('#create').on('click', function () {

        const departmentId = $('#department_id').val();
        const adjustmentType = $('input[name="adjustment_type"]:checked').val();
        const specialInstructions = $('#special_instructions').val();

        const hasItems = $('#itemTable tbody tr:not(#noItemRow)').length > 0;

        if (!departmentId || !adjustmentType || !hasItems) {
            swal({
                title: "Error!",
                text: "Please complete all required fields and add at least one item.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }
        if (!specialInstructions) {
            swal({
                title: "Error!",
                text: "Please enter special instructions.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }
        const formData = new FormData();
        formData.append('action', 'create_stock_adjustment');
        formData.append('department_id', departmentId);
        formData.append('adjustment_type', adjustmentType);
        formData.append('special_instructions', specialInstructions);

        // Append item data
        $('#itemTable tbody tr:not(#noItemRow)').each(function () {
            const itemId = $(this).find('input[name="item_codes[]"]').val(); // hidden input
            const code = $(this).find('td:eq(1)').text().trim();
            const name = $(this).find('td:eq(2)').text().trim();
            const qty = $(this).find('td:eq(3)').text().trim();

            formData.append('item_ids[]', itemId);
            formData.append('item_codes[]', code);
            formData.append('item_names[]', name);
            formData.append('item_qtys[]', qty);
        });

        $(".someBlock").preloader(); // start loader

        $.ajax({
            url: 'ajax/php/stock-adjustment.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $(".someBlock").preloader("remove"); // stop loader

                let res = {};
                try {
                    res = typeof response === 'object' ? response : JSON.parse(response);
                } catch (e) {
                    console.error('Invalid JSON:', response);
                    swal({
                        title: "Error!",
                        text: "Server returned an invalid response.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                if (res.status === 'success') {
                    swal({
                        title: "Success!",
                        text: "Stock adjustment saved successfully.",
                        type: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    swal({
                        title: "Error!",
                        text: res.message || "Failed to save stock adjustment.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function () {
                $(".someBlock").preloader("remove");
                swal({
                    title: "Error!",
                    text: "An unexpected error occurred while saving.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });
});