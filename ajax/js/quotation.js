jQuery(document).ready(function () {

    //windows loard
    loadCustomer();
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

    // ----------------------ITEM MASTER SECTION START ----------------------//
    //item master loard with pricess 
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

        if (slicedItems.length > 0) {
            $.each(slicedItems, function (index, item) {
                let rowIndex = start + index + 1;
                tbody += `<tr class="table-primary">
                <td>${rowIndex}</td>
                <td>${item.code} - ${item.name}</td> 
                <td>${item.note}</td>
                <td>${item.total_available_qty}</td>
                <td>${item.group}</td>
                <td>${item.brand}</td>
                <td>${item.category}</td>
                <td>
                    ${item.is_active == 1
                        ? '<span class="badge bg-soft-success font-size-12">Active</span>'
                        : '<span class="badge bg-soft-danger font-size-12">InActive</span>'}
                </td>
            </tr>`;

                if (Array.isArray(item.stock_tmp) && item.stock_tmp.length > 0) {
                    $.each(item.stock_tmp, function (i, row) {
                        tbody += `<tr class="table-light text-danger small">
                        <td colspan="2"><strong>ARN:</strong> ${row.arn_no}<br><span style="color: green;">Department:</span> ${row.department}</td>
                        <td><strong>Qty:</strong> ${row.qty}</td>
                        <td><strong>Cost:</strong> ${parseFloat(row.cost).toFixed(2)}</td>
                        <td><strong>Cash Price:</strong> ${parseFloat(row.cash_price).toFixed(2)}</td>
                        <td><strong>Credit Price:</strong> ${parseFloat(row.credit_price).toFixed(2)}</td>
                        <td><strong>Cash Dis:</strong> ${row.cash_dis}%</td>
                        <td><strong>Credit Dis:</strong> ${row.credit_dis}%</td>
                    </tr>`;
                    });
                }
            });
        } else {
            tbody = `<tr><td colspan="8" class="text-center text-muted">No items found</td></tr>`;
        }

        $('#itemMaster tbody').html(tbody);

        // Update pagination UI
        renderPaginationControls(page);
    }

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
        let page = parseInt($(this).data('page'));
        if (!isNaN(page)) {
            renderPaginatedItems(page);
        }
    });


    //clicka and append values
    $(document).on('click', '#itemMaster tbody tr.table-light', function () {
        // Get the main item row
        let mainRow = $(this).prevAll('tr.table-primary').first();

        // Extract item code and name
        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';
        let qtyText = mainRow.find('td').eq(3).text().trim();

        // Extract cash and credit price from this ARN row
        let cashPriceText = $(this).find('td').eq(3).text().trim();
        let creditPriceText = $(this).find('td').eq(4).text().trim();

        let cashPriceMatch = cashPriceText.match(/[\d,.]+/);
        let creditPriceMatch = creditPriceText.match(/[\d,.]+/);

        let cashPrice = cashPriceMatch ? cashPriceMatch[0] : '';
        let creditPrice = creditPriceMatch ? creditPriceMatch[0] : '';

        // Read selected payment type
        let paymentType = $('#payment_type').val();

        let priceToSet = '';

        if (paymentType == 1) {
            priceToSet = cashPrice;
        } else if (paymentType == 2) {
            priceToSet = creditPrice;
        } else {
            priceToSet = '';
        }

        // Set inputs
        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);
        $('#itemPrice').val(priceToSet);
        $('#stock_level').val(qtyText);


        // Clear qty, discount, payment
        $('#itemQty').val('');
        $('#itemDiscount').val('');
        $('#itemPayment').val('');
        $('#payment_type').prop('disabled', true);
        // Close modal (Bootstrap 5)
        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });

    // ----------------------ITEM MASTER SECTION END ----------------------//

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        location.reload();
    });


    $('#vat_type').on('change', function () {
        let value = $(this).val();

        if (value == '2') {
            $('.cus_width').css('width', '158px').removeClass('hidden');
            $('.th_vat').removeClass('hidden');
            $('.vat_total').removeClass('hidden');
        } else {
            $('.cus_width').css('width', '');
            $('.th_vat').addClass('hidden');
            $('.vat_total').addClass('hidden');
        }
    });

    // Add item to quatation table
    function addItem() {
        const code = $('#itemCode').val().trim();
        const name = $('#itemName').val().trim();
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;
        const payment = parseFloat($('#itemPayment').val()) || 0;
        const vat = parseFloat($('#vat').val()) || 0;
        const vatType = $('#vat_type').val(); // 2 = VAT applicable

        if (!code || !name || price <= 0 || qty <= 0) {
            alert("Please fill valid item details.");
            return;
        }

        let isDuplicate = false;
        $('#quotationItemsBody tr').each(function () {
            const existingCode = $(this).find('td:eq(0)').text().trim();
            if (existingCode === code) {
                isDuplicate = true;
                return false; // Break loop
            }
        });

        if (isDuplicate) {
           swal({
                title: "Duplicate Item!",
                text: `Item "${code}" is already added.`,
                type: 'warning',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        let subtotal = price * qty;
        let discountAmount = subtotal * (discount / 100);
        let total = subtotal - discountAmount;
        let vatAmount = 0;

        if (vatType == '2') {
            vatAmount = total * (vat / 100);
            total += vatAmount;
        }

        // Remove 'No Data' row if exists
        $('#noItemRow').remove();

        // Build VAT column only if applicable
        let vatColumn = '';
        if (vatType == '2') {
            vatColumn = `<td>${vatAmount.toFixed(2)}</td>`;


        }

        const row = `
        <tr>
            <td>${code}</td>
            <td>${name}</td>
            <td>${price.toFixed(2)}</td>
            <td>${qty}</td>
            <td>${discount}%</td>
            <td>${payment.toFixed(2)}</td>
            ${vatColumn}
            <td>${total.toFixed(2)}</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;
        $('#quotationItemsBody').append(row);

        // Clear input fields
        $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #itemPayment').val('');
        $('#vat_amount').val('');

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
        let vatTotal = 0;

        const vatType = $('#vat_type').val();
        const vatRate = parseFloat($('#vat').val()) || 0;

        $('#quotationItemsBody tr').each(function () {
            const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const discount = parseFloat($(this).find('td:eq(4)').text()) || 0;

            const rowSubtotal = price * qty;
            const rowDiscount = rowSubtotal * (discount / 100);
            const rowTotal = rowSubtotal - rowDiscount;

            subTotal += rowSubtotal;
            discountTotal += rowDiscount;

            if (vatType == '2') {
                const rowVAT = rowTotal * (vatRate / 100);
                vatTotal += rowVAT;

            }
        });

        const grandTotal = (subTotal - discountTotal + vatTotal);

        // Update display fields
        $('#finalTotal').val(subTotal.toFixed(2));        // Sub Total
        $('#disTotal').val(discountTotal.toFixed(2));     // Discount Total
        $('#vatTotal').val(vatTotal.toFixed(2));          // Total VAT (add input in form if not present)
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
        const vat = parseFloat($('#vat').val()) || 0;
        const vatType = $('#vat_type').val();

        const subtotal = price * qty;
        const discountedAmount = subtotal * (discount / 100);
        let total = subtotal - discountedAmount;

        // Reset VAT amount field
        $('#vat_amount').val('0.00');

        if (vatType == '2') {
            const vatAmount = total * (vat / 100);
            total += vatAmount;
            $('#vat_amount').val(vatAmount.toFixed(2));
        }

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
