<?php
/**
 * Created by PhpStorm.
 * User: Youri
 * Date: 4/21/2017
 * Time: 11:08 AM
 */
$db_conn = new PDO(
    "mysql:host=mysql-fifaproject.alwaysdata.net;dbname=fifaproject_project_fifa",
    "139419",
    "igoerwhjfgrweiogfje"
);
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);