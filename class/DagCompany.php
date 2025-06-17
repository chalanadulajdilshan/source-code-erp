<?php

class DagCompany
{
    public $id;
    public $name;
    public $code;
    public $address;
    public $contact_person;
    public $phone_number;
    public $email;
    public $is_active;
    public $remark;
    public $created_at;

    // Constructor to fetch data by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `code`, `address`, `contact_person`, `phone_number`, `email`, `is_active`, `remark`, `created_at` 
                      FROM `dag_company` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));
            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->code = $result['code'];
                $this->address = $result['address'];
                $this->contact_person = $result['contact_person'];
                $this->phone_number = $result['phone_number'];
                $this->email = $result['email'];
                $this->is_active = $result['is_active'];
                $this->remark = $result['remark'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new record
    public function create()
    {
        $query = "INSERT INTO `dag_company` (`name`, `code`, `address`, `contact_person`, `phone_number`, `email`, `is_active`, `remark`, `created_at`)
                  VALUES (
                    '{$this->name}', '{$this->code}', '{$this->address}', '{$this->contact_person}', 
                    '{$this->phone_number}', '{$this->email}', '{$this->is_active}', '{$this->remark}', NOW()
                  )";
        $db = new Database();
        $result = $db->readQuery($query);
        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        }
        return false;
    }

    // Update existing record
    public function update()
    {
        $query = "UPDATE `dag_company` SET
                  `name` = '{$this->name}',
                  `code` = '{$this->code}',
                  `address` = '{$this->address}',
                  `contact_person` = '{$this->contact_person}',
                  `phone_number` = '{$this->phone_number}',
                  `email` = '{$this->email}',
                  `is_active` = '{$this->is_active}',
                  `remark` = '{$this->remark}'
                  WHERE `id` = '{$this->id}'";

        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete record
    public function delete()
    {
        $query = "DELETE FROM `dag_company` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all records
    public function all()
    {
        $query = "SELECT * FROM `dag_company` ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getByStatusCompany($id)
    {
        $query = "SELECT * FROM `dag_company` WHERE `is_active` = $id ORDER BY `name` ASC";
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