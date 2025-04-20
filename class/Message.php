<?php

/**
 * Description of Message
 *
 * @author official
 */
class Message {

    public $id;
    public $status;
    public $description;

    public function __construct($id) {
        if ($id) {

            $query = "SELECT `id`,`status`,`description` FROM `message` WHERE `id`=" . $id;

            $db = new Database();

            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->status = $result['status'];
            $this->description = $result['description'];

            return $result;
        }
    }

}
