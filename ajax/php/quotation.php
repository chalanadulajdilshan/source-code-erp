<?php 
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
 
// Create a new quotation
 var_dump($_POST['action']);
exit();
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
    $creditPeriod = isset($_POST['credit_period']) ? $_POST['credit_period'] : 30;
    $paymentTerm = isset($_POST['payment_term']) ? $_POST['payment_term'] : $paymentType;
    $validity = isset($_POST['validity']) ? $_POST['validity'] : 30;
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;
    $vat_type = isset($_POST['vat_type']) ? $_POST['vat_type'] : 1;
    $creditLimit = isset($_POST['credit_limit']) ? $_POST['credit_limit'] : 0;
    $balance = isset($_POST['balance']) ? $_POST['balance'] : 0;

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

    // Calculate VAT based on type (1=None, 2=VAT, 3=SVAT)
    $vatRate = $vat_type == 2 ? 0.15 : 0; // 15% VAT only if VAT type = 2
    $vat = ($totalSubTotal - $totalDiscount) * $vatRate;
    $grandTotal = ($totalSubTotal - $totalDiscount) + $vat;

    // Create quotation
    $QUOTATION_ = new Quotation(NULL);

    $QUOTATION_->quotation_no = $quotationId;
    $QUOTATION_->company_id = $companyId;
    $QUOTATION_->date = $date;
    $QUOTATION_->customer_id = $customerId;
    $QUOTATION_->credit_limit = $creditLimit;
    $QUOTATION_->balance = $balance;
    $QUOTATION_->department_id = $departmentId;
    $QUOTATION_->marketing_executive_id = $executiveId;
    $QUOTATION_->payment_type = $paymentType;
    $QUOTATION_->remarks = $remarks;
    $QUOTATION_->credit_period = $creditPeriod;
    $QUOTATION_->payment_term = $paymentTerm;
    $QUOTATION_->validity = $validity;
    $QUOTATION_->sub_total = $totalSubTotal;
    $QUOTATION_->discount = $totalDiscount;
    $QUOTATION_->tax = $vat;
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
        }

        echo json_encode([
            "status" => 'success',
            "quotation_id" => $newQuotationId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount,
            "vat" => $vat,
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
    $quotationId = $_POST['id'] ?? $_POST['quotation_id']; // First try to get the actual ID, then fallback to quotation_no
    $items = json_decode($_POST['items'], true);
    
    // Calculate totals
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
    
    // Calculate VAT based on type (1=None, 2=VAT, 3=SVAT)
    $vatType = $_POST['vat_type'];
    $vatRate = $vatType == 2 ? 0.15 : 0; // 15% VAT only if VAT type = 2
    $vat = ($totalSubTotal - $totalDiscount) * $vatRate;
    $grandTotal = ($totalSubTotal - $totalDiscount) + $vat;

    // Create Quotation object and load the data by ID
    $QUOTATION_ = new Quotation($quotationId);
    
    if (!$QUOTATION_->id) {
        // Try to find the quotation by quotation_no
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

    // Update quotation details
    $QUOTATION_->quotation_no = $_POST['quotation_id'];
    $QUOTATION_->company_id = $_POST['company_id']; 
    $QUOTATION_->date = $_POST['date'];
    $QUOTATION_->customer_id = $_POST['customer_id'];
    $QUOTATION_->credit_limit = isset($_POST['credit_limit']) ? $_POST['credit_limit'] : $QUOTATION_->credit_limit;
    $QUOTATION_->balance = isset($_POST['balance']) ? $_POST['balance'] : $QUOTATION_->balance;
    $QUOTATION_->department_id = $_POST['department_id'];
    $QUOTATION_->marketing_executive_id = $_POST['marketing_executive_id'];
    $QUOTATION_->vat_type = $_POST['vat_type'];
    $QUOTATION_->payment_type = $_POST['payment_type'];
    $QUOTATION_->remarks = isset($_POST['remarks']) ? $_POST['remarks'] : $QUOTATION_->remarks;
    $QUOTATION_->credit_period = isset($_POST['credit_period']) ? $_POST['credit_period'] : $QUOTATION_->credit_period;
    $QUOTATION_->payment_term = isset($_POST['payment_term']) ? $_POST['payment_term'] : $QUOTATION_->payment_term;
    $QUOTATION_->validity = isset($_POST['validity']) ? $_POST['validity'] : $QUOTATION_->validity;
    $QUOTATION_->sub_total = $totalSubTotal;
    $QUOTATION_->discount = $totalDiscount;
    $QUOTATION_->tax = $vat;
    $QUOTATION_->grand_total = $grandTotal;

    // Attempt to update the quotation
    $result = $QUOTATION_->update();

    if ($result) {
    
        $db = new Database();
        $db->readQuery("DELETE FROM `quotation_item` WHERE `quotation_id` = '{$QUOTATION_->id}'");
        
        
        foreach ($items as $item) {
            $ITEM_MASTER = new ItemMaster(NULL);

            $item_id = $ITEM_MASTER->getIdbyItemCode($item['code']);
            
            if (!$item_id) {

                error_log("Could not find item with code: " . $item['code']);
                continue;
            }

            $QUOTATION_ITEM = new QuotationItem(NULL);
            $QUOTATION_ITEM->quotation_id = $QUOTATION_->id;
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
        }
        
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

if (isset($_POST['delete'])) { 
    $QUOTATION = new Quotation($_POST['id']); 

    $result = $QUOTATION->delete();
    
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
    
    $data = [
        'quotation' => get_object_vars($quotation),
        'items' => $items
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
?>