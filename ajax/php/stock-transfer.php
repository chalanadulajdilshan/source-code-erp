<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_POST['action']) && $_POST['action'] === 'get_available_qty') {

    $department_id = isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0;
    $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;

    if ($department_id > 0 && $item_id > 0) {
        $STOCK_MASTER = new StockMaster(NUll);


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

if (isset($_POST['action']) && $_POST['action'] === 'create_stock_transfer') {

    $from = $_POST['department_id'];
    $to = $_POST['to_department_id'];
    $date = $_POST['transfer_date'];
    $codes = $_POST['item_codes'];
    $names = $_POST['item_names'];
    $qtys = $_POST['item_qtys'];

    if (!$from || !$to || !$date || empty($codes)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    $STOCK_MASTER = new StockMaster(null);

    foreach ($codes as $index => $code) {
        $item_id = $code;
         
        $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 0;

        if ($item_id && $qty > 0) {
            $result = $STOCK_MASTER->transferQuantity($item_id, $from, $to, $qty, "Transfer on $date");

            if ($result['status'] !== 'success') {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Failed to transfer item code $code: " . $result['message']
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

    echo json_encode(['status' => 'success', 'message' => 'Stock transfer completed successfully.']);
    exit;
}



?>