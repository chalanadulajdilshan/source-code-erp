
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
    const code = $('#code').val();
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
    const vatPercent = parseFloat($('#tax').val()) || 0; // assuming this is your VAT input

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

    // Remove "No Data" row if exists
    $('#noDataRow').remove();

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
            <td>
                <button class="btn btn-danger btn-sm deleteRowBtn">Delete</button>
            </td>
        </tr>
    `;
    $('#itemTableBody').append(newRow);

    // --- Update Totals ---
    const totalDiscountPercent = dis1 + dis2 + dis3;
    const discountValue = (unitTotal * totalDiscountPercent) / 100;
    const vatValue = (unitTotal * vatPercent) / 100;

    // Update Total ARN Value
    const currentARN = parseFloat($('#total_arn').val()) || 0;
    $('#total_arn').val((currentARN + unitTotal).toFixed(2));

    // Update Total Discount
    const currentDiscount = parseFloat($('#total_discount').val()) || 0;
    $('#total_discount').val((currentDiscount + discountValue).toFixed(2));

    // Update Total VAT
    const currentVAT = parseFloat($('#total_vat').val()) || 0;
    $('#total_vat').val((currentVAT + vatValue).toFixed(2));

    // Update Total Received Quantity
    const currentRecQty = parseFloat($('#total_received_qty').val()) || 0;
    $('#total_received_qty').val((currentRecQty + recQty).toFixed(2));

    // Update Total Order Quantity
    const currentOrderQty = parseFloat($('#total_order_qty').val()) || 0;
    $('#total_order_qty').val((currentOrderQty + orderQty).toFixed(2));

    // --- Clear Fields ---
    $('#code, #order_qty, #rec_quantity, #cost, #dis_1, #dis_2, #dis_3, #actual_cost, #tax, #list_price, #cash_price, #credit_price, #margin, #unit_total').val('');
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




