<?php
class StockAdjustmentType
{
    public $id;
    public $name;
    public $description;
    public $type_direction; // e.g., 'IN' or 'OUT'
    public $is_active;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM stock_adjustment_type WHERE id = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->description = $result['description'];
                $this->type_direction = $result['type_direction'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    public function all()
    {
        $query = "SELECT * FROM stock_adjustment_type WHERE is_active = 1 ORDER BY id ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }
}
