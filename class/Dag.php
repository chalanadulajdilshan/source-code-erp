<?php

class Dag
{
    public $id;
    public $ref_no;
    public $received_date;
    public $delivery_date;
    public $customer_request_date;
    public $dag_company_id;
    public $company_received_date;
    public $company_request_date;
    public $remark;
    public $receipt_no;
    public $status;

    // Constructor: Initialize object with ID (fetch from DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `ref_no`, `received_date`, `delivery_date`, `customer_request_date`, `dag_company_id`, `company_received_date`, `company_request_date`, `remark`, `receipt_no`, `status` FROM `dag` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->ref_no = $result['ref_no'];
                $this->received_date = $result['received_date'];
                $this->delivery_date = $result['delivery_date'];
                $this->customer_request_date = $result['customer_request_date'];
                $this->dag_company_id = $result['dag_company_id'];
                $this->company_received_date = $result['company_received_date'];
                $this->company_request_date = $result['company_request_date'];
                $this->remark = $result['remark'];
                $this->receipt_no = $result['receipt_no'];
                $this->status = $result['status'];
            }
        }
    }

    // Create a new DAG record
    public function create()
    {
        $query = "INSERT INTO `dag` (
            `ref_no`, `received_date`, `delivery_date`, `customer_request_date`,
            `dag_company_id`, `company_received_date`, `company_request_date`,
            `remark`, `receipt_no`, `status`
        ) VALUES (
            '{$this->ref_no}', '{$this->received_date}', '{$this->delivery_date}', '{$this->customer_request_date}',
            '{$this->dag_company_id}', '{$this->company_received_date}', '{$this->company_request_date}',
            '{$this->remark}', '{$this->receipt_no}', '{$this->status}'
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing DAG record
    public function update()
    {
        $query = "UPDATE `dag` SET 
            `ref_no` = '{$this->ref_no}',
            `received_date` = '{$this->received_date}',
            `delivery_date` = '{$this->delivery_date}',
            `customer_request_date` = '{$this->customer_request_date}',
            `dag_company_id` = '{$this->dag_company_id}',
            `company_received_date` = '{$this->company_received_date}',
            `company_request_date` = '{$this->company_request_date}',
            `remark` = '{$this->remark}',
            `receipt_no` = '{$this->receipt_no}',
            `status` = '{$this->status}'
            WHERE `id` = '{$this->id}'";

        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete a DAG record
    public function delete()
    {
        $query = "DELETE FROM `dag` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all DAG records
    public function all()
    {
        $query = "SELECT * FROM `dag` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
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

    public function getLastID()
    {
        $query = "SELECT * FROM `dag` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}
?>