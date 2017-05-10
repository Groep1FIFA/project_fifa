<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

    <div class="teams">
        <h2>Teams</h2>
        <ol>
            <?php

            foreach ($teams as $team){
                echo
                "<li>{$team['name']}</li>";
            }
            ?>
        </ol>

        <div class="create-team">
            <form action="../app/admin_manager.php" method="post">
                <label for="teamName">Create a new team</label>
                <input type="text" name="teamName" placeholder="Team Name">
                <input type="hidden" name="form-type" value="createTeam">
                <input type="submit" value="Add">
            </form>
        </div>
    </div>

    <div class="players">
        <h2>Players</h2>
        <h3>Unasigned Players</h3>
            <?php
                $sqlSel = "SELECT * FROM tbl_players WHERE team_id IS NULL ";
                $players = $db_conn->query($sqlSel);
                foreach ($players as $player){
                    echo "<ul>
                        <li>{$player['first_name']}</li>
                        <li>{$player['last_name']}</li>
                    </ul>
                    <form action=\"../app/admin_manager.php\" method=\"post\">
                        <label for=\"addToTeam\"></label>
                        <input type=\"text\" name=\"addToTeam\" placeholder=\"Team Name\">
                        <input type=\"hidden\" name=\"form-type\" value=\"addToTeam\">
                        <input type=\"hidden\" name=\"player_id\" value=\"{$player['id']}\">
                        <input type=\"submit\" value=\"Add\">
                    </form>";
                }
            ?>
        <h3>Asinged Players</h3>
        <?php
            $sqlSel = "SELECT * FROM tbl_players WHERE team_id IS NOT NULL ";
            $players = $db_conn->query($sqlSel);
            foreach ($players as $player){
                $sqlSel = "SELECT * FROM tbl_teams WHERE id = '{$player['team_id']}'";
                $teamName = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                echo "<ul>
                <li>{$player['first_name']}</li>
                <li>{$player['last_name']}</li>
                <li>{$teamName[0]['name']}</li>
                </ul>
                <form action=\"../app/admin_manager.php\" method=\"post\" class=\"adjust\">
                    <label for=\"player_id\"></label>
                    <input type=\"hidden\" name=\"player_id\" value=\"{$player['id']}\">
                    <input type=\"hidden\" name=\"form-type\" value=\"changeTeam\">
                    <input type=\"submit\" value=\"Change Team\">
                </form>";
            }
        ?>
    </div>
    <div class="poules">
        <h2>Unasigned Teams</h2>
        <?php
        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NULL";
        $teams = $db_conn->query($sqlSel);
        foreach ($teams as $team){
            echo "<ul>
                        <li>{$team['name']}</li>
                    </ul>
                    <form action=\"../app/admin_manager.php\" method=\"post\">
                        <label for=\"pouleName\"></label>
                        <select name=\"pouleName\" id=\"\">
                            <option value=\"A\">Poule A</option>
                            <option value=\"B\">Poule B</option>
                            <option value=\"C\">Poule C</option>
                            <option value=\"D\">Poule D</option>
                        </select>
                        <input type=\"hidden\" name=\"form-type\" value=\"addToPoule\">
                        <input type=\"hidden\" name=\"team_id\" value=\"{$team['id']}\">
                        <input type=\"submit\" value=\"Add\">
                    </form>";
        }
        ?>
        <h2>Poules</h2>
        <?php
            $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NOT NULL";
            $teams = $db_conn->query($sqlSel);
            foreach ($poules as $poule){
                echo "<ul>
                    <li>{$poule['name']}
                    <form action=\"../app/admin_manager.php\" method=\"post\">
                        <label for=\"\"></label>
                        <input type=\"hidden\" name=\"form-type\" value=\"changePoule\">
                        <input type=\"hidden\" name=\"clearPoule\" value=\"{$poule['id']}\">
                        <input type=\"submit\" value=\"Clear Poule\">
                    </form>";
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

    <div class="schedule">
        <h2>Schedule</h2>
        <?php
        foreach ($matches as $match) {
            $sqlPoules = "SELECT * FROM tbl_poules WHERE id = '{$match['poule_id']}'";
            $sqlPoules = $db_conn->query($sqlPoules)->fetchAll(PDO::FETCH_ASSOC);
            $sqlATeams = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_a']}'";
            $sqlATeams = $db_conn->query($sqlATeams)->fetchAll(PDO::FETCH_ASSOC);
            $sqlBTeams = "SELECT * FROM tbl_teams WHERE poule_id = '{$match['poule_id']}' AND team_nr = '{$match['team_id_b']}'";
            $sqlBTeams = $db_conn->query($sqlBTeams)->fetchAll(PDO::FETCH_ASSOC);

            if (!isset($sqlATeams[0]['name']) && !isset($sqlBTeams[0]['name'])){
                echo 'Vul de poulen tot maximaal 4 teams.';
                break;
            }
            else {
                $sqlT1Players = "SELECT * FROM tbl_players WHERE team_id = '{$sqlATeams[0]['id']}'";
                $sqlT1Players = $db_conn->query($sqlT1Players);
                $sqlT2Players = "SELECT * FROM tbl_players WHERE team_id = '{$sqlBTeams[0]['id']}'";
                $sqlT2Players = $db_conn->query($sqlT2Players);
                
                echo "<ul>
                        <li>Poule: {$sqlPoules[0]['name']}
                            <ul>
                                <li>{$sqlATeams[0]['name']} VS {$sqlBTeams[0]['name']} {$match['start_time']}</li>";
                echo "<form action=\"../app/admin_manager.php\" method=\"post\">
                        <select name=\"player-name\" id=\"\">";
                            foreach ($sqlT1Players as $sqlT1Player){
                                echo "<option value=\"{$sqlT1Player['id']}\">{$sqlT1Player['first_name']} {$sqlT1Player['last_name']}</option>";
                            }
                echo "  </select>
                        <input type=\"hidden\" name=\"form-type\" value=\"team-a-scored\">
                        <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                        <input type=\"submit\" value=\"{$sqlATeams[0]['name']} scored\">
                      </form>
                        <li>{$match['score_team_a']}</li>
                        <li>{$match['score_team_b']}</li>
                      <form action=\"../app/admin_manager.php\" method=\"post\">
                        <input type=\"hidden\" name=\"form-type\" value=\"team-b-scored\">
                        <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                        <input type=\"submit\" value=\"{$sqlBTeams[0]['name']} scored\">
                        
                        <select name=\"player-scored\">";
                            foreach ($sqlT2Players as $sqlT2Player){
                                echo "<option value=\"\">{$sqlT2Player['first_name']} {$sqlT2Player['last_name']}</option>";
                            }
                echo "  </select>
                      </form>
                      <form action=\"../app/admin_manager.php\" method=\"post\">
                        <input type=\"hidden\" name=\"form-type\" value=\"match-finished\">
                        <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                        <input type=\"hidden\" name=\"team-id-a\" value=\"{$sqlATeams[0]['id']}\">
                        <input type=\"hidden\" name=\"team-id-b\" value=\"{$sqlBTeams[0]['id']}\">
                        <input type=\"submit\" value=\"End\">
                      </form>
                      </ul>
                        </li>
                      </ul>";
            }
        }
        ?>
    </div>
<!--    <input type=\"hidden\" name=\"score-a\" value=\"{$match['score_team_a']}\">-->
<!--    <input type=\"hidden\" name=\"score-b\" value=\"{$match['score_team_b']}\">-->

<?php require(realpath(__DIR__) . '/templates/footer.php');
