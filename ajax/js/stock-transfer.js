jQuery(document).ready(function () {

    // DataTable config - Modified to show only office department items by default
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
                d.department_id = $('#department_id').val(); // Filter by current department
                d.search_term = $('#search_item').val();
            },
            dataSrc: function (json) {
                // Ensure the quantity is properly set from the stock_master table
                if (json.data) {
                    json.data = json.data.map(item => {
                        // If qty is not available, try to get it from available_qty or set to 0
                        if (typeof item.qty === 'undefined' && typeof item.available_qty !== 'undefined') {
                            item.qty = item.available_qty;
                        } else if (typeof item.qty === 'undefined') {
                            item.qty = 0;
                        }
                        return item;
                    });
                }
                return json.data || [];
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        // In the DataTable configuration, modify the columns definition to use the correct quantity
        columns: [
            { data: "key", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "brand", title: "Brand" },
            { data: "category", title: "Category" },
            { data: "list_price", title: "List Price" },
            { data: "invoice_price", title: "Invoice Price" },
            {
                data: "department_stock", // This should be the key that contains the department-specific quantity
                title: "Available Qty",
                render: function (data, type, row) {
                    // Find the stock for the current department
                    const departmentId = $('#department_id').val();
                    const stock = row.department_stock ? row.department_stock.find(s => s.department_id == departmentId) : null;
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

    // Search item handler with debounce
    let searchTimeout;
    $('#search_item').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            table.ajax.reload();
        }, 500);
    });

    // On row click, load selected item and close modal
    $('#datatable tbody').on('click', 'tr', function () {
        const data = table.row(this).data();
        if (!data) return;

        // Fill the form fields
        $('#item_id').val(data.id);
        $('#itemCode').val(data.code);
        $('#itemName').val(data.name);
        $('#itemQty').val(1);

        const departmentId = $('#department_id').val();
        const itemId = data.id;

        // Close the modal immediately
        var modal = bootstrap.Modal.getInstance(document.getElementById('department_stock'));
        if (modal) {
            modal.hide();
        } else {
            $('#department_stock').modal('hide');
        }

        // Focus on quantity field after a short delay
        setTimeout(() => $('#itemQty').focus(), 200);

        // Get available quantity after closing the modal
        $.ajax({
            url: 'ajax/php/stock-transfer.php',
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
    });

    // Modal focus handler
    $('#main_item_master').on('hidden.bs.modal', function () {
        if (typeof focusAfterModal !== 'undefined' && focusAfterModal) {
            $('#itemQty').focus();
            focusAfterModal = false;
        }
    });

    // Add item to table
    document.querySelector('#add_item').addEventListener('click', function () {
        const item_id = document.getElementById('item_id').value.trim();
        const itemCode = document.getElementById('itemCode').value.trim();
        const itemName = document.getElementById('itemName').value.trim();
        const itemQty = document.getElementById('itemQty').value.trim();
        const availableQty = parseInt(document.getElementById('available_qty').value.trim()) || 0;

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

        // Only validate against available quantity if it's greater than 0
        if (availableQty > 0 && parseInt(itemQty) > availableQty) {
            swal({
                title: "Error!",
                text: "Transfer quantity cannot exceed available quantity in source department!",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });
            return;
        }

        const table = document.getElementById('itemTable').querySelector('tbody');

        // Check for duplicate item
        const existingItems = table.querySelectorAll('input[name="item_codes[]"]');
        for (let i = 0; i < existingItems.length; i++) {
            if (existingItems[i].value === item_id) {
                swal({
                    title: "Duplicate Item!",
                    text: "This item has already been added.",
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
        const serial = rowCount + 1;

        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${serial}</td>
        <td><input type="hidden" name="item_codes[]" value="${item_id}">${itemCode}</td>
        <td><input type="hidden" name="item_names[]" value="${itemName}">${itemName}</td>
        <td><input type="hidden" name="item_qtys[]" value="${itemQty}">${itemQty}</td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
    `;

        table.appendChild(row);

        // Remove row logic
        row.querySelector('.remove-row').addEventListener('click', function () {
            row.remove();
            const rows = table.querySelectorAll('tr');
            if (rows.length === 0) {
                table.innerHTML = `
                <tr id="noItemRow">
                    <td colspan="5" class="text-center text-muted">No items added</td>
                </tr>`;
            } else {
                // Recalculate serial numbers
                rows.forEach((tr, index) => {
                    tr.querySelector('td').textContent = index + 1;
                });
            }
        });

        // Clear input fields
        document.getElementById('itemCode').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemQty').value = '';
        document.getElementById('available_qty').value = '0';
        document.getElementById('itemCode').focus();
    });

    // Enter key handler for quantity field
    document.getElementById('itemQty').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('add_item').click();
        }
    });

    // Remove row functionality
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Clear added items when to_department changes
    $('#to_department_id').on('change', function () {
        const table = $('#show_table');
        table.html(`
        <tr id="noItemRow">
            <td colspan="8" class="text-center text-muted">No items added</td>
        </tr>
    `);

        // Clear inputs
        $('#item_id, #itemCode, #itemName, #itemQty, #available_qty').val('');
    });

    // Create/Save stock transfer
    $('#create').on('click', function () {
        const fromDept = $('#department_id').val();
        const toDept = $('#to_department_id').val();
        const transferDate = $('#transfer_date').val();

        // Check if at least one item is added
        const hasItems = $('#itemTable tbody tr:not(#noItemRow)').length > 0;

        if (!fromDept || !toDept || !transferDate || !hasItems) {
            swal({
                title: "Error!",
                text: "Please complete all required fields and add at least one item.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        const formData = new FormData();
        formData.append('action', 'create_stock_transfer');
        formData.append('department_id', fromDept);
        formData.append('to_department_id', toDept);
        formData.append('transfer_date', transferDate);

        // Collect item data from the table
        $('#itemTable tbody tr:not(#noItemRow)').each(function () {
            const itemId = $(this).find('input[name="item_codes[]"]').val();
            const name = $(this).find('input[name="item_names[]"]').val();
            const qty = $(this).find('input[name="item_qtys[]"]').val();

            formData.append('item_codes[]', itemId);
            formData.append('item_names[]', name);
            formData.append('item_qtys[]', qty);
        });

        // Start preloader
        $(".someBlock").preloader();

        $.ajax({
            url: 'ajax/php/stock-transfer.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Stop preloader
                $(".someBlock").preloader("remove");

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
                        text: "Stock transfer saved successfully.",
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
                        text: res.message || "Failed to save stock transfer.",
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