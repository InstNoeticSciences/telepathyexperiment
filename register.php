<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/password.php");
include("classes/user.php");

session_start();

require_once("lib/recaptchalib.php");

// empty the message variables from other pages
$_SESSION['password_result'] = '';
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
        <link rel="stylesheet" type="text/css" href="js/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
        <script type="text/javascript" src="js/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
        <script type="text/javascript" src="js/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
        <script type="text/javascript">window.dhx_globalImgPath="js/dhtmlxCalendar/codebase/imgs/";</script>
        <script type="text/javascript">
            var RecaptchaOptions = {
               theme: 'white',
               tabindex: 8
            };
        </script>
        <title>Experimenter Registration</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="register" method="post" action="scripts/do_register.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], REGISTER);
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
                    <h3>Enter your details:</h3>
                    <table>
                        <tr>
                            <td>
                            <?php
                            field_label("first_name", "First Name (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("first_name", 
                                             false,
                                             $_SESSION['first_name'],
                                             12,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        </tr>
 <!--                        <tr>
                            <td>
                            <?php
                            field_label("last_name", "Last Name (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("last_name", 
                                             false,
                                             $_SESSION['last_name'],
                                             12,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        </tr>
                       <tr>
                            <td>
                            <?php
                            field_label("age", "Age (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("age", 
                                             false,
                                             $_SESSION['age'],
                                             3,
                                             3,
                                             "Between ".AGE_MIN." and ".AGE_MAX." years");
                            ?>
                            </td>
                        </tr> 
                        <tr>
                            <td>
                            <?php
                            field_label("gender", "Gender");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_select("gender", 
                                               false,
                                               array("Male", "Female"),
                                               1,
                                               $_SESSION['gender']);
                            ?>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                            <?php
                            field_label("phone", "Phone (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("phone", 
                                             false,
                                             $_SESSION['phone'],
                                             12,
                                             20,
                                             "Landline or cell phone");
                            ?>
                            </td>
                        </tr>
                         <tr>
                            <td>
                            <?php
                            field_label("email", "Email Address");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("email", 
                                             false,
                                             $_SESSION['email'],
                                             20,
                                             50);
                            ?>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td>
                            <?php
                            field_label("username", "Username (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("username",
                                             false,
                                             $_SESSION['username'],
                                             12,
                                             20,
                                             "Between ".UNAME_MIN_LENGTH." ".
                                             "and ".UNAME_MAX_LENGTH." ".
                                             "characters");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("password", "Password (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_password("password",
                                                 false,
                                                 "",
                                                 20,
                                                 "Must be ".PWD_LENGTH." ".
                                                 "characters long");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("rep_password", "Repeat Password (*)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_password("rep_password", false, "", 20);
                            ?>
                            </td>
                        </tr>
 <!--                      <tr>
                            <td>
                            <?php
                            field_label("group_name", "Group Name (if any)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("group_name",
                                             false,
                                             $_SESSION['group_name'],
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>    
                            </td> -->
                            
                        </tr>

                    </table>
                    <br><br>
                    <table>
					    <tr><td><label class="label" for="emailinfo">Email is not required but will be needed in case of lost password</label></td></tr>
                        <input type="hidden" name="Age" value="0">
                        <input type="hidden" name="group_name" value="firsttest">
                        <input type="hidden" name="last_name" value="">
                        <input type="hidden" name="gender" value="X">
                    </table>
                        
<br>
<h2>PRIVACY</h2>

Your phone number and the phone number of your friends are safe with us. The server running the experiment will only call you for running the experiment. We will never call you directly. We will not share your information with any third party. Once you have completed the experiment, you may delete your account. Your phone and friend phone information will then be erased fron our server.

<h2>CONSENT TO PARTICIPATE IN RESEARCH</h2>

<b>Institution:</b> Institute of Noetic Sciences, 625 2nd Street, Petaluma, CA 94952.
<br><br>
<b>Principal investigators:</b> Arnaud Delorme, PhD and Dean Radin, PhD.
<br><br>
<b>Study title:</b> Telephone telepathy experiment.
<br><br>
<b>Who may participate:</b> Adults 18 or older or minors with their parents' or guardians' permission. Individuals suffering from psychiatric disorders should consult with their doctor before participating.
<br><br>
<b>Purpose of the experiment:</b> This study investigates telepathy/intuition.
<br><br>
<b>Procedure:</b>
A web user watches a video and/or reads a description of the experiment, and then agrees to participate by clicking on a registration button. As part of this agreement he confirms if he/she uses a cell phone for this experiment, he/she will be responsible for any SMS text message charges that this study generates as notification messages.<br><br>
After registering, the user enters the first name and telephone number of two friends on the website. The user is then instructed to call a toll-free 877 phone number begin the experiment. After this is completed, our web server calls the two friends and asks if they agree to participate in the study. Each friend responds by pressing one of three options on their phone keypad: (1) agree to participate, (2) do not agree to participate now but might agree later (3) choose not to participate at all. If one or both friends choose to not participate, then the experiment is canceled, the "not participate" phone numbers will not be called again, and the other friend and the user will be informed by a text message (if using a cell phone) that the experiment has been canceled. 
<br><br>
If everyone has agreed to participate, then our web server starts the experiment. Calls will occur on average about every two to four minutes. Our web server calls all three participants at the same time and it asks each person who they think they are about to speak to. Each participant now has three choices: the first two corresponds to the other two persons involved in the experiment. The third one is used to indicate that they feel that they are not going to be connected with someone. They each make their choice by pressing a number (number one, number two or number three) on their phone. The number corresponding to each person will be communicated by the web server and may vary with each call. Once the choice is made, our web server randomly connects two of the three people and informs the third person that they are not going to be connected in that trial. The calling period for the next trial will begin after the two connected friends hang up.
<br><br>
All responses are stored on our server for subsequent data analysis. If one of the participants does not pick up the phone, the trial is considered incomplete. After six completed trials or 12 total trials (completed or not completed) have been recorded, the overall score of the group is sent by SMS text message to all participants using a cell phone. You may also see your results online by clicking on the results tab; only the person who initially registered the experiment and added two friends will be able to access the results online, and must be logged in to do so. The score is computed by averaging the accuracy of all participants in completed trials (not including control trials). Once the six-trial experiment is complete, users may then create another experiment with the same participants if they wish to do so. Users may also remove one or both of their friends' numbers and add other friends who may wish to participate.
<br><br><b>Data collected in this study:</b> The data collected in this study are for use in an Institute of Noetic Sciences research project studying one way that telepathy is said to occur in everyday life. Summary data may be used for presentations and discussions, or published in formal reports, but participants' names, emails and phone number information will not appear in any presentation or publication without their prior permission.
<br><br>
<b>Risks and benefits:</b> There are no known risks involved in this study. The benefit of this study is that it will contribute to our understanding of how telepathy manifests in real-life situations. 
<br><br>
<b>Freedom to withdraw:</b> Participation in this study is entirely voluntary. 
<br><br>
<b>Implied consent:</b> As a web- and telephone-based experiment, we will assume that anyone who affirms by phone that they wish to participate is giving their informed consent.                                                       
                </div>
                <div class="right_box">
                    <h3>Enter the code below:</h3>
                    <center><?php echo recaptcha_get_html(captcha_pub); ?><br>
                    <?php input_field_submit("register", "Register and consent to participate", "button"); ?></center>
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_message("register_result", $_SESSION['register_result'], 1000);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>
