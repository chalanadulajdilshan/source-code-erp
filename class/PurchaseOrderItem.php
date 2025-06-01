<?php

class PurchaseOrderItem
{
    public $id;
    public $purchase_order_id;
    public $item_id;
    public $quantity;
    public $unit_price;
    public $total_price;


    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `purchase_order_items` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->purchase_order_id = $result['purchase_order_id'];
                $this->item_id = $result['item_id'];
                $this->quantity = $result['quantity'];
                $this->unit_price = $result['unit_price'];
                $this->total_price = $result['total_price'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `purchase_order_items` 
                  (`purchase_order_id`, `item_id`, `quantity`, `unit_price`, `total_price`) 
                  VALUES 
                  ('" . $this->purchase_order_id . "', '" . $this->item_id . "', '" . $this->quantity . "', '" .
            $this->unit_price . "', '" . $this->total_price . "')";

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
        $query = "UPDATE `purchase_order_items` SET 
                  `purchase_order_id` = '" . $this->purchase_order_id . "',
                  `item_id` = '" . $this->item_id . "',
                  `quantity` = '" . $this->quantity . "',
                  `unit_price` = '" . $this->unit_price . "',
                  `total_price` = '" . $this->total_price . "'";

        $db = new Database();
        return $db->readQuery($query);
    }

    public function delete()
    {
        $query = "DELETE FROM `purchase_order_items` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }



    public static function deleteByPurchaseOrderId($purchaseOrderId)
    {
        $query = "DELETE FROM `purchase_order_items` WHERE `purchase_order_id` = '" . intval($purchaseOrderId) . "'";
        $db = new Database();
        return $db->readQuery($query);
    }


    public function all()
    {
        $query = "SELECT * FROM `purchase_order_items` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


    public function checkPurchaseOrderIdExist($purchase_order_id, $item_id)
    {
        $db = new Database();
        $query = "SELECT id FROM `purchase_order_items` WHERE `purchase_order_id` = '{$purchase_order_id}' AND `item_id` = '{$item_id}'";
        $result = mysqli_fetch_array($db->readQuery($query));

        return ($result) ? $result['id'] : false;
    }

    public function getByPurchaseOrderId($item_id, $status = null)
    {
        $query = "SELECT * FROM `purchase_order_items` WHERE `purchase_order_id` = '" . (int) $item_id . "'";
        if ($status !== null) {
            // Assuming the purchase_order_items table has a 'status' column
            $query .= " AND `status` = '" . $status . "'";
        }

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