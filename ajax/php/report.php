<?php
include_once '../../class/include.php';
header('Content-Type: application/json');

//price control laord
if (isset($_POST['action']) && $_POST['action'] == 'load_filtered') {


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

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($items);
    exit;
}




