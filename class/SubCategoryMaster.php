<?php

class Subcategory
{
    public $id;
    public $code;
    public $category_id;
    public $name;
    public $is_active;

    // Constructor to initialize the category object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `sub_categroy` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->category_id = $result['category_id'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new category record in the database
    public function create()
    {
        $query = "INSERT INTO `sub_categroy` (`code`, `category_id`, `name`, `is_active`) VALUES (
            '" . $this->code . "',
            '" . $this->category_id . "',
            '" . $this->name . "',
            '" . $this->is_active . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing category record
    public function update()
    {
        $query = "UPDATE `sub_categroy` SET 
            `code` = '" . $this->code . "',
            `category_id` = '" . $this->category_id . "',
            `name` = '" . $this->name . "',
            `is_active` = '" . $this->is_active . "'
            WHERE `id` = " . (int) $this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true; // Return true if the update is successful
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a category record by ID
    public function delete()
    {
        $query = "DELETE FROM `sub_categroy` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all category records
    public function all()
    {
        $query = "SELECT * FROM `sub_categroy` ORDER BY `code` ASC";
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
        $query = "SELECT * FROM `sub_categroy` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

}
?>
