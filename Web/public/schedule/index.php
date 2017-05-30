<?php require(realpath(__DIR__) . '/../templates/header.php');
$currentPage = "index.php";?>

<section>
    <div class="poule-phase">
        <table>
            <?php
            $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 1";
            $finishedMatches = $db_conn->prepare($sqlSel);
            $finishedMatches->execute();
            
            foreach ($finishedMatches as $finishedMatch){
                $sqlPoules = "SELECT * FROM tbl_poules WHERE id = :poule_id";
                $sqlPoules = $db_conn->prepare($sqlPoules);
                $sqlPoules->execute(['poule_id' => $finishedMatch['poule_id']]);
                $sqlPoules = $sqlPoules->fetchAll(PDO::FETCH_ASSOC);
                
                $sqlTeamsA = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id AND team_nr = :team_id_a";
                $sqlTeamsA = $db_conn->prepare($sqlTeamsA);
                $sqlTeamsA->execute(['poule_id' => $finishedMatch['poule_id'], 'team_id_a' => $finishedMatch['team_id_a']]);
                $sqlTeamsA = $sqlTeamsA->fetchAll(PDO::FETCH_ASSOC);
                
                $sqlTeamsB = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id AND team_nr = :team_id_b";
                $sqlTeamsB = $db_conn->prepare($sqlTeamsB);
                $sqlTeamsB->execute(['poule_id' => $finishedMatch['poule_id'], 'team_id_b' => $finishedMatch['team_id_b']]);
                $sqlTeamsB = $sqlTeamsB->fetchAll(PDO::FETCH_ASSOC);
                
                if (!isset($sqlTeamsA[0]['name']) && !isset($sqlTeamsB[0]['name'])){
                    echo 'Vul de poulen tot maximaal 4 teams.';
                    break;
                }
                else {
                    echo "<tr class=\"match-data finished-match align-center\">
                        <td>{$finishedMatch['start_time']}</td>
                        <td>{$sqlPoules[0]['name']}</td>
                        <td>{$sqlTeamsA[0]['name']}</td>
                        <td>{$finishedMatch['score_team_a']} - {$finishedMatch['score_team_b']}</td>
                        <td class=\"last-match-data\">{$sqlTeamsB[0]['name']}</td>
                        </tr>";

                }
            }
            foreach ($matches as $match) {
                $sqlPoules = "SELECT * FROM tbl_poules WHERE id = :poule_id";
                $sqlPoules = $db_conn->prepare($sqlPoules);
                $sqlPoules->execute(['poule_id' => $match['poule_id']]);
                $sqlPoules = $sqlPoules->fetchAll(PDO::FETCH_ASSOC);
                
                $sqlTeamsA = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id AND team_nr = :team_id_a";
                $sqlTeamsA = $db_conn->prepare($sqlTeamsA);
                $sqlTeamsA->execute(['poule_id' => $match['poule_id'], 'team_id_a' => $match['team_id_a']]);
                $sqlTeamsA = $sqlTeamsA->fetchAll(PDO::FETCH_ASSOC);
                
                $sqlTeamsB = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id AND team_nr = :team_id_b";
                $sqlTeamsB = $db_conn->prepare($sqlTeamsB);
                $sqlTeamsB->execute(['poule_id' => $match['poule_id'], 'team_id_b' => $match['team_id_b']]);
                $sqlTeamsB = $sqlTeamsB->fetchAll(PDO::FETCH_ASSOC);
                
                if (!isset($sqlTeamsA[0]['name']) && !isset($sqlTeamsB[0]['name'])){
                    echo 'Vul de poulen tot maximaal 4 teams.';
                    break;
                }
                else {
                    echo "<tr class=\"match-data align-center\">
                        <td>{$match['start_time']}</td>
                        <td>{$sqlPoules[0]['name']}</td>
                        <td>{$sqlTeamsA[0]['name']}</td>
                        <td>{$match['score_team_a']} - {$match['score_team_b']}</td>
                        <td class=\"last-match-data\">{$sqlTeamsB[0]['name']}</td>
                        </tr>";
                }
            }
            $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 0";
            $sqlCount = $db_conn->prepare($sqlSel);
            $sqlCount->execute();
            $sqlCount = $sqlCount->rowCount();
            
            if ($sqlCount >= 1) {
                $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 0 AND playoff_ranking_b = 0 AND finished = 0";
                $quaterFinals = $db_conn->prepare($sqlSel);
                $quaterFinals->execute();
                $quaterCount = $quaterFinals->rowCount();
                
                $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 1 AND playoff_ranking_b = 1 AND finished = 0";
                $semiFinals = $db_conn->prepare($sqlSel);
                $semiFinals->execute();
                $semiCount = $semiFinals->rowCount();

                $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 2 AND playoff_ranking_b = 2 AND finished = 0";
                $finals = $db_conn->prepare($sqlSel);
                $finals->execute();
                
                foreach ($quaterFinals as $quaterFinal){
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = :poule_ranking_a";
                    $sqlPre = $db_conn->prepare($sqlSel);
                    $sqlPre->execute(['poule_ranking_a' => $quaterFinal['poule_ranking_a']]);
                    $countA = $sqlPre->rowCount();
                    
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = :poule_ranking_b";
                    $sqlPre = $db_conn->prepare($sqlSel);
                    $sqlPre->execute(['poule_ranking_b' => $quaterFinal['poule_ranking_b']]);
                    $countB = $sqlPre->rowCount();
                    
                    if ($countA > 0 && $countB > 0) {
                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id_a AND poule_ranking = :poule_ranking_a";
                        $teamA = $db_conn->prepare($sqlSel);
                        $teamA->execute(['poule_id_a' => $quaterFinal['poule_id_a'], 'poule_ranking_a' => $quaterFinal['poule_ranking_a']]);
                        $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);
                        
                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id_b AND poule_ranking = :poule_ranking_b";
                        $teamB = $db_conn->prepare($sqlSel);
                        $teamB->execute(['poule_id_b' => $quaterFinal['poule_id_b'], 'poule_ranking_b' => $quaterFinal['poule_ranking_b']]);
                        $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);
                        
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $aTeamPlayers = $db_conn->prepare($sqlSel);
                        $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
                        
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $bTeamPlayers = $db_conn->prepare($sqlSel);
                        $bTeamPlayers->execute(['id' => $teamB[0]['id']]);

                        echo "<tr class=\"match-data align-center quaters\">
                            <td>{$quaterFinal['start_time']}</td>
                            <td>Quater-Final</td>
                            <td>{$teamA[0]['name']}</td>
                            <td>{$quaterFinal['score_team_a']} - {$quaterFinal['score_team_b']}</td>
                            <td>{$teamB[0]['name']}</td>
                            </tr>";
                    }
                    else{
                        echo "<tr class=\"match-data align-center quaters\">
                            <td>{$quaterFinal['start_time']}</td>
                            <td>Quater-Final</td>
                            <td>T.B.D</td>
                            <td>{$quaterFinal['score_team_a']} - {$quaterFinal['score_team_b']}</td>
                            <td>T.B.D</td>
                            </tr>";
                    }
                }
                foreach ($semiFinals as $semiFinal) {
                    if ($quaterCount == 0) {
                        $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_a AND playoff_ranking = :playoff_ranking_a";
                        $teamA = $db_conn->prepare($sqlSel);
                        $teamA->execute(['playoff_id_a' => $semiFinal['playoff_id_a'], 'playoff_ranking_a' => $semiFinal['playoff_ranking_a']]);
                        $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);
    
                        $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_b AND playoff_ranking = :playoff_ranking_b";
                        $teamB = $db_conn->prepare($sqlSel);
                        $teamB->execute(['playoff_id_b' => $semiFinal['playoff_id_b'], 'playoff_ranking_b' => $semiFinal['playoff_ranking_b']]);
                        $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);
    
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $aTeamPlayers = $db_conn->prepare($sqlSel);
                        $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
                        
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $bTeamPlayers = $db_conn->prepare($sqlSel);
                        $bTeamPlayers->execute(['id' => $teamB[0]['id']]);
                        
                        echo "<tr class=\"match-data align-center semis\">
                            <td>{$semiFinal['start_time']}</td>
                            <td>Semi-Final</td>
                            <td>{$teamA[0]['name']}</td>
                            <td>{$semiFinal['score_team_a']} - {$semiFinal['score_team_b']}</td>
                            <td>{$teamB[0]['name']}</td>
                            </tr>";
                    }
                    else {
                        echo "<tr class=\"match-data align-center semis\">
                            <td>{$semiFinal['start_time']}</td>
                            <td>Semi-Final</td>
                            <td>T.B.D</td>
                            <td>{$semiFinal['score_team_a']} - {$semiFinal['score_team_b']}</td>
                            <td>T.B.D</td>
                            </tr>";
                    }
                }
                foreach ($finals as $final) {
                    if ($semiCount == 0) {
                        $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_a AND playoff_ranking = :playoff_ranking_a";
                        $teamA = $db_conn->prepare($sqlSel);
                        $teamA->execute(['playoff_id_a' => $semiFinal['playoff_id_a'], 'playoff_ranking_a' => $semiFinal['playoff_ranking_a']]);
                        $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);
    
                        $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_b AND playoff_ranking = :playoff_ranking_b";
                        $teamB = $db_conn->prepare($sqlSel);
                        $teamB->execute(['playoff_id_b' => $semiFinal['playoff_id_b'], 'playoff_ranking_b' => $semiFinal['playoff_ranking_b']]);
                        $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);
    
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $aTeamPlayers = $db_conn->prepare($sqlSel);
                        $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
    
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $bTeamPlayers = $db_conn->prepare($sqlSel);
                        $bTeamPlayers->execute(['id' => $teamB[0]['id']]);

                        echo "<tr class=\"match-data align-center finals\">
                            <td>{$final['start_time']}</td>
                            <td>Final</td>
                            <td>{$teamA[0]['name']}</td>
                            <td>{$final['score_team_a']} - {$final['score_team_b']}</td>
                            <td>{$teamB[0]['name']}</td>
                            </tr>";
                    }
                    else{
                        echo "<tr class=\"match-data align-center finals\">
                            <td>{$final['start_time']}</td>
                            <td>Final</td>
                            <td>T.B.D</td> 
                            <td>{$final['score_team_a']} - {$final['score_team_b']}</td>
                            <td>T.B.D</td>
                            </tr>";
                    }
                }
            }
            else{

            }
            ?>
        </table>
    </div>
