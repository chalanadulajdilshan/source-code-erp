<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');


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

    $paymentType = $_POST['payment_type'];



    $totalSubTotal = 0;
    $totalDiscount = 0;
    $final_cost = 0;

    // Calculate subtotal and discount
    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0; // item-wise discount

        //GET ARN ID BY ARN NO
        $ARN_MASTER = new ArnMaster(NULL);
        $arn_id = $ARN_MASTER->getArnIdByArnNo($item['arn_no']);

        $ITEM_MASTER = new ItemMaster($item['item_id']);

        $cost = $item['cost'];
        $final_cost_item = $cost * $item['qty'];
        $final_cost += $final_cost_item;

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $totalDiscount += $discount;
    }

    $netTotal = $totalSubTotal - $totalDiscount;

    $USER = new User($_SESSION['id']);
    $COMPANY_PROFILE = new CompanyProfile($USER->company_id);

    // VAT calculation - only if company has VAT enabled
    $tax = 0;
    if ($COMPANY_PROFILE->is_vat == 1) {
        $tax = round(($netTotal * $COMPANY_PROFILE->vat_percentage) / 100, 2);
    }

    // Grand total = net total + VAT
    $grandTotal = $netTotal + $tax;

    // Create invoice
    $SALES_INVOICE = new SalesInvoice(NULL);

    $SALES_INVOICE->invoice_no = $invoiceId;
    $SALES_INVOICE->invoice_type = 'INV';
    $SALES_INVOICE->invoice_date = date("Y-m-d H:i:s");
    $SALES_INVOICE->company_id = $_POST['company_id'];
    $SALES_INVOICE->customer_id = $_POST['customer_id'];
    $SALES_INVOICE->customer_name = ucwords(strtolower(trim($_POST['customer_name'])));
    $SALES_INVOICE->customer_mobile = $_POST['customer_mobile'];
    $SALES_INVOICE->customer_address = ucwords(strtolower(trim($_POST['customer_address'])));
    $SALES_INVOICE->department_id = $_POST['department_id'];
    $SALES_INVOICE->sale_type = $_POST['sales_type'];
    $SALES_INVOICE->final_cost = $final_cost;
    $SALES_INVOICE->payment_type = $paymentType;
    $SALES_INVOICE->sub_total = $totalSubTotal;
    $SALES_INVOICE->discount = $totalDiscount;
    $SALES_INVOICE->tax = $tax;
    $SALES_INVOICE->grand_total = $grandTotal;
    $SALES_INVOICE->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoiceResult = $SALES_INVOICE->create();

    $DOCUMENT_TRACKING = new DocumentTracking(null);

    if ($paymentType == 1) {
        $DOCUMENT_TRACKING->incrementDocumentId('cash');
    } else if ($paymentType == 2) {
        $DOCUMENT_TRACKING->incrementDocumentId('credit');
    } else {

        $DOCUMENT_TRACKING->incrementDocumentId('invoice');
    }

    if ($invoiceResult) {
        $invoiceTableId = $invoiceResult;

        foreach ($items as $item) {

            $item_discount = isset($item['discount']) ? $item['discount'] : 0;

            $ITEM_MASTER = new ItemMaster($item['item_id']);

            $SALES_ITEM = new SalesInvoiceItem(NULL);
            $SALES_ITEM->invoice_id = $invoiceTableId;
            $SALES_ITEM->item_code = $item['item_id'];
            $SALES_ITEM->item_name = $item['name'];
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

            // Update stock transaction with ARN reference if available
            $STOCK_TRANSACTION = new StockTransaction(NULL);
            $STOCK_TRANSACTION->item_id = $item['item_id'];

            // Update stock_item_tmp for ARN-based inventorysss           
            $STOCK_ITEM_TMP = new StockItemTmp(NULL);
            // Use negative qty to reduce stock
            $qtyToDeduct = -abs($item['qty']);
            $STOCK_ITEM_TMP->updateQtyByArnId(
                $arn_id,
                $item['item_id'],
                $_POST['department_id'],
                $qtyToDeduct
            );


            //stock transaction table update
            $STOCK_TRANSACTION->type = 4; // get this id from stock adjustment type table PK
            $STOCK_TRANSACTION->date = date("Y-m-d");
            $STOCK_TRANSACTION->qty_in = 0;
            $STOCK_TRANSACTION->qty_out = $item['qty'];
            $STOCK_TRANSACTION->remark = "INVOICE #$invoiceId " . (!empty($item['arn_id']) ? "(ARN: {$item['arn']}) " : "") . "Issued " . date("Y-m-d H:i:s");
            $STOCK_TRANSACTION->created_at = date("Y-m-d H:i:s");
            $STOCK_TRANSACTION->create();

            if ($paymentType == 1) {
                $payments = json_decode($_POST['payments'], true); // decode JSON → array

                if (is_array($payments)) {
                    foreach ($payments as $payment) {
                        $INVOICE_PAYMENT = new InvoicePayment(NULL);
                        $INVOICE_PAYMENT->invoice_id  = $invoiceTableId;
                        $INVOICE_PAYMENT->method_id   = $payment['method_id'];
                        $INVOICE_PAYMENT->amount      = $payment['amount'];
                        $INVOICE_PAYMENT->reference_no = $payment['reference_no'] ?? null;
                        $INVOICE_PAYMENT->bank_name    = $pssayment['bank_name'] ?? null;
                        $INVOICE_PAYMENT->cheque_date  = $payment['cheque_date'] ?? null;

                        $res = $INVOICE_PAYMENT->create();
                    }
                }
            }


            //audit log
            $AUDIT_LOG = new AuditLog(NUll);
            $AUDIT_LOG->ref_id = 1;
            $AUDIT_LOG->ref_code = $_POST['invoice_no'];
            $AUDIT_LOG->action = 'CREATE';
            $AUDIT_LOG->description = 'CREATE INVOICE NO #' . $invoiceTableId;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
            $AUDIT_LOG->create();
        }

        echo json_encode([
            "status" => 'success',
            "invoice_id" => $invoiceTableId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount,
            "vat" => $tax,
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

if (isset($_POST['get_by_id'])) {

    $SALES_INVOICE = new SalesInvoice();
    $response = $SALES_INVOICE->getByID($_POST['id']);

    $CUSTOMER_MASTER = new CustomerMaster($response['customer_id']);
    $response['customer_code'] = $CUSTOMER_MASTER->code;
    $response['customer_name'] = $CUSTOMER_MASTER->name;
    $response['customer_address'] = $CUSTOMER_MASTER->address;
    $response['customer_mobile'] = $CUSTOMER_MASTER->mobile_number;

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



if (isset($_POST['action']) && $_POST['action'] == 'latest') {
    $SALES_INVOICE = new SalesInvoice();
    $invoices = $SALES_INVOICE->latest();

    echo json_encode(["data" => $invoices]);
    exit();
}


// Handle cancel invoice action
if (isset($_POST['action']) && $_POST['action'] == 'cancel') {


    $invoiceId = $_POST['id'];
    $arnIds = $_POST['arnIds'];

    $SALES_INVOICE = new SalesInvoice(NULL);


    $result = $SALES_INVOICE->cancel($invoiceId);

    if ($result) {
        $STOCK_TRANSACTION = new StockTransaction(NULL);
        $SALES_INVOICE_ITEM = new SalesInvoiceItem(NULL);
        $STOCK_ITEM_TMP = new StockItemTmp(NULL);

        $items = $SALES_INVOICE_ITEM->getItemsByInvoiceId($invoiceId);


        foreach ($items as $item) {
            $STOCK_MASTER = new StockMaster(NULL);
            $currentQty = $STOCK_MASTER->getAvailableQuantity($SALES_INVOICE->department_id, $item['item_code']);
            $newQty = $currentQty + $item['quantity'];
            $STOCK_MASTER->quantity = $newQty;
            $STOCK_MASTER->updateQtyByItemAndDepartment($SALES_INVOICE->department_id, $item['item_code'], $newQty);


            // Update stock transaction with ARN reference if available

            $STOCK_TRANSACTION->item_id = $item['item_code'];

            // Update stock_item_tmp for ARN-based inventorysss           

            // Use negative qty to Increase stock
            $qtyToAdd = abs($item['quantity']);

            if (!empty($arnIds) && is_array($arnIds)) {
                foreach ($arnIds as $arnId) {
                    $STOCK_ITEM_TMP->updateQtyByArnId(
                        $arnId,                       // single ARN ID
                        $item['item_code'],
                        $SALES_INVOICE->department_id,
                        $qtyToAdd
                    );
                }
            }
        }

        $STOCK_TRANSACTION->type = 14; // get this id from stock adjustment type table PK
        $STOCK_TRANSACTION->date = date("Y-m-d");
        $STOCK_TRANSACTION->qty_in = $item['quantity'];
        $STOCK_TRANSACTION->qty_out = 0;
        $STOCK_TRANSACTION->remark = "INVOICE CANCELLED #$invoiceId " . (!empty($item['arn_id']) ? "(ARN: {$item['arn']}) " : "") . "Cancelled " . date("Y-m-d H:i:s");
        $STOCK_TRANSACTION->created_at = date("Y-m-d H:i:s");
        $STOCK_TRANSACTION->create();


        //audit log
        $AUDIT_LOG = new AuditLog(NUll);
        $AUDIT_LOG->ref_id = $invoiceId;
        $AUDIT_LOG->ref_code = $invoiceId;
        $AUDIT_LOG->action = 'CANCEL';
        $AUDIT_LOG->description = 'CANCEL INVOICE NO #' . $SALES_INVOICE->invoice_no;
        $AUDIT_LOG->user_id = $_SESSION['id'];
        $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
        $AUDIT_LOG->create();
    }
}
