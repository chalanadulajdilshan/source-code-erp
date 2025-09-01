<!doctype html>
<?php
include 'class/include.php';

if (!isset($_SESSION)) {
    session_start();
}

$invoice_id = $_GET['invoice_no'];
$US = new User($_SESSION['id']);
$COMPANY_PROFILE = new CompanyProfile($US->company_id);
$SALES_INVOICE = new SalesInvoice($invoice_id);
$CUSTOMER_MASTER = new CustomerMaster($SALES_INVOICE->customer_id);
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Invoice Details </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'main-css.php' ?>
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

    <style>
        @media print {

            /* Hide non-print elements */
            .no-print {
                display: none !important;
            }

            /* Make invoice full width */
            body,
            html {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            #invoice-content,
            .card {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }

            /* Use full page without A5 restriction */
            @page {
                size: auto;
                /* remove specific page size */
                margin: 10mm;
                /* optional margin */
            }


        }

        /* Remove padding and spacing in invoice table */
        #invoice-content table,
        #invoice-content th,
        #invoice-content td {
            padding: 2px !important;
            /* reduce padding */
            margin: 0 !important;
            border-spacing: 0 !important;
            border-collapse: collapse !important;
        }

        #invoice-content th,
        #invoice-content td {
            vertical-align: middle !important;
            /* optional: center content vertically */
        }

        /* Optional: remove Bootstrap table styles */
        #invoice-content .table {
            width: 100%;

            border-top-width: 0 !important;
            border-style: none !important;
        }
    </style>

</head

    <body data-layout="horizontal" data-topbar="colored">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h4>Invoice</h4>
        <div>
            <button onclick="window.print()" class="btn btn-success ms-2">Print</button>
            <button onclick="downloadPDF()" class="btn btn-primary ms-2">PDF</button>
        </div>
    </div>

    <div class="card" id="invoice-content">
        <div class="card-body">
            <!-- Company & Customer Info -->
            <div class="invoice-title">
                <div class="row mb-4">
                    <?php
                    function formatPhone($number)
                    {
                        $number = preg_replace('/\D/', '', $number);
                        if (strlen($number) == 10) {
                            return sprintf("(%s) %s-%s", substr($number, 0, 3), substr($number, 3, 3), substr($number, 6));
                        }
                        return $number;
                    }
                    ?>
                    <div class="col-md-5 text-muted">
                        <p class="mb-1" style="font-weight:bold;font-size:18px;"><?php echo $COMPANY_PROFILE->name ?></p>
                        <p class="mb-1" style="font-size:13px;"><?php echo $COMPANY_PROFILE->address ?></p>
                        <p class="mb-1" style="font-size:13px;"><?php echo $COMPANY_PROFILE->email ?> | <?php echo formatPhone($COMPANY_PROFILE->mobile_number_1); ?></p>
                    </div>
                    <div class="col-md-4 text-sm-start text-md-start">
                        <h3 style="font-weight:bold;font-size:18px;">
                            <?php echo ($SALES_INVOICE->payment_type == 1) ? "CASH SALES INVOICE" : "CREDIT SALES INVOICE"; ?>
                        </h3>
                        <p class="mb-1 text-muted" style="font-size:14px;"><strong>Customer Name:</strong> <?php echo $SALES_INVOICE->customer_name ?></p>
                        <p class="mb-1 text-muted" style="font-size:14px;"><strong>Customer Contact:</strong> <?php echo !empty($SALES_INVOICE->customer_address) ? $SALES_INVOICE->customer_address : '.................................' ?> - <?php echo !empty($SALES_INVOICE->customer_mobile) ? $SALES_INVOICE->customer_mobile : '.................................' ?></p>

                    </div>

                    <div class="col-md-3 text-sm-start text-md-end  ">
                        <p class="mb-1" style="font-size:14px;"><strong>Inv No:</strong> <?php echo $SALES_INVOICE->invoice_no ?></p>
                        <p class="mb-1" style="font-size:14px;"><strong>Inv Date:</strong> <?php echo date('d M, Y', strtotime($SALES_INVOICE->invoice_date)); ?></p>
                    </div>
                </div>
            </div>

            <!-- ITEM INVOICE PRINT -->
            <?php if ($SALES_INVOICE->invoice_type == 'INV') { ?>
                <div class="table-responsive">
                    <table class="table table-centered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>List Price</th>
                                <th>Dis %</th>
                                <th>Selling Price</th>
                                <th>Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px;" class="font-bold">
                            <?php
                            $TEMP_SALES_ITEM = new SalesInvoiceItem(null);
                            $temp_items_list = $TEMP_SALES_ITEM->getItemsByInvoiceId($invoice_id);
                            $subtotal = 0;
                            $total_discount = 0;

                            foreach ($temp_items_list as $key => $temp_items) {
                                $key++;
                                $price = (float) $temp_items['price'];
                                $quantity = (int) $temp_items['quantity'];
                                $discount_percentage = isset($temp_items['discount']) ? (float) $temp_items['discount'] : 0;
                                $discount_per_item = $price * ($discount_percentage / 100);
                                $selling_price = $price - $discount_per_item;
                                $line_total = $price * $quantity;
                                $subtotal += $price * $quantity;
                                $total_discount += $discount_per_item * $quantity;
                                $ITEM_MASTER = new ItemMaster($temp_items['item_code']);
                            ?>
                                <tr>
                                    <td>0<?php echo $key; ?></td>
                                    <td><?php echo $ITEM_MASTER->code . ' ' . $temp_items['item_name']; ?></td>
                                    <td><?php echo number_format($price, 2); ?></td>
                                    <td><?php echo $discount_percentage; ?>%</td>
                                    <td><?php echo number_format($selling_price, 2); ?></td>
                                    <td><?php echo $quantity; ?></td>
                                    <td class="text-end"><?php echo number_format($line_total, 2); ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="4" rowspan="3" style="vertical-align:top;  ">
                                    <h6 style="margin-top:8px;"><strong>Terms & Conditions:</strong></h6>
                                    <ul style="padding-left:20px;margin-bottom:0;">
                                        <?php
                                        $invoiceRemark = new InvoiceRemark();
                                        $paymentRemarks = $invoiceRemark->getRemarkByPaymentType($SALES_INVOICE->payment_type);
                                        if (!empty($paymentRemarks)) {
                                            foreach ($paymentRemarks as $remark) {
                                                if (!empty($remark['remark'])) {
                                                    echo '<li>' . htmlspecialchars($remark['remark']) . '</li>';
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td colspan="2" class="text-end">Gross Amount:-</td>
                                <td class="text-end"><?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end">Discount:-</td>
                                <td class="text-end">- <?php echo number_format($total_discount, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Net Amount:-</strong></td>
                                <td class="text-end"><strong><?php echo number_format($subtotal - $total_discount, 2); ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="padding-top:50px;">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="text-align:center;">_________________________<br><strong>Prepared By</strong></td>
                                            <td style="text-align:center;">_________________________<br><strong>Approved By</strong></td>
                                            <td style="text-align:center;">_________________________<br><strong>Received By</strong></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('invoice-content');
        const opt = {
            margin: 0.5,
            filename: 'Invoice_<?php echo $SALES_INVOICE->invoice_no ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    }

    // Trigger print on Enter
    document.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            window.print();
        }
    });
</script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>