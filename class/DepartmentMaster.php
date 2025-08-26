<?php

class DepartmentMaster
{
    public $id;
    public $code;
    public $name;
    public $remark;
    public $is_active;

    // Constructor to load department by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `department_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->remark = $result['remark'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new department
    public function create()
    {
        $query = "INSERT INTO `department_master` (`code`, `name`, `remark`, `is_active`) 
                  VALUES ('" . $this->code . "', '" . $this->name . "', '" . $this->remark . "', '" . $this->is_active . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        return $result ? mysqli_insert_id($db->DB_CON) : false;
    }

    // Update existing department
    public function update()
    {
        $query = "UPDATE `department_master` SET 
                  `code` = '" . $this->code . "', 
                  `name` = '" . $this->name . "', 
                  `remark` = '" . $this->remark . "', 
                  `is_active` = '" . $this->is_active . "' 
                  WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete department
    public function delete()
    {
        $query = "DELETE FROM `department_master` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Fetch all departments
    public function all()
    {
        $query = "SELECT * FROM `department_master` ORDER BY `name` ASC";
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
        $query = "SELECT * FROM `department_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function getActiveDepartment()
    {
        $query = "SELECT * FROM `department_master` WHERE `is_active` = 1 ORDER BY `id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function getByID($id)
    {
        $db = new Database();

        $query = "SELECT * FROM `department_master` WHERE `id` = '$id'";
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }   

}
?>
