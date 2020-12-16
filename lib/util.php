<?php
// length constants
define("PWD_LENGTH", 8);
define("UNAME_MIN_LENGTH", 4);
define("UNAME_MAX_LENGTH", 20);
define("RESET_STRING_LENGTH", 20);

// default and boundary values
define('ROWS_PER_VIEW', '1000'); //was 8
define('EMPTY_DATE', '0000-00-00');
define('EMPTY_TIME', '00:00:00');

// return codes
define("OK", 0);
define("UNIQUE", -1);
define("BAD_LENGTH", 1);
define("BAD_MATCH", 2);
define("BAD_CHARS", 3);
define("NOT_UNIQUE", 4);
define("NO_MATCH", 5);
define("BAD_VALUE", 6);
define("BAD_FORMAT", 7);

// page identifiers
define("ABOUT", 1);
define("CONTACT", 2);
define("INDEX", 3);
define("LOGIN", 4);
define("FRIENDS", 5);
define("PASSWORD", 6);
define("RESET", 7);
define("REGISTER", 8);
define("RESULTS", 9);
define("CONFIGURE", 10);
define("ALL_RESULTS", 11);

// browsers
define("FIREFOX", 1);
define("SAFARI", 2);
define("IE", 3);
define("CHROME", 4);

// database operations
define('INSERT', 1);
define('UPDATE', 2);
define('DELETE', 3);

// experiment and trial statuses
define('NOT_STARTED', 1);
define('IN_PROGRESS', 2);
define('COMPLETE', 3);
define('ABORTED', 4);
define('HIT', 'Y');
define('MISS', 'N');

// graph configuration
$graph_cfg = array (
    'title'=>'Experimental Performance against Chance',
    'background-color'=>'FFFFFF',
    'graph-background-color'=>'FFFFFF',
    'font-color'=>'000000',
    'border-color'=>'009900',
    'column-color'=>'00FF00',
    'column-shadow-color'=>'009900',
    'column-font-color-q1'=>'000000',
    'column-font-color-q2'=>'000000',
    'random-column-color'=>1
);

// captcha keys
define("captcha_pub", "6Ley8vISAAAAAKg4pFk6B_8KLmRMi9moW4m9Accg");
define("captcha_prv", "6Ley8vISAAAAANBWRgrr1GtlI2oql3NfjAEWjcfX");

