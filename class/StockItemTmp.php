<?php

class StockItemTmp
{
    public $id;
    public $arn_id;
    public $item_id;
    public $qty;
    public $cost;
    public $cash_price;
    public $credit_price;
    public $created_at;
    public $status;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `stock_item_tmp` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `stock_item_tmp` (
            `arn_id`, `item_id`, `qty`, `cost`, `cash_price`, `credit_price`, `created_at`
        ) VALUES (
            '{$this->arn_id}', '{$this->item_id}', '{$this->qty}', '{$this->cost}',
            '{$this->cash_price}', '{$this->credit_price}', NOW()
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
        $query = "UPDATE `stock_item_tmp` SET
            `arn_id` = '{$this->arn_id}',
            `item_id` = '{$this->item_id}',
            `qty` = '{$this->qty}',
            `cost` = '{$this->cost}',
            `cash_price` = '{$this->cash_price}',
            `credit_price` = '{$this->credit_price}'
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
        $query = "DELETE FROM `stock_item_tmp` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `stock_item_tmp` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getByArnId($arn_id)
    {
        $query = "SELECT * FROM `stock_item_tmp` WHERE `arn_id` = '" . (int) $arn_id . "'";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
    public function getByItemId($id)
    {
        $query = "SELECT * FROM `stock_item_tmp` WHERE `item_id` = '" . (int) $id . "'";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function updateStockItemTmpPrice($id, $field, $value)
    {
        $allowedFields = ['cost', 'cash_price', 'credit_price', 'cash_dis', 'credit_dis'];

        if (!in_array($field, $allowedFields)) {
            return ['error' => 'Invalid field'];
        }

        if (!is_numeric($value)) {
            return ['error' => 'Value must be numeric'];
        }

        $value = floatval($value);

        if (in_array($field, ['cash_dis', 'credit_dis']) && ($value < 0 || $value > 100)) {
            return ['error' => 'Discount must be between 0 and 100'];
        }

        $db = new Database();
        $value = mysqli_real_escape_string($db->DB_CON, $value);
        $id = (int) $id;

        $query = "UPDATE `stock_item_tmp` SET `$field` = '$value' WHERE `id` = $id";

        $result = $db->readQuery($query);

        if ($result) {
            return ['success' => true];
        } else {
            return ['error' => 'Database update failed'];
        }
    }




}

?>