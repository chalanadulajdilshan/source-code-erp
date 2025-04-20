<?php

class ItemMaster
{

    public $id;
    public $code;
    public $name;
    public $brand;
    public $size;
    public $pattern;
    public $group;
    public $category;
    public $cost;
    public $re_order_level;
    public $re_order_qty;
    public $whole_sale_price;
    public $retail_price;
    public $cash_discount;
    public $credit_discount;
    public $stock_type;
    public $note;
    public $is_active;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `item_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->brand = $result['brand'];
                $this->size = $result['size'];
                $this->pattern = $result['pattern'];
                $this->group = $result['group'];
                $this->category = $result['category'];
                $this->cost = $result['cost'];
                $this->re_order_level = $result['re_order_level'];
                $this->re_order_qty = $result['re_order_qty'];
                $this->whole_sale_price = $result['whole_sale_price'];
                $this->retail_price = $result['retail_price'];
                $this->cash_discount = $result['cash_discount'];
                $this->credit_discount = $result['credit_discount'];
                $this->stock_type = $result['stock_type'];
                $this->note = $result['note'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `item_master` (
            `code`, `name`, `brand`, `size`, `pattern`, `group`, `category`, 
            `cost`, `re_order_level`, `re_order_qty`, `whole_sale_price`, 
            `retail_price`, `cash_discount`, `credit_discount`, `stock_type`, `note`, `is_active`
        ) VALUES (
            '$this->code', '$this->name', '$this->brand', '$this->size', '$this->pattern', '$this->group',
            '$this->category', '$this->cost', '$this->re_order_level', '$this->re_order_qty',
            '$this->whole_sale_price', '$this->retail_price', '$this->cash_discount',
            '$this->credit_discount', '$this->stock_type', '$this->note', '$this->is_active'
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
        $query = "UPDATE `item_master` SET 
            `code` = '$this->code', 
            `name` = '$this->name', 
            `brand` = '$this->brand', 
            `size` = '$this->size', 
            `pattern` = '$this->pattern', 
            `group` = '$this->group', 
            `category` = '$this->category', 
            `cost` = '$this->cost', 
            `re_order_level` = '$this->re_order_level', 
            `re_order_qty` = '$this->re_order_qty', 
            `whole_sale_price` = '$this->whole_sale_price', 
            `retail_price` = '$this->retail_price', 
            `cash_discount` = '$this->cash_discount', 
            `credit_discount` = '$this->credit_discount', 
            `stock_type` = '$this->stock_type', 
            `note` = '$this->note', 
            `is_active` = '$this->is_active'
            WHERE `id` = '$this->id'";
 

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM `item_master` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `item_master` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // You can change this method name/logic based on your real use case
    public function getItemsByCategory($category_id)
    {
        $query = "SELECT * FROM `item_master` WHERE `category` = '$category_id' ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


    public function getLastID()
    {
        $query = "SELECT * FROM `item_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function fetchForDataTable($request)
    {
        $db = new Database();
    
        $start = isset($request['start']) ? (int)$request['start'] : 0;
        $length = isset($request['length']) ? (int)$request['length'] : 100;
        $search = $request['search']['value'] ?? '';
    
        $status = $request['status'] ?? null;
        $stockOnly = isset($request['stock_only']) ? filter_var($request['stock_only'], FILTER_VALIDATE_BOOLEAN) : false;
    
        $where = "WHERE 1=1";
    
        // Search filter
        if (!empty($search)) {
            $where .= " AND (name LIKE '%$search%' OR code LIKE '%$search%')";
        }
    
        // Status filter
        if (!empty($status)) {
            if ($status === 'active' || $status === '1' || $status === 1) {
                $where .= " AND is_active = 1";
            } elseif ($status === 'inactive' || $status === '0' || $status === 0) {
                $where .= " AND is_active = 0";
            }
        }
        
    
        // Stock only filter
        if ($stockOnly) {
            $where .= " AND stock_type = 1"; 
        }
    
        // Total records
        $totalSql = "SELECT * FROM item_master";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);
    
        // Filtered records
        $filteredSql = "SELECT * FROM item_master $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);
    
        // Paginated query
        $sql = "$filteredSql LIMIT $start, $length";
        $dataQuery = $db->readQuery($sql);
    
        $data = [];
    
        while ($row = mysqli_fetch_assoc($dataQuery)) {
            $CATEGORY = new CategoryMaster($row['category']);
            $BRAND = new Brand($row['brand']);
    
            $nestedData = [
                "id" => $row['id'],
                "code" => $row['code'],
                "name" => $row['name'],
                "pattern" => $row['pattern'],
                "size" => $row['size'],
                "group" => $row['group'],
                "re_order_level" => $row['re_order_level'],
                "re_order_qty" => $row['re_order_qty'],
                "brand_id" => $row['brand'],
                "brand" => $BRAND->name,
                "category_id" => $row['category'],
                "cost" => number_format($row['cost'], 2),
                "category" => $CATEGORY->name,
                "whole_sale_price" => number_format($row['whole_sale_price'], 2),
                "retail_price" => number_format($row['retail_price'], 2),
                "cash_discount" => $row['cash_discount'],
                "credit_discount" => $row['credit_discount'],
                "stock_type" => $row['stock_type'],
                "note" => $row['note'],
                "status" => $row['is_active'],
                "status_label" => $row['is_active'] == 1
                    ? '<span class="badge bg-soft-success font-size-12">Active</span>'
                    : '<span class="badge bg-soft-danger font-size-12">Inactive</span>'
            ];
    
            $data[] = $nestedData;
        }
    
        return [
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($filteredData),
            "data" => $data
        ];
    }
    
    

    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `item_master` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }
    
    

}

?>