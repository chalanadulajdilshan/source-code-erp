<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Dag
if (isset($_POST['create'])) {

    $DAG = new DAG(NULL);

    // Set DAG master fields
    $DAG->ref_no = $_POST['ref_no'];
    $DAG->department_id = $_POST['department_id'];
    $DAG->customer_id = $_POST['customer_id'];
    $DAG->received_date = $_POST['received_date'];
    $DAG->customer_request_date = $_POST['customer_request_date'];
    $DAG->dag_company_id = $_POST['dag_company_id'];
    $DAG->delivery_date = $_POST['delivery_date'];
    $DAG->company_issued_date = $_POST['company_issued_date'];
    $DAG->company_delivery_date = $_POST['company_delivery_date'];
    $DAG->remark = $_POST['remark'];
    $DAG->receipt_no = $_POST['receipt_no'];

    $dag_id = $DAG->create();

    if ($dag_id) {
        // Insert DAG items
        if (isset($_POST['dag_items'])) {
            $items = json_decode($_POST['dag_items'], true);

            foreach ($items as $item) {
                $DAG_ITEM = new DagItem(NULL);
                $DAG_ITEM->dag_id = $dag_id;
                $DAG_ITEM->vehicle_no = $item['vehicle_no'];
                $DAG_ITEM->belt_id = $item['belt_id']; // JS uses 'belt_id'
                $DAG_ITEM->barcode = $item['barcode'];
                $DAG_ITEM->casing_cost = $item['casing_cost'];
                $DAG_ITEM->qty = $item['qty'];
                $DAG_ITEM->total_amount = $item['total_amount'];
                $DAG_ITEM->create();
            }
        }

        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}


// Update Dag details
if (isset($_POST['update'])) {
    $DAG = new DAG($_POST['dag_id']); // use correct key 'dag_id' from JS

    // Update DAG master fields
    $DAG->ref_no = $_POST['ref_no'];
    $DAG->department_id = $_POST['department_id'];
    $DAG->customer_id = $_POST['customer_id'];
    $DAG->received_date = $_POST['received_date'];
    $DAG->customer_request_date = $_POST['customer_request_date'];
    $DAG->dag_company_id = $_POST['dag_company_id'];
    $DAG->delivery_date = $_POST['delivery_date'];
    $DAG->company_issued_date = $_POST['company_issued_date'];
    $DAG->company_delivery_date = $_POST['company_delivery_date'];
    $DAG->remark = $_POST['remark'];
    $DAG->receipt_no = $_POST['receipt_no'];

    if ($DAG->update()) {
        // Delete all old DAG items
        $DAG_ITEM = new DagItem(null);
        $DAG_ITEM->deleteDagItemByItemId($DAG->id);

        // Add new DAG items
        if (isset($_POST['dag_items'])) {
            $items = json_decode($_POST['dag_items'], true);
            foreach ($items as $item) {
                $DAG_ITEM = new DagItem(null);
                $DAG_ITEM->dag_id = $DAG->id;
                $DAG_ITEM->vehicle_no = $item['vehicle_no'];
                $DAG_ITEM->belt_id = $item['belt_id'];
                $DAG_ITEM->barcode = $item['barcode'];
                $DAG_ITEM->casing_cost = $item['casing_cost'];
                $DAG_ITEM->qty = $item['qty'];
                $DAG_ITEM->total_amount = $item['total_amount'];
                $DAG_ITEM->create();
            }
        }

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }
    exit();
}



if (isset($_POST['dag_id'])) {
    $dag_id = $_POST['dag_id'];

    $DAG_ITEM = new DagItem(null);
    $items = $DAG_ITEM->getByValuesDagId($dag_id);

    echo json_encode([
        "status" => "success",
        "data" => $items
    ]);
    exit();
}
?>