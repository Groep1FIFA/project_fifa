<!--This header is just a suggestion. Do whatever you want with it!-->
<?php
require("../app/database.php");
$sqlSelAdmin = "SELECT * FROM tbl_admin";
$admins = $db_conn->query($sqlSelAdmin)->fetchAll(PDO::FETCH_ASSOC);
$sqlSelMatches = "SELECT * FROM tbl_matches";
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
    <!-- you can link bootstrap if you want.   -->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
