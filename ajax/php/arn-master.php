<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

$data = json_decode(file_get_contents("php://input"), true);

// ---------- Create ARN ----------
if (isset($data['create'])) {
    // 1. Collect master data
    $ARN = new ArnMaster(NULL);
    $ARN->arn_no = $data['arn_no'];
    $ARN->supplier_id = $data['supplier'];
    $ARN->arn_date = $data['arn_date'];
    $ARN->lc_tt_no = $data['lc_no'];
    $ARN->ci_no = $data['ci_no'];
    $ARN->bl_no = $data['bl_no'];
    $ARN->pi_no = $data['pi_no'];
    $ARN->brand = $data['brand'];
    $ARN->category = $data['category'];
    $ARN->country = $data['country'];
    $ARN->order_by = $data['order_by'];
    $ARN->purchase_type = $data['purchase_type'];
    $ARN->arn_status = $data['arn_status'];
    $ARN->credit_note_amount = $data['credit_note_amount'];
    $ARN->delivery_date = $data['delivery_date'];
    $ARN->invoice_date = $data['invoice_date'];
    $ARN->entry_date = $data['entry_date'];
    $ARN->total_arn_value = $data['total_arn'];
    $ARN->total_discount = $data['total_discount'];
    $ARN->total_vat = $data['total_vat'];
    $ARN->total_received_qty = $data['total_received_qty'];
    $ARN->total_order_qty = $data['total_order_qty'];
    $ARN->department = $data['department_id'];
    $ARN->po_no = $data['purchase_order_id'];
    $ARN->po_date = $data['purchase_date'];

    // 2. Update Purchase Order Status
    $PURCHASE_ORDER = new PurchaseOrder($ARN->po_no);
    $PURCHASE_ORDER->status = 1;
    $PURCHASE_ORDER->update();

    // 3. Create ARN master
    $arn_id = $ARN->create();

    if ($arn_id) {
        // 4. Log audit
        $AUDIT_LOG = new AuditLog(NULL);
        $AUDIT_LOG->ref_id = $arn_id;
        $AUDIT_LOG->ref_code = $ARN->arn_no;
        $AUDIT_LOG->action = 'CREATE';
        $AUDIT_LOG->description = 'CREATE ARN NO #' . $ARN->arn_no;
        $AUDIT_LOG->user_id = $_SESSION['id'];
        $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
        $AUDIT_LOG->create();

        // Document Tracking ID update
        (new DocumentTracking(null))->incrementDocumentId('arn');

        // 5. Process ARN items
        foreach ($data['items'] as $item) {
            // ARN Item
            $ARN_ITEM = new ArnItem(NULL);
            $ARN_ITEM->arn_id = $arn_id;
            $ARN_ITEM->item_code = $item['item_id'];
            $ARN_ITEM->order_qty = $item['order_qty'];
            $ARN_ITEM->received_qty = $item['rec_qty'];
            $ARN_ITEM->discount_1 = $item['dis1'];
            $ARN_ITEM->discount_2 = $item['dis2'];
            $ARN_ITEM->discount_3 = $item['dis3'];
            $ARN_ITEM->discount_4 = $item['dis4'];
            $ARN_ITEM->discount_5 = $item['dis5'];
            $ARN_ITEM->final_cost = $item['actual_cost'];
            $ARN_ITEM->unit_total = $item['unit_total'];
            $ARN_ITEM->cost = $item['cost'];
            $ARN_ITEM->invoice_price = $item['invoice_price'];

            $ARN_ITEM->created_at = date("Y-m-d H:i:s");
            $ARN_ITEM->create();

            // Stock Item Temporary
            $STOCK_ITEM_TMP = new StockItemTmp();
            $STOCK_ITEM_TMP->arn_id = $arn_id;
            $STOCK_ITEM_TMP->item_id = $item['item_id'];
            $STOCK_ITEM_TMP->qty = $item['rec_qty'];
            $STOCK_ITEM_TMP->cost = $item['actual_cost'];
            $STOCK_ITEM_TMP->cost = $item['cost'];
            $STOCK_ITEM_TMP->invoice_price = $item['invoice_price'];
            $STOCK_ITEM_TMP->department_id = $data['department_id'];
            $STOCK_ITEM_TMP->status = 1;
            $STOCK_ITEM_TMP->create();

            // Stock Master update
            $stockMaster = new StockMaster();
            $existingStock = $stockMaster->getAvailableQuantity($ARN->department, $item['item_id']);
            if ($existingStock > 0) {
                $newQty = $existingStock + $item['rec_qty'];
                $stockMaster->updateQtyByItemAndDepartment($ARN->department, $item['item_id'], $newQty);
            } else {
                $stockMaster->item_id = $item['item_id'];
                $stockMaster->department_id = $ARN->department;
                $stockMaster->quantity = $item['rec_qty'];
                $stockMaster->created_at = date("Y-m-d H:i:s");
                $stockMaster->invoice_price = $item['invoice_price'];
                $stockMaster->create();
            }

            // Stock Transaction log
            $stockTransaction = new StockTransaction(NULL);
            $stockTransaction->item_id = $item['item_id'];
            $stockTransaction->type = 2; // Stock In
            $stockTransaction->date = date("Y-m-d");
            $stockTransaction->qty_in = $item['rec_qty'];
            $stockTransaction->qty_out = 0;
            $stockTransaction->remark = "ARN #{$ARN->arn_no} received";
            $stockTransaction->created_at = date("Y-m-d H:i:s");
            $stockTransaction->create();
        }

        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error', "message" => "Failed to create ARN master."]);
    }

    exit();
}




// ---------- Delete ARN ----------
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $ARN = new ArnMaster($_POST['id']);
    $result = $ARN->delete();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete ARN.']);
    }
    exit();
}

if (isset($_POST['arn_id'])) {
    $arn_id = intval($_POST['arn_id']);
    $ARN = new ArnMaster(null);
    $items = $ARN->getByArnId($arn_id);
    echo json_encode($items);
}

if (isset($_POST['arn_id_cancel'])) {
    $arn_id = intval($_POST['arn_id_cancel']);
    $ARN = new ArnMaster(null);

    $result = $ARN->cancelArn($arn_id);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to cancel ARN. Please try again.'
        ]);
    }
    exit;
}



if (isset($_POST['brand_id'])) {
    $brandId = $_POST['brand_id'];
    $BRAND = new Brand($brandId);

    echo json_encode(['discount' => $BRAND->discount]);
    exit();
}
