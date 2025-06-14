<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Price Control| <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description" />
    <meta content="Themesbrand" name="author" />
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
                            Your Item Pricess Summary
                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Price Control </li>
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
                                            <h5 class="font-size-16 mb-1">Manage Price Control </h5>
                                            <p class="text-muted text-truncate mb-0">Details about Price Control</p>
                                        </div>

                                    </div>

                                </div>

                                <div class="p-4">
                                    <form id="form-data">
                                        <div class="row">

                                            <div class="col-md-2">
                                                <label for="username" class="form-label">Search By Item Code or Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="item_code" name="item_code" type="text"
                                                        class="form-control" placeholder="Search by item code or Name">

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="Department" class="form-label">Select Brand</label>
                                                <div class="input-group mb-3">
                                                    <select id="brand_id" name="brand_id" class="form-select">
                                                        <option value="0">-- All Brands -- </option>
                                                        <?php
                                                        $BRAND = new Brand(NULL);
                                                        foreach ($BRAND->activeBrands() as $brand) {
                                                            echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="Department" class="form-label">Select Category</label>
                                                <div class="input-group mb-3">
                                                    <select id="category_id" name="category_id" class="form-select">
                                                        <option value="0">-- All Categories -- </option>

                                                        <?php
                                                        $CATEGORY_MASTER = new CategoryMaster(NULL);
                                                        foreach ($CATEGORY_MASTER->getActiveCategory() as $category) {
                                                            echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="Department" class="form-label">Select Group</label>
                                                <div class="input-group mb-3">
                                                    <select id="group_id" name="group_id" class="form-select">

                                                        <option value="0">-- All Groups -- </option>
                                                        <?php
                                                        $GROUP_MASTER = new GroupMaster(NULL);
                                                        foreach ($GROUP_MASTER->getActiveGroups() as $group) {
                                                            echo "<option value='{$group['id']}'>{$group['name']}</option>";
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="department_id" class="form-label">Department</label>
                                                <div class="input-group mb-3">
                                                    <select id="department_id" name="department_id" class="form-select">
                                                        <option value="0"> -- All Departments -- </option>
                                                        <?php
                                                        $DEPARTMENT_MASTER = new DepartmentMaster(NUll);
                                                        foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                            if ($US->type != 1) {
                                                                if ($departments['id'] = $US->department_id) {
                                                                    ?>
                                                                    <option value="<?php echo $departments['id'] ?>">
                                                                        <?php echo $departments['name'] ?>
                                                                    </option>
                                                                <?php }
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $departments['id'] ?>">
                                                                    <?php echo $departments['name'] ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        } ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-4">
                                                <button id="view_price_report" class="btn btn-primary delete-item">
                                                    <i class="uil uil-file   me-1"></i> View
                                                </button>
                                            </div>


                                            <hr class="my-4">

                                            <!-- Table -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="priceControl">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <td>#Id</td>
                                                            <th>Item Code and Name</th>
                                                            <th>Description</th>
                                                            <th>All Qty</th>
                                                            <th>Group</th>
                                                            <th>Brand</th>
                                                            <th>Category</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted">No items
                                                                added</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>

            <?php include 'footer.php' ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="ajax/js/report.js"></script>

    <!-- /////////////////////////// -->

    <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
    <script src="assets/js/jquery.preloader.min.js"></script>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>

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