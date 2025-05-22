<?php

class SalesType
{
    public $id;
    public $code;
    public $name;
    public $is_active;

    // Constructor: load record by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `sales_type` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new sales_type record
    public function create()
    {
        $query = "INSERT INTO `sales_type` (`code`, `name`, `is_active`) VALUES (
            '" . $this->code . "',
            '" . $this->name . "',
            '" . $this->is_active . "'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing sales_type record
    public function update()
    {
        $query = "UPDATE `sales_type` SET 
            `code`      = '" . $this->code . "',
            `name`      = '" . $this->name . "',
            `is_active` = '" . $this->is_active . "'
            WHERE `id` = " . (int) $this->id;

        $db = new Database();
        $result = $db->readQuery($query);

        return $result ? true : false;
    }

    // Delete a sales_type record by ID
    public function delete()
    {
        $query = "DELETE FROM `sales_type` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all sales_type records
    public function all()
    {
        $query = "SELECT * FROM `sales_type` ORDER BY `code` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Retrieve only active sales types
    public function getSalesTypeByStatus($status)
    {
        $query = "SELECT * FROM `sales_type` WHERE `is_active` = $status ORDER BY `id` ASC";
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