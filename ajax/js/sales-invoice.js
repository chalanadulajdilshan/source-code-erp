jQuery(document).ready(function () {


    //windows loder
    loadCustomer();
    getInvoiceData();


    // DataTable config
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "ajax/php/item-master.php",
            type: "POST",
            data: function (d) {
                d.filter_by_invoice = true;
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
            { data: "key", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "brand", title: "Brand" },
            { data: "cost", title: "Cost" },
            { data: "cash_price", title: "Credit" },
            { data: "credit_price", title: "Retail" },
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


        const departmentId = $('#department_id').val();
        const itemId = data.id;


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

        calculatePayment();

        setTimeout(() => $('#itemQty').focus(), 200);

        $('#item_master').modal('hide');
    });

    $('#department_id').on('change', function () {
        $('#item_id').val('');
        $('#itemCode').val('');
        $('#itemName').val('');
        $('#itemQty').val('');
        $('#itemPrice').val('');
        $('#itemPayment').val('');
        $('#available_qty').val(0);

    });


    $('#item_master').on('hidden.bs.modal', function () {
        if (focusAfterModal) {
            $('#itemQty').focus();
            focusAfterModal = false;
        }
    });


    //get first row cash sales customer
    function loadCustomer() {

        $.ajax({
            url: 'ajax/php/customer-master.php',
            method: 'POST',
            data: { action: 'get_first_customer' }, // you can customize this key/value
            dataType: 'json',
            success: function (data) {
                if (!data.error) {
                    $('#customer_id').val(data.customer_id);
                    $('#customer_code').val(data.customer_code);
                    $('#customer_name').val(data.customer_name);
                    $('#customer_address').val(data.customer_address);
                    $('#customer_mobile').val(data.mobile_number); // adjust key if needed
                } else {
                    console.warn('No customer found');
                }
            },
            error: function () {
                console.error('AJAX request failed.');
            }
        });
    }

    $('input[name="payment_type"]').on('change', function () {
        getInvoiceData();
    });

    //get invoice id 
    function getInvoiceData() {
        const paymentType = $('input[name="payment_type"]:checked').val(); // 'cash' or 'credit'

        $.ajax({
            url: 'ajax/php/common.php',
            method: 'POST',
            data: {
                action: 'get_invoice_id_by_type',
                payment_type: paymentType
            },
            dataType: 'json',
            success: function (response) {
                if (response.invoice_id) {
                    $('#invoice_no').val(response.invoice_id);
                } else {
                    console.warn('Invoice ID generation failed');
                }
            },
            error: function () {
                console.error('Failed to fetch invoice ID');
            }
        });
    }


    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        location.reload();
    });

    // Open payment modal and pre-fill total
    $('#create').on('click', function () {
        const total = parseFloat($('#finalTotal').val().replace(/,/g, '')) || 0;

        $('#modalFinalTotal').val(total.toFixed(2));
        $('#amountPaid').val('');
        $('#balanceAmount').val('0.00').removeClass('text-danger');
        $('#paymentModal').modal('show');
    });

    // Calculate and display balance or show insufficient message
    $('#amountPaid').on('input', function () {
        const paid = parseFloat($(this).val()) || 0;
        const total = parseFloat($('#modalFinalTotal').val()) || 0;

        if (paid < total) {
            $('#balanceAmount').val('Insufficient').addClass('text-danger');
        } else {
            const balance = paid - total;
            $('#balanceAmount').val(balance.toFixed(2)).removeClass('text-danger');
        }
    });

    // Handle payment form submission
    $('#paymentForm').on('submit', function (e) {
        e.preventDefault();

        if (!$('#customer_id').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        const invoiceNo = $('#invoice_no').val().trim();

        $.ajax({
            url: 'ajax/php/sales-invoice.php',
            method: 'POST',
            data: {
                action: 'check_invoice_id',
                invoice_no: invoiceNo
            },
            dataType: 'json',
            success: function (checkRes) {
                if (checkRes.exists) {
                    swal({
                        title: "Duplicate!",
                        text: "Invoice No <strong>" + invoiceNo + "</strong> already exists.",
                        type: 'error',
                        html: true,
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                processInvoiceCreation(); // move your creation logic into a function
            },
            error: function () {
                swal({
                    title: "Error!",
                    text: "Unable to verify Invoice No. right now.",
                    type: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });



    function processInvoiceCreation() {
        const total = parseFloat($('#modalFinalTotal').val());
        const paid = parseFloat($('#amountPaid').val()) || 0;
        const paymentType = $('#modalPaymentType').val();

        if (paid < total) {
            swal({
                title: "Error!",
                text: "Paid amount cannot be less than Final Total",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const items = [];
        $('#invoiceItemsBody tr').each(function () {
            const code = $(this).find('td:eq(0)').text().trim();
            const name = $(this).find('td:eq(1)').text().trim();
            const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const discount = parseFloat($(this).find('td:eq(4)').text()) || 0;
            const payment = parseFloat($(this).find('td:eq(5)').text()) || 0;
            const totalItem = parseFloat($(this).find('td:eq(6)').text()) || 0;
            const item_id = $(this).find('input[name="item_id[]"]').val();


            if (code && !isNaN(totalItem)) {
                items.push({
                    item_id,
                    code,
                    name,
                    price,
                    qty,
                    discount,
                    payment,
                    total: totalItem
                });
            }
        });

        if (items.length === 0) {
            swal({
                title: "Error!",
                text: "Please add at least one item.",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const formData = new FormData($("#form-data")[0]);
        formData.append('create', true);
        formData.append('total', total);
        formData.append('paid', paid);
        formData.append('payment_type', $('input[name="payment_type"]:checked').val());
        formData.append('customer_id', $('#customer_id').val());
        formData.append('items', JSON.stringify(items));
        formData.append('invoice_no', $('#invoice_no').val());

        $('.someBlock').preloader();

        $.ajax({
            url: 'ajax/php/sales-invoice.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.someBlock').preloader('remove');
            },
            success: function (res) {
                swal({
                    title: "Success!",
                    text: "Invoice saved successfully!",
                    type: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
                $('#paymentModal').modal('hide');
                location.reload();
                window.open("invoice.php?invoice_no=" + res.invoice_id, "_blank");
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                swal({
                    title: "Error",
                    text: "Something went wrong!",
                    type: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }



    // Add item to invoice table
    function addItem() {
        const item_id = $('#item_id').val().trim();
        const code = $('#itemCode').val().trim();
        const name = $('#itemName').val().trim();
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;
        const payment = parseFloat($('#itemPayment').val()) || 0;
        const availableQty = parseFloat($('#available_qty').val()) || 0; // You must have this field in your form

        if (!code || !name || price <= 0 || qty <= 0) {
            swal({
                title: "Error!",
                text: "Please enter valid item details including quantity and price.",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        } else if (qty > availableQty) {
            swal({
                title: "Error!",
                text: "Transfer quantity cannot exceed available quantity!",
                type: "error",
                timer: 2500,
                showConfirmButton: false,
            });
            return;
        } else {



            const table = document.getElementById('invoiceTable').querySelector('tbody');

            // Check for duplicate item code
            const existingItems = table.querySelectorAll('input[name="item_codes[]"]');
            for (let i = 0; i < existingItems.length; i++) {
                if (existingItems[i].value === code) {
                    swal({
                        title: "Duplicate Item!",
                        text: "This item has already been added.",
                        type: "warning",
                        timer: 2500,
                        showConfirmButton: false,
                    });
                    return;

                }
            }

            const total = (price * qty) - ((price * qty) * (discount / 100));

            // Remove "no data" message
            $('#noItemRow').remove();

            const row = `
        <tr>
            <td>${code}<input type="hidden" name="item_id[]" value="${item_id}"></td>
            <td>${name}</td>
            <td class="item-price">${price.toFixed(2)}</td>
            <td class="item-qty">${qty}</td>
            <td class="item-discount">${discount}</td>
            <td>${payment.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td>${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;

            $('#invoiceItemsBody').append(row);
            $('#available_qty').val(0);
            // Clear inputs
            $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment').val('');

            updateFinalTotal();
        }
    }


    // Remove item row
    function removeRow(button) {
        $(button).closest('tr').remove();
        updateFinalTotal();
    }

    // Update total at the bottom
    function updateFinalTotal() {
        let total = 0;
        $('#invoiceItemsBody tr').each(function () {
            const rowTotal = parseFloat($(this).find('td:eq(6)').text()) || 0;
            total += rowTotal;
        });
        $('#finalTotal').val(total.toFixed(2));
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
    window.removeRow = function (button) {
        $(button).closest('tr').remove();

        // If no rows left, add no-item message
        if ($('#invoiceItemsBody tr').length === 0) {
            $('#invoiceItemsBody').append(`
                <tr id="noItemRow">
                    <td colspan="8" class="text-center text-muted">No items added</td>
                </tr>
            `);
        }

        updateFinalTotal();
    };

    // Function to calculate final total from all rows
    function updateFinalTotal() {
        let subTotal = 0;
        let discountTotal = 0;

        $('#invoiceItemsBody tr').each(function () {
            const price = parseFloat($(this).find('.item-price').text()) || 0;
            const qty = parseFloat($(this).find('.item-qty').text()) || 0;
            const discount = parseFloat($(this).find('.item-discount').text()) || 0;

            const itemSubTotal = price * qty;
            const itemDiscount = itemSubTotal * (discount / 100);
            const itemTotal = itemSubTotal - itemDiscount;

            subTotal += itemSubTotal;
            discountTotal += itemDiscount;
        });

        const grandTotal = subTotal - discountTotal;
        $('#subTotal').val(subTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#disTotal').val(discountTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#finalTotal').val(grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

    }

    // Disable price field to prevent manual changes
    $('#itemPrice').prop('readonly', true);

    // Amount Paid focus
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amountPaid').focus();
    });


});
