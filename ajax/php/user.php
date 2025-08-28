<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
define("SITE_NAME", "ERP Development");


// Create a new user
if (isset($_POST['create'])) {

    $name          = $_POST['name'];
    $code          = $_POST['code'];
    $type          = $_POST['type'];
    $active        = isset($_POST['active']) ? 1 : 0;
    $email         = $_POST['email'];
    $phone         = $_POST['phone'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];
    $company_id    = $_POST['company_id'];
    $department_id = $_POST['department_id'];

    $USER = new User(NULL);

    $res = $USER->create($name, $code, $type, $company_id, $active, $email, $phone, $username, $password, $password, $department_id);

    echo json_encode([
        "status" => $res ? 'success' : 'error'
    ]);
    exit();
}

// Update an existing user
if (isset($_POST['update'])) {

    $USER = new User($_POST['user_id']);

    $USER->name          = $_POST['name'];
    $USER->code          = $_POST['code'];
    $USER->email         = $_POST['email'];
    $USER->phone         = $_POST['phone'];
    $USER->username      = $_POST['username'];
    $USER->company_id    = $_POST['company_id'];
    $USER->department_id = $_POST['department_id'];
    $USER->active_status = isset($_POST['active']) ? 1 : 0;

    $result = $USER->update();

    echo json_encode([
        "status" => $result ? 'success' : 'error'
    ]);
    exit();
}

// Forget password
if (isset($_POST['forget-password'])) {
    $email = $_POST['email'];
    $USER  = new User(NULL);

    // First check if email exists
    $userData = $USER->checkEmail($email);

    if ($userData !== false && !empty($userData)) {

        if ($USER->GenarateCode($email)) {
            $resetData = $USER->SelectForgetUser($email);

            // Ensure associative access
            if (is_array($resetData) && isset($resetData['resetcode'])) {
                $resetCode = $resetData['resetcode'];
            } else {
                echo json_encode([
                    "status" => 'error',
                    "message" => 'Reset code not found.'
                ]);
                exit();
            }

            // Email configuration
            $to      = $email;
            $subject = "Your Password Reset Code | " . SITE_NAME;

            $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                <title>$subject</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4a6fa5; padding: 20px; text-align: center; color: white; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .code { 
                        font-size: 24px; 
                        font-weight: bold; 
                        letter-spacing: 3px;
                        text-align: center;
                        margin: 20px 0;
                        padding: 15px;
                        background: #e9ecef;
                        border-radius: 4px;
                    }
                    .footer { 
                        margin-top: 20px;
                        padding-top: 10px;
                        border-top: 1px solid #ddd;
                        font-size: 12px;
                        color: #777;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Password Reset Request</h2>
                    </div>
                    <div class='content'>
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Please use the following verification code:</p>
                        <div class='code'>$resetCode</div>
                        <p>This code will expire in 15 minutes.</p>
                        <p>If you didn't request this, please ignore this email or contact support if you have any concerns.</p>
                        <p>Best regards,<br>" . SITE_NAME . " Team</p>
                    </div>
                    <div class='footer'>
                        <p>This is an automated message, please do not reply to this email.</p>
                        <p>© " . date('Y') . " " . SITE_NAME . ". All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";


            $fromEmail = "erp.sourcecode@chalana.xyz"; // your domain email

            $headers   = [];
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=UTF-8';
            $headers[] = 'From: ' . SITE_NAME . ' <' . $fromEmail . '>';
            $headers[] = 'Reply-To: ' . $fromEmail;
            $headers[] = 'X-Mailer: PHP/' . phpversion();
            $headers[] = 'X-Priority: 1';
            $headers[] = 'X-MSMail-Priority: High';
            $headers[] = 'Importance: High';
            $headers   = implode("\r\n", $headers);

            $additional_parameters = "-f " . $fromEmail;

            $mailSent = mail($to, $subject, $message, $headers, $additional_parameters);

            if ($mailSent) {
                echo json_encode([
                    "status" => 'success',
                    "message" => 'A password reset code has been sent to your email address.'
                ]);
            } else {
                error_log("Failed to send password reset email to $email");
                echo json_encode([
                    "status" => 'error',
                    "message" => 'Failed to send reset code. Please try again later.'
                ]);
            }
        } else {
            echo json_encode([
                "status" => 'error',
                "message" => 'Failed to generate reset code. Please try again.'
            ]);
        }
    } else {
        echo json_encode([
            "status" => 'error',
            "message" => 'If your email is registered in our system, you will receive a reset code.'
        ]);
    }
    exit();
}

// Verify reset code
if (isset($_POST['verify-reset-code'])) {

    if (!isset($_POST['resetcode']) || empty(trim($_POST['resetcode']))) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Reset code is required.'
        ]);
        exit();
    }

    $code = trim($_POST['resetcode']);
    $USER = new User(NULL);

    try {
        $user_id = $USER->SelectResetCode($code);
        if ($user_id) {
            echo json_encode([
                'status' => 'success',
                'code' => $code,
                'user_id' => $user_id,
                'message' => 'Code verified successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'The code you entered is invalid or has expired.'
            ]);
        }
    } catch (Exception $e) {
        error_log('Reset code verification error: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while verifying the reset code.'
        ]);
    }
    exit();
}

// Update password with reset code
if (isset($_POST['update-password'])) {

    if (!isset($_POST['resetcode']) || empty(trim($_POST['resetcode']))) {
        echo json_encode([
            "status" => "error",
            "message" => "Reset code is required."
        ]);
        exit();
    }

    if (!isset($_POST['password']) || empty(trim($_POST['password']))) {
        echo json_encode([
            "status" => "error",
            "message" => "Password is required."
        ]);
        exit();
    }

    $code = trim($_POST['resetcode']);
    $password = trim($_POST['password']);

    // Validate password length
    if (strlen($password) < 6) {
        echo json_encode([
            "status" => "error",
            "message" => "Password must be at least 6 characters long."
        ]);
        exit();
    }

    $USER = new User(NULL);

    // First verify that the reset code is still valid
    $user_id = $USER->SelectResetCode($code);
    if (!$user_id) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid or expired reset code."
        ]);
        exit();
    }

    // Update the password and clear the reset code
    if ($USER->updatePassword($password, $code)) {
        // Clear the reset code after successful password update
        $USER->clearResetCode($code);

        echo json_encode([
            "status" => "success",
            "message" => "Password updated successfully. You can now login with your new password."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update password. Please try again."
        ]);
    }
    exit();
}
