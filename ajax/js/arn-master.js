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
            { data: "key", title: "#ID" },
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

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $('#category').prop('selectedIndex', 0); // Optional, if using dropdowns
        $("#create").show();
    });

    function calculatePayment() {

        const recQty = parseFloat($('#rec_quantity').val()) || 0;
        const comCost = parseFloat($('#cost').val()) || 0;
        const actual_cost = parseFloat($('.actual_cost').val()) || 0;



        const dis1 = parseFloat($('#dis_1').val()) || 0;
        const dis2 = parseFloat($('#dis_2').val()) || 0;
        const dis3 = parseFloat($('#dis_3').val()) || 0;

        // Calculate discounts
        let disAmount1 = comCost * (dis1 / 100);
        let disAmount2 = (comCost - disAmount1) * (dis2 / 100);
        let disAmount3 = (comCost - disAmount1 - disAmount2) * (dis3 / 100);
        let finalCost = comCost - disAmount1 - disAmount2 - disAmount3;

        let unitTotal = actual_cost * recQty;

        // let tax = unitTotal * 0.15;

        $('#actual_cost').val(finalCost.toFixed(2));
        $('#unit_total').val(unitTotal.toFixed(2));
        // $('#tax').val(tax.toFixed(2));
    }

    // Bind function to relevant input fields
    $('#arn-item-table').on('input', '#rec_quantity, .actual_cost', calculatePayment);



    $('#addItemBtn').on('click', function () {
        const code = $('#itemCode').val();
        const recQty = parseFloat($('#rec_quantity').val()) || 0;
        const cost = parseFloat($('#cost').val()) || 0;
        const dis1 = parseFloat($('#dis_1').val()) || 0;
        const dis2 = parseFloat($('#dis_2').val()) || 0;
        const dis3 = parseFloat($('#dis_3').val()) || 0;
        const actualCost = parseFloat($('.actual_cost').val()) || 0;
        const unitTotal = parseFloat($('#unit_total').val()) || 0;
        const listPrice = parseFloat($('#list_price').val()) || 0;
        const cashPrice = parseFloat($('#cash_price').val()) || 0;
        const creditPrice = parseFloat($('#credit_price').val()) || 0;
        const tax = parseFloat($('#tax').val()) || 0;

        // ─────── Validations ───────
        if (!code) {
            return swal({ title: "Error!", text: "Please select an Item Code", type: "error", timer: 2000, showConfirmButton: false });
        }
        if (!recQty || recQty <= 0) {
            return swal({ title: "Error!", text: "Please enter a valid Received Quantity", type: "error", timer: 2000, showConfirmButton: false });
        }
        if (!cost || cost <= 0) {
            return swal({ title: "Error!", text: "Please enter Commercial Cost", type: "error", timer: 2000, showConfirmButton: false });
        }
        if (!listPrice || listPrice <= 0) {
            return swal({ title: "Error!", text: "Please enter List Price", type: "error", timer: 2000, showConfirmButton: false });
        }
        if (!cashPrice || cashPrice <= 0) {
            return swal({ title: "Error!", text: "Please enter Cash Price", type: "error", timer: 2000, showConfirmButton: false });
        }
        if (!creditPrice || creditPrice <= 0) {
            return swal({ title: "Error!", text: "Please enter Credit Price", type: "error", timer: 2000, showConfirmButton: false });
        }



        // ─────── Remove placeholder row ───────
        $('#noDataRow').remove();

        // ─────── Generate a unique row ID (for later removal if needed) ───────
        const itemId = `item_${Date.now()}`;

        // ─────── Add new row ───────
        const newRow = `
        <tr data-itemid="${itemId}">
            <td>${code}</td>
            
            <td>${recQty}</td>
            <td>${cost.toFixed(2)}</td>
            <td>${dis1}</td>
            <td>${dis2}</td>
            <td>${dis3}</td>
            <td>${actualCost.toFixed(2)}</td>
            <td>${unitTotal.toFixed(2)}</td>
            <td>${listPrice.toFixed(2)}</td>
            <td>${cashPrice.toFixed(2)}</td>
            <td>${creditPrice.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm deleteRowBtn">Delete</button></td>
        </tr>
    `;
        $('#itemTableBody').append(newRow);

        // ─────── Update Totals ───────
        const currentARN = parseFloat($('#total_arn').val()) || 0;
        $('#total_arn').val((currentARN + unitTotal).toFixed(2));

        const currentVAT = parseFloat($('#total_vat').val()) || 0;
        $('#total_vat').val((currentVAT + (tax * recQty)).toFixed(2));

        const currentDiscount = parseFloat($('#total_discount').val()) || 0;
        const discountValue = (cost - actualCost) * recQty;
        $('#total_discount').val((currentDiscount + discountValue).toFixed(2));

        // ─────── Clear Input Fields ───────
        $('#rec_quantity').val('');
        $('#cost').val('');
        $('#dis_1, #dis_2, #dis_3').val('');
        $('#actual_cost').val('');
        $('#unit_total').val('');
        $('#list_price, #cash_price, #credit_price').val('');
        updateSummaryValues();
    });




    $(document).on('click', '.select-purchase-order', function () {
        const id = $(this).data('id');
        const poNumber = $(this).data('po_number');
        const orderDate = $(this).data('order_date');
        const supplierId = $(this).data('supplier_id');
        const supplierCode = $(this).data('supplier_code');
        const supplierName = $(this).data('supplier_name');
        const supplierAddress = $(this).data('supplier_address');
        const piNo = $(this).data('pi_no');
        const lcTtNo = $(this).data('lc_tt_no');
        const brand = $(this).data('brand');
        const blNo = $(this).data('bl_no');
        const country = $(this).data('country');
        const ciNo = $(this).data('ci_no');
        const department = $(this).data('department');
        const orderBy = $(this).data('order_by');
        const remarks = $(this).data('remarks');
        const grandTotal = $(this).data('grand_total');
        const status = $('#status').val();

        // Set values to form inputs
        $('#purchase_order_id').val(id);
        $('#po_no').val(poNumber);
        $('#order_date').val(orderDate);
        $('#supplier_id').val(supplierId);
        $('#supplier_code').val(supplierCode);
        $('#supplier_name').val(supplierName);
        $('#supplier_address').val(supplierAddress);
        $('#pi_no').val(piNo);
        $('#lc_tt_no').val(lcTtNo);
        $('#brand').val(brand);
        $('#bl_no').val(blNo);
        $('#country').val(country);
        $('#ci_no').val(ciNo);
        $('#department_id').val(department);
        $('#order_by').val(orderBy);
        $('#remarks').val(remarks);
        $('#grandTotal').val(grandTotal);
        $('#finalTotal').val(grandTotal);

        if (typeof loadSupplierById === 'function') {
            loadSupplierById(supplierId);
        }

        // Fetch item details
        $.ajax({
            url: 'ajax/php/purchase-order.php',
            method: 'POST',
            data: { action: 'get_purchase_order', id: id, status: status },
            dataType: 'json',
            beforeSend: function () {
                $('body').preloader({ text: 'Loading purchase order...' });
            },
            success: function (response) {
                $('body').preloader('remove');

                if (response.status === 'success') {
                    const items = response.data.items || [];
                    $('#itemTableBody').empty();

                    if (items.length > 0) {
                        items.forEach((item, index) => {
                            const price = parseFloat(item.unit_price) || 0;
                            const qty = parseFloat(item.quantity) || 0;
                            const unitTotal = price * qty;

                            const row = `
                            <tr data-item-id="${item.item_id}">
                                <td style="width: 250px;">
                                    ${item.item_code} - ${item.item_name}
                                    <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
                                </td>
                                <td><input type="number" name="items[${index}][order_qty]" class="form-control form-control-sm" readonly value="${qty}"></td>

                                <td><input type="number" name="items[${index}][rec_qty]" class="form-control form-control-sm" value="${item.rec_qty || 0}"></td>
                                <td><input type="number" step="0.01" name="items[${index}][com_cost]" class="form-control form-control-sm" value="${item.com_cost || 0}"></td>
                                <td><input type="number" step="0.01" name="items[${index}][dis1]" class="form-control form-control-sm me-1" value="${item.dis1 || 0}" placeholder="D1"></td>
                                <td><input type="number" step="0.01" name="items[${index}][dis2]" class="form-control form-control-sm" value="${item.dis2 || 0}" placeholder="D2"></td>
                                <td><input type="number" step="0.01" name="items[${index}][dis3]" class="form-control form-control-sm" value="${item.dis3 || 0}" placeholder="D3"></td>
                                <td><input type="number" step="0.01" name="items[${index}][actual_cost]" class="form-control form-control-sm" value="${item.actual_cost || 0}"></td>
                                <td><input type="number" step="0.01" name="items[${index}][unit_total]" class="form-control form-control-sm" value="${unitTotal.toFixed(2)}" readonly></td>
                                <td><input type="number" step="0.01" name="items[${index}][list_price]" class="form-control form-control-sm" value="${item.list_price || 0}"></td>
                                <td><input type="number" step="0.01" name="items[${index}][cash_price]" class="form-control form-control-sm" value="${item.cash_price || 0}"></td>
                                <td><input type="number" step="0.01" name="items[${index}][credit_price]" class="form-control form-control-sm" value="${item.credit_price || 0}"></td>
                                <td><button type="button" class="btn btn-sm btn-danger delete-purchase-order-item" data-item-id="${item.id}"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        `;
                            $('#itemTableBody').append(row);
                        });

                        $('#arn-item-table').hide();
                        $('#itemTable').removeClass('mt-5');
                    } else {
                        $('#itemTableBody').append(`
                        <tr id="noDataRow">
                            <td colspan="13" class="text-center text-muted">No items found</td>
                        </tr>
                    `);
                    }

                    $('#create').hide();
                    $('#update').show();
                    $('.delete-po').show();
                    $('#po_number_modal').modal('hide');
                    updateSummaryValues();
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


    $(document).on('input', '#itemTableBody input', function () {
        updateSummaryValues();
    });

    function updateSummaryValues() {
        let totalDiscount = 0;
        let totalVAT = 0;
        let totalReceivedQty = 0;
        let totalOrderQty = 0;
        let totalARN = 0;

        // Loop through each row in itemTable
        $('#itemTableBody tr').each(function () {
            const dis1 = parseFloat($(this).find('[name*="[dis1]"]').val()) || 0;
            const dis2 = parseFloat($(this).find('[name*="[dis2]"]').val()) || 0;
            const dis3 = parseFloat($(this).find('[name*="[dis3]"]').val()) || 0;
            const vat = parseFloat($(this).find('[name*="[vat]"]').val()) || 0;
            const recQty = parseFloat($(this).find('[name*="[rec_qty]"]').val()) || 0;
            const orderQty = parseFloat($(this).find('[name*="[order_qty]"]').val()) || 0;
            const total = parseFloat($(this).find('[name*="[unit_total]"]').val()) || 0;

            totalDiscount += dis1 + dis2 + dis3;
            totalVAT += vat;
            totalReceivedQty += recQty;
            totalOrderQty += orderQty;
            totalARN += total;
        });

        // Update the summary fields
        $('#total_discount').val(totalDiscount.toFixed(2));
        $('#total_vat').val(totalVAT.toFixed(2));
        $('#total_received_qty').val(totalReceivedQty.toFixed(2));
        $('#total_order_qty').val(totalOrderQty.toFixed(2));
        $('#total_arn').val(totalARN.toFixed(2));
    }

    //create arn
    $('#create_arn').on('click', function (e) {
        e.preventDefault();

        const arnNo = $('#arn_no').val();
        const supplier = $('#supplier_id').val();

        if (!arnNo || arnNo.length === 0) {
            swal({
                title: "Error!",
                text: "Please enter an ARN Number",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });

            return;
        }
        if (!supplier || supplier.length === 0) {

            swal({
                title: "Error!",
                text: "Please select a Supplier",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });

            return;
        }

        let items = [];
        $('#itemTableBody tr').each(function () {
            // Skip "No data" row if present
            if ($(this).attr('id') === 'noDataRow') return;
            const $row = $(this);
            const cols = $(this).find('td');
            const itemId = $row.data('item-id');

            items.push({
                item_id: itemId,
                code: $(cols[0]).text().trim(),
                order_qty: parseFloat($(cols[1]).find('input').val()) || 0,
                rec_qty: parseFloat($(cols[2]).find('input').val()) || 0,
                cost: parseFloat($(cols[3]).find('input').val()) || 0,
                dis1: parseFloat($(cols[4]).find('input').val()) || 0,
                dis2: parseFloat($(cols[5]).find('input').val()) || 0,
                dis3: parseFloat($(cols[6]).find('input').val()) || 0,
                actual_cost: parseFloat($(cols[7]).find('input').val()) || 0,
                unit_total: parseFloat($(cols[8]).find('input').val()) || 0,
                list_price: parseFloat($(cols[9]).find('input').val()) || 0,
                cash_price: parseFloat($(cols[10]).find('input').val()) || 0,
                credit_price: parseFloat($(cols[11]).find('input').val()) || 0
            });

        });

        if (items.length === 0) {
            return
            swal({
                title: "Error!",
                text: "No items to submit",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });

        }

        const payload = {
            create: true,
            arn_no: arnNo,
            supplier: supplier,
            arn_date: $('#entry_date').val(),
            lc_no: $('#ci_no').val(),
            bl_no: $('#bl_no').val(),
            pi_no: $('#pi_no').val(),
            department_id: $('#department_id').val(),
            purchase_order_id: $('#purchase_order_id').val(),
            purchase_date: $('#order_date').val(),

            invoice_date: $('#invoice_date').val(),
            entry_date: $('#entry_date').val(),
            total_arn: parseFloat($('#total_arn').val()) || 0,
            total_discount: parseFloat($('#total_discount').val()) || 0,
            total_vat: parseFloat($('#total_vat').val()) || 0,
            total_received_qty: parseFloat($('#total_received_qty').val()) || 0,
            total_order_qty: parseFloat($('#total_order_qty').val()) || 0,
            items: items
        };

        $.ajax({
            url: "ajax/php/arn-master.php",
            type: "POST",
            data: JSON.stringify(payload),
            contentType: "application/json",
            success: function (response) {
                if (response.status === 'success') {
                    swal({
                        title: "Success!",
                        text: "ARN created successfully!",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });

                    setTimeout(() => location.reload(), 2000);
                } else {
                    swal({
                        title: "Error!",
                        text: response.message || "Failed to create ARN.",
                        icon: "error",
                        timer: 2000,
                        buttons: false,
                    });
                }
            },
            error: function () {
                swal({
                    title: "Error!",
                    text: "Server error. Please try again.",
                    icon: "error",
                    timer: 2000,
                    buttons: false,
                });
            }
        });
    });


    //delete purchasse order item
    $(document).on('click', '.delete-purchase-order-item', function (e) {
        e.preventDefault();

        const button = $(this);
        const itemId = button.data('item-id');

        if (!itemId) {
            swal({
                title: "Error!",
                text: "Item ID not found.",
                type: 'error',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete this item?",
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
                    url: "ajax/php/purchase-order.php",
                    type: "POST",
                    data: {
                        item_id: itemId,
                        action: 'delete_items',
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Item has been removed.",
                                type: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Remove row from UI
                            button.closest('tr').remove();
                            updateSummaryValues();
                        } else {
                            swal({
                                title: "Error!",
                                text: "Could not delete item.",
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


