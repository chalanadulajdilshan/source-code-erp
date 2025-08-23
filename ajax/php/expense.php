<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all POST data for debugging
error_log('POST data: ' . print_r($_POST, true));

// Create a new expense
if (isset($_POST['create'])) {
    try {
        // Validate required fields
        if (empty($_POST['code']) || empty($_POST['expense_type']) || empty($_POST['expense_date']) || empty($_POST['amount'])) {
            echo json_encode([
                "status" => 'error',
                "message" => 'Missing required fields'
            ]);
            exit();
        }

        $EXPENSE = new Expense(NULL); // Create a new expense

        // Set the expense details - Note: using 'expense_type' from form, not 'expense_type_id'
        $EXPENSE->code = $_POST['code'];
        $EXPENSE->expense_type_id = $_POST['expense_type']; // This should match the form field name
        $EXPENSE->expense_date = $_POST['expense_date'];
        $EXPENSE->amount = $_POST['amount'];
        $EXPENSE->remark = isset($_POST['remark']) ? $_POST['remark'] : '';

        // Log the expense object for debugging
        error_log('Creating expense with data: ' . print_r($EXPENSE, true));

        // Attempt to create the expense
        $res = $EXPENSE->create();

        if ($res) {
            echo json_encode([
                "status" => 'success',
                "message" => 'Expense created successfully',
                "id" => $res
            ]);
        } else {
            echo json_encode([
                "status" => 'error',
                "message" => 'Failed to create expense'
            ]);
        }
    } catch (Exception $e) {
        error_log('Exception in create: ' . $e->getMessage());
        echo json_encode([
            "status" => 'error',
            "message" => 'Exception: ' . $e->getMessage()
        ]);
    }
    exit();
}

// Update expense details
if (isset($_POST['update'])) {
    try {
        // Validate required fields
        if (empty($_POST['id']) || empty($_POST['code']) || empty($_POST['expense_type']) || empty($_POST['expense_date']) || empty($_POST['amount'])) {
            echo json_encode([
                "status" => 'error',
                "message" => 'Missing required fields for update'
            ]);
            exit();
        }

        $EXPENSE = new Expense($_POST['id']); // Retrieve expense by ID

        // Check if expense exists
        if (!$EXPENSE->id) {
            echo json_encode([
                "status" => 'error',
                "message" => 'Expense not found'
            ]);
            exit();
        }

        // Update expense details
        $EXPENSE->code = $_POST['code'];
        $EXPENSE->expense_type_id = $_POST['expense_type']; // This should match the form field name
        $EXPENSE->expense_date = $_POST['expense_date'];
        $EXPENSE->amount = $_POST['amount'];
        $EXPENSE->remark = isset($_POST['remark']) ? $_POST['remark'] : '';

        // Log the expense object for debugging
        error_log('Updating expense with data: ' . print_r($EXPENSE, true));

        // Attempt to update the expense
        $result = $EXPENSE->update();

        if ($result) {
            echo json_encode([
                "status" => 'success',
                "message" => 'Expense updated successfully'
            ]);
        } else {
            echo json_encode([
                "status" => 'error',
                "message" => 'Failed to update expense'
            ]);
        }
    } catch (Exception $e) {
        error_log('Exception in update: ' . $e->getMessage());
        echo json_encode([
            "status" => 'error',
            "message" => 'Exception: ' . $e->getMessage()
        ]);
    }
    exit();
}

// Delete expense
if (isset($_POST['delete']) && isset($_POST['id'])) {
    try {
        if (empty($_POST['id'])) {
            echo json_encode([
                "status" => 'error',
                "message" => 'No expense ID provided'
            ]);
            exit();
        }

        $expense = new Expense($_POST['id']);

        // Check if expense exists
        if (!$expense->id) {
            echo json_encode([
                "status" => 'error',
                "message" => 'Expense not found'
            ]);
            exit();
        }

        $result = $expense->delete();

        if ($result) {
            echo json_encode([
                "status" => 'success',
                "message" => 'Expense deleted successfully'
            ]);
        } else {
            echo json_encode([
                "status" => 'error',
                "message" => 'Failed to delete expense'
            ]);
        }
    } catch (Exception $e) {
        error_log('Exception in delete: ' . $e->getMessage());
        echo json_encode([
            "status" => 'error',
            "message" => 'Exception: ' . $e->getMessage()
        ]);
    }
    exit();
}

// If no valid action is found
echo json_encode([
    "status" => 'error',
    "message" => 'No valid action specified'
]);