</section>

<div class="play-offs">
    <div class="playoffs-title">
        <h2>Playoffs</h2>
    </div>
    <ul class="playoffs-schedule flex-between align-center">
        <?php
        $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 0";
        $sqlPre = $db_conn->prepare($sqlSel);
        $sqlPre->execute();
        $sqlCount = $sqlPre->rowCount();

        if ($sqlCount >= 1) {
            $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 0 AND playoff_ranking_b = 0 AND finished = 0";
            $quaterFinals = $db_conn->prepare($sqlSel);
            $quaterFinals->execute();
            $quaterCount = $quaterFinals->rowCount();


            $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 1 AND playoff_ranking_b = 1 AND finished = 0";
            $semiFinals = $db_conn->prepare($sqlSel);
            $semiFinals->execute();
            $semiCount = $semiFinals->rowCount();

            $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 2 AND playoff_ranking_b = 2 AND finished = 0";
            $finals = $db_conn->prepare($sqlSel);
            $finals->execute();

            echo "<ul class=\"quaters flex-column flex-between\">";
            foreach ($quaterFinals as $quaterFinal){
                $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = :poule_ranking_a";
                $teamA = $db_conn->prepare($sqlSel);
                $teamA->execute(['poule_ranking_a' => $quaterFinal['poule_ranking_b']]);
                $countA = $teamA->rowCount();
                
                $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = :poule_ranking_b";
                $teamB = $db_conn->prepare($sqlSel);
                $teamB->execute(['poule_ranking_b' => $quaterFinal['poule_ranking_b']]);
                $countB = $teamB->rowCount();
                
                if ($countA > 0 && $countB > 0) {
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id_a AND poule_ranking = :poule_ranking_a";
                    $teamA = $db_conn->prepare($sqlSel);
                    $teamA->execute(['poule_id_a' => $quaterFinal['poule_id_a'], 'poule_ranking_a' => $quaterFinal['poule_ranking_a']]);
                    $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);
                   
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :poule_id_b AND poule_ranking = :poule_ranking_b";
                    $teamB = $db_conn->prepare($sqlSel);
                    $teamB->execute(['poule_id_b' => $quaterFinal['poule_id_b'], 'poule_ranking_b' => $quaterFinal['poule_ranking_b']]);
                    $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);
                    
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                    $aTeamPlayers = $db_conn->prepare($sqlSel);
                    $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
                    
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                    $bTeamPlayers = $db_conn->prepare($sqlSel);
                    $bTeamPlayers->execute(['id' => $teamB[0]['id']]);
                    
                    echo "<li class=\"quater\">{$teamA[0]['name']} VS {$teamB[0]['name']}</li>";
                }
                else{
                    echo "<li class=\"quater\">T.B.D VS T.B.D</li>";
                }
            }
            echo "</ul>
                  <ul class=\"semis flex-column flex-between\">";
            foreach ($semiFinals as $semiFinal) {
                if ($quaterCount == 0) {
                    $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_a AND playoff_ranking = :playoff_ranking_a";
                    $teamA = $db_conn->prepare($sqlSel);
                    $teamA->execute(['playoff_id_a' => $semiFinal['playoff_id_a'], 'playoff_ranking_a' => $semiFinal['playoff_ranking_a']]);
                    $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);

                    $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = :playoff_id_b AND playoff_ranking = :playoff_ranking_b";
                    $teamB = $db_conn->prepare($sqlSel);
                    $teamB->execute(['playoff_id_b' => $semiFinal['playoff_id_b'], 'playoff_ranking_b' => $semiFinal['playoff_ranking_b']]);
                    $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);
                    
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                    $aTeamPlayers = $db_conn->prepare($sqlSel);
                    $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
                    
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = :id";
                    $bTeamPlayers = $db_conn->prepare($sqlSel);
                    $bTeamPlayers->execute(['id' => $teamB[0]['id']]);

                    echo "<li class=\"semi\">{$teamA[0]['name']} VS {$teamB[0]['name']}</li>";
                }
                else {
                    echo "<li class=\"semi\">T.B.D VS T.B.D</li>";
                }
            }
            echo "</ul>
                  <ul class=\"finals flex-column flex-between\">";
            foreach ($finals as $final) {
                if ($semiCount == 0) {
                    $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$final['playoff_id_a']}' AND playoff_ranking = '{$final['playoff_ranking_a']}'";
                    $teamA = $db_conn->prepare($sqlSel);
                    $teamA->execute(['playoff_id_a' => $final['playoff_id_a'], 'playoff_ranking_a' => $final['playoff_ranking_a']]);
                    $teamA = $teamA->fetchAll(PDO::FETCH_ASSOC);
                    
                    $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$final['playoff_id_b']}' AND playoff_ranking = '{$final['playoff_ranking_b']}'";
                    $teamB = $db_conn->prepare($sqlSel);
                    $teamB->execute(['playoff_id_b' => $final['playoff_id_b'], 'playoff_ranking_b' => $final['playoff_ranking_b']]);
                    $teamB = $teamB->fetchAll(PDO::FETCH_ASSOC);

                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamA[0]['id']}'";
                    $aTeamPlayers = $db_conn->prepare($sqlSel);
                    $aTeamPlayers->execute(['id' => $teamA[0]['id']]);
                    
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamB[0]['id']}'";
                    $bTeamPlayers = $db_conn->prepare($sqlSel);
                    $bTeamPlayers->execute(['id' => $teamB[0]['id']]);
                    
                    echo "<li class=\"final\">{$teamA[0]['name']} VS {$teamB[0]['name']}</li>";
                }
                else {
                    echo "<li class=\"final\">T.B.D VS T.B.D</li>";
                }
            }
            echo "</ul>";
        }


        ?>
    </ul>
</div>

<?php require(realpath(__DIR__) . '/../templates/footer.php');