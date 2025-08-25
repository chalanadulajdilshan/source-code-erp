<?php
include 'class/include.php';
include 'auth.php';

$USER = new User($_SESSION['id']);
$message = '';
$message_type = '';

// Handle form submission for profile update
if (isset($_POST['update_profile'])) {
    $USER->name = $_POST['name'];
    $USER->email = $_POST['email'];
    $USER->phone = $_POST['phone'];

    if ($USER->update()) {
        $message = "Profile updated successfully!";
        $message_type = "success";
    } else {
        $message = "Failed to update profile.";
        $message_type = "error";
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($USER->verifyPassword($current_password)) {
        if ($new_password === $confirm_password) {
            if ($USER->changePassword($USER->id, $new_password)) {
                $message = "Password changed successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to change password. Please try again.";
                $message_type = "error";
            }
        } else {
            $message = "New passwords do not match.";
            $message_type = "error";
        }
    } else {
        $message = "Current password is incorrect.";
        $message_type = "error";
    }
}

// Handle profile image upload
if (isset($_POST['update_image'])) {
    if (isset($_FILES['profile_image'])) {
        $handle = new Upload($_FILES['profile_image']);
        if ($handle->uploaded) {
            // First, delete the old image if it exists
            if (!empty($USER->image_name) && file_exists('upload/users/' . $USER->image_name)) {
                unlink('upload/users/' . $USER->image_name);
            }

            // Process the new image
            $handle->image_resize = true;
            $handle->file_new_name_ext = 'jpg';
            $handle->image_ratio_crop = 'C';
            $handle->file_new_name_body = $USER->id;
            $handle->image_x = 300;
            $handle->image_y = 300;
            $handle->Process('upload/users/');

            if ($handle->processed) {
                // Update the user's image name in the database
                $USER->image_name = $handle->file_dst_name;
                if ($USER->update()) {
                    // Simply reload the user data without destroying session
                    $USER = new User($USER->id);
                    $message = "Profile image updated successfully!";
                    $message_type = "success";
                    echo "<script>
                        setTimeout(function() {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Profile image updated successfully!',
                                icon: 'success',
                                confirmButtonColor: '#5b73e8',
                                timer: 2000,
                                timerProgressBar: true
                            }).then((result) => {
                                window.location.href = 'profile.php';
                            });
                        }, 100);
                    </script>";
                } else {
                    $message = "Failed to update profile image in database.";
                    $message_type = "error";
                }
            } else {
                $message = $handle->error;
                $message_type = "error";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>My Profile | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <?php include 'main-css.php' ?>
    <!-- Sweet Alert 2 css -->
    <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
</head>

<body data-layout="horizontal" data-topbar="colored">
    <?php if (!empty($message)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '<?php echo ucfirst($message_type); ?>!',
                    text: '<?php echo $message; ?>',
                    icon: '<?php echo $message_type; ?>',
                    confirmButtonColor: '#5b73e8',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <div id="layout-wrapper">
        <?php include 'navigation.php' ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">My Profile</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <?php echo $message; ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <?php
                                                    $imagePath = (!empty($USER->image_name) && file_exists("upload/users/" . $USER->image_name))
                                                        ? "upload/users/" . $USER->image_name . '?t=' . time()
                                                        : "assets/images/users/avatar-1.jpg";
                                                    ?>
                                                    <img src="<?php echo $imagePath; ?>"
                                                        alt="Profile Image"
                                                        class="img-fluid rounded-circle"
                                                        style="width: 200px; height: 200px; object-fit: cover;">

                                                    <h5 class="mb-1 mt-3"><?php echo htmlspecialchars($USER->name); ?></h5>
                                                    <p class="text-muted"><?php echo htmlspecialchars($USER->email); ?></p>

                                                    <form method="post" enctype="multipart/form-data" class="mt-3">
                                                        <div class="mb-3">
                                                            <input type="file" name="profile_image" class="form-control" accept="image/*" required>
                                                        </div>
                                                        <button type="submit" name="update_image" class="btn btn-primary w-100">
                                                            <i class="uil uil-upload me-1"></i> Upload New Photo
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">
                                                        <i class="uil uil-user-circle font-size-20"></i>
                                                        <span class="d-none d-sm-block">Profile</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#change-password" role="tab">
                                                        <i class="uil uil-lock font-size-20"></i>
                                                        <span class="d-none d-sm-block">Change Password</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content p-3 border border-top-0">
                                                <div class="tab-pane active" id="profile" role="tabpanel">
                                                    <form method="post">
                                                        <div class="mb-3">
                                                            <label class="form-label">Full Name</label>
                                                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($USER->name); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($USER->email); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Phone</label>
                                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($USER->phone); ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Username</label>
                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($USER->username); ?>" disabled>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="submit" name="update_profile" class="btn btn-primary">
                                                                <i class="uil uil-edit me-1"></i> Update Profile
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="tab-pane" id="change-password" role="tabpanel">
                                                    <form method="post">
                                                        <div class="mb-3">
                                                            <label class="form-label">Current Password</label>
                                                            <input type="password" class="form-control" name="current_password" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">New Password</label>
                                                            <input type="password" class="form-control" name="new_password" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Confirm New Password</label>
                                                            <input type="password" class="form-control" name="confirm_password" required>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="submit" name="change_password" class="btn btn-primary">
                                                                <i class="uil uil-key-skeleton-alt me-1"></i> Change Password
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'footer.php' ?>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <?php include 'main-js.php'; ?>
</body>

</html>