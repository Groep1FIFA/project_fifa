<!--This header is just a suggestion. Do whatever you want with it!-->
<?php
require("../app/database.php");
$sqlSelAdmin = "SELECT * FROM tbl_admin";
$admins = $db_conn->query($sqlSelAdmin)->fetchAll(PDO::FETCH_ASSOC);
$sqlSelMatches = "SELECT * FROM tbl_matches ORDER BY poule_id ASC, start_time ASC";
$matches = $db_conn->query($sqlSelMatches)->fetchAll(PDO::FETCH_ASSOC);
$sqlSelPlayers = "SELECT * FROM  tbl_players";
$players = $db_conn->query($sqlSelPlayers)->fetchAll(PDO::FETCH_ASSOC);
$sqlSelPoules = "SELECT * FROM tbl_poules";
$poules = $db_conn->query($sqlSelPoules)->fetchAll(PDO::FETCH_ASSOC);
$sqlSelTeams = "SELECT * FROM tbl_teams";
$teams = $db_conn->query($sqlSelTeams)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Fifa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrapper">
    <img class="background" src="assets/img/banner_stadium.jpg" alt="">
    <header>
       <h1>Project <span>FIFA</span></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="schedule.php">Schedule</a></li>
                <li><a href="standins.php">Standings</a></li>
                <li><a href="teams.php">Teams</a></li>
            </ul>
        </nav>
    </header>
    <div class="main-content">
        <div class="container">