<?php require(realpath(__DIR__) . '/templates/header.php'); ?>
    <div class="banner flex-between">
        <?php
        foreach ($poules as $poule){
            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '{$poule['id']}'";
            $teams = $db_conn->query($sqlSel);
            echo "<ul class=\"poule-item\">
            <li class=\"poule-name\">Poule: {$poule['name']}</li>
            <ul class=\"poule-teams\">";
            foreach ($teams as $team){
                echo "<li>{$team['name']}</li>";
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
                    $sqlTeams = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_a']}' OR poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_b']}'";
                    $sqlTeams = $db_conn->query($sqlTeams)->fetchAll(PDO::FETCH_ASSOC);

                    echo "<pre>";
                    var_dump($sqlTeams);

                    if (!isset($sqlTeams[1]['name'])){
                        echo 'Vul de poulen tot maximaal 4 teams.';
                        break;
                    }
                    else {
                        echo "<tr class=\"match-data align-center\">
                    <td>{$match['start_time']}</td>
                    <td>{$sqlPoules[0]['name']}</td>
                    <td>{$sqlTeams[0]['name']}</td>
                    <td>{$match['score_team_a']} - {$match['score_team_b']}</td> 
                    <td class=\"last-match-data\">{$sqlTeams[1]['name']}</td>
                    </tr>";

                    }
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
                    echo "<tr class=\"align-center\">
                             <td>{$playerGoal['first_name']}</td>
                             <td>{$playerGoal['last_name']}</td>
                             <td>{$playerGoal['goals']}</td>
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


    <div class="">
        <?php
        foreach ($teams as $team){
            echo "<ul>
                    <li>{$team['name']}</li>
                   ";
            $teamPlayers = "SELECT * FROM tbl_players WHERE team_id='{$team['id']}'";
            $teamPlayers = $db_conn->query($teamPlayers);
            foreach ($teamPlayers as $teamPlayer){
                echo
                "<ul>
                    <li>{$teamPlayer['first_name']} {$teamPlayer['last_name']}</li>
                </ul>";
            }
            echo "</ul>";
        }
        ?>
    </div>

    <div class="">
        <?php

        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NOT NULL";
        $teams = $db_conn->query($sqlSel);
        foreach ($poules as $poule){
            echo "<ul>
                <li>{$poule['name']}";
            $sqlSel = "SELECT * FROM tbl_poules WHERE name = '{$poule['name']}'";
            $pouleId = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
            $pouleId = $pouleId[0]['id'];
            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '$pouleId'";
            $teams = $db_conn->query($sqlSel);

            foreach ($teams as $team){
                echo "<ul><li>{$team['name']}</li></ul>";
            }

            echo "</li>
                  </ul>";
        }
        ?>
    </div>
<?php require(realpath(__DIR__) . '/templates/footer.php');
