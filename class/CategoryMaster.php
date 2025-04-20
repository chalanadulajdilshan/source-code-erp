<?php

class CategoryMaster
{
    public $id;
    public $code;
    public $name;

    public $is_active;
    public $queue;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `category_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->is_active = $result['is_active'];
                $this->queue = $result['queue'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `category_master` (`code`,`name`, `is_active`) 
                  VALUES (
                    '{$this->code}',  
                    '{$this->name}', 
                    '{$this->is_active}'
                  )";
        $db = new Database();
        return $db->readQuery($query) ? mysqli_insert_id($db->DB_CON) : false;
    }

    public function update()
    {
        $query = "UPDATE `category_master` 
                  SET 
                    `name` = '{$this->name}', 
                    `is_active` = '{$this->is_active}'
                  WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function delete()
    {
        $query = "DELETE FROM `category_master` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `category_master` ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function getLastID()
    {
        $query = "SELECT * FROM `category_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
 
     public function getActiveCategory()
     {
         $query = "SELECT * FROM `category_master` WHERE `is_active` = 1 ORDER BY `queue` ASC";
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