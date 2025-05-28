<?php

class PurchaseOrder
{
    public $id;
    public $po_number;
    public $entry_date;
    public $supplier_id;
    public $pi_no;
    public $address;
    public $lc_tt_no;
    public $brand;
    public $bl_no;
    public $country;
    public $ci_no;
    public $department;
    public $order_by;
    public $remarks;
    public $created_by;
    public $created_at;
    public $updated_at;

    // Constructor to initialize the PurchaseOrder object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `purchase_orders` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->po_number = $result['po_number'];
                $this->entry_date = $result['entry_date'];
                $this->supplier_id = $result['supplier_id'];
                $this->pi_no = $result['pi_no'];
                $this->address = $result['address'];
                $this->lc_tt_no = $result['lc_tt_no'];
                $this->brand = $result['brand'];
                $this->bl_no = $result['bl_no'];
                $this->country = $result['country'];
                $this->department = $result['department'];
                $this->order_by = $result['order_by'];
                $this->remarks = $result['remarks'];
                $this->created_by = $result['created_by'];
                $this->created_at = $result['created_at'];
                $this->updated_at = $result['updated_at'];
            }
        }
    }

    // Create a new purchase order record
    public function create()
    {
        $query = "INSERT INTO `purchase_orders` (
            `po_number`, `entry_date`, `supplier_id`, `pi_no`, `address`, `lc_tt_no`, 
            `brand`, `bl_no`, `country`, `department`, `order_by`, `remarks`, `created_by`, `created_at`, `updated_at`
        ) VALUES (
            '{$this->po_number}', '{$this->entry_date}', '{$this->supplier_id}', '{$this->pi_no}', '{$this->address}', '{$this->lc_tt_no}','{$this->brand}',
            '{$this->bl_no}', '{$this->country}', '{$this->department}', '{$this->order_by}', '{$this->remarks}', '{$this->created_by}', '{$this->created_at}', '{$this->updated_at}'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing purchase order record
    public function update()
    {
        $query = "UPDATE `purchase_orders` SET 
            `po_number` = '{$this->po_number}', 
            `entry_date` = '{$this->entry_date}',
            `supplier_id` = '{$this->supplier_id}', 
            `pi_no` = '{$this->pi_no}', 
            `address` = '{$this->address}', 
            `lc_tt_no` = '{$this->lc_tt_no}', 
            `brand` = '{$this->brand}', 
            `bl_no` = '{$this->bl_no}',
            `country` = '{$this->country}',
            `department` = '{$this->department}',
            `order_by` = '{$this->order_by}',
            `remarks` = '{$this->remarks}', 
            `created_by` = '{$this->created_by}', 
            `created_at` = '{$this->created_at}', 
            `updated_at` = '{$this->updated_at}'
            WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a sales invoice record by ID
    public function delete()
    {
        $query = "DELETE FROM `purchase_orders` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all sales invoice records
    public function all()
    {
        $query = "SELECT * FROM `purchase_orders` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function checkPurchaseIdExist($quotation_no)
    {
        $query = "SELECT * FROM `quotation` where `quotation_no` = '$quotation_no' ";

        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        return ($result) ? true : false;
    }

    public function getLastID()
    {
        $query = "SELECT * FROM `purchase_orders` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
    
}
?>
