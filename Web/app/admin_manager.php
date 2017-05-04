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
    elseif ($formType == 'addToTeam'){
        if (!empty($_POST['addToTeam'])){
            $addTeam = $_POST['addToTeam'];
            $playerId = $_POST['player_id'];

            $sqlSel = "SELECT * FROM tbl_teams WHERE name = '$addTeam'";
            $sqlCount = $db_conn->query($sqlSel)->rowCount();

            if ($sqlCount == 1){
                $team_id = "SELECT * FROM tbl_teams WHERE name = '$addTeam'";
                $team_id = $db_conn->query($team_id)->fetchAll(PDO::FETCH_ASSOC);
                $team_id = $team_id[0]['id'];

                $sqlUpd = "UPDATE tbl_players SET team_id = '$team_id' WHERE id = '$playerId'";
                $db_conn->query($sqlUpd);

                $message = "Player added to $addTeam";
                header("Location: ../public/admin_panel.php?message=$message");
            }
            else{
                $message = 'Team does not exists';
                header("Location: ../public/admin_panel.php?message=$message");
            }
        }
        else{

        }
    }
    elseif ($formType == 'changeTeam'){
        $playerId = $_POST['player_id'];

        $sqlSel = "SELECT * FROM tbl_players WHERE id = '$playerId'";
        $playerName = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);

        $sqlUpd = "UPDATE tbl_players SET team_id = NULL WHERE id = '$playerId'";
        $db_conn->query($sqlUpd);

        $message = "unasigned {$playerName[0]['first_name']} {$playerName[0]['last_name']}";
        header("Location: ../public/admin_panel.php?message=$message");
    }
    elseif ($formType == 'addToPoule'){
        $pouleName = $_POST['pouleName'];
        $teamId = $_POST['team_id'];

        $sqlSel = "SELECT * FROM tbl_poules WHERE naam = '$pouleName'";
        $pouleId = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);

        $sqlUpd = "UPDATE tbl_teams SET poule_id = '{$pouleId[0]['id']}' WHERE id = '$teamId'";
        $db_conn->query($sqlUpd);

        $message = "Succesfully added";
        header("Location: ../public/admin_panel.php?message=$message");
    }
    elseif ($formType == 'changePoule'){
        $changePoule = $_POST['changePoule'];

        $sqlSel = "SELECT * FROM tbl_teams WHERE id = '$changePoule'";
        $pouleId = $db_conn->query($sqlSel);

        $sqlUpd = "UPDATE tbl_teams SET poule_id = NULL WHERE id = '$changePoule'";
        $db_conn->query($sqlUpd);

        $message = "Succesfully changed";
        header("Location: ../public/admin_panel.php?message=$message");
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