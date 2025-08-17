<?php

class UserPermission
{
    public $id;
    public $user_id;
    public $page_id;
    public $add_page;
    public $edit_page;
    public $search_page;
    public $delete_page;
    public $print_page;
    public $other_page;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `user_permission` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->user_id = $result['user_id'];
                $this->page_id = $result['page_id'];
                $this->add_page = $result['add_page'];
                $this->edit_page = $result['edit_page'];
                $this->search_page = $result['search_page'];
                $this->delete_page = $result['delete_page'];
                $this->print_page = $result['print_page'];
                $this->other_page = $result['other_page'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `user_permission` 
            (`user_id`, `page_id`, `add_page`, `edit_page`, `search_page`, `delete_page`, `print_page`, `other_page`) 
            VALUES (
                '" . $this->user_id . "',
                '" . $this->page_id . "',
                '" . $this->add_page . "',
                '" . $this->edit_page . "',
                '" . $this->search_page . "',
                '" . $this->delete_page . "',
                '" . $this->print_page . "',
                '" . $this->other_page . "'
            )";

        $db = new Database();
        $result = $db->readQuery($query);
        return $result ? mysqli_insert_id($db->DB_CON) : false;
    }

    public function update()
    {
        $query = "UPDATE `user_permission` SET 
            `user_id` = '" . $this->user_id . "',
            `page_id` = '" . $this->page_id . "',
            `add_page` = '" . $this->add_page . "',
            `edit_page` = '" . $this->edit_page . "',
            `search_page` = '" . $this->search_page . "',
            `delete_page` = '" . $this->delete_page . "',
            `print_page` = '" . $this->print_page . "',
            `other_page` = '" . $this->other_page . "'
            WHERE `id` = " . (int) $this->id;

        $db = new Database();
        $result = $db->readQuery($query);
        return $result ? $this->__construct($this->id) : false;
    }

    public function delete()
    {
        $query = "DELETE FROM `user_permission` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `user_permission` ORDER BY `user_id` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = [];

        while ($row = mysqli_fetch_array($result)) {
            $array_res[] = $row;
        }

        return $array_res;
    }

    public function getPermissions($user_id, $page_id)
    {
        $query = "SELECT `add_page`, `edit_page`, `search_page`, `delete_page`, `print_page`, `other_page` 
                  FROM `user_permission` 
                  WHERE `user_id` = $user_id AND `page_id` = $page_id 
                  LIMIT 1";

        $db = new Database();
        $result = mysqli_fetch_assoc($db->readQuery($query));

        return $result ? $result : [
            'add_page' => 0,
            'edit_page' => 0,
            'search_page' => 0,
            'delete_page' => 0,
            'print_page' => 0,
            'other_page' => 0
        ];
    }


    public static function checkAccess($pageId, $redirectTo = 'no-permission.php')
    {


        if (!isset($_SESSION['id'])) {
            header("Location: login.php");
            exit();
        }

        $userId = (int) $_SESSION['id'];
        $pageId = (int) $pageId;

        $db = new Database();
        $query = "SELECT `add_page`, `edit_page`, `delete_page`, `search_page`, `print_page`, `other_page`
              FROM `user_permission`
              WHERE `user_id` = $userId AND `page_id` = $pageId
              LIMIT 1";

             
        $result = $db->readQuery($query);

        if ($row = mysqli_fetch_assoc($result)) {
            // Check if at least one permission is granted
            if (
                !$row['add_page'] &&
                !$row['edit_page'] &&
                !$row['delete_page'] &&
                !$row['search_page'] &&
                !$row['print_page'] &&
                !$row['other_page']
            ) {
                header("Location: $redirectTo");
                exit();
            }
        } else {
            // No permission row found at all
            header("Location: $redirectTo");
            exit();
        }
    }

    public function getUserPermissionByPages($user_id, $page_id)
    {
        $query = "SELECT `add_page`, `edit_page`, `search_page`, `delete_page`, `print_page`, `other_page`
                  FROM `user_permission` 
                  WHERE `user_id` = " . (int) $user_id . " 
                  AND `page_id` = " . (int) $page_id;

        $db = new Database();
        $result = $db->readQuery($query);

        if ($row = mysqli_fetch_assoc($result)) {
            return [
                'add' => (bool) $row['add_page'],
                'edit' => (bool) $row['edit_page'],
                'search' => (bool) $row['search_page'],
                'delete' => (bool) $row['delete_page'],
                'print' => (bool) $row['print_page'],
                'other' => (bool) $row['other_page']
            ];
        }

        return [
            'add' => false,
            'edit' => false,
            'search' => false,
            'delete' => false,
            'print' => false,
            'other' => false
        ];
    }


    public function hasPermission($user_id, $page_id)
    {
        $query = "SELECT `add_page`, `edit_page`, `search_page`, `delete_page`, `print_page`, `other_page`
              FROM `user_permission` 
              WHERE `user_id` = " . (int) $user_id . " 
              AND `page_id` = " . (int) $page_id . " 
              LIMIT 1";

        $db = new Database();
        $result = $db->readQuery($query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return [
                'add_page' => ((int) $row['add_page'] === 1),
                'edit_page' => ((int) $row['edit_page'] === 1),
                'search_page' => ((int) $row['search_page'] === 1),
                'delete_page' => ((int) $row['delete_page'] === 1),
                'print_page' => ((int) $row['print_page'] === 1),
                'other_page' => ((int) $row['other_page'] === 1),
            ];
        } else {
            // No record found — return all false
            return [
                'add_page' => false,
                'edit_page' => false,
                'search_page' => false,
                'delete_page' => false,
                'print_page' => false,
                'other_page' => false
            ];
        }
    }



}
