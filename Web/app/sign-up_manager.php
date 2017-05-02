<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 4/21/2017
 * Time: 11:19 AM
 */
require ('database.php');

if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['personal_id'])){
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $id = $_POST['personal_id'];

    $sqlSel = "SELECT * FROM tbl_players WHERE student_id = '$id'";
    $sqlCount = $db_conn->query($sqlSel)->rowCount();

    if ($sqlCount == 0){
        $sqlIns = "INSERT INTO tbl_players (first_name, last_name, student_id) VALUES ('$fname', '$lname', '$id')";
        $db_conn->query($sqlIns);

        $message = 'You have been registered';
        header("Location: ../public/index.php?message=$message");
    }
    else{
        $message = 'Your ID has been used already';
        header("Location: ../public/index.php?message=$message");
    }
}
else{
    $message = 'Please fill in the forms';
    header("Location: ../public/index.php?message=$message");
}