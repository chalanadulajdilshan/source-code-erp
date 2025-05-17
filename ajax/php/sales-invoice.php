<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
session_start();

// Create a new invoice
if (isset($_POST['create'])) {

    $invoiceId = $_POST['invoice_no']; 
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

    $USER = new User($_SESSION['id']);
    $COMPANY_PROFILE = new CompanyProfile($USER->company_id);

    // VAT calculation (18% of net total)
    $vat = round(($netTotal * $COMPANY_PROFILE->vat_percentage) / 100, 2);

    // Grand total = net total + VAT
    $grandTotal = $netTotal + $vat;

    // Create invoice
    $SALES_INVOICE = new SalesInvoice(NULL);

    $SALES_INVOICE->invoice_no = $invoiceId;
    $SALES_INVOICE->invoice_date = date("Y-m-d H:i:s");
    $SALES_INVOICE->company_id = $_POST['company_id'];
    $SALES_INVOICE->customer_id = $_POST['customer_id'];
    $SALES_INVOICE->department_id = $_POST['department_id'];
    $SALES_INVOICE->sale_type = $_POST['sales_type'];
    $SALES_INVOICE->payment_type = $paymentType;
    $SALES_INVOICE->sub_total = $totalSubTotal;
    $SALES_INVOICE->discount = $totalDiscount;
    $SALES_INVOICE->tax = $vat;
    $SALES_INVOICE->grand_total = $grandTotal;
    $SALES_INVOICE->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoiceResult = $SALES_INVOICE->create();

    if ($invoiceResult) {
        $invoiceId = $invoiceResult;

        foreach ($items as $item) {

            $item_discount = isset($item['discount']) ? $item['discount'] : 0;

            $SALES_ITEM = new TempSalesItem(NULL);
            $SALES_ITEM->invoice_id = $invoiceId;
            $SALES_ITEM->item_code = $item['code'];
            $SALES_ITEM->item_name = $item['name'];
            $SALES_ITEM->price = $item['price'];
            $SALES_ITEM->quantity = $item['qty'];
            $SALES_ITEM->discount = $item_discount;
            $SALES_ITEM->total = ($item['price'] * $item['qty']) - $item_discount;

            $SALES_ITEM->created_at = date("Y-m-d H:i:s");
            $SALES_ITEM->create();


            $ITEM_MASTER = new ItemMaster($item['item_id']);

            $currentQty = $ITEM_MASTER->available_qty;
            $newQty = $currentQty - $item['qty'];
            $ITEM_MASTER->available_qty = $newQty;
            $ITEM_MASTER->update();

            $STOCK = new StockTransaction(NULL);
            $STOCK->item_id = $item['item_id'];

            $STOCK_ADJ_TYPE = new StockAdjustmentType(4);
            //SALE
            $STOCK->type = $STOCK_ADJ_TYPE->name;
            $STOCK->date = date("Y-m-d");
            $STOCK->qty_in = 0;
            $STOCK->qty_out = $item['qty'];
            $STOCK->remark = 'Invoice No: ' . $invoiceId;
            $STOCK->created_at = date("Y-m-d H:i:s");
            $STOCK->create();

        }

        echo json_encode([
            "status" => 'success',
            "invoice_id" => $invoiceId,
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
    $invoiceId = $_POST['invoice_id']; // Retrieve invoice ID

    // Create SalesInvoice object and load the data by ID
    $SALES_INVOICE = new SalesInvoice($invoiceId);

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



if (isset($_POST['filter'])) {

    $SALES_INVOICE = new SalesInvoice();
    $response = $SALES_INVOICE->fetchInvoicesForDataTable($_REQUEST);


    echo json_encode($response);
    exit;
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