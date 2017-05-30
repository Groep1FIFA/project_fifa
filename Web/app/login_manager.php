<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 4/21/2017
 * Time: 11:08 AM
 */
require ('database.php');

if (isset($_POST['form-type'])){
    $formType = $_POST['form-type'];
    if ($formType == 'admin_login'){
        if (!empty($_POST['username']) && !empty($_POST['password'])){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $sqlSel = "SELECT * FROM tbl_admin WHERE username = :username AND password = :password";
            $login = $db_conn->prepare($sqlSel);
            $login->execute(['username' => $username, 'password' => $password]);

            if ($login->rowCount() >= 1){
                session_start();
                $_SESSION['admin_login'] = true;

                $message = 'You have been logged in';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
            else{
                $message = 'The username or password was invalid';
                header("Location: ../public/home/?message=$message");
            }
        }
        else{
            $message = 'Please fill in the forms';
            header("Location: ../public/home/?message=$message");
        }
    }
    elseif($formType == 'logout'){
        session_start();
        session_unset();
        session_destroy();

        $message = 'You have been logged out';
        header("Location: ../public/home/?message=$message");
    }
    else{
        $message = 'failed';
        header("Location: ../public/home/?message=$message");
    }
}
else{
    $message = 'failed';
    header("Location: ../public/home/?message=$message");
}