<?php
    include("lib/database.php");

    // url string variable
    $url = $_SERVER['QUERY_STRING'];

    // get data returned by the callback
    $apiID     = $_GET['api_id'];
    $apiMsgID  = $_GET['apiMsgID'];
    $cliMsgId  = $_GET['cliMsgId'];
    $to        = $_GET['to'];
    $timestamp = $_GET['timestamp'];
    $from      = $_GET['from'];
    $status    = $_GET['status'];
    $charge    = $_GET['charge'];
?>
