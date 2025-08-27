<?php

class NonPermissionPage
{
    public $id;
    public $page;
    public $is_active;

    // Constructor to initialize the object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `non_permission_pages` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->page = $result['page'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new record
    public function create()
    {
        $query = "INSERT INTO `non_permission_pages` (`page`, `is_active`) VALUES (
            '" . $this->page . "',
            " . (int)$this->is_active . "
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return new ID
        } else {
            return false;
        }
    }

    // Update existing record
    public function update()
    {
        $query = "UPDATE `non_permission_pages` SET
            `page` = '" . $this->page . "',
            `is_active` = " . (int)$this->is_active . "
            WHERE `id` = " . (int)$this->id;

        $db = new Database();
        return $db->readQuery($query) ? true : false;
    }

    // Retrieve all records
    public function all()
    {
        $query = "SELECT * FROM `non_permission_pages` ORDER BY `id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
}
