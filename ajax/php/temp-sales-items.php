<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_GET['invoice_id'])) {
    $invoice_id = intval($_GET['invoice_id']);
    $tempSalesItem = new SalesInvoiceItem(null);
    $items = $tempSalesItem->getItemsByInvoiceId($invoice_id);

    echo json_encode($items);
}
?>
