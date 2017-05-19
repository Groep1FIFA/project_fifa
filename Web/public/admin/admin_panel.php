<?php
require("../../app/database.php");
$sqlSelAdmin = "SELECT * FROM tbl_admin";
$admins = $db_conn->prepare($sqlSelAdmin);
$admins->execute();
$admins = $admins->fetchAll(PDO::FETCH_ASSOC);

$sqlSelMatches = "SELECT * FROM tbl_matches WHERE finished = '0' ORDER BY start_time ASC";
$matches = $db_conn->prepare($sqlSelMatches);
$matches->execute();
$matches = $matches->fetchAll(PDO::FETCH_ASSOC);

$sqlSelMatches = "SELECT * FROM tbl_matches WHERE finished = '1'";
$matchesFinished = $db_conn->prepare($sqlSelMatches);
$matchesFinished->execute();
$matchesFinished = $matchesFinished->rowCount();

$sqlSelPlayers = "SELECT * FROM  tbl_players";
$players = $db_conn->prepare($sqlSelPlayers);
$players->execute();
$players = $players->fetchAll(PDO::FETCH_ASSOC);

$sqlSelPoules = "SELECT * FROM tbl_poules";
$poules = $db_conn->prepare($sqlSelPoules);
$poules->execute();
$poules = $poules->fetchAll(PDO::FETCH_ASSOC);

$sqlSelTeams = "SELECT * FROM tbl_teams";
$teams = $db_conn->prepare($sqlSelTeams);
$teams->execute();
$teams = $teams->fetchAll(PDO::FETCH_ASSOC);

session_start();

