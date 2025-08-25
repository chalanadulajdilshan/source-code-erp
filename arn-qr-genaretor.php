<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Home | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

    <style>
        .barcode-item {
            width: 60mm;
            height: 40mm;
            margin: 3mm;
            padding: 8mm;
            border: 2px dashed #ccc;
            display: inline-block;
            text-align: center;
            background: #fff;
            page-break-inside: avoid;
            vertical-align: top;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            position: relative;
        }

        .barcode-item .product-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
        }

        .barcode-item svg {
            width: 45mm;
            height: 15mm;
        }

        .barcode-item .barcode-number {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .barcode-info {
            font-size: 10px;
            line-height: 1.3;
            color: #666;
            font-family: Arial, sans-serif;
            margin-top: 5mm;
        }

        .barcode-info .item-details {
            display: flex;
            justify-content: space-between;
            margin-top: 2mm;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5mm;
            justify-content: center;
            padding: 15mm;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 20px;
        }

        .hidden {
            display: none;
        }

        /* Thermal Printer Optimized Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }

            .barcode-container {
                background: none;
                padding: 0;
                margin: 0;
                display: block;
                gap: 0;
            }

            /* One barcode per row for thermal printer */
            .barcode-item {
                width: 58mm;
                height: auto;
                margin: 0;
                padding: 2mm;
                border: none;
                display: block;
                page-break-inside: avoid;
                page-break-after: always;
                box-shadow: none;
                border-radius: 0;
                background: white;
                text-align: center;
            }

            .barcode-item:last-child {
                page-break-after: auto;
            }

            .barcode-item svg {
                width: 50mm;
                height: 12mm;
                margin: 1mm 0;
            }

            .barcode-item .barcode-number {
                font-size: 8px;
                font-weight: bold;
                color: #000;
                font-family: 'Courier New', monospace;
                letter-spacing: 0.5px;
                margin: 1mm 0;
            }

            .card,
            .container-fluid {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }

        /* Thermal printer paper size (58mm width) */
        @page {
            size: 58mm auto;
            margin: 2mm;
        }
    </style>
</head>

<body data-layout="horizontal" data-topbar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'navigation.php' ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row no-print">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="card-title mb-0">ARN Barcodes Generator</h4>
                                        <div class="d-flex">
                                            <div class="input-group me-2" style="width: 300px;">
                                                <input type="text" id="arnSearch" class="form-control" placeholder="Enter ARN Number...">
                                                <button class="btn btn-primary" type="button" id="searchArnBtn">
                                                    <i class="fas fa-search me-1"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Cost</th>
                                                    <th>Item Quantity</th>
                                                    <th>Item Unit Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemTableBody">
                                                <tr id="noDataRow">
                                                    <td colspan="5" class="text-center">No data available</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3 hidden" id="printButtons">
                                        <button class="btn btn-success me-2" type="button" id="generateAllBarcodesBtn">
                                            <i class="fas fa-barcode me-1"></i> Generate All Barcodes
                                        </button>
                                        <button class="btn btn-info me-2 hidden" type="button" id="printAllBtn">
                                            <i class="fas fa-print me-1"></i> Print All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barcodes Container -->
                    <div class="row" id="barcodesSection">
                        <div class="col-lg-12">
                            <div class="barcode-container" id="barcodesContainer"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'footer.php' ?>
        </div>
    </div>

    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="ajax/js/common.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>

    <script src="ajax/js/arn-qr-genarator.js"></script>
    <?php include 'main-js.php' ?>


</body>

</html>