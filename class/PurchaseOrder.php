<?php

class PurchaseOrder
{
    public $id;
    public $po_number;
    public $supplier_id	;
    public $order_date;
    public $expected_date;
    public $status;
    public $total_amount;
    public $remarks;
    public $created_by;
    public $created_at;
    public $updated_at;

    // Constructor to initialize the SalesInvoice object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `purchase_orders` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->po_number = $result['po_number'];
                $this->supplier_id = $result['supplier_id'];
                $this->order_date = $result['order_date'];
                $this->expected_date = $result['expected_date'];
                $this->status = $result['status'];
                $this->total_amount = $result['total_amount'];
                $this->remarks = $result['remarks'];
                $this->created_by = $result['created_by'];
                $this->created_at = $result['created_at'];
                $this->updated_at = $result['updated_at'];
            }
        }
    }

    // Create a new sales invoice record
    public function create()
    {
        $query = "INSERT INTO `purchase_orders` (
            `po_number`, `supplier_id`, `order_date`, `expected_date`, `status`, 
            `total_amount`, `remarks`, `created_by`, `created_at`, `updated_at`
        ) VALUES (
            '{$this->po_number}', '{$this->supplier_id}', '{$this->order_date}', '{$this->expected_date}', '{$this->status}', 
            '{$this->total_amount}', '{$this->remarks}', '{$this->created_by}', '{$this->created_at}', '{$this->updated_at}'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing sales invoice record
    public function update()
    {
        $query = "UPDATE `purchase_orders` SET 
            `po_number` = '{$this->po_number}', 
            `supplier_id` = '{$this->supplier_id}', 
            `order_date` = '{$this->order_date}', 
            `expected_date` = '{$this->expected_date}', 
            `status` = '{$this->status}', 
            `total_amount` = '{$this->total_amount}', 
            `remarks` = '{$this->remarks}', 
            `created_by` = '{$this->created_by}', 
            `created_at` = '{$this->created_at}', 
            `updated_at` = '{$this->updated_at}'
            WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a sales invoice record by ID
    public function delete()
    {
        $query = "DELETE FROM `purchase_orders` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all sales invoice records
    public function all()
    {
        $query = "SELECT * FROM `purchase_orders` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function fetchInvoicesForDataTable($request)
{
    $db = new Database();
    $conn = $db->DB_CON;

    $start = isset($request['start']) ? (int)$request['start'] : 0;
    $length = isset($request['length']) ? (int)$request['length'] : 100;
    $search = $request['search']['value'] ?? '';

    $where = "WHERE 1=1";

    // Search filter
    if (!empty($search)) {
        $escapedSearch = mysqli_real_escape_string($conn, $search);
        $where .= " AND (invoice_no LIKE '%$escapedSearch%' OR remark LIKE '%$escapedSearch%')";
    }

    // Total records (without filters)
    $totalSql = "SELECT COUNT(*) as count FROM sales_invoice";
    $totalResult = $db->readQuery($totalSql);
    $totalData = mysqli_fetch_assoc($totalResult)['count'];

    // Total filtered records
    $filteredSql = "SELECT COUNT(*) as count FROM sales_invoice $where";
    $filteredResult = $db->readQuery($filteredSql);
    $filteredData = mysqli_fetch_assoc($filteredResult)['count'];

    // Paginated query
    $query = "SELECT * FROM sales_invoice $where ORDER BY invoice_date DESC LIMIT $start, $length";
     

    $result = $db->readQuery($query);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // Optionally load related names if needed
        $CUSTOMER = new CustomerMaster($row['customer_id']);
        $DEPARTMENT = new DepartmentMaster($row['department_id']);

        $nestedData = [
            "invoice_no"    => $row['invoice_no'],
            "invoice_date"  => $row['invoice_date'],
            "customer"      => $CUSTOMER->name ?? $row['customer_id'],
            "department"    => $DEPARTMENT->name ?? $row['department_id'],
            "grand_total"   => number_format($row['grand_total'], 2),
            "remark"        => $row['remark']
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


    public function getLastID()
    {
        $query = "SELECT * FROM `sales_invoice` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }


    public function activeCountry()
    {
        $query = "SELECT * FROM `country` WHERE is_active = 1 ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }
}
?>
