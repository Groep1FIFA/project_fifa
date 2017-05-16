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
//CREATE TEAM
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
//ADD PLAYER TO TEAM
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
//DELETE PLAYER FROM TEAM
    elseif ($formType == 'changeTeam'){
        $playerId = $_POST['player_id'];

        $sqlSel = "SELECT * FROM tbl_players WHERE id = '$playerId'";
        $playerName = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);

        $sqlUpd = "UPDATE tbl_players SET team_id = NULL WHERE id = '$playerId'";
        $db_conn->query($sqlUpd);

        $message = "unasigned {$playerName[0]['first_name']} {$playerName[0]['last_name']}";
        header("Location: ../public/admin_panel.php?message=$message");
    }
//ADD TEAM TO POULE
    elseif ($formType == 'addToPoule'){
        $pouleName = $_POST['pouleName'];
        $teamId = $_POST['team_id'];

        $sqlSel = "SELECT * FROM tbl_poules WHERE name = '$pouleName'";
        $pouleId = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);

        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = {$pouleId[0]['id']}";
        $pouleId = $db_conn->query($sqlSel)->rowCount();

        if ($pouleId < 4) {
            $sqlUpd = "UPDATE tbl_teams SET poule_id = '{$pouleId[0]['id']}' WHERE id = '$teamId'";
            $db_conn->query($sqlUpd);

            $teamNr = $pouleId;

            $sqlUpd = "UPDATE tbl_teams SET team_nr = '$teamNr' WHERE id = '$teamId'";
            $db_conn->query($sqlUpd);

            $message = "Succesfully added";
            header("Location: ../public/admin_panel.php?message=$message");
        }
        else{
            $message = "this poule is full";
            header("Location: ../public/admin_panel.php?message=$message");
        }
    }
//DELETE TEAM FROM POULE
    elseif ($formType == 'changePoule'){
        $changePoule = $_POST['clearPoule'];

        $sqlUpd = "UPDATE tbl_teams SET poule_id = NULL , team_nr = NULL WHERE poule_id = '$changePoule'";
        $db_conn->query($sqlUpd);

        $message = "Succesfully changed";
        header("Location: ../public/admin_panel.php?message=$message");
    }