// long constants
define("REG_PHONE_USA", "^([0-9]( |-)?)?(\(?[0-9]{3}\)?|[0-9]{3})( |-)?([0-9]{3}( |-)?[0-9]{4}|[a-zA-Z0-9]{7})$");
define("REG_MAIL_1", "^[^@]{1,64}@[^@]{1,255}$");
define("REG_MAIL_2", "^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$");
define("SOURCE_CHARS", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");

function reformat_phone_number($str) {
	$str = str_replace(array("-","."," ","(",")"), "", $str);
	$str = preg_replace("/[^0-9,.]/", "0", $str);
	if (strlen($str) == 10) $str = "1".$str;
	if (strlen($str) == 11) $str = "+".$str;
	$str[0] = "+";
	$str[1] = "1";
	return $str;
}

/**
 * perform password validation
 * @param   string  $pwd        the entered password
 * @param   string  $rep_pwd    password confirmation
 * @return  int     OK|BAD_LENGTH|BAD_MATCH
 */
function validate_password($pwd, $rep_pwd) {
    // check length
    if(strlen($pwd) < PWD_LENGTH || preg_match("/\s/", $pwd)) {
        return BAD_LENGTH;
    } // end if

    // check for matching entries
    if(strcmp($pwd, $rep_pwd) != 0) {
        return BAD_MATCH;
    } // end if

    // everything OK
    return OK;
} // end validate_password()

/**
 * perform an existence check on a database
 * @param   string  $host       the database host
 * @param   string  $name       the database name
 * @param   string  $type       the database type
 * @param   string  $user       the database user
 * @param   string  $password   the password for the database user
 * @return  int     OK|NO_MATCH
 */
function validate_database($host, $name, $type, $user, $password) {
    switch($type) {
        case "MySQL":
            if(!(function_exists(mysql_connect) && is_callable(mysql_connect))) {
                return NO_MATCH;
            } // end if

            // attempt to open a connection
            $link = @mysql_connect($host, $user, $password);

            if(!$link) {
                return NO_MATCH;
            } // end if

            // attempt to select the desired database
            if(!mysql_select_db($name)) {
                mysql_close($link);
                return NO_MATCH;
            } // end if
            
            mysql_close($link);
            break;
        case "PostgreSQL":
            if(!(function_exists(pg_connect) && is_callable(pg_connect))) {
                return NO_MATCH;
            } // end if
            
            // attempt to open a connection
            $conn = "host=$host dbname=$name user=$user password=$password";
            $link = pg_connect($conn);

            if(!$link) {
                return NO_MATCH;
            } // end if

            pg_disconnect($link);
            break;
        default:
            return NO_MATCH;
    } // end switch

    // everything OK
    return OK;
} // end validate_database()

/**
 * validate a number
 * @param   string  $value      the number to be validated
 * @param   int     $min_value  minimum value for the number
 * @param   int     $max_value  maximum value for the number
 * @return  int     OK|BAD_LENGTH|BAD_CHARS|BAD_VALUE
 */
function validate_number($value, $min_value, $max_value) {
    if(strlen($value) <= 0) {
        return BAD_LENGTH;
    } // end if

    // check characters
    if(preg_match("/^[0-9]+\z/", $value) == 0) {
        return BAD_CHARS;
    } // end if

    // check value
    if($value < $min_value || $value > $max_value) {
        return BAD_VALUE;
    } // end if

    // everything OK
    return OK;
} // end validate_number()

/**
 * perform username validation
 * @param   string  $uname  the entered username
 * @return  int     OK|BAD_LENGTH|NOT_UNIQUE|BAD_CHARS
 */
function validate_username($uname) {
    // check length
    if(strlen($uname) > UNAME_MAX_LENGTH || strlen($uname) < UNAME_MIN_LENGTH) {
        return BAD_LENGTH;
    } // end if

    // check characters
    if(preg_match("/^[A-Za-z0-9]+\z/", $uname) == 0) {
        return BAD_CHARS;
    } // end if

    // check uniqueness
    $db = new database();
    $db->dblink();
    
    $result = $db->get_recs("users", "username", "username='{$uname}'");
    $recs = $db->fetch_objects($result);

    if(is_array($recs) || strcmp($recs->username, $uname) == 0) {
        return NOT_UNIQUE;
    } // end if

    // everything OK
    return OK;
} // end validate_username()

/**
 * perform cell phone validation
 * @param   string  $phone      the cell phone number
 * @param   string  $username   the username (default: null)
 * @return  int     OK|BAD_FORMAT|NOT_UNIQUE
 */
 
function validate_phone_number($phone) {
	if ($phone[0] == '+' && isnumeric($phone) && len($phone) == 11) return OK;
	else return BAD_FORMAT;
}

function validate_phone($phone, $username = null) {
    // first check the format
    if(validate_formatted_field($phone, REG_PHONE_USA) != OK) {
        return BAD_FORMAT;
    } // END IF

    // check uniqueness
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users", "username, phone", "phone='{$phone}'");
    $recs = $db->fetch_objects($result);

    if(is_array($recs)) {
        if($username == null) {
            return NOT_UNIQUE;
        }

        $num_recs = count($recs);
        
        for($i = 0; $i < $num_recs; $i++) {
            if(strcmp($recs[$i]->username, $username) != 0) {
                return NOT_UNIQUE;
            } // end if
        } // end for
    } // end if

    // everything OK
    return OK;
} // end validate_phone()

/**
 * validate a formatted field
 * @param   string  $field  the field to be validated
 * @param   string  $format a regular expression defining the format
 * @return  int     OK|BAD_LENGTH|BAD_FORMAT
 */
function validate_formatted_field($field, $format) {
    // check length
    if(!strlen($field) > 0) {
        return BAD_LENGTH;
    } // end if

    // check format
    if(!ereg($format, $field)) {
        return BAD_FORMAT;
    } // end if

    // everything OK
    return OK;
} // end validate_formatted_field()

/**
 * perform email address validation
 * @param   string  $email      the email address
 * @param   string  $username   the username (default: null)
 * @return  int     OK|BAD_CHARS|BAD_LENGTH|NOT_UNIQUE
 */
function validate_email($email, $username = null) {
    // check length
    if(!strlen($email) > 0) {
        return BAD_LENGTH;
    } // end if

    // check characters
    if (!ereg(REG_MAIL_1, $email)) {
        return BAD_CHARS;
    } // end if

    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);

    for ($i = 0; $i < sizeof($local_array); $i++) {
        if(!ereg(REG_MAIL_2, $local_array[$i])) {
            return BAD_CHARS;
        } // end if
    } // end for

    // check uniqueness
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users", "username, email", "email='{$email}'");
    $recs = $db->fetch_objects($result);

    if(is_array($recs)) {
        if($username == null) {
            return NOT_UNIQUE;
        }
        
        $num_recs = count($recs);
        
        for($i = 0; $i < $num_recs; $i++) {
            if(strcmp($recs[$i]->username, $username) != 0) {
                return NOT_UNIQUE;
            } // end if
        } // end for
    } // end if

    // everything OK
    return OK;
} // end validate_email()

