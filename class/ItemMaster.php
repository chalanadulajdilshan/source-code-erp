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
    public $re_order_level;
    public $re_order_qty;
    public $list_price;
    public $invoice_price;
    public $stock_type;
    public $note;
    public $discount;
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
                $this->list_price = $result['list_price'];
                $this->invoice_price = $result['invoice_price'];
                $this->re_order_level = $result['re_order_level'];
                $this->re_order_qty = $result['re_order_qty'];
                $this->stock_type = $result['stock_type'];
                $this->note = $result['note'];
                $this->discount = $result['discount'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `item_master` (
    `code`, `name`, `brand`, `size`, `pattern`, `group`, `category`, 
     `re_order_level`, `re_order_qty`, `stock_type`, `note`,`list_price`,`invoice_price`,`discount`, `is_active`
) VALUES (
    '$this->code', '$this->name', '$this->brand', '$this->size', '$this->pattern', '$this->group',
    '$this->category',  '$this->re_order_level', '$this->re_order_qty',
     '$this->stock_type', '$this->note', '$this->list_price', '$this->invoice_price', '$this->discount', '$this->is_active'
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
            `list_price` = '$this->list_price', 
            `invoice_price` = '$this->invoice_price', 
            `re_order_level` = '$this->re_order_level', 
            `re_order_qty` = '$this->re_order_qty', 
            `stock_type` = '$this->stock_type', 
            `note` = '$this->note',
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

    public function getItemsFiltered($category_id = 0, $brand_id = 0, $group_id = 0, $department_id = 0, $item_code = '')
    {
        $conditions = [];

        if ((int) $category_id > 0) {
            $conditions[] = "`category` = '" . (int) $category_id . "'";
        }

        if ((int) $brand_id > 0) {
            $conditions[] = "`brand` = '" . (int) $brand_id . "'";
        }

        if ((int) $group_id > 0) {
            $conditions[] = "`group` = '" . (int) $group_id . "'";
        }

        if (!empty($item_code)) {
            $conditions[] = "(`code` LIKE '%" . $item_code . "%' OR `name` LIKE '%" . $item_code . "%')";
        }


        // Join condition to filter department stock
        $join = "";
        if ((int) $department_id > 0) {
            $join = "INNER JOIN stock_master sm ON sm.item_id = im.id AND sm.department_id = '" . (int) $department_id . "'";
        }

        $where = "";
        if (count($conditions) > 0) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        $query = "SELECT DISTINCT im.* FROM item_master im $join $where ORDER BY im.name ASC";

        $db = new Database();
        $result = $db->readQuery($query);

        $items = [];

        $STOCK_TMP = new StockItemTmp(NULL);

        while ($row = mysqli_fetch_assoc($result)) {
            $CATEGORY = new CategoryMaster($row['category']);
            $BRAND = new Brand($row['brand']);
            $GROUP_MASTER = new GroupMaster($row['group']);
            $STOCK_MASTER = new StockMaster(NULL);

            $row['group'] = $GROUP_MASTER->name;
            $row['category'] = $CATEGORY->name;
            $row['brand'] = $BRAND->name;

            $row['stock_tmp'] = $STOCK_TMP->getByItemId($row['id']);
            $row['total_available_qty'] = $STOCK_MASTER->getTotalAvailableQuantity($row['id']);

            foreach ($row['stock_tmp'] as $key => $stockRow) {
                // ARN
                $arnData = new ArnMaster($stockRow['arn_id']);
                $row['stock_tmp'][$key]['arn_no'] = $arnData ? $arnData->arn_no : null;

                if (!$arnData || $arnData->is_cancelled == 1) {
                    unset($row['stock_tmp'][$key]);
                    continue;
                }
                usort($row['stock_tmp'], function ($a, $b) {
                    return strtotime($a['created_at']) - strtotime($b['created_at']);
                });

                $row['stock_tmp'][$key]['final_cost'] = $stockRow['cost']; // Assuming 'cost' = final cost
                $row['stock_tmp'][$key]['list_price'] = $stockRow['list_price'];
                $row['stock_tmp'][$key]['invoice_price'] = $stockRow['invoice_price'];

                // Department
                $DEPARTMENT_MASTER = new DepartmentMaster($stockRow['department_id']);
                $departmentName = $DEPARTMENT_MASTER ? $DEPARTMENT_MASTER->name : null;
                $row['stock_tmp'][$key]['department'] = $departmentName;
            }

            $items[] = $row;
        }


        return $items;
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

        $start = isset($request['start']) ? (int) $request['start'] : 0;
        $length = isset($request['length']) ? (int) $request['length'] : 100;
        $search = $request['search']['value'] ?? '';
        $searchTerm = $request['search_term'] ?? '';

        $status = $request['status'] ?? null;
        $stockOnly = isset($request['stock_only']) ? filter_var($request['stock_only'], FILTER_VALIDATE_BOOLEAN) : false;
        $departmentId = isset($request['department_id']) ? (int)$request['department_id'] : 0;

        $where = "WHERE 1=1";
        $join = "";

        // Search filter
        if (!empty($search)) {
            $where .= " AND (im.name LIKE '%$search%' OR im.code LIKE '%$search%')";
        }

        // Additional search term from custom search box
        if (!empty($searchTerm)) {
            $where .= " AND (im.name LIKE '%$searchTerm%' OR im.code LIKE '%$searchTerm%')";
        }

        $brandId = $request['brand'] ?? null;

        if (!empty($brandId)) {
            $brandId = (int) $brandId;
            $where .= " AND im.brand = {$brandId}";
        }

        // Status filter
        if (!empty($status)) {
            if ($status === 'active' || $status === '1' || $status === 1) {
                $where .= " AND im.is_active = 1";
            } elseif ($status === 'inactive' || $status === '0' || $status === 0) {
                $where .= " AND im.is_active = 0";
            }
        }

        // Stock only filter
        if ($stockOnly) {
            $where .= " AND im.stock_type = 1";
        }

        // Department filter
        if ($departmentId > 0) {
            $join = " LEFT JOIN stock_master sm2 ON im.id = sm2.item_id";
            $where .= " AND sm2.department_id = $departmentId";
        }

        // Check if we're on the stock transfer page and need to show all departments
        $showAllDepartments = isset($request['show_all_departments']) ? (bool)$request['show_all_departments'] : false;
        $fromDepartmentId = isset($request['from_department_id']) ? (int)$request['from_department_id'] : 0;

        // If showing all departments but we have a from_department_id (stock transfer case)
        if ($showAllDepartments && $fromDepartmentId > 0) {
            $join = " LEFT JOIN stock_master sm2 ON im.id = sm2.item_id AND sm2.department_id = $fromDepartmentId";
        }

        // Total records (no filter)
        $totalSql = "SELECT COUNT(*) as total FROM item_master";
        $totalQuery = $db->readQuery($totalSql);
        $totalRow = mysqli_fetch_assoc($totalQuery);
        $totalData = $totalRow['total'];

        // Filtered records with JOIN and aggregation
        $filteredSql = "
        SELECT 
            im.*, 
            " . ($fromDepartmentId > 0 ? 
               "IFNULL((SELECT SUM(sm.quantity) FROM stock_master sm WHERE sm.item_id = im.id AND sm.department_id = $fromDepartmentId), 0) as total_qty" : 
               "IFNULL((SELECT SUM(quantity) FROM stock_master WHERE item_id = im.id), 0) as total_qty") . " 
        FROM item_master im
        $join
        $where
        GROUP BY im.id ";

        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);

        // Paginated query
        $sql = "$filteredSql LIMIT $start, $length";
        $dataQuery = $db->readQuery($sql);

        $data = [];
        $key = 1;
        while ($row = mysqli_fetch_assoc($dataQuery)) {
            $CATEGORY = new CategoryMaster($row['category']);
            $BRAND = new Brand($row['brand']);

            // Get department stock information
            $departmentStocks = [];
            $stockQuery = "SELECT department_id, quantity FROM stock_master WHERE item_id = {$row['id']}";
            $stockResult = $db->readQuery($stockQuery);
            while ($stockRow = mysqli_fetch_assoc($stockResult)) {
                $departmentStocks[] = [
                    'department_id' => (int)$stockRow['department_id'],
                    'quantity' => (float)$stockRow['quantity']
                ];
            }

            $nestedData = [
                "key" => $key,
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
                "category" => $CATEGORY->name,
                "list_price" => $row['list_price'],
                "invoice_price" => $row['invoice_price'],
                "discount" => $row['discount'],
                "stock_type" => $row['stock_type'],
                "note" => $row['note'],
                "status" => $row['is_active'],
                "qty" => $row['total_qty'],
                "department_stock" => $departmentStocks, // Add department stock information
                "status_label" => $row['is_active'] == 1
                    ? '<span class="badge bg-soft-success font-size-12">Active</span>'
                    : '<span class="badge bg-soft-danger font-size-12">Inactive</span>'
            ];

            $data[] = $nestedData;
            $key++;
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

    public static function checkReorderLevel()
    {
        $db = new Database();
        $query = "SELECT `id`, `code`, `name`,   `re_order_level` FROM `item_master`";
        $result = $db->readQuery($query);

        $reorderItems = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $reorderItems[] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
            ];

        }

        return $reorderItems;
    }





}

?>