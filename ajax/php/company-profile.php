<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

if (isset($_POST['create'])) {
    $COMPANY = new CompanyProfile();

    // Upload logo image
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/company-logos/';
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
        $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid file type. Only PNG, JPG, JPEG, and WEBP are allowed.'
            ];
            echo json_encode($response);
            exit();
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $randomFileName = uniqid('logo_', true) . '.' . $fileExt;
        $uploadPath = $uploadDir . $randomFileName;

        // Move uploaded file
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
            $COMPANY->image_name = $randomFileName;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to upload logo.'
            ];
            echo json_encode($response);
            exit();
        }
    } else {
        $COMPANY->image_name = null;
    }

    // Assign form data with fallback
    $COMPANY->company_code = $_POST['company_code'] ?? '';
    $COMPANY->name = isset($_POST['name']) ? ucwords(strtolower(trim($_POST['name']))) : '';
    $COMPANY->address = isset($_POST['address']) ? ucwords(strtolower(trim($_POST['address']))) : '';
    $COMPANY->mobile_number_1 = $_POST['mobile_number_1'] ?? '';
    $COMPANY->mobile_number_2 = $_POST['mobile_number_2'] ?? '';
    $COMPANY->mobile_number_3 = $_POST['mobile_number_3'] ?? '';
    $COMPANY->email = $_POST['email'] ?? '';
    $COMPANY->vat_number = $_POST['vat_number'] ?? '';
    $COMPANY->is_active = isset($_POST['is_active']) ? 1 : 0;
    $COMPANY->is_vat = isset($_POST['is_vat']) ? 1 : 0;

    // Save to DB
    $result = $COMPANY->create();

    if ($result) {
        $response = [
            'status' => 'success',
            'message' => 'Company profile saved successfully.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Failed to save company profile.'
        ];
    }

    echo json_encode($response);
    exit();
}

if (isset($_POST['update'])) {

    $COMPANY = new CompanyProfile($_POST['company_id']);

    // Handle image upload if a new logo is uploaded
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/company-logos/';
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
        $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid file type. Only PNG, JPG, JPEG, and WEBP are allowed.'
            ];
            echo json_encode($response);
            exit();
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $randomFileName = uniqid('logo_', true) . '.' . $fileExt;
        $uploadPath = $uploadDir . $randomFileName;

        // Move uploaded file
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
            $COMPANY->image_name = $randomFileName;  // Update the image name with the new logo
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to upload logo.'
            ];
            echo json_encode($response);
            exit();
        }
    } else {
        // Keep the existing image if no new file is uploaded
        $COMPANY->image_name = $_POST['image_name']; // Existing image name
    }

    // Assign form data with fallback
    $COMPANY->company_code = $_POST['company_code'] ?? '';
    $COMPANY->name = isset($_POST['name']) ? ucwords(strtolower(trim($_POST['name']))) : '';
    $COMPANY->address = isset($_POST['address']) ? ucwords(strtolower(trim($_POST['address']))) : '';
    $COMPANY->mobile_number_1 = $_POST['mobile_number_1'] ?? '';
    $COMPANY->mobile_number_2 = $_POST['mobile_number_2'] ?? '';
    $COMPANY->mobile_number_3 = $_POST['mobile_number_3'] ?? '';
    $COMPANY->email = $_POST['email'] ?? '';
    $COMPANY->vat_number = $_POST['vat_number'] ?? '';
    $COMPANY->is_active = isset($_POST['is_active']) ? 1 : 0;
    $COMPANY->is_vat = isset($_POST['is_vat']) ? 1 : 0;

    // Save to DB
    $result = $COMPANY->update();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }

    echo json_encode($response);
    exit();
}


if (isset($_POST['delete'])) {

    $COMPANY = new CompanyProfile($_POST['id']);

    $result = $COMPANY->delete();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}
?>