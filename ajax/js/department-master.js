jQuery(document).ready(function () {

    // Create Branch
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter department name",
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
                url: "ajax/php/department-master.php", // Adjust the URL based on your needs
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
                            text: "Department added successfully!",
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
        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter department name",
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
                url: "ajax/php/department-master.php",
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
                            text: "Department updated successfully!",
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
        $('#name').prop('selectedIndex', 0);
        $("#create").show();
    });

    

    //model click append value form
    $(document).on('click', '.select-department', function () {
        $('#department_id').val($(this).data('id')); // set hidden id
        $('#code').val($(this).data('code'));
        $('#name').val($(this).data('name'));
        $('#remark').val($(this).data('remark'));
    
        if ($(this).data('status') == 1) {
            $('#is_active').prop('checked', true);
        } else {
            $('#is_active').prop('checked', false);
        }
    
        $("#create").hide();
        $('#departmentModal').modal('hide'); // Close the modal
    });
    

    //delete branch
    $(document).on('click', '.delete-department', function (e) {
        e.preventDefault();
    
        var departmentId = $('#department_id').val();
        var departmentName = $('#name').val();
    
        if (!departmentId || departmentId === "") {
            swal({
                title: "Error!",
                text: "Please select a department first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
    
        swal({
            title: "Are you sure?",
            text: "Do you want to delete department '" + departmentName + "'?",
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
                    url: 'ajax/php/department-master.php',
                    type: 'POST',
                    data: {
                        id: departmentId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');
    
                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Department has been deleted.",
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
