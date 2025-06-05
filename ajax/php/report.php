<?php
include_once '../../class/include.php';
header('Content-Type: application/json');

if (isset($_POST['action']) && $_POST['action'] == 'load_filtered') {

    $category_id = $_POST['category_id'] ?? 0;
    $brand_id = $_POST['brand_id'] ?? 0;
    $group_id = $_POST['group_id'] ?? 0;

    $ITEM = new ItemMaster(NULL);
    $items = $ITEM->getItemsFiltered($category_id, $brand_id, $group_id);

    echo json_encode($items);
    exit;
}

