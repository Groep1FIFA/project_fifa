<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 4/21/2017
 * Time: 11:19 AM
 */
require ('database.php');

if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['personal_id'])){
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $id = trim($_POST['personal_id']);

    $sqlSel = "SELECT * FROM tbl_players WHERE student_id = :id";
    $sqlCount = $db_conn->prepare($sqlSel);
    $sqlCount->execute(['id' => $id]);

    if ($sqlCount->rowCount() == 0){
        $sqlIns = "INSERT INTO tbl_players (first_name, last_name, student_id) VALUES (:fname, :lname, :id)";
        $sqlPre = $db_conn->prepare($sqlIns);
        $sqlPre->execute(['fname' => $fname, 'lname' => $lname, 'id' => $id]);

        $message = 'You have been registered';
        header("Location: ../public/home/?message=$message");
    }
    else{
        $message = 'Your ID has been used already';
        header("Location: ../public/home/?message=$message");
    }
}
else{
    $message = 'Please fill in the forms';
    header("Location: ../public/home/?message=$message");
}