<?php

class DagCompany
{

    public $id;
    public $name;
    public $code;
    public $address;
    public $contact_person;
    public $phone_number;
    public $email;
    public $is_active;
    public $remark;
    public $created_at;

    // Constructor to fetch data by ID


    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `dag_company` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->code = $result['code'];
                $this->address = $result['address'];
                $this->contact_person = $result['contact_person'];
                $this->phone_number = $result['phone_number'];
                $this->email = $result['email'];
                $this->is_active = $result['is_active'];
                $this->remark = $result['remark'];
                $this->created_at = $result['created_at'];
            }
        }
    }


    public function create()
    {
        $query = "INSERT INTO `dag_company` (
            `name`, `code`, `address`,  `contact_person`,  `phone_number`,  `email`, `is_active`, `remark`
        ) VALUES (
            '$this->name', '$this->code', '$this->address', '$this->contact_person', '$this->phone_number', '$this->email', '$this->is_active', '$this->remark'
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
        $query = "UPDATE `dag_company` SET 
            `name` = '$this->name',
            `code` = '$this->code',
            `address` = '$this->address',
            `contact_person` = '$this->contact_person',
            `phone_number` = '$this->phone_number',
            `email` = '$this->email',   
            `is_active` = '$this->is_active',
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
        $query = "DELETE FROM `dag_company` WHERE `id` = '$this->id'";

        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all records


    public function all()
    {
        $query = "SELECT * FROM `dag_company` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getByStatusCompany($id)
    {
        $query = "SELECT * FROM `dag_company` WHERE id =$id ORDER BY `name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


    public function getLastID()
    {
        $query = "SELECT * FROM `dag_company` ORDER BY `id` DESC LIMIT 1";
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
        $totalSql = "SELECT * FROM dag_company";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);

        // Filtered records
        $filteredSql = "SELECT * FROM dag_company $where";
        $filteredQuery = $db->readQuery($filteredSql);
        $filteredData = mysqli_num_rows($filteredQuery);

    }


    public function getIdbyItemCode($code)
    {
        $query = "SELECT `id` FROM `dag_company` WHERE `code` = '$code' LIMIT 1";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }

        return null;
    }

    public function getActiveDagCompany()
    {
        $query = "SELECT * FROM `dag_company` WHERE `is_active` = 1 ORDER BY `id` ASC";
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