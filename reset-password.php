<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Reset Password | Admin & Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="#" class="d-block auth-logo" style="margin-bottom: 18px;">
                            <img src="assets/images/logo.png" alt="" width="30%" class="logo logo-dark">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Set New Password</h5>
                                <p class="text-muted">Enter your new password below.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form action="#" method="post" id="reset-password-form">
                                    <input type="hidden" name="resetcode" id="resetcode" value="<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>">

                                    <div class="mb-3">
                                        <label class="form-label" for="password">New Password</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Enter new password" required minlength="6">
                                        <small class="text-muted">Password must be at least 6 characters long</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                            placeholder="Confirm new password" required minlength="6">
                                        <div id="password-match-message" class="mt-2" style="display: none;"></div>
                                    </div>

                                    <div class="mt-3 text-end">
                                        <button class="btn btn-primary w-sm waves-effect waves-light" type="submit" id="reset-password-btn">Update Password</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Remember your password? <a href="login.php" class="fw-medium text-primary">Sign in</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <p>© <script>
                                document.write(new Date().getFullYear())
                            </script> AI ERP Development <i class="mdi mdi-heart text-danger"></i> by sourcecode.lk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="assets/libs/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script src="ajax/js/user.js" type="text/javascript"></script>

</body>

</html>