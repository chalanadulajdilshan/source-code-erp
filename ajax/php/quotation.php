<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');


if (isset($_POST['action']) && $_POST['action'] == 'check_quotation_id') {


    $quotationNo = trim($_POST['quotation_id']);
    $QUOTATION = new Quotation(NULL);
    $res = $QUOTATION->checkQuotationIdExist($quotationNo);

    // Send JSON response
    echo json_encode(['exists' => $res]);
    exit();
}

// Create a new quotation 
if (isset($_POST['action']) && $_POST['action'] == 'create_quotation') {
    $quotationId = $_POST['quotation_id'];
    $items = json_decode($_POST['items'], true);


    // Get all posted values
    $date = $_POST['date'];
    $customerId = $_POST['customer_id'];
    $companyId = $_POST['company_id'];
    $departmentId = $_POST['department_id'];
    $executiveId = $_POST['marketing_executive_id'];
    $salesType = $_POST['sales_type'];
    $paymentType = $_POST['payment_type'];
    $creditPeriod = $_POST['credit_period'];
    $paymentTerm = $_POST['payment_term'];
    $validity = $_POST['validity'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;
    $vat_type = $_POST['vat_type'];


    $totalSubTotal = 0;
    $totalDiscount = 0;

    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0;

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $discountAmount = ($itemTotal * $discount) / 100;
        $totalDiscount += $discountAmount;
    }


    $grandTotal = ($totalSubTotal - $totalDiscount);

    // Create quotation
    $QUOTATION_ = new Quotation(NULL);

    $QUOTATION_->quotation_no = $quotationId;
    $QUOTATION_->company_id = $companyId;
    $QUOTATION_->date = $date;
    $QUOTATION_->customer_id = $customerId;
    $QUOTATION_->department_id = $departmentId;
    $QUOTATION_->marketing_executive_id = $executiveId;
    $QUOTATION_->payment_type = $paymentType;
    $QUOTATION_->remarks = $remarks;
    $QUOTATION_->credit_period = $creditPeriod;
    $QUOTATION_->payment_term = $paymentTerm;
    $QUOTATION_->validity = $validity;
    $QUOTATION_->sub_total = $totalSubTotal;
    $QUOTATION_->discount = $totalDiscount;
    $QUOTATION_->grand_total = $grandTotal;
    $QUOTATION_->vat_type = $_POST['vat_type'];
    $QUOTATION_->created_at = date("Y-m-d H:i:s");

    $quotationResult = $QUOTATION_->create();


    if ($quotationResult) {


        $newQuotationId = $quotationResult;

        foreach ($items as $item) {
            $ITEM_MASTER = new ItemMaster(NULL);
            $item_id = $ITEM_MASTER->getIdbyItemCode($item['code']);

            $QUOTATION_ITEM = new QuotationItem(NULL);
            $QUOTATION_ITEM->quotation_id = $newQuotationId;
            $QUOTATION_ITEM->item_code = $item_id;
            $QUOTATION_ITEM->item_name = $item['name'];
            $QUOTATION_ITEM->price = $item['price'];
            $QUOTATION_ITEM->qty = $item['qty'];
            $QUOTATION_ITEM->discount = isset($item['discount']) ? $item['discount'] : 0;

            // Calculate item subtotal with discount
            $itemTotal = $item['price'] * $item['qty'];
            $discountAmount = ($itemTotal * $QUOTATION_ITEM->discount) / 100;
            $QUOTATION_ITEM->sub_total = $itemTotal - $discountAmount;

            $QUOTATION_ITEM->create();

            $DOCUMENT_TRACKING = new DocumentTracking(null);
            $DOCUMENT_TRACKING->incrementDocumentId('quotation');

            //audit log
            $AUDIT_LOG = new AuditLog(NUll);
            $AUDIT_LOG->ref_id = $newQuotationId;
            $AUDIT_LOG->ref_code = $quotationId;
            $AUDIT_LOG->action = 'CREATE';
            $AUDIT_LOG->description = 'CREATE QUATATION NO #' . $quotationId;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
            $AUDIT_LOG->create();
        }

        echo json_encode([
            "status" => 'success',
            "quotation_id" => $newQuotationId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount,
            "grand_total" => $grandTotal
        ]);

        exit();
    } else {
        echo json_encode([
            "status" => 'error',
            "message" => "Failed to create quotation"
        ]);
        exit();
    }
}

