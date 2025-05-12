jQuery(document).ready(function () {

    // Create Branch
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#bankId').val() || $('#bankId').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a bank",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter branch name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#code').val() || $('#code').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter branch code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#address').val() || $('#address').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter address",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#phoneNumber').val() || $('#phoneNumber').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter phone number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#city').val() || $('#city').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter city",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/branch.php", // Adjust the URL based on your needs
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    // Remove preloader
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Branch added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    } else if (result.status === 'error') {
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

    // Update Branch
    $("#update").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#bankId').val() || $('#bankId').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a bank",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter branch name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#code').val() || $('#code').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter branch code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#address').val() || $('#address').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter address",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#phoneNumber').val() || $('#phoneNumber').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter phone number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#city').val() || $('#city').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter city",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/branch.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    // Remove preloader
                    $('.someBlock').preloader('remove');

                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Branch updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    } else if (result.status === 'error') {
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

    //remove input field values
    $("#new").click(function (e) {
        e.preventDefault();

        // Reset all fields in the form
        $('#form-data')[0].reset();

        // Optional: Reset selects to default option (if needed)
        $('#bankId').prop('selectedIndex', 0);
        $("#create").show();
    });

    

    //model click append value form
    $(document).on('click', '.select-branch', function () {
        $('#branch_id').val($(this).data('id')); // set hidden id
        $('#bankId').val($(this).data('bankid'));
        $('#code').val($(this).data('code'));
        $('#name').val($(this).data('name'));
        $('#address').val($(this).data('address'));
        $('#phoneNumber').val($(this).data('phone'));
        $('#city').val($(this).data('city'));

        if ($(this).data('active') == 1) {
            $('#activeStatus').prop('checked', true);
        } else {
            $('#activeStatus').prop('checked', false);
        }

        $("#create").hide();
        $('#branch_master').modal('hide'); // Close the modal
    });

    //delete branch
    $(document).on('click', '.delete-branch', function (e) {
        e.preventDefault();

        var branchId = $('#branch_id').val();
        var branchName = $('#name').val();

        if (!branchId || branchId === "") {
            // Show an error message if no branch is selected
            swal({
                title: "Error!",
                text: "Please select a branch first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return; // Stop the deletion process if no branch is selected
        }
        swal({
            title: "Are you sure?",
            text: "Do you want to delete branch '" + branchName + "'?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                // Optional: Show preloader
                $('.someBlock').preloader();

                $.ajax({
                    url: 'ajax/php/branch.php',
                    type: 'POST',
                    data: {
                        id: branchId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Branch has been deleted.",
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

});
