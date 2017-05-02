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
        <h2>Unasigned Players</h2>
            <?php
                $sqlSel = "SELECT * FROM tbl_players WHERE team_id IS NULL ";
                $players = $db_conn->query($sqlSel);
                foreach ($players as $player){
                    echo "<ul>
                        <li>{$player['first_name']}</li>
                        <li>{$player['last_name']}</li>
                    </ul>
                    <form action=\"../app/admin_manager.php\" method=\"post\">
                        <label for=\"addTeam\"></label>
                        <input type=\"text\" name=\"addTeam\" placeholder=\"Team Name\">
                        <input type=\"hidden\" name=\"form-type\" value=\"addTeam\">
                        <input type=\"hidden\" name=\"player_id\" value=\"{$player['id']}\">
                        <input type=\"submit\" value=\"Add\">
                    </form>";
                }
            ?>
        <h2>Singed Players</h2>
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


</div>

<?php require(realpath(__DIR__) . '/templates/footer.php');