if (!isset($_SESSION['admin_login'])){
    session_unset();
    session_destroy();

    header("Location: ../admin");
}
else{

}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Project Fifa</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
<body>
<div class="wrapper">
    <img class="background" src="../assets/img/banner_stadium.jpg" alt="">
    <section>
        <header>
            <div class="admin-header container flex-between align-center">
                <h1>Project <span>FIFA</span></h1>
                <h1>Admin <span>Panel</span></h1>
            </div>
            <div class="logout">
                <form action="../../app/login_manager.php" method="post">
                    <input type="hidden" name="form-type" value="logout">
                    <input type="submit" value="Logout">
                </form>
            </div>
        </header>
    </section>
    <div class="main-content">
        <div class="container">
        <section>
            <div class="admin-banner">
                <ul>
                    <?php

                    foreach ($teams as $team){
                        echo
                        "<li class=\"align-center team-item\">{$team['name']}</li>";
                    }
                    ?>
                </ul>

                <div class="create-team flex-center">
                    <form action="../../app/admin_manager.php" method="post" class="flex-column align-center">
                        <input type="text" name="teamName" placeholder="Create a new team">
                        <input type="hidden" name="form-type" value="createTeam">
                        <input type="submit" value="Add" class="submit">
                    </form>
                </div>
            </div>
        </section>
        <section>
            <div class="players flex-between">
                <div class="unasigned-players">
                    <h2>Unassigned Players</h2>
                        <?php
                        $sqlSel = "SELECT * FROM tbl_players WHERE team_id IS NULL ";
                        $players = $db_conn->prepare($sqlSel);
                        $players->execute();

                        foreach ($players as $player){
                            echo "<ul class=\"flex\">
                                <li>{$player['first_name']} {$player['last_name']}</li>";
                            if($matchesFinished == 0) {
                                echo "<form class=\"flex align-center\" action=\"../../app/admin_manager.php\" method=\"post\">
                                    <input class=\"add-player\" type=\"text\" name=\"addToTeam\" placeholder=\"Team Name\">
                                    <input type=\"hidden\" name=\"form-type\" value=\"addToTeam\">
                                    <input type=\"hidden\" name=\"player_id\" value=\"{$player['id']}\">
                                    <input class=\"add-player player-add\" type=\"submit\" value=\"Add\">
                                </form>";
                            }
                            else{

                            }
                            echo "</ul>";
                        }
                        ?>
                </div>
                <div class="asigned-players">
                    <h2>Assinged Players</h2>
                    <?php
                    $sqlSel = "SELECT * FROM tbl_players WHERE team_id IS NOT NULL ";
                    $players = $db_conn->prepare($sqlSel);
                    $players->execute();

                    foreach ($players as $player){
                        $sqlSel = "SELECT * FROM tbl_teams WHERE id = :team_id";
                        $teamName = $db_conn->prepare($sqlSel);
                        $teamName->execute(['team_id' => $player['team_id']]);
                        $teamName = $teamName->fetchAll(PDO::FETCH_ASSOC);

                        echo "<ul class=\"flex align-center\">
                        <li class=\"player-name\">{$player['first_name']} {$player['last_name']}</li>
                        <li>{$teamName[0]['name']}</li>";
                        if($matchesFinished == 0) {
                            echo "<form action = \"../../app/admin_manager.php\" method=\"post\" class=\"adjust\">
                                <label for=\"player_id\"></label>
                                <input type=\"hidden\" name=\"player_id\" value=\"{$player['id']}\">
                                <input type=\"hidden\" name=\"form-type\" value=\"changeTeam\">
                                <input class=\"add-player\" type=\"submit\" value=\"Change Team\">
                            </form>";
                        }
                        else{

                        }
                        echo "</ul>";
                    }
                    ?>
                </div>
            </div>
        </section>
        <section>
            <div class="poules flex-between">
                <div class="unasigned-teams">
                    <h2>Unassigned Teams</h2>
                    <?php
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NULL";
                    $teams = $db_conn->prepare($sqlSel);
                    $teams->execute();

                    foreach ($teams as $team){
                        echo "<ul class=\"flex\">
                                <li>{$team['name']}</li>";
                        if($matchesFinished == 0) {
                            echo "<form action=\"../../app/admin_manager.php\" method=\"post\">
                                <label for=\"pouleName\"></label>
                                <select class=\"add-player\" name=\"pouleName\" id=\"\">
                                    <option value=\"A\">Poule A</option>
                                    <option value=\"B\">Poule B</option>
                                    <option value=\"C\">Poule C</option>
                                    <option value=\"D\">Poule D</option>
                                </select>
                                <input type=\"hidden\" name=\"form-type\" value=\"addToPoule\">
                                <input type=\"hidden\" name=\"team_id\" value=\"{$team['id']}\">
                                <input class=\"add-player player-add\" type=\"submit\" value=\"Add\">
                            </form>";
                        }
                        echo "</ul>";
                    }
                    ?>
                </div>
                <div class="asigned-teams">
                    <h2>Poules</h2>
                    <?php
                    $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id IS NOT NULL";
                    $teams = $db_conn->prepare($sqlSel);
                    $teams->execute();

                    foreach ($poules as $poule){
                        echo "<ul>
                            <li>{$poule['name']}";
                        if($matchesFinished == 0) {
                            echo "<form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input class=\"add-player\" type=\"hidden\" name=\"form-type\" value=\"changePoule\">
                                <input type=\"hidden\" name=\"clearPoule\" value=\"{$poule['id']}\">
                                <input class=\"add-player player-add\" type=\"submit\" value=\"Clear Poule\">
                            </form>";
                        }
                        $sqlSel = "SELECT * FROM tbl_poules WHERE name = :name";
                        $pouleId = $db_conn->prepare($sqlSel);
                        $pouleId->execute(['name' => $poule['name']]);
                        $pouleId = $pouleId->fetchAll(PDO::FETCH_ASSOC);
                        $pouleId = $pouleId[0]['id'];

                        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = :pouleId";
                        $teams = $db_conn->prepare($sqlSel);
                        $teams->execute(['pouleId' => $pouleId]);

                        foreach ($teams as $team){
                            echo "<ul><li>{$team['name']}</li></ul>";
                        }
                        echo "</li>
                            </ul>";
                    }
                    ?>
                </div>
            </div>
        </section>
        <section>
            <div class="schedule admin-schedule">
                <h2>Schedule</h2>
                <table>
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
                        $sqlT1Players = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $sqlT1Players = $db_conn->prepare($sqlT1Players);
                        $sqlT1Players->execute(['id' => $sqlATeams[0]['id']]);

                        $sqlT2Players = "SELECT * FROM tbl_players WHERE team_id = :id";
                        $sqlT2Players = $db_conn->prepare($sqlT2Players);
                        $sqlT2Players->execute(['id' => $sqlBTeams[0]['id']]);

                        echo "<tr class=\"align-center\">
                                <td>Poule: {$sqlPoules[0]['name']}</td>
                                <td>{$sqlATeams[0]['name']} VS {$sqlBTeams[0]['name']} {$match['start_time']}</td>";
                        echo "<form action=\"../../app/admin_manager.php\" method=\"post\">
                                <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                                    foreach ($sqlT1Players as $sqlT1Player){
                                        echo "<option value=\"{$sqlT1Player['id']}\">{$sqlT1Player['first_name']} {$sqlT1Player['last_name']}</option>";
                                    }
                        echo "  </select></td>
                                <input type=\"hidden\" name=\"form-type\" value=\"team-a-scored\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                                <td><input class=\"score-button\" type=\"submit\" value=\"{$sqlATeams[0]['name']} scored\"></td>
                              </form>
                                <td>{$match['score_team_a']} - {$match['score_team_b']}</td>
                              <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"team-b-scored\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                                <td><input class=\"score-button\" type=\"submit\" value=\"{$sqlBTeams[0]['name']} scored\"></td>
                                
                                <td><select class=\"player-selector\" name=\"player-name\">";
                                    foreach ($sqlT2Players as $sqlT2Player){
                                        echo "<option value=\"{$sqlT2Player['id']}\">{$sqlT2Player['first_name']} {$sqlT2Player['last_name']}</option>";
                                    }
                        echo "  </select></td>
                              </form>
                              <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"match-finished\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$match['id']}\">
                                <input type=\"hidden\" name=\"team-id-a\" value=\"{$sqlATeams[0]['id']}\">
                                <input type=\"hidden\" name=\"team-id-b\" value=\"{$sqlBTeams[0]['id']}\">
                                <td><input class=\"end-button\" type=\"submit\" value=\"End\"></td>
                              </form>
                              </tr>";
                    }
                }
                ?>
                </table>
            </div>
            <div class="playoffs">
                <h2>Playoffs</h2>
                <table class="align-center">
                    <?php
                    $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 0";
                    $sqlCount = $db_conn->query($sqlSel)->rowCount();

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

                            echo "<tr class=\"quaters\">
                            <td>Quater-Final</td>
                            <td>{$quaterFinal['start_time']}</td>
                            <td>{$teamA[0]['name']} VS {$teamB[0]['name']}</td>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                            <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                            foreach ($aTeamPlayers as $aTeamPlayer) {
                                echo "<option value=\"{$aTeamPlayer['id']}\">{$aTeamPlayer['first_name']} {$aTeamPlayer['last_name']}</option>";
                            }
                            echo "</select></td>
                            <input type=\"hidden\" name=\"form-type\" value=\"playoff-a-scored\">
                            <input type=\"hidden\" name=\"match-id\" value=\"{$quaterFinal['id']}\">
                            <td><input class=\"score-button\" type=\"submit\" value=\"{$teamA[0]['name']} scored\"></td>
                            </form>
                            <td>{$quaterFinal['score_team_a']} VS {$quaterFinal['score_team_b']}</td>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                            <input type=\"hidden\" name=\"form-type\" value=\"playoff-b-scored\">
                            <input type=\"hidden\" name=\"match-id\" value=\"{$quaterFinal['id']}\">
                            <td><input class=\"score-button\" type=\"submit\" value=\"{$teamB[0]['name']} scored\"></td>
                            <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                            foreach ($bTeamPlayers as $bTeamPlayer) {
                                echo "<option value=\"{$bTeamPlayer['id']}\">{$bTeamPlayer['first_name']} {$bTeamPlayer['last_name']}</option>";
                            }
                            echo "</select>
                                </td>
                            </form>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"playoff-finished\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$quaterFinal['id']}\">
                                <input type=\"hidden\" name=\"team-id-a\" value=\"{$teamA[0]['id']}\">
                                <input type=\"hidden\" name=\"team-id-b\" value=\"{$teamB[0]['id']}\">
                                <td><input class=\"end-button\" type=\"submit\" value=\"End\"></td>
                              </form>
                            </tr>";
                        }
                        else{
                            echo "<tr class=\"to-be-decided quaters\">
                            <td>Quater-Final</td>
                            <td>T.B.D {$quaterFinal['score_team_a']} VS {$quaterFinal['score_team_b']} T.B.D</td>
                            <td>{$quaterFinal['start_time']}</td>
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

                                echo "<tr class=\"semis\">
                            <td>Semi-Final</td>
                            <td>{$semiFinal['start_time']}</td>
                            <td>{$teamA[0]['name']} VS {$teamB[0]['name']}</td>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                            <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                                foreach ($aTeamPlayers as $aTeamPlayer) {
                                    echo "<option value=\"{$aTeamPlayer['id']}\">{$aTeamPlayer['first_name']} {$aTeamPlayer['last_name']}</option>";
                                }
                                echo "</select></td>
                            <input type=\"hidden\" name=\"form-type\" value=\"playoff-a-scored\">
                            <input type=\"hidden\" name=\"match-id\" value=\"{$semiFinal['id']}\">
                            <td><input class=\"score-button\" type=\"submit\" value=\"{$teamA[0]['name']} scored\"></td>
                            </form>
                            <td>{$semiFinal['score_team_a']} VS {$semiFinal['score_team_b']}</td>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                            <input type=\"hidden\" name=\"form-type\" value=\"playoff-b-scored\">
                            <input type=\"hidden\" name=\"match-id\" value=\"{$semiFinal['id']}\">
                            <td><input class=\"score-button\" type=\"submit\" value=\"{$teamB[0]['name']} scored\"></td>
                            <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                                foreach ($bTeamPlayers as $bTeamPlayer) {
                                    echo "<option value=\"{$bTeamPlayer['id']}\">{$bTeamPlayer['first_name']} {$bTeamPlayer['last_name']}</option>";
                                }
                                echo "</select>
                                </td>
                            </form>
                            <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"playoff-finished\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$semiFinal['id']}\">
                                <input type=\"hidden\" name=\"team-id-a\" value=\"{$teamA[0]['id']}\">
                                <input type=\"hidden\" name=\"team-id-b\" value=\"{$teamB[0]['id']}\">
                                <td><input class=\"end-button\" type=\"submit\" value=\"End\"></td>
                              </form>
                            </tr>";
                            }
                            else {
                                echo "<tr class=\"to-be-decided semis\">
                            <td>Semi-Final</td>
                            <td>T.B.D {$semiFinal['score_team_a']} VS {$semiFinal['score_team_b']} T.B.D</td>
                            <td>{$semiFinal['start_time']}</td>
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

                                echo "<tr class=\"finals\">
                                <td>Final</td>
                                <td>{$final['start_time']}</td>
                                <td>{$teamA[0]['name']} VS {$teamB[0]['name']}</td>
                                <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                                    foreach ($aTeamPlayers as $aTeamPlayer) {
                                        echo "<option value=\"{$aTeamPlayer['id']}\">{$aTeamPlayer['first_name']} {$aTeamPlayer['last_name']}</option>";
                                    }
                                    echo "</select></td>
                                <input type=\"hidden\" name=\"form-type\" value=\"playoff-a-scored\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$final['id']}\">
                                <td><input class=\"score-button\" type=\"submit\" value=\"{$teamA[0]['name']} scored\"></td>
                                </form>
                                <td>{$final['score_team_a']} VS {$final['score_team_b']}</td>
                                <form action=\"../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"playoff-b-scored\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$final['id']}\">
                                <td><input class=\"score-button\" type=\"submit\" value=\"{$teamB[0]['name']} scored\"></td>
                                <td><select class=\"player-selector\" name=\"player-name\" id=\"\">";
                                foreach ($bTeamPlayers as $bTeamPlayer) {
                                    echo "<option value=\"{$bTeamPlayer['id']}\">{$bTeamPlayer['first_name']} {$bTeamPlayer['last_name']}</option>";
                                }
                                echo "</select>
                                </td>
                            </form>
                            <form action=\" ../../app/admin_manager.php\" method=\"post\">
                                <input type=\"hidden\" name=\"form-type\" value=\"playoff-finished\">
                                <input type=\"hidden\" name=\"match-id\" value=\"{$final['id']}\">
                                <input type=\"hidden\" name=\"team-id-a\" value=\"{$teamA[0]['id']}\">
                                <input type=\"hidden\" name=\"team-id-b\" value=\"{$teamB[0]['id']}\">
                                <td><input class=\"end-button\" type=\"submit\" value=\"End\"></td>
                              </form>
                            </tr>";
                            }
                            else{
                                echo "<tr class=\"to-be-decided finals\">
                            <td>Final</td>
                            <td>T.B.D {$final['score_team_a']} VS {$final['score_team_b']} T.B.D</td>
                            <td>{$final['start_time']}</td>
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
        </div>
    </div>
    <footer class="admin-footer">
        <div class="container playoffs-schedule">
            <?php
                $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 0";
                $finishedMatches = $db_conn->query($sqlSel)->rowCount();
                $sqlSel = "SELECT * FROM tbl_teams WHERE poule_ranking = 1 OR poule_ranking = 2";
                $pouleRanking = $db_conn->query($sqlSel)->rowCount();

                if ($finishedMatches == 0 && $pouleRanking == 0){
                    echo "<form action=\"../../app/admin_manager.php\" method=\"post\">
                        <input type=\"hidden\" name=\"form-type\" value=\"start-playoffs\">
                        <input type=\"submit\" value=\"Start The Playoffs\">
                      </form>";
                }
                else{

                }
            ?>
        </div>
        <h3 class="align-center">Copyright &copy; 2017, Project FIFA Groep 1</h3>
    </footer>
    <script src="../../assets/js/jquery.js"></script>
    <script src="../../assets/js/team_players.js"></script>
    <script src="../../assets/js/smooth-scroll.js"></script>
    <script>smoothScroll.init();</script>
</div>
</body>
</html>
