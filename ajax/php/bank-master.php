<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Prevent any output before JSON
ob_start();

// Create a new bank
if (isset($_POST['create'])) {
    try {
        $BANK = new Bank(NULL); // Create a new bank

        // Set the bank details
        $BANK->name = $_POST['name'];
        $BANK->code = $_POST['code'];

        // Attempt to create the bank
        $res = $BANK->create();

        // Clear any output buffer
        ob_clean();

        if ($res) {
            $result = [
                "status" => 'success'
            ];
            echo json_encode($result);
            exit();
        } else {
            $result = [
                "status" => 'error'
            ];
            echo json_encode($result);
            exit();
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            "status" => 'error',
            "message" => $e->getMessage()
        ]);
        exit();
    }
}

// Update bank details
if (isset($_POST['update'])) {
    try {
        $BANK = new Bank($_POST['id']); // Retrieve bank by ID

        // Update bank details
        $BANK->name = $_POST['name'];
        $BANK->code = $_POST['code'];

        // Attempt to update the bank
        $result = $BANK->update();

        // Clear any output buffer
        ob_clean();

        if ($result !== false) { // Check if update was successful
            $response = [
                "status" => 'success'
            ];
            echo json_encode($response);
            exit();
        } else {
            $response = [
                "status" => 'error'
            ];
            echo json_encode($response);
            exit();
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            "status" => 'error',
            "message" => $e->getMessage()
        ]);
        exit();
    }
}

// Delete bank
if (isset($_POST['delete']) && isset($_POST['id'])) {
    try {
        $bank = new Bank($_POST['id']);
        $result = $bank->delete();

        // Clear any output buffer
        ob_clean();

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            "status" => 'error',
            "message" => $e->getMessage()
        ]);
        exit();
    }
}

?>