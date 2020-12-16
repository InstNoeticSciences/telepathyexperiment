<?php
include("inc/config.php");
include("lib/util.php");
include("lib/phpgraphlib.php");
include("classes/database.php");
include("classes/user.php");

session_start();

authenticate($_SESSION['auth'], "index.php");

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
$_SESSION['contact_result'] = '';
$_SESSION['profile_result'] = '';
$_SESSION['config_result'] = '';
$_SESSION['login_result'] = '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/forms.css" />
        <link rel="stylesheet" type="text/css" href="css/tags.css" />
        <link rel="stylesheet" type="text/css" href="css/divs.css" />
        <title>Experimental Results</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="banner">
                <?php
                banner();
                ?>
            </div>
            <div class="header_left">
                <?php
                main_menu($_SESSION['auth'], RESULTS);
                ?>
            </div>
            <div class="header_right">
                    <?php
                    admin_menu($_SESSION['auth'], isset($_SESSION['user']) ?
                                                  $_SESSION['user']->is_admin() :
                                                  false);
                    ?>
            </div>
            <div class="left_box">
                <br><br>
                <?php echo generate_graph($_SESSION['user']) ?>
            </div>
            <div class="right_box">
                <h3>Experimental Results</h3>
                <?php describe_results($_SESSION['user']) ?>
            </div>
            <div class="footer"></div>
        </div>
    </body>
</html>