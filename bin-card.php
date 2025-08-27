<!doctype html>
<?php
include 'class/include.php';
include './auth.php';
?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Bin Card | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

</head>

<body data-layout="horizontal" data-topbar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'navigation.php' ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ==============================================================     -->

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">

                            <!-- <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Bin Card
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Sup. Card
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Po. Card
                                </a> -->
                        <?php endif; ?>


                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Bin Card </li>
                            </ol>
                        </div>
                    </div>

                    <!--- Hidden Values -->
                    <input type="hidden" id="item_id">
                    <input type="hidden" id="availableQty">

                    <!-- end page title -->

                    <!-- Form Section -->
                    <form class="card p-3 mb-4">
                        <div class="row g-3">
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
                                        <h5 class="font-size-16 mb-1">Bin Card </h5>
                                        <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="itemCode" class="form-label">Item Code</label>
                                        <div class="input-group mb-3">
                                            <input id="itemCode" name="itemCode" type="text" placeholder="Item Code"
                                                class="form-control" readonly>

                                            <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                data-bs-target="#main_item_master">
                                                <i class="uil uil-search me-1"></i>
                                            </button>

                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="showAll">
                                            <label class="form-check-label fw-bold" for="showAll">
                                                Show All Items
                                            </label>
                                        </div>
                                    </div>
                                    <!-- hidden item id -->
                                    <input type="hidden" id="item_id" name="item_id">
                                    <div class="col-md-4">
                                        <label for="Description" class="form-label">Item Name</label>
                                        <div class="input-group mb-3">
                                            <input id="itemName" name="itemName" type="text" class="form-control"
                                                placeholder="item name" readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <label for="Department" class="form-label">Department</label>
                                        <div class="input-group mb-3">
                                            <select id="department_id" name="department_id" class="form-select">
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
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="date">Select Days</label>
                                        <select class="form-select">
                                            <?php
                                            $DEFAULT_DATA = new DefaultData();
                                            foreach ($DEFAULT_DATA->Years() as $key => $year) {
                                            ?>
                                                <option value="<?php echo $year ?>"><?php echo $year ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="selectDays">Select Days</label>
                                        <select class="form-select" id="selectDays">
                                            <option>-- Select Days --</option>
                                            <?php
                                            $DEFAULT_DATA = new DefaultData();
                                            foreach ($DEFAULT_DATA->Days() as $key => $days) {
                                            ?>
                                                <option value="<?php echo $key ?>"><?php echo $days ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="dateFrom">From</label>
                                        <input id="dateFrom" name="dateFrom" type="text"
                                            class="form-control date-picker" placeholder="Select date ">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="dateTo">To</label>
                                        <input id="dateTo" name="dateTo" type="text" class="form-control date-picker"
                                            placeholder="Select date ">
                                    </div>

                                </div>
                            </div>
                            <div id="stock-info" class="col-md-2 d-flex flex-column mt-5"></div>
                    </form>

                </div>
                <!-- Monthly Consumption -->
                <div class="card p-3 mb-4 hidden">
                    <h5>Monthly Consumption</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Aug</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dec</th>
                                            <th>Avg for 12 Months</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="13" class="text-center text-muted">No items added</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Show Transactions -->
                <div class="card p-3 mb-4">
                    <div class="mb-2">
                        <input type="checkbox" id="showTransactions">
                        <label for="showTransactions" class="ms-1 fw-bold">Show Transactions</label>
                    </div>


                    <!-- Transaction Table -->
                    <div class="table-responsive mb-4" id="transactionTable" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Adj Type</th>
                                    <th>Remark</th>
                                    <th>Direction</th>
                                    <th>Stk In</th>
                                    <th>Stk Out</th>
                                    <th>Stk Bal</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" id="transactionTableBody">
                                <tr>
                                    <td colspan="6" class="text-muted">No items added</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Stock In Hand Table -->
                <div class="card p-3">
                    <h5>Department Wise Stock In Hand</h5>
                    <table class="table table-bordered table-sm text-center mb-0" id="departmentStockTable">
                        <thead class="table-light">
                            <tr>
                                <th>Department</th>
                                <th>Stock</th>
                                <th>Pending Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No items added</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <?php include 'footer.php' ?>

        </div>
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="ajax/js/bin-card.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Initialize the datepicker
            $(".date-picker").datepicker({
                dateFormat: 'yy-mm-dd' // or 'dd-mm-yy' as per your format
            });
            var today = $.datepicker.formatDate('yy-mm-dd', new Date());
            $(".date-picker").val(today);
        });
    </script>
</body>

</html>