<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/user.php");

session_start();

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
$_SESSION['contact_result'] = '';
$_SESSION['profile_result'] = '';
$_SESSION['config_result'] = '';
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
        <script type="text/javascript" src="js/browser.js"></script>
        <title>About Telephone Telepathy</title>
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
                    main_menu($_SESSION['auth'], ABOUT);
                    ?>
                </div>
                <div class="header_right">
                    <?php
                    admin_menu($_SESSION['auth'], isset($_SESSION['user']) ?
                                                  $_SESSION['user']->is_admin() :
                                                  false);
                    ?>
                </div>
                <iframe width="820" height="461" src="//www.youtube.com/embed/IHOSx9eN9Og?rel=0" frameborder="0" allowfullscreen></iframe>
            <!--div class="left_box">
                <object width="425" height="344">
                    <param name="movie"
                           value="http://www.youtube.com/v/UdOi3s-tBzk&hl=en_GB&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param>
                    <param name="allowFullScreen"
                           value="true"></param>
                    <param name="allowscriptaccess"
                           value="always"></param>
                    <embed src="http://www.youtube.com/v/UdOi3s-tBzk&hl=en_GB&fs=1&rel=0&color1=0x006699&color2=0x54abd6"
                           type="application/x-shockwave-flash"
                           allowscriptaccess="always"
                           allowfullscreen="true"
                           width="425" 
                           height="344"></embed>
                </object>
            </div>
            <div class="right_box"-->
            <br>
            <br>
                <h3>About the Telephone Telepathy Experiment</h3>
                <p class="info">
                    Many people claim to be able to know who's calling them
                    before they answer the call. Is this just coincidence or
                    is some other factor involved? The Telephone Telepathy
                    experiment attempts to answer this question empirically.
                    <br><br>
                    To participate in the experiment you will need a U.S. phone
                    (cell phone or landline) as well as <?php echo MAX_FRIENDS ?>
                    people with whom you share a close relationship (eg.
                    friends, family). These people will require a U.S. phone. Only those users which have a cell phone will be able to receive accuracy scores by text message at the end of the experiment (other users may call their friends so they communicate the results to them).<br><br>
                    Once you have <a href="register.php">registered</a> you can
                    <a href="login.php">login</a> and add some
                    <a href="friends.php">friends</a>. After logging in, further
                    instructions for conducting the test will be
                    <a href="index.php">available</a>. The full description of the experiment is available on the registration page in the consent form.
                    <br><br>
                    You may also watch the video to see how it works.
                    <br><br>
                    Please consider supporting our research by <a href="http://trans.noetic.org/site/Donation2?df_id=2120&2120.donation=form1">donating to the Institute of Noetic Sciences</a>.
                </p>
            <div class="footer"></div>
        </div>
    </body>
</html>