/**
 * perform name field validation
 * @param   string  $name           the name (first or last)
 * @param   boolean $allow_spaces   allow spaces in the name (default: null)
 * @return  int     OK|BAD_CHARS|BAD_LENGTH
 */
function validate_name($name, $allow_spaces = null) {
    // check length
    if(!strlen($name) > 0) {
        return BAD_LENGTH;
    } // end if

    // check characters
    if((!$allow_spaces || $allow_spaces == null) &&
        preg_match("/^[A-Za-z]+\z/", $name) == 0) {
        return BAD_CHARS;
    } // end if

    if($allow_spaces && preg_match("/^[A-Z a-z]+\z/", $name) == 0) {
        return BAD_CHARS;
    } // end if

    return OK;
} // end validate_name()

/**
 * multiple character replacement in a string
 * @param   string  $source the source string
 * @param   array   $pairs  character replacement pairs
 * @return  string  the source string with replacements implemented
 */
function str_multi_replace($source, $pairs) {
    if(!is_array($pairs)) {
        return null;
    } // end if

    $target = $source;

    foreach($pairs as $src_char => $tar_char) {
        $target = str_replace($src_char, $tar_char, $target);
    } // end foreach

    return $target;
} // end str_multi_replace()

/**
 * check for a non unique friend in the friends list
 * @param   string  $name       a friend name
 * @param   string  $phone      a phone number
 * @param   array   $friends    the friends list (name,phone)
 * @return  boolean true|false
 */
function unique_friend($name, $phone, $friends) {
    $count = 0;
    $num_friends = count($friends);
    
    // a friend is not unique if it appears more than once in the list
    for($i = 0; $i < $num_friends; $i++) {
        // ignore punctuation in the phone numbers
        $pairs = array("-" => "",
                       "(" => "",
                       ")" => "",
                       " " => "");

        $phone_1 = str_multi_replace($friends[$i]->phone, $pairs);
        $phone_2 = str_multi_replace($phone, $pairs);
        
        if(strcmp($friends[$i]->name, $name) == 0 ||
           strcmp($phone_1, $phone_2) == 0) {
            $count++;
        } // end if
    } // end for

    if($count > 1) {
        return false;
    } // end if

    return true;
} // end unique_friend()

/**
 * log the user in to the system
 * @param   string  $username   the entered username
 * @param   string  $password   the entered password
 * @return  boolean true|false
 */
function validate_credentials($username, $password) {
    $pwd = new password($password);
    $pwd->encrypt($username);

    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users", "password", "username='{$username}'");
    $recs = $db->fetch_objects($result);

    if(is_array($recs)) {
        $db_password = $recs[0]->password;
    } else {
        $db_password = $recs->password;
    } // end if

    if($pwd->compare($db_password)) {
        return true;
    } // end if

    return false;
} // end validate_credentials()

/**
 * echo a retry message to the screen
 * @param   string  $message    message to be displayed
 */
function retry_message($message) {
    die("$message. Click the Back button to try again.");
} // end retry_message()

/**
 * match an email address to a user
 * @param   string  $username   the entered username
 * @param   string  $email      the entered email address
 * @return  int     OK|NO_MATCH
 */
function match_email_user($username, $email) {
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users",
                            "email",
                            "username='{$username}' AND
                             email='{$email}'");

    $recs = $db->fetch_objects($result);
    
    if(is_array($recs)) {
        return OK;
    } // end if

    return NO_MATCH;
} // end match_email_user()

/**
 * match a verification code to a username
 * @param   string  $username   the entered username
 * @param   string  $code       the entered code
 * @return  int     OK|NO_MATCH
 */
function match_user_code($username, $code) {
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users",
                            "email",
                            "username='{$username}' AND
                             code='{$code}'");

    $recs = $db->fetch_objects($result);

    if(is_array($recs)) {
        return OK;
    } // end if

    return NO_MATCH;
} // end match_user_code()

