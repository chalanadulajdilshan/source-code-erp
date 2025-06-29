jQuery(document).ready(function () {


    //windows loder
    loadCustomer();
    getInvoiceData();

    $('#view_price_report').on('click', function (e) {
        e.preventDefault();
        loadItems();
    });

    //loard item master
    $('#item_brand_id, #item_category_id, #item_group_id,#item_department_id').on('change', function () {
        loadItems();
    });

    //loard item master
    $('#item_item_code').on('keyup', function () {
        loadItems();
    });

    //loard item master
    $('#item_master').on('shown.bs.modal', function () {
        loadItems();
    });

    //payment type change
    $('input[name="payment_type"]').on('change', function () {
        getInvoiceData();
    });

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        location.reload();
    });
    // Bind Enter key to add item
    $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment').on('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addItem();

        }
    });

    // Call payment calculation on input change
    $('#itemPrice, #itemQty, #itemDiscount').on('input', calculatePayment);

    // Amount Paid focus
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amountPaid').focus();
    });

    // Bind button click
    $('#addItemBtn').click(addItem);



    // ----------------------ITEM MASTER SECTION START ----------------------//


    let fullItemList = []; // Global variable
    let itemsPerPage = 1;

    function loadItems(page = 1) {
        let brand_id = $('#item_brand_id').val();
        let category_id = $('#item_category_id').val();
        let group_id = $('#item_group_id').val();
        let department_id = $('#item_department_id').val();
        let item_code = $('#item_item_code').val().trim();

        $.ajax({
            url: 'ajax/php/report.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'loard_price_Control',
                brand_id,
                category_id,
                group_id,
                department_id,
                item_code
            },
            success: function (data) {
                fullItemList = data || [];
                renderPaginatedItems(page);
            },
            error: function () {
                $('#itemMaster tbody').html(`<tr><td colspan="8" class="text-danger text-center">Error loading data</td></tr>`);
                $('#itemPagination').empty();
            }
        });
    }


    function renderPaginatedItems(page = 1) {
        let start = (page - 1) * itemsPerPage;
        let end = start + itemsPerPage;
        let slicedItems = fullItemList.slice(start, end);
        let tbody = '';

        let usedQtyMap = {};
        $('#invoiceItemsBody tr').each(function () {
            let rowCode = $(this).find('input[name="item_codes[]"]').val();
            let rowArn = $(this).find('input[name="arn_ids[]"]').val();
            let rowQty = parseFloat($(this).find('.item-qty').text()) || 0;
            let key = `${rowCode}_${rowArn}`;


            if (!usedQtyMap[key]) usedQtyMap[key] = 0;
            usedQtyMap[key] += rowQty;
        });

        if (slicedItems.length > 0) {
            $.each(slicedItems, function (index, item) {
                let rowIndex = start + index + 1;

                // 🔹 Main item row
                tbody += `<tr class="table-primary">
                    <td>${rowIndex}</td>
                    <td>${item.code} - ${item.name}</td> 
                    <td>${item.note}</td>
                    <td>${item.total_available_qty}</td>
                    <td>${item.group}</td>
                    <td>${item.brand}</td>
                    <td><strong class="text-danger">${Number(item.list_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong></td>
                    <td>${item.category}</td>
                </tr>`;

                $('#available_qty').val(item.total_available_qty);

                // 🔹 Render ARN rows
                let firstActiveAssigned = false;
                $.each(item.stock_tmp, function (i, row) {
                    const totalQty = parseFloat(row.qty);
                    const arnId = row.arn_no;
                    const itemKey = `${item.code}_${arnId}`;
                    const usedQty = parseFloat(usedQtyMap[itemKey]) || 0;
                    const remainingQty = totalQty - usedQty;

                    let rowClass = '';
                    if (remainingQty <= 0) {
                        rowClass = 'used-arn';
                    } else if (!firstActiveAssigned) {
                        $('.arn-row').removeClass('selected-arn');
                        rowClass = 'active-arn selected-arn';
                        firstActiveAssigned = true;
                        $('#availableQty').val(remainingQty);
                    } else {
                        rowClass = 'disabled-arn';
                    }

                    tbody += `
                    <tr class="table-info arn-row ${rowClass}" 
                        data-arn-index="${i}" 
                        data-qty="${totalQty}" 
                        data-used="${usedQty}" 
                        data-arn-id="${arnId}">
                        <td colspan="2"><strong>ARN:</strong> ${arnId}</td>
                        <td>
                            <div><strong>Department:</strong></div>
                            <div>${row.department}</div>
                        </td>
                        <td colspan="2">
                            <strong>Available Qty:</strong> <span class="arn-qty">${remainingQty}</span>
                        </td>
                        <td><strong>Cost:</strong> ${row.cost}</td>
                        <td colspan="2">${row.created_at}</td>
                    </tr>`;
                });
            });
        } else {
            tbody = `<tr><td colspan="8" class="text-center text-muted">No items found</td></tr>`;
        }

        $('#itemMaster tbody').html(tbody);
        renderPaginationControls(page);
    }


    $(document).on('click', '.arn-row', function () {
        if ($(this).hasClass('disabled-arn') || $(this).hasClass('used-arn')) {
            return;
        }

        // Deselect others
        $('.arn-row').removeClass('active-arn selected-arn');
        $(this).addClass('active-arn selected-arn');

        const totalQty = parseFloat($(this).data('qty')) || 0;
        const usedQty = parseFloat($(this).data('used')) || 0;
        const remainingQty = totalQty - usedQty;

        if (remainingQty <= 0) {
            swal("Warning", "No quantity left in this ARN.", "warning");
            return;
        }

        $('#availableQty').val(remainingQty);
    });



    function renderPaginationControls(currentPage) {
        let totalPages = Math.ceil(fullItemList.length / itemsPerPage);
        let pagination = '';

        if (totalPages <= 1) {
            $('#itemPagination').html('');
            return;
        }

        pagination += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                     <a class="page-link" href="#" data-page="${currentPage - 1}">Prev</a>
                   </li>`;

        for (let i = 1; i <= totalPages; i++) {
            pagination += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                         <a class="page-link" href="#" data-page="${i}">${i}</a>
                       </li>`;
        }

        pagination += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                     <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                   </li>`;

        $('#itemPagination').html(pagination);
    }


    $(document).on('click', '#itemPagination .page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).data('page')) || 1;
        renderPaginatedItems(page);
    });



    let itemAvailableMap = {};
    //clicka and append values
    $(document).on('click', '#itemMaster tbody tr.table-light', function () {
        let mainRow = $(this).prevAll('tr.table-primary').first();
        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';
        let list_price = mainRow.find('td').eq(6).text().trim().replace(/,/g, '') || '0';

        // Extract available qty from .table-info row
        let qtyRow = $(this).find('td[colspan="2"]').parent().find('td').eq(3).html();
        let qtyMatch = qtyRow.match(/Available Qty:\s*(\d+\.?\d*)/i);
        let availableQty = qtyMatch ? parseFloat(qtyMatch[1]) : 0;

        // Store available qty in map and hidden field
        itemAvailableMap[itemCode] = availableQty;
        $('#availableQty').val(availableQty);

        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);
        $('#itemPrice').val(parseFloat(list_price).toFixed(2));
        $('#itemQty').val('');
        $('#itemDiscount').val('');
        $('#itemPayment').val('');
        $('#item_id').val(mainRow.find('td').eq(0).data('item-id') || ''); // If you have it

        calculatePayment();

        setTimeout(() => $('#itemQty').focus(), 200);

        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });


    $(document).on('click', '#itemMaster tbody tr.table-info', function () {
        // Get the main item row
        let mainRow = $(this).prevAll('tr.table-primary').first();

        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';
        let list_price = mainRow.find('td').eq(6).text().replace(/,/g, '').trim(); // Column with price
        const tdHtml = $(this).find('td');

        // Extract Available Qty (in td:eq(3))
        let availableQtyText = tdHtml.eq(2).text();
        let qtyMatch = availableQtyText.match(/Available Qty:\s*([\d.,]+)/i);
        let availableQty = qtyMatch ? parseFloat(qtyMatch[1].replace(/,/g, '')) : 0;

        // Extract Cost (in td:eq(5))
        let costText = tdHtml.eq(5).text();
        let costMatch = costText.match(/Cost:\s*([\d.,]+)/i);
        let cost = costMatch ? parseFloat(costMatch[1].replace(/,/g, '')) : 0;

        // Extract ARN (in td:eq(0))
        let arnText = tdHtml.eq(0).text();
        let arnMatch = arnText.match(/ARN:\s*(.+)/i);
        let arn = arnMatch ? arnMatch[1].trim() : '';



        // Apply to inputs
        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);
        $('#itemPrice').val(list_price); // Use cost instead of list_price
        $('#availableQty').val(availableQty);
        $('#arn_no').val(arn); // optiona 

        // Clear qty, discount, payment
        $('#itemQty').val('');
        $('#itemDiscount').val('');
        $('#itemPayment').val('');
        $('#payment_type').prop('disabled', true);

        calculatePayment();
        setTimeout(() => $('#itemQty').focus(), 200);

        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });

    // ----------------------ITEM MASTER SECTION END ----------------------//


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
        let availableQty = parseFloat($('#availableQty').val()) || 0;

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
        }

        // Find the active ARN row
        const activeArn = $('.arn-row.active-arn').first();
        if (!activeArn.length) {
            swal("Error!", "No active ARN available for item issue.", "error");
            return;
        }

        const arnId = activeArn.data('arn-id'); // ✅ Now declared early
        const arnQty = parseFloat(activeArn.data('qty'));
        const usedQty = parseFloat(activeArn.data('used')) || 0;
        const remainingQty = arnQty - usedQty;

        if (qty > remainingQty) {
            swal("Error!", `Only ${remainingQty} qty available for the current ARN.`, "error");
            return;
        }

        // If item already exists in invoice, remove and restore ARN qty
        let alreadyExists = false;
        $('#invoiceItemsBody tr').each(function () {
            const existingCode = $(this).find('input[name="item_codes[]"]').val();
            const existingArn = $(this).find('input[name="arn_ids[]"]').val();
            if (existingCode === code && existingArn === arnId) {
                const existingQty = parseFloat($(this).find('.item-qty').text()) || 0;

                // Restore used quantity
                const currentUsed = parseFloat(activeArn.data('used')) || 0;
                const newUsed = currentUsed - existingQty;

                activeArn.data('used', newUsed);
                activeArn.find('.arn-qty').text((arnQty - newUsed).toFixed(2));

                $(this).remove();
                alreadyExists = true;
                return false;
            }
        });

        if (alreadyExists) {
            swal("Warning!", "This item from the current ARN is already added.", "warning");
            return;
        }

        const total = (price * qty) - ((price * qty) * (discount / 100));
        $('#noItemRow').remove();

        const row = `
            <tr>
                <td>${code}
                    <input type="hidden" name="item_id[]" value="${item_id}">
                    <input type="hidden" name="item_codes[]" value="${code}">
                    <input type="hidden" name="arn_ids[]" value="${arnId}">
                </td>
                <td>${name}</td>
                <td class="item-price">${price.toFixed(2)}</td>
                <td class="item-qty">${qty}</td>
                <td class="item-discount">${discount}</td>
                <td>${payment.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this, '${code}', ${qty}, '${arnId}')">Remove</button>
                </td>
            </tr>
        `;

        $('#invoiceItemsBody').append(row);

        // Clear input fields
        updateFinalTotal()
        $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment, #item_id').val('');


        const newUsedQty = usedQty + qty;
        activeArn.data('used', newUsedQty);

        remainingQty = arnQty - newUsedQty;
        activeArn.find('.arn-qty').text(remainingQty.toFixed(2));

        // Disable ARN if fully used
        if (remainingQty <= 0) {
            activeArn.removeClass('active-arn').addClass('used-arn');
            activeArn.find('.arn-qty').text('0');

            // Activate the next available ARN
            const nextArn = activeArn.nextAll('.arn-row.disabled-arn').first();
            if (nextArn.length) {
                nextArn.removeClass('disabled-arn').addClass('active-arn');
            }
        }

        $('.arn-row').each(function () {
            const qty = parseFloat($(this).data('qty')) || 0;
            const used = parseFloat($(this).data('used')) || 0;
            const remaining = qty - used;

            if (remaining <= 0) {
                $(this).removeClass('active-arn selected-arn').addClass('disabled-arn');
                $(this).find('.arn-qty').text('0');
            }
        });

        ;
    }
    function updateFinalTotal() {

        let subTotal = 0;
        let discountTotal = 0;
        let taxTotal = 0;

        $('#invoiceItemsBody tr').each(function () {
            const qty = parseFloat($(this).find('.item-qty').text().replace(/,/g, '')) || 0;
            const price = parseFloat($(this).find('.item-price').text().replace(/,/g, '')) || 0;
            const discount = parseFloat($(this).find('.item-discount').text().replace(/,/g, '')) || 0;

            const itemTotal = price * qty;
            const itemDiscount = itemTotal * (discount / 100);
            const itemTax = 0;

            subTotal += itemTotal;
            discountTotal += itemDiscount;
            taxTotal += itemTax;
        });

        const grandTotal = subTotal - discountTotal + taxTotal;
        $('#subTotal').val(subTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#disTotal').val(discountTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#tax').val(taxTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#finalTotal').val(grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

    }




    // Remove item row
    function removeRow(btn, code, qty, arnId) {
        $(btn).closest('tr').remove();

        const arnRow = $(`.arn-row[data-arn-id="${arnId}"]`);
        let usedQty = parseFloat(arnRow.data('used')) || 0;
        let newUsedQty = usedQty - qty;

        arnRow.data('used', newUsedQty);
        arnRow.find('.arn-qty').text(parseFloat(arnRow.data('qty')) - newUsedQty);

        // Reactivate if previously marked as used
        if (arnRow.hasClass('used-arn')) {
            arnRow.removeClass('used-arn').addClass('active-arn');

            // Re-disable next ARN if unused
            const nextArn = arnRow.nextAll('.arn-row.active-arn').first();
            if (nextArn.length && parseFloat(nextArn.data('used')) === 0) {
                nextArn.removeClass('active-arn').addClass('disabled-arn');
            }
        }

        updateFinalTotal();
    }


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





});
