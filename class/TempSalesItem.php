<?php

class TempSalesItem
{
    public $id;
    public $invoice_id;
    public $item_code;
    public $item_name;
    public $quantity;
    public $price;
     public $discount;
    public $total;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT  * 
                      FROM `temp_sales_items` 
                      WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->invoice_id = $result['invoice_id'];
                $this->item_code = $result['item_code'];
                $this->item_name = $result['product_id'];
                $this->quantity = $result['quantity'];                
                $this->discount = $result['discount'];
                $this->price = $result['price'];
                $this->total = $result['total'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {


        $query = "INSERT INTO `temp_sales_items` 
    (`invoice_id`, `item_code`, `item_name`, `price`, `discount`,`quantity`, `total`, `created_at`) 
    VALUES (
        '{$this->invoice_id}', 
        '{$this->item_code}', 
        '{$this->item_name}', 
        '{$this->price}', 
        '{$this->discount}', 
        '{$this->quantity}', 
        '{$this->total}',  
        NOW()
    )";



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
        $query = "UPDATE `temp_sales_items` SET 
             
            `item_code` = '{$this->item_code}', 
            `item_name` = '{$this->item_name}', 
            `price` = '{$this->price}', 
            `quantity` = '{$this->quantity}', 
            `total` = '{$this->total}' 
            WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM `temp_sales_items` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT  * 
                  FROM `temp_sales_items` 
                  ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getItemsByInvoiceId($invoice_id)
    {
        $query = "SELECT  * 
                  FROM `temp_sales_items` where `invoice_id` =  $invoice_id 
                  ORDER BY `id` DESC";
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