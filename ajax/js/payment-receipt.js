jQuery(document).ready(function () {

    function updateChequeTotal() {
        let total = 0;
        $("#chequeBody tr").each(function () {
            const amount = $(this).find(".cheque-amount").data("amount");
            if (amount) {
                total += parseFloat(amount);
            }
        });
        $("#cheque_total").val(formatAmount(total));
        $("#cheque_balance").val(formatAmount(total));

    }

    function isValidChequeNo(chequeNo) {
        const chequePattern = /^\d{6,12}$/; // 6 to 12 digits
        return chequePattern.test(chequeNo);
    }

    function isValidDate(dateStr) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const inputDate = new Date(dateStr);
        return !isNaN(inputDate.getTime()) && inputDate >= today;
    }

    $('#add_cheque').on('click', function () {
        const chequeNo = $('#cheque_no').val().trim();
        const chequeDate = $('#cheque_date').val().trim();
        const bankBranch = $('#bank_branch_name').val().trim();
        const bankBranchId = $('#bank_branch').val().trim();
        const amount = $('#amount').val().trim();
        const cheque_total = parseFloat($('#cheque_total').val().replace(/,/g, '')) || 0;
        const outstanding = parseFloat($('#outstanding').val().replace(/,/g, '')) || 0;

        // Validation
        if (!isValidChequeNo(chequeNo)) {
            return swal("Invalid Cheque Number", "Cheque number should be 6–12 digits.", "error");
        }

        if (!isValidDate(chequeDate)) {
            return swal("Invalid Cheque Date", "Cheque date must be today or a future date.", "error");
        }

        if (!bankBranch || !bankBranchId) {
            return swal("Missing Bank", "Please select a valid Bank & Branch.", "error");
        }

        if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
            return swal("Invalid Amount", "Amount should be a number greater than 0.", "error");
        }

        if (parseFloat(cheque_total) > parseFloat(outstanding) || parseFloat(amount) > parseFloat(outstanding)) {
            return swal("Exceeded Outstanding", "You added more than the Outstanding Amount.", "error");
        }


        // Remove "no item" row if exists
        $('#noItemRow').remove();

        const newRow = `
            <tr>
                <td>${chequeNo}<input type="hidden" name="cheque_nos[]" value="${chequeNo}"></td>
                <td>${chequeDate}<input type="hidden" name="cheque_dates[]" value="${chequeDate}"></td>
                <td>${bankBranch}<input type="hidden" name="bank_branches[]" value="${bankBranchId}"></td>
                      <td class="cheque-amount" data-amount="${amount}">${formatAmount(amount)}<input type="hidden" name="cheque_amounts[]" value="${amount}"></td> 
                <td> <button type="button" class="btn btn-sm btn-danger remove-row" >Remove</button></td>
               
            </tr>
        `;

        $('#chequeBody').append(newRow);
        updateChequeTotal();

        // Clear inputs
        $('#cheque_no').val('');
        $('#cheque_date').val('');
        $('#bank_branch_name').val('');
        $('#bank_branch').val('');
        $('#amount').val('');
    });
    $('#cheque_no, #cheque_date, #bank_branch_name, #amount').on('keypress', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            $('#add_cheque').click();
        }
    });
    // Handle row removal
    $('#chequeBody').on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        if ($("#chequeBody tr").length === 0) {
            $('#chequeBody').append(`<tr id="noItemRow"><td colspan="5" class="text-center text-muted">No items added</td></tr>`);
        }
        updateChequeTotal();
    });

    $(document).on("click", ".select-branch", function () {
        const branchId = $(this).data("id");
        const bankBranchName = $(this).find("td:eq(2)").text();

        // Set values in input fields
        $("#bank_branch").val(branchId);
        $("#bank_branch_name").val(bankBranchName);

        // Close the modal
        $("#branch_master").modal("hide");

    });


    // add customers for same formate 
    $('#customerModal').on('shown.bs.modal', function () {
        loadCustomerTable();
    });
    //loard customers all
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
                    d.category = 1;
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
                { data: "mobile_number", title: "Mobile" },
                { data: "email", title: "Email" },
                { data: "category", title: "Category" },
                { data: "province", title: "Province" },
                { data: "credit_limit", title: "Credit Limit" },
                { data: "outstanding", title: "Outstanding" },
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
                $('#outstanding').val(data.outstanding);

                $('#customerModal').modal('hide');
                loadCustomerCreditInvoices(data.id);
            }
        });
    }

    //loard firrst customer Credit Invoices
    function loadCustomerCreditInvoices(customerId) {

        if (!customerId) return;

        $.ajax({
            url: 'ajax/php/payment-receipt.php',
            type: 'POST',
            data: {
                action: 'get_credit_invoices',
                customer_id: customerId
            },
            dataType: 'json',
            success: function (response) {

                if (response.success && response.data.length > 0) {
                    $('#invoiceBody').empty();

                    let subTotal = 0;
                    response.data.forEach(function (invoice) {
                        const invoiceValue = parseFloat(invoice.grand_total || 0);
                        const paidAmount = parseFloat(invoice.paid_amount || 0);
                        const overdue = invoiceValue - paidAmount;
                        subTotal += invoiceValue;

                        const row = `
                    <tr>
                        <td>${invoice.invoice_date}</td>
                        <td>${invoice.invoice_no}</td>
                        <td>${formatAmount(invoiceValue)}</td>
                        <td>${formatAmount(paidAmount)}</td>
                        <td><span class="text-danger fw-bold invoice-overdue">${formatAmount(overdue)}</span></td>
                        <td><input type="text" name="cheque_pay[]" class="form-control form-control-sm cheque-pay" value="0.00"></td>
                         <td><input type="text" name="cash_pay[]" class="form-control form-control-sm cash-pay" value="0.00"></td>
                        <td>${formatAmount(overdue)}</td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="uil uil-trash"></i></button></td>
                    </tr>`;
                        $('#invoiceBody').append(row);
                    });

                    $('#finalTotal').val(formatAmount(subTotal));
                    $('#grandTotal').val(formatAmount(subTotal));
                    $('#disTotal').val(formatAmount(0));
                } else {
                    $('#invoiceBody').html(`<tr><td colspan="11" class="text-center text-muted">No items found</td></tr>`);
                    $('#finalTotal, #grandTotal, #disTotal').val("0.00");
                }
            },
            error: function (xhr) {
                console.error("Failed to fetch invoices", xhr);
            }
        });
    }

    //number formate loard 
    function formatAmount(amount) {
        return parseFloat(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }


    $(document).on('input', '.cheque-pay', function () {
        const $row = $(this).closest('tr');
        let inputVal = parseFloat($(this).val()) || 0;

        // Get Overdue text, remove commas, convert to float
        const overdueText = $row.find('.invoice-overdue').text().trim() || "0";
        const overdue = parseFloat(overdueText.replace(/,/g, '')) || 0;

        // Get total cheque amount from #cheque_total
        const chequeTotal = parseFloat($('#cheque_total').val().replace(/,/g, '')) || 0;

        // Calculate total assigned except current input
        let totalAssigned = 0;
        $('.cheque-pay').not(this).each(function () {
            totalAssigned += parseFloat($(this).val()) || 0;
        });

        const remainingBalance = chequeTotal - totalAssigned;

        // Validate current input
        if (inputVal > overdue || inputVal > remainingBalance) {
            const allowed = Math.min(overdue, remainingBalance);
            $(this).val(allowed.toFixed(2));

            swal({
                title: "Invalid Amount!",
                text: `You can't enter more than Overdue (Rs. ${overdueText}) or Remaining Cheque Balance (Rs. ${remainingBalance.toFixed(2)})`,
                type: "error",
                timer: 3000,
                showConfirmButton: false,
            });

            inputVal = allowed;
        }

        // Recalculate new assigned total
        let newAssignedTotal = 0;
        $('.cheque-pay').each(function () {
            newAssignedTotal += parseFloat($(this).val()) || 0;
        });

        // Update balance field
        const newBalance = chequeTotal - newAssignedTotal;
        $('#cheque_balance').val(newBalance < 0 ? '0.00' : newBalance.toFixed(2));
    });

    $(document).on('input', '.cash-pay', function () {
        const $row = $(this).closest('tr');
        let inputVal = parseFloat($(this).val()) || 0;

        // Get Overdue text, remove commas, convert to float
        const overdueText = $row.find('.invoice-overdue').text().trim() || "0";
        const overdue = parseFloat(overdueText.replace(/,/g, '')) || 0;

        // Get total cash amount from #cash_total (assuming you have this field like #cheque_total)
        const cashTotal = parseFloat($('#cash_total').val().replace(/,/g, '')) || 0;

        // Calculate total assigned cash except current input
        let totalAssigned = 0;
        $('.cash-pay').not(this).each(function () {
            totalAssigned += parseFloat($(this).val()) || 0;
        });

        const remainingBalance = cashTotal - totalAssigned;

        // Validate current input
        if (inputVal > overdue || inputVal > remainingBalance) {
            const allowed = Math.min(overdue, remainingBalance);
            $(this).val(allowed.toFixed(2));
            swal({
                title: "Invalid Amount!",
                text: `You can't enter more than Overdue (Rs. ${overdueText}) or Remaining Cheque Balance (Rs. ${remainingBalance.toFixed(2)})`,
                type: "error",
                timer: 3000,
                showConfirmButton: false,
            });


            inputVal = allowed;
        }

        // Recalculate new assigned total
        let newAssignedTotal = 0;
        $('.cash-pay').each(function () {
            newAssignedTotal += parseFloat($(this).val()) || 0;
        });

        // Update cash balance field
        const newBalance = cashTotal - newAssignedTotal;
        $('#cash_balance').val(newBalance < 0 ? '0.00' : newBalance.toFixed(2));
    });

    $('#cash_total').on('input', function () {
        const totalCash = parseFloat($(this).val()) || 0;
        $('#cash_balance').val(totalCash.toFixed(2));
    });


    $('#utilize').on('click', function (e) {
        e.preventDefault();

        // Check if there is any unused balance
        const chequeBalance = parseFloat($('#cheque_balance').val()) || 0;
        const cashBalance = parseFloat($('#cash_balance').val()) || 0;

        if (chequeBalance > 0 || cashBalance > 0) {
            swal("Unused Balance Detected", "Please utilize the remaining cheque or cash balance before proceeding.", "info");
        }

        const $invoiceBody = $('#invoiceBody');
        const $invoiceRows = $invoiceBody.find('tr').not('#noItemRow');
        const $utilizationBody = $('#utilizationBody');

        // Clear previous utilization rows
        $utilizationBody.empty();

        // If no invoice rows, show no data and alert
        if ($invoiceRows.length === 0) {
            showNoPaymentData();
            return;
        }

        // Today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        let rowsAppended = 0;

        $invoiceRows.each(function () {
            const $row = $(this);

            // Extract invoice number and date from columns
            const invNo = $row.find('td').eq(1).text().trim();
            const invDate = $row.find('td').eq(0).text().trim();

            // Extract payment amounts from inputs or elements with these classes
            let chequePay = parseFloat($row.find('.cheque-pay').val()) || 0;
            let cashPay = parseFloat($row.find('.cash-pay').val()) || 0;

            const totalAmount = chequePay + cashPay;

            // Skip if no payment
            if (totalAmount <= 0) return true; // continue to next row

            // Determine payment type label
            let payType = "";
            if (chequePay > 0 && cashPay > 0) {
                payType = "Cheque + Cash";
            } else if (chequePay > 0) {
                payType = "Cheque";
            } else {
                payType = "Cash";
            }

            // Calculate difference in days between invoice date and today
            const invDateObj = new Date(invDate);
            const todayDateObj = new Date(today);
            const diffMs = invDateObj - todayDateObj;
            const diffDays = Math.abs(Math.floor(diffMs / (1000 * 60 * 60 * 24)));

            // Build and append row HTML
            const rowHtml = `
            <tr> 
            <td>${invNo}</td>
                  <td>${invDate}</td>
                <td>${payType}</td>
                <td>${today}</td>
                <td>${totalAmount.toFixed(2)}</td>
                <td>${diffDays}</td>
            </tr>
        `;

            $utilizationBody.append(rowHtml);
            rowsAppended++;
        });

        // If no rows appended (all zero amounts), show no data and alert
        if (rowsAppended === 0) {
            showNoPaymentData();
            return;
        }

        // Show the utilization summary section with slideDown animation
        $('#utilization_summary').slideDown();

        // Helper function to show no data message and alert
        function showNoPaymentData() {
            $utilizationBody.html(`
            <tr class="no-data">
                <td colspan="6" class="text-center text-muted">No payment data available</td>
            </tr>
        `);
            swal("No Payments", "There is no payment data to show in the summary.", "warning");
        }

        // Disable cash total input
        $('#cash_total').prop('disabled', true);
        $('#cheque_no').prop('disabled', true);
        $('#cheque_date').prop('disabled', true);
        $('#bank_branch_name').prop('disabled', true);
        $('#amount').prop('disabled', true);
        $('#bank_branch_name').siblings('button').prop('disabled', true);
        $('.cheque-pay').prop('disabled', true);
        $('.cash-pay').prop('disabled', true);
        $('button[data-bs-target="#customerModal"]').prop('disabled', true);
        $('#add_cheque').prop('disabled', true);

    });

    $("#new").click(function (e) {
        e.preventDefault();

        // Reload the current page
        location.reload();
    });




});
