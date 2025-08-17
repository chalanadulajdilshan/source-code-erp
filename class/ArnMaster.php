<?php

class ArnMaster
{
    public $id;
    public $arn_no;
    public $lc_tt_no;
    public $pi_no;
    public $po_date;
    public $supplier_id;
    public $ci_no;
    public $bl_no;
    public $container_size;
    public $category;
    public $brand;
    public $department;
    public $po_no;
    public $country;
    public $order_by;
    public $purchase_type;
    public $arn_status;
    public $remark;
    public $invoice_date;
    public $entry_date;
    public $delivery_date;
    public $credit_note_amount;
    public $sub_arn_value;
    public $total_discount;
    public $total_arn_value;
    public $total_received_qty;
    public $total_order_qty;
    public $created_at;
    public $is_cancelled;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `arn_master` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));
            if ($result) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

 
    public function getArnIdByArnNo($arn_no) {
        if (empty($arn_no)) {
            return false;
        }
        
        $db = new Database();
        $arn_no = $db->escapeString($arn_no);
        
        $query = "SELECT `id` FROM `arn_master` WHERE `arn_no` = '{$arn_no}' LIMIT 1";
        $result = $db->readQuery($query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['id'];
        }
        
        return false;
    }

    public function create()
    {
        $query = "INSERT INTO `arn_master` (
            `arn_no`, `lc_tt_no`, `pi_no`, `po_date`, `supplier_id`, `ci_no`, `bl_no`,
            `container_size`, `category`, `brand`, `department`, `po_no`, `country`, `order_by`,
            `purchase_type`, `arn_status`, `remark`, `invoice_date`, `entry_date`, `delivery_date`,
            `credit_note_amount`, `sub_arn_value`, `total_discount`, `total_arn_value`,
            `total_received_qty`, `total_order_qty`, `created_at`
        ) VALUES (
            '{$this->arn_no}', '{$this->lc_tt_no}', '{$this->pi_no}', '{$this->po_date}', '{$this->supplier_id}',
            '{$this->ci_no}', '{$this->bl_no}', '{$this->container_size}', '{$this->category}', '{$this->brand}',
            '{$this->department}', '{$this->po_no}', '{$this->country}', '{$this->order_by}', '{$this->purchase_type}',
            '{$this->arn_status}', '{$this->remark}', '{$this->invoice_date}', '{$this->entry_date}', '{$this->delivery_date}',
            '{$this->credit_note_amount}', '{$this->sub_arn_value}', '{$this->total_discount}', '{$this->total_arn_value}',
            '{$this->total_received_qty}', '{$this->total_order_qty}', NOW()
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        return $result ? mysqli_insert_id($db->DB_CON) : false;
    }

    public function update()
    {
        $query = "UPDATE `arn_master` SET
            `arn_no` = '{$this->arn_no}',
            `lc_tt_no` = '{$this->lc_tt_no}',
            `pi_no` = '{$this->pi_no}',
            `po_date` = '{$this->po_date}',
            `supplier_id` = '{$this->supplier_id}',
            `ci_no` = '{$this->ci_no}',
            `bl_no` = '{$this->bl_no}',
            `container_size` = '{$this->container_size}',
            `category` = '{$this->category}',
            `brand` = '{$this->brand}',
            `department` = '{$this->department}',
            `po_no` = '{$this->po_no}',
            `country` = '{$this->country}',
            `order_by` = '{$this->order_by}',
            `purchase_type` = '{$this->purchase_type}',
            `arn_status` = '{$this->arn_status}',
            `remark` = '{$this->remark}',
            `invoice_date` = '{$this->invoice_date}',
            `entry_date` = '{$this->entry_date}',
            `delivery_date` = '{$this->delivery_date}',
            `credit_note_amount` = '{$this->credit_note_amount}',
            `sub_arn_value` = '{$this->sub_arn_value}',
            `total_discount` = '{$this->total_discount}',
            `total_arn_value` = '{$this->total_arn_value}',
            `total_received_qty` = '{$this->total_received_qty}',
            `total_order_qty` = '{$this->total_order_qty}'
        WHERE `id` = '{$this->id}'";

        $db = new Database();
        $result = $db->readQuery($query);
        return $result ? $this->__construct($this->id) : false;
    }

    public function delete()
    {
        $query = "DELETE FROM `arn_master` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `arn_master` ORDER BY `id` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            $array_res[] = $row;
        }

        return $array_res;
    }

    public function getByArnId($arn_id)
    {
        $query = "
        SELECT ai.*, im.code AS item_code, im.name AS item_name, im.id AS item_id
        FROM arn_items ai
        LEFT JOIN item_master im ON ai.item_code = im.id
        WHERE ai.arn_id = '{$arn_id}'
        ORDER BY ai.id ASC
    ";

        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $array_res[] = $row;
        }

        return $array_res;
    }



    public function getLastID()
    {
        $query = "SELECT * FROM `arn_master` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result ? $result['id'] : null;
    }

    public function cancelArn($arn_id)
    {
        $db = new Database();

        // Fetch ARN details to get department
        $arn = new ArnMaster($arn_id);
        $department_id = $arn->department;

        // Mark as cancelled in related tables
        $db->readQuery("UPDATE arn_master SET is_cancelled = 1 WHERE id = '{$arn_id}'");
        $db->readQuery("UPDATE arn_items SET is_cancelled = 1 WHERE arn_id = '{$arn_id}'");
        $db->readQuery("UPDATE stock_item_tmp SET is_cancelled = 1 WHERE arn_id = '{$arn_id}'");


        // Adjust stock by deducting received quantities
        $items = $this->getByArnId($arn_id); // assumes getByArnId returns items with item_code and received_qty

        foreach ($items as $item) {
            $STOCK_MASTER = new StockMaster();


            $STOCK_MASTER->adjustQuantity($item['item_id'], $department_id, $item['received_qty'], 'deductions', 'ARN cancellation adjustment');
        }

        return true;
    }


    public function reactivateArn($arn_id)
    {
        $db = new Database();

        $query1 = "UPDATE arn_master SET is_cancelled = 0 WHERE id = '{$arn_id}'";
        $db->readQuery($query1);

        $query2 = "UPDATE arn_items SET is_cancelled = 0 WHERE arn_id = '{$arn_id}'";
        $db->readQuery($query2);

        $query3 = "UPDATE stock_item_tmp SET is_cancelled = 0 WHERE arn_id = '{$arn_id}'";
        $db->readQuery($query3);

        // Also reactivate stock transactions as needed
        // Assuming you track with arn_id in remark or separate field
        $query4 = "UPDATE stock_transaction SET is_cancelled = 0 WHERE remark LIKE '%ARN #{$arn_id}%'";
        $db->readQuery($query4);

        return true;
    }



}
