<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

    <div class="main-content">
<h2>=====================================wedstrijdschema=====================================</h2>
        <div>
            <?php
                foreach ($matches as $match) {
                    $sqlPoules = "SELECT * FROM tbl_poules WHERE id = '{$match['poule_id']}'";
                    $sqlPoules = $db_conn->query($sqlPoules)->fetchAll(PDO::FETCH_ASSOC);
                    $sqlTeams = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}'
                        AND team_nr = '{$match['team_id_a']}' OR team_nr = '{$match['team_id_b']}'";
                    $sqlTeams = $db_conn->query($sqlTeams)->fetchAll(PDO::FETCH_ASSOC);

                    if (!isset($sqlTeams[1]['name'])){
                        echo 'Vul de poulen tot maximaal 4 teams.';
                        break;
                    }
                    else {
                        echo "<ul>
                            <li>Poule: {$sqlPoules[0]['naam']}
                                <ul>
                                    <li>{$sqlTeams[0]['name']} VS {$sqlTeams[1]['name']} {$match['start_time']}</li>
                                </ul>
                            </li>
                        </ul>";
                    }
                }
            ?>
        </div>
        <h2>=====================================teams + players=====================================</h2>

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
        <h2>=====================================topscoorders=====================================</h2>
        <div class="">
            <?php
                $playerGoals = "SELECT * FROM tbl_players ORDER BY goals DESC";
                $playerGoals = $db_conn->query($playerGoals);
                $playerGoals = $playerGoals->fetchAll(PDO::FETCH_ASSOC);
                foreach ($playerGoals as $playerGoal){
                    echo "<ul>
                             <li>{$playerGoal['first_name']}</li>
                             <li>{$playerGoal['last_name']}</li>
                             <li>{$playerGoal['goals']}</li>
                        </ul>";
                }
            
            ?>
        </div>
        <h2>=====================================Poules=====================================</h2>
        <div class="">
            <?php
            
            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NOT NULL";
            $teams = $db_conn->query($sqlSel);
            foreach ($poules as $poule){
                echo "<ul>
                    <li>{$poule['naam']}";
                $sqlSel = "SELECT * FROM tbl_poules WHERE naam = '{$poule['naam']}'";
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
    </div>
<h2>=====================================aanmelddinges=====================================</h2>
<?php require(realpath(__DIR__) . '/templates/footer.php');
