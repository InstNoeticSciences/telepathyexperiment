<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/user.php");

session_start();

// delete the graph file for this user
delete_file(getcwd(), "../graphics/",
                      "graph_".$_SESSION['user']->get_username().".png");

// clear session data
unset($_SESSION['auth']);
unset($_SESSION['user']);
unset($_SESSION['edit_mode']);

$_SESSION = array();
session_destroy();

header("Location: ../index.php");
?>
