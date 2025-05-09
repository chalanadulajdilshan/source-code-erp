<?php

class VehicleModel
{

    public $id;
    public $code;
    public $brand_id;
    public $name;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `vehicle_model` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->brand_id = $result['brand_id'];
                $this->name = $result['name'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `vehicle_model` (
            `code`,`brand_id`,`name`
        ) VALUES (
            '$this->code', '$this->brand_id', '$this->name'
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
        $query = "UPDATE `vehicle_model` SET 
            `code` = '$this->code', 
            `brand_id` = '$this->brand_id', 
            `name` = '$this->name'  
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
        $query = "DELETE FROM `vehicle_model` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `vehicle_model` ORDER BY name ASC";
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
        $query = "SELECT * FROM `vehicle_model` ORDER BY `id` DESC LIMIT 1";
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
    
    
        // Total records
        $totalSql = "SELECT * FROM vehicle_model";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);
    
        // Filtered records
        $filteredSql = "SELECT * FROM vehicle_model $where";
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
                "brand_id" => $row['brand_id'],
                "name" => $row['name'],
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
        $query = "SELECT `id` FROM `vehicle_model` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }
}

?>