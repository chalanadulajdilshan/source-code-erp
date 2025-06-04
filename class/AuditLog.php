<?php

class AuditLog
{
    public $id;
    public $table_name;
    public $record_id;
    public $action;
    public $description;
    public $user_id;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `audit_log` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->table_name = $result['table_name'];
                $this->record_id = $result['record_id'];
                $this->action = $result['action'];
                $this->description = $result['description'];
                $this->user_id = $result['user_id'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `audit_log` (
            `table_name`, `record_id`, `action`, `description`, `user_id`, `created_at`
        ) VALUES (
            '$this->table_name', '$this->record_id', '$this->action', '$this->description', '$this->user_id', NOW()
        )";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    public function all()
    {
        $query = "SELECT * FROM `audit_log` ORDER BY `created_at` DESC";
        $db = new Database();
        $result = $db->readQuery($query);

        $array_res = [];
        while ($row = mysqli_fetch_array($result)) {
            $array_res[] = $row;
        }

        return $array_res;
    }

    public function delete()
    {
        $query = "DELETE FROM `audit_log` WHERE `id` = '$this->id'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function getLastID()
    {
        $query = "SELECT `id` FROM `audit_log` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'] ?? null;
    }

    public function getLogsByRecord($table_name, $record_id)
    {
        $query = "SELECT * FROM `audit_log` 
                  WHERE `table_name` = '$table_name' 
                  AND `record_id` = '$record_id' 
                  ORDER BY `created_at` DESC";

        $db = new Database();
        $result = $db->readQuery($query);

        $logs = [];
        while ($row = mysqli_fetch_array($result)) {
            $logs[] = $row;
        }

        return $logs;
    }
}

?>