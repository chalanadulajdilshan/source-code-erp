<?php
require_once 'class/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arn_id = $_POST['arn_id'];
    $ref_no = $_POST['ref_no'];
    $return_reason = $_POST['return_reason'];
    $return_items = json_decode($_POST['return_items'], true);

    $db = new Database();

    $arn = mysqli_fetch_assoc($db->readQuery("SELECT * FROM arn_master WHERE id = $arn_id"));
    $supplier_id = $arn['supplier_id'];
    $department_id = $arn['department'];
    $created_by = 1; // get from session ideally

    $insert_return = "INSERT INTO purchase_return (ref_no, department_id, return_date, arn_id, supplier_id, total_amount, return_reason, created_by, created_at)
                      VALUES ('$ref_no', '$department_id', NOW(), '$arn_id', '$supplier_id', 0, '$return_reason', '$created_by', NOW())";
    $db->readQuery($insert_return);
    $return_id = mysqli_insert_id($db->DB_CON);

    $total_amount = 0;
    foreach ($return_items as $item) {
        $item_id = $item['item_id'];
        $qty = $item['quantity'];

        $item_data = mysqli_fetch_assoc($db->readQuery("SELECT cost FROM item_master WHERE id = '$item_id'"));
        $unit_price = $item_data['cost'];
        $net_amount = $unit_price * $qty;
        $total_amount += $net_amount;

        $db->readQuery("INSERT INTO purchase_return_items (return_id, item_id, quantity, unit_price, net_amount, created_at)
                        VALUES ('$return_id', '$item_id', '$qty', '$unit_price', '$net_amount', NOW())");
    }

    $db->readQuery("UPDATE purchase_return SET total_amount = '$total_amount' WHERE id = '$return_id'");

    echo json_encode(['status' => 'success']);
}
