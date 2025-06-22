<?php

class StockTransaction
{
    public $id;
    public $item_id;
    public $type;      // New column 'type'
    public $date;
    public $qty_in;
    public $qty_out;
    public $remark;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT id, item_id, type, date, qty_in, qty_out, remark, created_at FROM stock_transaction WHERE id = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->item_id = $result['item_id'];
                $this->type = $result['type'];      // assign new column
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
            (item_id, type, date, qty_in, qty_out, remark, created_at) 
            VALUES (
                '{$this->item_id}', 
                '{$this->type}', 
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
            type = '{$this->type}', 
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
        $query = "SELECT id, item_id, type, date, qty_in, qty_out, remark, created_at FROM stock_transaction ORDER BY id DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getAvailableQuantityByDepartment($department_id, $item_id, $days = 0, $date_from = null, $date_to = null)
    {
        $db = new Database();

        $conditions = "
        sm.department_id = '{$department_id}' 
        AND st.item_id = '{$item_id}'
    ";

        if (!empty($date_from) && !empty($date_to)) {
            $conditions .= " AND st.date BETWEEN '{$date_from} 00:00:00' AND '{$date_to} 23:59:59'";
        } elseif ($days > 0) {
            $conditions .= " AND st.date >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY)";
        }

        $query = "
        SELECT 
            SUM(st.qty_in) AS total_in, 
            SUM(st.qty_out) AS total_out
        FROM stock_transaction st
        INNER JOIN stock_master sm ON st.item_id = sm.item_id
        WHERE {$conditions}
    ";


        $result = mysqli_fetch_assoc($db->readQuery($query));

        if ($result) {
            $total_in = (float) $result['total_in'];
            $total_out = (float) $result['total_out'];
            return $total_in - $total_out;
        }

        return 0;
    }

    public function getTransactionRecords($department_id, $item_id, $date_from, $date_to)
    {
        $db = new Database();

        $date_from_escaped = $date_from;
        $date_to_escaped = $date_to;

        $query = "
    SELECT 
        st.id,
        st.item_id,
        sat.name AS type_name,
        sat.type_direction AS type_direction,
        st.date,
        st.qty_in,
        st.qty_out,
        st.remark,
        st.created_at
    FROM stock_transaction st
    INNER JOIN stock_master sm ON st.item_id = sm.item_id
    INNER JOIN stock_adjustment_type sat ON st.type = sat.id
    WHERE sm.department_id = '{$department_id}'
    AND st.item_id = '{$item_id}'
    AND st.date BETWEEN '{$date_from_escaped} 00:00:00' AND '{$date_to_escaped} 23:59:59'
    ORDER BY st.created_at ASC
";

        $result = $db->readQuery($query);

        $transactions = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = $row;
            }
        }

        return $transactions;
    }



}
?>