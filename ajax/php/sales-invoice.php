<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
session_start();


if (isset($_POST['action']) && $_POST['action'] == 'check_invoice_id') {


    $invoice_no = trim($_POST['invoice_no']);
    $SALES_INVOICE = new SalesInvoice(NULL);
    $res = $SALES_INVOICE->checkInvoiceIdExist($invoice_no);

    // Send JSON response
    echo json_encode(['exists' => $res]);
    exit();
}


// Create a new invoice
if (isset($_POST['create'])) {

    $invoiceId = $_POST['invoice_no'];
    $items = json_decode($_POST['items'], true); // array of items 
    $paid = $_POST['paid'];
    $paymentType = $_POST['payment_type'];


    $totalSubTotal = 0;
    $totalDiscount = 0;
    $final_cost = 0;
    
    // Calculate subtotal and discount
    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0; // item-wise discount

        $ITEM_MASTER = new ItemMaster($item['item_id']);

        $cost = $ITEM_MASTER->cost;
        $final_cost_item = $cost * $item['qty'];
        $final_cost += $final_cost_item;

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
    $SALES_INVOICE->final_cost = $final_cost;
    $SALES_INVOICE->payment_type = $paymentType;
    $SALES_INVOICE->sub_total = $totalSubTotal;
    $SALES_INVOICE->discount = $totalDiscount;
    $SALES_INVOICE->tax = $vat;
    $SALES_INVOICE->grand_total = $grandTotal;
    $SALES_INVOICE->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoiceResult = $SALES_INVOICE->create();
    $DOCUMENT_TRACKING = new DocumentTracking(null);

    if ($paymentType == 'cash') {
        $DOCUMENT_TRACKING->incrementDocumentId('cash');
    } else if ($paymentType == 'credit') {
        $DOCUMENT_TRACKING->incrementDocumentId('credit');
    } else {

        $DOCUMENT_TRACKING->incrementDocumentId('invoice');
    }
    if ($invoiceResult) {
        $invoiceId = $invoiceResult;

        foreach ($items as $item) {

            $item_discount = isset($item['discount']) ? $item['discount'] : 0;

            $ITEM_MASTER = new ItemMaster($item['item_id']);

            $SALES_ITEM = new TempSalesItem(NULL);
            $SALES_ITEM->invoice_id = $invoiceId;
            $SALES_ITEM->item_code = $item['item_id'];
            $SALES_ITEM->item_name = $item['name'];
            $SALES_ITEM->cost = $ITEM_MASTER->cost;
            $SALES_ITEM->price = $item['price'];
            $SALES_ITEM->quantity = $item['qty'];
            $SALES_ITEM->discount = $item_discount;
            $SALES_ITEM->total = ($item['price'] * $item['qty']) - $item_discount;

            $SALES_ITEM->created_at = date("Y-m-d H:i:s");
            $SALES_ITEM->create();

            //stock master update quantity
            $STOCK_MASTER = new StockMaster(NULL);

            $currentQty = $STOCK_MASTER->getAvailableQuantity($_POST['department_id'], $item['item_id']);
            $newQty = $currentQty - $item['qty'];
            $STOCK_MASTER->quantity = $newQty;
            $STOCK_MASTER->updateQtyByItemAndDepartment($_POST['department_id'], $item['item_id'], $newQty);

            $STOCK_TRANSACTION = new StockTransaction(NULL);
            $STOCK_TRANSACTION->item_id = $item['item_id'];

            //stock transaction table update
            $STOCK_TRANSACTION->type = 4;
            $STOCK_TRANSACTION->date = date("Y-m-d");
            $STOCK_TRANSACTION->qty_in = 0;
            $STOCK_TRANSACTION->qty_out = $item['qty'];
            $STOCK_TRANSACTION->remark = "INVOICE #$invoiceId Issued" . date("Y-m-d H:i:s");
            $STOCK_TRANSACTION->created_at = date("Y-m-d H:i:s");
            $STOCK_TRANSACTION->create();


            //audit log
            $AUDIT_LOG = new AuditLog(NUll);
            $AUDIT_LOG->ref_id = $invoiceId;
            $AUDIT_LOG->ref_code = $_POST['invoice_no'];
            $AUDIT_LOG->action = 'CREATE';
            $AUDIT_LOG->description = 'CREATE INVOICE NO #' . $invoiceId;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
            $AUDIT_LOG->create();

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