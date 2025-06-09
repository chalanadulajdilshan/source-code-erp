<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

$data = json_decode(file_get_contents("php://input"), true);

// ---------- Create ARN ----------
if (isset($data['create'])) {
    // 1. Create ARN master as you already do
    $arnNo = $data['arn_no'];
    $supplier = $data['supplier'];
    $arnDate = $data['arn_date'];
    $lcNo = $data['lc_no'];
    $blNo = $data['bl_no'];
    $pi_no = $data['pi_no'];
    $department_id = $data['department_id'];
    $purchase_order_id = $data['purchase_order_id'];
    $purchase_date = $data['purchase_date'];


    $invoiceDate = $data['invoice_date'];
    $entryDate = $data['entry_date'];
    $totalARN = $data['total_arn'];
    $totalDiscount = $data['total_discount'];
    $totalVAT = $data['total_vat'];
    $totalReceivedQty = $data['total_received_qty'];
    $totalOrderQty = $data['total_order_qty'];
    $items = $data['items'];



    $ARN = new ArnMaster(NULL);
    $ARN->arn_no = $arnNo;
    $ARN->supplier_id = $supplier;
    $ARN->arn_date = $arnDate;
    $ARN->lc_tt_no = $lcNo;
    $ARN->bl_no = $blNo;
    $ARN->pi_no = $pi_no;
    $ARN->department = $department_id;
    $ARN->po_no = $purchase_order_id;
    $ARN->po_date = $purchase_date;

    $PURCHASE_ORDER = new PurchaseOrder($purchase_order_id);
    $PURCHASE_ORDER->status = 1;
    $PURCHASE_ORDER->update();


    $ARN->invoice_date = $invoiceDate;
    $ARN->entry_date = $entryDate;
    $ARN->total_arn_value = $totalARN;
    $ARN->total_discount = $totalDiscount;
    $ARN->total_vat = $totalVAT;
    $ARN->total_received_qty = $totalReceivedQty;
    $ARN->total_order_qty = $totalOrderQty;
    $arn_id = $ARN->create();

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $arn_id;
    $AUDIT_LOG->ref_code = $arnNo;
    $AUDIT_LOG->action = 'CREATE';
    $AUDIT_LOG->description = 'CREATE ARN NO #' . $arnNo;
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    if ($arn_id) {

        foreach ($items as $item) {

            // 2. Create ARN items
            $ARN_ITEM = new ArnItem(NULL);
            $ARN_ITEM->arn_id = $arn_id;
            $ARN_ITEM->item_code = $item['item_id'];  // item_code = item_id here
            $ARN_ITEM->order_qty = $item['order_qty'];
            $ARN_ITEM->received_qty = $item['rec_qty'];
            $ARN_ITEM->commercial_cost = $item['cost'];
            $ARN_ITEM->discount_1 = $item['dis1'];
            $ARN_ITEM->discount_2 = $item['dis2'];
            $ARN_ITEM->discount_3 = $item['dis3'];
            $ARN_ITEM->final_cost = $item['actual_cost'];
            $ARN_ITEM->unit_total = $item['unit_total'];
            $ARN_ITEM->list_price = $item['list_price'];
            $ARN_ITEM->cash_price = $item['cash_price'];
            $ARN_ITEM->credit_price = $item['credit_price'];
            $ARN_ITEM->vat_percent = $item['vat_percent'] ?? 0;
            $ARN_ITEM->vat_value = $item['vat_value'] ?? 0;
            $ARN_ITEM->margin_percent = $item['margin_percent'] ?? 0;
            $ARN_ITEM->created_at = date("Y-m-d H:i:s");
            $ARN_ITEM->create();

            $DOCUMENT_TRACKING = new DocumentTracking(null);
            $DOCUMENT_TRACKING->incrementDocumentId('arn');

            // 3. Update Item Master cost and prices (optional, if needed)
            $itemMaster = new ItemMaster($item['item_id']);
            if ($itemMaster->id) {
                // Example: update cost fields here, adjust as per your business logic
                $itemMaster->cost = $item['actual_cost'];
                $itemMaster->list_price = $item['list_price'];
                $itemMaster->cash_price = $item['cash_price'];
                $itemMaster->credit_price = $item['credit_price'];
                $itemMaster->update();
            }

            // 4. Update Stock Master quantity per department (add received_qty)
            $stockMaster = new StockMaster();
            $existingStock = $stockMaster->getAvailableQuantity($department_id, $item['item_id']); // You might need to pass department here

            if ($existingStock > 0) {

                $newQty = $existingStock + $item['rec_qty'];


                $stockMaster->updateQtyByItemAndDepartment($department_id, $item['item_id'], $newQty);
            } else {
                // Create new stock record if none exists for this item/department
                $stockMaster->item_id = $item['item_id'];
                $stockMaster->department_id = $ARN->department;  // Ensure ARN master or data has department id
                $stockMaster->quantity = $item['rec_qty'];
                $stockMaster->created_at = date("Y-m-d H:i:s");
                $stockMaster->create();
            }

            // 5. Insert stock transaction record (stock in)
            $stockTransaction = new StockTransaction(NULL);
            $stockTransaction->item_id = $item['item_id'];
            $stockTransaction->type = 2;  // stock adjestment table 2 row
            $stockTransaction->date = date("Y-m-d");
            $stockTransaction->qty_in = $item['rec_qty'];
            $stockTransaction->qty_out = 0;
            $stockTransaction->remark = "ARN #$arnNo received";
            $stockTransaction->created_at = date("Y-m-d H:i:s");
            $stockTransaction->create();
        }

        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error', "message" => "Failed to create ARN master."]);
    }
    exit();
}


// ---------- Update ARN ----------
if (isset($_POST['update']) && isset($_POST['id'])) {
    $arnId = $_POST['id'];
    $ARN = new ArnMaster($arnId);

    $ARN->supplier_id = $_POST['supplier'];
    $ARN->arn_no = $_POST['arn_no'];
    $ARN->arn_date = $_POST['arn_date'];
    $ARN->lc_tt_no = $_POST['lc_no'];
    $ARN->bl_no = $_POST['bl_no'];
    $ARN->invoice_date = $_POST['invoice_date'];
    $ARN->entry_date = $_POST['entry_date'];
    $ARN->total_arn_value = $_POST['total_arn'];
    $ARN->total_discount = $_POST['total_discount'];
    $ARN->total_vat = $_POST['total_vat'];
    $ARN->total_received_qty = $_POST['total_received_qty'];
    $ARN->total_order_qty = $_POST['total_order_qty'];

    $result = $ARN->update();

    if ($result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update ARN."]);
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
?>