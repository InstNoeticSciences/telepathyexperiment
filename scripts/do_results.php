<?php
include("../lib/util.php");
include("../inc/config.php");
include("../classes/database.php");

session_start();

$error = false;
$redirect = "";

if($_POST['download_overview']) {
    download_table("experiments", "experiments_".date("Y-m-d_H-i",time()));
} // end if

if($_POST['download_detail']) {
    $where = "experiment_id = ".$_SESSION['experiment_id_dl'];
    download_table("results", "results_".date("Y-m-d_H-i",time()), $where);
} // end if

if($_POST['download_all']) {
    download_table("results", "results_".date("Y-m-d_H-i",time()));
} // end if
?>

