jQuery(document).ready(function () {

    //number formate
    function formatPriceInput(inputField) {
        let inputValue = inputField.value;

        inputValue = inputValue.replace(/[^\d.]/g, '');

        // If the input contains a decimal point, make sure it has only one
        let [integerPart, decimalPart] = inputValue.split('.');
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add commas for thousands

        if (decimalPart) {
            decimalPart = decimalPart.substring(0, 2); // Limit to 2 decimal places
        }

        // Reconstruct the formatted price
        let formattedPrice = decimalPart ? `${integerPart}.${decimalPart}` : integerPart;

        inputField.value = formattedPrice;
    }

    document.querySelectorAll('.number-format').forEach(function (inputField) {
        inputField.addEventListener('input', function () {
            formatPriceInput(this);
        });
    });

    //get district name
    $('#province').change(function () {

        $('.someBlock').preloader(); // Show preloader

        var province = $(this).val(); // Get selected province

        $('#district').empty(); // Clear existing district options

        $.ajax({
            url: "ajax/php/district.php",
            type: "POST",
            data: {
                province: province,
                action: 'GET_DISTRICT_BY_PROVINCE'
            },
            dataType: "JSON",
            success: function (jsonStr) {

                $('.someBlock').preloader('remove'); // Remove preloader

                var html = '<option value=""> - Select District - </option>';
                $.each(jsonStr, function (i, data) {
                    html += '<option value="' + data.id + '">' + data.name + '</option>';
                });

                $('#district').html(html); // Set new options
            },
            error: function () {
                $('.someBlock').preloader('remove'); // Remove preloader on error
                swal({
                    title: "Error!",
                    text: "Failed to load districts.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // add customers for same formate 
    $('#customerModal').on('shown.bs.modal', function () {
        loadCustomerTable();
    });
    //loard customers all
    function loadCustomerTable() {
        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }

        $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "ajax/php/customer-master.php",
                type: "POST",
                data: function (d) {
                    d.filter = true;
                    d.category = 1;
                },
                dataSrc: function (json) {
                    return json.data;
                },
                error: function (xhr) {
                    console.error("Server Error Response:", xhr.responseText);
                }
            },
            columns: [
                { data: "key", title: "#ID" },
                { data: "code", title: "Code" },
                { data: "name", title: "Name" },
                { data: "mobile_number", title: "Mobile" },
                { data: "email", title: "Email" },
                { data: "category", title: "Category" },
                { data: "province", title: "Province" },
                { data: "credit_limit", title: "Credit Limit" },
                { data: "vat_no", title: "Is Vat" },
                { data: "status_label", title: "Status" }
            ],
            order: [[0, 'desc']],
            pageLength: 100
        });

        $('#customerTable tbody').on('click', 'tr', function () {
            var data = $('#customerTable').DataTable().row(this).data();

            if (data) {
                $('#customer_id').val(data.id);
                $('#customer_code').val(data.code);
                $('#customer_name').val(data.name);
                $('#customer_address').val(data.address);
                $('#customer_mobile').val(data.mobile_number);
                $('#customerModal').modal('hide');
            }
        });
    }

    //loard firrst customer cash customer

    //windows loder
    loadCustomer();


    //get first row cash sales customer
    function loadCustomer() {

        $.ajax({
            url: 'ajax/php/customer-master.php',
            method: 'POST',
            data: { action: 'get_first_customer' }, // you can customize this key/value
            dataType: 'json',
            success: function (data) {
                if (!data.error) {
                    $('#customer_id').val(data.customer_id);
                    $('#customer_code').val(data.customer_code);
                    $('#customer_name').val(data.customer_name);
                    $('#customer_address').val(data.customer_address);
                    $('#customer_mobile').val(data.mobile_number); // adjust key if needed

                } else {
                    console.warn('No customer found');
                }
            },
            error: function () {
                console.error('AJAX request failed.');
            }
        });
    }


    //loard supliers model  
    $('#supplierModal').on('shown.bs.modal', function () {
        loadSupplierTable();
    });

    //loard supliers
    function loadSupplierTable() {
        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable('#supplierTable')) {
            $('#supplierTable').DataTable().destroy();
        }

        $('#supplierTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "ajax/php/customer-master.php",
                type: "POST",
                data: function (d) {
                    d.filter = true;
                    d.category = 2;
                },
                dataSrc: function (json) {
                    return json.data;
                },
                error: function (xhr) {
                    console.error("Server Error Response:", xhr.responseText);
                }
            },
            columns: [
                { data: "key", title: "#ID" },
                { data: "code", title: "Code" },
                { data: "name", title: "Name" },
                { data: "mobile_number", title: "Mobile" },
                { data: "email", title: "Email" },
                { data: "category", title: "Category" },
                { data: "province", title: "Province" },
                { data: "credit_limit", title: "Credit Limit" },
                { data: "vat_no", title: "Is Vat" },
                { data: "status_label", title: "Status" }
            ],
            order: [[0, 'desc']],
            pageLength: 100
        });

        $('#supplierTable tbody').on('click', 'tr', function () {
            var data = $('#supplierTable').DataTable().row(this).data();

            if (data) {
                $('#supplier_id').val(data.id);
                $('#supplier_code').val(data.code);
                $('#supplier_name').val(data.name);
            }

            $('#supplierModal').modal('hide');

        });
    }

    // When the modal is shown, load the DataTable
    $('#AllCustomerModal').on('shown.bs.modal', function () {
        loadAllCustomerTable();
    });

    function loadAllCustomerTable() {
        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable('#allCustomerTable')) {
            $('#allCustomerTable').DataTable().destroy();
        }

        $('#allCustomerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "ajax/php/customer-master.php",
                type: "POST",
                data: function (d) {
                    d.filter = true;
                    d.category = [1, 2, 3];
                },
                dataSrc: function (json) {
                    return json.data;
                },
                error: function (xhr) {
                    console.error("Server Error Response:", xhr.responseText);
                }
            },
            columns: [
                { data: "key", title: "#ID" },
                { data: "code", title: "Code" },
                { data: "name", title: "Name" },
                { data: "mobile_number", title: "Mobile" },
                { data: "email", title: "Email" },
                { data: "category", title: "Category" },
                { data: "province", title: "Province" },
                { data: "credit_limit", title: "Credit Limit" },
                { data: "vat_no", title: "Is Vat" },
                { data: "status_label", title: "Status" }
            ],
            order: [[0, 'desc']],
            pageLength: 100
        });

        // Row click event to populate form and close modal
        $('#allCustomerTable tbody').off('click').on('click', 'tr', function () {
            var data = $('#allCustomerTable').DataTable().row(this).data();

            if (data) {


                $('#customer_id').val(data.id || '');
                $('#code').val(data.code || '');
                $('#name').val(data.name || '');
                $('#address').val(data.address || '');
                $('#mobile_number').val(data.mobile_number || '');
                $('#mobile_number_2').val(data.mobile_number_2 || '');
                $('#email').val(data.email || '');
                $('#contact_person').val(data.contact_person || '');
                $('#contact_person_number').val(data.contact_person_number || '');

                // Checkbox (is_active), assuming 1 = checked, 0 = unchecked
                $('#is_active').prop('checked', data.status == 1);

                $('#credit_limit').val(data.credit_limit || '');
                $('#outstanding').val(data.outstanding || '');
                $('#overdue').val(data.overdue || '');
                $('#vat_no').val(data.vat_no || '');
                $('#svat_no').val(data.svat_no || '');

                // For select inputs, set value and trigger change to update select2 UI if used
                $('#category').val(data.category_id || '').trigger('change');
                $('#province').val(data.province_id || '').trigger('change');
                $('#district').val(data.district_id || '').trigger('change');
                $('#vat_group').val(data.vat_group || '').trigger('change');

                $('#remark').val(data.remark || '');
                $("#create").hide();
                // Close the modal
                $('#AllCustomerModal').modal('hide');
            }
        });
    }

    //sales invoice get same formate for all
    function initInvoiceTable() {
        var table = $('#invoiceTableData').DataTable({
            processing: true,
            serverSide: true,
            destroy: true, // Re-initializes cleanly when called again
            ajax: {
                url: "ajax/php/sales-invoice.php",
                type: "POST",
                data: function (d) {
                    d.filter = true;  // You can adjust this if needed
                },
                dataSrc: function (json) {
                    console.log("Response Data:", json); // Check the server response here
                    return json.data || [];  // Ensure data is in the expected format
                },
                error: function (xhr, error, thrown) {
                    console.error("Server Error:", xhr.responseText, error, thrown);
                }
            },
            columns: [
                { data: "id", title: "#ID" },
                { data: "invoice_no", title: "Invoice No" },
                { data: "invoice_date", title: "Date" },
                { data: "department", title: "Department" },
                { data: "customer", title: "Customer" },
                { data: "grand_total", title: "Grand Total" }
            ],
            order: [[0, 'desc']], // Sort by #ID (Descending)
            pageLength: 100
        });

        // Row click to populate form
        $('#invoiceTableData tbody').off('click').on('click', 'tr', function () {
            var data = table.row(this).data();
            if (!data) return;

            // Populate form fields safely
            $('#invoice_id').val(data.id || '');
            $('#invoice_no').val(data.invoice_no || '');
            $('#invoice_date').val(data.invoice_date || '');
            $('#department_id').val(data.department_id || '').trigger('change');
            $('#customer_id').val(data.customer_id || '').trigger('change');
            $('#sale_type').val(data.sale_type || '').trigger('change');
            $('#discount_type').val(data.discount_type || '').trigger('change');
            $('#payment_type').val(data.payment_type || '').trigger('change');
            $('#sub_total').val(data.sub_total || '');
            $('#discount').val(data.discount || '');
            $('#tax').val(data.tax || '');
            $('#grand_total').val(data.grand_total || '');
            $('#remark').val(data.remark || '');

            // Fetch items related to invoice
            fetchInvoiceItems(data.id);

            $("#create").hide();
            $('#invoiceModal').modal('hide');
        });


    }

    function fetchInvoiceItems(invoiceId) {
        $.ajax({
            url: 'ajax/php/temp-sales-items.php', // Replace with your PHP endpoint
            type: 'GET',
            data: { invoice_id: invoiceId },
            dataType: 'json',
            success: function (response) {
                let tbody = $('#invoiceItemsBody');
                tbody.empty();

                if (response && response.length > 0) {
                    response.forEach(item => {
                        let row = `
                        <tr>
                            <td>${item.item_code}</td>
                            <td>${item.item_name}</td>
                            <td>${parseFloat(item.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${item.quantity}</td>
                            <td>${item.discount}%</td>
                            <td>${parseFloat(item.price - (item.price * (item.discount / 100))).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${parseFloat(item.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>
 -
                            </td>
                        </tr>
                    `;
                        tbody.append(row);
                    });
                } else {
                    tbody.html(`<tr id="noItemRow">
                                <td colspan="8" class="text-center text-muted">No items found</td>
                            </tr>`);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch items:', error);
                $('#invoiceItemsBody').html(`<tr><td colspan="8" class="text-center text-danger">Error loading items</td></tr>`);
            }
        });
    }

    // Re-initialize when modal is shown
    $('#invoiceModal').on('shown.bs.modal', function () {
        initInvoiceTable();
    });

    $('#show-more-btn').on('click', function () {
        $('.extra-message').removeClass('d-none');
        $('#show-more-btn').addClass('d-none');
        $('#show-less-btn').removeClass('d-none');
    });

    $('#show-less-btn').on('click', function () {
        $('.extra-message').addClass('d-none');
        $('#show-more-btn').removeClass('d-none');
        $('#show-less-btn').addClass('d-none');
    });

    $(document).ready(function () {
        const totalMessages = $('.alert').length;
        const toggleLink = $('#toggle-messages');

        if (totalMessages <= 2) {
            toggleLink.hide(); // hide toggle link if ≤ 2 messages
        }

        let expanded = false;

        toggleLink.on('click', function (e) {
            e.preventDefault();
            if (!expanded) {
                $('.extra-message').removeClass('d-none');
                toggleLink.text(totalMessages + ' of ' + totalMessages + ' Hide messages');
            } else {
                $('.extra-message').addClass('d-none');
                toggleLink.text('2 of ' + totalMessages + ' click all messages');
            }
            expanded = !expanded;
        });
    });

});

