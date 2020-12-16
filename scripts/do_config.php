<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/config_file.php");
include("../classes/user.php");

session_start();

$error = false;

if($_POST['display']) {
    $_SESSION['edit_config'] = MODE_READONLY;
    $_SESSION['config_result'] = '';
    $path = dirname(realpath("do_config.php"));
    $config = new config_file(str_replace("/scripts", "/inc/config.php", $path));
    $config->read_file();
} // end if

if($_POST['change']) {
    $_SESSION['edit_config'] = '';
    $config = $_SESSION['config_file'];
    $path = dirname(realpath("do_config.php"));
    $config->set_path(str_replace("/scripts", "/inc/config.php", $path));
    $config->set_value("MAX_LOGIN_ATTEMPTS", $_POST["MAX_LOGIN_ATTEMPTS"]);
    $config->set_value("FIRST_EXTENSION", $_POST["FIRST_EXTENSION"]);
    $config->set_value("LAST_EXTENSION", $_POST["LAST_EXTENSION"]);
    $config->set_value("SYSTEM_ADMIN", $_POST["SYSTEM_ADMIN"]);
    $config->set_value("TWILIO_PHONE", $_POST["TWILIO_PHONE"]);
    $config->set_value("TWILIO_USER", $_POST["TWILIO_USER"]);
    $config->set_value("TWILIO_PASS", $_POST["TWILIO_PASS"]);
    $config->set_value("SMS_MIN", $_POST["SMS_MIN"]);
    $config->set_value("SMS_MAX", $_POST["SMS_MAX"]); 
    $config->set_value("PIN_MIN", $_POST["PIN_MIN"]);
    $config->set_value("PIN_MAX", $_POST["PIN_MAX"]);
    $config->set_value("AGE_MIN", $_POST["AGE_MIN"]);
    $config->set_value("AGE_MAX", $_POST["AGE_MAX"]);
    $config->set_value("db_host", $_POST["db_host"]);
    $config->set_value("db_user", $_POST["db_user"]);
    $config->set_value("db_pass", $_POST["db_pass"]);
    $config->set_value("db_name", $_POST["db_name"]);
    $config->set_value("db_type", $_POST["db_type"]);
} // end if

