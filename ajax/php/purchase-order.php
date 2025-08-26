<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_POST['action']) && $_POST['action'] == 'check_po_id') {


    $purchaseNo = trim($_POST['po_no']);
    $PURCHASE = new PurchaseOrder(NULL);
    $res = $PURCHASE->checkPurchaseIdExist($purchaseNo);

    // Send JSON response
    echo json_encode(['exists' => $res]);
    exit();
}

// Create a new purchase order
if (isset($_POST['action']) && $_POST['action'] == 'create_purchase_order') {

    $purchaseNo = $_POST['po_id'];
    $items = json_decode($_POST['items'], true);


    // Basic PO details
    $poNumber = $_POST['po_id'];
    $entryDate = $_POST['date'];
    $supplierId = $_POST['supplier_id'];
    $brand = $_POST['brand'];
    $invoice_no = $_POST['invoice_no'];
    $country = $_POST['country'];
    $department = $_POST['department'];
    $purchase_date = $_POST['purchase_date'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;

    $totalSubTotal = 0;

    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);


        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
    }


    $grandTotal = $totalSubTotal;


    // Create purchase order
    $PURCHASE_ORDER = new PurchaseOrder(NULL);

    $PURCHASE_ORDER->po_number = $poNumber;
    $PURCHASE_ORDER->order_date = $entryDate;
    $PURCHASE_ORDER->supplier_id = $supplierId;
    $PURCHASE_ORDER->brand = $brand;
    $PURCHASE_ORDER->invoice_no = $invoice_no;
    $PURCHASE_ORDER->country = $country;
    $PURCHASE_ORDER->department = $department;
    $PURCHASE_ORDER->purchase_date = $purchase_date;
    $PURCHASE_ORDER->remarks = $remarks;
    $PURCHASE_ORDER->grand_total = $grandTotal;
    $PURCHASE_ORDER->created_by = $_SESSION['id'];
    $PURCHASE_ORDER->created_at = date("Y-m-d H:i:s");

    $poResult = $PURCHASE_ORDER->create();


    if ($poResult) {


        $newPurchaseId = $poResult;

        foreach ($items as $item) {


            $PURCHASE_ORDER_ITEM = new PurchaseOrderItem(NULL);
            $PURCHASE_ORDER_ITEM->purchase_order_id = $newPurchaseId;
            $PURCHASE_ORDER_ITEM->item_id = $item['item_id'];
            $PURCHASE_ORDER_ITEM->quantity = $item['qty'];
            $PURCHASE_ORDER_ITEM->unit_price = $item['price'];

            // Calculate item subtotal with discount
            $itemTotal = $item['price'] * $item['qty'];
            $PURCHASE_ORDER_ITEM->total_price = $itemTotal;

            $PURCHASE_ORDER_ITEM->create();

            $DOCUMENT_TRACKING = new DocumentTracking(null);
            $DOCUMENT_TRACKING->incrementDocumentId('purchase');
        }

        //audit log
        $AUDIT_LOG = new AuditLog(NUll);
        $AUDIT_LOG->ref_id = $newPurchaseId;
        $AUDIT_LOG->ref_code = $purchaseNo;
        $AUDIT_LOG->action = 'CREATE';
        $AUDIT_LOG->description = 'CREATE PURCHASE ORDER NO #' . $purchaseNo;
        $AUDIT_LOG->user_id = $_SESSION['id'];
        $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
        $AUDIT_LOG->create();

        echo json_encode([
            "status" => 'success',
            "poNumber" => $newPurchaseId,
            "sub_total" => $totalSubTotal,
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

// Update purchase order
if (isset($_POST['action']) && $_POST['action'] === 'update_purchase_order') {
    $purchaseOrderId = $_POST['id'];
    $items = json_decode($_POST['items'], true); // decode items JSON

    // Load and update PO
    $PURCHASE_ORDER = new PurchaseOrder($purchaseOrderId);
    $PURCHASE_ORDER->po_number = $_POST['po_no'];
    $PURCHASE_ORDER->order_date = $_POST['order_date'];
    $PURCHASE_ORDER->supplier_id = $_POST['supplier_id'];
    $PURCHASE_ORDER->brand = $_POST['brand'];
    $PURCHASE_ORDER->invoice_no = $_POST['invoice_no'];
    $PURCHASE_ORDER->country = $_POST['country'];
    $PURCHASE_ORDER->department_id = $_POST['department_id'];
    $PURCHASE_ORDER->purchase_date = $_POST['purchase_date'];
    $PURCHASE_ORDER->remarks = $_POST['remarks'];

    $totalSubTotal = 0;

    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
    }



    $grandTotal = $totalSubTotal;


    $PURCHASE_ORDER->grand_total = $grandTotal;

    $updateResult = $PURCHASE_ORDER->update();

    if ($updateResult) {
        // Delete old items
        PurchaseOrderItem::deleteByPurchaseOrderId($purchaseOrderId);

        // Insert new items
        foreach ($items as $item) {

            $PURCHASE_ORDER_ITEM = new PurchaseOrderItem(NULL);
            $PURCHASE_ORDER_ITEM->purchase_order_id = $purchaseOrderId;
            $PURCHASE_ORDER_ITEM->item_id = $item['item_id'];
            $PURCHASE_ORDER_ITEM->quantity = $item['qty'];
            $PURCHASE_ORDER_ITEM->unit_price = $item['price'];

            // Calculate item subtotal with discount
            $itemTotal = $item['price'] * $item['qty'];
            $PURCHASE_ORDER_ITEM->total_price = $itemTotal;

            $PURCHASE_ORDER_ITEM->create();
        }

        //audit log
        $AUDIT_LOG = new AuditLog(NUll);
        $AUDIT_LOG->ref_id = $purchaseOrderId;
        $AUDIT_LOG->ref_code = $PURCHASE_ORDER->po_number;
        $AUDIT_LOG->action = 'UPDATE';
        $AUDIT_LOG->description = 'UPDATE PURCHASE ORDER NO #' . $PURCHASE_ORDER->po_number;
        $AUDIT_LOG->user_id = $_SESSION['id'];
        $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
        $AUDIT_LOG->create();

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update purchase order']);
    }

    exit();
}


// Get PurchaseOrder by ID
if (isset($_POST['action']) && $_POST['action'] == 'get_purchase_order') {

    $PURCHASE_ORDER = new PurchaseOrder($_POST['id']);
    $PURCHASE_ORDER_ITEM = new PurchaseOrderItem(null);


    $items = $PURCHASE_ORDER_ITEM->getByPurchaseOrderId($_POST['id']);

    $enhancedItems = [];

    foreach ($items as $item) {
        $ITEM_MASTER = new ItemMaster($item['item_id']); // item_code must exist in item row
        $BRAND_MASTER = new Brand($ITEM_MASTER->brand); // item_code must exist in item row

        $item['item_code'] = $ITEM_MASTER->code;
        $item['item_name'] = $ITEM_MASTER->name;

        $item['item_discount'] = $ITEM_MASTER->discount;
        $item['brand_discount'] = $BRAND_MASTER->discount;

        $item['item_selling_price'] = $ITEM_MASTER->invoice_price;
        $item['item_list_price'] = $ITEM_MASTER->list_price;

        $item['item_id'] = $ITEM_MASTER->id;
        $enhancedItems[] = $item;
    }

    $data = [
        'items' => $enhancedItems
    ];

    echo json_encode(['status' => 'success', 'data' => $data]);
}

// Delete purchase order
if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $PURCHASE_ORDER = new PurchaseOrder($_POST['id']);

    $result = $PURCHASE_ORDER->delete();

    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['id'];
    $AUDIT_LOG->ref_code = $PURCHASE_ORDER->po_number;
    $AUDIT_LOG->action = 'DELETE';
    $AUDIT_LOG->description = 'DELETE PURCHASE ORDER NO #' . $PURCHASE_ORDER->po_number;
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

// Delete purchase order items
if (isset($_POST['action']) && $_POST['action'] == 'delete_items') {

    $PURCHASE_ORDER_ITEM = new PurchaseOrderItem($_POST['item_id']);

    $result = $PURCHASE_ORDER_ITEM->delete();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
