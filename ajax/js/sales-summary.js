$(document).ready(function () {

    // Disable customer inputs if "Check All Customers" is checked
    $('#checkAllCustomers').on('change', function () {
        const checked = $(this).is(':checked');
        if (checked) {
            $('#customer_code, #customer_name, #customer_address').val('').prop('readonly', true);
        } else {
            $('#customer_code, #customer_name, #customer_address').prop('readonly', false);
        }
    });

    // Disable date inputs if date range selected
    $('#date_range').on('change', function () {
        const val = $(this).val();
        if (val) {
            $('#from_date, #to_date').val('').prop('disabled', true);
        } else {
            $('#from_date, #to_date').prop('disabled', false);
        }
    });

    $('#from_date, #to_date').on('input change', function () {
        if ($('#from_date').val() || $('#to_date').val()) {
            $('#date_range').val('').prop('disabled', true);
        } else {
            $('#date_range').prop('disabled', false);
        }
    });

    function getReportData() {
        const allCustomers = $('#checkAllCustomers').is(':checked');
        const customer_code = $('#customer_code').val();
        const from_date = $('#from_date').val();
        const to_date = $('#to_date').val();
        const date_range = $('#date_range').val();
        const status = $('#selectStatus').val();

        if (!allCustomers && !customer_code) {
            alert('Please select a customer or check "All Customers"');
            return null;
        }

        if (!status) {
            alert('Please select a status');
            return null;
        }

        if (!date_range && (!from_date || !to_date)) {
            alert('Please select either a date range or from-to dates');
            return null;
        }

        if (date_range && (from_date || to_date)) {
            alert('Please select either a date range or from-to dates, not both');
            return null;
        }

        return {
            all_customers: allCustomers ? 1 : 0,
            customer_code,
            from_date,
            to_date,
            date_range,
            status
        };
    }

    // View Report (HTML preview)
    function loadReport() {
        const data = getReportData();
        if (!data) return;

        $.ajax({
            url: 'ajax/php/sales-summary.php',
            method: 'POST',
            data: data,
            beforeSend: function () {
                $('#reportResult').html('<p>Loading report...</p>');
            },
            success: function (res) {
                $('#reportResult').html(res);
            },
            error: function () {
                $('#reportResult').html('<p class="text-danger">Failed to load report.</p>');
            }
        });
    }

    // Generate PDF Report
    function generatePDF() {
        const data = getReportData();
        if (!data) return;

        $.ajax({
            url: 'ajax/php/generate_pdf.php',
            method: 'POST',
            data: data,
            beforeSend: function () {
                $('#reportResult').html('<p>Generating PDF...</p>');
            },
            success: function (response) {
                try {
                    const res = JSON.parse(response);
                    if (res.pdf_url) {
                        window.open(res.pdf_url, '_blank');
                    } else {
                        $('#reportResult').html('<p class="text-danger">PDF generation failed.</p>');
                    }
                } catch (e) {
                    $('#reportResult').html('<p class="text-danger">Invalid response from server.</p>');
                }
            },
            error: function () {
                $('#reportResult').html('<p class="text-danger">PDF request failed.</p>');
            }
        });
    }

    // Events
    $('#selectStatus').on('change', loadReport);
    $('#print_btn').on('click', generatePDF);

});
