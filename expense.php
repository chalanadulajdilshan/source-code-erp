<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$EXPENSE = new Expense();

// Get the last inserted expense id
$lastId = $EXPENSE->getLastID();
$expense_id = 'EXP/0' . ($lastId + 1);
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Expense Entry | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <?php include 'main-css.php' ?>
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

                            <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-expense" id="delete">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">EXPENSE ENTRY</li>
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
                                            <h5 class="font-size-16 mb-1">Expense Entry</h5>
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
                                                <label class="form-label" for="code">Ref No</label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text" value="<?php echo $expense_id; ?>"
                                                        placeholder="Ref No" class="form-control" readonly>
                                                    <button class="btn btn-info" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#expenseModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="expense_type" class="form-label">Expense Type Name <span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <select id="expense_type" name="expense_type" class="form-select" required>
                                                        <option value="">Select Expense Type</option>
                                                        <?php
                                                        $expenseType = new Expenses();
                                                        $expenseTypes = $expenseType->all();
                                                        foreach ($expenseTypes as $type) {
                                                            echo "<option value='{$type['id']}'>" . htmlspecialchars($type['name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="expense_date">Date <span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control" id="expense_date" name="expense_date"
                                                        value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" step="0.01" class="form-control" id="amount"
                                                        name="amount" placeholder="Enter Amount" required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="remark" class="form-label">Remark</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="remark" name="remark"
                                                        placeholder="Enter Remark">
                                                </div>
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

    <!-- Modal for Expense Records -->
    <div class="modal fade bs-example-modal-xl" id="expenseModel" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Expense Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ref No</th>
                                        <th>Expense Type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $EXPENSE_LIST = new Expense(null);
                                    foreach ($EXPENSE_LIST->all() as $key => $expense) {
                                        $key++;
                                        // Get expense type name
                                        $expenseTypeObj = new Expenses($expense['expense_type_id']);
                                        $expenseTypeName = $expenseTypeObj->name ?? 'Unknown';
                                    ?>
                                        <tr class="select-expense" data-id="<?php echo $expense['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($expense['code']); ?>"
                                            data-expense_type="<?php echo htmlspecialchars($expense['expense_type_id']); ?>"
                                            data-expense_date="<?php echo htmlspecialchars($expense['expense_date']); ?>"
                                            data-amount="<?php echo htmlspecialchars($expense['amount']); ?>"
                                            data-remark="<?php echo htmlspecialchars($expense['remark']); ?>">
                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($expense['code']); ?></td>
                                            <td><?php echo htmlspecialchars($expenseTypeName); ?></td>
                                            <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                                            <td><?php echo number_format($expense['amount'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($expense['remark']); ?></td>
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
    <!-- Modal close -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/expense.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>