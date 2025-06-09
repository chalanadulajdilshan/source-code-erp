<?php

class Pages
{
    public $id;
    public $page_category;
    public $sub_page_category;
    public $page_name;
    public $page_url;

    // Constructor to initialize the Page object with an ID (fetch data from the DB)
    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT  * FROM `pages` WHERE `id` = " . (int) $id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->page_category = $result['page_category'];
                $this->sub_page_category = $result['sub_page_category'];
                $this->page_name = $result['page_name'];
                $this->page_url = $result['page_url'];
            }
        }
    }

    // Create a new page record in the database
    public function create()
    {
        $query = "INSERT INTO `pages` (`page_category`,`sub_page_category`, `page_name`, `page_url`) VALUES (
            '" . $this->page_category . "',
             '" . $this->sub_page_category . "',
            '" . $this->page_name . "',
            '" . $this->page_url . "')";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return mysqli_insert_id($db->DB_CON); // Return the ID of the newly inserted record
        } else {
            return false; // Return false if the insertion fails
        }
    }

    // Update an existing page record
    public function update()
    {
        $query = "UPDATE `pages` SET 
            `page_category` = '" . $this->page_category . "',
            `sub_page_category` = '" . $this->sub_page_category . "',
            `page_name` = '" . $this->page_name . "',
            `page_url` = '" . $this->page_url . "'
            WHERE `id` = " . (int) $this->id;
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return true; // Return true if the update is successful
        } else {
            return false; // Return false if the update fails
        }
    }

    // Delete a page record by ID
    public function delete()
    {
        $query = "DELETE FROM `pages` WHERE `id` = " . (int) $this->id;
        $db = new Database();
        return $db->readQuery($query);
    }

    // Retrieve all page records
    public function all()
    {
        $query = "SELECT * FROM `pages` ORDER BY `page_category` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    // Retrieve pages by category
    public function getPagesByCategory($category)
    {
        $query = "SELECT * FROM `pages` WHERE `page_category` = '" . $category . "' ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }

    public function getPagesBySubCategory($sub_category)
    {
        $query = "SELECT * FROM `pages` WHERE `sub_page_category` = '" . $sub_category . "' ORDER BY `queue` ASC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();

        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }

        return $array_res;
    }


}
?>