<?php
include '../../class/include.php';

// Get filters from POST, with default fallback
$filters = [
    'all_customers' => $_POST['all_customers'] ?? 0,
    'customer_code' => $_POST['customer_code'] ?? '',
    'from_date' => $_POST['from_date'] ?? '',
    'to_date' => $_POST['to_date'] ?? '',
    'date_range' => $_POST['date_range'] ?? '',
    'status' => $_POST['status'] ?? ''  // status filter
];

// Call the static filter method from SalesInvoice class
$SALES_INVOICE = new SalesInvoice();
$invoices = $SALES_INVOICE->filterSalesInvoices($filters);

// Initialize totals
$totalGrossAmount = 0;
$totalVat = 0;
$totalNetAmount = 0;

$html = '<table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Cr. Days</th>
                    <th>Gross Amount</th>
                    <th>VAT</th>
                    <th>Net Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';

foreach ($invoices as $inv) {
    // Accumulate totals (assuming these keys exist in $inv)
    $totalGrossAmount += (float) $inv['gross_amount'];
    $totalVat += (float) $inv['vat'];
    $totalNetAmount += (float) $inv['net_amount'];

    $html .= '<tr>
                <td>' . htmlspecialchars($inv['invoice_date']) . '</td>
                <td>' . htmlspecialchars($inv['invoice_no']) . '</td>
                <td>' . htmlspecialchars($inv['customer_id']) . '</td> <!-- You might want to get the customer name instead -->
                <td>' . htmlspecialchars($inv['credit_days'] ?? '') . '</td> <!-- Assuming credit days field -->
                <td class="text-right">' . number_format($inv['gross_amount'], 2) . '</td>
                <td class="text-right">' . number_format($inv['vat'], 2) . '</td>
                <td class="text-right">' . number_format($inv['net_amount'], 2) . '</td>
                <td>' . htmlspecialchars(ucfirst($inv['status'])) . '</td>
              </tr>';
}

// Add totals row
$html .= '<tr style="font-weight:bold; background-color:#f1f1f1;">
            <td colspan="4" class="text-right">Total</td>
            <td class="text-right">' . number_format($totalGrossAmount, 2) . '</td>
            <td class="text-right">' . number_format($totalVat, 2) . '</td>
            <td class="text-right">' . number_format($totalNetAmount, 2) . '</td>
            <td></td>
          </tr>';

$html .= '</tbody></table>';

echo $html;
?>