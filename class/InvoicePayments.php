<?php

class InvoicePayment
{
    public $id;
    public $invoice_id;
    public $method_id;
    public $amount;
    public $reference_no;
    public $cheque_date;
    public $bank_name;
    public $created_at;

    // Constructor to initialize object with ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `invoice_payments` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->invoice_id = $result['invoice_id'];
                $this->method_id = $result['method_id'];
                $this->amount = $result['amount'];
                $this->reference_no = $result['reference_no'];
                $this->cheque_date = $result['cheque_date'];
                $this->bank_name = $result['bank_name'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    // Create a new payment
    public function create()
    {
        $query = "INSERT INTO `invoice_payments` 
                  (`invoice_id`, `method_id`, `amount`, `reference_no`, `cheque_date`, `bank_name`, `created_at`) 
                  VALUES (
                      '" . $this->invoice_id . "', 
                      '" . $this->method_id . "', 
                      '" . $this->amount . "', 
                      '" . $this->reference_no . "', 
                      '" . $this->cheque_date . "', 
                      '" . $this->bank_name . "', 
                      NOW()
                  )";


        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        }
        return false;
    }

    // Update an existing payment
    public function update()
    {
        $query = "UPDATE `invoice_payments` SET
                  `invoice_id` = '" . $this->invoice_id . "',
                  `method_id` = '" . $this->method_id . "',
                  `amount` = '" . $this->amount . "',
                  `reference_no` = '" . $this->reference_no . "',
                  `cheque_date` = '" . $this->cheque_date . "',
                  `bank_name` = '" . $this->bank_name . "'
                  WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete a payment by ID
    public function delete()
    {
        $query = "DELETE FROM `invoice_payments` WHERE `id` = '" . $this->id . "'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all payments
    public function all()
    {
        $query = "SELECT * FROM `invoice_payments` ORDER BY `created_at` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    // Get payments by invoice ID
    public function getByInvoiceId($invoice_id)
    {
        $query = "SELECT * FROM `invoice_payments` 
                  WHERE `invoice_id` = '" . (int)$invoice_id . "' 
                  ORDER BY `created_at` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    // Get payments by method ID
    public function getByMethodId($method_id)
    {
        $query = "SELECT * FROM `invoice_payments` 
                  WHERE `method_id` = '" . (int)$method_id . "' 
                  ORDER BY `created_at` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    // Get total paid amount for an invoice
    public function getTotalPaidAmount($invoiceNo)
    {
        $db = new Database();
        $query = "SELECT COALESCE(SUM(amount), 0) as total_paid 
                 FROM invoice_payments ip
                 INNER JOIN sales_invoice si ON ip.invoice_id = si.id
                 WHERE si.invoice_no = '" . $db->escapeString($invoiceNo) . "'";

        $result = $db->readQuery($query);
        $row = mysqli_fetch_assoc($result);

        return (float)$row['total_paid'];
    }
}
