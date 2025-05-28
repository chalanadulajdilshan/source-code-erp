<?php

class StockMaster
{
    public $id;
    public $item_id;
    public $department_id;
    public $quantity;
    public $created_at;
    public $is_active;
    public $remark;

    // Constructor to initialize StockMaster object with ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `item_id`, `department_id`, `quantity`, `created_at`, `is_active`, `remark` 
                      FROM `stock_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->item_id = $result['item_id'];
                $this->department_id = $result['department_id'];
                $this->quantity = $result['quantity'];
                $this->created_at = $result['created_at'];
                $this->is_active = $result['is_active'];
                $this->remark = $result['remark'];
            }
        }
    }

    // Create a new stock_master record
    public function create()
    {
        $query = "INSERT INTO `stock_master` (`item_id`, `department_id`, `quantity`, `created_at`, `is_active`, `remark`) VALUES (
            '" . $this->item_id . "',
            '" . $this->department_id . "',
            '" . $this->quantity . "',
            NOW(),
            '" . $this->is_active . "',
            '" . $this->remark . "')";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update existing stock_master record
    public function update()
    {
        $query = "UPDATE `stock_master` SET 
                    `item_id` = '" . $this->item_id . "',
                    `department_id` = '" . $this->department_id . "',
                    `quantity` = '" . $this->quantity . "',
                    `is_active` = '" . $this->is_active . "',
                    `remark` = '" . $this->remark . "'
                  WHERE `id` = '" . $this->id . "'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a stock_master record
    public function delete()
    {
        $query = "DELETE FROM `stock_master` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all records
    public function all()
    {
        $query = "SELECT `id`, `item_id`, `department_id`, `quantity`, `created_at`, `is_active`, `remark` 
                  FROM `stock_master` ORDER BY `created_at` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get only active records
    public function getActive()
    {
        $query = "SELECT `id`, `item_id`, `department_id`, `quantity`, `created_at`, `is_active`, `remark` 
                  FROM `stock_master` WHERE `is_active` = 1 ORDER BY `created_at` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    // Get available quantity for item + department
    public static function getAvailableQuantity($department_id, $item_id)
    {
        $query = "SELECT `quantity` FROM `stock_master` 
                  WHERE `department_id` = " . (int) $department_id . " 
                  AND `item_id` = " . (int) $item_id . " 
                  AND `is_active` = 1 LIMIT 1";

        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result ? $result['quantity'] : 0;
    }

    public function transferQuantity($item_id, $from_department_id, $to_department_id, $transfer_qty, $remark = '')
    {

        $FROM_DEPARTMENT = new DepartmentMaster($from_department_id);
        $TO_DEPARTMENT = new DepartmentMaster($to_department_id);

        $db = new Database();

        // 1. Check available quantity in from_department
        $queryFrom = "SELECT * FROM `stock_master` 
                  WHERE `item_id` = '" . (int) $item_id . "' 
                    AND `department_id` = '" . (int) $from_department_id . "' 
                    AND `is_active` = 1
                  LIMIT 1";


        $resultFrom = mysqli_fetch_assoc($db->readQuery($queryFrom));

        if (!$resultFrom) {
            return ['status' => 'error', 'message' => 'No stock found in source department.'];
        }

        if ($resultFrom['quantity'] < $transfer_qty) {
            return ['status' => 'error', 'message' => 'Insufficient quantity in source department.'];
        }

        // 2. Deduct quantity from source department
        $newQtyFrom = $resultFrom['quantity'] - $transfer_qty;

        $updateFrom = "UPDATE `stock_master` SET `quantity` = '" . (int) $newQtyFrom . "', `remark` = '" . $remark . "' WHERE `id` = '" . (int) $resultFrom['id'] . "'";


        $STOCK_TRANSACTION_OUT = new StockTransaction(NULL);
        $STOCK_TRANSACTION_OUT->item_id = $item_id;
        $STOCK_TRANSACTION_OUT->type = 9; // deduction
        $STOCK_TRANSACTION_OUT->date = date('Y-m-d');
        $STOCK_TRANSACTION_OUT->qty_out = $transfer_qty;
        $STOCK_TRANSACTION_OUT->qty_in = 0;
        $STOCK_TRANSACTION_OUT->remark = 'Quantity deducted to ' . $FROM_DEPARTMENT->name . ' to ' . $TO_DEPARTMENT->name;
        $STOCK_TRANSACTION_OUT->create();

        $db->readQuery($updateFrom);

        // 3. Check if item exists in target department
        $queryTo = "SELECT * FROM `stock_master` 
                WHERE `item_id` = '" . (int) $item_id . "' 
                  AND `department_id` = '" . (int) $to_department_id . "' 
                  AND `is_active` = 1
                LIMIT 1";
        $resultTo = mysqli_fetch_assoc($db->readQuery($queryTo));

        if ($resultTo) {
            // Exists: Update quantity
            $newQtyTo = $resultTo['quantity'] + $transfer_qty;
            $updateTo = "UPDATE `stock_master` SET `quantity` = '" . (int) $newQtyTo . "', `remark` = '" . $remark . "' WHERE `id` = '" . (int) $resultTo['id'] . "'";
            $db->readQuery($updateTo);

            // Create transaction for addition
            $STOCK_TRANSACTION_IN = new StockTransaction(NULL);
            $STOCK_TRANSACTION_IN->item_id = $item_id;
            $STOCK_TRANSACTION_IN->type = 8; // addition
            $STOCK_TRANSACTION_IN->date = date('Y-m-d');
            $STOCK_TRANSACTION_IN->qty_in = $transfer_qty;
            $STOCK_TRANSACTION_IN->qty_out = 0;
            $STOCK_TRANSACTION_IN->remark = 'Quantity added to ' . $TO_DEPARTMENT->name . ' From ' . $FROM_DEPARTMENT->name;
            $STOCK_TRANSACTION_IN->create();

        } else {
            // Not exists: Insert new record
            $insert = "INSERT INTO `stock_master` (`item_id`, `department_id`, `quantity`, `is_active`, `remark`, `created_at`)
                   VALUES ('" . (int) $item_id . "', '" . (int) $to_department_id . "', '" . (int) $transfer_qty . "', 1, '" . $remark . "', NOW())";
            $db->readQuery($insert);

            $STOCK_TRANSACTION_IN = new StockTransaction(NULL);
            $STOCK_TRANSACTION_IN->item_id = $item_id;
            $STOCK_TRANSACTION_IN->type = 8; // addition
            $STOCK_TRANSACTION_IN->date = date('Y-m-d');
            $STOCK_TRANSACTION_IN->qty_in = $transfer_qty;
            $STOCK_TRANSACTION_IN->qty_out = 0;
            $STOCK_TRANSACTION_IN->remark = 'New department added with quantity';
            $STOCK_TRANSACTION_IN->create();
        }

        return ['status' => 'success', 'message' => 'Stock transferred successfully.'];
    }

}

?>