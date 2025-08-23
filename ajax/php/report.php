<?php
include_once '../../class/include.php';
header('Content-Type: application/json');

//price control laord
if (isset($_POST['action']) && $_POST['action'] == 'loard_price_Control') {

    $category_id = $_POST['category_id'] ?? 0;
    $brand_id = $_POST['brand_id'] ?? 0;
    $group_id = $_POST['group_id'] ?? 0;
    $department_id = $_POST['department_id'] ?? 0;
    $item_code = $_POST['item_code'] ?? '';

    $ITEM = new ItemMaster(NULL);
    $items = $ITEM->getItemsFiltered($category_id, $brand_id, $group_id, $department_id, $item_code);

    echo json_encode($items);
    exit;
}

//profit table loard
if (isset($_POST['action']) && $_POST['action'] === 'load_profit_report') {
    // Collect filters into an array
    $filters = [
        'category_id' => isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0,
        'brand_id' => isset($_POST['brand_id']) ? (int) $_POST['brand_id'] : 0,
        'group_id' => isset($_POST['group_id']) ? (int) $_POST['group_id'] : 0,
        'department_id' => isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0,
        'item_code' => isset($_POST['item_code']) ? trim($_POST['item_code']) : '',
        'customer_id' => isset($_POST['customer_id']) ? (int) $_POST['customer_id'] : 0,
        'company_id' => isset($_POST['company_id']) ? (int) $_POST['company_id'] : 0,
        'from_date' => isset($_POST['from_date']) ? $_POST['from_date'] : '',
        'to_date' => isset($_POST['to_date']) ? $_POST['to_date'] : '',
        'all_customers' => isset($_POST['all_customers']) ? $_POST['all_customers'] : false
    ];

    // Load profit data
    $salesInvoice = new SalesInvoice(NULL);
    $items = $salesInvoice->getProfitTable($filters);

    // Calculate total expenses for the same date range
    $totalExpenses = 0;
    if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
        $expense = new Expense(NULL);
        $totalExpenses = $expense->getTotalExpensesByDateRange($filters['from_date'], $filters['to_date']);
    }

    // Prepare response with both sales data and expense total
    $response = [
        'sales_data' => $items,
        'total_expenses' => $totalExpenses
    ];

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update_stock_tmp_price') {

    $id = (int) $_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $STOCK_ITEM_TMP = new StockItemTmp(NULL);

    $response = $STOCK_ITEM_TMP->updateStockItemTmpPrice($id, $field, $value);
    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['id'];
    $AUDIT_LOG->ref_code = '#ITEM/PRICE/UPDATE';
    $AUDIT_LOG->action = 'UPDATE';
    $AUDIT_LOG->description = 'UPDATE ITEM NO PRICES ';
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    echo json_encode($response);
    exit;
}

// Update item price
if (isset($_POST['action']) && $_POST['action'] === 'update_item_price') {
    try {
        $item_id = (int)$_POST['item_id'];
        $new_price = (float)$_POST['new_price'];

        if ($item_id <= 0 || $new_price < 0) {
            throw new Exception('Invalid input parameters');
        }

        // Load the item
        $ITEM = new ItemMaster($item_id);

        if (!$ITEM->id) {
            throw new Exception('Item not found');
        }

        // Update the price
        $ITEM->list_price = $new_price;

        // Recalculate invoice price if needed (based on discount)
        if ($ITEM->discount > 0) {
            $discount_amount = $new_price * ($ITEM->discount / 100);
            $ITEM->invoice_price = $new_price - $discount_amount;
        } else {
            $ITEM->invoice_price = $new_price;
        }

        // Save the changes
        $result = $ITEM->update();

        if ($result) {
            // Add audit log
            $AUDIT_LOG = new AuditLog(null);
            $AUDIT_LOG->ref_id = $item_id;
            $AUDIT_LOG->ref_code = $ITEM->code;
            $AUDIT_LOG->action = 'UPDATE';
            $AUDIT_LOG->description = 'UPDATED ITEM PRICE TO ' . $new_price;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date('Y-m-d H:i:s');
            $AUDIT_LOG->create();

            echo json_encode([
                'status' => 'success',
                'message' => 'Price updated successfully'
            ]);
        } else {
            throw new Exception('Failed to update item price');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
