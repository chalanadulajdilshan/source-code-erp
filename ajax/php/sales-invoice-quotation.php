<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
file_put_contents('debug_log.txt', print_r($_POST, true));


// ✅ Check if invoice number exists
if (isset($_POST['action']) && $_POST['action'] == 'check_invoice_id') {

    $invoice_no = trim($_POST['invoice_no']);
    $SALES_INVOICE = new SalesInvoice(NULL);
    $res = $SALES_INVOICE->checkInvoiceIdExist($invoice_no);

    echo json_encode(['exists' => $res]);
    exit();
}

if (isset($_POST['create'])) {

    $invoice_no = $_POST['invoice_no'];
    $items = json_decode($_POST['items'], true);
    $paid = $_POST['paid'];
    $payment_type = $_POST['payment_type'];

    $total_sub_total = 0;
    $total_discount = 0;
    $final_cost = 0;

    foreach ($items as $item) {


        $price = floatval($item['payment']);
        $qty = floatval($item['qty']);
        $item_total = $price * $qty;

        $total_sub_total += $item_total;
        $final_cost += $item_total; // Assuming cost same as total for quotation



        $QUOTATION_ITEM = new QuotationItem($item['quotation_id']);
        $QUOTATION_ITEM->casing_cost = $price;
        $QUOTATION_ITEM->total_amount = $item_total;
        $QUOTATION_ITEM->update();
    }

    //update QUOTATION print status
    $QUOTATION_ = new Quotation($_POST['quotation_id']);
    $QUOTATION_->is_print = 1;
    $QUOTATION_->update();

    $net_total = $total_sub_total;

    $USER = new User($_SESSION['id']);
    $COMPANY_PROFILE = new CompanyProfile($USER->company_id);

    if ($COMPANY_PROFILE->is_vat == 1) {
        $vat = round(($net_total * $COMPANY_PROFILE->vat_percentage) / 100, 2);
        $grand_total = $net_total + $vat;
    } else {
        $grand_total = $net_total;
    }

    $SALES_INVOICE = new SalesInvoice(NULL);
    $SALES_INVOICE->invoice_no = $invoice_no;
    $SALES_INVOICE->ref_id = $_POST['quotation_id'];

    $SALES_INVOICE->invoice_date = date("Y-m-d H:i:s");
    $SALES_INVOICE->company_id = $_POST['company_id'];
    $SALES_INVOICE->invoice_type = 'QUOTATION';
    $SALES_INVOICE->customer_id = $_POST['customer_id'];
    $SALES_INVOICE->department_id = $_POST['department_id'];
    $SALES_INVOICE->final_cost = $final_cost;
    $SALES_INVOICE->payment_type = $payment_type;
    $SALES_INVOICE->sub_total = $total_sub_total;
    $SALES_INVOICE->discount = $total_discount;
    $SALES_INVOICE->tax = $vat;
    $SALES_INVOICE->grand_total = $grand_total;
    $SALES_INVOICE->remark = !empty($_POST['remark']) ? $_POST['remark'] : null;

    $invoice_id = $SALES_INVOICE->create();

    // Document tracking update
    $DOCUMENT_TRACKING = new DocumentTracking(null);
    if ($payment_type == 'cash') {
        $DOCUMENT_TRACKING->incrementDocumentId('cash');
    } else if ($payment_type == 'credit') {
        $DOCUMENT_TRACKING->incrementDocumentId('credit');
    } else {
        $DOCUMENT_TRACKING->incrementDocumentId('invoice');
    }

    // If invoice creation successful
    if ($invoice_id) {
        foreach ($items as $item) {
            if (!isset($item['is_quotation']) || !$item['is_quotation'])
                continue;

            // Log audit
            $AUDIT_LOG = new AuditLog(NULL);
            $AUDIT_LOG->ref_id = $invoice_id;
            $AUDIT_LOG->ref_code = $invoice_no;
            $AUDIT_LOG->action = 'CREATE';
            $AUDIT_LOG->description = 'CREATE QUOTATION INVOICE #' . $invoice_no;
            $AUDIT_LOG->user_id = $_SESSION['id'];
            $AUDIT_LOG->created_at = date("Y-m-d H:i:s");
            $AUDIT_LOG->create();
        }

        echo json_encode([
            "status" => 'success',
            "invoice_id" => $invoice_id,
            "sub_total" => $total_sub_total,
            "discount" => $total_discount,
            "vat" => $vat,
            "grand_total" => $grand_total
        ]);
        exit();
    } else {
        echo json_encode(["status" => 'error']);
        exit();
    }
}

// 🔄 Future updates / filtering / deletion can be added here if needed

?>