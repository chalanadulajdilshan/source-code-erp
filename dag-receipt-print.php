<!doctype html>
<?php
include 'class/include.php';

if (!isset($_SESSION)) {
    session_start();
}

$dag_id = $_GET['id'];
$US = new User($_SESSION['id']);
$COMPANY_PROFILE = new CompanyProfile($US->company_id);

$DAG = new Dag($dag_id);
$CUSTOMER_MASTER = new CustomerMaster($DAG->customer_id);
$DEPARTMENT_MASTER = new DepartmentMaster($DAG->department_id)
    ?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Receipt Details | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
            <h4>Dag Receipt</h4>
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
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="invoice-title">

                    <div class="col-sm-6 text-sm-end float-end">
                        <p><strong>Receipt No:</strong> #<?php echo $DAG->ref_no ?></p>
                        <p><strong>Receipt Date:</strong>
                            <?php echo date('d M, Y', strtotime($DAG->received_date)); ?></p>
                        <p><strong>Department Name:</strong> <?php echo $DEPARTMENT_MASTER->name ?></p>
                    </div>
                    <div class="mb-4">
                        <img src="./uploads/company-logos/<?php echo $COMPANY_PROFILE->image_name ?>" alt="logo">
                    </div>

                    <div class="row mb-4">
                        <!-- Left: Company Info -->
                        <div class="col-sm-6">
                            <div class="text-muted">
                                <p class="mb-1"><i
                                        class="uil uil-building me-1"></i><?php echo $COMPANY_PROFILE->name ?></p>
                                <p class="mb-1"><i
                                        class="uil uil-map-marker me-1"></i><?php echo $COMPANY_PROFILE->address ?></p>
                                <p class="mb-1"><i
                                        class="uil uil-envelope-alt me-1"></i><?php echo $COMPANY_PROFILE->email ?></p>
                                <p><i class="uil uil-phone me-1"></i><?php echo $COMPANY_PROFILE->mobile_number_1 ?></p>
                            </div>
                        </div>

                        <!-- Right: Billed To -->
                        <div class="col-sm-6 text-sm-end">

                            <p><?php echo $CUSTOMER_MASTER->name ?><br><?php echo $CUSTOMER_MASTER->address ?>
                                <br><?php echo $CUSTOMER_MASTER->mobile_number ?><br>
                                <?php echo $CUSTOMER_MASTER->email ?>
                            </p>
                        </div>
                    </div>


                </div>




                <div class="table-responsive">
                    <table class="table table-centered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Vehicle No</th>
                                <th>Belt Name</th>
                                <th>Barcode</th>
                                <th>Cost</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $DAG_ITEM = new DagItem(null);

                            $subtotal = 0;
                            foreach ($DAG_ITEM->getByDagId($dag_id) as $key => $dag_item) {
                                $key++;
                                $BELT_MASTER = new BeltMaster($dag_item['belt_id']);

                                $subtotal += (float) $dag_item['total_amount'];
                                ?>

                                <tr>
                                    <td>0<?php echo $key; ?></td>
                                    <td><?php echo $dag_item['vehicle_no']; ?></td>
                                    <td><?php echo $BELT_MASTER->name ?></td>
                                    <td><?php echo $dag_item['barcode']; ?>%</td>
                                    <td><?php echo number_format($dag_item['casing_cost'], 2); ?></td>
                                    <!-- Selling price per item -->
                                    <td><?php echo $dag_item['qty']; ?></td>
                                    <td class="text-end"><?php echo number_format($dag_item['total_amount'], 2); ?></td>
                                </tr>
                            <?php } ?>

                            <!-- Totals section -->
                            <tr>
                                <td colspan="4" rowspan="3" style="vertical-align: top;">
                                    <!-- Terms & Conditions on the left -->
                                    <h6><strong>Terms & Conditions:</strong></h6>
                                    <ul style="padding-left: 20px; margin-bottom: 0;">
                                        <li>All goods once sold are non-refundable.</li>
                                        <li>Warranty as per manufacturer policy.</li>
                                        <li>Payment due within 15 days.</li>
                                    </ul>
                                </td>


                                <td colspan="2" class="text-end"> </td>
                                <td class="text-end"> </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end"> </td>
                                <td class="text-end"> </td>

                            </tr>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Net Amount:- </strong></td>
                                <td class="text-end">
                                    <strong><?php echo number_format($subtotal, 2); ?></strong>
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

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Apply print format on load
        window.onload = function () {
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

        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                window.print();
            }
        });
    </script>
</body>

</html>