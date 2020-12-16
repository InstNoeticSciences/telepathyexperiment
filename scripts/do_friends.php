<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/user.php");

session_start();

$error = false;
$friends = array();
$copy_friends = array();
$edit = $_SESSION['edit_friends'];

$_SESSION['update_result'] = '';

if($_POST['display']) {
    $edit = MODE_READONLY;
    $friends = $_SESSION['user']->get_friends();
}

if($_POST['change']) {
    $edit = '';

    for($i = 0; $i < MAX_FRIENDS; $i++) {
        $friends[$i]->friend_name = $_POST["name_"."$i"];
        $friends[$i]->phone = reformat_phone_number($_POST["phone_"."$i"]);
    } // end for
} // end if

if($_POST['update'] || $_POST['enter']) {
    // copy of friends list for duplicate checking
    for($i = 0; $i < MAX_FRIENDS; $i++) {
        $copy_friends[$i]->name = $_POST["name_$i"];
        $copy_friends[$i]->phone = reformat_phone_number($_POST["phone_$i"]);
    } // end for

    for($i = 0; $i < MAX_FRIENDS; $i++) {
        $friends[$i]->friend_name = $_POST["name_"."$i"];
        $friends[$i]->phone = reformat_phone_number($_POST["phone_"."$i"]);

        // validate the name
        switch(validate_name($friends[$i]->friend_name, false)) {
            case BAD_LENGTH:
                $friends[$i]->message = "Enter a name";
                $error = true;
                break;
            case BAD_CHARS:
                $friends[$i]->message = "Name can contain only letters and no space";
                $error = true;
                break;
            default:
                $friends[$i]->message = '';
                break;
        } // end switch

        if($error) {
            continue;
        } // end if

        // validate the phone number format
		
        /*switch(validate_formatted_field($friends[$i]->phone, REG_PHONE_USA)) {
        //switch(validate_phone_number($friends[$i]->phone)) {
            case BAD_LENGTH:
                $friends[$i]->message = "Enter a phone number";
                $error = true;
                break;
            case BAD_FORMAT:
                $friends[$i]->message = "Use phone format 999-999-9999 or 999 999 9999";
                $error = true;
                break;
            default:
                $friends[$i]->message = '';
                break;
        } // end switch */

        if($error) {
            continue;
        } // end if

        // ensure that each friend is unique
        if(!unique_friend($friends[$i]->friend_name,
                          $friends[$i]->phone,
                          $copy_friends)) {
            $friends[$i]->message = "Name and number must be unique";
            $error = true;
        } else {
            $friends[$i]->message = '';
        } // end if
    } // end if

     if($_POST['update'] && !$error) {
        $result = null;

        if(!$_SESSION['user']->has_friends()) {
            $_SESSION['user']->set_friends($friends);
            $result = $_SESSION['user']->insert_friends();
        } else {
            $_SESSION['user']->set_friends($friends);
            $result = $_SESSION['user']->update_friends();
        } // end if

        if($result == null) {
            $_SESSION['update_result'] = "I could not save your changes";
            $error = true;
        } else {
            $_SESSION['update_result'] = "Changes saved";
            $edit = MODE_READONLY;
        } // end if
     } // end if
} // end if

$_SESSION['friends'] = $friends;
$_SESSION['edit_friends'] = $edit;

// redirect back to the friends page
header("Location: ../index.php");
?>
