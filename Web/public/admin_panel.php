<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

<div class="main-content">



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
                    <li>{$poule['naam']}";
                    $sqlSel = "SELECT * FROM tbl_poules WHERE naam = '{$poule['naam']}'";
                        $pouleId = $db_conn->query($sqlSel)->fetchAll(PDO::FETCH_ASSOC);
                        $pouleId = $pouleId[0]['id'];
                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '$pouleId'";
                        $teams = $db_conn->query($sqlSel);
            
                        foreach ($teams as $team){
                            echo "<ul><li>{$team['name']}</li></ul>
                            <form action=\"../app/admin_manager.php\" method=\"post\">
                                <label for=\"changePoule\"></label>
                                <input type=\"hidden\" name=\"form-type\" value=\"changePoule\">
                                <input type=\"hidden\" name=\"changePoule\" value=\"{$team['id']}\">
                                <input type=\"submit\" value=\"Change poule\">
                            </form>";
                        }
                    echo "</li>
                    </ul>";
            }

        ?>
    </div>
<!--    <input type="text" name="addToPoule" placeholder="Poule">-->
</div>

<?php require(realpath(__DIR__) . '/templates/footer.php');
