<?php

class Bank
{

    public $id;
    public $name;
    public $code;
    public $created_at;

    // Constructor to initialize the bank object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `code`, `created_at` FROM `banks` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->code = $result['code'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new bank record in the database
    public function create()
    {
        $query = "INSERT INTO `banks` (`name`, `code`, `created_at`) VALUES ('" .
            $this->name . "', '" . $this->code . "', NOW())";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing bank record
    public function update()
    {
        $query = "UPDATE `banks` SET 
            `name` = '$this->name',
            `code` = '$this->code'
            WHERE `id` = '$this->id'";
 

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Delete a bank record by ID
    public function delete()
    {
        $query = "DELETE FROM `banks` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }


    public function all()
    {

        $query = "SELECT * FROM `banks` ORDER BY name ASC";
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