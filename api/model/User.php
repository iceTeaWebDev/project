<?php
class User {
    // define properties
    private $conn;
    public $user_id;
    public $username;
    public $email;
    public $tel;
    public $address;
    public $password;
    public $role;

    public function __construct($connect)
    {
        $this->conn = $connect;
    }

    public function get_all_users() {
        $user_query = "SELECT * FROM user";
        $user_obj = $this->conn->prepare($user_query);
        if($user_obj->execute()) {
            return true;
        }
    }

    public function create_user() {
        $user_query = "INSERT INTO user (username, email, tel, address, password) VALUES (:username, :email, :tel, :address, :password)";

        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindValue(':username', $this->username, PDO::PARAM_STR);
        $user_obj->bindValue(':email', $this->email, PDO::PARAM_STR);
        $user_obj->bindValue(':tel', $this->tel, PDO::PARAM_STR);
        $user_obj->bindValue(':address', $this->address, PDO::PARAM_STR);
        $user_obj->bindValue(':password', $this->password, PDO::PARAM_STR);
        if($user_obj->execute()) {
            return true;
        }
        return false;
    }

    public function update_user() {
        $user_query = "UPDATE user SET username=:username, email=:email, tel=:tel, address=:address, password=:password WHERE user_id=:user_id";
        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindValue(':username', $this->username, PDO::PARAM_STR);
        $user_obj->bindValue(':email', $this->email, PDO::PARAM_STR);
        $user_obj->bindValue(':tel', $this->tel, PDO::PARAM_STR);
        $user_obj->bindValue(':address', $this->address, PDO::PARAM_STR);
        $user_obj->bindValue(':password', $this->password, PDO::PARAM_STR);
        $user_obj->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        if($user_obj->execute()) {
            return true;
        }
        return false;
    }

    public function delete_user() {
        $user_query = "DELETE FROM user WHERE user_id=:user_id";
        $user_obj = $this->conn->prepare($user_query);
        $user_obj->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        if($user_obj->execute()) {
            return true;
        }
        return false;
    }

    public function check_login() {
        $user_query = "SELECT * FROM user WHERE username=:username AND password=:password";

        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindValue(':username', $this->username, PDO::PARAM_STR);
        $user_obj->bindValue(':password', $this->password, PDO::PARAM_STR);

        if($user_obj->execute()) {
            return $user_obj->fetch(PDO::FETCH_ASSOC);
        }
        return array();
    }

    public function check_username() {
        $user_query = "SELECT * FROM user WHERE username=:username";
        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindValue(':username', $this->username, PDO::PARAM_STR);
        if($user_obj->execute()) {
            return $user_obj->fetch(PDO::FETCH_ASSOC);
        }
        return array();
    }
}