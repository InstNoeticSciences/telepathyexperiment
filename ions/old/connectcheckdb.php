<?php

$result = mysql_query("SELECT image, response FROM trials WHERE sessionid = '$sessionid'" ) or die (mysql_error());

print '<?xml version="1.0" encoding="UTF-8"?><Response><Dial><Conference beep="false" waitUrl="" startConferenceOnEnter="true" endConferenceOnExit="true">NoMusicNoBeepRoom</Conference></Dial></Response>';

?>
