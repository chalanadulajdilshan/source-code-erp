<?php

class TempSalesItem
{
    public $id;
    public $temp_invoice_id;
    public $product_id;
    public $product_name;
    public $quantity;
    public $price;
    public $total;
    public $user_id;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT  * 
                      FROM `temp_sales_items` 
                      WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->temp_invoice_id = $result['temp_invoice_id'];
                $this->product_id = $result['product_id'];
                $this->product_name = $result['product_name'];
                $this->quantity = $result['quantity'];
                $this->price = $result['price'];
                $this->total = $result['total'];
                $this->user_id = $result['user_id'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `temp_sales_items` 
            (`temp_invoice_id`, `product_id`, `product_name`, `price`, `quantity`, `total`, `user_id`, `created_at`) 
            VALUES (
                '{$this->temp_invoice_id}', 
                '{$this->product_id}', 
                '{$this->product_name}', 
                '{$this->price}', 
                '{$this->quantity}', 
                '{$this->total}', 
                '{$this->user_id}', 
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
            `temp_invoice_id` = '{$this->temp_invoice_id}', 
            `product_id` = '{$this->product_id}', 
            `product_name` = '{$this->product_name}', 
            `price` = '{$this->price}', 
            `quantity` = '{$this->quantity}', 
            `total` = '{$this->total}', 
            `user_id` = '{$this->user_id}' 
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
}
?>
