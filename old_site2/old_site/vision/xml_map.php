<?

// muzhi xml data for map generator
// version: 0.2.1
// last update: 10/19/2007 12:38;
//
//including congfiguration
include("../inc/config.php");
include("../lib/database.php");
include("../lib/messages.php");
include("../inc/text.php");
//getting avatar
function avatar_image($id) {
	$dirpath = "../avatars_mini/";
	$dh = opendir($dirpath);
	while (false !== ($file = readdir($dh))) {
		$exp[$i] = explode( ".", $file);
		if($exp[$i][0] == $id) {
			$avatar =  "../avatars_mini/".$exp[$i][0].".".$exp[$i][1];
			break;
		}
		$i++;
	}
	return $avatar;


}

//time count
function how_long_ago($time){
		$now = time();
		$timespan = $now - $time;
		$days = floor($timespan/86400);
		if($days != 0) {
			if($days == 1) return yesterday;
			else return $days." ".days_ago;
		}

		$hours = floor($timespan/3600);
		if($hours != 0) {
			if($hours == 1) return "$hours ".hour_ago;
			else return "$hours ".hours_ago;
		}

		$minutes = floor($timespan/60);
		$secs = $timespan - ($minutes * 60);
		if($minutes != 0 && $secs != 0) {
			if($minutes != 1 && $secs != 1) return "$minutes ".mins_and." $secs ".secs_ago;
			else if($minutes == 1 && $secs != 1) return "$minutes ".min_and." $secs ".secs_ago;
			else if($minutes == 1 && $secs == 1) return "$minutes ".min_and." $sec ".secs_ago;
		}
		else if($minutes != 0 && $secs == 0) return "$minutes ".minutes_ago;
		else if($minutes == 0 && $secs != 0) return "$secs ".secs_ago;
}


//generating xml
header('Content-Type: text/xml');
header('Cache-control: No-Cache');
header('Pragma: No-Chache');
$dom = new DOMDocument('1.0', 'utf-8');
$muzhi = $dom->createElement('muzhi');
$dom->appendChild($muzhi);
//mysql connection
mysql_connect(db_host, db_user, db_pass);
mysql_select_db(db_name);
mysql_query( "set character set utf8;" );
//setting the limits and the last post id;
if($_GET['last'] == 0) {
	$limit = "limit 0, 20";
	$lastpost = '';
}else if($_GET['last'] >0) {
	$limit= "limit 0, 20";
	$lastpost = "and m.id > ".$_GET['last'];
}else{
	$limit = "limit 0,20";
	$lastopst = '';
}
//mysql query
$sql = "Select m.id, u.id, m.user, m.msg, m.time, u.x, u.y, u.location from messages as m inner join users as u on u.username = m.user where m.direct=0 and u.visible=1 ".$lastpost." order by time desc ".$limit.";";
$rez = mysql_query($sql);

while ($dupa = mysql_fetch_Array($rez)) {
	//handling a query
	$newPost = $dom ->createElement('post');
	$newPost->setAttribute('pid', $dupa['0']);
	$newPost->setAttribute('u_id', $dupa[1]);
	$newPost->setAttribute('avatar25link', avatar_image($dupa[1]));
	$newPost->setAttribute('user', $dupa[2]);
	$newPost->setAttribute('post', urldecode($dupa[3]));
	$newPost->setAttribute('when', how_long_ago($dupa[4]));
	$newPost->setAttribute('x', $dupa[5]);
	$newPost->setAttribute('y', $dupa[6]);
	$newPost->setAttribute('location', urldecode($dupa[7]));
	$muzhi->appendChild($newPost);
}
mysql_close();
$result = $dom->saveXML();
echo($result);





?>
