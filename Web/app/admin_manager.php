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
            $teamName = trim($_POST['teamName']);

            $sqlSel = "SELECT * FROM tbl_teams WHERE name = :teamName";
            $count = $db_conn->prepare($sqlSel);
            $count->execute(['teamName' => $teamName]);

            if ($count->rowCount() == 0){
                $sqlIns = "INSERT INTO tbl_teams (name) VALUES (:teamName)";
                $sqlPre = $db_conn->prepare($sqlIns);
                $sqlPre->execute(['teamName' => $teamName]);

                $message = 'Team succesfully added';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
            else{
                $message = 'Team already exists';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
        }
        else{
            $message = 'please enter a team name';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//DELETE TEAM
    elseif ($formType == 'delete'){
        if (!empty('tbl-name') && !empty('id')){
            $tblName = trim($_POST['tbl-name']);
            $id = trim($_POST['id']);

            $sqlDel = "DELETE FROM $tblName WHERE id = :id";
            $sqlPre = $db_conn->prepare($sqlDel);
            $sqlPre->execute(['id' => $id]);

            $message = 'Deleted succesfully';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        else{
            $message = 'Failed to delete';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//ADD PLAYER TO TEAM
    elseif ($formType == 'addToTeam'){
        if (!empty($_POST['addToTeam'])){
            $addTeam = trim($_POST['addToTeam']);
            $playerId = trim($_POST['player_id']);

            $sqlSel = "SELECT * FROM tbl_teams WHERE name = :addTeam";
            $sqlCount = $db_conn->prepare($sqlSel);
            $sqlCount->execute(['addTeam' => $addTeam]);

            if ($sqlCount->rowCount() == 1){
                $team_id = "SELECT * FROM tbl_teams WHERE name = :addTeam";
                $sqlPre = $db_conn->prepare($team_id);
                $sqlPre->execute(['addTeam' => $addTeam]);
                $sqlPre = $sqlPre->fetchAll(PDO::FETCH_ASSOC);

                $team_id = $sqlPre[0]['id'];

                $sqlUpd = "UPDATE tbl_players SET team_id = :team_id WHERE id = :playerId";
                $sqlPre = $db_conn->prepare($sqlUpd);
                $sqlPre->execute(['team_id' => $team_id, 'playerId' => $playerId]);

                $message = "Player added to $addTeam";
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
            else{
                $message = 'Team does not exists';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
        }
        else{
            $message = 'Please fill in a team name';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//DELETE PLAYER FROM TEAM
    elseif ($formType == 'changeTeam'){
        $playerId = trim($_POST['player_id']);

        $sqlSel = "SELECT * FROM tbl_players WHERE id = :playerId";
        $playerName = $db_conn->prepare($sqlSel);
        $playerName->execute(['playerId' => $playerId]);
        $playerName = $playerName->fetchAll(PDO::FETCH_ASSOC);

        $sqlUpd = "UPDATE tbl_players SET team_id = NULL WHERE id = :playerId";
        $sqlPre = $db_conn->prepare($sqlUpd);
        $sqlPre->execute(['playerId' => $playerId]);

        $message = "unasigned {$playerName[0]['first_name']} {$playerName[0]['last_name']}";
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//ADD TEAM TO POULE
    elseif ($formType == 'addToPoule'){
        $pouleName = trim($_POST['pouleName']);
        $teamId = trim($_POST['team_id']);

        $sqlSel = "SELECT * FROM tbl_poules WHERE name = :pouleName";
        $pouleId = $db_conn->prepare($sqlSel);
        $pouleId->execute(['pouleName' => $pouleName]);
        $pouleId = $pouleId->fetchAll(PDO::FETCH_ASSOC);

        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :pouleId";
        $pouleCount = $db_conn->prepare($sqlSel);
        $pouleCount->execute(['pouleId' => $pouleId[0]['id']]);

        if ($pouleCount->rowCount() < 4) {
            $sqlUpd = "UPDATE tbl_teams SET poule_id = :pouleId WHERE id = :teamId";
            $sqlPre = $db_conn->prepare($sqlUpd);
            $sqlPre->execute(['pouleId' => $pouleId[0]['id'], 'teamId' => $teamId]);

            $teamNr = $pouleCount->rowCount() + 1;

            $sqlUpd = "UPDATE tbl_teams SET team_nr = :teamNr WHERE id = :teamId";
            $sqlPre = $db_conn->prepare($sqlUpd);
            $sqlPre->execute(['teamNr' => $teamNr, 'teamId' => $teamId]);

            $message = "Succesfully added";
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        else{
            $message = "this poule is full";
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//DELETE TEAM FROM POULE
    elseif ($formType == 'changePoule'){
        $changePoule = trim($_POST['clearPoule']);

        $sqlUpd = "UPDATE tbl_teams SET poule_id = NULL , team_nr = NULL WHERE poule_id = :changePoule";
        $sqlPre = $db_conn->prepare($sqlUpd);
        $sqlPre->execute(['changePoule' => $changePoule]);

        $message = "Succesfully changed";
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//CREATE MATCH
    elseif ($formType == 'create-match'){
        $team_nr1 = trim($_POST['team_nr1']);
        $team_nr2 = trim($_POST['team_nr2']);
        $dateTime = trim($_POST['date-time']);
        $poule_id = trim($_POST['poule_id']);

        if($team_nr1 != $team_nr2) {
            $sqlSel = "SELECT * FROM tbl_matches WHERE team_id_a = :team_nr1 AND team_id_b = :team_nr2 AND poule_id = :poule_id";
            $sqlPre = $db_conn->prepare($sqlSel);
            $sqlPre->execute(['team_nr1' => $team_nr1, 'team_nr2' => $team_nr2, 'poule_id' => $poule_id]);
            $matchesCount1 = $sqlPre->rowCount();

            $sqlSel = "SELECT * FROM tbl_matches WHERE team_id_a = :team_nr2 AND team_id_b = :team_nr1 AND poule_id = :poule_id";
            $sqlPre = $db_conn->prepare($sqlSel);
            $sqlPre->execute(['team_nr1' => $team_nr1, 'team_nr2' => $team_nr2, 'poule_id' => $poule_id]);
            $matchesCount2 = $sqlPre->rowCount();

            if ($matchesCount1 == 0 && $matchesCount2 == 0) {
                $sqlIns = "INSERT INTO tbl_matches (team_id_a, team_id_b, poule_id, start_time) VALUES (:team_nr1, :team_nr2, :poule_id, :start_time)";
                $sqlPre = $db_conn->prepare($sqlIns);
                $sqlPre->execute(['team_nr1' => $team_nr1, 'team_nr2' => $team_nr2, 'poule_id' => $poule_id, 'start_time' => $dateTime]);

                $message = 'Match has been created';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
            else {
                $message = 'This match exists already';
                header("Location: ../public/admin/admin_panel.php?message=$message");
            }
        }
        else{
            $message = 'A team can not play against him selve';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//TEAM A SCORED
    elseif($formType == 'team-a-scored') {
        if (!isset($_POST['player-name'])){
            $message = 'There are no players in that team';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        else {
            $matchId = trim($_POST['match-id']);
            $playerId = trim($_POST['player-name']);

            $matchSel = "SELECT * FROM tbl_matches WHERE id = :matchId";
            $match = $db_conn->prepare($matchSel);
            $match->execute(['matchId' => $matchId]);
            $match = $match->fetchAll(PDO::FETCH_ASSOC);

            $matchGoals = $match[0]['score_team_a'] + 1;

            $playerSel = "SELECT * FROM tbl_players WHERE id = :playerId";
            $player = $db_conn->prepare($playerSel);
            $player->execute(['playerId' => $playerId]);
            $player = $player->fetchAll(PDO::FETCH_ASSOC);

            $playerGoals = $player[0]['goals'] + 1;

            $matchUpdate = "UPDATE tbl_matches SET score_team_a = :matchGoals WHERE id = :matchId";
            $sqlPre = $db_conn->prepare($matchUpdate);
            $sqlPre->execute(['matchGoals' => $matchGoals, 'matchId' => $matchId]);

            $playerUpdate = "UPDATE tbl_players SET goals = :playerGoals WHERE id = :playerId";
            $sqlPre = $db_conn->prepare($playerUpdate);
            $sqlPre->execute(['playerGoals' => $playerGoals, 'playerId' => $playerId]);

            $message = 'Succesfully updated';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
//QUATERFINALS TEAM A SCORED
    elseif($formType == 'playoff-a-scored'){
        $matchId = trim($_POST['match-id']);
        $playerId = trim($_POST['player-name']);

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = :matchId";
        $match = $db_conn->prepare($matchSel);
        $match->execute(['matchId' => $matchId]);
        $match->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_a'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id = :playerId";
        $player = $db_conn->prepare($playerSel);
        $player->execute(['playerId' => $playerId]);
        $player->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_playoffs SET score_team_a = :matchGoals WHERE id = :matchId";
        $sqlPre = $db_conn->prepare($matchUpdate);
        $sqlPre->execute(['matchGoals' => $matchGoals, 'matchId' => $matchId]);

        $playerUpdate = "UPDATE tbl_players SET goals = :playerGoals WHERE id = :playerId";
        $sqlPre = $db_conn->prepare($playerUpdate);
        $sqlPre->execute(['playerGoals' => $playerGoals, 'playerId' => $playerId]);

        $message = 'Succesfully updated';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//TEAM B SCORED
    elseif($formType == 'team-b-scored'){
        if (!isset($_POST['player-name'])){
            $message = 'There are no players in that team';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        else {
            $matchId = trim($_POST['match-id']);
            $playerId = trim($_POST['player-name']);

            $matchSel = "SELECT * FROM tbl_matches WHERE id = :matchId";
            $match = $db_conn->prepare($matchSel);
            $match->execute(['matchId' => $matchId]);
            $match = $match->fetchAll(PDO::FETCH_ASSOC);

            $matchGoals = $match[0]['score_team_b'] + 1;

            $playerSel = "SELECT * FROM tbl_players WHERE id = :playerId";
            $player = $db_conn->prepare($playerSel);
            $player->execute(['playerId' => $playerId]);
            $player = $player->fetchAll(PDO::FETCH_ASSOC);

            $playerGoals = $player[0]['goals'] + 1;

            $matchUpdate = "UPDATE tbl_matches SET score_team_b = :matchGoals WHERE id = :matchId";
            $sqlPre = $db_conn->prepare($matchUpdate);
            $sqlPre->execute(['matchGoals' => $matchGoals, 'matchId' => $matchId]);

            $playerUpdate = "UPDATE tbl_players SET goals = :playerGoals WHERE id = :playerId";
            $sqlPre = $db_conn->prepare($playerUpdate);
            $sqlPre->execute(['playerGoals' => $playerGoals, 'playerId' => $playerId]);

            $message = 'Succesfully updated';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
    }
// QUATERFINALS TEAM B SCORED
    elseif($formType == 'playoff-b-scored'){
        $matchId = trim($_POST['match-id']);
        $playerId = trim($_POST['player-name']);

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = :matchId";
        $match = $db_conn->prepare($matchSel);
        $match->execute(['matchId' => $matchId]);
        $match = $match->fetchAll(PDO::FETCH_ASSOC);

        $matchGoals = $match[0]['score_team_b'] + 1;

        $playerSel = "SELECT * FROM tbl_players WHERE id=:playerId";
        $player = $db_conn->prepare($playerSel);
        $player->execute(['playerId' => $playerId]);
        $player = $player->fetchAll(PDO::FETCH_ASSOC);

        $playerGoals = $player[0]['goals'] + 1;

        $matchUpdate = "UPDATE tbl_playoffs SET score_team_b = :matchGoals WHERE id = :matchId";
        $sqlPre = $db_conn->prepare($matchUpdate);
        $sqlPre->execute(['matchGoals' => $matchGoals, 'matchId' => $matchId]);

        $playerUpdate = "UPDATE tbl_players SET goals = :playerGoals WHERE id = :playerId";
        $sqlPre = $db_conn->prepare($playerUpdate);
        $sqlPre->execute(['playerGoals' => $playerGoals, 'playerId' => $playerId]);

        $message = 'Succesfully updated';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//MATCH STARTED
    elseif ($formType == 'start-match'){
        $match_id = trim($_POST['match-id']);
        $match_type = trim($_POST['match-type']);

        if ($match_type == 'poule') {
            $sqlUpd = "UPDATE tbl_matches SET score_team_a = 0 , score_team_b = 0, started = 1 WHERE id = :match_id";
            $sqlPre = $db_conn->prepare($sqlUpd);
            $sqlPre->execute(['match_id' => $match_id]);
        }
        elseif ($match_type == 'playoff'){
            $sqlUpd = "UPDATE tbl_playoffs SET score_team_a = 0 , score_team_b = 0, started = 1 WHERE id = :match_id";
            $sqlPre = $db_conn->prepare($sqlUpd);
            $sqlPre->execute(['match_id' => $match_id]);
        }
        $message = 'This match has been started';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//MATCH FINISHED
    elseif($formType == 'match-finished'){
        $matchId = trim($_POST['match-id']);
        $teamIdA = trim($_POST['team-id-a']);
        $teamIdB = trim($_POST['team-id-b']);

        $matchSel = "SELECT * FROM tbl_matches WHERE id = :matchId";
        $match = $db_conn->prepare($matchSel);
        $match->execute(['matchId' => $matchId]);
        $match = $match->fetchAll(PDO::FETCH_ASSOC);

        $selTeamA = "SELECT * FROM tbl_teams WHERE id = :teamIdA";
        $teamA = $db_conn->prepare($selTeamA);
        $teamA->execute(['teamIdA' => $teamIdA]);
        $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);

        $selTeamB = "SELECT * FROM tbl_teams WHERE id = :teamIdB";
        $teamB = $db_conn->prepare($selTeamB);
        $teamB->execute(['teamIdB' => $teamIdB]);
        $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);

        $matchUpdateFinished = "UPDATE tbl_matches SET finished = 1 WHERE id = :matchId";
        $sqlPre = $db_conn->prepare($matchUpdateFinished);
        $sqlPre->execute(['matchId' => $matchId]);

        //TEAMS WIN/LOSE/TIE
        $result = $match[0]['score_team_a'] - $match[0]['score_team_b'];
        $resultB = $match[0]['score_team_b'] - $match[0]['score_team_a'];
        if ($result == 0){
            $tiesA = $teamA[0]['tie'] + 1;
            $tiesB = $teamB[0]['tie'] + 1;

            $pointsA = $teamA[0]['points'] + 1;
            $pointsB = $teamB[0]['points'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET tie = :tiesA, points = :pointsA WHERE id = :teamIdA";
            $sqlPre = $db_conn->prepare($updateTeamA);
            $sqlPre->execute(['tiesA' => $tiesA, 'pointsA' => $pointsA, 'teamIdA' => $teamIdA]);

            $updateTeamB = "UPDATE tbl_teams SET tie = :tiesB, points = :pointsB WHERE id = :teamIdB";
            $sqlPre = $db_conn->prepare($updateTeamB);
            $sqlPre->execute(['tiesB' => $tiesB, 'pointsB' => $pointsB, 'teamIdB' => $teamIdB]);

        }
        elseif ($result > 0){
            $winsA = $teamA[0]['win'] + 1;
            $losesB = $teamB[0]['lose'] + 1;

            $pointsA = $teamA[0]['points'] + 3;

            $updateTeamA = "UPDATE tbl_teams SET win = :winsA, points = :pointsA WHERE id = :teamIdA";
            $sqlPre = $db_conn->prepare($updateTeamA);
            $sqlPre->execute(['winsA' => $winsA, 'pointsA' => $pointsA, 'teamIdA' => $teamIdA]);

            $updateTeamB = "UPDATE tbl_teams SET lose = :losesB WHERE id = :teamIdB";
            $sqlPre = $db_conn->prepare($updateTeamB);
            $sqlPre->execute(['losesB' => $losesB, 'teamIdB' => $teamIdB]);
        }
        elseif ($result < 0){
            $losesA = $teamA[0]['lose'] + 1;
            $winsB = $teamB[0]['win'] + 1;

            $pointsB = $teamB[0]['points'] + 3;

            $updateTeamA = "UPDATE tbl_teams SET lose = :losesA WHERE id = :teamIdA";
            $sqlPre = $db_conn->prepare($updateTeamA);
            $sqlPre->execute(['losesA' => $losesA, 'teamIdA' => $teamIdA]);

            $updateTeamB = "UPDATE tbl_teams SET win = :winsB, points = :pointsB WHERE id = :teamIdB";
            $sqlPre = $db_conn->prepare($updateTeamB);
            $sqlPre->execute(['winsB' => $winsB, 'pointsB' => $pointsB, 'teamIdB' => $teamIdB]);
        }
        else{
            $message = 'Something went wrong';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        $goalBalanceA = $teamA[0]['goal_balance'] + $result;
        $goalBalanceB = $teamB[0]['goal_balance'] + $resultB;

        $updateTeamA = "UPDATE tbl_teams SET goal_balance = :goalBalanceA WHERE id = :teamIdA";
        $sqlPre = $db_conn->prepare($updateTeamA);
        $sqlPre->execute(['goalBalanceA' => $goalBalanceA, 'teamIdA' => $teamIdA]);

        $updateTeamB = "UPDATE tbl_teams SET goal_balance = :goalBalanceB WHERE id = :teamIdB";
        $sqlPre = $db_conn->prepare($updateTeamB);
        $sqlPre->execute(['goalBalanceB' => $goalBalanceB, 'teamIdB' => $teamIdB]);

        $message = 'Succesfully updated';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//QUATERFINAL FINISHED
    elseif($formType == 'playoff-finished'){
        $matchId = trim($_POST['match-id']);
        $teamIdA = trim($_POST['team-id-a']);
        $teamIdB = trim($_POST['team-id-b']);

        $matchSel = "SELECT * FROM tbl_playoffs WHERE id = :matchId";
        $match = $db_conn->prepare($matchSel);
        $match->execute(['matchId' => $matchId]);
        $match = $match->fetchAll(PDO::FETCH_ASSOC);

        $selTeamA = "SELECT * FROM tbl_teams WHERE id = :teamIdA";
        $teamA = $db_conn->prepare($selTeamA);
        $teamA->execute(['teamIdA' => $teamIdA]);
        $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);

        $selTeamB = "SELECT * FROM tbl_teams WHERE id = :teamIdB";
        $teamB = $db_conn->prepare($selTeamB);
        $teamB->execute(['teamIdB' => $teamIdB]);
        $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);

        //TEAMS WIN/LOSE/TIE
        $result = $match[0]['score_team_a'] - $match[0]['score_team_b'];
        $resultB = $match[0]['score_team_b'] - $match[0]['score_team_a'];
        if ($result == 0){
            $message = 'There are no ties in the Playoffs';
            header("Location: ../public/admin/admin_panel.php?message=$message");
            die();
        }
        elseif ($result > 0){
            $winsA = $teamA[0]['win'] + 1;
            $losesB = $teamB[0]['lose'] + 1;

            $pointsA = $teamA[0]['points'] + 3;
            $playoffRanking = $teamA[0]['playoff_ranking'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET win = :winsA, points = :pointsA, playoff_ranking = :playoffRanking, playoff_id = :matchId WHERE id = :teamIdA";
            $sqlPre = $db_conn->prepare($updateTeamA);
            $sqlPre->execute(['winsA' => $winsA, 'pointsA' => $pointsA, 'playoffRanking' => $playoffRanking, 'matchId' => $matchId, 'teamIdA' => $teamIdA]);

            $updateTeamB = "UPDATE tbl_teams SET lose = :losesB, playoff_ranking = 0, playoff_id = 0 WHERE id = :teamIdB";
            $sqlPre = $db_conn->prepare($updateTeamB);
            $sqlPre->execute(['losesB' => $losesB, 'teamIdB' => $teamIdB]);

            $matchUpdateFinished = "UPDATE tbl_playoffs SET finished = 1 WHERE id = :matchId";
            $sqlPre = $db_conn->prepare($matchUpdateFinished);
            $sqlPre->execute(['matchId' => $matchId]);
        }
        elseif ($result < 0){
            $losesA = $teamA[0]['lose'] + 1;
            $winsB = $teamB[0]['win'] + 1;

            $pointsB = $teamB[0]['points'] + 3;
            $playoffRanking = $teamB[0]['playoff_ranking'] + 1;

            $updateTeamA = "UPDATE tbl_teams SET lose = :losesA, playoff_ranking = 0, playoff_id = 0 WHERE id = :teamIdA";
            $sqlPre = $db_conn->prepare($updateTeamA);
            $sqlPre->execute(['losesA' => $losesA, 'teamIdA' => $teamIdA]);

            $updateTeamB = "UPDATE tbl_teams SET win = :winsB, points = :pointsB, playoff_ranking = :playoffRanking, playoff_id = :matchId WHERE id = :teamIdB";
            $sqlPre = $db_conn->prepare($updateTeamB);
            $sqlPre->execute(['winsB' => $winsB, 'pointsB' => $pointsB, 'playoffRanking' => $playoffRanking, 'matchId' => $matchId, 'teamIdB' => $teamIdB]);

            $matchUpdateFinished = "UPDATE tbl_playoffs SET finished = 1 WHERE id = :matchId";
            $sqlPre = $db_conn->prepare($matchUpdateFinished);
            $sqlPre->execute(['matchId' => $matchId]);
        }
        else{
            $message = 'Something went wrong';
            header("Location: ../public/admin/admin_panel.php?message=$message");
        }
        $goalBalanceA = $teamA[0]['goal_balance'] + $result;
        $goalBalanceB = $teamB[0]['goal_balance'] + $resultB;

        $updateTeamA = "UPDATE tbl_teams SET goal_balance = :goalBalanceA WHERE id = :teamIdA";
        $sqlPre = $db_conn->prepare($updateTeamA);
        $sqlPre->execute(['goalBalanceA' => $goalBalanceA, 'teamIdA' => $teamIdA]);

        $updateTeamB = "UPDATE tbl_teams SET goal_balance = :goalBalanceB WHERE id = :teamIdB";
        $sqlPre = $db_conn->prepare($updateTeamB);
        $sqlPre->execute(['goalBalanceB' => $goalBalanceB, 'teamIdB' => $teamIdB]);

        $message = 'Succesfully updated';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
//START PLAYOFFS
    elseif ($formType == 'start-playoffs'){
        //POULE A
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 1 ORDER BY points DESC";
        $RankingPouleA = $db_conn->prepare($sqlSel);
        $RankingPouleA->execute();
        $RankingPouleA = $RankingPouleA->fetchAll(PDO::FETCH_ASSOC);

        $RankOnePouleA = $RankingPouleA[0]['id'];
        $RankTwoPouleA = $RankingPouleA[1]['id'];

        $updRankOnePouleA = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = :RankOnePouleA";
        $sqlPre = $db_conn->prepare($updRankOnePouleA);
        $sqlPre->execute(['RankOnePouleA' => $RankOnePouleA]);

        $updRankTwoPouleA = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = :RankTwoPouleA";
        $sqlPre = $db_conn->prepare($updRankTwoPouleA);
        $sqlPre->execute(['RankTwoPouleA' => $RankTwoPouleA]);

        //POULE B
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 2 ORDER BY points DESC";
        $RankingPouleB = $db_conn->prepare($sqlSel);
        $RankingPouleB->execute();
        $RankingPouleB = $RankingPouleB->fetchAll(PDO::FETCH_ASSOC);

        $RankOnePouleB = $RankingPouleB[0]['id'];
        $RankTwoPouleB = $RankingPouleB[1]['id'];

        $updRankOnePouleB = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = :RankOnePouleB";
        $sqlPre = $db_conn->prepare($updRankOnePouleB);
        $sqlPre->execute(['RankOnePouleB' => $RankOnePouleB]);

        $updRankTwoPouleB = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = :RankTwoPouleB";
        $sqlPre = $db_conn->prepare($updRankTwoPouleB);
        $sqlPre->execute(['RankTwoPouleB' => $RankTwoPouleB]);

        //POULE C
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 3 ORDER BY points DESC";
        $RankingPouleC = $db_conn->prepare($sqlSel);
        $RankingPouleC->execute();
        $RankingPouleC = $RankingPouleC->fetchAll(PDO::FETCH_ASSOC);

        $RankOnePouleC = $RankingPouleC[0]['id'];
        $RankTwoPouleC = $RankingPouleC[1]['id'];

        $updRankOnePouleC = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = :RankOnePouleC";
        $sqlPre = $db_conn->prepare($updRankOnePouleC);
        $sqlPre->execute(['RankOnePouleC' => $RankOnePouleC]);

        $updRankTwoPouleC = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = :RankTwoPouleC";
        $sqlPre = $db_conn->prepare($updRankTwoPouleC);
        $sqlPre->execute(['RankTwoPouleC' => $RankTwoPouleC]);

        //POULE D
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = 4 ORDER BY points DESC";
        $RankingPouleD = $db_conn->prepare($sqlSel);
        $RankingPouleD->execute();
        $RankingPouleD = $RankingPouleD->fetchAll(PDO::FETCH_ASSOC);

        $RankOnePouleD = $RankingPouleD[0]['id'];
        $RankTwoPouleD = $RankingPouleD[1]['id'];

        $updRankOnePouleD = "UPDATE tbl_teams SET poule_ranking = 1 WHERE id = :RankOnePouleD";
        $sqlPre = $db_conn->prepare($updRankOnePouleD);
        $sqlPre->execute(['RankOnePouleD' => $RankOnePouleD]);

        $updRankTwoPouleD = "UPDATE tbl_teams SET poule_ranking = 2 WHERE id = :RankTwoPouleD";
        $sqlPre = $db_conn->prepare($updRankTwoPouleD);
        $sqlPre->execute(['RankTwoPouleD' => $RankTwoPouleD]);

        $message = 'The playoffs have been started';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
    else{
        $message = 'Failed';
        header("Location: ../public/admin/admin_panel.php?message=$message");
    }
}
else{
    $message = 'Failed';
    header("Location: ../public/admin/admin_panel.php?message=$message");
}