<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 5/15/2017
 * Time: 3:09 PM
 */
?>
<?php require(realpath(__DIR__) . '/../templates/header.php'); ?>

<div class="standings">
    <?php
    foreach ($poules as $poule){
        echo "<ul class=\"flex align-center\">
        <li class=\"poule-name\">{$poule['name']}</li>
        <table>
        <tr>
            <th>Team Name</th>
            <th>Points</th>
            <th>Wins</th>
            <th>Ties</th>
            <th>Loses</th>
            <th>Goal Balance</th>
        </tr>";

        $sqlSel = "SELECT * FROM tbl_teams WHERE poule_id = '{$poule['id']}' ORDER BY points DESC, goal_balance DESC";
        $teams = $db_conn->query($sqlSel);

        foreach ($teams as $team){
            echo "<tr>
                  <td>{$team['name']}</td>
                  <td>{$team['points']}</td>
                  <td>{$team['win']}</td>
                  <td>{$team['tie']}</td>
                  <td>{$team['lose']}</td>
                  <td>{$team['goal_balance']}</td>
                  </tr>";
        }
        echo "</table></ul>";
    }

    ?>
</div>

<?php require(realpath(__DIR__) . '/../templates/footer.php');