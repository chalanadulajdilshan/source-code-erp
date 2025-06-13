<?php

class Branch
{
    public $id;
    public $bank_id;
    public $name;
    public $code;
    public $address;
    public $phone_number;
    public $city;
    public $remark;

    public $active_status;

    public $created_at;

    // Constructor to initialize the branch object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT *
                      FROM `branches` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->bank_id = $result['bank_id'];
                $this->name = $result['name'];
                $this->code = $result['code'];
                $this->address = $result['address'];
                $this->phone_number = $result['phone_number'];
                $this->city = $result['city'];
                $this->created_at = $result['created_at'];
                $this->remark = $result['remark'];
                $this->active_status = $result['active_status'];


            }
        }
    }

    // Create a new branch record in the database
    public function create()
    {
        $query = "INSERT INTO `branches` (`bank_id`, `name`, `code`, `address`, `phone_number`, `city`, active_status,remark,`created_at`) 
                  VALUES ('" . $this->bank_id . "', '" . $this->name . "', '" . $this->code . "', '" .
            $this->address . "', '" . $this->phone_number . "', '" . $this->city . "', '" . $this->active_status . "', '" . $this->remark . "', NOW())";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing branch record
    public function update()
    {
        $query = "UPDATE `branches` SET 
        `bank_id` = '" . $this->bank_id . "',
        `name` = '" . $this->name . "', 
        `code` = '" . $this->code . "',
        `address` = '" . $this->address . "',
        `phone_number` = '" . $this->phone_number . "', 
        `active_status` = '" . $this->active_status . "', 
        `city` = '" . $this->city .
            "' WHERE `id` = '" . $this->id . "'";



        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true; // Refresh the object with updated data
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a branch record by ID
    public function delete()
    {
        $query = "DELETE FROM `branches` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all branches from the database
    public function all()
    {
        $query = "SELECT *   FROM `branches` ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

        public function getByStatus($status)
    {
        $query = "SELECT *   FROM `branches` WHERE `active_status` = $status ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


    // Get all branches for a specific bank
    public function getByBankId($bank_id)
    {
        $query = "SELECT `id`, `bank_id`, `name`, `code`, `address`, `phone_number`, `city`, `created_at` 
                  FROM `branches` WHERE `bank_id` = '" . (int) $bank_id . "' ORDER BY `name` ASC";
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