<?php

class PurchaseReturn
{
    public $id;
    public $ref_no;
    public $department_id;
    public $return_date;
    public $arn_id;
    public $supplier_id;
    public $total_amount;
    public $return_reason;
    public $created_by;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `purchase_return` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `purchase_return` (
            `ref_no`, `department_id`, `return_date`, `arn_id`, `supplier_id`, `total_amount`,
            `return_reason`, `created_by`, `created_at`
        ) VALUES (
            '{$this->ref_no}', '{$this->department_id}', '{$this->return_date}', '{$this->arn_id}', 
            '{$this->supplier_id}', '{$this->total_amount}', '{$this->return_reason}', 
            '{$this->created_by}', NOW()
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE `purchase_return` SET 
            `ref_no` = '{$this->ref_no}',
            `department_id` = '{$this->department_id}',
            `return_date` = '{$this->return_date}',
            `arn_id` = '{$this->arn_id}',
            `supplier_id` = '{$this->supplier_id}',
            `total_amount` = '{$this->total_amount}',
            `return_reason` = '{$this->return_reason}',
            `created_by` = '{$this->created_by}'
        WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM `purchase_return` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `purchase_return` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = [];
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getLastID()
    {
        $query = "SELECT `id` FROM `purchase_return` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}
?>
