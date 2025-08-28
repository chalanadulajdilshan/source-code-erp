<?php
// Session is started in class/include.php

// Include necessary files
include 'class/include.php';

// Only include auth.php if not on password reset related pages
if (
    basename($_SERVER['PHP_SELF']) !== 'forget-password.php' &&
    basename($_SERVER['PHP_SELF']) !== 'reset-password.php'
) { // Add other password reset related pages if needed
    include 'auth.php';
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Forget Password </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- include main CSS -->
    <?php include 'main-css.php'; ?>


</head>

<body class="authentication-bg">
    <div class="account-pages my-5  pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div>

                        <a href="index.html" class="mb-5 d-block auth-logo">
                            <img src="assets/images/logo-dark.png" alt="" height="22" class="logo logo-dark">
                            <img src="assets/images/logo-light.png" alt="" height="22" class="logo logo-light">
                        </a>
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p class="text-muted">Reset Password with Minible.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <div class="alert alert-success text-center mb-4" role="alert">
                                        Enter your Email and instructions will be sent to you!
                                    </div>
                                    <form id="form-data">
                                        <div class="mb-3">
                                            <label class="form-label" for="useremail">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                        </div>

                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit" id="forget-password">Reset</button>
                                        </div>


                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Remember It ? <a href="auth-login.html" class="fw-medium text-primary"> Signin </a></p>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> AI ERP Development <i class="mdi mdi-heart text-danger"></i> by sourcecode.lk
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>

    <!-- JAVASCRIPT -->
    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/user.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>