<?php

/**
 * Description of User
 *
 * @author Suharshana DsW
 * @web www.nysc.lk
 */
class User
{

    public $id;
    public $name;
    public $code;
    public $email;
    public $phone;
    public $image_name;
    public $createdAt;
    public $type;
    public $isActive;
    public $authToken;
    public $lastLogin;
    public $username;
    public $resetCode;
    private $password;
    public $show_password;
    public $company_id;
    public $department_id;


    public function __construct($id)
    {

        if ($id) {

            $query = "SELECT * FROM `user` WHERE `id`=" . $id;

            $db = new Database();

            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->code = $result['code'];
            $this->name = $result['name'];
            $this->email = $result['email'];
            $this->phone = $result['phone'];
            $this->image_name = $result['image_name'];
            $this->createdAt = $result['createdAt'];
            $this->type = $result['type'];
            $this->isActive = $result['isActive'];
            $this->authToken = $result['authToken'];
            $this->lastLogin = $result['lastLogin'];
            $this->username = $result['username'];
            $this->resetCode = $result['resetcode'];
            $this->show_password = $result['show_password'];
            $this->company_id = $result['company_id'];
            $this->department_id = $result['department_id'];

            return $result;
        }
    }

    public function create($name, $code, $type, $company_id, $active, $email, $phone, $username, $show_password, $password, $department_id)
    {
        $enPass = md5($password);

        date_default_timezone_set('Asia/Colombo');
        $createdAt = date('Y-m-d H:i:s');
        $query = "INSERT INTO `user` (`name`,code,`type`,`company_id`,`isActive`,`email`,`phone`,`createdAt`,`username`,`show_password`,`password`,`department_id`) VALUES  ('" . $name . "','" . $code . "',  '" . $type . "',  '" . $company_id . "','" . $active . "', '" . $email . "','" . $phone . "', '" . $createdAt . "', '" . $username . "',  '" . $show_password . "', '" . $enPass . "','" . $department_id . "')";

        $db = new Database();

        $result = $db->readQuery($query);
        if ($result) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function login($username, $password)
    {

        $enPass = md5($password);

        $query = "SELECT * FROM `user` WHERE `username`= '" . $username . "' AND `password`= '" . $enPass . "'";

        $db = new Database();

        $result = mysqli_fetch_array($db->readQuery($query));

        if (!$result) {

            return FALSE;
        } else {

            $this->id = $result['id'];
            $this->setAuthToken($result['id']);
            $this->setLastLogin($result['id']);
            $user = $this->__construct($result['id']);
            $this->setUserSession($user);

            return $user;
        }
    }

    public function checkOldPass($id, $password)
    {

        $enPass = md5($password);

        $query = "SELECT `id` FROM `user` WHERE `id`= '" . $id . "' AND `password`= '" . $enPass . "'";

        $db = new Database();

        $result = mysqli_fetch_array($db->readQuery($query));

        if (!$result) {

            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function changePassword($id, $password)
    {



        $enPass = md5($password);

        $query = "UPDATE  `user` SET "
            . "`password` ='" . $enPass . "' "
            . "WHERE `id` = '" . $id . "'";

        $db = new Database();

        $result = $db->readQuery($query);

        if ($result) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function all()
    {

        $query = "SELECT * FROM `user` ";

        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {

            array_push($array_res, $row);
        }
        return $array_res;
    }

    public function authenticate()
    {

        if (!isset($_SESSION)) {

            session_start();
        }

        $id = NULL;

        $authToken = NULL;

        if (isset($_SESSION["id"])) {

            $id = $_SESSION["id"];
        }



        if (isset($_SESSION["authToken"])) {

            $authToken = $_SESSION["authToken"];
        }


        $query = "SELECT `id` FROM `user` WHERE `id`= '" . $id . "' AND `authToken`= '" . $authToken . "'";

        $db = new Database();

        $result = mysqli_fetch_array($db->readQuery($query));

        if (!$result) {

            return FALSE;
        } else {



            return TRUE;
        }
    }

    public function logOut()
    {



        if (!isset($_SESSION)) {

            session_start();
        }



        unset($_SESSION["id"]);

        unset($_SESSION["name"]);

        unset($_SESSION["email"]);

        unset($_SESSION["phone"]);

        unset($_SESSION["isActive"]);

        unset($_SESSION["type"]);

        unset($_SESSION["authToken"]);

        unset($_SESSION["lastLogin"]);

        unset($_SESSION["username"]);

        return TRUE;
    }

    public function update()
    {

        $query = "UPDATE  `user` SET "
            . "`name` ='" . $this->name . "', "
            . "`username` ='" . $this->username . "', "
            . "`type` ='" . $this->type . "', "
            . "`email` ='" . $this->email . "', "
            . "`company_id` ='" . $this->company_id . "', "
            . "`department_id` ='" . $this->department_id . "', "
            . "`image_name` ='" . $this->image_name . "', "
            . "`phone` ='" . $this->phone . "'  "
            . "WHERE `id` = '" . $this->id . "'";

        $db = new Database();

        $result = $db->readQuery($query);

        if ($result) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    private function setUserSession($user)
    {

        if (!isset($_SESSION)) {
            session_start([
                'cookie_lifetime' => 3200,
            ]);
        }

        $_SESSION["id"] = $user['id'];

        $_SESSION["name"] = $user['name'];

        $_SESSION["email"] = $user['email'];

        $_SESSION["phone"] = $user['phone'];

        $_SESSION["isActive"] = $user['isActive'];

        $_SESSION["type"] = $user['type'];

        $_SESSION["authToken"] = $user['authToken'];

        $_SESSION["lastLogin"] = $user['lastLogin'];

        $_SESSION["username"] = $user['username'];
    }

    private function setAuthToken($id)
    {

        $authToken = md5(uniqid(rand(), true));

        $query = "UPDATE `user` SET `authToken` ='" . $authToken . "' WHERE `id`='" . $id . "'";

        $db = new Database();

        if ($db->readQuery($query)) {
            return $authToken;
        } else {

            return FALSE;
        }
    }

    private function setLastLogin($id)
    {



        date_default_timezone_set('Asia/Colombo');

        $now = date('Y-m-d H:i:s');

        $query = "UPDATE `user` SET `lastLogin` ='" . $now . "' WHERE `id`='" . $id . "'";

        $db = new Database();

        if ($db->readQuery($query)) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function checkEmail($email)
    {



        $query = "SELECT `email`,`username` FROM `user` WHERE `email`= '" . $email . "'";

        $db = new Database();

        $result = mysqli_fetch_array($db->readQuery($query));

        if (!$result) {

            return FALSE;
        } else {

            return $result;
        }
    }

    public function GenarateCode($email)
    {

        $rand = rand(10000, 99999);

        $query = "UPDATE  `user` SET "
            . "`resetcode` ='" . $rand . "' "
            . "WHERE `email` = '" . $email . "'";

        $db = new Database();

        $result = $db->readQuery($query);

        if ($result) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function SelectForgetUser($email)
    {



        if ($email) {



            $query = "SELECT `email`,`username`,`resetcode` FROM `user` WHERE `email`= '" . $email . "'";

            $db = new Database();

            $result = mysqli_fetch_array($db->readQuery($query));

            $this->username = $result['username'];

            $this->email = $result['email'];

            $this->resetCode = $result['resetcode'];

            return $result;
        }
    }

    public function SelectResetCode($code)
    {
        $query = "SELECT `id` FROM `user` WHERE `resetcode` = '" . $code . "' AND `resetcode` IS NOT NULL";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['id'];
        } else {
            return false;
        }
    }

    public function updatePassword($password, $code)
    {



        $enPass = md5($password);

        $query = "UPDATE  `user` SET "
            . "`password` ='" . $enPass . "' "
            . "WHERE `resetcode` = '" . $code . "'";

        $db = new Database();

        $result = $db->readQuery($query);

        if ($result) {

            return TRUE;
        } else {

            return FALSE;
        }
    }


    public function getLastID()
    {
        $query = "SELECT * FROM `user` ORDER BY `id` DESC LIMIT 1";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result['id'];
    }

    public function getActiveUsers()
    {
        $query = "SELECT * FROM `user` WHERE `isActive` = 1";
        $db = new Database();
        $result = $db->readQuery($query);
        $array_res = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($array_res, $row);
        }
        return $array_res;
    }

    public function verifyPassword($password)
    {
        $enPass = md5($password);
        $query = "SELECT `id` FROM `user` WHERE `id` = " . $this->id . " AND `password` = '" . $enPass . "'";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result ? true : false;
    }

    // Add this method to your User.php class

    public function clearResetCode($code)
    {
        $query = "UPDATE `user` SET `resetcode` = NULL WHERE `resetcode` = '" . $code . "'";
        $db = new Database();
        $result = $db->readQuery($query);

        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
