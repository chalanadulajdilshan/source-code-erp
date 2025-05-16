<?php

class SuplierDiscount
{

    public $id;
    public $code;
    public $date;
    public $discount_id;
    public $suplier_id;
    public $name;
    public $brand_id;
    public $discount;
    public $is_active;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `suplier_discount` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->date = $result['date'];
                $this->discount_id = $result['discount_id'];
                $this->suplier_id = $result['suplier_id'];
                $this->name = $result['name'];
                $this->brand_id = $result['brand_id'];
                $this->discount = $result['discount'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `suplier_discount` (
            `code`, `date`, `discount_id`, `suplier_id`, `name`, `brand_id`, `discount`,`is_active`
        ) VALUES (
            '$this->code', '$this->date', '$this->discount_id', '$this->suplier_id', '$this->name', '$this->brand_id', '$this->discount','$this->is_active'
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
        $query = "UPDATE `suplier_discount` SET 
            `code` = '$this->code', 
            `date` = '$this->date',
            `discount_id` = '$this->discount_id', 
            `suplier_id` = '$this->suplier_id', 
            `name` = '$this->name', 
            `brand_id` = '$this->brand_id', 
            `discount` = '$this->discount',   
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
        $query = "DELETE FROM `suplier_discount` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `suplier_discount` ORDER BY name ASC";
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
        $query = "SELECT * FROM `suplier_discount` ORDER BY `id` DESC LIMIT 1";
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
    
        $where = "WHERE 1=1";
        
        // Filter by category if specified (for supplier-only filter)
        if (isset($request['supplier_only']) && $request['supplier_only'] && 
            isset($request['category']) && is_array($request['category'])) {
            $categories = implode(',', array_map('intval', $request['category']));
            $where .= " AND category IN ($categories)";
        }
    
        // Search filter
        if (!empty($search)) {
            $where .= " AND (name LIKE '%$search%' OR code LIKE '%$search%' OR mobile_number LIKE '%$search%')";
        }
    
        // Status filter if provided
        if (isset($request['status'])) {
            if ($request['status'] === 'active' || $request['status'] === '1') {
                $where .= " AND is_active = 1";
            } elseif ($request['status'] === 'inactive' || $request['status'] === '0') {
                $where .= " AND is_active = 0";
            }
        }
    
        // Total records
        $totalSql = "SELECT COUNT(*) as total FROM customer_master";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_fetch_assoc($totalQuery)['total'];
    
        // Filtered records
        $filteredSql = "SELECT COUNT(*) as filtered FROM customer_master $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_fetch_assoc($filteredQuery)['filtered'];
    
        // Paginated query
        $sql = "SELECT * FROM customer_master $where ORDER BY id DESC LIMIT $start, $length";
        $dataQuery = $db->readQuery($sql);
    
        $data = [];
    
        while ($row = mysqli_fetch_assoc($dataQuery)) {
            // Format category labels
            $categoryLabel = '';
            switch ($row['category']) {
                case 1:
                    $categoryLabel = 'Customer';
                    break;
                case 2:
                    $categoryLabel = 'Supplier';
                    break;
                case 3:
                    $categoryLabel = 'Both';
                    break;
                default:
                    $categoryLabel = 'Unknown';
            }
            
            $nestedData = [
                "id" => $row['id'],
                "code" => $row['code'],
                "name" => $row['name'],
                "mobile_number" => $row['mobile_number'],
                "email" => $row['email'],
                "category" => $categoryLabel,
                "province" => $row['province'] ?? '',
                "credit_limit" => $row['credit_limit'] ?? '0',
                "vat_no" => $row['vat_no'] ?? '',
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
        $query = "SELECT `id` FROM `suplier_discount` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }
    
    

}

?>
