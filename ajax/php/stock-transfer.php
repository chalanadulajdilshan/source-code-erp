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

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = 01;
    $AUDIT_LOG->ref_code = 'REF/STK/TRN/01';
    $AUDIT_LOG->action = 'TRN';
    $AUDIT_LOG->description = 'TRN STOCK NO # REF/TRN/ADJ/01';
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

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

if (isset($_POST['action']) && $_POST['action'] === 'get_available_qty_by_dates') {

    $item_id = (int) $_POST['item_id'];
    $department_id = (int) $_POST['department_id'];
    $days = isset($_POST['days']) ? (int) $_POST['days'] : 0;
    $date_from = $_POST['date_from'] ?? null;
    $date_to = $_POST['date_to'] ?? null;
    $show_all = $_POST['show_all'] ?? null;

    if ($item_id > 0 && $department_id > 0) {

        $STOCK = new StockTransaction();
        $available_qty = $STOCK->getAvailableQuantityByDepartment(
            $department_id,
            $item_id,
            $days,
            $date_from,
            $date_to,

        );

        echo json_encode([
            'status' => 'success',
            'available_qty' => $available_qty
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input values.'
        ]);
    }

    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'get_transaction_records') {

    $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
    $department_id = isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0;
    $date_from = $_POST['date_from'] ?? null;
    $date_to = $_POST['date_to'] ?? null;


    if ($item_id > 0 && $department_id > 0 && $date_from && $date_to) {

        $STOCK = new StockTransaction();

        // Assuming your class has a method like this:
        $transactions = $STOCK->getTransactionRecords(
            $department_id,
            $item_id,
            $date_from,
            $date_to
        );

        echo json_encode([
            'status' => 'success',
            'transactions' => $transactions
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid or missing input values.'
        ]);
    }

    exit();
}
if (isset($_POST['action']) && $_POST['action'] === 'get_department_stock_status') {

    $item_id = (int) $_POST['item_id'];

    if ($item_id > 0) {
        $STOCK_MASTER = new StockMaster();
        $DEPARTMENT_MASTER = new DepartmentMaster();


        $results = [];

        foreach ($DEPARTMENT_MASTER->all() as $dept) {
            $available_qty = $STOCK_MASTER->getAvailableQuantity($dept['id'], $item_id);
            $pending_orders = 10;

            $results[] = [
                'department_name' => $dept['name'],
                'available_qty' => $available_qty,
                'pending_orders' => $pending_orders,
            ];
        }

        echo json_encode([
            'status' => 'success',
            'data' => $results
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid item ID']);
    }

    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'get_department_stock') {
    $item_id = (int)$_POST['item_id'];

    if ($item_id > 0) {
        $departments = StockMaster::getDepartmentWiseStock($item_id);
        echo json_encode(['status' => 'success', 'data' => $departments]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid item ID']);
    }
    exit();
}


?>