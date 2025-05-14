<?php

class EmployeeMaster
{

    public $id;
    public $code;
    public $name;
    public $full_name;
    public $gender;
    public $birthday;
    public $nic_no;
    public $mobile_1;
    public $mobile_2;
    public $email;
    public $epf_available;
    public $epf_no;
    public $finger_print_no;
    public $department_id;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `employee_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->name = $result['name'];
                $this->full_name = $result['full_name'];
                $this->gender = $result['gender'];
                $this->birthday = $result['birthday'];
                $this->nic_no = $result['nic_no'];
                $this->mobile_1 = $result['mobile_1'];
                $this->mobile_2 = $result['mobile_2'];
                $this->email = $result['email'];
                $this->epf_available = $result['epf_available'];
                $this->epf_no = $result['epf_no'];
                $this->finger_print_no = $result['finger_print_no'];
                $this->department_id = $result['department_id'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `employee_master` (
            `code`, `name`, `full_name`, `gender`, `birthday`, `nic_no`, `mobile_1`, `mobile_2`, `email`, `epf_available`, `epf_no`, `finger_print_no`,`department_id`
        ) VALUES (
            '$this->code', '$this->name', '$this->full_name', '$this->gender', '$this->birthday', '$this->nic_no', '$this->mobile_1', '$this->mobile_2', '$this->email', '$this->epf_available', '$this->epf_no', '$this->finger_print_no', '$this->department_id'
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
        $query = "UPDATE `employee_master` SET 
            `code` = '$this->code', 
            `name` = '$this->name', 
            `full_name` = '$this->full_name',
            `gender` = '$this->gender',
            `birthday` = '$this->birthday',
            `nic_no` = '$this->nic_no',
            `mobile_1` = '$this->mobile_1',
            `mobile_2` = '$this->mobile_2',
            `email` = '$this->email',
            `epf_available` = '$this->epf_available',
            `epf_no` = '$this->epf_no',
            `finger_print_no` = '$this->finger_print_no', 
            `department_id` = '$this->department_id'
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
        $query = "DELETE FROM `employee_master` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `employee_master` ORDER BY name ASC";
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
        $query = "SELECT * FROM `employee_master` ORDER BY `id` DESC LIMIT 1";
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
            if ($status === 'available' || $status === '1' || $status === 1) {
                $where .= " AND epf_available = 1";
            } elseif ($status === 'not_available' || $status === '0' || $status === 0) {
                $where .= " AND epf_available = 0";
            }
        }
    
        // Total records
        $totalSql = "SELECT * FROM employee_master";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);
    
        // Filtered records
        $filteredSql = "SELECT * FROM employee_master $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);
    }
    
    

    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `employee_master` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
    
        return null;
    }
}

?>