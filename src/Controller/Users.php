<?php

namespace TLMK\Library;
require "../../vendor/autoload.php";

//use TLMK\Library\Users as Users;
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);
//print_r($_POST);
if (isset($_POST['action'])) {
//    echo 'action set \n!';
    $userAction = new Users;

    if ($_POST['action'] == 'insert') {
//        echo 'insert On!';
        if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['password'])
            && isset($_POST['tel']) && isset($_POST['email'])
        ) {
            $response = $userAction->addUser($_POST['username'], $_POST['name'], $_POST['tel'], $_POST['password'],
                $_POST['email']);
            echo "<p class='white-text'><i class='material-icons teal-text'>done</i>Usuario Registrado</p>";
        } else {
            echo "<p class='white-text'><i class='material-icons teal-text'>remove_circle</i>{$response}</p>";
        }
    }

    if ($_POST['action'] == 'checkUserName') {
        if (isset($_POST['username'])) {
            $response = $userAction->checkUserName($_POST['username']);
            echo $response;
        }
    }

    if ($_POST['action'] == 'update') {
        if (isset($_POST['_id'])) {

        }
    }
    if ($_POST['action'] == 'delete') {

    }
    
    if ($_POST['action'] == 'userList') {
        $list = $userAction->listUsers();
        foreach ($list as $user) {
            echo '<tr>';
            echo "<td>{$user['name']}</td>";
            echo "<td>{$user['phone']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td><a id='{$user['_id']}' class='btn-flat red accent-2 white-text'>
<i class='material-icons'>chevron_right</i></a></td>";
            echo '</tr>';
        }

    }
    if($_POST['action']== 'getUsersOptionList'){
        $list =$userAction->listUsers();
        foreach ($list as $user) {
            echo "<option value='{$user['_id']}'>{$user['user_name']}</option>";
        }

    }
}