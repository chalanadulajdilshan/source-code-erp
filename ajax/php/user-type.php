<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new user type
if (isset($_POST['create'])) {
    $userType = new UserType(NULL);  

    
    $userType->name = $_POST['name'];
    $userType->is_active = isset($_POST['is_active']) ? 1 : 0; 

    // Attempt to create the user type
    $res = $userType->create();

    if ($res) {
        $result = [
            "status" => 'success',
            "message" => "User Type created successfully."
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error',
            "message" => "Failed to create User Type."
        ];
        echo json_encode($result);
        exit();
    }
}

// Update user type details
if (isset($_POST['update'])) {
    $userType = new UserType($_POST['user_type_id']); // Retrieve user type by ID

    // Update user type details
    $userType->name = $_POST['name'];
    $userType->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the user type
    $result = $userType->update();

    if ($result) {
        $result = [
            "status" => 'success',
            "message" => "User Type updated successfully."
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error',
            "message" => "Failed to update User Type."
        ];
        echo json_encode($result);
        exit();
    }
}

// Delete a user type
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $userType = new UserType($_POST['id']);
    $result = $userType->delete(); // Ensure this method exists in your UserType class

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'User Type deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete User Type.']);
    }
}

?>
