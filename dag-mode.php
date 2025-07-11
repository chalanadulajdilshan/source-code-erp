<div class="modal fade" id="dagModel" tabindex="-1" role="dialog" aria-labelledby="dagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dagModalLabel">Select DAG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table id="dagTable" class="table table-bordered table-hover dt-responsive nowrap w-100">
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
                        foreach ($DAG->printStatus(0) as $key => $dag) {
                            $key++;
                            $DEPARTMENT = new DepartmentMaster($dag['department_id']);
                            $CUSTOMER = new CustomerMaster($dag['customer_id']);
                            $DAG_COMPANY = new DagCompany($dag['dag_company_id']); // adjust if class name is different
                        
                            ?>

                            <tr class="select-dag" data-id="<?= $dag['id'] ?>"
                                data-ref_no="<?= htmlspecialchars($dag['ref_no']) ?>"
                                data-department_id="<?= $dag['department_id'] ?>"
                                data-customer_id="<?= $dag['customer_id'] ?>" data-customer_code="<?= $CUSTOMER->code ?>"
                                data-customer_name="<?= $CUSTOMER->name ?>"
                                data-received_date="<?= $dag['received_date'] ?>"
                                data-delivery_date="<?= $dag['delivery_date'] ?>"
                                data-customer_request_date="<?= $dag['customer_request_date'] ?>"
                                data-dag_company_id="<?= $dag['dag_company_id'] ?>"
                                data-company_issued_date="<?= $dag['company_issued_date'] ?>"
                                data-company_delivery_date="<?= $dag['company_delivery_date'] ?>"
                                data-receipt_no="<?= $dag['receipt_no'] ?>"
                                data-remark="<?= htmlspecialchars($dag['remark']) ?>" data-status="<?= $dag['status'] ?>">


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