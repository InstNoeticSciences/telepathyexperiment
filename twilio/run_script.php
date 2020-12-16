<?php
$script = $argv[1];
$params = implode(' ', array_slice($argv, 2));
$cmd    = "{$script} {$params} > /dev/null &";

$output = array();
$return = 0;
exec("php {$cmd}", $output, $return);

exit((int)$return);
?>