if(($_POST['update'] || $_POST['enter']) && $_SESSION['edit_config'] != MODE_READONLY) {
    $config = $_SESSION['config_file'];
    $path = dirname(realpath("do_config.php"));
    $config->set_path(str_replace("/scripts", "/inc/config.php", $path));
    $config->set_value("MAX_LOGIN_ATTEMPTS", $_POST["MAX_LOGIN_ATTEMPTS"]);
    $config->set_value("FIRST_EXTENSION", $_POST["FIRST_EXTENSION"]);
    $config->set_value("LAST_EXTENSION", $_POST["LAST_EXTENSION"]);
    $config->set_value("SYSTEM_ADMIN", $_POST["SYSTEM_ADMIN"]);
    $config->set_value("TWILIO_PHONE", $_POST["TWILIO_PHONE"]);
    $config->set_value("TWILIO_PASS", $_POST["TWILIO_PASS"]);
    $config->set_value("TWILIO_USER", $_POST["TWILIO_USER"]);
    $config->set_value("SMS_MIN", $_POST["SMS_MIN"]);
    $config->set_value("SMS_MAX", $_POST["SMS_MAX"]);
    $config->set_value("PIN_MIN", $_POST["PIN_MIN"]);
    $config->set_value("PIN_MAX", $_POST["PIN_MAX"]);
    $config->set_value("AGE_MIN", $_POST["AGE_MIN"]);
    $config->set_value("AGE_MAX", $_POST["AGE_MAX"]);
    $config->set_value("db_host", $_POST["db_host"]);
    $config->set_value("db_user", $_POST["db_user"]);
    $config->set_value("db_pass", $_POST["db_pass"]);
    $config->set_value("db_name", $_POST["db_name"]);
    $config->set_value("db_type", $_POST["db_type"]);
    
    if(!$error) {
        // validate the email address of the system administrator
        switch(validate_email($config->get_value("SYSTEM_ADMIN"))) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Enter Admin. Email";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Admin. Email is invalid";
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the email address of the twilio user
        switch(validate_email($config->get_value("TWILIO_USER"))) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Enter Twilio User Email";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Twilio User Email is invalid";
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the twilio phone number
        switch(validate_formatted_field($config->get_value("TWILIO_PHONE"), REG_PHONE_USA)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Enter Twilio Phone";
                $error = true;
                break;
            case BAD_FORMAT:
                $_SESSION['config_result'] = "Twilio Phone must be in the format (999) 999-9999";
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the database parameters
        switch(validate_database($config->get_value("db_host"),
                                 $config->get_value("db_name"),
                                 $config->get_value("db_type"),
                                 $config->get_value("db_user"),
                                 $config->get_value("db_pass"))) {
            case NO_MATCH:
                $_SESSION['config_result'] = "Check DB parameters: unable to connect";
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the number of login attempts
        switch(validate_number($config->get_value("MAX_LOGIN_ATTEMPTS"), 2, 99)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Max. Login Attempts";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Max. Login Attempts must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                $_SESSION['config_result'] = "Max. Login Attempts must be between 2 and 99";
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the minimum pin number
        switch(validate_number($config->get_value("PIN_MIN"), 10000, $config->get_value("PIN_MAX") - 1)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Min. Experimenter Pin";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Min. Experimenter Pin must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("PIN_MAX") <= 10000) {
                    $_SESSION['config_result'] = "Min. Experimenter Pin must be >= 10000 ".
                                                 "and < Max. Experimenter Pin";
                } else {
                    $_SESSION['config_result'] = "Min. Experimenter Pin must be >= 10000 ".
                                                 "and < ".$config->get_value("PIN_MAX");
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the maximum pin number
        switch(validate_number($config->get_value("PIN_MAX"), $config->get_value("PIN_MIN") + 1, 99999)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Max. Experimenter Pin";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Max. Experimenter Pin must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("PIN_MAX") > 99999) {
                    $_SESSION['config_result'] = "Max. Experimenter Pin must be > Min. Experimenter Pin ".
                                                 "and <= 99999";
                } else {
                    $_SESSION['config_result'] = "Max. Experimenter Pin must be > ".$config->get_value("PIN_MIN")." ".
                                                 "and <= 99999";
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the minimum experimenter age
        switch(validate_number($config->get_value("AGE_MIN"), 1, $config->get_value("AGE_MAX") - 1)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Min. Age";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Min. Age must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("AGE_MAX") <= 999) {
                    $_SESSION['config_result'] = "Min. Age must be >= 1 ".
                                                 "and < Max. Age";
                } else {
                    $_SESSION['config_result'] = "Min. Age must be >= 1 ".
                                                 "and < ".$config->get_value("AGE_MAX");
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the maximum experimenter age
        switch(validate_number($config->get_value("AGE_MAX"), $config->get_value("AGE_MIN") + 1, 999)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Max. Age";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Max. Age must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("AGE_MAX") > 999) {
                    $_SESSION['config_result'] = "Max. Age must be > Min. Age ".
                                                 "and <= 999";
                } else {
                    $_SESSION['config_result'] = "Max. Age must be > ".$config->get_value("AGE_MIN")." ".
                                                 "and <= 999";
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the first extension number
        switch(validate_number($config->get_value("FIRST_EXTENSION"), 1000, $config->get_value("LAST_EXTENSION") - 1)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Min. Experimenter Extension";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Min. Experimenter Extension must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("LAST_EXTENSION") <= 1000) {
                    $_SESSION['config_result'] = "Min. Experimenter Extension must be >= 1000 ".
                                                 "and < Max. Experimenter Extension";
                } else {
                    $_SESSION['config_result'] = "Min. Experimenter Extension must be >= 1000 ".
                                                 "and < ".$config->get_value("LAST_EXTENSION");
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the last extension
        switch(validate_number($config->get_value("LAST_EXTENSION"), $config->get_value("FIRST_EXTENSION") + 1, 9999)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Max. Experimenter Extension";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Max. Experimenter Extension must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("LAST_EXTENSION") > 9999) {
                    $_SESSION['config_result'] = "Max. Experimenter Extension must be > Min. Experimenter Extension ".
                                                 "and <= 9999";
                } else {
                    $_SESSION['config_result'] = "Max. Experimenter Extension must be > ".$config->get_value("FIRST_EXTENSION")." ".
                                                 "and <= 9999";
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the lower SMS delay range
        switch(validate_number($config->get_value("SMS_MIN"), 0, $config->get_value("SMS_MAX") - 1)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Min. SMS Delay";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Min. SMS Delay must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("SMS_MAX") < 0) {
                    $_SESSION['config_result'] = "Min. SMS Delay must be >= 0 ".
                                                 "and < Max. SMS Delay";
                } else {
                    $_SESSION['config_result'] = "Min. SMS Delay must be >= 0 ".
                                                 "and < ".$config->get_value("SMS_MAX");
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // validate the upper SMS delay range
        switch(validate_number($config->get_value("SMS_MAX"), $config->get_value("SMS_MIN") + 1, 30)) {
            case BAD_LENGTH:
                $_SESSION['config_result'] = "Please enter Max. SMS Delay";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['config_result'] = "Max. SMS Delay must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                if($config->get_value("SMS_MAX") > 30) {
                    $_SESSION['config_result'] = "Max. SMS Delay must be > Min. SMS Delay".
                                                 "and <= 30";
                } else {
                    $_SESSION['config_result'] = "Max. SMS Delay must be > ".$config->get_value("SMS_MIN")." ".
                                                 "and <= 30";
                } // end if
                $error = true;
                break;
            default:
                $_SESSION['config_result'] = '';
                break;
        } // end switch
    } // end if

    if($_POST['update'] && !$error) {
        if($config->write_file()) {
            $_SESSION['config_result'] = "Changes saved";
            $_SESSION['edit_config'] = MODE_READONLY;
        } else {
            $_SESSION['update_result'] = "I could not save your changes";
            $error = true;
        } // end if
    } // end if
} // end if

$_SESSION['config_file'] = $config;

// redirect back to the configuration page
header("Location: ../configure.php");
?>
