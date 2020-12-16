<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/user.php");

session_start();

if($_POST['enter']) {
    $redirect = "Location: ../confirm.php";
} // end if

if($_POST['confirm']) {
    $_SESSION['confirm_delete'] = true;
    $redirect = "Location: do_index.php";
} // end if

if($_POST['deny']) {
    $_SESSION['confirm_delete'] = false;
    $redirect = "Location: ../index.php";
} // end if

header($redirect);
?>
