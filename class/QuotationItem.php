<?php

class QuotationItem
{
    public $id;
    public $quotation_id;
    public $item_code;
    public $item_name;
    public $price;
    public $qty;
    public $discount;
    public $sub_total;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `quotation_item` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->quotation_id = $result['quotation_id'];
                $this->item_code = $result['item_code'];
                $this->item_name = $result['item_name'];
                $this->price = $result['price'];
                $this->qty = $result['qty'];
                $this->discount = $result['discount'];
                $this->sub_total = $result['sub_total'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `quotation_item` 
                  (`quotation_id`, `item_code`, `item_name`, `price`, `qty`, `discount`, `sub_total`) 
                  VALUES 
                  ('" . $this->quotation_id . "', '" . $this->item_code . "', '" . $this->item_name . "', '" .
            $this->price . "', '" . $this->qty . "', '" . $this->discount . "', '" . $this->sub_total . "')";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE `quotation_item` SET 
                  `quotation_id` = '" . $this->quotation_id . "',
                  `item_code` = '" . $this->item_code . "',
                  `item_name` = '" . $this->item_name . "',
                  `price` = '" . $this->price . "',
                  `qty` = '" . $this->qty . "',
                  `discount` = '" . $this->discount . "',
                  `sub_total` = '" . $this->sub_total . "'
                  WHERE `id` = '" . $this->id . "'";

        $db = new Database();
        return $db->readQuery($query);
    }

    public function delete()
    {
        $query = "DELETE FROM `quotation_item` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `quotation_item` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


    public function checkQuotationItemExist($quotation_id, $item_code)
    {
        $db = new Database();
        $query = "SELECT id FROM `quotation_item` WHERE `quotation_id` = '{$quotation_id}' AND `item_code` = '{$item_code}'";
        $result = mysqli_fetch_array($db->readQuery($query));

        return ($result) ? $result['id'] : false;
    }

    public function getByQuotationId($quotation_id)
    {
        $query = "SELECT * FROM `quotation_item` WHERE `quotation_id` = '" . (int) $quotation_id . "'";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
}
