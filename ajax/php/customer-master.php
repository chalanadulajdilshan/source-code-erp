<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new customer
if (isset($_POST['create'])) {

    $CUSTOMER = new CustomerMaster(NULL); // New customer object

    $CUSTOMER->code = $_POST['code'];
    $CUSTOMER->name = ucwords(strtolower($_POST['name']));
    $CUSTOMER->mobile_number = $_POST['mobile_number'];
    $CUSTOMER->mobile_number_2 = $_POST['mobile_number_2'];
    $CUSTOMER->email = $_POST['email'];
    $CUSTOMER->contact_person = ucwords(strtolower($_POST['contact_person']));
    $CUSTOMER->contact_person_number = $_POST['contact_person_number'];
    $CUSTOMER->credit_limit = $_POST['credit_limit'];
    $CUSTOMER->outstanding = $_POST['outstanding'];
    $CUSTOMER->overdue = $_POST['overdue'];
    $CUSTOMER->vat_no = $_POST['vat_no'];
    $CUSTOMER->svat_no = $_POST['svat_no'];
    $CUSTOMER->address = ucwords(strtolower($_POST['address']));
    $CUSTOMER->remark = $_POST['remark'];
    $CUSTOMER->category = $_POST['category'];
    $CUSTOMER->district = $_POST['district'];
    $CUSTOMER->province = $_POST['province'];
    $CUSTOMER->vat_group = $_POST['vat_group'];
    $CUSTOMER->is_vat = isset($_POST['is_vat']) ? 1 : 0;
    $CUSTOMER->is_active = isset($_POST['is_active']) ? 1 : 0;

    $res = $CUSTOMER->create();

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['code'];
    $AUDIT_LOG->ref_code = $_POST['code'];
    $AUDIT_LOG->action = 'CREATE';
    $AUDIT_LOG->description = 'CREATE CUSTOMER NO #' . $_POST['code'];
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();


    if ($res) {
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}

if (isset($_POST['create-invoice-customer'])) {

    $CUSTOMER = new CustomerMaster(NULL); // New customer object

    $CUSTOMER->code = $_POST['code'];
    $CUSTOMER->name = ucwords(strtolower($_POST['name']));
    $CUSTOMER->mobile_number = $_POST['mobile_number'];
    $CUSTOMER->address = ucwords(strtolower($_POST['address']));
    $res = $CUSTOMER->createInvoiceCustomer();

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $res;
    $AUDIT_LOG->ref_code = $_POST['code'];
    $AUDIT_LOG->action = 'CREATE';
    $AUDIT_LOG->description = 'CREATE CUSTOMER NO #' . $_POST['code'];
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();


    if ($res) {

        $CUSTOMER = new CustomerMaster($res);
        echo json_encode(["status" => "success", "customer_id" => $CUSTOMER->code, "customer_name" => $CUSTOMER->name, "customer_address" => $CUSTOMER->address, "customer_mobile_number" => $CUSTOMER->mobile_number]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}

// Update customer
if (isset($_POST['update'])) {

    $CUSTOMER = new CustomerMaster($_POST['customer_id']); // Load customer by ID

    $CUSTOMER->code = $_POST['code'];
    $CUSTOMER->name = ucwords(strtolower($_POST['name']));
    $CUSTOMER->mobile_number = $_POST['mobile_number'];
    $CUSTOMER->mobile_number_2 = $_POST['mobile_number_2'];
    $CUSTOMER->email = $_POST['email'];
    $CUSTOMER->contact_person = ucwords(strtolower($_POST['contact_person']));
    $CUSTOMER->contact_person_number = $_POST['contact_person_number'];
    $CUSTOMER->credit_limit = $_POST['credit_limit'];
    $CUSTOMER->outstanding = $_POST['outstanding'];
    $CUSTOMER->overdue = $_POST['overdue'];
    $CUSTOMER->vat_no = $_POST['vat_no'];
    $CUSTOMER->svat_no = $_POST['svat_no'];
    $CUSTOMER->address = ucwords(strtolower($_POST['address']));
    $CUSTOMER->remark = $_POST['remark'];
    $CUSTOMER->category = $_POST['category'];
    $CUSTOMER->district = $_POST['district'];
    $CUSTOMER->province = $_POST['province'];
    $CUSTOMER->vat_group = isset($_POST['vat_group']) ? $_POST['vat_group'] : '';
    $CUSTOMER->is_vat = isset($_POST['is_vat']) ? 1 : 0;
    $CUSTOMER->is_active = isset($_POST['is_active']) ? 1 : 0;

    $res = $CUSTOMER->update();

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['customer_id'];
    $AUDIT_LOG->ref_code = $_POST['code'];
    $AUDIT_LOG->action = 'UPDATE';
    $AUDIT_LOG->description = 'UPDATE CUSTOMER NO #' . $_POST['code'];
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();


    if ($res) {
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}

// Delete customer
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $CUSTOMER = new CustomerMaster($_POST['id']);
    $res = $CUSTOMER->delete();

    //audit log
    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['id'];
    $AUDIT_LOG->ref_code = $CUSTOMER->code;
    $AUDIT_LOG->action = 'DELETE';
    $AUDIT_LOG->description = 'DELETE CUSTOMER NO #' . $CUSTOMER->code;
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    if ($res) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit; // Add exit here to prevent further execution
}

if (isset($_POST['filter'])) {
    $category = $_POST['category'];
    $CUSTOMER_MASTER = new CustomerMaster();
    $response = $CUSTOMER_MASTER->fetchForDataTable($_REQUEST, $category);
    echo json_encode($response);
    exit;
}

// search by customer
if (isset($_POST['query'])) {
    $search = $_POST['query'];

    $CUSTOMER_MASTER = new CustomerMaster();
    $customers = $CUSTOMER_MASTER->searchCustomers($search);

    if ($customers) {
        echo json_encode($customers);  // Return the customers as a JSON string
    } else {
        echo json_encode([]);  // Return an empty array if no customers are found
    }
    exit;
}

// Make sure to use isset() before accessing $_POST['action']
if (isset($_POST['action']) && $_POST['action'] == 'get_first_customer') {
    $CUSTOMER = new CustomerMaster(1); // Fetch customer with ID 1

    $response = [
        "status" => "success",
        "customer_id" => $CUSTOMER->id,
        "customer_name" => $CUSTOMER->name,
        "customer_code" => $CUSTOMER->code ?? '',
        "mobile_number" => $CUSTOMER->mobile_number,
        "customer_address" => $CUSTOMER->address,
        "email" => $CUSTOMER->email ?? ''
    ];

    echo json_encode($response);
    exit;
}
