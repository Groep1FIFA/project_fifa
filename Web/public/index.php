<?php require(realpath(__DIR__) . '/templates/header.php'); ?>

    <div class="main-content">

        <div>
            <?php

            foreach ($players as $player){
                echo "<ul>
                        <li>{$player['first_name']}</li>
                        <li>{$player['last_name']}</li>
                        <li>{$player['team_id']}</li>
                     </ul>";
            }
            ?>
        </div>
        <div class="">
            <?php

            foreach ($teams as $team){

            }
            ?>
        </div>


    </div>

<?php require(realpath(__DIR__) . '/templates/footer.php');
