<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 4/21/2017
 * Time: 11:08 AM
 */
require ("database.php");
if (isset($_POST['form-type'])){
    $formType = $_POST['form-type'];

    if ($formType == 'createTeam'){
        if (!empty($_POST['teamName'])){
            $teamName = $_POST['teamName'];

            $sqlSel = "SELECT * FROM tbl_teams WHERE name = '$teamName'";
            $count = $db_conn->query($sqlSel)->rowCount();

            if ($count == 0){
                $sqlIns = "INSERT INTO tbl_teams (name) VALUES ('$teamName')";
                $db_conn->query($sqlIns);

                $message = 'Team succesfully added';
                header("Location: ../public/admin_panel.php?message=$message");
            }
        }
        else{
            $message = 'please enter a team name';
            header("Location: ../public/admin_panel.php?message=$message");
        }
    }
    else{
        $message = 'Failed';
        header("Location: ../public/admin_panel.php?message=$message");
    }
}
else{
    $message = 'Failed';
    header("Location: ../public/admin_panel.php?message=$message");
}