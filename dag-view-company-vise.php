<?php
include 'class/include.php';
include 'auth.php';

$companyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$companyId) {
    header("Location: dag-company.php");
    exit();
}

// Fetch company details
$DAG_COMPANY = new DagCompany($companyId);
$companyName = $DAG_COMPANY->name;

// Fetch DAGs by company
$DAG = new DAG(null);
$dagList = $DAG->getByCompany($companyId);
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Create Dag | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

    <style>
        .editing-row {
            background-color: #fff3cd !important;
        }
    </style>

</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'navigation.php' ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">


                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Creat Dag</li>
                            </ol>
                        </div>
                    </div>

                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">

                                <div class="p-4">

                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    01
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1"> View Dag in - " <?php echo $companyName ?> "
                                            </h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below Creat
                                                Dag</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">

                                    <table class="table table-bordered table-striped" id="dagListTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ref No</th>
                                                <th>Customer</th>
                                                <th>Recived Date</th>
                                                <th>Com Delivery Date</th>
                                                <th>Receipt No</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($dagList as $index => $dag): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($dag['ref_no']) ?></td>

                                                    <td>
                                                        <?php
                                                        $CUSTOMER_MASTER = new CustomerMaster($dag['customer_id']);
                                                        echo htmlspecialchars($CUSTOMER_MASTER->name);
                                                        ?>
                                                    </td>

                                                    <td><?= htmlspecialchars($dag['received_date']) ?></td>
                                                    <td><?= htmlspecialchars($dag['company_delivery_date']) ?></td>
                                                    <td><?= htmlspecialchars($dag['receipt_no']) ?></td>

                                                    <td>
                                                        <?php
                                                        $status = $dag['status'];

                                                        if ($status == 'pending') {
                                                            echo '<span class="badge bg-soft-warning font-size-12">Pending</span>';
                                                        } elseif ($status == 'assigned') {
                                                            echo '<span class="badge bg-soft-primary font-size-12">Assigned</span>';
                                                        } elseif ($status == 'received') {
                                                            echo '<span class="badge bg-soft-info font-size-12">Received</span>';
                                                        } elseif ($status == 'rejected_company') {
                                                            echo '<span class="badge bg-soft-danger font-size-12">Rejected by Company</span>';
                                                        } elseif ($status == 'rejected_store') {
                                                            echo '<span class="badge bg-soft-dark font-size-12">Rejected by Store</span>';
                                                        } elseif ($status == 'completed') {
                                                            echo '<span class="badge bg-soft-success font-size-12">Completed</span>';
                                                        } elseif ($status == 'cancelled') {
                                                            echo '<span class="badge bg-soft-secondary font-size-12">Cancelled</span>';
                                                        } else {
                                                            echo '<span class="badge bg-soft-light font-size-12">Unknown</span>';
                                                        }
                                                        ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>
            <?php include 'footer.php' ?>

        </div>
    </div>




    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/create-dag.js"></script>
    <script src="ajax/js/common.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

    <script>
        $('#dagListTable').DataTable();
    </script>


</body>

</html>