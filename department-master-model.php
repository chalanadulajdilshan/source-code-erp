<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Departments</h5>
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
                                    <th>#ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $DEPARTMENT_MASTER = new DepartmentMaster(null);
                                foreach ($DEPARTMENT_MASTER->all() as $key => $department) {
                                    $key++;
                                    ?>
                                    <tr class="select-department"
                                        data-id="<?php echo $department['id']; ?>"
                                        data-code="<?php echo htmlspecialchars($department['code']); ?>"
                                        data-name="<?php echo htmlspecialchars($department['name']); ?>"
                                        data-remark="<?php echo htmlspecialchars($department['remark']); ?>"
                                        data-status="<?php echo $department['is_active']; ?>">

                                        <td><?php echo $key; ?></td>
                                        <td><?php echo htmlspecialchars($department['code']); ?></td>
                                        <td><?php echo htmlspecialchars($department['name']); ?></td>
                                        <td><?php echo htmlspecialchars($department['remark']); ?></td>
                                        <td>
                                            <?php if ($department['is_active'] == 1): ?>
                                                <span class="badge bg-soft-success font-size-12">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                            <?php endif; ?>
                                        </td>
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
