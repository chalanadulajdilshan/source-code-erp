<div id="invoiceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Search Bar -->
                        <div class="input-group mb-2">
                            <input type="text" id="invoiceSearch" class="form-control" placeholder="Search invoice no / customer / department">
                            <button class="btn btn-primary" id="searchBtn">Search</button>
                        </div>

                        <!-- Invoice Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="invoiceTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Department</th>
                                        <th>Customer</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>