/**
 * generate a random string of the specified length
 * @param   int     $length the length of the string to be generated
 * @param   string  $source the source characters for the string
 * @return  string  a random string
 */
function random_string($length, $source) {
    $string = null;

    for($i = 0; $i < $length; $i++) {
        $string .= $source[mt_rand(0, strlen($source))];
    } // end for

    return $string;
} // end random_string()

/**
 * check that a session is authenticated and redirect if not
 * @param   string  $auth       the authentication variable
 * @param   string  $location   redirect here on failure
 */
function authenticate($auth, $location) {
    if(!isset($auth)) {
        header("Location: ".$location);
    } // end if
} // end authenticate()

/**
 * check for administrator-only functionality
 * @param   boolean $is_admin   true if the user is an administrator
 * @param   string  $location   redirect here on failure
 */
function admin_check($is_admin, $location) {
    if(!$is_admin) {
        header("Location: ".$location);
    } // end if
} // end admin_check()

/**
 * generate the administration menu for the site
 * @param   string  $auth   the authentication variable
 * @param   boolean $admin  true if the logged user is an administrator
 */
function admin_menu($auth, $admin) {
    if(isset($auth) && $admin) {
        echo "<ul id='list-nav-admin'>";
        echo "<li><a href='configure.php' title='Configure Experiment Options'>
                    <img src='graphics/configure.png' width='18' height='18' alt='Configure' />
              </a></li>";
        echo "<li><a href='results_overview.php' target='_blank' title='Results Overview and Download'>
                    <img src='graphics/results_nav.jpg' width='18' height='18' alt='Results' />
              </a></li>";
        echo "<li><a href='http://www.twilio.com/user/account' title='Visit Twilio' target='_blank'>
                    <img src='graphics/icon_twilio.png' width='18' 'height='18' alt='Twilio' />
                  </a></li>";
        echo "</ul>";
    }
} // end admin_menu()

/**
 * generate the main menu for the site
 * @param   string  $auth   the authentication variable
 * @param   string  $page   the calling page
 */
function main_menu($auth, $page) {
    echo "<ul id='list-nav-user'>";

    switch($page) {
        case ABOUT:
            if(isset($auth)) {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='friends.php'>Friends</a></li>
                      <li><a href='results.php'>Results</a></li>
                      <li><a href='logout.php'>Logout</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } else {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='register.php'>Register</a></li>
                      <li><a href='login.php'>Login</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } // end if
            break;
        case CONTACT:
            if(isset($auth)) {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='about.php'>About</a></li>
                      <li><a href='friends.php'>Friends</a></li>
                      <li><a href='results.php'>Results</a></li>
                      <li><a href='logout.php'>Logout</a></li>";
            } else {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='about.php'>About</a></li>
                      <li><a href='register.php'>Register</a></li>
                      <li><a href='login.php'>Login</a></li>";
            } // end if
            break;
        case FRIENDS:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='results.php'>Results</a></li>
                  <li><a href='logout.php'>Logout</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case INDEX:
            if(isset($auth)) {
                echo "<li><a href='about.php'>About</a></li>
                      <li><a href='friends.php'>Friends</a></li>
                      <li><a href='results.php'>Results</a></li>
                      <li><a href='logout.php'>Logout</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } else {
                echo "<li><a href='about.php'>About</a></li>
                      <li><a href='register.php'>Register</a></li>
                      <li><a href='login.php'>Login</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } // end if
            break;
        case LOGIN:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='register.php'>Register</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case PASSWORD:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='login.php'>Login</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case REGISTER:
            if(isset($auth)) {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='about.php'>About</a></li>
                      <li><a href='friends.php'>Friends</a></li>
                      <li><a href='results.php'>Results</a></li>
                      <li><a href='logout.php'>Logout</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } else {
                echo "<li><a href='index.php'>Home</a></li>
                      <li><a href='about.php'>About</a></li>
                      <li><a href='login.php'>Login</a></li>
                      <li><a href='contact.php'>Contact</a></li>";
            } // end if
            break;
        case RESET:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='login.php'>Login</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case RESULTS:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='friends.php'>Friends</a></li>
                  <li><a href='logout.php'>Logout</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case CONFIGURE:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='friends.php'>Friends</a></li>
                  <li><a href='results.php'>Results</a></li>
                  <li><a href='logout.php'>Logout</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        case ALL_RESULTS:
            echo "<li><a href='index.php'>Home</a></li>
                  <li><a href='about.php'>About</a></li>
                  <li><a href='friends.php'>Friends</a></li>
                  <li><a href='results.php'>Results</a></li>
                  <li><a href='logout.php'>Logout</a></li>
                  <li><a href='contact.php'>Contact</a></li>";
            break;
        default:
            break;
    } // end switch

    echo "</ul>";
} // end main_menu()

