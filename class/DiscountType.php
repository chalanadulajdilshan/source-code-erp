<?php

class DiscountType
{

    public $id;
    public $code;
    public $name;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `discount_types` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
            }
        }
    }

    public function all()
    {
        $query = "SELECT * FROM `discount_types` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
    

    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `discount_types` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }
    
    

}

?>