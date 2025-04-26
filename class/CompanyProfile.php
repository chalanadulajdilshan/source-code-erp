<?php

class CompanyProfile
{
    public $id;
    public $name;
    public $address;
    public $mobile_number_1;
    public $mobile_number_2;
    public $mobile_number_3;
    public $email;
    public $image_name;
    public $is_active;
    public $is_vat;
    public $vat_number;
    public $vat_percentage;

    public $company_code;

    // Constructor to load data by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `company_profile` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->address = $result['address'];
                $this->mobile_number_1 = $result['mobile_number_1'];
                $this->mobile_number_2 = $result['mobile_number_2'];
                $this->mobile_number_3 = $result['mobile_number_3'];
                $this->email = $result['email'];
                $this->image_name = $result['image_name'];
                $this->is_active = $result['is_active'];
                $this->is_vat = $result['is_vat'];
                $this->vat_number = $result['vat_number'];
                $this->vat_percentage = $result['vat_percentage']; 
                $this->company_code = $result['company_code'];
            }
        }
    }

    // Method to create a new company profile
    public function create()
    {
        $query = "INSERT INTO `company_profile` (`name`, `address`, `mobile_number_1`, `mobile_number_2`, `mobile_number_3`, `email`, `image_name`, `is_active`, `is_vat`, `vat_number`, `company_code`) 
                  VALUES (
                    '{$this->name}', 
                    '{$this->address}', 
                    '{$this->mobile_number_1}', 
                    '{$this->mobile_number_2}', 
                    '{$this->mobile_number_3}', 
                    '{$this->email}', 
                    '{$this->image_name}', 
                    '{$this->is_active}',
                    '{$this->is_vat}',
                    '{$this->vat_number}',
                    '{$this->company_code}'
                  )";
        $db = new Database();
        return $db->readQuery($query) ? mysqli_insert_id($db->DB_CON) : false;
    }

    // Method to update an existing company profile
    public function update()
    {
        $query = "UPDATE `company_profile` 
                  SET 
                    `name` = '{$this->name}', 
                    `address` = '{$this->address}', 
                    `mobile_number_1` = '{$this->mobile_number_1}', 
                    `mobile_number_2` = '{$this->mobile_number_2}', 
                    `mobile_number_3` = '{$this->mobile_number_3}', 
                    `email` = '{$this->email}', 
                    `image_name` = '{$this->image_name}', 
                    `is_active` = '{$this->is_active}',
                    `is_vat` = '{$this->is_vat}',
                    `vat_number` = '{$this->vat_number}',
                    `company_code` = '{$this->company_code}'
                  WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Method to delete the company profile
    public function delete()
    {
        $query = "DELETE FROM `company_profile` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Method to get all company profiles
    public function all()
    {
        $query = "SELECT * FROM `company_profile` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function getActiveCompany()
    {
        $query = "SELECT * FROM `company_profile` WHERE `is_active` = 1 ORDER BY `id` ASC";
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
