<?php

class PurchaseType {

    public $id;
    public $title;
    public $queue;

    public function __construct($id) {
        if ($id) {
            $query = "SELECT * FROM `purchase_type` WHERE `id` = " . $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->title = $result['title'];
            $this->queue = $result['queue'];

            return $this;
        }
    }

    public function create() {
        $query = "INSERT INTO `purchase_type` (`title`, `queue`) VALUES ('"
            . $this->title . "', '"
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
        $query = "SELECT * FROM `purchase_type` ORDER BY queue ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    public function update() {
        $query = "UPDATE `purchase_type` SET "
            . "`title` = '" . $this->title . "' "
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
        $query = "DELETE FROM `purchase_type` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function arrange($key, $img) {
        $query = "UPDATE `purchase_type` SET `queue` = '" . $key . "' WHERE `id` = '" . $img . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function getActivitiesByTitle($title) {
        $query = "SELECT `id` FROM `purchase_type` WHERE `title` LIKE '" . $title . "'";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}
