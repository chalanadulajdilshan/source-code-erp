<?php
class Database
{
    private $host;
    private $name;
    private $user;
    private $password;
    public $DB_CON;
    public function __construct()
    {
        // Detect environment
        if ($this->isLocalServer()) {
            // Local DB settings
            $this->host = 'localhost';
            $this->name = 'source_code_erp';
            $this->user = 'root';
            $this->password = '';
        } else {
            // Online DB settings
            $this->host = 'localhost';
            $this->name = 'chalcepi_erp';
            $this->user = 'chalcepi_erp';
            $this->password = '}v+kRGNUPxtr';
            $this->DB_CON ='';
        }

        // Connect
        $this->DB_CON = mysqli_connect($this->host, $this->user, $this->password, $this->name);

        if (!$this->DB_CON) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }

    private function isLocalServer()
    {
        // Method 1: Check hostname
        if (in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])) {
            return true;
        }

        return false;
    }

    public function readQuery($query)
    {
        $result = mysqli_query($this->DB_CON, $query) or die(mysqli_error($this->DB_CON));
        return $result;
    }

    public function escapeString($string)
    {
        return $this->DB_CON->real_escape_string($string);
    }
}
