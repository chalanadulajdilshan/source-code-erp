<?php

class PurchaseOrder
{
    public $id;
    public $po_number;
    public $order_date;
    public $supplier_id;
    public $address;
    public $brand;
    public $invoice_no;
    public $country;
    public $department;
    public $purchase_date;
    public $grand_total;
    public $status;
    public $remarks;
    public $created_by;
    public $created_at;
    public $updated_at;

    // Constructor to initialize the PurchaseOrder object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `purchase_orders` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->po_number = $result['po_number'];
                $this->order_date = $result['order_date'];
                $this->supplier_id = $result['supplier_id'];
                $this->address = $result['address'];
                $this->brand = $result['brand'];
                $this->invoice_no = $result['invoice_no'];
                $this->country = $result['country'];
                $this->department = $result['department'];
                $this->purchase_date = $result['purchase_date'];
                $this->status = $result['status'];
                $this->remarks = $result['remarks'];
                $this->grand_total = $result['grand_total'];
                $this->created_by = $result['created_by'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new purchase order record
    public function create()
    {
        $query = "INSERT INTO `purchase_orders` (
            `po_number`, `order_date`, `supplier_id`, `address`, `brand`, `invoice_no`, `country`, `department`, `purchase_date`, `status`, `remarks`, `grand_total`, `created_by`, `created_at`
        ) VALUES (
            '{$this->po_number}', '{$this->order_date}', '{$this->supplier_id}', '{$this->address}', '{$this->brand}',
            '{$this->invoice_no}', '{$this->country}', '{$this->department}', '{$this->purchase_date}', 0, '{$this->remarks}', '{$this->grand_total}', '{$this->created_by}', '{$this->created_at}'
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
            `order_date` = '{$this->order_date}',
            `supplier_id` = '{$this->supplier_id}', 
            `address` = '{$this->address}', 
            `brand` = '{$this->brand}', 
            `invoice_no` = '{$this->invoice_no}',
            `country` = '{$this->country}',
            `department` = '{$this->department}',
            `purchase_date` = '{$this->purchase_date}',
            `status` = '{$this->status}',
            `remarks` = '{$this->remarks}', 
             `grand_total` = '{$this->grand_total}', 
            `created_by` = '{$this->created_by}', 
            `created_at` = '{$this->created_at}'    
            WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Delete a sales invoice record by ID
    public function delete()
    {
        PurchaseOrderItem::deleteByPurchaseOrderId($this->id);

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

    public function getAllByStatus($status)
    {
        $query = "SELECT * FROM `purchase_orders` where `status` = $status ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
    public function checkPurchaseIdExist($po_no)
    {
        $query = "SELECT * FROM `purchase_orders` where `po_number` = '$po_no'";


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
