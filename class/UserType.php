<?php

/**
 * Description of Product
 *
 * 
 *  
 */
class UserType
{

    public $id;
    public $name;

    public function __construct($id)
    {
        if ($id) {

            $query = "SELECT  * FROM `user_type` WHERE `id`=" . $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->name = $result['name'];
        }
    }

    public function create()
    {

        $query = "INSERT INTO `user_type` (`name`) VALUES  ('"
            . $this->name . "')";

        $db = new Database();
        $result = $db->readQuery($query);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function update()
    {

        $query = "UPDATE  `user_type` SET "
            . "`name` ='" . $this->name . "' "
            . "WHERE `id` = '" . $this->id . "'";
 

        $db = new Database();
        $result = $db->readQuery($query);
        if ($result) {
            return $this->__construct($this->id);
        } else {
            return FALSE;
        }
    }

    public function getOrderDate()
    {
        $query = "SELECT * FROM `user_type` ORDER BY `date` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    public function all()
    {
        $query = "SELECT * FROM `user_type` ";
        
        
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    public function arrange($key, $img)
    {
        $query = "UPDATE `user_type` SET `queue` = '" . $key . "'  WHERE id = '" . $img . "'";
        $db = new Database();
        $result = $db->readQuery($query);
        return $result;
    }

    public function delete()
    {
        $query = 'DELETE FROM `user_type` WHERE id="' . $this->id . '"';
        $db = new Database();
        return $db->readQuery($query);
    }
}
