<?php

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */

/**

 * Description of name

 *

 * @author sourcecode.lk

 */
class Province {

    public $id;
    public $name; 
    public $queue;

    public function __construct($id) {

        if ($id) {


            $query = "SELECT * FROM `provinces` WHERE `id`=" . $id;

            $db = new Database();

            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->name = $result['name'];  
            $this->queue = $result['queue'];


            return $this;
        }
    }

    public function create() {

        $query = "INSERT INTO `provinces` (`name`,`queue`) VALUES  ('"
                . $this->name . "','" 
                . $this->queue . "')";

        $db = new Database();

        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return FALSE;
        }
    }

    public function all() {


        $query = "SELECT * FROM `provinces` ORDER BY queue ASC";

        $db = new Database();

        $result = $db->readQuery($query);

        $array_res = array();


        while ($row = mysqli_fetch_array($result)) {

            array_push($array_res, $row);
        }



        return $array_res;
    }

    public function update() {

        $query = "UPDATE  `provinces` SET "
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

    public function delete() {

       

        $query = 'DELETE FROM `provinces` WHERE id="' . $this->id . '"';



        $db = new Database();



        return $db->readQuery($query);
    }

    public function arrange($key, $img) {

        $query = "UPDATE `provinces` SET `queue` = '" . $key . "'  WHERE id = '" . $img . "'";

        $db = new Database();

        $result = $db->readQuery($query);

        return $result;
    }

    public function getActivitiesByTitle($name) {

        $query = "SELECT `id` FROM `provinces` WHERE `name` LIKE '" . $name . "'";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

}
