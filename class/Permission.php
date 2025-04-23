<?php

class Permission
{
    public $id;
    public $permission_name;

    // Constructor to initialize the Permission object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `permission_name` FROM `permissions` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->permission_name = $result['permission_name'];
            }
        }
    }

    // Create a new permission record in the database
    public function create()
    {
        $query = "INSERT INTO `permissions` (`permission_name`) VALUES ('" . $this->permission_name . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing permission record
    public function update()
    {
        $query = "UPDATE `permissions` SET `permission_name` = '" . $this->permission_name . "' WHERE `id` = " . (int) $this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id); // Refresh the object with updated data
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a permission record by ID
    public function delete()
    {
        $query = "DELETE FROM `permissions` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all permissions
    public function all()
    {
        $query = "SELECT `id`, `permission_name` FROM `permissions` ORDER BY `permission_name` ASC";
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