/**
 * generate html for a text input field
 * @param   string  $id         used for name and id
 * @param   boolean $readonly   indicates readonly field
 * @param   string  $value      value for the field
 * @param   int     $size       size of the field
 * @param   int     $max        maxlength of the field (default: 0)
 * @param   string  $tip        help text for field
 */
function input_field_text($id,
                          $readonly,
                          $value,
                          $size,
                          $max = 0,
                          $tip = null) {
    echo "<input type=\"text\"
                 id=\"$id\"
                 name=\"$id\"
                 size=\"$size\"
                 value=\"$value\"";

    if($readonly) {
        echo "class=\"readonly\"
              readonly=\"readonly\"";    
    } else {
        echo "class=\"input\"";
    } // end if

    if($max > 0) {
        echo "maxlength=\"$max\"";
    } else {
        echo "maxlength=\"$size\"";
    } // end if

    if(!is_null($tip) && !$readonly) {
        echo "title=\"$tip\"";
    } // end if

    echo "onClick=\"highlight(this);\"/>";
} // end input_field_text()

/**
 * generate html for a select field
 * @param   string  $id         used for name and id
 * @param   boolean $disabled   disable selection
 * @param   array   $values     array of possible selection values
 * @param   int     $size       number of visible selection values
 * @param   string  $selected   selected value
 */
function input_field_select($id, $disabled, $values, $size, $selected) {
    if(is_array($values)) {
        $num_values = count($values);
        
        echo "<select id=\"$id\"
                      name=\"$id\"
                      size=\"$size\"";

        if($disabled) {
            echo "disabled=\"disabled\"";
        } else {
            echo "class=\"input\"";
        } // end if

        echo " />";
        
        for($i = 0; $i < $num_values; $i++) {
            echo "<option value=\"$values[$i]\"";

            if(strcmp($selected, $values[$i]) == 0) {
                echo "selected=\"selected\"";
            } // end if
            
            echo ">$values[$i]</option>";
        } // end for

        echo "</select>";
    } // end if
} // end input_field_select()

/**
 * generate html for a password/obscured input field
 * @param   string  $id         used for name and id
 * @param   boolean $readonly   indicates readonly field
 * @param   string  $value      value for the field
 * @param   int     $size       size and maxlength of the field
 * @param   string  $tip        tooltip text
 */
function input_field_password($id, $readonly, $value, $size, $tip = null) {
    echo "<input type=\"password\"
                 class=\"input\"
                 id=\"$id\"
                 name=\"$id\"
                 size=\"$size\"
                 maxlength=\"$size\"
                 value=\"$value\"";

    if($readonly) {
        echo "readonly=\"readonly\"";
    } // end if

    if(!is_null($tip) && !$readonly) {
        echo "title=\"$tip\"";
    } // end if
    
    echo " />";
} // end input_field_password()

/**
 * generate html for a message field
 * @param   string  $id         used for name and id
 * @param   string  $value      value for the field
 */
function input_field_message($id, $value, $size) {
    echo "<p><font color=red>$value</font></p>";

/*    echo "<input type=\"text\"
                 class=\"msg\"
                 id=\"$id\"
                 name=\"$id\"
                 value=\"$value\"
                 readonly=\"readonly\"
                 size=\"$size\" />"; */
} // end input_field_message()

/**
 * generate html for a submit input field
 * @param   string  $id         used for name and id
 * @param   string  $value      value for the field
 * @param   string  $class      class for the field
 */
function input_field_submit($id, $value, $class) {
    echo "<input type=\"submit\"
                 class=\"$class\"
                 id=\"$id\"
                 name=\"$id\"
                 value=\"$value\"> ";
} // end input_field_password()

/**
 * generate html for a field label
 * @param   string  $id         field id/name for label
 * @param   string  $value      value for the label
 */
function field_label($id, $value) {
    echo "<label class=\"label\" for=\"$id\">$value</label>";
} // end field_label()

/**
 * generate a sequential pin number for experiment access
 * @param   int $min    minimum pin value
 * @param   int $max    maximum pin value
 * @return  int a unique pin number or 0 (fail)
 */
