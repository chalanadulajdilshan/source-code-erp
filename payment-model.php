<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Finalize Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Final Total -->
                <div class="mb-3">
                    <label>Final Total</label>
                    <input type="text" id="modalFinalTotal" class="form-control" value="100000" readonly>
                </div>

                <div class="mb-3">
                    <input type="hidden" id="modal_invoice_id" class="form-control" value="" readonly>
                </div>

                <!-- Dynamic Payment Rows -->
                <div id="paymentRows">
                    <!-- Payment row template will be appended here -->
                </div>

                <button type="button" class="btn btn-sm btn-primary mb-3" id="addPaymentRow">
                    + Add Payment Method
                </button>

                <!-- Totals -->
                <div class="mb-3">
                    <label>Total Paid</label>
                    <input type="text" id="totalPaid" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label>Balance</label>
                    <input type="text" id="balanceAmount" class="form-control" readonly>
                </div>

                <!-- Note -->
                <div class="mb-3">
                    <label>Note</label>
                    <input type="text" id="note" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <!-- Call savePayments() directly -->
                <button type="button" class="btn btn-success" id="savePayment">Save Payment</button>
            </div>
        </div>
    </div>
</div>



<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="ajax/js/invoice-payments.js"></script>
<!-- JS Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const finalTotal = document.getElementById('modalFinalTotal');
        const totalPaid = document.getElementById('totalPaid');
        const balanceAmount = document.getElementById('balanceAmount');
        const paymentRows = document.getElementById('paymentRows');
        const addPaymentRowBtn = document.getElementById('addPaymentRow');

        let rowId = 0;

        // Function to create new payment row
        function createPaymentRow() {
            rowId++;
            const row = document.createElement('div');
            row.classList.add('payment-row', 'border', 'p-3', 'mb-2');
            row.dataset.id = rowId;

            row.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <label>Payment Type</label>
                    <select name="paymentType[]" class="form-select paymentType" required>
                        <option value="">-- Select --</option>
                        <?php
                        $PAYMENT_TYPE = new PaymentType(NULL);
                        foreach ($PAYMENT_TYPE->all() as $type) {
                            echo "<option value='{$type['id']}'>{$type['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Amount</label>
                    <input type="number" name="amount[]" class="form-control paymentAmount" min="0" step="0.01" required>
                </div>
                <div class="col-md-4 chequeDetails d-none">
                    <label>Cheque No</label>
                    <input type="text" name="chequeNumber[]" class="form-control mb-2">
                    <label>Bank</label>
                    <input type="text" name="chequeBank[]" class="form-control mb-2">
                    <label>Date</label>
                    <input type="date" name="chequeDate[]" class="form-control">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                </div>
            </div>
        `;

            paymentRows.appendChild(row);

            // Add event listeners
            const paymentTypeSelect = row.querySelector('.paymentType');
            const chequeDetails = row.querySelector('.chequeDetails');
            const amountInput = row.querySelector('.paymentAmount');
            const removeBtn = row.querySelector('.removeRow');

            paymentTypeSelect.addEventListener('change', () => {
                if (paymentTypeSelect.options[paymentTypeSelect.selectedIndex].text.toLowerCase() === 'cheque') {
                    chequeDetails.classList.remove('d-none');
                } else {
                    chequeDetails.classList.add('d-none');
                }
            });

            amountInput.addEventListener('input', updateTotals);
            removeBtn.addEventListener('click', () => {
                row.remove();
                updateTotals();
            });
        }

        // Update totals
        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.paymentAmount').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            totalPaid.value = total.toFixed(2);
            balanceAmount.value = (parseFloat(finalTotal.value) - total).toFixed(2);
        }

        addPaymentRowBtn.addEventListener('click', createPaymentRow);

        // Initialize with one row
        createPaymentRow();
    });
</script>