<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_POST['action']) && $_POST['action'] === 'get_available_qty') {
    $department_id = isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0;
    $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;

    if ($department_id > 0 && $item_id > 0) {
        $STOCK_MASTER = new StockMaster(null);
        $available_qty = $STOCK_MASTER->getAvailableQuantity($department_id, $item_id);

        echo json_encode([
            'status' => 'success',
            'available_qty' => $available_qty
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid department or item ID'
        ]);
    }
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'create_stock_adjustment') {

    $department_id = $_POST['department_id'];
    $adjustment_date = date('Y-m-d H:i:s');
    $adjustment_type = $_POST['adjustment_type'];
    $special_instructions = $_POST['special_instructions'];

    $itemIds = $_POST['item_ids'];
    $codes = $_POST['item_codes'];
    $names = $_POST['item_names'];
    $qtys = $_POST['item_qtys'];

    if (!$department_id || !$adjustment_date || empty($codes)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    $STOCK_MASTER = new StockMaster(null);


    //audit log
    $AUDIT_LOG = new AuditLog(null);
    $AUDIT_LOG->ref_id = 0;
    $AUDIT_LOG->ref_code = 'REF/STK/ADJ/00';
    $AUDIT_LOG->action = 'ADJ';
    $AUDIT_LOG->description = 'ADJ STOCK NO # REF/STK/ADJ/00';
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();


    foreach ($itemIds as $index => $itemId) {

        $item_id = $itemId;
        $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 0;

        if ($item_id && $qty > 0) {

            $result = $STOCK_MASTER->adjustQuantity(
                item_id: $item_id,
                department_id: $department_id,
                adjust_qty: $qty,
                adjust_type: $adjustment_type,
                remark: $special_instructions . ' - on ' . $adjustment_date
            );


            if ($result['status'] !== 'success') {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Failed to adjust stock for item code : " . $result['message']
                ]);
                exit;
            }

        } else {
            echo json_encode([
                'status' => 'error',
                'message' => "Invalid item code or quantity for item: $code"
            ]);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Stock adjustment completed successfully.']);
    exit;
}

?>