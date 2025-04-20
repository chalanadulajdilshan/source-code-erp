<?php

class BrandCategory
{
    public $id;
    public $name;
    public $queue;

    // Constructor to fetch brand category by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `name`, `queue` FROM `brand_category` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->queue = $result['queue'];
            }
        }
    }

    // Create new brand category
    public function create()
    {
        $query = "INSERT INTO `brand_category` (`name`, `queue`) VALUES (
            '" . $this->name . "',
            '" . (int) $this->queue . "'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update existing brand category
    public function update()
    {
        $query = "UPDATE `brand_category` SET
            `name` = '" . $this->name . "',
            `queue` = '" . (int) $this->queue . "'
            WHERE `id` = '" . (int) $this->id . "'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete brand category
    public function delete()
    {
        $query = "DELETE FROM `brand_category` WHERE `id` = '" . (int) $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all categories
    public function all()
    {
        $query = "SELECT * FROM `brand_category` ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Optional: Get by name
    public function getByName($name)
    {
        $query = "SELECT * FROM `brand_category` WHERE `name` = '" . $name . "'";
        $db = new Database();
        $result = $db->readQuery($query);
        return mysqli_fetch_array($result);
    }
}
