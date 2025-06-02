<!doctype html>
<?php
include 'class/include.php';

$CREATE_DAG = new CreateDag(NULL);

// Get the last inserted package id
$lastId = $CREATE_DAG->getLastID();
$dag_id = 'CD-00' . $lastId + 1;

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Horizontal Layout | Minible - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description" />
    <meta content="Themesbrand" name="#" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/libs/@chenfengyuan/datepicker/datepicker.min.css">
    <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="assets/css/preloader.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">



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
                            <a href="#" class="btn btn-success" id="new">
                                <i class="uil uil-plus me-1"></i> New
                            </a>
                            <a href="#" class="btn btn-primary" id="create">
                                <i class="uil uil-save me-1"></i> Save
                            </a>
                            <a href="#" class="btn btn-info" id="print">
                                <i class="uil uil-print me-1"></i> Print
                            </a>
                            <a href="#" class="btn btn-warning" id="update">
                                <i class="uil uil-edit me-1"></i> Update
                            </a>
                            <a href="#" class="btn btn-danger delete-dag">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">CREATE DAG</li>
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
                                            <h5 class="font-size-16 mb-1">CREATE DAG</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">

                                    <form id="form-data" autocomplete="off">
                                        <div class="row">

                                            <div class="col-md-2">
                                                    <label class="form-label" for="code">Invoice No </label>
                                                    <div class="input-group mb-3">
                                                        <input id="code" name="code" type="text" value="<?php echo $dag_id; ?>"
                                                            placeholder="Ref No" class="form-control" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#dagModel">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                            </div>

                                            <div class="col-md-3">
                                                    <label for="department_id" class="form-label">Department <span
                                                            class="text-danger"></span></label>
                                                    <select id="department_id" name="department_id" class="form-select"
                                                        required>
                                                        <option value=""> --Select Department --</option>
                                                        <?php
                                                        $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                        foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $department_master) {
                                                            ?>
                                                            <option value="<?php echo $department_master['id']; ?>">
                                                                <?php echo $department_master['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                            </div>

                                            <div class="col-md-2">
                                                    <label for="name" class="form-label">Date</label>
                                                    <div class="input-group" id="datepicker2">

                                                        <input type="text" class="form-control date-picker" id="date"
                                                            name="date"> <span class="input-group-text"><i
                                                                class="mdi mdi-calendar"></i></span>
                                                    </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="customerCode" class="form-label">Customer Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_id" name="customer_id" type="text"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#customerModal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="customerName" class="form-label">Customer Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_name" name="customer_name" type="text"
                                                        class="form-control" placeholder="Enter Customer Name" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Casing_Cost" class="form-label">Casing Cost</label>
                                                    <div class="input-group mb-3">
                                                        <input id="casing_cost" name="casing_cost" type="text"
                                                            placeholder="Casing Cost" class="form-control">
                                                    </div>

                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Type" class="form-label">Type</label>
                                                    <div class="input-group mb-3">
                                                        <select name="type" id="type" class="text_purchase3 col-sm-9 form-control"> 
                                                            <option value="">-- Select Type --</option>
                                                            <option value="CANVAS">CANVAS</option>
                                                            <option value="RADIAL">RADIAL</option>
                                                        </select>
                                                    </div>
                                            </div>                                         

                                            <div class="col-md-2">
                                                    <label for="Size" class="form-label">Size</label>
                                                    <div class="input-group mb-3">
                                                        <select id="size" name="size" class="form-select">
                                                            <option value="">-- Select Size --</option>
                                                            <?php
                                                            $ITEM_MASTER = new ItemMaster(NULL);
                                                            foreach ($ITEM_MASTER->all() as $item_master) {
                                                                ?>
                                                                <option value="<?php echo $item_master['id']; ?>">
                                                                    <?php echo $item_master['size']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Make" class="form-label">Make</label>
                                                    <div class="input-group mb-3">
                                                        <select name="make" id="make" class="text_purchase3 col-sm-9 form-control"> 
                                                            <option value="">-- Select Make --</option>
                                                            <option value="Arpico">Arpico</option>
                                                            <option value="ceat">Ceat</option>
                                                        </select>
                                                    </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Belt_Design" class="form-label">Belt Design</label>
                                                    <div class="input-group mb-3">
                                                        <select id="belt_design" name="belt_design" class="form-select">
                                                            <option value="">-- Select Belt Design --</option>
                                                            <?php
                                                            $BELT_MASTER = new BeltMaster(NULL);
                                                            foreach ($BELT_MASTER->getActiveBelt() as $belt_master) {
                                                                ?>
                                                                <option value="<?php echo $belt_master['id']; ?>">
                                                                    <?php echo $belt_master['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Job_No" class="form-label">Job No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="job_no" name="job_no" type="text"
                                                            placeholder="Job No" class="form-control">
                                                    </div>

                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Serial_No" class="form-label">Serial No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="serial_no" name="serial_no" type="text"
                                                            placeholder="Serial No" class="form-control">
                                                    </div>

                                            </div>

                                            <div class="col-md-2">
                                                    <label for="Warranty" class="form-label">Warranty</label>
                                                    <div class="input-group mb-3">
                                                        <select id="warranty" name="warranty" class="form-select">
                                                            <option value="">-- Select Belt Warranty --</option>
                                                            <option value="1">1 Year</option>
                                                            <option value="2">2 Year</option>
                                                            <option value="3">3 Year</option>
                                                            <option value="4">4 Year</option>
                                                            <option value="5">5 Year</option>
                                                        </select>
                                                    </div>
                                            </div>

                                            <div class="col-12 mt-3">
                                                    <label for="remark" class="form-label">Remarks</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes..."></textarea>
                                            </div>
                                            

                                            
                                        </div>
                                        <input type="hidden" id="id" name="id" value="0">
                                        
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>  
            <?php include 'footer.php' ?>

        </div> 
    </div>
    
  
<!-- model open here -->
<div class="modal fade bs-example-modal-xl" id="dagModel" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Dag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
  

                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Department </th>
                                    <th>Customer Name</th>
                                    <th>Casing Cost</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Make</th>
                                    <th>Belt Design</th>
                                    <th>Job No</th>
                                    <th>Serial No</th>
                                    <th>Warranty</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                                $DAG = new CreateDag(null);
                                foreach ($DAG->all() as $key => $dag) {
                                    $key++;
                                    $DEPARTMENT_MASTER = new DepartmentMaster($dag['department_id']);
                                    $ITEM_MASTER = new ItemMaster($dag['size']);
                                    $BELT_MASTER = new BeltMaster($dag['belt_design']);
                                    $CUSTOMER = new CustomerMaster($dag['customer_id']);
                                    ?>
                                    <tr class="select-dag" data-id="<?php echo $dag['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($dag['code']); ?>"
                                            data-department_id="<?php echo htmlspecialchars($dag['department_id']); ?>"
                                            data-date="<?php echo htmlspecialchars($dag['date']); ?>"
                                            data-customer_id="<?php echo htmlspecialchars($dag['customer_id']); ?>"
                                            data-customer_name="<?php echo htmlspecialchars($dag['customer_id']); ?>"
                                            data-casing_cost="<?php echo htmlspecialchars($dag['casing_cost']); ?>"
                                            data-type="<?php echo htmlspecialchars($dag['type']); ?>"
                                            data-size="<?php echo htmlspecialchars($dag['size']); ?>"
                                            data-make="<?php echo htmlspecialchars($dag['make']); ?>"
                                            data-belt_design="<?php echo htmlspecialchars($dag['belt_design']); ?>"
                                            data-job_no="<?php echo htmlspecialchars($dag['job_no']); ?>"
                                            data-serial_no="<?php echo htmlspecialchars($dag['serial_no']); ?>"
                                            data-warranty="<?php echo htmlspecialchars($dag['warranty']); ?>"
                                            data-remark="<?php echo htmlspecialchars($dag['remark']); ?>"
                                    >

                                    <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($dag['code']); ?></td>
                                            <td><?php echo htmlspecialchars($DEPARTMENT_MASTER->name); ?></td>
                                            <td><?php echo htmlspecialchars($CUSTOMER->name); ?></td>
                                            <td><?php echo htmlspecialchars($dag['casing_cost']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['type']); ?></td>
                                            <td><?php echo htmlspecialchars($ITEM_MASTER->size); ?></td>
                                            <td><?php echo htmlspecialchars($dag['make']); ?></td>
                                            <td><?php echo htmlspecialchars($BELT_MASTER->name); ?></td>
                                            <td><?php echo htmlspecialchars($dag['job_no']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['serial_no']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['warranty']); ?></td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- model close here -->

    <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <!-- /////////////////////////// -->
        <script src="ajax/js/create-dag.js"></script>
        <script src="ajax/js/common.js"></script>
 

        <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
        <script src="assets/js/jquery.preloader.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/@chenfengyuan/datepicker/datepicker.min.js"></script>

        <!-- Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <!-- Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>
        <!-- apexcharts -->

        <script src="assets/js/pages/dashboard.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script>
            $('#quotation_table').DataTable();
            $(function () {
                // Initialize the datepicker
                $(".date-picker").datepicker({
                    dateFormat: 'yy-mm-dd' // or 'dd-mm-yy' as per your format
                });

                // Set today's date as default value
                var today = $.datepicker.formatDate('yy-mm-dd', new Date());
                $(".date-picker").val(today);
            });
        </script>

</body>

</html>