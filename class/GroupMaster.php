<?php

class GroupMaster
{
    public $id;
    public $code;
    public $name;
    public $is_active;
    public $queue;
    public $created_at;
    public $updated_at;

    // Constructor to fetch data by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `group_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
                $this->queue = $result['queue'];
                $this->created_at = $result['created_at'];
                $this->updated_at = $result['updated_at'];
            }
        }
    }

    // Create a new record in the group_master table
    public function create()
    {
        $query = "INSERT INTO `group_master` (`code`, `name`, `is_active`, `queue`, `created_at`) 
                  VALUES (
                    '{$this->code}', 
                    '{$this->name}', 
                    '{$this->is_active}', 
                    '{$this->queue}', 
                    NOW()
                  )";
        $db = new Database();
        return $db->readQuery($query) ? mysqli_insert_id($db->DB_CON) : false;
    }

    // Update an existing group_master record
    public function update()
    {
        $query = "UPDATE `group_master` 
                  SET 
                    `code` = '{$this->code}', 
                    `name` = '{$this->name}', 
                    `is_active` = '{$this->is_active}', 
                    `queue` = '{$this->queue}', 
                    `updated_at` = NOW()
                  WHERE `id` = '{$this->id}'";
        
        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete a group_master record
    public function delete()
    {
        $query = "DELETE FROM `group_master` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all records from the group_master table
    public function all()
    {
        $query = "SELECT * FROM `group_master` ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    // Get active group_master records
    public function getActiveGroups()
    {
        $query = "SELECT * FROM `group_master` WHERE `is_active` = 1 ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function getLastID()
    {
        $query = "SELECT * FROM `group_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}
?>
