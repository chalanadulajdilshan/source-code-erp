<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_POST['action']) && $_POST['action'] == 'check_purchase_id') {


    $purchaseNo = trim($_POST['po_no']);
    $PURCHASE = new PurchaseOrder(NULL);
    $res = $PURCHASE->checkPurchaseOrderIdExist($purchaseNo);

    // Send JSON response
    echo json_encode(['exists' => $res]);
    exit();
}

// Create a new purchase order
if (isset($_POST['action']) && $_POST['action'] == 'create_purchase') {
    $purchaseNo = $_POST['po_no'];
    $items = json_decode($_POST['items'], true);


    // Basic PO details
    $poNumber = $_POST['po_number'];
    $entryDate = $_POST['entryDate'];
    $supplierId = $_POST['supplier_id'];
    $piNo = $_POST['pi_no'];
    $address = $_POST['address'];
    $lcTtNo = $_POST['lc_tt_no'];
    $brand = $_POST['brand'];
    $blNo = $_POST['bl_no'];
    $country = $_POST['country'];
    $ci_no = $_POST['ci_no'];
    $department = $_POST['department'];
    $orderBy = $_POST['order_by'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;

    $totalSubTotal = 0;
    $totalDiscount = 0;

    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0;

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $discountAmount = ($itemTotal * $discount) / 100;
        $totalDiscount += $discountAmount;
    }


    $grandTotal = ($totalSubTotal - $totalDiscount);

    // Create purchase order
    $PURCHASE_ORDER = new PurchaseOrder(NULL);

    $PURCHASE_ORDER->po_number = $poNumber;
    $PURCHASE_ORDER->entry_date = $entryDate;
    $PURCHASE_ORDER->supplier_id = $supplierId;
    $PURCHASE_ORDER->pi_no = $piNo;
    $PURCHASE_ORDER->address = $address;
    $PURCHASE_ORDER->lc_tt_no = $lcTtNo;
    $PURCHASE_ORDER->brand = $brand;
    $PURCHASE_ORDER->bl_no = $blNo;
    $PURCHASE_ORDER->country = $country;
    $PURCHASE_ORDER->ci_no = $ci_no;
    $PURCHASE_ORDER->department = $department;
    $PURCHASE_ORDER->order_by = $orderBy;
    $PURCHASE_ORDER->remarks = $remarks;
    $PURCHASE_ORDER->created_by = $createdBy;
    $PURCHASE_ORDER->created_at = $createdAt;
    $PURCHASE_ORDER->updated_at = $updatedAt;

    $poResult = $PURCHASE_ORDER->create();


    if ($poResult) {


        $newPurchaseId = $poResult;

        foreach ($items as $item) {
            $ITEM_MASTER = new ItemMaster(NULL);
            $item_id = $ITEM_MASTER->getIdbyItemCode($item['code']);

            $QUOTATION_ITEM = new QuotationItem(NULL);
            $QUOTATION_ITEM->poNumber = $newPurchaseId;
            $QUOTATION_ITEM->item_code = $item_id;
            $QUOTATION_ITEM->item_name = $item['name'];
            $QUOTATION_ITEM->pattern = $item['pattern'];
            $QUOTATION_ITEM->qty = $item['qty'];
            $QUOTATION_ITEM->discount = isset($item['discount']) ? $item['discount'] : 0;

            // Calculate item subtotal with discount
            $itemTotal = $item['price'] * $item['qty'];
            $discountAmount = ($itemTotal * $QUOTATION_ITEM->discount) / 100;
            $QUOTATION_ITEM->sub_total = $itemTotal - $discountAmount;

            $QUOTATION_ITEM->create();

            $DOCUMENT_TRACKING = new DocumentTracking(null);
            $DOCUMENT_TRACKING->incrementDocumentId('quotation');

        }

        echo json_encode([
            "status" => 'success',
            "poNumber" => $newPurchaseId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount, 
            "grand_total" => $grandTotal
        ]);

        exit();
    } else {
        echo json_encode([
            "status" => 'error',
            "message" => "Failed to create quotation"
        ]);
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
            "status" => 'error's
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