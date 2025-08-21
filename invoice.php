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
    <title>Invoice Details | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

    <!-- Unicons CDN -->
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">



    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                margin: 20mm;
            }

            body.print-a4 {
                width: 210mm;
                height: 297mm;
            }

            body.print-a3 {
                width: 297mm;
                height: 420mm;
            }

            body.print-a5 {
                width: 148mm;
                height: 210mm;
            }

            body.print-letter {
                width: 8.5in;
                height: 11in;
            }

            body.print-legal {
                width: 8.5in;
                height: 14in;
            }

            body.print-tabloid {
                width: 11in;
                height: 17in;
            }

            body.print-dotmatrix {
                width: 9.5in;
                height: 11in;
            }
        }
    </style>
</head>

<body class="print-a4" data-layout="horizontal" data-topbar="colored">

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h4>Invoice</h4>
            <div>
                <select id="printFormat" class="form-select d-inline w-auto" onchange="setPrintFormat(this.value)">
                    <option value="a4" selected>A4</option>
                    <option value="a3">A3</option>
                    <option value="a5">A5</option>
                    <option value="letter">Letter</option>
                    <option value="legal">Legal</option>
                    <option value="tabloid">Tabloid</option>
                    <option value="dotmatrix">Dot Matrix</option>
                </select>
                <button onclick="window.print()" class="btn btn-success ms-2">Print</button>
                <button onclick="downloadPDF()" class="btn btn-primary ms-2">PDF</button>

            </div>
        </div>

        <div class="card" id="invoice-content">
            <div class="card-body">
                <div class="invoice-title">
                    <div class="row mb-4">


                        <!-- Column 2: Company Info -->
                        <div class="col-md-9 col-sm-9 text-muted">
                            <p class="mb-1" style="font-weight: bold;font-size: 20px;"> <?php echo $COMPANY_PROFILE->name ?></p>
                            <p class="mb-1" style="font-size: 14px;"> <?php echo $COMPANY_PROFILE->address ?></p>
                            <p class="mb-1" style="font-size: 14px;"> <?php echo $COMPANY_PROFILE->email ?> - <?php
                                                                                                                function formatPhone($number)
                                                                                                                {
                                                                                                                    $number = preg_replace('/[^0-9]/', '', $number);
                                                                                                                    $number = ltrim($number, '0');
                                                                                                                    $number = '94' . $number;
                                                                                                                    return '(+94) ' . substr($number, 2, 2) . ' ' . substr($number, 4, 3) . ' ' . substr($number, 7);
                                                                                                                }

                                                                                                                $mobile1 = !empty($COMPANY_PROFILE->mobile_number_1) ? formatPhone($COMPANY_PROFILE->mobile_number_1) : '';
                                                                                                                $mobile2 = !empty($COMPANY_PROFILE->mobile_number_2) ? formatPhone($COMPANY_PROFILE->mobile_number_2) : '';
                                                                                                                echo $mobile1 . ($mobile1 && $mobile2 ? ' / ' : '') . $mobile2;
                                                                                                                ?>
                            </p>
                        </div>



                        <!-- Column 4: Customer Info -->
                        <div class="col-md-3 col-sm-6 text-sm-start text-md-end">
                            <h3 style="font-weight: bold;font-size: 20px;"><?php echo strtoupper($SALES_INVOICE->payment_type) ?> SALES INVOICE</h3>
                        </div>

                        <hr>
                        <!-- Column 5: Customer Info -->
                        <div class="col-md-6 col-sm-6 text-muted">
                            <p class="mb-1" style="font-size: 14px;"> <span style="font-weight: bold;">Customer Name:</span> <?php echo $SALES_INVOICE->customer_name ?></p>
                            <p class="mb-1" style="font-size: 14px;"> <span style="font-weight: bold;">Customer Address:</span> <?php echo !empty($SALES_INVOICE->customer_address) ? $SALES_INVOICE->customer_address : '.................................' ?></p>
                            <p class="mb-1" style="font-size: 14px;"> <span style="font-weight: bold;">Customer Mobile:</span><?php echo !empty($SALES_INVOICE->customer_mobile) ? $SALES_INVOICE->customer_mobile : '.................................' ?> </p>


                        </div>

                        <!-- Column 3: Invoice Info -->
                        <div class="col-md-3 col-sm-6 text-sm-start text-md-end">

                        </div>

                        <!-- Column 4: Customer Info -->
                        <div class="col-md-3 col-sm-6 text-sm-start text-md-end">
                            <p class="mb-1" style="font-size: 14px;"> <span style="font-weight: bold;"> Invoice No :</span> <?php echo $SALES_INVOICE->invoice_no  ?></p>
                            <p class="mb-1" style="font-size: 14px;"> <span style="font-weight: bold;">Invoice Date:</span> <?php echo date('d M, Y', strtotime($SALES_INVOICE->invoice_date)); ?></p>

                        </div>


                    </div>
                </div>
                <!-- ITEM INVOICE PRINT -->
                <?php if ($SALES_INVOICE->invoice_type == 'INV') { ?>

                    <!-- item invoice print -->
                    <div class="table-responsive">
                        <table class="table table-centered">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Item</th>
                                    <th>List Price</th>
                                    <th>Dis % </th>
                                    <th>Selling Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
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

                                    // Calculate selling price after discount (per item)
                                    $discount_per_item = $price * ($discount_percentage / 100);
                                    $selling_price = $price - $discount_per_item;

                                    // Line total = selling price × quantity
                                    $line_total = $price * $quantity;

                                    // Totals
                                    $subtotal += $price * $quantity;
                                    $total_discount += $discount_per_item * $quantity;
                                ?>

                                    <tr>
                                        <td>0<?php echo $key; ?></td>
                                        <td><?php echo $temp_items['item_code'] . ' ' . $temp_items['item_name']; ?></td>
                                        <td><?php echo number_format($price, 2); ?></td>
                                        <td><?php echo $discount_percentage; ?>%</td>
                                        <td><?php echo number_format($selling_price, 2); ?></td> <!-- Selling price per item -->
                                        <td><?php echo $quantity; ?></td>
                                        <td class="text-end"><?php echo number_format($line_total, 2); ?></td>
                                    </tr>
                                <?php } ?>

                                <!-- Totals section -->
                                <tr>
                                    <td colspan="4" rowspan="3" style="vertical-align: top;">
                                        <!-- Terms & Conditions on the left -->
                                        <h6><strong>Terms & Conditions:</strong></h6>
                                        <ul style="padding-left: 20px; margin-bottom: 0;">
                                            <?php
                                            // Add payment type specific remarks
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


                                    <td colspan="2" class="text-end">Gross Amount:- </td>
                                    <td class="text-end"><?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Discount:- </td>
                                    <td class="text-end">- <?php echo number_format($total_discount, 2); ?></td>

                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Net Amount:- </strong></td>
                                    <td class="text-end">
                                        <strong><?php echo number_format($subtotal - $total_discount, 2); ?></strong>
                                    </td>
                                </tr>

                                <!-- Signature line -->
                                <tr>
                                    <td colspan="7" style="padding-top: 50px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Prepared By</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Approved By</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Received By</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                            </tbody>


                        </table>
                    </div>
                <?php } else { ?>

                    <!-- BELT INVOICE PRINT -->
                    <h5>Order Summary</h5>
                    <div class="table-responsive">
                        <table class="table table-centered">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Vehicle No</th>
                                    <th>Belt</th>
                                    <th>Barcode</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $DAG_ITEM = new DagItem(NULL);

                                $subtotal = 0;
                                $total_discount = 0;

                                foreach ($DAG_ITEM->getByDagId($SALES_INVOICE->ref_id) as $key => $dag_item) {
                                    $key++;
                                    $price = (float) $dag_item['casing_cost'];
                                    $quantity = (int) $dag_item['qty'];
                                    $discount_percentage = isset($SALES_INVOICE->discount) ? (float) $SALES_INVOICE->discount : 0;
                                    $BELT_MASTER = new BeltMaster($dag_item['belt_id']);
                                    // Calculate selling price after discount (per item)
                                    $discount_per_item = $price * ($discount_percentage / 100);
                                    $selling_price = $price - $discount_per_item;

                                    // Line total = selling price × quantity
                                    $line_total = $price * $quantity;

                                    // Totals
                                    $subtotal += $price * $quantity;
                                    $total_discount += $discount_per_item * $quantity;
                                ?>

                                    <tr>
                                        <td>0<?php echo $key; ?></td>
                                        <td><?php echo $dag_item['vehicle_no']; ?></td>
                                        <td><?php echo $BELT_MASTER->name; ?></td>
                                        <td><?php echo $dag_item['barcode']; ?>%</td>
                                        <td><?php echo number_format($price, 2); ?></td> <!-- Selling price per item -->
                                        <td><?php echo $quantity; ?></td>
                                        <td class="text-end"><?php echo number_format($line_total, 2); ?></td>
                                    </tr>
                                <?php } ?>

                                <!-- Totals section -->
                                <tr>
                                    <td colspan="4" rowspan="3" style="vertical-align: top;">
                                        <!-- Terms & Conditions on the left -->
                                        <h6><strong>Terms & Conditions:</strong></h6>
                                        <ul style="padding-left: 20px; margin-bottom: 0;">
                                            <?php
                                            // Add payment type specific remarks
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
                                            <li>All goods once sold are non-refundable.</li>
                                            <li>Warranty as per manufacturer policy.</li>
                                            <li>Payment due within 15 days.</li>
                                        </ul>
                                    </td>


                                    <td colspan="2" class="text-end">Gross Amount:- </td>
                                    <td class="text-end"><?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Discount:- </td>
                                    <td class="text-end">- <?php echo number_format($total_discount, 2); ?></td>

                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Net Amount:- </strong></td>
                                    <td class="text-end">
                                        <strong><?php echo number_format($subtotal - $total_discount, 2); ?></strong>
                                    </td>
                                </tr>

                                <!-- Signature line -->
                                <tr>
                                    <td colspan="7" style="padding-top: 50px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Prepared By</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Approved By</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    _________________________<br>
                                                    <strong>Received By</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                            </tbody>


                        </table>
                    </div>
                <?php

                }
                ?>

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
                    unit: 'in',
                    format: 'a3',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Apply print format on load
        window.onload = function() {
            setPrintFormat('a4');
        };

        function setPrintFormat(format) {
            const formats = [
                'a4', 'a3', 'a5',
                'letter', 'legal',
                'tabloid', 'dotmatrix'
            ];
            document.body.className = document.body.className
                .split(' ')
                .filter(c => !formats.map(f => 'print-' + f).includes(c))
                .join(' ')
                .trim();

            document.body.classList.add('print-' + format);
        }

        document.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                window.print();
            }
        });
    </script>
</body>

</html>