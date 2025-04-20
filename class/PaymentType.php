<?php

class PaymentType
{
    public $id;
    public $name;
    public $queue;

    // Constructor to initialize the object by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `queue` FROM `payment_type` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->queue = $result['queue'];
            }
        }
    }

    // Create a new payment type record
    public function create()
    {
        $query = "INSERT INTO `payment_type` (`name`, `queue`) VALUES (
                    '" . $this->name . "', 
                    '" . $this->queue . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing payment type record
    public function update()
    {
        $query = "UPDATE `payment_type` SET 
                    `name` = '" . $this->name . "', 
                    `queue` = '" . $this->queue . "' 
                  WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a payment type record
    public function delete()
    {
        $query = "DELETE FROM `payment_type` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all payment type records
    public function all()
    {
        $query = "SELECT * FROM `payment_type` ORDER BY `queue` ASC";
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
