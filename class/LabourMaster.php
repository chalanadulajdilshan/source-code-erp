<?php

class LabourMaster {
    public $id;
    public $code;
    public $name;
    public $type;
    public $is_active;

    // Constructor to initialize the labour object with an ID (fetch data from DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `code`, `name`, `type`, `is_active` FROM `labour_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->type = $result['type'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new labour record
    public function create()
    {
        $query = "INSERT INTO `labour_master` (`code`, `name`, `type`, `is_active`) VALUES (
            '" . $this->code . "',
            '" . $this->name . "',
            '" . $this->type . "',
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

    // Update existing labour record
    public function update()
    {
        $query = "UPDATE `labour_master` SET 
            `code` = '" . $this->code . "', 
            `name` = '" . $this->name . "', 
            `type` = '" . $this->type . "', 
            `is_active` = '" . $this->is_active . "' 
            WHERE `id` = '" . $this->id . "'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Delete labour record
    public function delete()
    {
        $query = "DELETE FROM `labour_master` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all labour records
    public function all()
    {
        $query = "SELECT * FROM `labour_master` ORDER BY id DESC";
       
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
        $query = "SELECT * FROM `labour_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

}

?>
