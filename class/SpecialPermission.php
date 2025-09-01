<?php

class SpecialUserPermission
{
    public $id;
    public $user_id;
    public $permission_name;
    public $status;
    public $created_at;
    public $updated_at;

    // Constructor to fetch record by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `user_id`, `permission_name`, `status`, `created_at`, `updated_at` 
                      FROM `special_user_permissions` 
                      WHERE `id` = " . (int) $id;

            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->user_id = $result['user_id'];
                $this->permission_name = $result['permission_name'];
                $this->status = $result['status'];
                $this->created_at = $result['created_at'];
                $this->updated_at = $result['updated_at'];
            }
        }
    }

    // Create a new record
    public function create()
    {
        $db = new Database();

        // First check if a permission with the same name already exists for this user
        $checkQuery = "SELECT `id` FROM `special_user_permissions` 
                      WHERE `user_id` = " . (int)$this->user_id . " 
                      AND `permission_name` = '" . $db->escapeString($this->permission_name) . "'";

        $checkResult = $db->readQuery($checkQuery);

        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row) {
                // Permission already exists, update it instead
                $this->id = $row['id'];
                return $this->update();
            }
        }

        // If we get here, create a new permission
        $query = "INSERT INTO `special_user_permissions` 
                  (`user_id`, `permission_name`, `status`, `created_at`, `updated_at`) 
                  VALUES (
                      " . (int)$this->user_id . ", 
                      '" . $db->escapeString($this->permission_name) . "', 
                      '" . $db->escapeString($this->status) . "', 
                      NOW(), 
                      NOW()
                  )";

        $result = $db->readQuery($query);

        if ($result) {
            $this->id = $db->DB_CON ? mysqli_insert_id($db->DB_CON) : 0;
            return $this->id; // Return the ID of the created record
        }

        // Log the error for debugging
        error_log("Failed to create permission. Query: " . $query);
        if ($db->DB_CON) {
            error_log("MySQL Error: " . mysqli_error($db->DB_CON));
        }
        return false;
    }

    // Update record
    public function update()
    {
        $db = new Database();

        $query = "UPDATE `special_user_permissions` SET 
                    `user_id` = " . (int)$this->user_id . ",
                    `permission_name` = '" . $db->escapeString($this->permission_name) . "',
                    `status` = '" . $db->escapeString($this->status) . "',
                    `updated_at` = NOW()
                  WHERE `id` = " . (int)$this->id;

        $result = $db->readQuery($query);

        if ($result) {
            return $this->id; // Return the ID on success
        }

        error_log("Failed to update permission. Query: " . $query . " Error: " . mysqli_error($db->DB_CON));
        return false;
    }

    // Get all records
    public function all()
    {
        $query = "SELECT `id`, `user_id`, `permission_name`, `status`, `created_at`, `updated_at` 
                  FROM `special_user_permissions` 
                  ORDER BY `created_at` DESC";

        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Get all records for a specific user
    public function getByUser($userId)
    {
        $query = "SELECT `id`, `user_id`, `permission_name`, `status`, `created_at`, `updated_at`
                  FROM `special_user_permissions`
                  WHERE `user_id` = " . (int) $userId . "
                  ORDER BY `created_at` DESC";

        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $array_res[] = $row;
            }
        } else {
            error_log("Error in getByUser: " . ($db->DB_CON ? mysqli_error($db->DB_CON) : 'No database connection'));
        }

        return $array_res;
    }
}
