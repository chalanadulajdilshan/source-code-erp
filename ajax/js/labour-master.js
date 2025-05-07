jQuery(document).ready(function () {

    // Create Branch
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#type').val() || $('#type').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a labour type.!",
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

        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/labour-master.php", // Adjust the URL based on your needs
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
                            text: "Labour added successfully!",
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
        if (!$('#type').val() || $('#type').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a labour type.!",
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

        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/labour-master.php",
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
                            text: "Labour updated successfully!",
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
        $('#type').prop('selectedIndex', 0);
        $("#create").show();
    });

    //model click append value form
    // model click - append labour data to form fields
    $(document).on('click', '.select-labour', function () {
        $('#id').val($(this).data('id'));           // Set hidden ID
        $('#name').val($(this).data('name')); 
        $('#code').val($(this).data('code'));            // Labour name
        $('#type').val($(this).data('type'));            // Labour type

        // Activate the checkbox if active
        if ($(this).data('active') == 1) {
            $('#is_active').prop('checked', true);
        } else {
            $('#is_active').prop('checked', false);
        }
        $("#create").hide();
        // Close modal
        $('#labourMasterModal').modal('hide');
    });



    //delete branch
    $(document).on('click', '.delete-labour', function (e) {
        e.preventDefault();

        var id = $('#id').val();
        var name = $('#name').val();

        if (!id || id === "") {
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
            text: "Do you want to delete branch '" + name + "'?",
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
                    url: "ajax/php/labour-master.php", 
                    type: 'POST',
                    data: {
                        id: id,
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
