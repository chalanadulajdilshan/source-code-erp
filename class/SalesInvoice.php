<?php

class SalesInvoice
{
    public $id;
    public $invoice_no;
    public $invoice_date;
    public $company_id;
    public $customer_id;
    public $department_id;
    public $sale_type;
    public $discount_type;
    public $payment_type;
    public $sub_total;
    public $discount;
    public $tax;
    public $grand_total;
    public $remark;

    // Constructor to initialize the SalesInvoice object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `sales_invoice` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->invoice_no = $result['invoice_no'];
                $this->invoice_date = $result['invoice_date'];
                $this->company_id = $result['company_id'];
                $this->customer_id = $result['customer_id'];
                $this->department_id = $result['department_id'];
                $this->sale_type = $result['sale_type'];
                $this->discount_type = $result['discount_type'];
                $this->payment_type = $result['payment_type'];
                $this->sub_total = $result['sub_total'];
                $this->discount = $result['discount'];
                $this->tax = $result['tax'];
                $this->grand_total = $result['grand_total'];
                $this->remark = $result['remark'];
            }
        }
    }

    // Create a new sales invoice record
    public function create()
    {
        $query = "INSERT INTO `sales_invoice` (
            `invoice_no`, `invoice_date`, `company_id`, `customer_id`, `department_id`, 
            `sale_type`, `discount_type`, `payment_type`, `sub_total`, `discount`, 
            `tax`, `grand_total`, `remark`
        ) VALUES (
            '{$this->invoice_no}', '{$this->invoice_date}', '{$this->company_id}', '{$this->customer_id}', '{$this->department_id}', 
            '{$this->sale_type}', '{$this->discount_type}', '{$this->payment_type}', '{$this->sub_total}', '{$this->discount}', 
            '{$this->tax}', '{$this->grand_total}', '{$this->remark}'
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
        $query = "UPDATE `sales_invoice` SET 
            `invoice_no` = '{$this->invoice_no}', 
            `invoice_date` = '{$this->invoice_date}', 
            `company_id` = '{$this->company_id}', 
            `customer_id` = '{$this->customer_id}', 
            `department_id` = '{$this->department_id}', 
            `sale_type` = '{$this->sale_type}', 
            `discount_type` = '{$this->discount_type}', 
            `payment_type` = '{$this->payment_type}', 
            `sub_total` = '{$this->sub_total}', 
            `discount` = '{$this->discount}', 
            `tax` = '{$this->tax}', 
            `grand_total` = '{$this->grand_total}', 
            `remark` = '{$this->remark}' 
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
        $query = "DELETE FROM `sales_invoice` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all sales invoice records
    public function all()
    {
        $query = "SELECT * FROM `sales_invoice` ORDER BY `invoice_date` DESC";
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
}
?>
