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
// count all experiments initiated by this user
$count = $db->get_recs("experiments",
                       "count(*) as ile",
                       "initiator='{$user->username}'",
                       "start_date_time desc");

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

// get all experiments initiated by the user
$result = $db->get_recs("experiments",
                        "*",
                        "initiator='{$user->username}'",
                        "start_date_time desc");

if($result) {
    $recs = $db->fetch_objects($result);
} // end if

// get all of the user's friends
$friends = $user->get_friends();

if(is_array($recs) && ($user->visible || $user->username == $lu->username)) {
    $trial_count = count_my_trials($user->username, $recs);
    $hit_count = count_my_hits($user->username, $recs);
    $hit_rate = percentage($trial_count, $hit_count, "2");

    $most_hit = boundary_caller($recs, $friends, MOST_HITS, 4);
    $least_hit = boundary_caller($recs, $friends, LEAST_HITS, 4);

    // summary statistics for user (initiator)
    echo "<div class='stat_box' id='stat_box' name='stat_box'}";
    echo "<p class='tt_comment'>Number of Trials Conducted by Me = ".$trial_count."<br>";
    echo "Number of Hits Scored by Me = ".$hit_count."<br>";
    echo "Hit Rate = ".$hit_rate."%<br><br>";
    echo "I have scored the most hits with: ".(is_array($most_hit) ? implode(",",$most_hit) : $most_hit)."<br>";
    echo "I have scored the least hits with: ".(is_array($least_hit) ? implode(",",$least_hit) : $least_hit)."<br></p>";
    echo "</div>";

    // download options
    form_begin();
    form_submit_nl("my_download", "Download My Results", "tt_submit");
    form_end();
} // end if
?>