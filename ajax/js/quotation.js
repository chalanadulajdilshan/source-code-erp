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
            { data: "brand", title: "Brand" },
            { data: "cost", title: "Cost" },
            { data: "cash_price", title: "Cash" },
            { data: "credit_price", title: "Credit" },
            { data: "cash_discount", title: "Cash %" },
            { data: "credit_discount", title: "Credit %" },
            
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });


    // On row click, load selected item into input fields
    $('#datatable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        const salesType = $('#sales_type').val();
        const paymentType = $('#payment_type').val();

        if (salesType == 1) {  // Whole Sales
            $('#itemPrice').val(data.cash_price.replace(/,/g, ''));
        } else if (salesType == 2) {  // Retail Sales
            $('#itemPrice').val(data.credit_price.replace(/,/g, ''));
        }


        if (paymentType == 1) {
            $('#itemDiscount').val(data.cash_discount);
        } else if (paymentType == 2) {
            $('#itemDiscount').val(data.credit_discount);
        } else {
            $('#itemDiscount').val(0);
        }

        $('#item_id').val(data.id);
        $('#itemCode').val(data.code);
        $('#itemName').val(data.name);
        $('#itemQty').val(1);
        $('#available_qty').val(data.id);

        calculatePayment();

        setTimeout(() => $('#itemQty').focus(), 200);

        $('#item_master').modal('hide');
    });


    $('#item_master').on('hidden.bs.modal', function () {
        if (focusAfterModal) {
            $('#itemQty').focus();
            focusAfterModal = false;
        }
    });



    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        location.reload();
    });


    // Add item to quatation table
    function addItem() {
        const code = $('#itemCode').val().trim();
        const name = $('#itemName').val().trim();
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;
        const payment = parseFloat($('#itemPayment').val()) || 0;

        if (!code || !name || price <= 0 || qty <= 0) {
            alert("Please fill valid item details.");
            return;
        }

        const total = (price * qty) - ((price * qty) * (discount / 100));

        // Remove no data message if exists
        $('#noItemRow').remove();

        const row = `
        <tr>
            <td>${code}</td>
            <td>${name}</td>
            <td>${price.toFixed(2)}</td>
            <td>${qty}</td>
            <td>${discount}%</td>
            <td>${payment.toFixed(2)}</td>
            <td>${total.toFixed(2)}</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;
        $('#quotationItemsBody').append(row);

        // Clear input fields
        $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment').val('');

        updateFinalTotal();
    }

    // Remove item row
    function removeRow(button) {
        $(button).closest('tr').remove();
        updateFinalTotal();
    }

    // Update total at the bottom
    function updateFinalTotal() {
        let subTotal = 0;
        let discountTotal = 0;

        $('#quotationItemsBody tr').each(function () {
            const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const discount = parseFloat($(this).find('td:eq(4)').text()) || 0;
            const rowTotal = parseFloat($(this).find('td:eq(6)').text()) || 0;

            subTotal += price * qty;
            discountTotal += (price * qty * discount / 100);
        });


        const grandTotal = (subTotal - discountTotal);

        // Update display fields
        $('#finalTotal').val(subTotal.toFixed(2));        // Sub Total
        $('#disTotal').val(discountTotal.toFixed(2));     // Discount Total 
        $('#grandTotal').val(grandTotal.toFixed(2));      // Grand Total
    }


    // Bind button click
    $('#addItemBtn').click(addItem);

    // Bind Enter key to add item
    $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment').on('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addItem();
        }
    });

    // Calculate payment
    function calculatePayment() {
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;

        const subtotal = price * qty;
        const discountedAmount = subtotal * (discount / 100);
        const total = subtotal - discountedAmount;

        $('#itemPayment').val(total.toFixed(2));
    }

    // Call payment calculation on input change
    $('#itemPrice, #itemQty, #itemDiscount').on('input', calculatePayment);

    // Global function to remove row
    let deletedItems = [];


    window.removeRow = function (button) {
        const $row = $(button).closest('tr');

        // Get hidden item_id
        const itemId = $row.find('input.item-id').val();
        if (itemId) {
            deletedItems.push(itemId);

            $row.remove();

            if ($('#quotationItemsBody tr').length === 0) {
                $('#quotationItemsBody').append(`
            <tr id="noItemRow">
                <td colspan="8" class="text-center text-muted">No items added</td>
            </tr>
        `);
            }

            updateFinalTotal();
        };

    }

    // Disable price field to prevent manual changes
    $('#itemPrice').prop('readonly', true);

    $('#create').click(function (e) {
        e.preventDefault();

        const customeId = $('#customer_id').val().trim();
        const customerCode = $('#customer_code').val().trim();
        const customerName = $('#customer_name').val().trim();
        const quotationId = $('#quotation_id').val().trim();

        if (!customerCode || !customerName) {
            swal({
                title: "Error!",
                text: "Please select the customer.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!$('#date').val()) {
            swal({
                title: "Error!",
                text: "Please select a date.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!quotationId) {
            swal({
                title: "Error!",
                text: "Quotation No. cannot be blank.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        $.ajax({
            url: 'ajax/php/quotation.php',
            method: 'POST',
            data: {
                action: 'check_quotation_id',
                quotation_id: quotationId
            },
            dataType: 'json',
            success: function (checkResponse) {

                if (checkResponse.exists) {
                    swal({
                        title: "Duplicate!",
                        text: "Quotation No <strong>" + quotationId + "</strong> already exists.",
                        type: 'error',
                        html: true,
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                const items = [];
                let hasInvalidItem = false;

                $('#quotationItemsBody tr').each(function () {

                    if ($(this).attr('id') === 'noItemRow') {
                        return;
                    }

                    const itemCode = $(this).find('td:eq(0)').text().trim();
                    const itemName = $(this).find('td:eq(1)').text().trim();
                    const itemPrice = parseFloat($(this).find('td:eq(2)').text()) || 0;
                    const itemQty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                    const itemDiscount = parseFloat($(this).find('td:eq(4)').text().replace('%', '')) || 0;
                    const itemTotal = parseFloat($(this).find('td:eq(6)').text()) || 0;

                    if (!itemCode || !itemName || itemPrice <= 0 || itemQty <= 0) {
                        hasInvalidItem = true;
                        return false; // break out of .each()
                    }

                    items.push({
                        code: itemCode,
                        name: itemName,
                        price: itemPrice,
                        qty: itemQty,
                        discount: itemDiscount,
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
                        text: "Please add items to the quotation.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }


                const finalTotal = parseFloat($('#finalTotal').val()) || 0;

                const quotationData = {
                    action: 'create_quotation',
                    quotation_id: quotationId,
                    customer_id: customeId,
                    customer_code: customerCode,
                    customer_name: customerName,
                    date: $('#date').val(),
                    company_id: $('#company_id').val(),
                    department_id: $('#department_id').val(),
                    marketing_executive_id: $('#marketing_executive_id').val(),
                    sales_type: $('#sales_type').val(),
                    payment_type: $('#payment_type').val(),
                    remarks: $('#remark').val(),
                    credit_period: $('#credit_period').val(),
                    payment_term: $('#payment_type').val(),
                    validity: $('#validity').val(),
                    vat_type: $('#vat_type').val(),
                    grand_total: finalTotal,
                    items: JSON.stringify(items),

                };


                $.ajax({
                    url: 'ajax/php/quotation.php',
                    method: 'POST',
                    data: quotationData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            swal({
                                title: "Success!",
                                text: "Quotation created successfully!",
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
                                text: response.message || "Error creating quotation.",
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
                    text: "Unable to verify Quotation No. right now.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });

    $('#update').click(function (e) {
        e.preventDefault();

        const id = $('#id').val().trim();
        const quotationId = $('#quotation_id').val().trim();
        const customerCode = $('#customer_code').val().trim();
        const customerName = $('#customer_name').val().trim();

        if (!id || !quotationId) {
            swal({
                title: "Error!",
                text: "Please select a quotation to update.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!customerCode || !customerName) {
            swal({
                title: "Error!",
                text: "Please select the customer.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        if (!$('#date').val()) {
            swal({
                title: "Error!",
                text: "Please select a date.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        const items = [];
        let hasInvalidItem = false;

        $('#quotationItemsBody tr').each(function () {
            if ($(this).attr('id') === 'noItemRow') return;
            const itemCode = $(this).find('td:eq(0)').text().trim();
            const itemName = $(this).find('td:eq(1)').text().trim();
            const itemPrice = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const itemQty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const itemDiscount = parseFloat($(this).find('td:eq(4)').text().replace('%', '')) || 0;
            const itemTotal = parseFloat($(this).find('td:eq(6)').text()) || 0;

            if (!itemCode || !itemName || itemPrice <= 0 || itemQty <= 0) {
                hasInvalidItem = true;
                return false;
            }

            items.push({
                code: itemCode,
                name: itemName,
                price: itemPrice,
                qty: itemQty,
                discount: itemDiscount,
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
                text: "Please add items to the quotation.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        let finalTotal = parseFloat($('#finalTotal').text()) || 0;

        const quotationData = {
            action: 'update_quotation',
            id: id,
            quotation_id: quotationId,
            credit_period: $('#credit_period').val(),
            customer_id: $('#customer_id').val(),
            customer_name: customerName,
            date: $('#date').val(),
            company_id: $('#company_id').val(),
            department_id: $('#department_id').val(),
            marketing_executive_id: $('#marketing_executive_id').val(),
            sales_type: $('#sales_type').val(),
            payment_type: $('#payment_type').val(),
            remarks: $('#remark').val(),
            vat_type: $('#vat_type').val(),
            sub_total: finalTotal,
            discount: 0,
            grand_total: finalTotal,
            items: JSON.stringify(items),
            deleted_items: JSON.stringify(deletedItems)
        };

        $.ajax({
            url: 'ajax/php/quotation.php',
            method: 'POST',
            data: quotationData,
            dataType: 'json',
            beforeSend: function () {
                $('body').preloader({
                    text: 'Updating quotation...'
                });
            },
            success: function (response) {
                $('body').preloader('remove');
                if (response.status === 'success') {
                    swal({
                        title: "Success!",
                        text: "Quotation updated successfully!",
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
                        text: response.message || "Error updating quotation.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr) {
                $('body').preloader('remove');
                console.error("AJAX error:", xhr.responseText);
                swal({
                    title: "Error!",
                    text: "AJAX request failed. Please try again.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });

    // Handle quotation selection from modal
    $(document).on('click', '.select-model', function () {
        const quotationId = $(this).data('id');
        const quotationNo = $(this).data('quotation_no');
        const date = $(this).data('date');
        const customerId = $(this).data('customer_name');
        const companyId = $(this).data('company_id');
        const departmentId = $(this).data('department_id');


        // Set the quotation ID and date in the form
        $('#id').val(quotationId);
        $('#quotation_id').val(quotationNo);
        $('#date').val(date);
        $('#company_id').val(companyId);
        $('#department_id').val(departmentId);

        // Get full quotation details from server
        $.ajax({
            url: 'ajax/php/quotation.php',
            method: 'POST',
            data: {
                action: 'get_quotation',
                id: quotationId
            },
            dataType: 'json',
            beforeSend: function () {
                // Show loading indicator
                $('body').preloader({
                    text: 'Loading quotation...'
                });
            },
            success: function (response) {
                $('body').preloader('remove');

                if (response.status === 'success') {
                    const quotation = response.data.quotation;
                    const items = response.data.items;

                    // Load customer details
                    loadCustomerById(quotation.customer_id);

                    // Set form values
                    $('#marketing_executive_id').val(quotation.marketing_executive_id);
                    $('#sales_type').val(quotation.sale_type || 1);
                    $('#payment_type').val(quotation.payment_type);
                    $('#vat_type').val(quotation.vat_type || 1);
                    $('#remark').val(quotation.remarks);

                    $('#finalTotal').val(quotation.sub_total);
                    $('#disTotal').val(quotation.discount);
                    $('#grandTotal').val(quotation.grand_total);
                    $('#credit_period').val(quotation.credit_period);
                    $('#validity').val(quotation.validity);

                    // Clear existing items
                    $('#quotationItemsBody').empty();

                    // Add items to the table
                    if (items.length > 0) {
                        items.forEach(function (item) {

                            const discount = parseFloat(item.discount) || 0;
                            const price = parseFloat(item.price) || 0;
                            const qty = parseFloat(item.qty) || 0;
                            const subtotal = price * qty;
                            const total = parseFloat(item.sub_total) || 0;

                            const row = `
                            <tr>
                                <td>${item.item_code}                                
                                 <input type="hidden" class="item-id" value="${item.item_id}"></td>
                                <td>${item.item_name}</td>
                                <td>${price.toFixed(2)}</td>
                                <td>${qty}</td>
                                <td>${discount}%</td>
                                <td>${subtotal.toFixed(2)}</td>
                                <td>${total.toFixed(2)}</td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
                            </tr>
                            `;

                            $('#quotationItemsBody').append(row);
                        });
                    } else {
                        // Add "No items" row if no items found
                        $('#quotationItemsBody').append(`
                            <tr id="noItemRow">
                                <td colspan="8" class="text-center text-muted">No items added</td>
                            </tr>
                        `);
                    }

                    $('#create').hide();
                    $('#update').show();
                    $('.delete-quotation').show();

                    // Update final total
                    $('#finalTotal').html(`<strong>${quotation.grand_total}</strong>`);

                    // Close the modal
                    $('#quotationModel').modal('hide');

                } else {
                    swal({
                        title: "Error!",
                        text: "Error loading quotation details.",
                        type: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr) {
                $('body').preloader('remove');
                console.error("AJAX error:", xhr.responseText);
                swal({
                    title: "Error!",
                    text: "AJAX request failed. Please try again.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });

    function loadCustomerById(customerId) {
        $.ajax({
            url: 'ajax/php/quotation.php',
            method: 'POST',
            data: {
                action: 'get_customer_by_id',
                customer_id: customerId
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#customer_id').val(data.id);
                    $('#customer_code').val(data.code);
                    $('#customer_name').val(data.name);
                    $('#customer_address').val(data.address);
                    $('#customer_mobile').val(data.mobile_number);
                } else {
                    console.error("Customer not found");
                }
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
            }
        });
    }

    $(document).on('click', '.delete-quotation', function (e) {
        e.preventDefault();

        var id = $("#id").val();
        var quotation_id = $("#quotation_id").val();

        swal({
            title: "Are you sure?",
            text: "Do you want to delete quotation  '" + quotation_id + "'?",
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
                    url: "ajax/php/quotation.php",
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
                                text: "Quotation has been deleted.",
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
