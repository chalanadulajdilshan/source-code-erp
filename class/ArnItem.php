<?php

class ArnItem
{
    public $id;
    public $arn_id;
    public $item_code;
    public $order_qty;
    public $received_qty;
    public $commercial_cost;
    public $discount_1;
    public $discount_2;
    public $discount_3;
    public $final_cost;
    public $unit_total;
    public $list_price;
    public $cash_price;
    public $credit_price;
    public $vat_percent;
    public $vat_value;
    public $margin_percent;
    public $created_at;
    public $updated_at;

    public function __construct($id = NULL)
    {
        if ($id) {
            $db = new Database();
            $query = "SELECT * FROM `arn_items` WHERE `id` = '$id'";
            $result = $db->readQuery($query);
            if ($row = mysqli_fetch_assoc($result)) {
                $this->id = $row['id'];
                $this->arn_id = $row['arn_id'];
                $this->item_code = $row['item_code'];
                $this->order_qty = $row['order_qty'];
                $this->received_qty = $row['received_qty'];
                $this->commercial_cost = $row['commercial_cost'];
                $this->discount_1 = $row['discount_1'];
                $this->discount_2 = $row['discount_2'];
                $this->discount_3 = $row['discount_3'];
                $this->final_cost = $row['final_cost'];
                $this->unit_total = $row['unit_total'];
                $this->list_price = $row['list_price'];
                $this->cash_price = $row['cash_price'];
                $this->credit_price = $row['credit_price'];
                $this->vat_percent = $row['vat_percent'];
                $this->vat_value = $row['vat_value'];
                $this->margin_percent = $row['margin_percent'];
                $this->created_at = $row['created_at'];
                $this->updated_at = $row['updated_at'];
            }
        }
    }

    public function create()
    {
        $db = new Database();
        $query = "INSERT INTO `arn_items` (
            `arn_id`, `item_code`, `order_qty`, `received_qty`, `commercial_cost`,
            `discount_1`, `discount_2`, `discount_3`, `final_cost`, `unit_total`,
            `list_price`, `cash_price`, `credit_price`, `vat_percent`, `vat_value`,
            `margin_percent`, `created_at`
        ) VALUES (
            '{$this->arn_id}', '{$this->item_code}', '{$this->order_qty}', '{$this->received_qty}', '{$this->commercial_cost}',
            '{$this->discount_1}', '{$this->discount_2}', '{$this->discount_3}', '{$this->final_cost}', '{$this->unit_total}',
            '{$this->list_price}', '{$this->cash_price}', '{$this->credit_price}', '{$this->vat_percent}', '{$this->vat_value}',
            '{$this->margin_percent}', NOW()
        )";

        $result = $db->readQuery($query);
        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    public static function all()
    {
        $db = new Database();
        $query = "SELECT * FROM `arn_items`";
        $result = $db->readQuery($query);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function delete()
    {
        $db = new Database();
        $query = "DELETE FROM `arn_items` WHERE `id` = '{$this->id}'";
        return $db->readQuery($query);
    }

    public function update()
    {
        $db = new Database();
        $query = "UPDATE `arn_items` SET 
            `order_qty` = '{$this->order_qty}',
            `received_qty` = '{$this->received_qty}',
            `commercial_cost` = '{$this->commercial_cost}',
            `discount_1` = '{$this->discount_1}',
            `discount_2` = '{$this->discount_2}',
            `discount_3` = '{$this->discount_3}',
            `final_cost` = '{$this->final_cost}',
            `unit_total` = '{$this->unit_total}',
            `list_price` = '{$this->list_price}',
            `cash_price` = '{$this->cash_price}',
            `credit_price` = '{$this->credit_price}',
            `vat_percent` = '{$this->vat_percent}',
            `vat_value` = '{$this->vat_value}',
            `margin_percent` = '{$this->margin_percent}',
            `updated_at` = NOW()
        WHERE `id` = '{$this->id}'";

        return $db->readQuery($query);
    }
}
?>