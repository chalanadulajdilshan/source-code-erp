<?php

class DocumentTracking
{
    public $id;
    public $company_code;
    public $accounting_year_start;
    public $accounting_year_end;
    public $item_id;
    public $invoice_id;
    public $cash_id;
    public $credit_id;
    public $quotation_id;
    public $po_id;
    public $pr_id;
    public $arn_id;
    public $payment_receipt_id;
    public $sales_return_id;
    public $vat_percentage;
    public $created_at;
    public $updated_at;

    // Constructor to initialize the object using ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `document_tracking` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    // Create a new document_tracking record
    public function create()
    {
        $query = "INSERT INTO `document_tracking` (
            `company_code`, `accounting_year_start`, `accounting_year_end`, `invoice_id`, 
            `quotation_id`, `arn_id`, `vat_percentage`, `created_at`, `updated_at`
        ) VALUES (
            '{$this->company_code}', '{$this->accounting_year_start}', '{$this->accounting_year_end}', 
            '{$this->invoice_id}', '{$this->quotation_id}', '{$this->arn_id}', '{$this->vat_percentage}', 
            NOW(), NOW()
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing record
    public function update()
    {
        $query = "UPDATE `document_tracking` SET 
            `company_code` = '{$this->company_code}',
            `accounting_year_start` = '{$this->accounting_year_start}',
            `accounting_year_end` = '{$this->accounting_year_end}',
            `invoice_id` = '{$this->invoice_id}',
            `quotation_id` = '{$this->quotation_id}',
            `arn_id` = '{$this->arn_id}',
            `vat_percentage` = '{$this->vat_percentage}',
            `updated_at` = NOW()
        WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a record by ID
    public function delete()
    {
        $query = "DELETE FROM `document_tracking` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all records
    public function all()
    {
        $query = "SELECT * FROM `document_tracking` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get all records by company ID and accounting year range 
    public function getAllByCompanyAndYear($company_id, $year_start, $year_end)
    {
        $query = "SELECT `id` FROM `document_tracking` 
              WHERE `company_code` = '" . (int) $company_id . "'
              AND `accounting_year_start`  <= '" . $year_start . "'
              AND `accounting_year_end` >= '" . $year_end . "' and `status` = 1";


        $db = new Database();
        $result = $db->readQuery($query);
        $ids = [];

        while ($row = mysqli_fetch_array($result)) {
            $ids[] = $row['id'];
        }

        return $ids;
    }

    //update Ids
    public function incrementDocumentId($type)
    {
        $db = new Database();

        // Map accepted types to column names
        $columns = [
            'item' => 'item_id',
            'purchase' => 'po_id',
            'quotation' => 'quotation_id',
            'invoice' => 'invoice_id',
            'cash' => 'cash_id',
            'credit' => 'credit_id',
            'sales_return' => 'sales_return_id',
            'payment_receipt' => 'payment_receipt_id',
            'arn' => 'arn_id'
        ];

        // Check if valid type
        if (!array_key_exists($type, $columns)) {
            return false;
        }

        $column = $columns[$type];

        // Fetch current value
        $query = "SELECT `$column` FROM `document_tracking` WHERE `status` = 1 LIMIT 1";

        $result = $db->readQuery($query);
        $row = mysqli_fetch_array($result);

        if ($row) {
            $new_id = (int) $row[$column] + 1;

            $update_query = "UPDATE `document_tracking` SET 
                            `$column` = '$new_id',
                            `updated_at` = NOW() ";

            $db->readQuery($update_query);
            return $new_id;
        }

        return false;
    }
}
