<?php
include("inc/config.php");
include("lib/error_handler.php");
include("lib/util.php");
include("classes/simple_grid.php");
include("classes/database.php");
include("classes/user.php");

session_start();

authenticate($_SESSION['auth'], "index.php");
admin_check($_SESSION['user']->is_admin(), "index.php");

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
$_SESSION['contact_result'] = '';
$_SESSION['login_result'] = '';
$_SESSION['reset_result'] = '';

// get the experiment id and store it
if(isset($_GET['experiment_id'])) {
    $experiment_id = $_GET['experiment_id'];
    $_SESSION['experiment_id_dl'] = $experiment_id;
} else {
    $_SESSION['experiment_id_dl'] = '';
} // end if
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/forms.css" />
        <link rel="stylesheet" type="text/css" href="css/tags.css" />
        <link rel="stylesheet" type="text/css" href="css/divs.css" />
        <link rel="stylesheet" type="text/css" href="css/grid.css" />
        <title>Results Detail</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/grid.js"></script>
    </head>
    <body onload="init('xsl/results_detail.xsl',
                       'grid.php',
                       'status_div',
                       'grid_div',
                       'results',
                       'experiment_id=<?php echo $experiment_id ?>');">
        <form name="results_detail" method="post" action="scripts/do_results.php">
            <div class="wrapper">
                <div class="left_box">
                    <div id="grid_div" />
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_submit("download_detail", "Download These Trials", "button");
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>