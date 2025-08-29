<?php

include '../../class/include.php';
include '../../auth.php';


if (isset($_POST['action']) && $_POST['action'] === 'get_invoice_id_by_type') {

    $payment_type = trim($_POST['payment_type']); // Trim to remove any whitespace
    $DOCUMENT_TRACKING = new DocumentTracking(1); // Use the correct document tracking ID (1)


    // Choose last ID field based on payment type
    if ($payment_type === '1') {
        $lastNumber = $DOCUMENT_TRACKING->cash_id;
        $invoiceNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $invoice_id = $COMPANY_PROFILE_DETAILS->company_code . '/CA/0' . $_SESSION['id'] . '/' . $invoiceNumber;
    } elseif ($payment_type === '2') {
        $lastNumber = $DOCUMENT_TRACKING->credit_id;
        $invoiceNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $invoice_id = $COMPANY_PROFILE_DETAILS->company_code . '/CR/0' . $_SESSION['id'] . '/' . $invoiceNumber;
    } else {
        echo json_encode(['error' => true, 'message' => 'Invalid payment type']);
        exit;
    }

    echo json_encode(['invoice_id' => $invoice_id]);
    exit;
}
