<!--This header is just a suggestion. Do whatever you want with it!-->
<?php
require("../../app/database.php");
$sqlSelAdmin = "SELECT * FROM tbl_admin";
$admin = $db_conn->prepare($sqlSelAdmin);
$admin->execute();
$admin = $admin->fetchAll(PDO::FETCH_ASSOC);

$sqlSelMatches = "SELECT * FROM tbl_matches WHERE finished = '0' ORDER BY start_time ASC";
$matches = $db_conn->prepare($sqlSelMatches);
$matches->execute();
$matches = $matches->fetchAll(PDO::FETCH_ASSOC);

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
        <?php
        if (isset($_GET['admin'])){
          echo "<div class=\"login-container\">
                <div class=\"login\">
                <h2>Login</h2>
                <form class=\"flex-column align-center\" action=\"../../app/login_manager.php\" method=\"post\">
                <input type=\"text\" name=\"username\" placeholder=\"Username\">
                <input type=\"password\" name=\"password\" placeholder=\"Password\">
                <input type=\"hidden\" name=\"form-type\" value=\"admin_login\">
                <input id=\"login-submit\" type=\"submit\" value=\"Login\">
                </form>
                </div>
                </div>";
        }
        else{

        }
        ?>
    <img class="background" src="../assets/img/banner_stadium.jpg" alt="">
    <header>
        <div class="container flex-between align-center">
            <h1>Project <span>FIFA</span></h1>
            <nav class="navigation">
                <ul class="flex">
                    <li id="left-nav"><a href="../home">Home</a></li>
                    <li><a href="../schedule">Schedule</a></li>
                    <li><a href="../standings">Standings</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content">
        <div class="container">