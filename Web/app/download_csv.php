<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 5/31/2017
 * Time: 11:01 AM
 */

require ('database.php');

if (isset($_POST['form-type'])){
    $formtype = $_POST['form-type'];

    if ($formtype == 'download_teams'){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=teams.csv');
        $output = fopen("php://output", "w");

        $sqlSel = 'SELECT id, poule_id, name, team_nr, poule_ranking, playoff_ranking FROM tbl_teams';
        $sqlPre = $db_conn->prepare($sqlSel);
        $sqlPre->execute();

        while($row = $sqlPre->fetch(PDO::FETCH_ASSOC))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
    elseif ($formtype == 'download_schedule'){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=matches.csv');
        $output = fopen("php://output", "w");

        $sqlSel = 'SELECT * FROM tbl_matches';
        $sqlPre = $db_conn->prepare($sqlSel);
        $sqlPre->execute();

        while($row = $sqlPre->fetch(PDO::FETCH_ASSOC))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
    elseif ($formtype == 'download_playoffs'){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=playoffs.csv');
        $output = fopen("php://output", "w");

        $sqlSel = 'SELECT * FROM tbl_playoffs';
        $sqlPre = $db_conn->prepare($sqlSel);
        $sqlPre->execute();

        while($row = $selPre->fetch(PDO::FETCH_ASSOC))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
}
else{
    $message = 'something went wrong';
    header("Location: ../public/admin/admin_panel.php?message=$message");
}