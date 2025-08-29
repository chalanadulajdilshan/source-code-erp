jQuery(document).ready(function () {

    // Create Customer
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/customer-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Customer added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);

                    } else {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }

        return false;
    });


    // Create Customer in Invoice
    $("#create-invoice-customer").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data-invoice")[0]);
            formData.append('create-invoice-customer', true);

            $.ajax({
                url: "ajax/php/customer-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Customer added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Close modal
                        $('#customerAddModal').modal('hide');

                        $('#customer_code').val(result.customer_id);
                        $('#customer_name').val(result.customer_name);
                        $('#customer_address').val(result.customer_address);
                        $('#customer_mobile').val(result.customer_mobile_number);

                    } else {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }

        return false;
    });

    // Update Customer
    $("#update").click(function (event) {
        event.preventDefault();

        if (!$('#code').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val()) {
            swal({
                title: "Error!",
                text: "Please enter customer name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#province').val() || $('#province').val() == 0) {
            swal({
                title: "Error!",
                text: "Please Select Province",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });


        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/customer-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Customer updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);

                    } else {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }

        return false;
    });

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $('#category').prop('selectedIndex', 0); // Optional, if using dropdowns
        $("#create").show();
    });


    // Delete Customer
    $(document).on('click', '.delete-customer', function (e) {
        e.preventDefault();

        var customerId = $('#customer_id').val();
        var customerName = $('#name').val();

        if (!customerId || customerId === "") {
            swal({
                title: "Error!",
                text: "Please select a customer first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete customer '" + customerName + "'?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('.someBlock').preloader();

                $.ajax({
                    url: 'ajax/php/customer-master.php',
                    type: 'POST',
                    data: {
                        id: customerId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Customer has been deleted.",
                                type: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);

                        } else {
                            swal({
                                title: "Error!",
                                text: "Something went wrong.",
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });


    $("#customer_code").keyup(function () {
        var query = $(this).val();

        if (query.length >= 1) {
            $.ajax({
                url: "ajax/php/customer-master.php",
                type: "POST",
                data: { query: query },
                success: function (data) {
                    // If `data` is already an object, no need to parse it
                    var customers = data; // `data` is already an object, no need to JSON.parse() again
                    var listHtml = "";

                    if (customers.length > 0) {
                        customers.forEach(function (customer) {
                            listHtml += `
                                <a href="#" class="list-group-item list-group-item-action"
                                   data-id="${customer.id}"
                                   data-code="${customer.code}"
                                   data-address="${customer.address}"
                                   data-mobile="${customer.mobile_number}"
                                   data-name="${customer.name}">
                                   ${customer.code} - ${customer.name}
                                </a>`;
                        });
                    } else {
                        listHtml = '<a href="#" class="list-group-item list-group-item-action disabled" style="color:black">No results found</a>';
                    }

                    // Populate the list with customer results
                    $("#customerList").html(listHtml).show();
                },
                error: function () {
                    $("#customerList").html('<a href="#" class="list-group-item list-group-item-action disabled" style="color:black">Error loading customers</a>').show();
                }
            });
        } else {
            $("#customerList").hide();
        }
    });


    var selectedIndex = -1; // Keeps track of the selected customer in the dropdown

    // Keydown event to handle Up and Down arrow key navigation
    $(document).on("keydown", "#customer_code", function (e) {
        var listItems = $("#customerList .list-group-item");

        if (listItems.length > 0) {
            // Handle Down arrow key
            if (e.keyCode == 40) { // Down arrow
                if (selectedIndex < listItems.length - 1) {
                    selectedIndex++;
                }
            }
            // Handle Up arrow key
            else if (e.keyCode == 38) { // Up arrow
                if (selectedIndex > 0) {
                    selectedIndex--;
                }
            }
            // Handle Enter key
            else if (e.keyCode == 13) { // Enter key
                var selectedItem = listItems.eq(selectedIndex);
                if (selectedItem.length) {
                    // Retrieve customer data
                    var customerCode = selectedItem.data("code");
                    var customerName = selectedItem.data("name");
                    var customerAddress = selectedItem.data("address");
                    var customerMobile = selectedItem.data("mobile");

                    // Populate input fields with selected customer data
                    $("#customer_code").val(customerCode);
                    $("#customer_name").val(customerName);
                    $("#customer_address").val(customerAddress);
                    $("#customer_mobile").val(customerMobile);

                    // Hide the customer dropdown list
                    $("#customerList").hide();

                    // Highlight the selected item
                    listItems.removeClass("active"); // Remove active class from all items
                    selectedItem.addClass("active"); // Highlight the current selected item
                    $("#customer_code").blur(); // Unfocus the input field
                }
            }

            // Highlight the selected item in the dropdown
            listItems.removeClass("active"); // Remove any previously highlighted class
            listItems.eq(selectedIndex).addClass("active"); // Highlight the current item
        }
    });

    // When a user clicks on a dropdown item
    $(document).on("click", "#customerList .list-group-item", function () {
        // Retrieve customer data from the clicked item
        var customerCode = $(this).data("code");
        var customerName = $(this).data("name");
        var customerAddress = $(this).data("address");
        var customerMobile = $(this).data("mobile");

        // Populate the fields with the customer data
        $("#customer_code").val(customerCode);
        $("#customer_name").val(customerName);
        $("#customer_address").val(customerAddress);
        $("#customer_mobile").val(customerMobile);

        // Hide the customer list after selection
        $("#customerList").hide();

        // Keep the selected item highlighted
        $("#customerList .list-group-item").removeClass("active");
        $(this).addClass("active");

    });

    // Click outside to close the dropdown if it's open
    $(document).click(function (e) {
        if (!$(e.target).closest("#customer_code").length) {
            $("#customerList").hide();
        }
    });


});