// Update quotation details
if (isset($_POST['action']) && $_POST['action'] == 'update_quotation') {

    $quotationId = $_POST['id'] ?? $_POST['quotation_id'];
    $items = json_decode($_POST['items'], true);
    $deletedItems = isset($_POST['deleted_items']) ? json_decode($_POST['deleted_items'], true) : [];

    $totalSubTotal = 0;
    $totalDiscount = 0;

    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0;

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $discountAmount = ($itemTotal * $discount) / 100;
        $totalDiscount += $discountAmount;
    }

    $grandTotal = $totalSubTotal - $totalDiscount;

    $QUOTATION_ = new Quotation($quotationId);

    if (!$QUOTATION_->id) {
        $db = new Database();
        $query = "SELECT id FROM quotation WHERE quotation_no = '{$quotationId}'";
        $result = $db->readQuery($query);

        if ($row = mysqli_fetch_array($result)) {
            $QUOTATION_ = new Quotation($row['id']);
        } else {
            echo json_encode([
                "status" => 'error',
                "message" => "Quotation not found with ID or number: {$quotationId}"
            ]);
            exit();
        }
    }

    // Update quotation main details
    $QUOTATION_->quotation_no = $_POST['quotation_id'];
    $QUOTATION_->company_id = $_POST['company_id'];
    $QUOTATION_->date = $_POST['date'];
    $QUOTATION_->customer_id = $_POST['customer_id'];
    $QUOTATION_->department_id = $_POST['department_id'];
    $QUOTATION_->marketing_executive_id = $_POST['marketing_executive_id'];
    $QUOTATION_->vat_type = $_POST['vat_type'];
    $QUOTATION_->payment_type = $_POST['payment_type'];
    $QUOTATION_->remarks = $_POST['remarks'] ?? $QUOTATION_->remarks;
    $QUOTATION_->credit_period = $_POST['credit_period'] ?? $QUOTATION_->credit_period;
    $QUOTATION_->payment_term = $_POST['payment_term'] ?? $QUOTATION_->payment_term;
    $QUOTATION_->validity = $_POST['validity'] ?? $QUOTATION_->validity;
    $QUOTATION_->sub_total = $totalSubTotal;
    $QUOTATION_->discount = $totalDiscount;
    $QUOTATION_->grand_total = $grandTotal;

    if ($QUOTATION_->update()) {
        $db = new Database();

        foreach ($deletedItems as $itemCode) {

            $deleteQuery = "DELETE FROM quotation_item WHERE quotation_id = '{$QUOTATION_->id}' AND item_code = '{$itemCode}'";
            $db->readQuery($deleteQuery);
        }

        // Insert/update items
        foreach ($items as $item) {
            $ITEM_MASTER = new ItemMaster(NULL);
            $item_id = $ITEM_MASTER->getIdbyItemCode($item['code']);

            if (!$item_id) {
                error_log("Could not find item with code: " . $item['code']);
                continue;
            }

            $itemTotal = $item['price'] * $item['qty'];
            $discountAmount = ($itemTotal * $item['discount']) / 100;
            $subTotal = $itemTotal - $discountAmount;

            $QUOTATION_ITEM = new QuotationItem(NULL);
            $existingItemId = $QUOTATION_ITEM->checkQuotationItemExist($QUOTATION_->id, $item_id);

            if ($existingItemId) {
                // Update
                $updateQuery = "
                    UPDATE quotation_item SET 
                        item_name = '{$item['name']}',
                        price = '{$item['price']}',
                        qty = '{$item['qty']}',
                        discount = '{$item['discount']}',
                        sub_total = '{$subTotal}'
                    WHERE id = '{$existingItemId}'
                ";
                $db->readQuery($updateQuery);
            } else {
                // Insert
                $QUOTATION_ITEM = new QuotationItem(NULL);
                $QUOTATION_ITEM->quotation_id = $QUOTATION_->id;
                $QUOTATION_ITEM->item_code = $item_id;
                $QUOTATION_ITEM->item_name = $item['name'];
                $QUOTATION_ITEM->price = $item['price'];
                $QUOTATION_ITEM->qty = $item['qty'];
                $QUOTATION_ITEM->discount = $item['discount'];
                $QUOTATION_ITEM->sub_total = $subTotal;
                $QUOTATION_ITEM->create();
            }
        }
        //audit log
        $AUDIT_LOG = new AuditLog(NUll);
        $AUDIT_LOG->ref_id = $QUOTATION_->id;
        $AUDIT_LOG->ref_code = $QUOTATION_->quotation_no;
        $AUDIT_LOG->action = 'UPDATE';
        $AUDIT_LOG->description = 'UPDATE QUATATION NO #' . $QUOTATION_->quotation_no;
        $AUDIT_LOG->user_id = $_SESSION['id'];
        $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
        $AUDIT_LOG->create();

        echo json_encode([
            "status" => 'success'
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => 'error',
            "message" => "Failed to update quotation"
        ]);
        exit();
    }
}




if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $QUOTATION = new Quotation($_POST['id']);

    $result = $QUOTATION->delete();

    $AUDIT_LOG = new AuditLog(NUll);
    $AUDIT_LOG->ref_id = $_POST['id'];
    $AUDIT_LOG->ref_code = $QUOTATION->payment_type;
    $AUDIT_LOG->action = 'DELETE';
    $AUDIT_LOG->description = 'DELETE QUATATION NO #' . $QUOTATION->payment_type;
    $AUDIT_LOG->user_id = $_SESSION['id'];
    $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
    $AUDIT_LOG->create();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

// Get quotation by ID
if (isset($_POST['action']) && $_POST['action'] == 'get_quotation') {

    $quotation = new Quotation($_POST['id']);
    $quotationItems = new QuotationItem();
    $items = $quotationItems->getByQuotationId($_POST['id']);

    $customerMaster = new CustomerMaster($quotation->customer_id);

    $customerData = [
        'customer_code'   => $customerMaster->code,
        'customer_name'   => $customerMaster->name,
        'address'         => $customerMaster->address,
        'mobile_number'   => $customerMaster->mobile_number
    ];

    $enhancedItems = [];

    foreach ($items as $item) {
        $ITEM_MASTER = new ItemMaster($item['item_code']); // item_code must exist in item row

        $item['item_code'] = $ITEM_MASTER->code;
        $item['item_id'] = $ITEM_MASTER->id;
        $enhancedItems[] = $item;
    }

    $data = [
        'quotation' => get_object_vars($quotation),
        'items' => $enhancedItems,
        'customer' => $customerData
    ];

    echo json_encode(['status' => 'success', 'data' => $data]);
}


// Get customer by ID
if (isset($_POST['action']) && $_POST['action'] == 'get_customer_by_id') {
    $customerId = $_POST['customer_id'];
    $customer = new CustomerMaster($customerId);

    if ($customer->id) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $customer->id,
                'code' => $customer->code,
                'name' => $customer->name,
                'address' => $customer->address,
                'mobile_number' => $customer->mobile_number,
                'email' => $customer->email,
                'credit_limit' => $customer->credit_limit,
                'balance' => $customer->outstanding
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
    }
    exit();
}
