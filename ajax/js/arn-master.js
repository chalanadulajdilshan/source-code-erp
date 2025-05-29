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

        $('#item_master').modal('hide');
    });

 


    function calculatePayment() {
      
        const recQty = parseFloat($('#rec_quantity').val()) || 0;
        const comCost = parseFloat($('#cost').val()) || 0;
  
        const dis1 = parseFloat($('#dis_1').val()) || 0;
        const dis2 = parseFloat($('#dis_2').val()) || 0;
        const dis3 = parseFloat($('#dis_3').val()) || 0;

        const listPrice = parseFloat($('#list_price').val()) || 0;
        const cashPrice = parseFloat($('#cash_price').val()) || 0;
        const creditPrice = parseFloat($('#credit_price').val()) || 0;

        // Step 1: Apply discount 1
        let disAmount1 = comCost * (dis1 / 100);
        let priceAfterDis1 = comCost - disAmount1;

        // Step 2: Apply discount 2 on result of discount 1
        let disAmount2 = priceAfterDis1 * (dis2 / 100);
        let priceAfterDis2 = priceAfterDis1 - disAmount2;

        // Step 3: Apply discount 3 on result of discount 2
        let disAmount3 = priceAfterDis2 * (dis3 / 100);
        let finalCost = priceAfterDis2 - disAmount3;

        // Step 4: Unit total = finalCost × received qty
        let unitTotal = finalCost * recQty;

        // Step 5: Tax (optional logic – here 15% VAT as an example)
        let tax = finalCost * 0.15;

        // Step 6: Margin % from list price
        let margin = listPrice > 0 ? ((listPrice - finalCost) / finalCost) * 100 : 0;

        // Display values
        $('#actual_cost').val(finalCost.toFixed(2));
        $('#unit_total').val(unitTotal.toFixed(2));
        $('#tax').val(tax.toFixed(2));
        $('#margin').val(margin.toFixed(2));
    }

    $('#addItemBtn').on('click', function () {
        const code = $('#itemCode').val(); // Fixed selector
        let orderQty = parseFloat($('#order_qty').val()) || 0;
        const recQty = parseFloat($('#rec_quantity').val()) || 0;
        const cost = parseFloat($('#cost').val()) || 0;
        const dis1 = parseFloat($('#dis_1').val()) || 0;
        const dis2 = parseFloat($('#dis_2').val()) || 0;
        const dis3 = parseFloat($('#dis_3').val()) || 0;
        const actualCost = parseFloat($('#actual_cost').val()) || 0;
        const unitTotal = parseFloat($('#unit_total').val()) || 0;
        const listPrice = parseFloat($('#list_price').val()) || 0;
        const cashPrice = parseFloat($('#cash_price').val()) || 0;
        const creditPrice = parseFloat($('#credit_price').val()) || 0;
        const tax = parseFloat($('#tax').val()) || 0;

        // Validation
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
        if (!orderQty || orderQty === 0) {
            orderQty = recQty;
            $('#order_qty').val(recQty);
        }

        $('#noDataRow').remove(); // Remove "No Data" row if present

        // Add item to table
        const newRow = `
        <tr>
            <td>${code}</td>
            <td>${orderQty}</td>
            <td>${recQty}</td>
            <td>${cost.toFixed(2)}</td>
            <td>${dis1} / ${dis2} / ${dis3}</td>
            <td>${actualCost.toFixed(2)}</td>
            <td>${unitTotal.toFixed(2)}</td>
            <td>${listPrice.toFixed(2)}</td>
            <td>${cashPrice.toFixed(2)}</td>
            <td>${creditPrice.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm deleteRowBtn">Delete</button></td>
        </tr>
    `;
        $('#itemTableBody').append(newRow);

        // Totals
        const currentARN = parseFloat($('#total_arn').val()) || 0;
        $('#total_arn').val((currentARN + unitTotal).toFixed(2));

        const currentVAT = parseFloat($('#total_vat').val()) || 0;
        $('#total_vat').val((currentVAT + tax * recQty).toFixed(2));

        const currentDiscount = parseFloat($('#total_discount').val()) || 0;
        const discountValue = (cost - actualCost) * recQty;
        $('#total_discount').val((currentDiscount + discountValue).toFixed(2));
    });


    //create arn
    $('#create_arn').on('click', function (e) {
        e.preventDefault();

        // Optional validation for header fields
        const arnNo = $('#arn_no').val();
        const supplier = $('#customer_code').val();

        if (!$("#arn_no").val() || $("#arn_no").val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter an ARN Number",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });
        } else if (!$("#customer_code").val() || $("#customer_code").val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a Supplier",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });
        } else {

            // Collect item table data
            let items = [];
            $('#itemTableBody tr').each(function () {
                const cols = $(this).find('td');
                items.push({
                    code: $(cols[0]).text(),
                    order_qty: parseFloat($(cols[1]).text()) || 0,
                    rec_qty: parseFloat($(cols[2]).text()) || 0,
                    cost: parseFloat($(cols[3]).text()) || 0,
                    dis1: parseFloat($(cols[4]).text().split('/')[0]) || 0,
                    dis2: parseFloat($(cols[4]).text().split('/')[1]) || 0,
                    dis3: parseFloat($(cols[4]).text().split('/')[2]) || 0,
                    actual_cost: parseFloat($(cols[5]).text()) || 0,
                    unit_total: parseFloat($(cols[6]).text()) || 0,
                    list_price: parseFloat($(cols[7]).text()) || 0,
                    cash_price: parseFloat($(cols[8]).text()) || 0,
                    credit_price: parseFloat($(cols[9]).text()) || 0
                });
            });

            if (items.length === 0) {
                return swal({ title: "Error!", text: "No items to submit.", type: "error", timer: 2000, showConfirmButton: false });
            }

            // Prepare header + item data
            const payload = {
                create: true,
                arn_no: arnNo,
                supplier: supplier,
                arn_date: $('#entry_date').val(),
                lc_no: $('#ci_no').val(),
                bl_no: $('#bl_no').val(),
                invoice_date: $('#invoice_date').val(),
                entry_date: $('#entry_date').val(),
                total_arn: parseFloat($('#total_arn').val()) || 0,
                total_discount: parseFloat($('#total_discount').val()) || 0,
                total_vat: parseFloat($('#total_vat').val()) || 0,
                total_received_qty: parseFloat($('#total_received_qty').val()) || 0,
                total_order_qty: parseFloat($('#total_order_qty').val()) || 0,
                items: items
            };

            // Send AJAX to server
            $.ajax({
                url: "ajax/php/arn-master.php",
                type: "POST",
                data: JSON.stringify(payload),
                contentType: "application/json",
                success: function (response) {
                    if (response.status === 'success') {
                        swal({ title: "Success!", text: "ARN created successfully.", type: "success", timer: 2000, showConfirmButton: false });
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        swal({ title: "Error!", text: "Failed to create ARN.", type: "error", timer: 2000, showConfirmButton: false });
                    }
                }
            });
        }
    });

});


