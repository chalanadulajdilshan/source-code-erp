<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Save multiple payments
if (isset($_POST['saveMultiple']) && isset($_POST['invoice_id']) && isset($_POST['payments'])) {

    $invoiceId = $_POST['invoice_id'];
    $payments = $_POST['payments']; // This is an array of payments
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    $success = true;

    foreach ($payments as $p) {
        $payment = new InvoicePayment(NULL);
        $payment->invoice_id = $invoiceId;
        $payment->method_id = $p['method_id'];
        $payment->amount = $p['amount'];
        $payment->reference_no = isset($p['reference_no']) ? $p['reference_no'] : null;
        $payment->bank_name = isset($p['bank_name']) ? $p['bank_name'] : null;
        $payment->cheque_date = isset($p['cheque_date']) ? $p['cheque_date'] : null;

        $res = $payment->create();
        if (!$res) {
            $success = false;
            break;
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save payments.']);
        exit();
    }
}
