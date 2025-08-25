<?php

/**

 * Description of User

 *

 * @author sublime holdings

 * @web www.sublime.lk

 * */
class Database
{

    private $host = 'localhost';
    private $name = 'chalcepi_erp';
    private $user = 'chalcepi_erp';
    private $password = '}v+kRGNUPxtr';
    public $DB_CON = '';

    // private $host = 'localhost';
    // private $name = 'source_code_erp';
    // private $user = 'root';
    // private $password = '';
    // public $DB_CON = '';

    public function __construct()
    {

        $this->DB_CON = mysqli_connect($this->host, $this->user, $this->password, $this->name);
    }

    public function readQuery($query)
    {
        $result = mysqli_query($this->DB_CON, $query) or die(mysqli_error());

        return $result;
    }
    public function escapeString($string)
    {
        return $this->DB_CON->real_escape_string($string);
    }
}