function generate_seq_pin($min, $max) {
    $pin = 0;
    
    $db = new database();
    $db->dblink();

    // read all existing pin numbers
    $result = $db->get_recs("users", "pin");
    $recs = $db->fetch_objects($result);

    // check if this is the first pin
    if(!is_array($recs)) {
        // this is the first pin
        $pin = $min;
    } else {
        rsort($recs);

        // allocate the next available pin
        if($recs[0]->pin < $max) {
            $pin = $recs[0]->pin + 1;
        } else {
            // try and find a gap
            for($i = $min; $i < $max; $i++) {
                // check the difference between two adjacent pins
                if($recs[$i]->pin - $recs[$i + 1]->pin > 1) {
                    // difference is greater than 1: gap found
                    $pin = $recs[$i]->pin - 1;
                    break;
                } // end if
            } // end for
        } // end if
    } // end if

    return $pin;
} // end generate_seq_pin()

/**
 * generate a random pin number for experiment access
 * @param   int $min    minimum pin value
 * @param   int $max    maximum pin value
 * @return  int a unique pin number or 0 (fail)
 */
function generate_pin($min, $max) {
    $pin = 0;
    $all_pins = array();
    $used_pins = array();

    $db = new database();
    $db->dblink();

    // read all existing pin numbers
    $result = $db->get_recs("users", "pin");
    $recs = $db->fetch_objects($result);

    // create an unstructured array of used pins
    if(is_array($recs)) {
        $n = count($recs);

        for($i = 0; $i < $n; $i++) {
            $used_pins[] = $recs[$i]->pin;
        } // end for
    } // end if

    if(count($used_pins) <= 0) {
        // all pins are available
        $pin = mt_rand($min, $max);
    } else {
        // create an array of all possible pin numbers
        for($i = $min; $i <= $max; $i++) {
            $all_pins[] = $i;
        } // end for

        // create an array of all unused pin numbers
        $unused_pins = array_diff($all_pins, $used_pins);

        // randomly select an unused pin
        $pin = $unused_pins[mt_rand(0, count($unused_pins))];
    } // end if

    return $pin;
} // end generate_pin()

/**
 * show version
 */
function version() {
    echo "<img src=\"graphics/telephone_telepathy_version.jpg\" alt=\"Version\" />";
} // end version()

/**
 * show browser support badges
 * @param   int $browser    browser indicator
 */
function browser_support($browser) {
    switch($browser) {
        case FIREFOX:
            echo "<img src=\"graphics/Firefox-32.png\" alt=\"Firefox\" />";
            break;
        case SAFARI:
            echo "<img src=\"graphics/Safari-32.png\" alt=\"Safari\" />";
            break;
        case IE:
            echo "<img src=\"graphics/IE-32.png\" alt=\"IE\" />";
            break;
        case CHROME:
            echo "<img src=\"graphics/Chrome-32.png\" alt=\"Chrome\" />";
            break;
        default:
            break;
    } // end switch
} // end browser_support()

/**
 * display the banner
 */
function banner() {
    echo "<img src=\"graphics/telephone_telepathy_banner_beta.jpg\"
               alt=\"The Telephone Telepathy Experiment\" />";
} // end banner()

/**
 * compare two arrays
 * @param   array   $a1    first array for comparison
 * @param   array   $a2    second array for comparison
 * @return  boolean true|false
 */
function array_compare($a1, $a2) {
    if (!(is_array($a1) and (is_array($a2)))) {
        return false;
    } // end if

    if (!count($a1) == count($a2)) {
       return false;
    } // end if

    foreach ($a1 as $key => $val) {
        if (!array_key_exists($key, $a2)) {
            return false;
        } else if (is_array($val) and is_array($a2[$key])) {
            if (!array_compare_recursive($val,$a2[$key])) {
                return false;
            } // end if
        } else if (!($val === $a2[$key])) {
            return false;
        } // end if
   } // end foreach

   return true;
} // end array_compare()

/**
 * extract label data from the experimental results array
 * @param   array   $results    the results array (trial structure)
 * @return  array   array of labels (experiment id)
 */
