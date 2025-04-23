<?php

class UserPermission
{
    public $id;
    public $user_id;
    public $page_id;
    public $permission_id;

    // Constructor: Load data by ID
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT `id`, `user_id`, `page_id`, `permission_id` FROM `user_permission` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->user_id = $result['user_id'];
                $this->page_id = $result['page_id'];
                $this->permission_id = $result['permission_id'];
            }
        }
    }

    // Create a new user permission
    public function create()
    {
        $query = "INSERT INTO `user_permission` (`user_id`, `page_id`, `permission_id`) VALUES (
            '" . $this->user_id . "',
            '" . $this->page_id . "',
            '" . $this->permission_id . "')";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON);
        } else {
            return false;
        }
    }

    // Update an existing user permission
    public function update()
    {
        $query = "UPDATE `user_permission` SET 
            `user_id` = '" . $this->user_id . "',
            `page_id` = '" . $this->page_id . "',
            `permission_id` = '" . $this->permission_id . "'
            WHERE `id` = " . (int) $this->id;

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return $this->__construct($this->id);
        } else {
            return false;
        }
    }

    // Delete a user permission
    public function delete()
    {
        $query = "DELETE FROM `user_permission` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Get all user permissions
    public function all()
    {
        $query = "SELECT `id`, `user_id`, `page_id`, `permission_id` FROM `user_permission` ORDER BY `user_id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getUserPermissionByPages($user_id, $page_id)
    {
        $query = "SELECT * FROM `user_permission` WHERE `user_id` = $user_id AND `page_id` = $page_id ORDER BY `user_id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];
    
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
    
        return $array_res;
    }
    


    public static function checkAccess($pageId, $redirectTo = 'no-permission.php')
    {
        if (!isset($_SESSION['id'])) {
            header("Location: login.php"); // Redirect to login if not logged in
            exit();
        }

        $userId = $_SESSION['id'];
        $db = new Database();
        $query = "SELECT `id` FROM `user_permission` WHERE `user_id` = '$userId' AND `page_id` = '$pageId'";
        $result = $db->readQuery($query);

        if (mysqli_num_rows($result) == 0) {
            header("Location: $redirectTo"); // Redirect if no permission
            exit();
        }
    }

}
?>