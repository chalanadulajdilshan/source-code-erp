<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$DAG = new Dag(NULL);

// Get the last inserted package id
$lastId = $DAG->getLastID();
$dag_id = 'CD/00/' . ($lastId + 1);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Create DAG | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                            <a href="#" class="btn btn-success" id="new">
                                <i class="uil uil-plus me-1"></i> New
                            </a>

                            <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-info" id="print" style="display: none;">
                                    <i class="uil uil-print me-1"></i> Print
                                </a>

                            <?php endif; ?>

                            <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update" style="display: none;">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-dag">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

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
                                            <h5 class="font-size-16 mb-1"> Creat Dag</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below Creat
                                                Dag</p>
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
                                                <label class="form-label" for="ref_no">Ref No </label>
                                                <div class="input-group mb-3">
                                                    <input id="ref_no" name="ref_no" type="text"
                                                        value="<?php echo $dag_id; ?>" placeholder="Ref No"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#mainDagModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="department_id" class="form-label">Department <span
                                                        class="text-danger"></span></label>
                                                <select id="department_id" name="department_id" class="form-select"
                                                    required>

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
                                                <label for="customerCode" class="form-label">Customer Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_code" name="customer_code" type="text"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#customerModal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- hidden send to customer id to table -->
                                            <input type="hidden" id="customer_id" name="customer_id">
                                            <!-- hidden send to customer id to table -->

                                            <div class="col-md-5">
                                                <label for="customerName" class="form-label">Customer Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_name" name="customer_name" type="text"
                                                        class="form-control" placeholder="Enter Customer Name" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Dag Received Date</label>
                                                <div class="input-group">

                                                    <input type="text" class="form-control date-picker-date"
                                                        id="received_date" name="received_date"
                                                        placeholder="Select Recived Date"> <span
                                                        class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-2 hidden">
                                                <label for="name" class="form-label">Dag Delivery Date</label>
                                                <div class="input-group">

                                                    <input type="text" class="form-control date-picker-date"
                                                        id="delivery_date" name="delivery_date"
                                                        placeholder="Select Delivery Date"> <span
                                                        class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Customer Request Date</label>
                                                <div class="input-group">

                                                    <input type="text" class="form-control date-picker-date"
                                                        id="customer_request_date" name="customer_request_date"
                                                        placeholder="Select Request Date"> <span
                                                        class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>

                                            <hr class="mt-4">
                                            <div class="col-md-3">
                                                <label for="Type" class="form-label">Assaign Company required</label>
                                                <div class="input-group mb-3">
                                                    <select name="dag_company_id" id="dag_company_id"
                                                        class="text_purchase3 col-sm-9 form-control">
                                                        <option value="0">-- Select Company --</option>
                                                        <?php
                                                        $DAG_COMPANY = new DagCompany(null);
                                                        foreach ($DAG_COMPANY->getByStatusCompany(1) as $dag_company) {

                                                        ?>
                                                            <option value="<?php echo $dag_company['id'] ?>">
                                                                <?php echo $dag_company['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="company_issued_date" class="form-label">Company Issued Date</label>
                                                <div class="input-group" id="company_issued_date_group">
                                                    <input type="text" class="form-control date-picker"
                                                        id="company_issued_date" name="company_issued_date"
                                                        placeholder="Select Issued Date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Company Delivery Date</label>
                                                <div class="input-group" id="delivery_date">

                                                    <input type="text" class="form-control date-picker-date"
                                                        id="company_delivery_date" name="company_delivery_date"
                                                        placeholder="Select Delivery Date"> <span
                                                        class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="receipt_no" class="form-label">Receipt No</label>
                                                <div class="input-group mb-3">
                                                    <input id="receipt_no" name="receipt_no" type="text"
                                                        placeholder="Receipt No" class="form-control">
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <label for="dag_status" class="form-label">Dag Status</label>
                                                <div class="input-group mb-3">
                                                    <select name="dag_status" id="dag_status"
                                                        class="text_purchase3 col-sm-9 form-control">
                                                        <option value="pending">Pending Dag</option>
                                                        <option value="assigned">Assign Company</option>
                                                        <option value="received">Received Dag</option>
                                                        <option value="rejected_company">Rejected in Company</option>
                                                        <option value="rejected_store">Rejected in Store</option>
                                                    </select>
                                                </div>
                                            </div>



                                            <hr class="my-4">

                                            <h5 class="mb-3">Add Dag Items</h5>


                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label">Vehicle No</label>
                                                    <input type="text" id="vehicleNo" class="form-control"
                                                        placeholder="Vehicle No">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="beltDesign" class="form-label">Belt Design</label>
                                                    <div class="input-group">
                                                        <select id="beltDesign" name="belt_design" class="form-select">
                                                            <option value="">-- Select Belt Design --</option>
                                                            <?php
                                                            $BELT_MASTER = new BeltMaster(NULL);
                                                            foreach ($BELT_MASTER->getActiveBelt() as $belt_master) {
                                                            ?>
                                                                <option value="<?= $belt_master['id']; ?>">
                                                                    <?= $belt_master['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 hidden">
                                                    <label class="form-label">Casing Cost</label>
                                                    <input type="number" id="casingCost" class="form-control"
                                                        placeholder="Cost" oninput="calculateDagItemTotal()">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Barcode</label>
                                                    <input type="text" id="barcode" class="form-control"
                                                        placeholder="Barcode">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Qty</label>
                                                    <input type="number" id="quantity" class="form-control"
                                                        placeholder="Qty" oninput="calculateDagItemTotal()">
                                                </div>


                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-success w-100"
                                                        id="addDagItemBtn">Add</button>
                                                </div>
                                            </div>

                                            <!-- Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Vehicle No</th>
                                                            <th>Belt Design</th>
                                                            <th>Barcode</th>
                                                            <th>Qty</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dagItemsBody">
                                                        <tr id="noDagItemRow">
                                                            <td colspan="7" class="text-center text-muted">No items
                                                                added</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>


                                            <hr>

                                            <div class="col-md-5 mt-3">
                                                <label for="remark" class="form-label">Remarks validate to
                                                    update</label>
                                                <textarea id="remark" name="remark" class="form-control" rows="4"
                                                    placeholder="Enter any remarks or notes..."></textarea>
                                            </div>
                                            <div class="col-md-3"></div>

                                            <div class="col-md-4 hidden">
                                                <div class="  p-2 border rounded bg-light" style="max-width: 600px;">
                                                    <div class="row mb-2">
                                                        <div class="col-7">
                                                            <input type="text" class="form-control  " value="Sub Total"
                                                                disabled>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control" id="finalTotal"
                                                                value="0.00" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-7">
                                                            <input type="text" class="form-control  "
                                                                value="Discount %:" disabled>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control" id="discount"
                                                                value="0">
                                                        </div>
                                                    </div>



                                                    <div class="row mb-2">
                                                        <div class="col-7">
                                                            <input type="text" class="form-control   fw-bold"
                                                                value="Grand Total:" disabled>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control  fw-bold"
                                                                id="grandTotal" value="0.00" disabled>
                                                        </div>
                                                    </div>
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

    <div class="modal fade" id="mainDagModel" tabindex="-1" role="dialog" aria-labelledby="dagModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dagModalLabel">Select DAG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table id="maindagTable" class="table table-bordered table-hover dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ref No</th>
                                <th>Department</th>
                                <th>Customer</th>
                                <th>Received Date</th>
                                <th>Delivery Date</th>
                                <th>Customer Request</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $DAG = new DAG(null);
                            foreach ($DAG->all() as $key => $dag) {
                                $key++;
                                $DEPARTMENT = new DepartmentMaster($dag['department_id']);
                                $CUSTOMER = new CustomerMaster($dag['customer_id']);
                                $DAG_COMPANY = new DagCompany($dag['dag_company_id']); // adjust if class name is different

                            ?>

                                <tr class="select-dag" data-id="<?= $dag['id'] ?>"
                                    data-ref_no="<?= htmlspecialchars($dag['ref_no']) ?>"
                                    data-department_id="<?= $dag['department_id'] ?>"
                                    data-customer_id="<?= $dag['customer_id'] ?>"
                                    data-customer_code="<?= $CUSTOMER->code ?>" data-customer_name="<?= $CUSTOMER->name ?>"
                                    data-received_date="<?= $dag['received_date'] ?>"
                                    data-delivery_date="<?= $dag['delivery_date'] ?>"
                                    data-customer_request_date="<?= $dag['customer_request_date'] ?>"
                                    data-dag_company_id="<?= $dag['dag_company_id'] ?>"
                                    data-company_issued_date="<?= $dag['company_issued_date'] ?>"
                                    data-company_delivery_date="<?= $dag['company_delivery_date'] ?>"
                                    data-receipt_no="<?= $dag['receipt_no'] ?>"
                                    data-remark="<?= htmlspecialchars($dag['remark']) ?>"
                                    data-status="<?= $dag['status'] ?>">


                                    <td><?= $key ?></td>
                                    <td><?= htmlspecialchars($dag['ref_no']) ?></td>
                                    <td><?= htmlspecialchars($DEPARTMENT->name) ?></td>
                                    <td><?= htmlspecialchars($CUSTOMER->name) ?></td>
                                    <td><?= htmlspecialchars($dag['received_date']) ?></td>
                                    <td><?= htmlspecialchars($dag['delivery_date']) ?></td>
                                    <td><?= htmlspecialchars($dag['customer_request_date']) ?></td>


                                    <?php
                                    $status = htmlspecialchars($dag['status']);
                                    $label = '';
                                    $bgClass = '';

                                    switch ($status) {
                                        case 'pending':
                                            $label = 'Pending';
                                            $bgClass = 'bg-soft-warning'; // yellow
                                            break;
                                        case 'assigned':
                                            $label = 'Assigned';
                                            $bgClass = 'bg-soft-primary'; // blue
                                            break;
                                        case 'received':
                                            $label = 'Received';
                                            $bgClass = 'bg-soft-success'; // green
                                            break;
                                        case 'rejected_company':
                                            $label = 'Rejected by Company';
                                            $bgClass = 'bg-soft-danger'; // red
                                            break;
                                        case 'rejected_store':
                                            $label = 'Rejected by Store';
                                            $bgClass = 'bg-soft-danger'; // red
                                            break;
                                        default:
                                            $label = ucfirst($status); // fallback
                                            $bgClass = 'bg-soft-secondary'; // gray
                                            break;
                                    }
                                    ?>


                                    <td>
                                        <span class="badge <?php echo $bgClass; ?> font-size-12">
                                            <?php echo $label; ?>
                                        </span>

                                    </td>



                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
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



</body>

</html>