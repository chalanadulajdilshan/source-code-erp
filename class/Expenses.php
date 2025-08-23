<?php

class Expense
{
    public $id;
    public $code;
    public $expense_type_id;
    public $expense_date;
    public $amount;
    public $remark;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `expenses` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                error_log('Found expense: ' . print_r($result, true));
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->expense_type_id = $result['expense_type_id'];
                $this->expense_date = $result['expense_date'];
                $this->amount = $result['amount'];
                $this->remark = $result['remark'];
            }
        }
    }

    public function create()
    {
        // Escape values to prevent SQL injection
        $code = mysqli_real_escape_string((new Database())->DB_CON, $this->code);
        $expense_type_id = (int) $this->expense_type_id;
        $expense_date = mysqli_real_escape_string((new Database())->DB_CON, $this->expense_date);
        $amount = (float) $this->amount;
        $remark = mysqli_real_escape_string((new Database())->DB_CON, $this->remark);

        $query = "INSERT INTO `expenses` (
            `code`, `expense_type_id`, `expense_date`, `amount`, `remark`
        ) VALUES (
            '$code', '$expense_type_id', '$expense_date', '$amount', '$remark'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            $insertId = mysqli_insert_id($db->DB_CON);
            error_log('Successfully created expense with ID: ' . $insertId);
            return $insertId;
        } else {
            $error = mysqli_error($db->DB_CON);
            error_log('Error creating expense: ' . $error);
            error_log('Query: ' . $query);
            return false;
        }
    }

    public function update()
    {
        // Escape values to prevent SQL injection
        $code = mysqli_real_escape_string((new Database())->DB_CON, $this->code);
        $expense_type_id = (int) $this->expense_type_id;
        $expense_date = mysqli_real_escape_string((new Database())->DB_CON, $this->expense_date);
        $amount = (float) $this->amount;
        $remark = mysqli_real_escape_string((new Database())->DB_CON, $this->remark);
        $id = (int) $this->id;

        $query = "UPDATE `expenses` SET 
            `code` = '$code', 
            `expense_type_id` = '$expense_type_id',
            `expense_date` = '$expense_date',
            `amount` = '$amount',
            `remark` = '$remark'
            WHERE `id` = '$id'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            error_log('Successfully updated expense with ID: ' . $id);
            return $result;
        } else {
            $error = mysqli_error($db->DB_CON);
            error_log('Error updating expense: ' . $error);
            return false;
        }
    }

    public function delete()
    {
        $id = (int) $this->id;
        $query = "DELETE FROM `expenses` WHERE `id` = '$id'";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            error_log('Successfully deleted expense with ID: ' . $id);
            return $result;
        } else {
            $error = mysqli_error($db->DB_CON);
            error_log('Error deleting expense: ' . $error);
            return false;
        }
    }

    public function all()
    {
        // Fixed ORDER BY clause - removed 'name' which doesn't exist in expenses table
        $query = "SELECT * FROM `expenses` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getLastID()
    {
        $query = "SELECT * FROM `expenses` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        // Handle case when no records exist
        if ($result && isset($result['id'])) {
            return $result['id'];
        } else {
            return 0; // Return 0 if no records found
        }
    }

    public function getTotalExpenseAmount($dateFrom = null, $dateTo = null)
    {
        error_log('Getting total expense amount');

        $where = "WHERE 1=1"; // Better WHERE clause construction

        if ($dateFrom) {
            $dateFrom = mysqli_real_escape_string((new Database())->DB_CON, $dateFrom);
            $where .= " AND expense_date >= '$dateFrom'";
        }
        if ($dateTo) {
            $dateTo = mysqli_real_escape_string((new Database())->DB_CON, $dateTo);
            $where .= " AND expense_date <= '$dateTo'";
        }

        $query = "SELECT SUM(amount) as total_amount FROM `expenses` $where";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        $totalAmount = $result['total_amount'] ? $result['total_amount'] : 0;

        error_log('Total amount found: ' . $totalAmount);
        return $totalAmount;
    }

    public function getExpensesByType($expense_type_id)
    {
        error_log('Getting expenses by type: ' . $expense_type_id);

        $expense_type_id = (int) $expense_type_id;
        $query = "SELECT * FROM `expenses` WHERE `expense_type_id` = '$expense_type_id' ORDER BY expense_date DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        error_log('Returning ' . count($array) . ' expenses');
        return $array;
    }

    public function getExpensesByDateRange($dateFrom, $dateTo)
    {
        error_log('Getting expenses by date range: ' . $dateFrom . ' - ' . $dateTo);

        $dateFrom = mysqli_real_escape_string((new Database())->DB_CON, $dateFrom);
        $dateTo = mysqli_real_escape_string((new Database())->DB_CON, $dateTo);

        $query = "SELECT e.*, et.name as expense_type_name 
                 FROM `expenses` e 
                 LEFT JOIN `expenses_type` et ON e.expense_type_id = et.id 
                 WHERE e.expense_date BETWEEN '$dateFrom' AND '$dateTo' 
                 ORDER BY e.expense_date DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        error_log('Returning ' . count($array) . ' expenses');
        return $array;
    }

    public function getTotalExpensesByDateRange($dateFrom, $dateTo)
    {
        error_log('Getting total expenses from ' . $dateFrom . ' to ' . $dateTo);

        $dateFrom = mysqli_real_escape_string((new Database())->DB_CON, $dateFrom);
        $dateTo = mysqli_real_escape_string((new Database())->DB_CON, $dateTo);

        $query = "SELECT COALESCE(SUM(amount), 0) as total_expenses 
                 FROM `expenses` 
                 WHERE expense_date BETWEEN '$dateFrom' AND '$dateTo'";

        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        $totalExpenses = isset($result['total_expenses']) ? (float)$result['total_expenses'] : 0;

        error_log('Total expenses found: ' . $totalExpenses);
        return $totalExpenses;
    }
}
