<?php

class InvoiceRemark
{

    public $id;
    public $code;
    public $payment_type;
    public $remark;
    public $queue;
    public $is_active;


    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `remark` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->payment_type = $result['payment_type'];
                $this->remark = $result['remark'];
                $this->queue = $result['queue'];
                $this->is_active = $result['is_active'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `remark` (
            `code`, `payment_type`, `remark`, `queue`, `is_active`
            ) VALUES (
                '$this->code', '$this->payment_type', '$this->remark', '$this->queue', '$this->is_active'
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
        $query = "UPDATE `remark` SET 
            `code` = '$this->code', 
            `payment_type` = '$this->payment_type', 
            `remark` = '$this->remark',  
            `queue` = '$this->queue', 
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

    public function all()
    {
        $query = "SELECT * FROM `remark` ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function getActiveGroups()
    {
        $query = "SELECT * FROM `remark` WHERE `is_active` = 1 ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function delete()
    {
        $query = "DELETE FROM `remark` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }


    public function getLastID()
    {
        $query = "SELECT * FROM `remark` ORDER BY `id` DESC LIMIT 1";
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

        // Total records
        $totalSql = "SELECT * FROM remark";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);

        // Filtered records
        $filteredSql = "SELECT * FROM remark $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);
    }

    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `remark` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }

        return null;
    }

    public function getRemarkByPaymentType($paymentType)
    {
        $array = array();

        // Map text payment types to numeric values
        $paymentTypeMap = [
            'cash' => 1,
            'credit' => 2
            // Add more mappings if needed
        ];

        // Convert text payment type to numeric if needed
        $paymentType = isset($paymentTypeMap[strtolower($paymentType)])
            ? $paymentTypeMap[strtolower($paymentType)]
            : (int)$paymentType;

        // Get all active remarks for this payment type, ordered by queue
        $query = "SELECT `remark` FROM `remark` WHERE `payment_type` = $paymentType AND `is_active` = 1 ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($row['remark'])) {
                    $array[] = $row;
                }
            }
        }

        return $array;
    }
}
