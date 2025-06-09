<?php

class PageCategory
{
    public $id;
    public $name;
    public $icon;
    public $is_active;
    public $queue;


    // Constructor to initialize the PageCategory object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `page_categories` WHERE `id` = " . (int) $id . " ORDER BY `queue`";
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->icon = $result['icon'];
                $this->queue = $result['queue'];
            }
        }
    }

    // Create a new page category record in the database
    public function create()
    {
        $query = "INSERT INTO `page_categories` (`name`, `icon`, `is_active`) VALUES (
            '" . $this->name . "', 
            " . ($this->icon !== null ? $this->icon : "NULL") . ", 
            " . (int) $this->is_active . ")";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing page category record
    public function update()
    {
        $query = "UPDATE `page_categories` SET 
            `name` = '" . $this->name . "', 
            `icon` = " . ($this->icon !== null ? $this->icon : "NULL") . ", 
            `is_active` = " . (int) $this->is_active . " 
            WHERE `id` = " . (int) $this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id); // Refresh the object with updated data
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a page category record by ID
    public function delete()
    {
        $query = "DELETE FROM `page_categories` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all page categories
    public function all()
    {
        $query = "SELECT `id`, `name`, `icon`, `is_active` FROM `page_categories` ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Retrieve all main categories (categories without a parent)
    public function getMainCategories()
    {
        $query = "SELECT `id`, `name`, `is_active` FROM `page_categories` WHERE `icon` IS NULL ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Retrieve all subcategories for a given parent category ID
    public function getActiveCategory()
    {
        $query = "SELECT * FROM `page_categories` WHERE `is_active` = 1 ORDER BY `queue` ASC";
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