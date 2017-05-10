<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

<section>
    <div class="poule-phase">
        <table>
            <?php
            $sqlSel = "SELECT * FROM tbl_matches WHERE finished = 1";
            $finishedMatches = $db_conn->query($sqlSel);
            foreach ($finishedMatches as $finishedMatch){
                $sqlPoules = "SELECT * FROM tbl_poules WHERE id = '{$finishedMatch['poule_id']}'";
                $sqlPoules = $db_conn->query($sqlPoules)->fetchAll(PDO::FETCH_ASSOC);
                $sqlTeamsA = "SELECT * FROM tbl_teams WHERE poule_id = '{$finishedMatch['poule_id']}' AND team_nr = '{$finishedMatch['team_id_a']}'";
                $sqlTeamsA = $db_conn->query($sqlTeamsA)->fetchAll(PDO::FETCH_ASSOC);
                $sqlTeamsB = "SELECT * FROM tbl_teams WHERE poule_id = '{$finishedMatch['poule_id']}' AND team_nr = '{$finishedMatch['team_id_b']}'";
                $sqlTeamsB = $db_conn->query($sqlTeamsB)->fetchAll(PDO::FETCH_ASSOC);

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
            ?>
        </table>
    </div>
</section>

<div class="play-offs">

</div>

<?php require(realpath(__DIR__) . '/templates/footer.php');