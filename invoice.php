<!doctype html>
<?php
include 'class/include.php';

$COMPANY_PROFILE = new CompanyProfile(1)
    ?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Invoice Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Unicons CDN -->
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link href="assets/css/icons.min.css" rel="stylesheet" />
    <!-- App CSS -->
    <link href="assets/css/app.min.css" rel="stylesheet" />

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
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="invoice-title">

                    <div class="col-sm-6 text-sm-end float-end">
                        <p><strong>Invoice No:</strong> #MN0131</p>
                        <p><strong>Invoice Date:</strong> 09 Jul, 2020</p>
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
                             
                            <p>Preston Miller<br>4450 Fancher Drive<br>Dallas, TX
                                75247<br>PrestonMiller@armyspy.com<br>001-234-5678</p>
                        </div>
                    </div>


                </div>

                <hr  >



                <h5>Order Summary</h5>
                <div class="table-responsive">
                    <table class="table table-centered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>Nike N012 Running Shoes</td>
                                <td>$260</td>
                                <td>1</td>
                                <td class="text-end">$260.00</td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>Adidas Running Shoes</td>
                                <td>$250</td>
                                <td>1</td>
                                <td class="text-end">$250.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Sub Total</td>
                                <td class="text-end">$510.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Discount</td>
                                <td class="text-end">- $50.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Shipping</td>
                                <td class="text-end">$25.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Tax</td>
                                <td class="text-end">$13.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td class="text-end"><strong>$498.00</strong></td>
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