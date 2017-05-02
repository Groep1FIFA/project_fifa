<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

    <div class="main-content">
        <p>------------------------------------------------------------------</p>
        <div>
            <?php
                
                foreach ($matches as $match){
                    
                    $matchTeamsA = "SELECT * FROM tbl_teams WHERE id='{$match['team_id_a']}'";
                    $matchTeamsA = $db_conn->query($matchTeamsA);
                    foreach ($matchTeamsA as $matchTeamA) {
                        echo "<ul>
                                    <li>{$matchTeamA['name']}</li>
                              </ul>";
                    }

                    $matchTeamsB = "SELECT * FROM tbl_teams WHERE id='{$match['team_id_b']}'";
                    $matchTeamsB = $db_conn->query($matchTeamsB);
                    foreach ($matchTeamsB as $matchTeamB){
                        echo "<ul>
                                <li>{$matchTeamB['name']}</li>
                              </ul>";
                    }
    
                    echo "<ul>
                                <li>{$match['start_time']}</li>
                            </ul>";
                }
            ?>
        </div>
        <p>------------------------------------------------------------------</p>
        <div class="">
            <?php

            foreach ($teams as $team){
                echo "<ul>
                        <li>{$team['name']}</li>
                       </ul>";
                $teamPlayers = "SELECT * FROM tbl_players WHERE team_id='{$team['id']}'";
                $teamPlayers = $db_conn->query($teamPlayers);
                foreach ($teamPlayers as $teamPlayer){
                    echo
                    "<ul>
                        <li>{$teamPlayer['first_name']}</li>
                    </ul>
                    ";
                }
            }
            ?>
        </div>
        <p>------------------------------------------------------------------</p>
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
    </div>
    <p>------------------------------------------------------------------</p>
<?php require(realpath(__DIR__) . '/templates/footer.php');
