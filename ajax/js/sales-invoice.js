jQuery(document).ready(function () {


    //WINDOWS LOADER
    loadCustomer();
    getInvoiceData();

    $('#view_price_report').on('click', function (e) {
        e.preventDefault();
        loadItems();
    });

    //LOARD ITEM MASTER
    $('#item_brand_id, #item_category_id, #item_group_id,#item_department_id').on('change', function () {
        loadItems();
    });

    //LOARD ITEM MASTER
    $('#item_item_code').on('keyup', function () {
        loadItems();
    });

    //LOARD ITEM MASTER
    $('#item_master').on('shown.bs.modal', function () {
        loadItems();
    });

    $('#all_item_master').on('shown.bs.modal', function () {
        loadAllItems();
    });

    //PAYMENT TYPE CHANGE
    $('input[name="payment_type"]').on('change', function () {
        getInvoiceData();
        togglePaymentButtons();
    });

    // Initial button state
    togglePaymentButtons();

    // Function to toggle payment/save buttons based on payment type
    function togglePaymentButtons() {
        const paymentType = $('input[name="payment_type"]:checked').val();
        if (paymentType === '1') { // Cash
            $('#payment').show();
            $('#save').hide();
        } else { // Credit
            $('#payment').hide();
            $('#save').show();
        }
    }

    // RESET INPUT FIELDS
    $("#new").click(function (e) {
        e.preventDefault();
        location.reload();
    });

    // BIND ENTER KEY TO ADD ITEM
    $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount').on('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addItem();

        }
    });

    // CALL PAYMENT CALCULATION ON INPUT CHANGE
    $('#itemPrice, #itemQty, #itemDiscount').on('input', calculatePayment);

    // AMOUNT PAID FOCUS
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amountPaid').focus();
    });

    // BIND BUTTON CLICK
    $('#addItemBtn').click(addItem);




    // ----------------------ITEM MASTER SECTION START ----------------------//

    let fullItemList = []; // Global variable
    let itemsPerPage = 20;

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

    function loadAllItems(page = 1) {

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
                renderPaginatedAllItems(page);
            },
            error: function () {
                $('#itemMaster tbody').html(`<tr><td colspan="8" class="text-danger text-center">Error loading data</td></tr>`);
                $('#itemPagination').empty();
            }
        });
    }

    //append to model to data in this funtion
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

                // Main item row
                tbody += `<tr class="table-primary">
                    <td>${rowIndex}</td>
                    <td>${item.code} - ${item.name}</td> 
                    <td>${item.note}</td>
                    <td>${item.total_available_qty}</td>
                    <td>${item.group}</td>
                    <td>${item.brand}</td>
                     <td>${item.category}</td>
                     <td hidden >${item.id}</td>
                </tr>`;

                $('#available_qty').val(item.total_available_qty);

                // Render ARN rows
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
                        $('#available_qty').val(remainingQty);
                    } else {
                        rowClass = 'disabled-arn';
                    }

                    tbody += `
                    <tr class="table-info arn-row ${rowClass}" 
                        data-arn-index="${i}" 
                        data-qty="${totalQty}" 
                        data-used="${usedQty}" 
                        data-arn-id="${arnId}">
                        
                        <td colspan="1"><strong>ARN:</strong> ${arnId}</td>
                        
                        <td>
                            <div><strong>Department:</strong></div>
                            <div>${row.department}</div>
                        </td>
                        
                        <td>
                            <div><strong>Available Qty:</strong></div>
                            <div class="arn-qty">${remainingQty}</div>
                        </td>
                    
                        <td>
                            <div><strong>List Price:</strong></div>
                            <div class='text-danger'><b>${Number(row.list_price).toLocaleString('en-US', { minimumFractionDigits: 2 })}</b></div>
                        </td>
                    
                        <td>
                            <div><strong>Sales Price:</strong></div>
                            <div class='text-danger'><b>${Number(row.invoice_price).toLocaleString('en-US', { minimumFractionDigits: 2 })}</b></div>
                        </td>
                    
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

    function renderPaginatedAllItems(page = 1) {

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

                // Main item row
                tbody += `<tr class="table-primary">
                    <td>${rowIndex}</td>
                    <td>${item.code} - ${item.name}</td> 
                    <td>${item.brand}</td>
                    <td>${item.category}</td>
                    <td>${item.total_available_qty}</td>
                    <td>${item.list_price}</td>
                    <td>${item.invoice_price}</td>
                    <td hidden >${item.id}</td>
                </tr>`;

                $('#available_qty').val(item.total_available_qty);
            });
        } else {
            tbody = `<tr><td colspan="8" class="text-center text-muted">No items found</td></tr>`;
        }

        $('#all_itemMaster tbody').html(tbody);
        renderPaginationControls(page);
    }

    //GET DATA ARN VISE
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

        $('#available_qty').val(remainingQty);
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


    //click the and append values
    $(document).on('click', '#itemMaster tbody tr.table-light', function () {
        let mainRow = $(this).prevAll('tr.table-primary').first();
        let infoRow = $(this).prev('tr.table-info');

        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';


        // Extract available qty from .table-info row
        let qtyRow = $(this).find('td[colspan="2"]').parent().find('td').eq(3).html();
        let qtyMatch = qtyRow.match(/Available Qty:\s*(\d+\.?\d*)/i);
        let availableQty = qtyMatch ? parseFloat(qtyMatch[1]) : 0;

        // Store available qty in map and hidden field
        itemAvailableMap[itemCode] = availableQty;
        $('#available_qty').val(availableQty);

        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);

        $('#itemQty').val('');
        $('#itemDiscount').val('');




        calculatePayment();

        setTimeout(() => $('#itemQty').focus(), 200);

        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });

    $(document).on('click', '#all_itemMaster tbody tr', function () {
        let mainRow = $(this).closest('tr.table-primary'); // ✅ pick the clicked row

        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';
        let itemPrice = mainRow.find('td').eq(6).text().trim();
        let availableQty = mainRow.find('td').eq(4).text().trim();
        let item_id = mainRow.find('td').eq(7).text().trim();
        // Store available qty in map and hidden field

        $('#available_qty').val(availableQty);

        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);

        $('#item_id').val(item_id);
        $('#itemPrice').val(itemPrice);

        calculatePayment();

        setTimeout(() => $('#itemQty').focus(), 200);

        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('all_item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });



    $(document).on('click', '#itemMaster tbody tr.table-info', function () {
        // Get the main item row
        let mainRow = $(this).prevAll('tr.table-primary').first();
        let lastColValue = mainRow.find('td').last().text();

        $('#item_id').val(lastColValue);

        let itemText = mainRow.find('td').eq(1).text().trim();
        let parts = itemText.split(' - ');
        let itemCode = parts[0] || '';
        let itemName = parts[1] || '';
        const tdHtml = $(this).find('td');

        // Extract Available Qty (in td:eq(3))
        let availableQtyText = tdHtml.eq(2).text();
        let qtyMatch = availableQtyText.match(/Available Qty:\s*([\d.,]+)/i);
        let availableQty = qtyMatch ? parseFloat(qtyMatch[1].replace(/,/g, '')) : 0;



        // Extract ARN (in td:eq(0))
        let arnText = tdHtml.eq(0).text();
        let arnMatch = arnText.match(/ARN:\s*(.+)/i);
        let arn = arnMatch ? arnMatch[1].trim() : '';

        //Extract Invoice Price (now from td:eq(5))
        let invoicePriceText = tdHtml.eq(4).text();

        let invoiceMatch = invoicePriceText.match(/Sales Price:\s*([\d.,]+)/i);
        let invoicePrice = invoiceMatch ? parseFloat(invoiceMatch[1].replace(/,/g, '')) : 0;

        // Apply to inputs
        $('#itemCode').val(itemCode);
        $('#itemName').val(itemName);
        $('#itemPrice').val(invoicePrice); // Use cost instead of list_price
        $('#available_qty').val(availableQty);
        $('#arn_no').val(arn); // optiona 

        // Clear qty, discount, payment
        $('#itemQty').val('');
        $('#itemDiscount').val('');
        $('#payment_type').prop('disabled', true);

        calculatePayment();
        setTimeout(() => $('#itemQty').focus(), 200);

        let itemMasterModal = bootstrap.Modal.getInstance(document.getElementById('item_master'));
        if (itemMasterModal) {
            itemMasterModal.hide();
        }
    });

    // ----------------------ITEM MASTER SECTION END ----------------------//



    //CHANGE THE DEPARTMENT VALUES EMPTY        
    $('#department_id').on('change', function () {
        $('#item_id').val('');
        $('#itemCode').val('');
        $('#itemName').val('');
        $('#itemQty').val('');
        $('#itemPrice').val('');
        $('#available_qty').val(0);

    });

    //ITEM MODEL HIDDEN SECTION 
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


    //GET INVOICE ID BY PAYMENT TYPE VISE
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

    // OPEN PAYMENT MODEL AND PRE-FILL TOTAL
    $('#payment').on('click', function () {
        const totalRaw = $('#finalTotal').val();
        const invoiceId = $('#invoice_id').val();

        const total = parseFloat(totalRaw.replace(/,/g, ''));

        if (isNaN(total) || total <= 0) {
            swal({
                title: "Error!",
                text: "Please enter a valid Final Total amount",
                type: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }

        $('#modal_invoice_id').val(invoiceId);
        $('#modalFinalTotal').val(total.toFixed(2));
        $('#amountPaid').val('');
        $('#balanceAmount').val('0.00').removeClass('text-danger');
        $('#paymentModal').modal('show');
    });


    // CALCULATE AND DISPLAY BALANCE OR SHOW INSUFFICIENT MESSAGE
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




    // HANDLE PAYMENT FORM SUBMISSION
    $("#savePayment").click(function (event) {
        event.preventDefault();

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
        const dag_id = $('#dag_id').val();

        if (dag_id != 0) {
            processDAGInvoiceCreation();
        } else {

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

                    processInvoiceCreation();


                },
                error: function () {
                    swal({
                        title: "Error!",
                        text: "Unable to verify Invoice No. right now.",
                        type: 'error',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    //ITEM INVOICE PROCESS
    function processInvoiceCreation() {


        const items = [];
        const dagItems = [];

        //  item invoice to send this php file
        $('#invoiceItemsBody tr').each(function () {
            const code = $(this).find('td:eq(0)').text().trim();
            const name = $(this).find('td:eq(1)').text().trim();
            const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const discount = parseFloat($(this).find('td:eq(4)').text()) || 0;
            const totalItem = parseFloat($(this).find('td:eq(6)').text()) || 0;
            const item_id = $(this).find('input[name="item_id[]"]').val();
            const arn_no = $(this).find('input[name="arn_ids[]"]').val();
            const arn_cost = parseFloat($(this).find('input[name="arn_costs[]"]').val()) || price;


            if (code && !isNaN(totalItem) && item_id) {
                items.push({
                    item_id,
                    code,
                    name,
                    price,
                    qty,
                    discount,
                    total: totalItem,
                    cost: arn_cost, // Using ARN cost instead of price
                    arn_no
                });
            }
        });




        if (items.length === 0 && dagItems.length === 0) {
            swal({
                title: "Error!",
                text: "Please add at least one item.",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const customerName = $('#customer_name').val().trim();
        if (!customerName) {
            swal({
                title: "Error!",
                text: "Please select a customer before creating an invoice.",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            $('#customer_name').focus();
            return;
        }



        let payments = [];
        let finalTotal = parseFloat($('#modalFinalTotal').val()) || 0;
        let totalAmount = 0;

        // Collect all payment rows
        $('#paymentRows .payment-row').each(function () {

            let methodId = $(this).find('.paymentType').val();
            let amount = parseFloat($(this).find('.paymentAmount').val()) || 0;
            let paymentMethod = $(this).find('.paymentType option:selected').text().toLowerCase();

            // Only include cheque details for cheque payments
            let chequeNumber = null;
            let chequeBank = null;
            let chequeDate = '1000-01-01'; // Default valid MySQL date

            if (paymentMethod.includes('cheque')) {
                chequeNumber = $(this).find('input[name="chequeNumber[]"]').val() || null;
                chequeBank = $(this).find('input[name="chequeBank[]"]').val() || null;
                let dateInput = $(this).find('input[name="chequeDate[]"]').val();
                chequeDate = dateInput ? dateInput : '1000-01-01'; // Use default date if not provided
            }

            if (!methodId) {
                swal({
                    title: "Error!",
                    text: "Please select a payment method in all rows.",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
                return false; // break out of each
            }

            if (amount <= 0) {
                swal({
                    title: "Error!",
                    text: "Please enter a valid amount in all rows.",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
                return false; // break out of each
            }

            totalAmount += amount;

            payments.push({
                method_id: methodId,
                amount: amount,
                reference_no: chequeNumber,
                bank_name: chequeBank,
                cheque_date: chequeDate || null
            });
        });


        if (totalAmount !== finalTotal) {
            swal({
                title: "Error!",
                text: "Total amount does not match the final total.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return false;
        }


        const formData = new FormData($('#form-data')[0]);
        formData.append('create', true);
        formData.append('payment_type', $('input[name="payment_type"]:checked').val());
        formData.append('customer_id', $('#customer_id').val());
        formData.append('customer_name', $('#customer_name').val());
        formData.append('customer_mobile', $('#customer_mobile').val());
        formData.append('customer_address', $('#customer_address').val());
        formData.append('invoice_no', $('#invoice_no').val());
        formData.append('items', JSON.stringify(items));
        formData.append('sales_type', $('input[name="payment_type"]:checked').val()); // Using payment_type as sales_type
        formData.append('company_id', $('#company_id').val() || 1); // Default to 1 if not found
        formData.append('department_id', $('#department_id').val() || 1); // Default to 1 if not found
        formData.append('payments', JSON.stringify(payments));

        $('.someBlock').preloader();

        $.ajax({
            url: 'ajax/php/sales-invoice.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                const invoiceId = res.invoice_id;

                // Save regular items
                $.ajax({
                    url: 'ajax/php/sales-invoice-item.php',
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,
                        items: JSON.stringify(items)
                    },
                    success: function () {
                        console.log("Item invoice saved");
                    },
                    error: function () {
                        console.error("Item invoice save failed");
                    }
                });

                // Save DAG items
                $.ajax({
                    url: 'ajax/php/sales-invoice-dag.php',
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,
                        items: JSON.stringify(dagItems)
                    },
                    success: function () {
                        console.log("DAG invoice saved");
                    },
                    error: function () {
                        console.error("DAG invoice save failed");
                    }
                });

                swal({
                    title: "Success!",
                    text: "Invoice saved successfully!",
                    type: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });

                $('#paymentModal').modal('hide');
                window.open("invoice.php?invoice_no=" + invoiceId, "_blank");
                setTimeout(() => location.reload(), 3000);
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

    //PROCESS DAG INVOICE CREATION
    function processDAGInvoiceCreation() {
        const total = parseFloat($('#modalFinalTotal').val());
        const paid = parseFloat($('#amountPaid').val()) || 0;

        if (paid < total) {
            swal({
                title: "Error!",
                text: "Paid amount cannot be less than Final Totalsss",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const dagItems = [];

        $('#dagItemsBodyInvoice tr.dag-item-row').each(function () {
            const code = $(this).find('td:eq(0)').text().trim();
            const name = $(this).find('td:eq(1)').text().trim();
            const price = parseFloat($(this).find('td:eq(2)').text()) || 0;
            const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
            const payment = parseFloat($(this).find('input.price').val()) || 0;
            const totalItem = parseFloat($(this).find('input.totalPrice').val()) || 0;
            const dag_item_id = $(this).find('input#dag_item_id').val();



            if (code && !isNaN(totalItem)) {
                dagItems.push({
                    dag_item_id,
                    code,
                    name,
                    price,
                    qty,
                    payment,
                    total: totalItem
                });
            }
        });

        if (dagItems.length === 0) {
            swal({
                title: "Error!",
                text: "Please add at least one DAG item.",
                type: 'error',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const invoiceId = $('#invoice_no').val();
        if (!invoiceId) {
            swal("Error!", "Invoice ID is missing.", "error");
            return;
        }

        $('.someBlock').preloader();

        // Prepare FormData with all values
        const formData = new FormData($('#form-data')[0]);
        formData.append('create', true);
        formData.append('total', finalTotal);
        formData.append('paid', totalAmount);

        formData.append('invoice_id', invoiceId);
        formData.append('payment_type', $('input[name="payment_type"]:checked').val());
        formData.append('customer_id', $('#customer_id').val());
        formData.append('invoice_no', $('#invoice_no').val());
        formData.append('items', JSON.stringify(dagItems));
        formData.append('dag_id', $('#dag_id').val());

        $.ajax({
            url: 'ajax/php/sales-invoice-dag.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                swal({
                    title: "Success!",
                    text: "DAG Invoice saved successfully!",
                    type: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });

                $('#paymentModal').modal('hide');
                window.open("invoice.php?invoice_no=" + invoiceId, "_blank");
                setTimeout(() => location.reload(), 3000);
            },
            error: function () {
                swal("Error!", "Failed to save DAG invoice.", "error");
            }
        });
    }


    //ADD ITEM TO INVOICE TABLE
    function addItem() {
        const item_id = $('#item_id').val().trim();
        const code = $('#itemCode').val().trim();
        const name = $('#itemName').val().trim();
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;

        let availableQty = parseFloat($('#available_qty').val()) || 0;


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

        const arnId = activeArn.data('arn-id'); // Now declared early
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
        $('#noQuotationItemRow').remove();
        $('#noInvoiceItemRow').remove();

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
                <td class="item-sell-price">${sale_price.toFixed(2)}</td>
                <td>${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-item" data-code="${code}" data-qty="${qty}" data-arn-id="${arnId}">Remove</button>
                </td>
            </tr>
        `;

        $('#invoiceItemsBody').append(row);

        // Clear input fields
        updateFinalTotal()
        $('#itemCode, #itemName, #itemPrice, #itemQty, #itemDiscount, #item_id').val('');


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


    //UPDATE FINAL TOTAL
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


    // EVENT DELEGATION FOR REMOVE BUTTONS
    $(document).on('click', '.btn-remove-item', function () {
        const btn = this;
        const code = $(btn).data('code');
        const qty = parseFloat($(btn).data('qty'));
        const arnId = $(btn).data('arn-id');

        removeRow(btn, code, qty, arnId);
    });

    // REMOVE ITEM ROWinvoiceTable
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


    // CALCULATE PAYMENT
    function calculatePayment() {
        const price = parseFloat($('#itemPrice').val()) || 0;
        const qty = parseFloat($('#itemQty').val()) || 0;
        const discount = parseFloat($('#itemDiscount').val()) || 0;

        const subtotal = price * qty;
        const discountedAmount = subtotal * (discount / 100);
        const total = subtotal - discountedAmount;

        $('#itemPayment').val(total.toFixed(2));
    }

    // Get all ARN IDs from the table
    function getAllArnIds() {
        let arnIds = [];

        $("#invoiceItemsBody .btn-remove-item").each(function () {
            let arnId = $(this).data("arn-id");
            arnIds.push(arnId);
        });

        return arnIds;
    }

    // CANCEL INVOICE FUNCTION

    $(document).on("click", ".cancel-category", function () {

        const invoiceId = $('#invoice_id').val();
        let arnIds = getAllArnIds();


        swal(
            {
                title: "Are you sure?",
                text: "You will not be able to recover this approvel course request.!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Cancel it!",
                closeOnConfirm: false,
            },
            function () {
                $.ajax({
                    url: 'ajax/php/sales-invoice.php',
                    type: "POST",
                    data: {
                        action: 'cancel',
                        id: invoiceId,
                        arnIds: arnIds
                    },
                    dataType: "JSON",
                    success: function (jsonStr) {
                        if (jsonStr.status) {
                            swal({
                                title: "Cancelled!",
                                text: "Your approvel course request has been cancelled.",
                                type: "success",
                                timer: 2000,
                                showConfirmButton: false,
                            });

                            $("#div" + id).remove();
                            window.location.reload();
                        }
                    },
                });
            }
        );
    });

    // ADD CLICK EVENT LISTENER TO CUSTOMER NAME FIELD
    $('#customer_name').on('click', function () {
        // Clear customer-related fields

        $('#customer_name').val('');
        $('#customer_address').val('');
        $('#customer_mobile').val('');

        // Set focus back to customer name for better UX
        $(this).val('').focus();
    });

    $('#quotationBtn').on('click', function () {
        $('#quotationModel').modal('show');
    });


    function fetchQuotationData(quotationId) {

        $.ajax({
            url: 'ajax/php/quotation.php',
            type: 'POST',
            data: {
                action: 'get_quotation',
                id: quotationId
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {

                    const quotation = response.data.quotation;
                    const customer = response.data.customer;
                    const items = response.data.items;
                    // console.log('Quotation:', quotation);
                    console.log('Customer:', customer.customer_code);

                    $('#quotationModal').modal('hide');

                    $('#quotation_ref_no').val(quotation.quotation_no || '');

                    // Set customer information
                    $('#customer_code').val(customer.customer_code || '');
                    $('#customer_name').val(customer.customer_name || '');
                    $('#customer_address').val(customer.address || '');
                    $('#customer_mobile').val(customer.mobile_number || '');

                    $('#invoiceItemsBody').empty();

                    // Add items to the table
                    if (items.length > 0) {
                        items.forEach(function (item) {

                            const discount = parseFloat(item.discount) || 0;
                            const price = parseFloat(item.price) || 0;
                            const qty = parseFloat(item.qty) || 0;
                            const total = parseFloat(item.sub_total) || 0;

                            const row = `
                            <tr>
                                <td>${item.item_code}                                
                                <input type="hidden" class="item-id" value="${item.item_id}"></td>
                                <td>${item.item_name}</td>
                                <td><input type="number" class="item-price form-control form-control-sm price"   value="${price}"  ></td>
                                <td><input type="number" class="item-qty form-control form-control-sm qty" value="${qty}"></td>
                                <td><input type="number" class="item-discount form-control form-control-sm discount" value="${discount}"></td>
                                <td><input type="text" class="item-total form-control form-control-sm totalPrice"  value="${total.toFixed(2)}" readonly>
                                <td><button type="button" class="btn btn-sm btn-danger btn-remove-item" onclick="removeRow(this)">Remove</button></td>
                            </tr>
                            `;

                            $('#invoiceItemsBody').append(row);
                        });
                    } else {
                        // Add "No items" row if no items found
                        $('#invoiceItemsBody').append(`
                            <tr id="noItemRow">
                                <td colspan="8" class="text-center text-muted">No items added</td>
                            </tr>
                        `);
                    }


                } else {
                    alert('No quotation data found');
                }
            }
            ,
            error: function (xhr, status, error) {
                console.error('Error fetching quotation data:', error);
                alert('Failed to load quotation data. Please try again.');
            }
        });
        updateFinalTotal();
    }


    // Row click → populate form
    $("#quotationTableBody tr").on("click", function () {
        const id = $(this).data("id");
        if (id) {
            fetchQuotationData(id);
        }
    });


    //PRINT INVOICE 
    $(document).on("click", "#print", function () {
        const invoiceId = $('#invoice_id').val();


        if (invoiceId === "") {
            swal({
                title: "Warning!",
                text: "Please enter a valid Invoice ID before printing.",
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            window.location.href = "invoice.php?invoice_no=" + invoiceId;
        }


    });


});
