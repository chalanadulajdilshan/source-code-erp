<!-- Special User Permission Modal -->
<div id="permissionModal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="PermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="PermissionModalLabel">Manage Special User Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="newPermissionName" class="form-control" placeholder="Enter Permission Name">
                    </div>
                    <div class="col-md-3">
                        <select id="newPermissionStatus" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" id="add_special_permission">Add Permission</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="special_permissionTable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Permission Name</th>
                                    <th>Is Active</th>
                                </tr>
                            </thead>
                            <tbody id="special_permissionTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>