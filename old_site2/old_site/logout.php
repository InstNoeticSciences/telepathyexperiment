<?
session_start();
unset($_SESSION['user']);
$_SESSION['logged_out'] = 1;
header("Location: home");
?>