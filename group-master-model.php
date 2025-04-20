<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Manage Groups</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#Id</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $GROUP = new GroupMaster(NULL);
                                foreach ($GROUP->all() as $key => $group) {
                                    $key++;
                                    ?>
                                    <tr class="select-group" data-id="<?php echo $group['id']; ?>"
                                        data-code="<?php echo htmlspecialchars($group['code']); ?>"
                                        data-name="<?php echo htmlspecialchars($group['name']); ?>"
                                        data-queue="<?php echo htmlspecialchars($group['queue']); ?>"
                                        data-active="<?php echo $group['is_active']; ?>">

                                        <td><?php echo $key; ?></td>
                                        <td><?php echo htmlspecialchars($group['code']); ?></td>
                                        <td><?php echo htmlspecialchars($group['name']); ?></td>
                                        <td>
                                            <?php if ($group['is_active'] == 1): ?>
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