<?php 
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
var_dump($_POST['quotation_id']);
exit();
// Create a new quotation
if (isset($_POST['action']) && $_POST['action'] == 'create_quotation') {


    $quotationId = $_POST['quotation_id'];
    $items = json_decode($_POST['items'], true); // array of items
    $paid = $_POST['paid'];
    $paymentType = $_POST['payment_type'];

    $totalSubTotal = 0;
    $totalDiscount = 0;

    // Calculate subtotal and discount
    foreach ($items as $item) {
        $price = floatval($item['price']);
        $qty = floatval($item['qty']);
        $discount = isset($item['discount']) ? floatval($item['discount']) : 0; // item-wise discount

        $itemTotal = $price * $qty;
        $totalSubTotal += $itemTotal;
        $totalDiscount += $discount;
    }

    $netTotal = $totalSubTotal - $totalDiscount;

   
    // Create invoice
    $QUOTATION_ = new Quotation(NULL);

    $QUOTATION_->quotation_no = $quotationId;
    $QUOTATION_->date = $_POST['date'];
    $QUOTATION_->company_id = $_POST['company_id'];
    $QUOTATION_->customer_id = $_POST['customer_code'];
    $QUOTATION_->department_id = $_POST['department_id'];
    $QUOTATION_->marketing_executive_id = $_POST['marketing_executive_id'];

    $QUOTATION_->sale_type = $_POST['sales_type'];
    $QUOTATION_->discount_type = $paymentType;
    $QUOTATION_->sub_total = $totalSubTotal;
    $QUOTATION_->discount = $totalDiscount;
    $QUOTATION_->tax = $vat;
    $QUOTATION_->grand_total = $grandTotal;
    $QUOTATION_->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoiceResult = $QUOTATION_->create();

    if ($invoiceResult) {
        $quotationId = $invoiceResult;

        foreach ($items as $item) {

            $ITEM_MASTER = new ItemMaster(NULL);
            $item_code = $ITEM_MASTER->getIdbyItemCode($item['code']);


            $QUOTATION_ITEM = new QuotationItem(NULL);
            $QUOTATION_ITEM->temp_invoice_id = $quotationId;
            $QUOTATION_ITEM->product_id = $item['item_id'];;
            $QUOTATION_ITEM->product_name = $item['name'];
            $QUOTATION_ITEM->price = $item['price'];
            $QUOTATION_ITEM->quantity = $item['qty'];
            $QUOTATION_ITEM->discount = isset($item['discount']) ? $item['discount'] : 0;
            $QUOTATION_ITEM->total = ($item['price'] * $item['qty']) - $QUOTATION_ITEM->discount;
            // $QUOTATION_ITEM->user_id = $_SESSION['user_id'];
            $QUOTATION_ITEM->created_at = date("Y-m-d H:i:s");
            $QUOTATION_ITEM->create();
        }

        echo json_encode([
            "status" => 'success',
            "invoice_id" => $quotationId,
            "sub_total" => $totalSubTotal,
            "discount" => $totalDiscount,
            "vat" => $vat,
            "grand_total" => $grandTotal
        ]);
        exit();

    } else {
        echo json_encode(["status" => 'error']);
        exit();
    }
}


// Update invoice details
if (isset($_POST['update'])) {
    $quotationId = $_POST['invoice_id']; // Retrieve invoice ID

    // Create SalesInvoice object and load the data by ID
    $QUOTATION_ = new SalesInvoice($quotationId);

    // Update invoice details
    $QUOTATION_->invoice_date = date("Y-m-d H:i:s"); // You can update the date or other details here
    $QUOTATION_->company_id = $_POST['company_id'];
    $QUOTATION_->customer_id = $_POST['customer_id'];
    $QUOTATION_->department_id = $_POST['department_id'];
    $QUOTATION_->sale_type = $_POST['sale_type'];
    $QUOTATION_->discount_type = $_POST['discount_type'];
    $QUOTATION_->sub_total = $_POST['sub_total'];
    $QUOTATION_->discount = $_POST['discount'];
    $QUOTATION_->tax = $_POST['tax'];
    $QUOTATION_->grand_total = $_POST['grand_total']; // New grand total
    $QUOTATION_->remark = $_POST['remark'];

    // Attempt to update the invoice
    $result = $QUOTATION_->update();

    if ($result) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}

// Delete invoice
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $invoice = new SalesInvoice($_POST['id']);
    $result = $invoice->delete(); // Make sure this method exists in your class

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>