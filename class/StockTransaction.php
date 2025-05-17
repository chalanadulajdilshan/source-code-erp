<?php

class StockTransaction
{
    public $id;
    public $item_id; 
     public $date;
    public $qty_in;
    public $qty_out;
    public $remark;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM stock_transaction WHERE id = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->item_id = $result['item_id']; 
                 $this->date = $result['date'];
                $this->qty_in = $result['qty_in'];
                $this->qty_out = $result['qty_out'];
                $this->remark = $result['remark'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO stock_transaction 
            (item_id,date, qty_in, qty_out, remark, created_at) 
            VALUES (
                '{$this->item_id}',  
                 '{$this->date}', 
                '{$this->qty_in}', 
                '{$this->qty_out}', 
                '{$this->remark}', 
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
        $query = "UPDATE stock_transaction SET 
            item_id = '{$this->item_id}',  
             date = '{$this->date}', 
            qty_in = '{$this->qty_in}', 
            qty_out = '{$this->qty_out}', 
            remark = '{$this->remark}' 
            WHERE id = '{$this->id}'";

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
        $query = "DELETE FROM stock_transaction WHERE id = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM stock_transaction ORDER BY id DESC";
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
