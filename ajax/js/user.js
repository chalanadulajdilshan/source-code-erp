jQuery(document).ready(function () {

    // Create Branch
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#type').val() || $('#type').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select user type",
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
        } else if (!$('#username').val() || $('#username').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter user name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#password').val() || $('#password').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter password",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#phone').val() || $('#phone').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter phone number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#company_id').val() || $('#company_id').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select company",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#department_id').val() || $('#department_id').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select department",
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
                url: "ajax/php/user.php", // Adjust the URL based on your needs
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
                            text: "Company added successfully!",
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
                text: "Please select user type",
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
        } else if (!$('#username').val() || $('#username').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter user name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });

        } else if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#phone').val() || $('#phone').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter phone number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#department_id').val() || $('#department_id').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select department",
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
                url: "ajax/php/user.php",
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
                            text: "Company updated successfully!",
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

    // JS to open modal when button is clicked
    $('#open-branch-modal').click(function (e) {
        e.preventDefault();
        var myModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-xl'));
        myModal.show();
    });

    $(document).on("click", ".select-user", function () {
        const row = $(this);
        $("#user_id").val(row.data("id"));
        $("#code").val(row.data("code"));
        $("#name").val(row.data("name"));
        $("#username").val(row.data("username"));
        $("#phone").val(row.data("phone"));
        $("#email").val(row.data("email"));
        $("#type").val(row.data("type"));
        $("#password").val(row.data("show_password"));
        $("#company_id").val(row.data("company_id"));
        $("#department_id").val(row.data("department_id"));


        // Set checkbox state based on active flag
        $("#active").prop("checked", row.data("active") == 1);

        $("#create").hide();
        // Close modal
        $("#userModal").modal("hide");
    });


});
