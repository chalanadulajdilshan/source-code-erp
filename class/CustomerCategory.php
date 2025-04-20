<?php

class CustomerCategory
{
    public $id;
    public $name;
    public $is_active;

    // Constructor to initialize the customer category object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `is_active` FROM `customer_category` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new customer category record in the database
    public function create()
    {
        $query = "INSERT INTO `customer_category` (`name`, `is_active`) VALUES ('" .
            $this->name . "', '" . $this->is_active . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing customer category record
    public function update()
    {
        $query = "UPDATE `customer_category` SET `name` = '" . $this->name . "', `is_active` = '" .
            $this->is_active . "' WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id); // Refresh the object with updated data
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a customer category record by ID
    public function delete()
    {
        $query = "DELETE FROM `customer_category` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all customer category records
    public function all()
    {
        $query = "SELECT * FROM `customer_category` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function activeCategory()
    {
        $query = "SELECT * FROM `customer_category` WHERE is_active = 1 ORDER BY id ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

}

?>
