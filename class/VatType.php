<?php

class VatType
{
    public $id;
    public $vat_type_name;
    public $description;
    public $is_active;

    // Constructor to initialize the object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `vat_type_name`, `description`, `is_active` FROM `vat_types` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->vat_type_name = $result['vat_type_name'];
                $this->description = $result['description'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    // Create a new VAT type record
    public function create()
    {
        $query = "INSERT INTO `vat_types` (`vat_type_name`, `description`, `is_active`) VALUES (
            '" . $this->vat_type_name . "',
            '" . $this->description . "',
            '" . $this->is_active . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing VAT type record
    public function update()
    {
        $query = "UPDATE `vat_types` SET 
            `vat_type_name` = '" . $this->vat_type_name . "',
            `description` = '" . $this->description . "',
            `is_active` = '" . $this->is_active . "'
            WHERE `id` = " . (int)$this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id); // Reload object data
        } else {
            return false;
        }
    }

    // Delete a VAT type record by ID
    public function delete()
    {
        $query = "DELETE FROM `vat_types` WHERE `id` = " . (int)$this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all VAT types
    public function all()
    {
        $query = "SELECT * FROM `vat_types` ORDER BY `vat_type_name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Retrieve only active VAT types (optional)
    public function getActiveTypes()
    {
        $query = "SELECT * FROM `vat_types` WHERE `is_active` = 1 ORDER BY `vat_type_name` ASC";
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
