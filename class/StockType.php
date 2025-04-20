<?php

class StockType
{
    public $id;
    public $name;
    public $description;
    public $is_active;
    public $created_at;

    // Constructor to initialize StockType object with ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `description`, `is_active`, `created_at` 
                      FROM `stock_type` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->description = $result['description'];
                $this->is_active = $result['is_active'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new stock type record
    public function create()
    {
        $query = "INSERT INTO `stock_type` (`name`, `description`, `is_active`, `created_at`) VALUES (
            '" . $this->name . "',
            '" . $this->description . "',
            '" . $this->is_active . "',
            NOW())";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return newly inserted ID
        } else {
            return false;
        }
    }

    // Update existing stock type
    public function update()
    {
        $query = "UPDATE `stock_type` SET 
                    `name` = '" . $this->name . "', 
                    `description` = '" . $this->description . "', 
                    `is_active` = '" . $this->is_active . "' 
                  WHERE `id` = '" . $this->id . "'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id); // Refresh with updated data
        } else {
            return false;
        }
    }

    // Delete stock type
    public function delete()
    {
        $query = "DELETE FROM `stock_type` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all stock types
    public function all()
    {
        $query = "SELECT * FROM `stock_type` ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
    
    public function getActiveStockType()
    {
        $query = "SELECT * FROM `stock_type` WHERE `is_active` = 1 ORDER BY `id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }
}

?>
