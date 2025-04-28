<?php

class MarketingExecutive
{
    public $id;
    public $code;
    public $full_name;
    public $nic;
    public $mobile_number;
    public $whatsapp_number;
    public $target_month;
    public $target;
    public $joined_date;
    public $is_active;
    public $remark;
    public $created_at;

    public $queue;

    // Constructor to initialize the object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `marketing_executive` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->code = $result['code'];
                $this->full_name = $result['full_name'];
                $this->nic = $result['nic'];
                $this->mobile_number = $result['mobile_number'];
                $this->whatsapp_number = $result['whatsapp_number'];
                $this->target_month = $result['target_month'];
                $this->target = $result['target'];
                $this->joined_date = $result['joined_date'];
                $this->is_active = $result['is_active'];
                $this->remark = $result['remark'];
                $this->created_at = $result['created_at'];
                $this->queue = $result['queue'];

            }
        }
    }

    // Create a new marketing executive
    public function create()
    {
        $query = "INSERT INTO `marketing_executive` 
            (`code`, `full_name`, `nic`, `mobile_number`, `whatsapp_number`, `target_month`, `target`, `joined_date`, `is_active`, `remark`, `created_at`) 
            VALUES 
            (
                '$this->code', '$this->full_name', '$this->nic', '$this->mobile_number', '$this->whatsapp_number',
                '$this->target_month', '$this->target', '$this->joined_date', '$this->is_active', '$this->remark', NOW()
            )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing marketing executive
    public function update()
    {
        $query = "UPDATE `marketing_executive` SET 
            `code` = '$this->code',
            `full_name` = '$this->full_name',
            `nic` = '$this->nic',
            `mobile_number` = '$this->mobile_number',
            `whatsapp_number` = '$this->whatsapp_number',
            `target_month` = '$this->target_month',
            `target` = '$this->target',
            `joined_date` = '$this->joined_date',
            `is_active` = '$this->is_active',
            `remark` = '$this->remark'
            WHERE `id` = '$this->id'";

        $db = new Database();
        return $db->readQuery($query);
    }

    // Delete a marketing executive
    public function delete()
    {
        $query = "DELETE FROM `marketing_executive` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all marketing executives
    public function all()
    {
        $query = "SELECT * FROM `marketing_executive` ORDER BY `full_name` ASC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getActiveExecutives()
    {
        $query = "SELECT * FROM `marketing_executive` where is_active = 1 ORDER BY `queue` ASC";
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
        $query = "SELECT * FROM `marketing_executive` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }


}
?>
