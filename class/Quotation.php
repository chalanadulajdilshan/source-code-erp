<?php

class Quotation
{
    public $id;
    public $quotation_no;
    public $company_id;  
    public $date;
    public $customer_id;
    public $credit_limit;
    public $balance;
    public $department_id;
    public $marketing_executive_id;
    public $vat_type;
    public $payment_type;
    public $remarks;
    public $credit_period;
    public $payment_term;
    public $validity;
    public $sub_total;
    public $discount;
    public $tax;
    public $grand_total;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `quotation` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->quotation_no = $result['quotation_no'];
                $this->company_id = $result['company_id'];
                $this->date = $result['date'];
                $this->customer_id = $result['customer_id'];
                $this->credit_limit = $result['credit_limit'];
                $this->balance = $result['balance'];
                $this->department_id = $result['department_id'];
                $this->marketing_executive_id = $result['marketing_executive_id'];
                $this->vat_type = $result['vat_type'];
                $this->payment_type = $result['payment_type'];
                $this->remarks = $result['remarks'];
                $this->credit_period = $result['credit_period'];
                $this->payment_term = $result['payment_term'];
                $this->validity = $result['validity'];
                $this->sub_total = $result['sub_total'];
                $this->discount = $result['discount'];
                $this->tax = $result['tax'];
                $this->grand_total = $result['grand_total'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `quotation` 
        (`quotation_no`, `company_id`, `date`, `customer_id`, `credit_limit`, `balance`, `department_id`, `marketing_executive_id`, `vat_type`,  `payment_type`, `remarks`, `credit_period`, `payment_term`, `validity`, `sub_total`, `discount`, `tax`, `grand_total`, `created_at`) 
        VALUES 
        ('{$this->quotation_no}', '{$this->company_id}',  '{$this->date}', '{$this->customer_id}', '{$this->credit_limit}', '{$this->balance}', '{$this->department_id}', '{$this->marketing_executive_id}', '{$this->vat_type}', '{$this->payment_type}', '{$this->remarks}', '{$this->credit_period}', '{$this->payment_term}', '{$this->validity}', '{$this->sub_total}', '{$this->discount}', '{$this->tax}', '{$this->grand_total}', '{$this->created_at}')";
 
 
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
        $query = "UPDATE `quotation` SET 
        `quotation_no` = '{$this->quotation_no}',
        `company_id` = '{$this->company_id}', 
        `date` = '{$this->date}',
        `customer_id` = '{$this->customer_id}',
        `credit_limit` = '{$this->credit_limit}',
        `balance` = '{$this->balance}',
        `department_id` = '{$this->department_id}',
        `marketing_executive_id` = '{$this->marketing_executive_id}',
        `vat_type` = '{$this->vat_type}',
        `payment_type` = '{$this->payment_type}',
        `remarks` = '{$this->remarks}',
        `credit_period` = '{$this->credit_period}',
        `payment_term` = '{$this->payment_term}',
        `validity` = '{$this->validity}',
        `sub_total` = '{$this->sub_total}',
        `discount` = '{$this->discount}',
        `tax` = '{$this->tax}',
        `grand_total` = '{$this->grand_total}',
        `created_at` = '{$this->created_at}'
        WHERE `id` = '{$this->id}'";

        $db = new Database();
        return $db->readQuery($query);
    }

public function delete()
{
    $db = new Database();
    
    // Delete related quotation items
    $db->readQuery("DELETE FROM `quotation_item` WHERE `quotation_id` = '{$this->id}'");

    // Delete the quotation itself
    $query = "DELETE FROM `quotation` WHERE `id` = '{$this->id}'";
    return $db->readQuery($query);
}


    public function all()
    {
        $query = "SELECT * FROM `quotation` ORDER BY `id` DESC";
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
        $query = "SELECT * FROM `quotation` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }
}
?>
