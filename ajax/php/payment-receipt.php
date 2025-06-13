<?php
include '../../class/include.php';
header('Content-Type: application/json');

if ($_POST['action'] === 'get_credit_invoices') {
    $customerId = (int) $_POST['customer_id'];

    $INVOICE = new SalesInvoice(null);
    $data = $INVOICE->getCreditInvoicesByCustomerAndStatus(0, $customerId); // status = 1 (Active)

    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}
