<?php
// set the error handler method
set_error_handler("error_handler", E_ALL);

/**
 * error handling function
 * @param   string  $err_no the error number
 * @param   string  $err_st the error message
 * @param   string  $err_fl the file in which the error occurred
 * @param   string  $err_ln the line on which the error occurred
 */
function error_handler($err_no, $err_st, $err_fl, $err_ln) {
    // clear any output that has already been generated
    ob_clean();

    // output the error message
    $error_ms = "ERRNO: ".$err_no.chr(10)." ".
                "TEXT: ".$err_st.chr(10)." ".
                "LOCATION: ".$err_fl." ".
                ", line ".$err_ln;

    echo $error_ms;
    exit;
} // end error_handler()
?>
