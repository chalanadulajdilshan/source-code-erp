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
        let total = 0;
        $('#quotationItemsBody tr').each(function () {
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
        if ($('#quotationItemsBody tr').length === 0) {
            $('#quotationItemsBody').append(`
                <tr id="noItemRow">
                    <td colspan="8" class="text-center text-muted">No items added</td>
                </tr>
            `);
        }

        updateFinalTotal();
    };

    // Function to calculate final total from all rows
    function updateFinalTotal() {
        let total = 0;

        $('#quotationItemsBody tr').each(function () {
            const totalCell = $(this).find('td').eq(6);
            if (totalCell.length && !isNaN(totalCell.text())) {
                total += parseFloat(totalCell.text()) || 0;
            }
        });

        $('#finalTotal').html(`<strong>${total.toFixed(2)}</strong>`);
    }

    // Disable price field to prevent manual changes
    $('#itemPrice').prop('readonly', true);

    // Amount Paid focus
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amountPaid').focus();
    });

    $('#create').click(function (e) {
        e.preventDefault();
    
        const customerCode = $('#customer_code').val().trim();
        const customerName = $('#customer_name').val().trim();
        const quotationId = $('#quotation_id').val().trim();
    
        if (!customerCode || !customerName) {
            swal({
                title: "Error!",
                text: "Please select the customer.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
    
        const items = [];
        let hasInvalidItem = false;
    
        $('#quotationItemsBody tr').each(function () {
            const itemCode = $(this).find('td:eq(0)').text().trim();
            const itemName = $(this).find('td:eq(1)').text().trim();
            const itemPrice = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const itemQty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const itemDiscount = parseFloat($(this).find('td:eq(4)').text()) || 0;
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
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
    
        if (items.length === 0) {
            swal({
                title: "Error!",
                text: "Please add items to the quotation.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
    
        const quotationData = {
            action: 'create_quotation',
            quotation_id: quotationId,
            customer_code: customerCode,
            customer_name: customerName,
            date: $('#date').val(),
            company_id: $('#company_id').val(),
            department_id: $('#department_id').val(),
            marketing_executive_id: $('#marketing_executive_id').val(),
            sales_type: $('#sales_type').val(),
            payment_type: $('#payment_type').val(),
            paid: $('#paid').val(),
            remark: $('#remark').val(),
            items: JSON.stringify(items)
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
                        timer: 2000,
                        showConfirmButton: false
                    });
    
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                } else {
                    swal({
                        title: "Error!",
                        text: "Error creating quotation.",
                        type: 'error',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function () {
                swal({
                    title: "Error!",
                    text: "AJAX request failed. Please try again.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
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

});
