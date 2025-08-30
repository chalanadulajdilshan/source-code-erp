<?php

class SalesInvoice
{
    public $id;
    public $is_cancel;
    public $ref_id;
    public $invoice_no;
    public $invoice_type;
    public $invoice_date;
    public $company_id;
    public $customer_id;
    public $customer_name;
    public $customer_mobile;
    public $customer_address;
    public $department_id;
    public $sale_type;
    public $discount_type;
    public $final_cost;
    public $payment_type;
    public $sub_total;
    public $discount;
    public $tax;
    public $grand_total;
    public $remark;
    public $status;

    // Constructor to initialize the SalesInvoice object with an ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `sales_invoice` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->is_cancel = $result['is_cancel'];
                $this->ref_id = $result['ref_id'];
                $this->invoice_type = $result['invoice_type'];
                $this->invoice_no = $result['invoice_no'];
                $this->invoice_date = $result['invoice_date'];
                $this->company_id = $result['company_id'];
                $this->customer_id = $result['customer_id'];
                $this->customer_name = $result['customer_name'];
                $this->customer_mobile = $result['customer_mobile'];
                $this->customer_address = $result['customer_address'];
                $this->department_id = $result['department_id'];
                $this->sale_type = $result['sale_type'];
                $this->discount_type = $result['discount_type'];
                $this->final_cost = $result['final_cost'];
                $this->payment_type = $result['payment_type'];
                $this->sub_total = $result['sub_total'];
                $this->discount = $result['discount'];
                $this->tax = $result['tax'];
                $this->grand_total = $result['grand_total'];
                $this->remark = $result['remark'];
                $this->status = $result['status'];
            }
        }
    }

    // Create a new sales invoice record
    public function create()
    {
        $query = "INSERT INTO `sales_invoice` (
            `ref_id`,`invoice_type`,`invoice_no`, `invoice_date`, `company_id`, `customer_id`, `customer_name`, `customer_mobile`, `customer_address`, `department_id`, 
            `sale_type`, `discount_type`,`final_cost`, `payment_type`, `sub_total`, `discount`, 
            `tax`, `grand_total`, `remark`
        ) VALUES (
            '{$this->ref_id}','{$this->invoice_type}', '{$this->invoice_no}', '{$this->invoice_date}', '{$this->company_id}', '{$this->customer_id}', '{$this->customer_name}', '{$this->customer_mobile}', '{$this->customer_address}', '{$this->department_id}', 
            '{$this->sale_type}', '{$this->discount_type}', '{$this->final_cost}','{$this->payment_type}', '{$this->sub_total}', '{$this->discount}', 
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
            `invoice_type` = '{$this->invoice_type}', 
            `invoice_date` = '{$this->invoice_date}', 
            `company_id` = '{$this->company_id}', 
            `customer_id` = '{$this->customer_id}', 
            `customer_name` = '{$this->customer_name}', 
            `customer_mobile` = '{$this->customer_mobile}', 
            `customer_address` = '{$this->customer_address}', 
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

    public function cancel($invoiceId)
    {

        // Use prepared statement to prevent SQL injection
        $query = "UPDATE `sales_invoice` SET `is_cancel` = 1 WHERE `id` = $invoiceId";

        $db = new Database();
        $result = $db->readQuery($query); // Assuming your Database class supports parameters

        if ($result) {

            return true; // Return boolean instead of calling constructor
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

        $start = isset($request['start']) ? (int) $request['start'] : 0;
        $length = isset($request['length']) ? (int) $request['length'] : 100;
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
                "id" => $row['id'], // Needed!
                "invoice_no" => $row['invoice_no'],
                "invoice_date" => $row['invoice_date'],
                "customer" => $CUSTOMER->name ?? $row['customer_id'],
                "department" => $DEPARTMENT->name ?? $row['department_id'],
                "grand_total" => number_format($row['grand_total'], 2),
                "remark" => $row['remark']
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

        if ($result && isset($result['id'])) {
            return $result['id'];
        } else {
            return 0; // Or null, depending on how you want to handle "no results"
        }
    }

    public function getByID($id)
    {
        $query = "SELECT * FROM `sales_invoice` where `id` = '$id'";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        if ($result && isset($result['id'])) {
            return $result;
        } else {
            return 0; // Or null, depending on how you want to handle "no results"
        }
    }


    public function checkInvoiceIdExist($id)
    {
        $query = "SELECT * FROM `sales_invoice` where `invoice_no` = '$id' ";


        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));

        return ($result) ? true : false;
    }

    public static function filterSalesInvoices($filters)
    {
        $db = new Database();
        $conditions = [];

        // Customer filter
        if (empty($filters['all_customers']) && !empty($filters['customer_code'])) {
            $conditions[] = "`customer_id` = '" . $db->escapeString($filters['customer_code']) . "'";
        }

        // Date range or from-to
        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $conditions[] = "`invoice_date` BETWEEN '" . $db->escapeString($filters['from_date']) . "' AND '" . $db->escapeString($filters['to_date']) . "'";
        }

        if (!empty($filters['date_range'])) {
            $today = date('Y-m-d');
            switch ($filters['date_range']) {
                case 'today':
                    $conditions[] = "`invoice_date` = '$today'";
                    break;
                case 'this_week':
                    $start = date('Y-m-d', strtotime('monday this week'));
                    $end = date('Y-m-d', strtotime('sunday this week'));
                    $conditions[] = "`invoice_date` BETWEEN '$start' AND '$end'";
                    break;
                case 'this_month':
                    $start = date('Y-m-01');
                    $end = date('Y-m-t');
                    $conditions[] = "`invoice_date` BETWEEN '$start' AND '$end'";
                    break;
            }
        }

        // // Status filter
        // if (!empty($filters['status'])) {
        //     $conditions[] = "`status` = '" . $db->escapeString($filters['status']) . "'";
        // }

        // Build WHERE clause
        $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT 
                `id`, `invoice_no`, `invoice_date`, `customer_id`, `final_cost`, `grand_total`, `status`
            FROM `sales_invoice`
            $where
            ORDER BY `invoice_date` DESC";

        $result = $db->readQuery($sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    public static function getProfitTable($filters)
    {
        $db = new Database();
        $conditions = [];

        // Filter: Customer
        if (empty($filters['all_customers']) && !empty($filters['customer_id'])) {
            $conditions[] = "`customer_id` = '" . $db->escapeString($filters['customer_id']) . "'";
        }

        // Filter: Department
        if (!empty($filters['department_id'])) {
            $conditions[] = "`department_id` = '" . $db->escapeString($filters['department_id']) . "'";
        }

        //company vise
        if (!empty($filters['company_id'])) {
            $conditions[] = "`company_id` = '" . $db->escapeString($filters['company_id']) . "'";
        }


        // Filter: Date range
        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $conditions[] = "`invoice_date` BETWEEN '" . $db->escapeString($filters['from_date']) . "' AND '" . $db->escapeString($filters['to_date']) . "'";
        }


        // Build WHERE clause
        $where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

        // Final SQL query
        $sql = "SELECT * FROM `sales_invoice`
            $where
            ORDER BY `invoice_date` DESC";

        $result = $db->readQuery($sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $COMPANY_PROFILE = new CompanyProfile($row['company_id']);
            $CUSTOMER_MASTER = new CustomerMaster($row['customer_id']);
            $DEPARTMENT_MASTER = new DepartmentMaster($row['department_id']);
            $SALES_TYPE = new SalesType($row['sale_type']);


            $row['company_name'] = $COMPANY_PROFILE->name;
            $row['customer_name'] = $CUSTOMER_MASTER->name;
            $row['department_name'] = $DEPARTMENT_MASTER->name;
            $row['sales_type'] = $SALES_TYPE->code;

            $data[] = $row;
        }

        return $data;
    }

    public function getCreditInvoicesByCustomerAndStatus($status, $customer_id)
    {
        $query = "SELECT * FROM `sales_invoice` where `sale_type` = 2 and `status`= $status and `customer_id` = $customer_id ORDER BY `invoice_date` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function latest()
    {
        $query = "SELECT si.*, dm.name as department_name 
              FROM sales_invoice si
              LEFT JOIN department_master dm ON si.department_id = dm.id
              ORDER BY si.id DESC 
        LIMIT 10";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            $array_res[] = $row;
            $DEPARTMENT_MASTER = new DepartmentMaster($row['department_id']);
            $row['department_name'] = $DEPARTMENT_MASTER->name;
        }

        return $array_res;
    }

    // Search invoices (invoice_no, customer, department)
    public function search($keyword)
    {
        $db = new Database();
        $keyword = $db->escapeString($keyword);

        $query = "SELECT si.* 
                  FROM `sales_invoice` si
                  LEFT JOIN `customer_master` c ON si.customer_id = c.id
                  LEFT JOIN `department_master` d ON si.department_id = d.id
                  WHERE si.invoice_no LIKE '%$keyword%'
                     OR c.name LIKE '%$keyword%'
                     OR d.name LIKE '%$keyword%'
                  ORDER BY si.id DESC
                  LIMIT 50";

        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            $array_res[] = $row;
        }

        return $array_res;
    }
}
