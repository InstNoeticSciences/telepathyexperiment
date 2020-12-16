<?php
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/sms_queue.php");
include("../classes/twilio_sms.php");
include("../classes/database.php");

$sid = $_POST['CallSid'];

// no timeout on this script
set_time_limit(0);

// tell the client the request has finished processing
header('Connection: close');

@ob_end_clean();

// continue running once client disconnects
ignore_user_abort();

ob_start();

// regular request processing and cleanup
$iSize = ob_get_length();
header("Content-Length: $iSize");
ob_end_flush();
flush();
session_write_close();

// asynchronous sms queue processing
$queue = new sms_queue($sid);

if($queue->fetch()) {
    $queue->process(false); // Arnaud Delorme change Oct 1st, 2013
//    $queue->process(true);
} // end if
?>
