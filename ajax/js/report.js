jQuery(document).ready(function () {


    //windows loard price control
    // loadPriceControlItems();

    // $('#brand_id, #category_id, #group_id,#department_id').on('change', function () {
    //     loadPriceControlItems();
    // });

    // $('#item_code').on('keyup', function () {
    //     loadPriceControlItems();
    // });

    // function loadPriceControlItems() {
    //     let brand_id = $('#brand_id').val();
    //     let category_id = $('#category_id').val();
    //     let group_id = $('#group_id').val();
    //     let department_id = $('#department_id').val();
    //     let item_code = $('#item_code').val().trim();

    //     $.ajax({
    //         url: 'ajax/php/report.php',
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {
    //             action: 'load_filtered',
    //             brand_id: brand_id,
    //             category_id: category_id,
    //             group_id: group_id,
    //             department_id: department_id,
    //             item_code: item_code
    //         },
    //         success: function (data) {
    //             let tbody = '';
    //             if (data.length > 0) {
    //                 $.each(data, function (index, item) {
    //                     index++
    //                     tbody += `<tr>
    //                         <td>${index}</td>
    //                         <td>${item.code} - ${item.name}</td>
    //                         <td>${item.note}</td>
    //                        <td> ${item.departments
    //                             .filter(dep => parseFloat(dep.quantity) > 0)
    //                             .map(dep => `${dep.department_name} - <span style="color:red;">${dep.quantity}</span>`)
    //                             .join('<br>')}
    //                         </td>

    //                         <td>${parseFloat(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>

    //                         <td>${parseFloat(item.list_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
    //                         <td>${parseFloat(item.cash_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
    //                         <td>${parseFloat(item.credit_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
    //                         <td>${item.cash_discount}</td>
    //                         <td>${item.credit_discount}</td>
    //                     </tr>`;
    //                 });
    //             } else {
    //                 tbody = `<tr><td colspan="9" class="text-center text-muted">No items found</td></tr>`;
    //             }
    //             $('#priceControl tbody').html(tbody);
    //         },
    //         error: function (xhr, status, error) {
    //             console.error('Error loading items:', error);
    //             $('#priceControl tbody').html(`<tr><td colspan="9" class="text-danger text-center">Error loading data</td></tr>`);
    //         }
    //     });
    // }

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
