<?php
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

// -- tt project includes --
include("../lib/tt_experiment_util.php"); // experiment-related utilities

$db = new database;
$db->dblink();

$x = explode("___", $_GET['stuff']);
$uid = $x[0];
$page_num = $x[1];
$logged_user = $x[2];

$rec = $db->get_rec("users", "*", "id='{$uid}'");
$user = new user($rec);
$rec = $db->get_rec("users", "*", "username='{$logged_user}'");
$lu = new user($rec);

/*================================================== tt experiment: start */
// initialise the page count variables
$exp_count = 1;
$page_count = ceil($exp_count/mpp);

if(!$page_num) {
    $page_num = 1;
} // end if

$limit = mpp;
$offset = ($page_num - 1) * $limit;
$next = $page_num + 1;
$prev = $page_num - 1;
$dots = 0;

for($i = 1; $i<=$page_count; $i++){
	$page_numbers[$i] = $i;
} // end for

// get all experiments
$result = $db->get_recs("experiments", "*", "", "start_date_time desc");

if($result) {
    $experiments = $db->fetch_objects($result);
} // end if

// get all of the user names
$result = $db->get_recs("users", "*");

if($result) {
    $user_list = $db->fetch_objects($result);
} // end if

if(is_array($experiments) && ($user->visible || $user->username == $lu->username)) {
    $trial_count = count_my_trials('', $experiments);
    $hit_count = count_my_hits('', $experiments);
    $hit_rate = percentage($trial_count, $hit_count, "2");
    
    $most_hit = boundary_caller($experiments, $user_list, MOST_HITS, 4);
    $least_hit = boundary_caller($experiments, $user_list, LEAST_HITS, 4);

    // summary statistics for all users
    echo "<div class='stat_box' id='stat_box' name='stat_box'}";
    echo "<p class='tt_comment'>Total Number of Trials = ".$trial_count."<br>";
    echo "Total Number of Hits = ".$hit_count."<br>";
    echo "Hit Rate = ".$hit_rate."%<br><br>";
    echo "The most hits have been scored with: ".(is_array($most_hit) ? implode(",",$most_hit) : $most_hit)."<br>";
    echo "The least hits have been scored with: ".(is_array($least_hit) ? implode(",",$least_hit) : $least_hit)."<br></p>";
    echo "</div>";

    // download options
    form_begin();
    form_submit_nl("all_download", "Download All Results", "tt_submit");
    form_end();
} // end if
?>