function extract_labels($results) {
    $labels = Array();
    $ids = array();

    if(is_array($results)) {
        sort($results);
        $num_trials = count($results);

        // extract all experiment ids
        for($i = 0; $i < $num_trials; $i++) {
            $ids[] = $results[$i]->experiment_id;
        } // end for

        // return only the unique ids
        $unique_labels = array_unique($ids);

        // fix the indexes
        for($i = 0; $i < $num_trials; $i++) {
            if(isset($unique_labels[$i])) {
                $labels[] = $unique_labels[$i];
            } // end if
        } // end for
    } // end if

    return $labels;
} // extract_labels()

/**
 * extract data values from the experimental results array
 * @param   array   $results    experimental results (trial structure)
 * @return  array   number of hits per experiment/trial
 */
function extract_data($results) {
    $trials = Array();
    $hit_count = 0;

    if(is_array($results)) {
        sort($results);
        $num_trials = count($results);

        for($i = 0; $i < $num_trials; $i++) {
            if($i <= 0) {
                // first trial
                $hit_count+=$results[$i]->hit;
				//if($results[$i]->hit == HIT) {
                //    $hit_count++;
                //} // end if
            } else {
                // other trials
                if($results[$i]->experiment_id !=
                   $results[$i - 1]->experiment_id) {

                    // new experiment
                    $trials[] = $hit_count;
                    $hit_count = 0;                   
                } // end if

                $hit_count+=$results[$i]->hit;
                //if($results[$i]->hit == HIT) {
                //    $hit_count++;
                //} // end if
            } // end if
        } // end for

        // pick up the last experiment if there is more than one
        if(($num_trials / MAX_TRIALS) > 1) {

            // hit count for the last experiment
	        $hit_count+=$results[$i]->hit;
            //if($results[$num_trials - 1]->hit == HIT) {
            //    $hit_count++;
            //} // end if
        } // end if

        // record the last result
        $trials[count($trials)] = $hit_count;
    } // end if

    // return the data in graph format
    return $trials;
} // extract_data()

/**
 * build an array of graph data at chance level
 * @param   array   $data_actual    experimental data in graph format
 * @return  array   chance level data for each trial
 */
function chance_data($data_actual) {
    $chance = Array();

    if(is_array($data_actual)) {
        $num_points = count($data_actual);

        for($i = 0; $i < $num_points; $i++) {
            $chance[] = 33.333333333;
        } // end for
    } // end if

    return $chance;
} // end chance_data()

/**
 * generate a graph of results vs. chance (uses a remote api)
 * @param   object  $user   user object
 * @return  string  image tag for the generated graph
 */
function generate_graph($user) {
    // default image
    $img = null;
   
    if(isset($user)) {
        // generate a unique file name
        $file = "graphics/graph_".$user->get_username().".png";

        // initialise the graph
        $graph = new PHPGraphLib(425, 190, $file);
        $results = array();
        $data = array();

        // extract the axis labels and data
        $user->set_results();
        $results = $user->get_results();

        if(!empty($results)) {
            $x_labels = extract_labels($results);
            $y_data_actual = extract_data($results);
            
            // format the results
            $num_results = count($x_labels);
            
            for($i = 0; $i < $num_results; $i++) {
                $data['E'.strval($i+1)] = $y_data_actual[$i];
                //$data[$x_labels[$i]] = $y_data_actual[$i];
            } // end for

            // populate the graph
            $graph->addData($data);
            $graph->setGradient('blue', 'aqua');
            $graph->setLegend(true);
            $graph->setLegendTitle('Hits');
            $graph->setTitle('Hits vs. Chance per Experiment');
            $graph->setTitleLocation('left');
            $graph->setRange(MAX_TRIALS * 3, 0);
            $graph->setGoalLine( MAX_TRIALS );
            $graph->setGoalLineColor('red');
            $graph->setYValues(true);
            $graph->setDataPoints(true);
            $graph->setDataValues(true);
            $graph->setLine(true);
            $graph->createGraph();

            $img = "<img src='$file' alt='Error generating graph' />";
        } // end if
    } // end if

    if(strlen($img) <= 0) {
        $img = "<img src='graphics/telephone_telepathy.jpg'
                     alt='telephone telepathy'
                     width='425'
                     height='344' />";
    } // end if

    return $img;
} // end generate_graph()

/**
 * text output for the results page
 * @param   object  $user   user object
 */
