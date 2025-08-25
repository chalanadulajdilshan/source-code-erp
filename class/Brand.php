<?php



class Brand
{
    public $id;
    public $category_id;
    public $name;
    public $discount;
    public $is_active;
    public $remark;
    public $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `brands` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->category_id = $result['category_id'];
                $this->name = $result['name'];
                $this->discount = $result['discount'];
                $this->is_active = $result['is_active'];
                $this->remark = $result['remark'];
                $this->created_at = $result['created_at'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `brands` (`category_id`, `name`, `discount`, `is_active`, `remark`, `created_at`) 
                  VALUES (
                    '{$this->category_id}', 
                    '{$this->name}', 
                    '{$this->discount}', 
                    '{$this->is_active}', 
                    '{$this->remark}', 
                    NOW()
                  )";
        $db = new Database();
        return $db->readQuery($query) ? mysqli_insert_id($db->DB_CON) : false;
    }

    public function update()
    {
        $query = "UPDATE `brands` 
                  SET 
                    `category_id` = '{$this->category_id}', 
                    `name` = '{$this->name}', 
                    `discount` = '{$this->discount}', 
                    `is_active` = '{$this->is_active}', 
                    `remark` = '{$this->remark}'
                  WHERE `id` = '{$this->id}'";



        $db = new Database();
        return $db->readQuery($query);
    }

    public function delete()
    {
        $query = "DELETE FROM `brands` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `brands` ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function activeBrands()
    {
        $query = "SELECT * FROM `brands` WHERE is_active = 1 ORDER BY name ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }
}
