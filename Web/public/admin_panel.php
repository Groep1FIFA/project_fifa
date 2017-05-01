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
        <?php


        ?>
    </div>




</div>

<?php require(realpath(__DIR__) . '/templates/footer.php');
