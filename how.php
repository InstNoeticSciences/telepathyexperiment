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
        <script type="text/javascript" src="js/util.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/fadeslideshow.js">

        /***********************************************
        * Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
        * This notice MUST stay intact for legal use
        * Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
        ***********************************************/

        </script>
        <script type="text/javascript">
        var mygallery=new fadeSlideShow({
                wrapperid: "fadeshow2", //ID of blank DIV on page to house Slideshow
                dimensions: [425, 344], //width/height of gallery in pixels. Should reflect dimensions of largest image
                imagearray: [
                        ["http://telepathyexperiment.com/graphics/how_1.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_2.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_3.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_4.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_5.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_6.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_7.jpg", "", "", ""],
                        ["http://telepathyexperiment.com/graphics/how_8.jpg", "", "", ""] 
                ],
                displaymode: {type:'auto', pause:1500, cycles:0, wraparound:false},
                persist: false, //remember last viewed slide and recall within same session?
                fadeduration: 250, //transition duration (milliseconds)
                descreveal: "always",
                togglerid: "fadeshow2toggler"
        })
        </script>
        <title>How it Works</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
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
            <div class="left_box">
                <div id="fadeshow2"></div>
            </div>
            <div class="right_box">
                <h3>How the Experiment Works</h3>
                <p class="info">
                    <a href="login.php">Log in</a> to the website to get the experiment system number
                            and your 5-digit access pin then call the system and follow the prompts to
                            start an experiment. <?php echo MAX_TRIALS ?> trials will be run per experiment.
                            For each trial one of your friends will be randomly selected to call the
                            <b>experiment system</b>. Callers are notified via an SMS message containing
                            connection instructions. The experiment system will call you and prompt you to
                            guess who is calling before connecting the call. There is no need to remove
                            caller ID's or distinctive ring tones from your phone: all connections are made
                            through the experiment system. Caller selection is randomised for each trial:
                            it is possible that the same friend will be selected to call you multiple
                            times in a row. At the end of the experiment you will be given a score.<br><br>
                            This experiment will <b>not</b> work with <b>landlines</b>.
                </p>
                <a class="switch" href="about.php">Learn about the experiment</a>
            </div>
            <div class="footer"></div>
        </div>
    </body>
</html>