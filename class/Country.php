<?php

class Country
{
    public $id;
    public $code;
    public $name;
    public $created_at;

    // Constructor to initialize the country object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `country` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new country record in the database
    public function create()
    {
        $query = "INSERT INTO `country` (`code`,`name`,`is_active`,`created_at`) VALUES ('" .
            $this->code . "', '" .
            $this->name . "', '" . 
            $this->is_active . "', NOW())";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing country record
    public function update()
    {
        $query = "UPDATE `country` SET 
            `name` = '" . $this->name . "',
            `is_active` = '" . $this->is_active . "'
            WHERE `id` = " . (int) $this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true; // Refresh the object with updated data
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a country record by ID
    public function delete()
    {
        $query = "DELETE FROM `country` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all countries
    public function all()
    {
        $query = "SELECT * FROM `country` ORDER BY id ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get countries by a specific type (if any)
    public function getCountriesByType($type)
    {
        $query = 'SELECT * FROM `country` WHERE `type` = "' . $type . '" ORDER BY name ASC';
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
        $query = "SELECT * FROM `country` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function activeCountry()
    {
        $query = "SELECT * FROM `country` WHERE is_active = 1 ORDER BY name ASC";
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