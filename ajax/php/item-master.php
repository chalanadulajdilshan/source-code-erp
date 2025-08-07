<?php

include '../../class/include.php';

header('Content-Type: application/json; charset=UTF8');

// Create a new item
if (isset($_POST['create'])) {

    $ITEM = new ItemMaster(NULL); // Create a new ItemMaster object

    // Set item details
    $ITEM->code = $_POST['code'];
    $ITEM->name = $_POST['name'];
    $ITEM->brand = $_POST['brand'];
    $ITEM->size = $_POST['size'];
    $ITEM->pattern = $_POST['pattern'];
    $ITEM->group = $_POST['group'];
    $ITEM->category = $_POST['category'];
    $ITEM->list_price = $_POST['list_price'];
    $ITEM->invoice_price = $_POST['invoice_price'];
    $ITEM->re_order_level = $_POST['re_order_level'];
    $ITEM->re_order_qty = $_POST['re_order_qty'];
    $ITEM->stock_type = $_POST['stock_type'];
    $ITEM->note = $_POST['note'];
    $ITEM->discount = $_POST['discount'];
    $ITEM->is_active = isset($_POST['is_active']) ? 1 : 0; //  

    // Attempt to create the item
    $res = $ITEM->create();


    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $res;
    $AUDIT_LOG->ref_code = $_POST['code'];
    $AUDIT_LOG->action = 'CREATE';
    $AUDIT_LOG->description = 'CREATE ITEM NO #' . $_POST['code'];
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    $DOCUMENT_TRACKING = new DocumentTracking(null);
    $DOCUMENT_TRACKING->incrementDocumentId('item');


    if ($res) {
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

// Update item details
if (isset($_POST['update'])) {

    $ITEM = new ItemMaster($_POST['item_id']); // Retrieve item by ID

    // Update item details
    $ITEM->code = $_POST['code'];
    $ITEM->name = $_POST['name'];
    $ITEM->brand = $_POST['brand'];
    $ITEM->size = $_POST['size'];
    $ITEM->pattern = $_POST['pattern'];
    $ITEM->group = $_POST['group'];
    $ITEM->category = $_POST['category'];
    $ITEM->re_order_level = $_POST['re_order_level'];
    $ITEM->re_order_qty = $_POST['re_order_qty'];
    $ITEM->stock_type = $_POST['stock_type'];
    $ITEM->note = $_POST['note'];
    $ITEM->list_price = $_POST['list_price'];
    $ITEM->invoice_price = $_POST['invoice_price'];
    $ITEM->discount = $_POST['discount'];
    $ITEM->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the item
    $result = $ITEM->update();


    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['item_id'];
    $AUDIT_LOG->ref_code = $_POST['code'];
    $AUDIT_LOG->action = 'UPDATE';
    $AUDIT_LOG->description = 'UPDATE ITEM NO #' . $_POST['code'];
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

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

// Delete item
if (isset($_POST['delete']) && isset($_POST['id'])) {
    try {
        $ITEM_MASTER = new ItemMaster($_POST['id']);
        
        if (!$ITEM_MASTER->id) {
            throw new Exception('Item not found');
        }
        
        $result = $ITEM_MASTER->delete();

        if ($result) {
            // Add audit log
            $AUDIT_LOG = new AuditLog(null);
            $AUDIT_LOG->ref_id = $_POST['id'];
            $AUDIT_LOG->ref_code = $ITEM_MASTER->code;
            $AUDIT_LOG->action = 'DELETE';
            $AUDIT_LOG->description = 'DELETED ITEM #' . $ITEM_MASTER->code;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date('Y-m-d H:i:s');
            $AUDIT_LOG->create();
            
            echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
        } else {
            throw new Exception('Failed to delete item');
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

//filter for item for invoices
if (isset($_POST['filter_by_invoice'])) {
    $ITEM_MASTER = new ItemMaster();
    $response = $ITEM_MASTER->fetchForDataTable($_REQUEST);

    echo json_encode($response);
    exit;
}

if (isset($_POST['filter'])) {
    $ITEM_MASTER = new ItemMaster();
    $response = $ITEM_MASTER->fetchForDataTable($_REQUEST);

    echo json_encode($response);
    exit;
}

?>