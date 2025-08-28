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

    // Forget password - send reset code to email
    $("#forget-password").click(function (e) {
        e.preventDefault();

        if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('forget-password', true);

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

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Password reset code sent to your email!",
                            type: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.href = "enter-reset-code.php";
                        }, 3000);

                    } else if (result.status === 'error') {
                        swal({
                            title: "Error!",
                            text: result.message || "Something went wrong.",
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Verify reset code
    $("#verify-code-btn").click(function (e) {
        e.preventDefault();

        if (!$('#resetcode').val() || $('#resetcode').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter the reset code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if ($('#resetcode').val().length !== 5) {
            swal({
                title: "Error!",
                text: "Reset code must be 5 digits",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            var formData = new FormData();
            formData.append('resetcode', $('#resetcode').val());
            formData.append('verify-reset-code', true);

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
                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Code verified successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.href = "reset-password.php?code=" + encodeURIComponent(result.code);
                        }, 2000);

                    } else if (result.status === 'error') {
                        swal({
                            title: "Error!",
                            text: result.message || "Invalid reset code.",
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Password confirmation validation
    $('#confirm_password').on('keyup', function () {
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var messageDiv = $('#password-match-message');

        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                messageDiv.html('<small class="text-success">Passwords match</small>').show();
            } else {
                messageDiv.html('<small class="text-danger">Passwords do not match</small>').show();
            }
        } else {
            messageDiv.hide();
        }
    });

    // Reset password with new password
    $("#reset-password-btn").click(function (e) {
        e.preventDefault();

        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var resetCode = $('#resetcode').val();

        // Validation
        if (!password || password.length === 0) {
            swal({
                title: "Error!",
                text: "Please enter a new password",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (password.length < 6) {
            swal({
                title: "Error!",
                text: "Password must be at least 6 characters long",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!confirmPassword || confirmPassword.length === 0) {
            swal({
                title: "Error!",
                text: "Please confirm your password",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (password !== confirmPassword) {
            swal({
                title: "Error!",
                text: "Passwords do not match",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!resetCode || resetCode.length === 0) {
            swal({
                title: "Error!",
                text: "Reset code is missing",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            var formData = new FormData();
            formData.append('password', password);
            formData.append('resetcode', resetCode);
            formData.append('update-password', true);

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
                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Password updated successfully! You can now login.",
                            type: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.href = "login.php";
                        }, 3000);

                    } else if (result.status === 'error') {
                        swal({
                            title: "Error!",
                            text: result.message || "Failed to update password.",
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

});