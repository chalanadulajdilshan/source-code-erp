<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last purchase return if 
$lastId = $DOCUMENT_TRACKING->pr_id;
$purchase_return_id = $COMPANY_PROFILE_DETAILS->company_code . '/PR/00/0' . $lastId + 1;
?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Purchase Return | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
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
                            <a href="#" class="btn btn-success" id="new" onclick="newPurchaseReturn()">
                                <i class="uil uil-plus me-1"></i> New
                            </a>

                            <?php if ($PERMISSIONS['add_page']): ?>
                            <a href="#" class="btn btn-primary" id="makeReturnBtn">
                                <i class="uil uil-save me-1"></i> Save
                            </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['edit_page']): ?>
                            <a href="#" class="btn btn-warning" id="update">
                                <i class="uil uil-edit me-1"></i> Update
                            </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                            <a href="#" class="btn btn-danger delete-category">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Purchase Return </li>
                            </ol>
                        </div>
                    </div>
                    <!--- Hidden Values -->
                    <input type="hidden" id="item_id">
                    <input type="hidden" id="availableQty">

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
                                            <h5 class="font-size-16 mb-1">Enter Purchase Return </h5>
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
                                                <label for="ReferenceNo" class="form-label">Reference No</label>
                                                <div class="input-group mb-3">
                                                    <input id="reference_no" name="reference_no" type="text"
                                                        placeholder="Reference No" class="form-control"
                                                        value="<?php echo $purchase_return_id ?>" readonly>

                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#customerModal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="date">Date</label>
                                                <input id="date" name="date" type="text"
                                                    class="form-control date-picker">
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

                                            <div class="col-md-4">
                                                <label for="supplier" class="form-label">Supplier</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend" style="flex: 0 0 auto;">
                                                        <input id="customer_id" name="customer_id" type="text"
                                                            class="form-control ms-10 me-2" style="width: 120px;"
                                                            placeholder="Supply Code" readonly>
                                                    </div>
                                                    <input id="customer_name" name="customer_name" type="text"
                                                        class="form-control" placeholder="Supply Name" readonly> 
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="ARN_No" class="form-label">ARN No</label>
                                                <div class="input-group mb-3">
                                                    <input id="arn_no" name="arn_no" type="text" placeholder="Select ARN No"
                                                        class="form-control" disabled>

                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#po_number_modal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>

                                                </div>

                                            </div>

                                            <div class="col-md-9">
                                                <label for="Reason" class="form-label">Reason</label>
                                                <div class="input-group mb-3">
                                                    <input id="reason" name="reason" type="text" class="form-control"
                                                        placeholder="Enter the valid Reason..">
                                                </div>
                                            </div>


                                            <hr class="my-4">
                                            <h5 class="mb-3" id="itemTableHeader">ARN Return Notes</h5>

                                            <!-- Table -->
                                            <div class="table-responsive" id="itemTableContainer">

                                                <table id="purchase_return_table" class="table table-bordered dt-responsive nowrap"
                                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>##</th>
                                                            <th>Return Ref No</th>
                                                            <th>Return Date</th>
                                                            <th>Department</th>
                                                            <th>Supplier Code & Name</th>
                                                            <th>ARN No</th>
                                                            <th>Total Amount</th>
                                                            <th>Return Reason</th>
                                                            <th>Created At</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php
                                                        $ARN_RETURN = new PurchaseReturn(null);
                                                        foreach ($ARN_RETURN->all() as $key => $return) {
                                                            $key++;
                                                            $DEPARTMENT = new DepartmentMaster($return['department_id']);
                                                            $SUPPLIER = new CustomerMaster($return['supplier_id']);
                                                            $ARN = new ArnMaster($return['arn_id']);
                                                        ?>
                                                            <tr class="view-return-items" data-id="<?= $return['id']; ?>">
                                                                <td><?= $key; ?></td>
                                                                <td><?= htmlspecialchars($return['ref_no']); ?></td>
                                                                <td><?= htmlspecialchars($return['return_date']); ?></td>
                                                                <td><?= htmlspecialchars($DEPARTMENT->name); ?></td>
                                                                <td><?= htmlspecialchars($SUPPLIER->code . ' - ' . $SUPPLIER->name); ?></td>
                                                                <td><?= htmlspecialchars($ARN->arn_no); ?></td>
                                                                <td><?= htmlspecialchars(number_format($return['total_amount'], 2)); ?></td>
                                                                <td><?= htmlspecialchars($return['return_reason']); ?></td>
                                                                <td><?= htmlspecialchars($return['created_at']); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

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











    <!-- model open here -->
    <div class="modal fade bs-example-modal-xl" id="po_number_modal" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Arrival Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="arnTable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ARN No</th>
                                        <th>Order Date</th>
                                        <th>Supplier Code and Name</th>
                                        <th>PI No</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Total ARN Value</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $ARN_ORDER = new ArnMaster(null);
                                    foreach ($ARN_ORDER->all() as $key => $arn_order) {
                                        $CUSTOMER_MASTER = new CustomerMaster($arn_order['supplier_id']);
                                        $DEPARTMENT_MASTER = new DepartmentMaster($arn_order['department']);
                                        $key++;
                                    ?>
                                        <tr class="select-arn" onclick="selectArnOrder(<?= $arn_order['id']; ?>, '<?= $arn_order['arn_no']; ?>' )"
                                            style="cursor: pointer;"
                                            data-id="<?= $arn_order['id']; ?>"
                                            data-arn_no="<?= htmlspecialchars($arn_order['arn_no']); ?>"
                                            data-order_date="<?= htmlspecialchars($arn_order['po_date']); ?>"
                                            data-supplier_id="<?= htmlspecialchars($arn_order['supplier_id']); ?>"
                                            data-supplier_code="<?= htmlspecialchars($CUSTOMER_MASTER->code); ?>"
                                            data-supplier_name="<?= htmlspecialchars($CUSTOMER_MASTER->name); ?>"
                                            data-pi_no="<?= htmlspecialchars($arn_order['pi_no']); ?>"
                                            data-lc_tt_no="<?= htmlspecialchars($arn_order['lc_tt_no']); ?>"
                                            data-department="<?= htmlspecialchars($arn_order['department']); ?>"
                                            data-total_arn_value="<?= htmlspecialchars($arn_order['total_arn_value']); ?>"
                                            data-arn_status="<?= htmlspecialchars($arn_order['arn_status']); ?>">

                                            <td><?= $key; ?></td>
                                            <td><?= htmlspecialchars($arn_order['arn_no']); ?></td>
                                            <td><?= htmlspecialchars($arn_order['po_date']); ?></td>
                                            <td><?= htmlspecialchars($CUSTOMER_MASTER->code . ' - ' . $CUSTOMER_MASTER->name); ?></td>
                                            <td><?= htmlspecialchars($arn_order['pi_no']); ?></td>
                                            <td><?= htmlspecialchars($DEPARTMENT_MASTER->name); ?></td>

                                            <td>
                                                <?php if ($arn_order['arn_status'] == 1): ?>
                                                    <span class="badge bg-soft-success font-size-12">Approved</span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger font-size-12">Not Approved</span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?= htmlspecialchars($arn_order['total_arn_value']); ?></td>
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



<!-- Return Items Modal -->
<div class="modal fade" id="returnItemsModal" tabindex="-1" role="dialog" aria-labelledby="returnItemsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Return Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="returnItemsContainer">
      </div>
    </div>
  </div>
</div>








    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="ajax/js/customer-master.js"></script>
    <script src="ajax/js/purchase-order-return.js"></script>

    <!-- /////////////////////////// -->

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $('#arnTable').DataTable();
        $('#purchase_return_table').DataTable();
        $(function() {
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