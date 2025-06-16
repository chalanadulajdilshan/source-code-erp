jQuery(document).ready(function () {


    //profit report
    $('#view_price_report').on('click', function (e) {
        e.preventDefault();

        loadPriceControlItems();
    });
    //loard Price Control
    $('#brand_id, #category_id, #group_id,#department_id').on('change', function () {
        loadPriceControlItems();
    });

    //loard price item vise 
    $('#item_code').on('keyup', function () {
        loadPriceControlItems();
    });




    function loadPriceControlItems() {
        let brand_id = $('#brand_id').val();
        let category_id = $('#category_id').val();
        let group_id = $('#group_id').val();
        let department_id = $('#department_id').val();
        let item_code = $('#item_code').val().trim();

        $.ajax({
            url: 'ajax/php/report.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'loard_price_Control',
                brand_id: brand_id,
                category_id: category_id,
                group_id: group_id,
                department_id: department_id,
                item_code: item_code
            },
            success: function (data) {
                let tbody = '';
                if (data.length > 0) {
                    $.each(data, function (index, item) {
                        index++;
                        tbody += `<tr class="table-primary">
                        <td>${index}</td>
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
                                tbody += `<tr class="table-light" style="color: red;">
                                <td colspan="2">
                                    <strong>ARN:</strong> ${row.arn_no} 
                                    <span style="display: inline-block; color: green; font-weight: 500;">
                                        <div>Department: <span style="color: black;">${row.department}</span></div>
                                        <div>Available Qty: ${row.qty}</div>
                                    </span>
                                </td>

                                <td>
                                <span style="color: green; font-weight:500" >Cost: </span> 
                                    <input type="number" step="0.01" class="form-control form-control-sm cost-input" data-id="${row.id}" value="${parseFloat(row.cost).toFixed(2)}" />
                                </td>
                                <td>
                                <span style="color: green; font-weight:500" >Cash Price: </span> 
                                    <input type="number" step="0.01" class="form-control form-control-sm cash-price-input" data-id="${row.id}" value="${parseFloat(row.cash_price).toFixed(2)}" />
                                </td>
                                <td>
                                <span style="color: green; font-weight:500" >Credit Price: </span> 
                                    <input type="number" step="0.01" class="form-control form-control-sm credit-price-input" data-id="${row.id}" value="${parseFloat(row.credit_price).toFixed(2)}" />
                                </td>
                                <td>
                                <span style="color: green; font-weight:500" >Cash Dis %: </span> 
                                    <input type="number" step="1" min="0" max="100" class="form-control form-control-sm cash-discount-input" data-id="${row.id}" value="${row.cash_dis}" /> 
                                </td>
                                <td>
                                <span style="color: green; font-weight:500" >Credit Dis %: </span> 
                                    <input type="number" step="1" min="0" max="100" class="form-control form-control-sm credit-discount-input" data-id="${row.id}" value="${row.credit_dis}" /> 
                                </td>
                                <td>${row.created_at}</td>
                            </tr>`;
                            });
                        }
                    });
                } else {
                    tbody = `<tr><td colspan="10" class="text-center text-muted">No items found</td></tr>`;
                }
                $('#priceControl tbody').html(tbody);
            },
            error: function (xhr, status, error) {
                console.error('Error loading items:', error);
                $('#priceControl tbody').html(`<tr><td colspan="9" class="text-danger text-center">Error loading data</td></tr>`);
            }
        });
    }

    // Event delegation for dynamically created inputs
    $('#priceControl tbody').on('change', '.cost-input, .cash-price-input, .credit-price-input, .cash-discount-input, .credit-discount-input', function () {
        let input = $(this);
        let id = input.data('id'); // stock_tmp record ID
        let field = '';

        if (input.hasClass('cost-input')) field = 'cost';
        else if (input.hasClass('cash-price-input')) field = 'cash_price';
        else if (input.hasClass('credit-price-input')) field = 'credit_price';
        else if (input.hasClass('cash-discount-input')) field = 'cash_dis';
        else if (input.hasClass('credit-discount-input')) field = 'credit_dis';

        let value = input.val();

        // Basic validation: non-empty and numeric
        if (value === '' || isNaN(value)) {
            alert('Please enter a valid number');
            return;
        }

        // Preloader start (optional if you use preloader plugin)
        $(".someBlock").preloader();

        $.ajax({
            url: 'ajax/php/report.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_stock_tmp_price',
                id: id,
                field: field,
                value: value
            },
            success: function (response) {

                // Remove preloader
                $(".someBlock").preloader("remove");

                if (response.success) {
                    swal({
                        title: "Success!",
                        text: "Price Updated are successfully.!",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                } else if (response.error) {
                    swal({
                        title: "Error!",
                        text: "Price update error.",
                        type: "error",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            },
            error: function () {
                alert('Error while updating data.');
            }
        });
    });


    //profit report
    $('#view_profit_report').on('click', function (e) {
        e.preventDefault();

        loadProfitReport();
    });


    //Reload on filter change
    $('#brand_id, #department_id, #group_id, #category_id, #filter_type').on('change', function () {
        loadProfitReport();
    });

    //Reload on typing item code
    $('#item_code').on('keyup', function () {
        loadProfitReport();
    });

    //Main function
    function loadProfitReport() {

        let brand_id = $('#brand_id').val();
        let department_id = $('#department_id').val();
        let item_code = $('#item_id').val().trim();
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let filter_type = $('#filter_type').val(); // 1: summary, 2: detail, 3: brand wise

        $.ajax({
            url: 'ajax/php/report.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_profit_report',
                brand_id,
                department_id,
                item_code,
                from_date,
                to_date,
                filter_type
            },
            success: function (data) {
                let tbody = '';
                let totalFinalCost = 0;
                let totalGrandTotal = 0;
                let totalProfit = 0;

                if (data.length > 0) {
                    $.each(data, function (index, row) {
                        index++;
                        const finalCost = parseFloat(row.final_cost);
                        const grandTotal = parseFloat(row.grand_total);
                        const profit = grandTotal - finalCost;

                        totalFinalCost += finalCost;
                        totalGrandTotal += grandTotal;
                        totalProfit += profit;

                        tbody += `<tr>
                <td>${index}</td>
                <td>${row.invoice_no}</td>
                <td>${row.invoice_date}</td>  
                <td>${row.company_name}</td>
                <td>${row.customer_name}</td>
                <td>${row.department_name}</td>
                <td style="color: ${row.sales_type === 'CASH' ? 'green' : row.sales_type === 'CREDIT' ? 'blue' : 'black'};">
                    ${row.sales_type}
                </td>
                <td>${finalCost.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <strong style="color: red;">
                        ${profit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </strong>
                </td>
            </tr>`;
                    });

                    // Add total row
                    tbody += `<tr style="font-weight:bold; background-color:#f1f1f1;">
            <td colspan="7" class="text-end">Total Profit</td>
            <td>${totalFinalCost.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td>${totalGrandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td style="color: red;">
                ${totalProfit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
            </td>
        </tr>`;
                } else {
                    tbody = `<tr><td colspan="10" class="text-center text-muted">No profit data found</td></tr>`;
                }

                $('#profitReport tbody').html(tbody);

                // Optionally show date range above the table
                let fromDate = $('#from_date').val();
                let toDate = $('#to_date').val();
                if (fromDate && toDate) {
                    $('#profitReportDateRange').html(`Profit Report from <strong>${fromDate}</strong> to <strong>${toDate}</strong>`);
                }
            }
            ,
            error: function (xhr, status, error) {
                console.error('Error loading profit report:', error);
                $('#profitReport tbody').html(`<tr><td colspan="8" class="text-danger text-center">Error loading profit report</td></tr>`);
            }
        });
    }

});
