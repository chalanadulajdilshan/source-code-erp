<?php

class LabourType
{
    public $id;
    public $name;
    public $is_active;

    // Constructor to fetch by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `is_active` FROM `labour_types` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create new labour type
    public function create()
    {
        $query = "INSERT INTO `labour_types` (`name`, `is_active`) VALUES (
            '" . $this->name . "',
            '" . (int) $this->is_active . "')";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update existing labour type
    public function update()
    {
        $query = "UPDATE `labour_types` SET 
                    `name` = '" . $this->name . "',
                    `is_active` = '" . (int) $this->is_active . "'
                  WHERE `id` = '" . (int) $this->id . "'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete labour type
    public function delete()
    {
        $query = "DELETE FROM `labour_types` WHERE `id` = '" . (int) $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all labour types
    public function all()
    {
        $query = "SELECT * FROM `labour_types` ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get all active labour types
    public function getActiveLabourType()
    {
        $query = "SELECT * FROM `labour_types` where `is_active` = 1  ORDER BY `name` ASC";
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