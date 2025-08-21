jQuery(document).ready(function () {

    $('#brand').on('change', function () {
        table.ajax.reload();
    });

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
                d.stock_only = 0;
                d.brand = $('#brand').val();
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
            { data: "invoice_price", title: "Sales Price" },
            { data: "qty", title: "Quantity" },
            { data: "discount", title: "Discount %" },
            { data: "status_label", title: "Status" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });

    // On row click, load selected item into input fields
    $('#datatable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        $('#item_id').val(data.id);
        $('#itemCode').val(data.code);
        $('#qty').val(1);
        $('#rate').val(data.invoice_price);
        $('#available_qty').val(data.id);

        calculatePayment();

        setTimeout(() => $('#qty').focus(), 200);

        $('#main_item_master').modal('hide');
    });


    $('#item_master').on('hidden.bs.modal', function () {
        if (focusAfterModal) {
            $('#qty').focus();
            focusAfterModal = false;
        }
    });

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $('#category').prop('selectedIndex', 0); // Optional, if using dropdowns
        $("#create").show();
    });


    //////////////////////////item add///////////////////////

    function addItem() {

        const item_id = $('#item_id').val().trim();
        const code = $('#itemCode').val().trim();
        const qty = parseFloat($('#qty').val()) || 0;
        const rate = parseFloat($('#rate').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;

        if (!code || qty <= 0 || rate <= 0) {
            swal({
                title: "Validation Error!",
                text: "Please enter valid item code, quantity, and rate.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        let duplicate = false;
        $('#purchaseOrderBody tr').each(function () {
            const existingCode = $(this).find('td:first').text().trim();
            if (existingCode === code) {
                duplicate = true;
                return false; // Break loop
            }
        });

        if (duplicate) {
            swal({
                title: "Duplicate Item!",
                text: `Item "${code}" is already added.`,
                type: 'warning',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        const subtotal = qty * rate;
        const discountAmt = subtotal * (discount / 100);
        const total = subtotal - discountAmt;

        $('#noItemRow').remove();

        const row = `
        <tr data-item-id="${item_id}">
            <td>${code}</td>
            <td>${qty}</td>
            <td>${rate.toFixed(2)}</td>
            <td>${total.toFixed(2)}</td>
             <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;

        $('#purchaseOrderBody').append(row);

        $('#itemCode, #qty, #rate, #itemDiscount, #itemPayment').val('');
        updateFinalTotal();
    }

    // Remove item row
    function removeRow(button) {
        $(button).closest('tr').remove();
        updateFinalTotal();
    }
    // Global function to remove row
    let deletedItems = [];

    window.removeRow = function (button) {
        const $row = $(button).closest('tr');

        // Get hidden item_id (if exists)
        const itemId = $row.find('input.item-id').val();
        if (itemId) {
            deletedItems.push(itemId);
        }

        $row.remove();

        if ($('#purchaseOrderBody tr').length === 0) {
            $('#purchaseOrderBody').append(`
            <tr id="noItemRow">
                <td colspan="5" class="text-center text-muted">No items added</td>
            </tr>
        `);
        }

        updateFinalTotal();
    };


    function updateFinalTotal() {
        let subTotal = 0;
        let discountTotal = 0;

        $('#purchaseOrderBody tr').each(function () {
            const qty = parseFloat($(this).find('td:eq(1)').text()) || 0;
            const rate = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const totalAmount = parseFloat($(this).find('td:eq(3)').text()) || 0;

            subTotal += qty * rate;
            discountTotal += (qty * rate) - totalAmount;
        });

        const grandTotal = subTotal - discountTotal;

        $('#finalTotal').val(subTotal.toFixed(2));
        $('#disTotal').val(discountTotal.toFixed(2));
        $('#grandTotal').val(grandTotal.toFixed(2));
    }

    function calculatePayment() {
        const rate = parseFloat($('#rate').val()) || 0;
        const qty = parseFloat($('#qty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;

        const subtotal = rate * qty;
        const discountedAmount = subtotal * (discount / 100);
        const total = subtotal - discountedAmount;

        $('#itemPayment').val(total.toFixed(2));
    }

    $('#qty, #rate, #itemDiscount').on('input', calculatePayment);
    $('#addItemBtn').click(addItem);

    $('#itemCode, #qty, #rate, #itemDiscount').on('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addItem();
        }
    });

    //create purchase order
    $('#create').click(function (e) {
        e.preventDefault();

        const supplierCode = $('#supplier_code').val().trim();
        const supplier_id = $('#supplier_id').val().trim();
        const supplierName = $('#supplier_name').val().trim();
        const poId = $('#po_no').val().trim();

        if (!supplierCode || !supplierName) {
            swal({
                title: "Error!",
                text: "Please select the supplier.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!$('#order_date').val()) {
            swal({
                title: "Error!",
                text: "Please select a date.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!poId) {
            swal({
                title: "Error!",
                text: "Purchase Order No. cannot be blank.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        // Check duplicate PO ID
        $.ajax({
            url: 'ajax/php/purchase-order.php',
            method: 'POST',
            data: {
                action: 'check_po_id',
                po_no: poId
            },
            dataType: 'json',
            success: function (checkResponse) {
                if (checkResponse.exists) {
                    swal({
                        title: "Duplicate!",
                        text: "Purchase Order No <strong>" + poId + "</strong> already exists.",
                        type: 'error',
                        html: true,
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                const items = [];
                let hasInvalidItem = false;

                $('#purchaseOrderBody tr').each(function () {
                    if ($(this).attr('id') === 'noItemRow') return;
                    const itemId = $(this).data('item-id');
                    const itemCode = $(this).find('td:eq(0)').text().trim();
                    const qty = parseFloat($(this).find('td:eq(1)').text()) || 0;
                    const itemPrice = parseFloat($(this).find('td:eq(2)').text()) || 0;
                    const itemTotal = parseFloat($(this).find('td:eq(3)').text()) || 0;

                    if (!itemCode || itemPrice <= 0 || qty <= 0) {
                        hasInvalidItem = true;
                        return false;
                    }

                    items.push({
                        item_id: itemId,
                        code: itemCode,
                        price: itemPrice,
                        qty: qty,
                        total: itemTotal
                    });
                });

                if (hasInvalidItem) {
                    swal({
                        title: "Error!",
                        text: "Please ensure all items are filled correctly!",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                if (items.length === 0) {
                    swal({
                        title: "Error!",
                        text: "Please add items to the purchase order.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                const finalTotal = parseFloat($('#finalTotal').val()) || 0;

                const poData = {
                    action: 'create_purchase_order',
                    po_id: poId,
                    supplier_id: supplier_id,
                    brand: $('#brand').val(),
                    invoice_no: $('#invoice_no').val(),
                    country: $('#country').val(),
                    purchase_date: $('#purchase_date').val(),
                    date: $('#order_date').val(),
                    department: $('#department_id').val(),
                    purchase_date: $('#purchase_date').val(),
                    remarks: $('#remark').val(),
                    grand_total: finalTotal,
                    items: JSON.stringify(items)
                };

                $.ajax({
                    url: 'ajax/php/purchase-order.php',
                    method: 'POST',
                    data: poData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            swal({
                                title: "Success!",
                                text: "Purchase Order created successfully!",
                                type: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });
                            setTimeout(function () {
                                window.location.reload();
                            }, 2500);
                        } else {
                            swal({
                                title: "Error!",
                                text: response.message || "Error creating purchase order.",
                                type: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function () {
                        swal({
                            title: "Error!",
                            text: "AJAX request failed. Please try again.",
                            type: 'error',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                });

            },
            error: function () {
                swal({
                    title: "Error!",
                    text: "Unable to verify Purchase Order No. right now.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });

    //update purchase order
    $('#update').click(function (e) {
        e.preventDefault();

        const id = $('#purchase_order_id').val().trim();
        const poNo = $('#po_no').val().trim();
        const supplierId = $('#supplier_id').val().trim();
        const orderDate = $('#order_date').val().trim();

        if (!id || !poNo) {
            swal({
                title: "Error!",
                text: "Please select a purchase order to update.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;

        }

        if (!supplierId) {
            swal({
                title: "Error!",
                text: "Please select a supplier.",
                icon: 'error',
                timer: 2500,
                buttons: false
            });
            return;
        }

        if (!orderDate) {
            swal({
                title: "Error!",
                text: "Please select a valid order date.",
                icon: 'error',
                timer: 2500,
                buttons: false
            });
            return;
        }

        const items = [];
        let hasInvalidItem = false;

        $('#purchaseOrderBody tr').each(function () {
            if ($(this).attr('id') === 'noItemRow') return;

            const itemId = $(this).data('item-id');
            const itemText = $(this).find('td:eq(0)').text().trim();
            const qty = parseFloat($(this).find('td:eq(1)').text()) || 0;
            const unitPrice = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const total = parseFloat($(this).find('td:eq(3)').text()) || 0;

            if (!itemId || unitPrice <= 0 || qty <= 0) {
                hasInvalidItem = true;
                return false;
            }

            const [itemCode, itemName] = itemText.split(' - ');

            items.push({
                item_id: itemId,
                price: unitPrice,
                qty: qty,
                total: total
            });
        });

        if (hasInvalidItem) {
            swal({
                title: "Error!",
                text: "Please ensure all items have valid quantities and prices.",
                icon: 'error',
                timer: 2500,
                buttons: false
            });
            return;
        }

        if (items.length === 0) {
            swal({
                title: "Error!",
                text: "Please add at least one item to the purchase order.",
                icon: 'error',
                timer: 2500,
                buttons: false
            });
            return;
        }



        const purchaseOrderData = {
            action: 'update_purchase_order',
            id: id,
            po_no: poNo,
            order_date: orderDate,
            supplier_id: supplierId,
            brand: $('#brand').val(),
            invoice_no: $('#invoice_no').val(),
            country: $('#country').val(),
            department_id: $('#department_id').val(),
            purchase_date: $('#purchase_date').val(),
            order_by: $('#order_by').val(),
            remarks: $('#remark').val(),
            items: JSON.stringify(items),
            deleted_items: JSON.stringify(deletedItems || [])
        };

        $.ajax({
            url: 'ajax/php/purchase-order.php',
            method: 'POST',
            data: purchaseOrderData,
            dataType: 'json',
            beforeSend: function () {
                $('body').preloader({ text: 'Updating purchase order...' });
            },
            success: function (response) {
                $('body').preloader('remove');

                if (response.status === 'success') {

                    swal({
                        title: "Success!",
                        text: "Purchase Order update successfully!",
                        type: 'success',
                        timer: 2500,
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 2500);
                } else {
                    swal({
                        title: "Error!",
                        text: response.message || "Error updating purchase order.",
                        icon: 'error',
                        timer: 2500,
                        buttons: false
                    });
                }
            },
            error: function (xhr) {
                $('body').preloader('remove');
                console.error("AJAX Error:", xhr.responseText);
                swal({
                    title: "Error!",
                    text: "AJAX request failed. Please try again.",
                    icon: 'error',
                    timer: 2500,
                    buttons: false
                });
            }
        });
    });

    // Handle quotation selection from modal
    $(document).on('click', '.select-purchase-order', function () {
        // Get all data attributes from clicked row
        const id = $(this).data('id');
        const poNumber = $(this).data('po_number');
        const orderDate = $(this).data('order_date');
        const supplierId = $(this).data('supplier_id');
        const supplierCode = $(this).data('supplier_code');
        const supplierName = $(this).data('supplier_name');
        const supplierAddress = $(this).data('supplier_address');
        const brand = $(this).data('brand');
        const invoice_no = $(this).data('invoice_no');
        const country = $(this).data('country');
        const department = $(this).data('department');
        const purchase_date = $(this).data('purchase_date');
        const status = $(this).data('status');
        const remarks = $(this).data('remarks');
        const grandTotal = $(this).data('grand_total');

        // Set values to form inputs
        $('#purchase_order_id').val(id);
        $('#po_no').val(poNumber);
        $('#order_date').val(orderDate);
        $('#supplier_id').val(supplierId);
        $('#supplier_code').val(supplierCode);
        $('#supplier_name').val(supplierName);
        $('#supplier_address').val(supplierAddress);
        $('#brand').val(brand);
        $('#invoice_no').val(blNo);
        $('#country').val(country);
        $('#department_id').val(department);
        $('#purchase_date').val(purchase_date);
        $('#status').val(status);
        $('#remarks').val(remarks);
        $('#grandTotal').val(grandTotal);
        $('#finalTotal').val(grandTotal);
        // Optional: load supplier details if needed
        if (typeof loadSupplierById === 'function') {
            loadSupplierById(supplierId);
        }

        // Fetch detailed purchase order items via AJAX
        $.ajax({
            url: 'ajax/php/purchase-order.php',
            method: 'POST',
            data: {
                action: 'get_purchase_order',
                id: id
            },
            dataType: 'json',
            beforeSend: function () {
                $('body').preloader({ text: 'Loading purchase order...' });
            },
            success: function (response) {
                $('body').preloader('remove');

                if (response.status === 'success') {
                    const items = response.data.items;

                    // Clear existing items
                    $('#purchaseOrderBody').empty();

                    if (items.length > 0) {
                        items.forEach(item => {
                            const price = parseFloat(item.unit_price) || 0;
                            const qty = parseFloat(item.quantity) || 0;
                            const total = parseFloat(item.total_price) || 0;

                            const row = `
                                <tr data-item-id="${item.item_id}">
                                <td>${item.item_code} - ${item.item_name}</td>
                                <td>${qty}</td>
                                <td>${price.toFixed(2)}</td>
                               
                                
                                <td>${total.toFixed(2)}</td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
                            </tr>
                        `;
                            $('#purchaseOrderBody').append(row);
                        });
                    } else {
                        $('#purchaseOrderBody').append(`
                        <tr id="noItemRow">
                            <td colspan="7" class="text-center text-muted">No items found</td>
                        </tr>
                    `);
                    }

                    // Show/hide buttons as needed
                    if (status == 1) {
                        // status 1 = "active" (or whatever logic you want)
                        $('#create').hide();
                        $('#update').hide();
                        $('.delete-purchase-order').hide();
                    } else {
                        $('#create').hide();
                        $('#update').show();
                        $('.delete-purchase-order').show();
                    }

                    // Hide modal
                    $('#po_number_modal').modal('hide');
                } else {
                    swal({
                        title: "Error!",
                        text: "Failed to load purchase order.",
                        icon: 'error',
                        timer: 2500,
                        buttons: false
                    });
                }
            },
            error: function (xhr) {
                $('body').preloader('remove');
                console.error("AJAX error:", xhr.responseText);
                swal({
                    title: "Error!",
                    text: "AJAX request failed. Please try again.",
                    icon: 'error',
                    timer: 2500,
                    buttons: false
                });
            }
        });
    });


    //delete purchase order
    $(document).on('click', '.delete-purchase-order', function (e) {
        e.preventDefault();


        var id = $('#purchase_order_id').val();
        if (!id) {
            swal({
                title: "Error!",
                text: "Please select a purchase order to delete.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;

        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete this purchase order?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('.someBlock').preloader();

                $.ajax({
                    url: "ajax/php/purchase-order.php", // Adjust filename if different
                    type: "POST",
                    data: {
                        id: id,
                        action: 'delete',
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Purchase order has been deleted.",
                                type: "success",
                                timer: 2500,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 2500);

                        } else {
                            swal({
                                title: "Error!",
                                text: "Something went wrong.",
                                type: "error",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });


});
// Add this code at the end of purchase-order.js
$(document).ready(function () {
    // Initialize supplier table with filtering for suppliers only
    var supplierTable = $('#supplierTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "ajax/php/customer-master.php", // Make sure this is the correct endpoint
            type: "POST",
            data: function (d) {
                d.filter = true;
                d.supplier_only = true; // Filter suppliers only
                d.category = [2, 3]; // Only show Supplier (2) or Both (3)
            },
            dataSrc: function (json) {
                return json.data;
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        columns: [
            { data: "id", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "mobile_number", title: "Mobile" },
            { data: "email", title: "Email" },
            { data: "category", title: "Category" },
            { data: "province", title: "Province" },
            { data: "credit_limit", title: "Credit Limit" },
            { data: "vat_no", title: "Is Vat" },
            { data: "status_label", title: "Status" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });

    // Handle row selection in supplier modal
    $(document).on('click', '#supplierTable tbody tr', function () {
        var data = supplierTable.row(this).data();
        if (!data) return;

        // Fill the supplier fields in your form
        $('#supplier_id').val(data.id);
        $('#supplier_code').val(data.code);
        $('#supplier_name').val(data.name);
        $('#supplier_address').val(data.address || '');

        // Close the modal
        $('#supplierModal').modal('hide');
    });
});
