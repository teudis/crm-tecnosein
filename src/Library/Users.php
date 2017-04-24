<?php

namespace TLMK\Library;
use TLMK\DB\Database as Database;


class Users
{
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function addUser($username, $name, $phone, $pass, $email)
    {

        // Seguridad en funtion
        if (isset($_SESSION['tkn_sg']) &&  isset($_SESSION['username']) && $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT']) 
        {
            $salt = $this->generateRandomString();
            $password = password_hash($pass . $salt, PASSWORD_BCRYPT);
            $db = new Database();
            $sql = <<< SQL
            INSERT INTO users(user_name, name, phone, password, salt, email)
            VALUES (?,?,?,?,?,?)
SQL;

        $addUser = $db->insertRow($sql, [$username, $name, $phone, $password, $salt, $email]);
        $db->disconnect();
        if ($addUser == 1) {
            return 'Usuario registrado';
        } else {
            return 'Ups, Algo ha salido mal';
        }

      }
        else
        {

             header('Location: ../');
             exit;
        }

    }

    public function login($username, $pass)
    {
        $sql = <<< SQL
SELECT `_id`, user_name, name, password, salt, phone, level FROM users WHERE user_name=?
SQL;
        $db = new Database();
        $user = $db->getRow($sql, [$username]);
        $db->disconnect();
//        print_r($user);
        $logged = password_verify($pass . $user['salt'], $user['password']);
        if ($logged == true) {
            $_SESSION['_id'] = $user['_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['phone'] = $user['phone'];
            //session para controlar el IP del usuario y navegador
            $_SESSION['navegador'] = $_SERVER['HTTP_USER_AGENT']; 
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR']; 
            return 1;
        } else {
            return -1;
        }
    }

    public function checkUserName($username)
    {
        $sql = <<< SQL
SELECT count(user_name) FROM users WHERE user_name=?
SQL;
        $db = new Database();
        $user = $db->getRow($sql, [$username]);
        $db->disconnect();
        return $user;
    }


     public function getUserbyuser($username)
    {
        $sql = <<< SQL
SELECT `_id`, user_name, name, password, salt, phone, level  FROM users WHERE  user_name=?
SQL;
        $db = new Database();
        $user = $db->getRow($sql, [$username]);
        $db->disconnect();
        return $user;
    }

    public function updateUser($userId, $name = null, $phone = null, $email = null)
    {
          // Seguridad en funtion
        if (isset($_SESSION['tkn_sg']) &&  isset($_SESSION['username']) && $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT']) 
        {
        $sql = <<< SQL
SELECT name, phone, email FROM users WHERE `_id`=$userId
SQL;
        $db = new Database();
        $user = $db->getRow($sql, [$userId]);
        if ($name = null) {
            $name = $user['name'];
        }
        if ($phone = null) {
            $phone = $user['phone'];
        }
        if ($email = null) {
            $email = $user['email'];
        }
        $update = <<< SQL
UPDATE users SET name=?, phone=?, email=? WHERE `_id`=?
SQL;
        $db->updateRow($update, [$name, $phone, $email, $userId]);
        $db->disconnect();

    }
    else
        {
             header('Location: ../');
             exit;            
        }
    }

    public function deleteUser($userId)
    {
          // Seguridad en funtion
        if (isset($_SESSION['tkn_sg']) &&  isset($_SESSION['username']) && $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT']) 
        {
        $sql = <<< SQL
DELETE FROM users WHERE `_id`=?
SQL;
        $db = new Database();
        $db->deleteRow($sql, [$userId]);

        }
        else
        {
            header('Location: ../');
             exit;              
        }
    }

    public function listUsers()
    {
           // Seguridad en funtion
        if (isset($_SESSION['tkn_sg']) &&  isset($_SESSION['username']) && $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT']) 
        {
        $db = new Database();
        $sql = <<< SQL
SELECT `_id`, name, phone, email, user_name FROM users
SQL;
        return $db->getRows($sql);
        }
        else
        {
            header('Location: ../');
             exit;              
        }
    }

    public function checkLevel($userId){
          // Seguridad en funtion
        if (isset($_SESSION['tkn_sg']) &&  isset($_SESSION['username']) && $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT']) 
        {
        $db = new Database();
        $sql= <<< SQL
SELECT level FROM users WHERE `_id`=?
SQL;
        $userlevel = $db->getRow($sql,[$userId]);
        $db->disconnect();
        return $userlevel;

        }
        else
        {

            header('Location: ../');
             exit; 
        }
    }

}