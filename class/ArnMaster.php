<?php

class ArnMaster
{
    public $id;
    public $arn_no;
    public $lc_tt_no;
    public $pi_no;
    public $po_date;
    public $supplier_id;
    public $ci_no;
    public $bl_no;
    public $container_size;
    public $brand;
    public $department;
    public $po_no;
    public $country;
    public $order_by;
    public $purchase_type;
    public $arn_status;
    public $remark;
    public $invoice_date;
    public $entry_date;
    public $sub_arn_value;
    public $total_discount;
    public $total_arn_value;
    public $total_received_qty;
    public $total_order_qty;
    public $created_at;

    // Constructor to initialize the ARN object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM  `arn_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    // Create a new ARN record
    public function create()
    {
        $query = "INSERT INTO  `arn_master` (
            `arn_no`, `lc_tt_no`, `pi_no`, `po_date`, `supplier_id`, `ci_no`, `bl_no`,
            `container_size`, `brand`, `department`, `po_no`, `country`, `order_by`,
            `purchase_type`, `arn_status`, `remark`, `invoice_date`, `entry_date`,
            `sub_arn_value`, `total_discount`, `total_arn_value`, `total_received_qty`,
            `total_order_qty`, `created_at`
        ) VALUES (
            '{$this->arn_no}', '{$this->lc_tt_no}', '{$this->pi_no}', '{$this->po_date}', '{$this->supplier_id}',
            '{$this->ci_no}', '{$this->bl_no}', '{$this->container_size}', '{$this->brand}', '{$this->department}',
            '{$this->po_no}', '{$this->country}', '{$this->order_by}', '{$this->purchase_type}', '{$this->arn_status}',
            '{$this->remark}', '{$this->invoice_date}', '{$this->entry_date}', '{$this->sub_arn_value}',
            '{$this->total_discount}', '{$this->total_arn_value}', '{$this->total_received_qty}',
            '{$this->total_order_qty}', NOW()
        )";
     

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing ARN record
    public function update()
    {
        $query = "UPDATE  `arn_master` SET
            `arn_no` = '{$this->arn_no}',
            `lc_tt_no` = '{$this->lc_tt_no}',
            `pi_no` = '{$this->pi_no}',
            `po_date` = '{$this->po_date}',
            `supplier_id` = '{$this->supplier_id}',
            `ci_no` = '{$this->ci_no}',
            `bl_no` = '{$this->bl_no}',
            `container_size` = '{$this->container_size}',
            `brand` = '{$this->brand}',
            `department` = '{$this->department}',
            `po_no` = '{$this->po_no}',
            `country` = '{$this->country}',
            `order_by` = '{$this->order_by}',
            `purchase_type` = '{$this->purchase_type}',
            `arn_status` = '{$this->arn_status}',
            `remark` = '{$this->remark}',
            `invoice_date` = '{$this->invoice_date}',
            `entry_date` = '{$this->entry_date}',
            `sub_arn_value` = '{$this->sub_arn_value}',
            `total_discount` = '{$this->total_discount}',
            `total_arn_value` = '{$this->total_arn_value}',
            `total_received_qty` = '{$this->total_received_qty}',
            `total_order_qty` = '{$this->total_order_qty}'
        WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete an ARN record by ID
    public function delete()
    {
        $query = "DELETE FROM  `arn_master` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Fetch all ARN records
    public function all()
    {
        $query = "SELECT * FROM  `arn_master` ORDER BY `id` DESC";
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
        $query = "SELECT * FROM  `arn_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}

?>