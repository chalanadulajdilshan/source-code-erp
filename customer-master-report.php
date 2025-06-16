<!doctype html>
<?php
include 'class/include.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Customer Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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

                                <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                                <?php endif; ?>

                                <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                                <?php endif; ?>

                                <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-customer-report">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                                <?php endif; ?>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Customer Master </li>
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
                                                <h5 class="font-size-16 mb-1">Manage Customer Master </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                   Manage Customer Master </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <form id="form-data" autocomplete="off">
                                                <div class="row">
                                                    <div class="row align-items-end">
                                                        
                                                        <div class="col-md-2">
                                                            <label for="customer" class="form-label">Customer</label>
                                                                <select id="customer" name="customer" class="form-select">
                                                                        <option value="">-- Select Customer--</option>

                                                                </select>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn-success w-100"
                                                                id="viewBtn">View</button>
                                                        </div>   
                                                    </div>

                                                    <hr class="my-4">
                                                    <div class="row align-items-end">
                                                        
                                                        <div class="col-md-12"></div>

                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger w-100">Excel</button>
                                                            </div>
                                                            
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger w-100">Print</button>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <h5 class="mb-3">Details</h5>

                                                        <!-- Table -->
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-bordered" id="Table">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>#</th>
                                                                        <th>#</th>
                                                                        <th>#</th>
                                                                        <th>#</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody id="Body">
                                                                    <tr id="noItemRow">
                                                                        <td colspan="5" class="text-center text-muted">No items
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