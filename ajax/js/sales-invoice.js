jQuery(document).ready(function () {


    //windows loder
    loadCustomer();

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
            { data: "whole_sale_price", title: "Wholesale" },
            { data: "retail_price", title: "Retail" },
            { data: "cash_discount", title: "Cash Dis %" },
            { data: "credit_discount", title: "Credit Dis %" },
            { data: "status_label", title: "Status" }
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
            $('#itemPrice').val(data.whole_sale_price.replace(/,/g, ''));
        } else if (salesType == 2) {  // Retail Sales
            $('#itemPrice').val(data.retail_price.replace(/,/g, ''));
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

        $('.bs-example-modal-xl').modal('hide');
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


    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $('#category').prop('selectedIndex', 0); // Optional, if using dropdowns
        $("#create").show();
    });


    // Open payment modal and pre-fill total
    $('#create').on('click', function () {
        const total = parseFloat($('#finalTotal').text()) || 0;
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


        if (!$('#customer_code').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });


        } else {

            const total = parseFloat($('#modalFinalTotal').val()) || 0;
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

            // Collect invoice items
            const items = [];
            $('#invoiceItemsBody tr').each(function () {
                const code = $(this).find('td:eq(0)').text().trim();
                const name = $(this).find('td:eq(1)').text().trim();
                const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
                const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                const discount = parseFloat($(this).find('td:eq(4)').text()) || 0;
                const payment = parseFloat($(this).find('td:eq(5)').text()) || 0;
                const totalItem = parseFloat($(this).find('td:eq(6)').text()) || 0;
                const item_id = $('#item_id').val();

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

            // Use FormData to include form inputs + invoice items
            const formData = new FormData($("#form-data")[0]);
            formData.append('create', true);
            formData.append('total', total);
            formData.append('paid', paid);
            formData.append('payment_type', paymentType);
            formData.append('items', JSON.stringify(items));
            formData.append('invoice_no', $('#invoice_no').val());



            // Start Preloader
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
                    // Optional: Reset or redirect
                    window.location.href = "invoice.php?invoice_no=" + $('#invoice_no').val();
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
    });

    // Open item modal
    $('#open-item-modal').click(function (e) {
        e.preventDefault();
        const myModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-xl'));
        myModal.show();
    });

    // Add item to invoice table
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
            <td class="item-price">${price.toFixed(2)}</td>
            <td class="item-qty">${qty}</td>
            <td class="item-discount">${discount}</td>
            <td>${payment.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td>${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>

            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;
        $('#invoiceItemsBody').append(row);

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
        let total = 0;
        $('#invoiceItemsBody tr').each(function () {
            const rowTotal = parseFloat($(this).find('td:eq(6)').text()) || 0;
            total += rowTotal;
        });
        $('#finalTotal').text(total.toFixed(2));
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
        $('#subTotal').html(`<strong>${subTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong>`);
        $('#disTotal').html(`<strong>${discountTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong>`);
        $('#finalTotal').html(`<strong>${grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong>`);

    }

    // Disable price field to prevent manual changes
    $('#itemPrice').prop('readonly', true);

    // Amount Paid focus
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amountPaid').focus();
    });

    //////////////////////////customer details add//////////////////////////


    $('#customerModal').on('shown.bs.modal', function () {
        loadCustomerTable();
    });


    function loadCustomerTable() {
        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }

        $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "ajax/php/customer-master.php",
                type: "POST",
                data: function (d) {
                    d.filter = true;
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

        $('#customerTable tbody').on('click', 'tr', function () {
            var data = $('#customerTable').DataTable().row(this).data();

            if (data) {
                $('#customer_id').val(data.id);
                $('#customer_code').val(data.code);
                $('#customer_name').val(data.name);
                $('#customer_address').val(data.address);
                $('#customer_mobile').val(data.mobile_number);
                $('#customerModal').modal('hide');
            }
        });
    }


    //////////// append invoice data to table ///////////////////
    var table = $('#invoiceTable2').DataTable({
        processing: true,
        serverSide: true,
        destroy: true, // important when initializing inside modal
        ajax: {
            url: "ajax/php/sales-invoice.php",
            type: "POST",
            data: function (d) {
                d.filter = true;
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
            { data: "invoice_no", title: "Invoice No" },
            { data: "invoice_date", title: "Date" },
            { data: "department", title: "Department" },  // e.g., Sales, Marketing (string from JOIN)
            { data: "customer", title: "Customer" },      // e.g., Customer Name (string from JOIN)
            { data: "grand_total", title: "Grand Total" },
            { data: "remark", title: "Remark" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });

    // row click to fill form
    $('#invoiceTable2 tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        $('#invoice_id').val(data.id);
        $('#invoice_no').val(data.invoice_no);
        $('#invoice_date').val(data.invoice_date);
        $('#department_id').val(data.department_id).trigger('change');
        $('#customer_id').val(data.customer_id).trigger('change');
        $('#sale_type').val(data.sale_type).trigger('change');
        $('#discount_type').val(data.discount_type).trigger('change');
        $('#payment_type').val(data.payment_type).trigger('change');
        $('#sub_total').val(data.sub_total);
        $('#discount').val(data.discount);
        $('#tax').val(data.tax);
        $('#grand_total').val(data.grand_total);
        $('#remark').val(data.remark);

        $("#create").hide();
        $('#invoiceModal').modal('hide');
    });






});
