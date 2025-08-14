<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$DEPARTMENT_MASTER = new DepartmentMaster($US->department_id)
    ?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Stock Transfer | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

    <?php include 'department-stock-model.php' ?>

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

                            <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-primary" id="print">
                                    <i class="uil uil-print  me-1"></i> Print
                                </a>
                            <?php endif; ?>

                            <a href="#" class="btn btn-warning" id="search">
                                <i class="uil uil-search me-1"></i> Search
                            </a>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Stock Transfer </li>
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
                                            <h5 class="font-size-16 mb-1">Stock Transfer </h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <form id="form-data">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="Department" class="form-label">From Department</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" value="<?php echo $DEPARTMENT_MASTER->name ?>"
                                                        disabled class="form-control">
                                                    <input id="department_id" name="department_id" type="hidden"
                                                        value="<?php echo $DEPARTMENT_MASTER->id ?>">
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="form-label" for="transfer_date">Date</label>
                                                <input id="transfer_date" name="transfer_date" type="text"
                                                    class="form-control date-picker">
                                            </div>

                                            <hr class="my-4">


                                            <div class="col-md-3">
                                                <label for="toDepartment" class="form-label">To
                                                    Department</label>
                                                <div class="input-group mb-3">
                                                    <select id="to_department_id" name="to_department_id"
                                                        class="form-select">
                                                        <?php
                                                        $DEPARTMENT_MASTER = new DepartmentMaster(NUll);
                                                        foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                            echo $US->type;
                                                            if ($US->type != 1) {

                                                                if ($departments['id'] != $US->department_id) {
                                                                    ?>
                                                                    <option value="<?php echo $departments['id'] ?>">
                                                                        <?php echo $US->department_id . ' - ' . $departments['name'] ?>
                                                                    </option>
                                                                <?php }
                                                            } else {
                                                                if ($departments['id'] != $US->department_id) {
                                                                    ?>
                                                                    <option value="<?php echo $departments['id'] ?>">
                                                                        <?php echo $departments['name'] ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="itemCode" class="form-label">Item Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="itemCode" name="itemCode" type="text"
                                                        placeholder="Item Code" class="form-control" readonly>

                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#department_stock">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>

                                                </div>
                                            </div>
                                            <input type="hidden" id="item_id" name="item_id">
                                            <div class="col-md-4">
                                                <label for="Description" class="form-label">Item Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="itemName" name="itemName" type="text"
                                                        class="form-control" placeholder="item name" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Quantity</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" id="itemQty" min="0" class="form-control"
                                                        placeholder="Qty">

                                                    <button class="btn btn-info ms-1" type="button" id="add_item">
                                                        <i class="uil uil-plus"></i>Add
                                                    </button>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="table-responsive ">
                                                <table class="table table-bordered" id="itemTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>Qty</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="show_table">
                                                        <tr id="noItemRow">
                                                            <td colspan="8" class="text-center text-muted">No items
                                                                added</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>


                                            <hr>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="  p-2 border rounded bg-light"
                                                        style="max-width: 500px;">
                                                        <div class="row mb-2">
                                                            <div class="col-5">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value=" Available Quantity " disabled>
                                                            </div>
                                                            <div class="col-7">
                                                                <input type="text"
                                                                    class="form-control text-danger fw-bold"
                                                                    id="available_qty" value="0" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    <!-- /////////////////////////// -->

    <script src="ajax/js/common.js"></script>
    <script src="ajax/js/stock-transfer.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

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