function describe_results($user) {
    if(isset($user)) {
        $user->set_results();
        $results = $user->get_results();

        if(!empty($results)) {
            $y_data_actual = extract_data($results);
            $num_experiments = count($y_data_actual);
			$num_trials_real = $num_experiments * MAX_TRIALS;
            $num_trials = $num_experiments * MAX_TRIALS * 3;
            $num_hits = 0;

            // count the total number of hits
            for($i = 0; $i < $num_experiments; $i++) {
                $num_hits += $y_data_actual[$i];
            } // end for

            // calculate the percentages
            $score = round(($num_hits / $num_trials * 100));
            $chance = round(33.33333333, 0);
			
            echo "<p class=\"info\">
                    So far you have conducted $num_trials_real trials over
                    $num_experiments experiments. You and your friends have scored $num_hits hits out of $num_trials potentialy correct guesses.
                    This is an overall hit rate of <b>$score%</b>. The expected
                    hit rate by chance is <b>$chance%</b>. The graph on the left
                    provides a visual representation of your results.<br /><br />
                    The numbers along the x-axis are the experiment numbers for
                    your experiments. The numbers on the y-axis show the number
                    of hits. The amount of hits you scored for each experiment
                    is shown above each bar on the graph. The horizontal red
                    line represents the expected chance hit rate.
                  </p>";
        } else {
            echo "<p class=\"info\">
                      You have not conducted any experiments yet. To begin
                      an experiment follow the instructions
                      <a href=\"index.php\">here</a>. When you have completed
                      some experiments you will be able to view your results
                      on this page. Good luck!
                  </p>";
        } // end if
    } // end if
} // end describe_results()

/**
 * return the content from a given url using curl
 * @param   string  $url    the url
 * @return  string  the content
 */
function get_content($url)
{
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();

    ob_end_clean();

    return $string;
} // end get_content()

/**
 * delete a file from the server
 * @param   string  $old    current directory
 * @param   string  $path   path to file
 * @param   string  $file   file name
 * @return  boolean deletion successful or not
 */
function delete_file($old, $path, $file) {
    $result = false;
    
    if(isset($old) && file_exists($path.$file)) {
        chdir($path);
        $result = unlink($file);
        chdir($old);
    } // end if

    return $result;
} // end delete_file()

/**
 * download the contents of a table to a csv file
 * @param   string  $table  the name of the table to download
 * @param   string  $file   the path and file name
 * @param   string  $where  filter clause for record selection
 * @return  boolean download successful or not
 */
function download_table($table, $file, $where="") {
    $download_result = false;
    $csv_output = "";

    $db = new database();
    $db->dblink();

    $result = $db->get_recs($table, "*", $where);
    $recs = $db->fetch_objects($result);

    if(is_array($recs)) {
        $n = count($recs);
        sort($recs);
        
        // output the headers
        foreach($recs[0] as $key => $value) {
            $csv_output .= $key.",";
        } // end foreach

        $csv_output .= "\n";
        
        for($i = 0; $i < $n; $i++) {
            foreach($recs[$i] as $key => $value) {
                $csv_output .= $value.",";
            } // end foreach

            $csv_output .= "\n";
        } // end for

        $download_result = true;

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv".date("Y-m-d").".csv");
        header("Content-disposition: filename=".$file.".csv");

        echo $csv_output;
    } // end if

    return $download_result;
} // end download_table()

/**
 * return the total number of completed trials
 * @return  int the number of completed trials
 */
function count_completed_trials() {
    $db = new database();
    $db->dblink();
    
    $result = $db->get_recs("trials", "status", "status='".COMPLETE."'");
    $recs = $db->fetch_objects($result);

    return count($recs);
} // end count_completed_trials()

/**
 * return the overall percentage hit rate
 * @return  float   the overall percentage hit rate
 */
function overall_hit_rate() {
    $db = new database();
    $db->dblink();

    // get the number of hits
    $hit_result = $db->get_recs("trials", "hit", "status='".COMPLETE."' AND ".
                                             "hit='".HIT."'");
    $hit_recs = $db->fetch_objects($hit_result);
    $hits = count($hit_recs);

    // count the total number of completed trials
    $complete_trials = count_completed_trials();

    // calculate and return the hit rate as a percentage
    return round(($hits / $complete_trials) * 100, 2);
} // end overall_hit_rate()

/**
 * return the total number of hits
 * @return  int number of hits for completed trials
 */
function total_hit_count() {
    $db = new database();
    $db->dblink();

    // get the number of hits
    $hit_result = $db->get_recs("trials", "hit", "status='".COMPLETE."' AND ".
                                             "hit='".HIT."'");
    $hit_recs = $db->fetch_objects($hit_result);
    $hits = count($hit_recs);

    return $hits;
} // end total_hit_count()
?>
