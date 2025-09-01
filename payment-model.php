<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-cash-coin me-2"></i> Finalize Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light">
                <form id="paymentForm">


                    <!-- Final Total -->
                    <div class="mb-3">
                        <label class="fw-bold">Final Total</label>
                        <input type="text" id="modalFinalTotal" class="form-control form-control-lg text-end fw-bold border-primary" readonly>
                    </div>



                    <!-- Dynamic Payment Rows -->
                    <div id="paymentRows" class="mb-3"></div>

                    <button type="button" class="btn btn-outline-primary w-100 mb-4" id="addPaymentRow">
                        <i class="bi bi-plus-circle me-2"></i> Add Payment Method
                    </button>

                    <!-- Totals -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Total Paid</label>
                            <input type="text" id="totalPaid" class="form-control text-end bg-white fw-bold" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Balance</label>
                            <input type="text" id="balanceAmount" class="form-control text-end bg-white fw-bold" readonly>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="mt-4">
                        <label class="fw-bold">Note</label>
                        <textarea id="note" rows="2" class="form-control"></textarea>
                    </div>
            </div>
            </form>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" id="savePayment">
                    <i class="bi bi-check2-circle me-1"></i> Save Payment
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="assets/libs/jquery/jquery.min.js"></script>

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
            row.classList.add('payment-row', 'card', 'shadow-sm', 'mb-3');
            row.dataset.id = rowId;

            // Get the final total value and clean it (remove any non-numeric characters except decimal point)
            const finalTotalValue = parseFloat(finalTotal.value.replace(/[^0-9.-]+/g, "")) || 0;

            row.innerHTML = `
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="fw-semibold">Payment Type</label>
                            <select name="paymentType[]" class="form-select paymentType" required>
                                <option value="">-- Select --</option>
                                <?php
                                $PAYMENT_TYPE = new PaymentType(NULL);
                                foreach ($PAYMENT_TYPE->getActivePaymentType() as $type) {
                                    $selected = (strtolower($type['name']) === 'cash') ? 'selected' : '';
                                    echo "<option value='{$type['id']}' $selected>{$type['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold">Amount</label>
                            <input type="number" name="amount[]" id="amountPaid" class="form-control paymentAmount text-end fw-bold" min="0" step="0.01" 
                                   value="${finalTotalValue.toFixed(2)}" required>
                        </div>
                        <div class="col-md-4 chequeDetails d-none">
                            <label class="fw-semibold">Cheque No</label>
                            <input type="text" name="chequeNumber[]" class="form-control mb-2">
                            <label class="fw-semibold">Bank</label>
                            <input type="text" name="chequeBank[]" class="form-control mb-2">
                            <label class="fw-semibold">Date</label>
                            <input type="date" name="chequeDate[]" class="form-control">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
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

        // Update totals when amount changes
        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('paymentAmount')) {
                updateTotals();
            }
        });

        // Function to update payment totals
        function updateTotals() {
            let totalPaidValue = 0;
            const amountInputs = document.querySelectorAll('.paymentAmount');

            amountInputs.forEach(input => {
                totalPaidValue += parseFloat(input.value) || 0;
            });

            const finalTotalValue = parseFloat(finalTotal.value.replace(/[^0-9.-]+/g, "")) || 0;
            const balance = finalTotalValue - totalPaidValue;

            totalPaid.value = totalPaidValue.toFixed(2);
            balanceAmount.value = balance.toFixed(2);

            // Style the balance amount based on value
            if (balance > 0) {
                balanceAmount.classList.add('text-danger');
                balanceAmount.classList.remove('text-success');
            } else if (balance < 0) {
                balanceAmount.classList.add('text-warning');
                balanceAmount.classList.remove('text-danger');
            } else {
                balanceAmount.classList.remove('text-danger', 'text-warning');
                balanceAmount.classList.add('text-success');
            }
        }

        addPaymentRowBtn.addEventListener('click', createPaymentRow);

        // Add first payment row on page load
        createPaymentRow();

        // Update totals when the page loads
        updateTotals();
    });
</script>