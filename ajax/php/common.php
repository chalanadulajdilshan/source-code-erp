<?php

include '../../class/include.php';
include '../../auth.php';


if (isset($_POST['action']) && $_POST['action'] === 'get_invoice_id_by_type') {

    $payment_type = $_POST['payment_type'];
    $DOCUMENT_TRACKING = new DocumentTracking($doc_id);


    // Choose last ID field based on payment type
    if ($payment_type == 'cash') {
        $lastNumber = $DOCUMENT_TRACKING->cash_id;
        $invoice_id =  $COMPANY_PROFILE_DETAILS->company_code .'/CA/00/00' . ($lastNumber + 1);
    } elseif ($payment_type == 'credit') {
        $lastNumber = $DOCUMENT_TRACKING->credit_id;
        $invoice_id =  $COMPANY_PROFILE_DETAILS->company_code .'/CR/00/00' . ($lastNumber + 1);
    } else {
        echo json_encode(['error' => true, 'message' => 'Invalid payment type']);
        exit;
    }

    echo json_encode(['invoice_id' => $invoice_id]);
    exit;

}

