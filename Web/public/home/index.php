<?php require(realpath(__DIR__) . '/../templates/header.php');
$currentPage = "index.php";
?>
    <div class="banner flex-between">
        <?php
        foreach ($poules as $poule){
            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '{$poule['id']}'";
            $teams = $db_conn->query($sqlSel);
            echo "<ul class=\"poule-item\">
            <li class=\"poule-name\">Poule: {$poule['name']}</li>
            <ul class=\"poule-teams\">";
            foreach ($teams as $team){
                echo "<li><a data-scroll data-options='{ \"easing\": \"linear\" }' 
                href=\"#teams\">{$team['name']}</a></li>";
            }
            echo "</ul>
            </ul>";
        }
        
        
        ?>
    </div>
<section>
    <div class="flex-between">
        <div class="schedule">
            <h2>Schedule</h2>
            <table>
                <tr>
                    <th>Datum</th>
                    <th>Poule</th>
                    <th>Team</th>
                    <th>Score</th>
                    <th>Team</th>
                </tr>
                <?php
                foreach ($matches as $match) {
                    $sqlPoules = "SELECT * FROM tbl_poules WHERE id = '{$match['poule_id']}'";
                    $sqlPoules = $db_conn->query($sqlPoules)->fetchAll(PDO::FETCH_ASSOC);
                    $sqlTeamsA = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_a']}'";
                    $sqlTeamsA = $db_conn->query($sqlTeamsA)->fetchAll(PDO::FETCH_ASSOC);
                    $sqlTeamsB = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_b']}'";
                    $sqlTeamsB = $db_conn->query($sqlTeamsB)->fetchAll(PDO::FETCH_ASSOC);

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
                $sqlCount = $db_conn->query($sqlSel)->rowCount();

                if ($sqlCount >= 1) {
                    $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 0 AND playoff_ranking_b = 0 AND finished = 0";
                    $quaterFinals = $db_conn->query($sqlSel);
                    $quaterCount = $quaterFinals->rowCount();
                    $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 1 AND playoff_ranking_b = 1 AND finished = 0";
                    $semiFinals = $db_conn->query($sqlSel);
                    $semiCount = $semiFinals->rowCount();
                    $sqlSel = "SELECT * FROM tbl_playoffs WHERE playoff_ranking_a = 2 AND playoff_ranking_b = 2 AND finished = 0";
                    $finals = $db_conn->query($sqlSel);

                    foreach ($quaterFinals as $quaterFinal){
                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = '{$quaterFinal['poule_ranking_a']}'";
                        $countA = $db_conn->query($sqlSel)->rowCount();
                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = '{$quaterFinal['poule_ranking_b']}'";
                        $countB = $db_conn->query($sqlSel)->rowCount();

                        if ($countA > 0 && $countB > 0) {
                            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '{$quaterFinal['poule_id_a']}' AND poule_ranking = '{$quaterFinal['poule_ranking_a']}'";
                            $teamA = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '{$quaterFinal['poule_id_b']}' AND poule_ranking = '{$quaterFinal['poule_ranking_b']}'";
                            $teamB = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamA[0]['id']}'";
                            $aTeamPlayers = $db_conn->query($sqlSel);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamB[0]['id']}'";
                            $bTeamPlayers = $db_conn->query($sqlSel);

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
                            $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$semiFinal['playoff_id_a']}' AND playoff_ranking = '{$semiFinal['playoff_ranking_a']}'";
                            $teamA = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$semiFinal['playoff_id_b']}' AND playoff_ranking = '{$semiFinal['playoff_ranking_b']}'";
                            $teamB = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamA[0]['id']}'";
                            $aTeamPlayers = $db_conn->query($sqlSel);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamB[0]['id']}'";
                            $bTeamPlayers = $db_conn->query($sqlSel);

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
                            $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$final['playoff_id_a']}' AND playoff_ranking = '{$final['playoff_ranking_a']}'";
                            $teamA = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_teams WHERE playoff_id = '{$final['playoff_id_b']}' AND playoff_ranking = '{$final['playoff_ranking_b']}'";
                            $teamB = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamA[0]['id']}'";
                            $aTeamPlayers = $db_conn->query($sqlSel);
                            $sqlSel = "SELECT * FROM tbl_players WHERE team_id = '{$teamB[0]['id']}'";
                            $bTeamPlayers = $db_conn->query($sqlSel);

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
        <div class="topscoorders">
            <h2>Top scoorders</h2>
            <table>
                <?php
                $i = 0;

                $playerGoals = "SELECT * FROM tbl_players ORDER BY goals DESC";
                $playerGoals = $db_conn->query($playerGoals);
                $playerGoals = $playerGoals->fetchAll(PDO::FETCH_ASSOC);
                foreach ($playerGoals as $playerGoal){
                    echo "<tr>
                             <td>{$playerGoal['first_name']} {$playerGoal['last_name']}</td>
                             <td class=\"align-center\">{$playerGoal['goals']}</td>
                        </tr>";
                    $i++;
                    if ($i == 10){
                        break;
                    }
                }

                ?>
            </table>
        </div>
    </div>
</section>
<section>
    <div class="teams align-center">
        <h2>Teams</h2>
        <div class="teams-container" id="teams">
            <?php
            $sqlSel = "SELECT * FROM tbl_teams";
            $teams = $db_conn->query($sqlSel);

            foreach ($teams as $team){
                echo "<ul class=\"team align-center\">
                        <li class=\"team-name\">{$team['name']}</li>
                        <li class=\"cross\">{$team['name']}</li>
                        <ul class=\"team-players\">";
                $teamPlayers = "SELECT * FROM tbl_players WHERE team_id='{$team['id']}'";
                $teamPlayers = $db_conn->query($teamPlayers);
                foreach ($teamPlayers as $teamPlayer){
                    echo "<li>{$teamPlayer['first_name']} {$teamPlayer['last_name']}</li>";
                }
                echo "</ul>
                </ul>";
            }
            ?>
        </div>
    </div>
</section>
<?php require(realpath(__DIR__) . '/../templates/footer.php');