//TEAM A SCORED
    elseif($formType == 'team-a-scored') {
        $matchId = $_POST['match-id'];
        $playerId = $_POST['player-name'];

        $matchSel = "SELECT * FROM tbl_matches WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_a'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id='$playerId'";
        $player = $db_conn->query($playerSel)->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_matches SET score_team_a = '$matchGoals' WHERE id = '$matchId'";
        $db_conn->query($matchUpdate);
        $playerUpdate = "UPDATE tbl_players SET goals = '$playerGoals' WHERE id = '$playerId'";
        $db_conn->query($playerUpdate);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
//QUATERFINALS TEAM A SCORED
    elseif($formType == 'playoff-a-scored'){
        $matchId = $_POST['match-id'];
        $playerId = $_POST['player-name'];

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_a'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id='$playerId'";
        $player = $db_conn->query($playerSel)->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_playoffs SET score_team_a = '$matchGoals' WHERE id = '$matchId'";
        $db_conn->query($matchUpdate);
        $playerUpdate = "UPDATE tbl_players SET goals = '$playerGoals' WHERE id = '$playerId'";
        $db_conn->query($playerUpdate);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
//TEAM B SCORED
    elseif($formType == 'team-b-scored'){
        $matchId = $_POST['match-id'];
        $playerId = $_POST['player-name'];

        $matchSel = "SELECT * FROM tbl_matches WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_b'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id='$playerId'";
        $player = $db_conn->query($playerSel)->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_matches SET score_team_b = '$matchGoals' WHERE id = '$matchId'";
        $db_conn->query($matchUpdate);
        $playerUpdate = "UPDATE tbl_players SET goals = '$playerGoals' WHERE id = '$playerId'";
        $db_conn->query($playerUpdate);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
// QUATERFINALS TEAM B SCORED
    elseif($formType == 'playoff-b-scored'){
        $matchId = $_POST['match-id'];
        $playerId = $_POST['player-name'];

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_b'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id='$playerId'";
        $player = $db_conn->query($playerSel)->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_playoffs SET score_team_b = '$matchGoals' WHERE id = '$matchId'";
        $db_conn->query($matchUpdate);
        $playerUpdate = "UPDATE tbl_players SET goals = '$playerGoals' WHERE id = '$playerId'";
        $db_conn->query($playerUpdate);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
//MATCH FINISHED
    elseif($formType == 'match-finished'){
        $matchId = $_POST['match-id'];
        $teamIdA = $_POST['team-id-a'];
        $teamIdB = $_POST['team-id-b'];

        $matchSel = "SELECT * FROM tbl_matches WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $selTeamA = "SELECT * FROM tbl_teams WHERE id = '$teamIdA'";
        $teamA = $db_conn->query($selTeamA)->fetchAll(PDO::FETCH_ASSOC);

        $selTeamB = "SELECT * FROM tbl_teams WHERE id = '$teamIdB'";
        $teamB = $db_conn->query($selTeamB)->fetchAll(PDO::FETCH_ASSOC);

        $matchUpdateFinished = "UPDATE tbl_matches SET finished = 1 WHERE id = '$matchId'";
        $db_conn->query($matchUpdateFinished);

        //TEAMS WIN/LOSE/TIE
        $result = $match[0]['score_team_a'] - $match[0]['score_team_b'];
        $resultB = $match[0]['score_team_b'] - $match[0]['score_team_a'];
        if ($result == 0){
            $tiesA = $teamA[0]['tie'] + 1;
            $tiesB = $teamB[0]['tie'] + 1;

            $pointsA = $teamA[0]['points'] + 1;
            $pointsB = $teamB[0]['points'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET tie = '$tiesA', points = '$pointsA' WHERE id = '$teamIdA'";
            $db_conn->query($updateTeamA);
            $updateTeamB = "UPDATE tbl_teams SET tie = '$tiesB', points = '$pointsB' WHERE id = '$teamIdB'";
            $db_conn->query($updateTeamB);
        }
        elseif ($result > 0){
            $winsA = $teamA[0]['win'] + 1;
            $losesB = $teamB[0]['lose'] + 1;

            $pointsA = $teamA[0]['points'] + 3;

            $updateTeamA = "UPDATE tbl_teams SET win = '$winsA', points = '$pointsA' WHERE id = '$teamIdA'";
            $db_conn->query($updateTeamA);
            $updateTeamB = "UPDATE tbl_teams SET lose = '$losesB' WHERE id = '$teamIdB'";
            $db_conn->query($updateTeamB);
        }
        elseif ($result < 0){
            $losesA = $teamA[0]['lose'] + 1;
            $winsB = $teamB[0]['win'] + 1;

            $pointsB = $teamB[0]['points'] + 3;

            $updateTeamA = "UPDATE tbl_teams SET lose = '$losesA' WHERE id = '$teamIdA'";
            $db_conn->query($updateTeamA);
            $updateTeamB = "UPDATE tbl_teams SET win = '$winsB', points = '$pointsB' WHERE id = '$teamIdB'";
            $db_conn->query($updateTeamB);
        }
        else{
            $message = 'Something went wrong';
            header("Location: ../public/admin_panel.php?message=$message");
        }
        $goalBalanceA = $teamA[0]['goal_balance'] + $result;
        $goalBalanceB = $teamB[0]['goal_balance'] + $resultB;

        $updateTeamA = "UPDATE tbl_teams SET goal_balance = '$goalBalanceA' WHERE id = '$teamIdA'";
        $db_conn->query($updateTeamA);
        $updateTeamB = "UPDATE tbl_teams SET goal_balance = '$goalBalanceB' WHERE id = '$teamIdB'";
        $db_conn->query($updateTeamB);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
//QUATERFINAL FINISHED
    elseif($formType == 'playoff-finished'){
        $matchId = $_POST['match-id'];
        $teamIdA = $_POST['team-id-a'];
        $teamIdB = $_POST['team-id-b'];

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = '$matchId'";
        $match = $db_conn->query($matchSel)->fetchAll(PDO::FETCH_ASSOC);

        $selTeamA = "SELECT * FROM tbl_teams WHERE id = '$teamIdA'";
        $teamA = $db_conn->query($selTeamA)->fetchAll(PDO::FETCH_ASSOC);

        $selTeamB = "SELECT * FROM tbl_teams WHERE id = '$teamIdB'";
        $teamB = $db_conn->query($selTeamB)->fetchAll(PDO::FETCH_ASSOC);

        //TEAMS WIN/LOSE/TIE
        $result = $match[0]['score_team_a'] - $match[0]['score_team_b'];
        $resultB = $match[0]['score_team_b'] - $match[0]['score_team_a'];
        if ($result == 0){
            $message = 'There are no ties in the Playoffs';
            header("Location: ../public/admin_panel.php?message=$message");
            die();
        }
        elseif ($result > 0){
            $winsA = $teamA[0]['win'] + 1;
            $losesB = $teamB[0]['lose'] + 1;

            $pointsA = $teamA[0]['points'] + 3;
            $playoffRanking = $teamA[0]['playoff_ranking'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET win = '$winsA', points = '$pointsA', playoff_ranking = '$playoffRanking', playoff_id = '$matchId' WHERE id = '$teamIdA'";
            $db_conn->query($updateTeamA);
            $updateTeamB = "UPDATE tbl_teams SET lose = '$losesB', playoff_ranking = 0, playoff_id = 0 WHERE id = '$teamIdB'";
            $db_conn->query($updateTeamB);

            $matchUpdateFinished = "UPDATE tbl_playoffs SET finished = 1 WHERE id = '$matchId'";
            $db_conn->query($matchUpdateFinished);
        }
        elseif ($result < 0){
            $losesA = $teamA[0]['lose'] + 1;
            $winsB = $teamB[0]['win'] + 1;

            $pointsB = $teamB[0]['points'] + 3;
            $playoffRanking = $teamB[0]['playoff_ranking'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET lose = '$losesA', playoff_ranking = 0, playoff_id = 0 WHERE id = '$teamIdA'";
            $db_conn->query($updateTeamA);
            $updateTeamB = "UPDATE tbl_teams SET win = '$winsB', points = '$pointsB', playoff_ranking = '$playoffRanking', playoff_id = '$matchId' WHERE id = '$teamIdB'";
            $db_conn->query($updateTeamB);

            $matchUpdateFinished = "UPDATE tbl_playoffs SET finished = 1 WHERE id = '$matchId'";
            $db_conn->query($matchUpdateFinished);
        }
        else{
            $message = 'Something went wrong';
            header("Location: ../public/admin_panel.php?message=$message");
        }
        $goalBalanceA = $teamA[0]['goal_balance'] + $result;
        $goalBalanceB = $teamB[0]['goal_balance'] + $resultB;

        $updateTeamA = "UPDATE tbl_teams SET goal_balance = '$goalBalanceA' WHERE id = '$teamIdA'";
        $db_conn->query($updateTeamA);
        $updateTeamB = "UPDATE tbl_teams SET goal_balance = '$goalBalanceB' WHERE id = '$teamIdB'";
        $db_conn->query($updateTeamB);

        $message = 'Succesfully updated';
        header("Location: ../public/admin_panel.php?message=$message");
    }
//START PLAYOFFS
    elseif ($formType == 'start-playoffs'){
        //POULE A
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 1 ORDER BY points DESC";
        $RankingPouleA = $db_conn->query($sqlSel)->fetchAll();

        $RankOnePouleA = $RankingPouleA[0]['id'];
        $RankTwoPouleA = $RankingPouleA[1]['id'];

        $updRankOnePouleA = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = '$RankOnePouleA'";
        $db_conn->query($updRankOnePouleA);
        $updRankTwoPouleA = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = '$RankTwoPouleA'";
        $db_conn->query($updRankTwoPouleA);

        //POULE B
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 2 ORDER BY points DESC";
        $RankingPouleB = $db_conn->query($sqlSel)->fetchAll();

        $RankOnePouleB = $RankingPouleB[0]['id'];
        $RankTwoPouleB = $RankingPouleB[1]['id'];

        $updRankOnePouleB = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = '$RankOnePouleB'";
        $db_conn->query($updRankOnePouleB);
        $updRankTwoPouleB = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = '$RankTwoPouleB'";
        $db_conn->query($updRankTwoPouleB);

        //POULE C
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 3 ORDER BY points DESC";
        $RankingPouleC = $db_conn->query($sqlSel)->fetchAll();

        $RankOnePouleC = $RankingPouleC[0]['id'];
        $RankTwoPouleC = $RankingPouleC[1]['id'];

        $updRankOnePouleC = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = '$RankOnePouleC'";
        $db_conn->query($updRankOnePouleC);
        $updRankTwoPouleC = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = '$RankTwoPouleC'";
        $db_conn->query($updRankTwoPouleC);

        //POULE D
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 4 ORDER BY points DESC";
        $RankingPouleD = $db_conn->query($sqlSel)->fetchAll();

        $RankOnePouleD = $RankingPouleD[0]['id'];
        $RankTwoPouleD = $RankingPouleD[1]['id'];

        $updRankOnePouleD = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = '$RankOnePouleD'";
        $db_conn->query($updRankOnePouleD);
        $updRankTwoPouleD = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = '$RankTwoPouleD'";
        $db_conn->query($updRankTwoPouleD);

        $message = 'The playoffs have been started';
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