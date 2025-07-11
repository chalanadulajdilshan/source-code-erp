<?php

class DagItem
{
    public $id;
    public $dag_id;
    public $vehicle_no;
    public $belt_id;
    public $barcode;
    public $casing_cost;
    public $qty;
    public $total_amount;

    // Constructor to fetch data by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT *
                      FROM `dag_item` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));
            if ($result) {
                $this->id = $result['id'];
                $this->dag_id = $result['dag_id'];
                $this->vehicle_no = $result['vehicle_no'];
                $this->belt_id = $result['belt_id'];
                $this->barcode = $result['barcode'];
                $this->casing_cost = $result['casing_cost'];
                $this->qty = $result['qty'];
                $this->total_amount = $result['total_amount'];
            }
        }
    }

    // Create a new record
    public function create()
    {
        $query = "INSERT INTO `dag_item` (`dag_id`, `vehicle_no`, `belt_id`, `barcode`,`casing_cost`, `qty`, `total_amount`)
                  VALUES (
                    '{$this->dag_id}', '{$this->vehicle_no}', '{$this->belt_id}', '{$this->barcode}', '{$this->casing_cost}', 
                    '{$this->qty}', '{$this->total_amount}'
                  )";

        $db = new Database();
        $result = $db->readQuery($query);
        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        }
        return false;
    }

    // Update existing record
    public function update()
    {
        $query = "UPDATE `dag_item` SET
                  `dag_id` = '{$this->dag_id}',
                  `vehicle_no` = '{$this->vehicle_no}',
                  `belt_id` = '{$this->belt_id}',
                  `barcode` = '{$this->barcode}',
                   `casing_cost` = '{$this->casing_cost}',
                  `qty` = '{$this->qty}',
                  `total_amount` = '{$this->total_amount}'
                  WHERE `id` = '{$this->id}'";

        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete record
    public function delete()
    {
        $query = "DELETE FROM `dag_item` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete record
    public function deleteDagItemByItemId($id)
    {
        $query = "DELETE FROM `dag_item` WHERE `dag_id` = $id";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all records
    public function all()
    {
        $query = "SELECT * FROM `dag_item` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get items by dag_id
    public function getByDagId($dag_id)
    {
        $query = "SELECT * FROM `dag_item` WHERE `dag_id` = '{$dag_id}' ORDER BY `id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getByValuesDagId($dag_id)
    {
        $query = "SELECT di.*, bm.name AS belt_title 
              FROM `dag_item` di 
              LEFT JOIN `belt_master` bm ON di.belt_id = bm.id 
              WHERE di.dag_id = '{$dag_id}' 
              ORDER BY di.id ASC";

        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

}
?>