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
        <title>Results Overview</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/grid.js"></script>
    </head>
    <body onLoad="init('xsl/results_overview.xsl',
                       'grid.php',
                       'status_div',
                       'grid_div',
                       'v_experiments',
                       '');">
        <form name="results_overview" method="post" action="scripts/do_results.php">
            <div class="wrapper">

                <div class="footer">
                    <div class="statistics">
                        <p class="tiny">
                            <b>Statistics</b><br><br>
                            Number of Completed Trials = <?php echo count_completed_trials() ?> <br />
                            Number of Hits = <?php echo total_hit_count() ?> <br />
                            Hit Rate = <?php echo overall_hit_rate() ?> %
                        </p>
                    </div>
                    <br />
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_submit("download_overview", "Download Experiment Headers", "button");
                    input_field_submit("download_all", "Download Trials for All Experiments", "button");
                    ?>
                </div>
             </div>
                <div class="left_box">
                    <div id="grid_div" />
            </div>
        </form>
    </body>
</html>