<?php

class CustomerMaster
{
    public $id;
    public $code;
    public $name;
    public $address;
    public $mobile_number;
    public $mobile_number_2;
    public $email;
    public $contact_person;
    public $contact_person_number;
    public $credit_limit;
    public $outstanding;
    public $overdue;
    public $is_vat;
    public $vat_no;
    public $svat_no;
    public $category;
    public $district;
    public $province;
    public $vat_group;
    public $remark;
    public $nic_front_img;
    public $nic_back_img;
    public $is_active;

    // Constructor
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `customer_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    // Create new customer
    public function create()
    {
        $query = "INSERT INTO `customer_master` (
                    `code`, `name`, `address`, `mobile_number`, `mobile_number_2`, `email`, 
                    `contact_person`, `contact_person_number`, `credit_limit`, `outstanding`, `overdue`, 
                    `is_vat`, `vat_no`, `svat_no`, `category`, `district`, `province`, 
                    `vat_group`, `remark`, `nic_front_img`, `nic_back_img`, `is_active`
                ) VALUES (
                    '{$this->code}', '{$this->name}', '{$this->address}', '{$this->mobile_number}', '{$this->mobile_number_2}', '{$this->email}',
                    '{$this->contact_person}', '{$this->contact_person_number}', '{$this->credit_limit}', '{$this->outstanding}', '{$this->overdue}',
                    '{$this->is_vat}', '{$this->vat_no}', '{$this->svat_no}', '{$this->category}', '{$this->district}', '{$this->province}',
                    '{$this->vat_group}', '{$this->remark}', '{$this->nic_front_img}', '{$this->nic_back_img}', '{$this->is_active}'
                )";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }
    public function createInvoiceCustomer()
    {
        $query = "INSERT INTO `customer_master` (
    `code`, `name`, `address`, `mobile_number`, `mobile_number_2`, `email`, 
    `contact_person`, `contact_person_number`, `credit_limit`, `outstanding`, `overdue`, 
    `is_vat`, `vat_no`, `svat_no`, `category`, `district`, `province`, 
    `vat_group`, `remark`, `nic_front_img`, `nic_back_img`, `is_active`
) VALUES (
    '{$this->code}', '{$this->name}', '{$this->address}', '{$this->mobile_number}', '0', '0',
    '0', '0', '0', '0', '0',
    '0', '0', '0', '1', '0', '0',
    '0', '0', '0', '0','1'
)";


        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update existing customer
    public function update()
    {
        $query = "UPDATE `customer_master` SET 
                    `code` = '{$this->code}', 
                    `name` = '{$this->name}', 
                    `address` = '{$this->address}', 
                    `mobile_number` = '{$this->mobile_number}', 
                    `mobile_number_2` = '{$this->mobile_number_2}', 
                    `email` = '{$this->email}', 
                    `contact_person` = '{$this->contact_person}', 
                    `contact_person_number` = '{$this->contact_person_number}', 
                    `credit_limit` = '{$this->credit_limit}', 
                    `outstanding` = '{$this->outstanding}', 
                    `overdue` = '{$this->overdue}', 
                    `is_vat` = '{$this->is_vat}', 
                    `vat_no` = '{$this->vat_no}', 
                    `svat_no` = '{$this->svat_no}', 
                    `category` = '{$this->category}', 
                    `district` = '{$this->district}', 
                    `province` = '{$this->province}', 
                    `vat_group` = '{$this->vat_group}', 
                    `remark` = '{$this->remark}', 
                    `nic_front_img` = '{$this->nic_front_img}', 
                    `nic_back_img` = '{$this->nic_back_img}', 
                    `is_active` = '{$this->is_active}' 
                WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Delete customer
    public function delete()
    {
        $query = "DELETE FROM `customer_master` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all customers
    public function all()
    {
        $query = "SELECT * FROM `customer_master` ORDER BY name ASC";
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
        $query = "SELECT * FROM `customer_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function fetchForDataTable($request, $category)
    {
        $db = new Database();

        $start = isset($request['start']) ? (int) $request['start'] : 0;
        $length = isset($request['length']) ? (int) $request['length'] : 100;
        $search = $request['search']['value'];

        // Total records
        $totalSql = "SELECT * FROM customer_master";
        $totalQuery = $db->readQuery($totalSql);
        $totalData = mysqli_num_rows($totalQuery);

        // Search filter
        $sql = "SELECT * FROM customer_master WHERE id != 1 ";

        if (!empty($category)) {
            if (is_array($category)) {
                // Sanitize values to integers
                $category = array_map('intval', $category);
                $categoryList = implode(',', $category);
                $sql .= " AND category IN ($categoryList)   ";
            } else {
                // Single category value
                $category = intval($category); // sanitize
                $sql .= " AND category = $category    ";
            }
        }



        if (!empty($search)) {
            $sql .= "AND  name LIKE '%$search%' OR code LIKE '%$search%' OR mobile_number LIKE '%$search%'  and is_active !=0";
        }

        $filteredQuery = $db->readQuery($sql);
        $filteredData = mysqli_num_rows($filteredQuery);

        // Add pagination
        $sql .= " LIMIT $start, $length";
        $dataQuery = $db->readQuery($sql);

        $data = [];

        $key = 1;
        while ($row = mysqli_fetch_assoc($dataQuery)) {
            $CATEGORY = new CustomerCategory($row['category']);
            $PROVINCE = new Province($row['province']);
            $DISTRICT = new District($row['district']);

            $nestedData = [
                "key" => $key,
                "id" => $row['id'],
                "code" => $row['code'],
                "name" => $row['name'],
                "address" => $row['address'],
                "mobile_number" => $row['mobile_number'],
                "mobile_number_2" => $row['mobile_number_2'],
                "email" => $row['email'],
                "contact_person" => $row['contact_person'],
                "contact_person_number" => $row['contact_person_number'],
                "credit_limit" => number_format($row['credit_limit'], 2),
                "outstanding" => number_format($row['outstanding'], 2),
                "overdue" => number_format($row['overdue'], 2),
                "vat_no" => $row['vat_no'],
                "svat_no" => $row['svat_no'],
                "category_id" => $row['category'],
                "category" => $CATEGORY->name,
                "province_id" => $row['province'],
                "province" => $PROVINCE->name,
                "district_id" => $row['district'],
                "district" => $DISTRICT->name,
                "vat_group" => $row['vat_group'],
                "remark" => $row['remark'],
                "status" => $row['is_active'],
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


    public static function searchCustomers($search)
    {
        $db = new Database();
        $query = "SELECT *
                FROM customer_master 
                WHERE (code LIKE '%$search%' OR name LIKE '%$search%') 
                AND is_active = 1 ";


        $result = $db->readQuery($query);

        $customers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }

        return $customers;
    }
}
