<!doctype html>
<?php
include 'class/include.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Item Master Report | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">

    </head>

    <body data-layout="horizontal" data-topbar="colored">

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
                                <a href="#" class="btn btn-primary" id="view">
                                    <i class="uil uil-save me-1"></i> View Report
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Item Master Report  </li>
                                </ol>
                            </div>
                        </div>
                        <!--- Hidden Values -->


                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <div
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        01
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Manage Item Master Report </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                   Manage Item Master Report </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <form id="form-data" autocomplete="off">
                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <label for="item" class="form-label">Select Item</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="item" name="item" class="form-select">
                                                                <option value="">-- Select Item--</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="brand" class="form-label">Brand</label>
                                                        <div class="input-group mb-3">
                                                            <select id="brand" name="brand" class="form-select">
                                                                <option value="">-- All Brands --</option>
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
                                                        <label for="department" class="form-label">Department</label>
                                                        <div class="input-group mb-3">
                                                            <select id="department_id" name="department_id" class="form-select">
                                                                <?php
                                                                $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                                foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                                    ?>
                                                                    <option value="<?php echo $departments['id'] ?>">
                                                                        <?php echo $departments['name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="stock_type" class="form-label">Stock Type</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="stock_type" name="stock_type" class="form-select">
                                                                <option value="">-- Select Stock Type--</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="stock_item" class="form-label">Stock Item</label>
                                                        <div class="input-group mb-3">
                                                            <input id="stock_item" name="stock_item" type="text"
                                                                placeholder="Stock Item" class="form-control" readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#stockItemModal">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

      
                                                </div>
                                            </form>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <!-- /////////////////////////// -->
        <script src="ajax/js/quotation.js"></script>
        <script src="ajax/js/common.js"></script>
 

        <!-- include main js  -->
        <?php include 'main-js.php' ?>

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