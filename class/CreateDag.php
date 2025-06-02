<?php

class CreateDag
{

    public $id;
    public $code;
    public $department_id;
    public $date;
    public $customer_id;
    public $casing_cost;
    public $type;
    public $size;
    public $make;
    public $belt_design;
    public $job_no;
    public $serial_no;
    public $warranty;
    public $remark;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `dag` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->department_id = $result['department_id'];
                $this->date = $result['date'];
                $this->customer_id = $result['customer_id'];
                $this->casing_cost = $result['casing_cost'];
                $this->type = $result['type'];
                $this->size = $result['size'];
                $this->make = $result['make'];
                $this->belt_design = $result['belt_design'];
                $this->job_no = $result['job_no'];
                $this->serial_no = $result['serial_no'];
                $this->warranty = $result['warranty'];
                $this->remark = $result['remark'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `dag` (
            `code`, `department_id`, `date`, `customer_id`, `casing_cost`, `type`, `size`, `make`, `belt_design`, `job_no`, `serial_no`, `warranty`,`remark`
        ) VALUES (
            '$this->code', '$this->department_id', '$this->date', '$this->customer_id', '$this->casing_cost', '$this->type', '$this->size', '$this->make', '$this->belt_design', '$this->job_no', '$this->serial_no', '$this->warranty','$this->remark'
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
        $query = "UPDATE `dag` SET 
            `code` = '$this->code', 
            `department_id` = '$this->department_id',  
            `date` = '$this->date', 
            `customer_id` = '$this->customer_id', 
            `casing_cost` = '$this->casing_cost', 
            `type` = '$this->type', 
            `size` = '$this->size', 
            `make` = '$this->make', 
            `belt_design` = '$this->belt_design', 
            `job_no` = '$this->job_no', 
            `serial_no` = '$this->serial_no', 
            `warranty` = '$this->warranty', 
            `remark` = '$this->remark'
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
        $query = "DELETE FROM `dag` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
{
    $query = "SELECT * FROM `dag` ORDER BY id DESC";
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
        $query = "SELECT * FROM `dag` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function fetchForDataTable($request)
    {
        
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
        $totalSql = "SELECT * FROM dag";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);
    
        // Filtered records
        $filteredSql = "SELECT * FROM dag $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);
    
    }
    
    

    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `dag` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }

}

?>