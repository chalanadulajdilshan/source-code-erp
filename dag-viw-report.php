<?php
include 'class/include.php';
include 'auth.php';

$DAG_REPORT = new Dag();

// Get filter parameters
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');
$status = isset($_GET['status']) ? $_GET['status'] : '';
$dag_no = isset($_GET['dag_no']) ? $_GET['dag_no'] : '';

// Get filtered DAG reports
$reports = $DAG_REPORT->getFilteredReports($from_date, $to_date, $status, $dag_no);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>DAG View Report | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />

    <!-- Include main CSS -->
    <?php include 'main-css.php' ?>

    <!-- DataTables CSS -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">
    <div id="layout-wrapper">
        <?php include 'navigation.php' ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">DAG View Report</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                                        <li class="breadcrumb-item active">DAG View Report</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End page title -->

                    <!-- Filter Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Filter Options</h4>
                                    <form id="filter-form" method="get" action="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">From Date</label>
                                                    <input type="date" class="form-control" name="from_date" value="<?php echo $from_date ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date" class="form-control" name="to_date" value="<?php echo $to_date ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="">All Status</option>
                                                        <option value="pending" <?php echo ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                                                        <option value="approved" <?php echo ($status == 'approved') ? 'selected' : '' ?>>Approved</option>
                                                        <option value="rejected" <?php echo ($status == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">DAG Number</label>
                                                    <input type="text" class="form-control" name="dag_no" value="<?php echo htmlspecialchars($dag_no) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary w-md">Filter</button>
                                            <a href="dag-viw-report.php" class="btn btn-secondary w-md">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Section -->

                    <!-- Report Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-4">
                                        <h4 class="card-title">DAG Report</h4>
                                        <div>
                                            <button class="btn btn-danger btn-sm" onclick="printReport()">
                                                <i class="mdi mdi-printer me-1"></i> Print
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dag-report-table" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ref No</th>
                                                    <th>Date</th>
                                                    <th>Company</th>
                                                    <th>Department</th>
                                                    <th>Belt Design</th>
                                                    <th>Barcode</th>
                                                    <th>Vehicle No</th>
                                                    <th>Qty</th>
                                                    <th>Total Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($reports)): ?>
                                                    <?php $counter = 1; ?>
                                                    <?php foreach ($reports as $report): ?>
                                                        <tr>
                                                            <td><?php echo $counter++; ?></td>
                                                            <td><?php echo htmlspecialchars($report['ref_no']); ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($report['received_date'])); ?></td>
                                                            <td><?php echo htmlspecialchars($report['company_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($report['department_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($report['belt_design']); ?></td>
                                                            <td><?php echo htmlspecialchars($report['barcode']); ?></td>
                                                            <td><?php echo htmlspecialchars($report['vehicle_no']); ?></td>
                                                            <td class="text-center"><?php echo $report['qty']; ?></td>
                                                            <td class="text-end"><?php echo number_format($report['total_amount'], 2); ?></td>
                                                            <td>
                                                                <?php
                                                                $status_class = '';
                                                                switch (strtolower($report['status'])) {
                                                                    case 'approved':
                                                                        $status_class = 'bg-success';
                                                                        break;
                                                                    case 'rejected':
                                                                        $status_class = 'bg-danger';
                                                                        break;
                                                                    default:
                                                                        $status_class = 'bg-warning';
                                                                }
                                                                ?>
                                                                <span class="badge <?php echo $status_class; ?> font-size-12">
                                                                    <?php echo ucfirst($report['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="dag-view.php?id=<?php echo $report['id']; ?>" class="btn btn-primary btn-sm">
                                                                    <i class="mdi mdi-eye"></i>
                                                                </a>
                                                                <a href="dag-receipt-print.php?id=<?php echo $report['id']; ?>" target="_blank" class="btn btn-info btn-sm">
                                                                    <i class="mdi mdi-printer"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="12" class="text-center">No records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Report Table -->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include 'footer.php' ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <?php include 'main-js.php' ?>

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script>
        $(document).ready(function() {
            $('#dag-report-table').DataTable({
                "order": [
                    [2, "desc"]
                ], // Sort by date descending by default
                "pageLength": 25,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });

        function exportToExcel() {
            window.location.href = 'ajax/export-dag-report.php?from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>&status=<?php echo urlencode($status); ?>&dag_no=<?php echo urlencode($dag_no); ?>';
        }

        function printReport() {
            window.print();
        }
    </script>

    <script>
        // Ensure session is maintained during form submission
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');

            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    // Get all form data
                    const formData = new FormData(filterForm);

                    // Add session ID if not already in form data
                    if (!formData.has('PHPSESSID') && '<?php echo session_id(); ?>') {
                        formData.append('PHPSESSID', '<?php echo session_id(); ?>');
                    }

                    // Build URL with query parameters
                    const params = new URLSearchParams(formData).toString();
                    window.location.href = 'dag-viw-report.php?' + params;
                    e.preventDefault();
                });
            }
        });
    </script>
</body>

</html>