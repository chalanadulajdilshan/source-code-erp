<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new invoice
if (isset($_POST['create'])) {

    $quotationId = $_POST['quotation_id'];
    $items = json_decode($_POST['items'], true); // array of items
    $paid = $_POST['paid'];
    $paymentType = $_POST['payment_type'];

    $totalSubTotal = 0;
    $totalDiscount = 0;

    // Calculate subtotal and discount
    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0; // item-wise discount

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $totalDiscount += $discount;
    }

    $netTotal = $totalSubTotal - $totalDiscount;

    $COMPANY_PROFILE = new CompanyProfile(null);


    // VAT calculation (18% of net total)
    $vat = round(($netTotal * $COMPANY_PROFILE->vat_percentage) / 100, 2);

    // Grand total = net total + VAT
    $grandTotal = $netTotal + $vat;

    // Create invoice
    $SALES_INVOICE = new SalesInvoice(NULL);

    $SALES_INVOICE->invoice_no = $quotationId;
    $SALES_INVOICE->invoice_date = date("Y-m-d H:i:s");
    $SALES_INVOICE->company_id = $_POST['company_id'];
    $SALES_INVOICE->customer_id = $_POST['customer_code'];
    $SALES_INVOICE->department_id = $_POST['department_id'];
    $SALES_INVOICE->sale_type = $_POST['sales_type'];
    $SALES_INVOICE->discount_type = $paymentType;
    $SALES_INVOICE->sub_total = $totalSubTotal;
    $SALES_INVOICE->discount = $totalDiscount;
    $SALES_INVOICE->tax = $vat;
    $SALES_INVOICE->grand_total = $grandTotal;
    $SALES_INVOICE->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoiceResult = $SALES_INVOICE->create();

    if ($invoiceResult) {
        $quotationId = $invoiceResult;

        foreach ($items as $item) {

            $ITEM_MASTER = new ItemMaster(NULL);
            $item_code = $ITEM_MASTER->getIdbyItemCode($item['code']);


            $SALES_ITEM = new TempSalesItem(NULL);
            $SALES_ITEM->temp_invoice_id = $quotationId;
            $SALES_ITEM->product_id = $item['item_id'];;
            $SALES_ITEM->product_name = $item['name'];
            $SALES_ITEM->price = $item['price'];
            $SALES_ITEM->quantity = $item['qty'];
            $SALES_ITEM->discount = isset($item['discount']) ? $item['discount'] : 0;
            $SALES_ITEM->total = ($item['price'] * $item['qty']) - $SALES_ITEM->discount;
            // $SALES_ITEM->user_id = $_SESSION['user_id'];
            $SALES_ITEM->created_at = date("Y-m-d H:i:s");
            $SALES_ITEM->create();
        }

        echo json_encode([
            "status" => 'success',
            "invoice_id" => $quotationId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount,
            "vat" => $vat,
            "grand_total" => $grandTotal
        ]);
        exit();

    } else {
        echo json_encode(["status" => 'error']);
        exit();
    }
}


// Update invoice details
if (isset($_POST['update'])) {
    $quotationId = $_POST['invoice_id']; // Retrieve invoice ID

    // Create SalesInvoice object and load the data by ID
    $SALES_INVOICE = new SalesInvoice($quotationId);

    // Update invoice details
    $SALES_INVOICE->invoice_date = date("Y-m-d H:i:s"); // You can update the date or other details here
    $SALES_INVOICE->company_id = $_POST['company_id'];
    $SALES_INVOICE->customer_id = $_POST['customer_id'];
    $SALES_INVOICE->department_id = $_POST['department_id'];
    $SALES_INVOICE->sale_type = $_POST['sale_type'];
    $SALES_INVOICE->discount_type = $_POST['discount_type'];
    $SALES_INVOICE->sub_total = $_POST['sub_total'];
    $SALES_INVOICE->discount = $_POST['discount'];
    $SALES_INVOICE->tax = $_POST['tax'];
    $SALES_INVOICE->grand_total = $_POST['grand_total']; // New grand total
    $SALES_INVOICE->remark = $_POST['remark'];

    // Attempt to update the invoice
    $result = $SALES_INVOICE->update();

    if ($result) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}

// Delete invoice
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $invoice = new SalesInvoice($_POST['id']);
    $result = $invoice->delete(); // Make sure this method exists in your class

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>