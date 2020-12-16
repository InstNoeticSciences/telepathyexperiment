<?
class message {
	var $id, $user, $user_id, $time, $reply, $direct, $from, $msg;

	function message($rec){
		$this->id = $rec->id;
		$this->user = $rec->user;
		$this->time = $rec->time;
		$this->group_id = $rec->group_id;
		$this->group_title = $rec->group_title?$rec->group_title:'';
		$this->group_furl  = $rec->group_furl?$rec->group_furl:'';
		$this->reply = $rec->reply;
		$this->direct = $rec->direct;
		$this->from = $rec->from;
		$this->msg = stripslashes(urldecode($rec->msg));
		$db = new database;
		$db->dblink();
		$u = $db->get_rec("users", "id", "username='{$rec->user}'");
		$this->user_id = $u->id;
	}

	function how_long_ago(){
		$now = time();
		$timespan = $now - $this->time;
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

	function get_user_name($id){
		$db = new database;
		$db->dblink();
		$rec = $db->get_rec("users", "username", "id=$id");
		return $rec->username;
	}

	function reply_get_username(){
		$db = new database;
		$db->dblink();
		$rec = $db->get_rec("messages", "user", "id={$this->reply}");
		return $rec->user;
	}
	function make_clickable($string) {
		if(substr($string,0,4)=="www.")
			$res = "<a href=\"http://{$string}\" target=\"_blank\">{$string}</a>";
		else if(substr($string,0,4)=="ftp.")
			$res = "<a href=\"ftp://{$string}\" target=\"_blank\">{$string}</a>";
		else
			$res = "<a href=\"{$string}\" target=\"_blank\">{$string}</a>";
		return $res;
	}
	function parse_links(){
		$text = trim($this->msg);
		$array = array();
		$array2 = array();
		preg_match_all("/(ftp:\/\/|http:\/\/|https:\/\/|www.|ftp.)(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/", $text, $array, PREG_SET_ORDER);

		$result = array_merge($array,$array2);
		for($i=0; $i<count($result); $i++){
			$result[$i][1] = $this->make_clickable($result[$i][0]);
			$text= str_replace($result[$i][0], $result[$i][1], $text);

		}
		if(preg_match('/^\@[a-zA-Z0-9]+/', $text)){
			$words = explode(' ', $text);
			$username = substr($words[0], 1, strlen($words[0]));
			$username = str_replace(":", "", $username);
			$username = str_replace("-", "", $username);
			$username = str_replace(";", "", $username);
			$text = str_replace("@$username", "@<a href='profile/$username'>$username</a>", $text);
		}
		return $text;
	}

	function get_back_color() {
		$db = new database();
		$db->dblink();
		$rec = $db->get_rec("layouts", "bubble_fill_color", "user in (select id from users where username='{$this->user}')");
		if(!$rec->bubble_fill_color) return false;
		else return "#".$rec->bubble_fill_color;
	}
	function get_text_color() {
		$db = new database();
		$db->dblink();
		$rec = $db->get_rec("layouts", "bubble_text_color", "user in (select id from users where username='{$this->user}')");
		if(!$rec->bubble_text_color) return false;
		else return "#".$rec->bubble_text_color;
	}

	function get_avatar(){
		$db = new database();
		$db->dblink();
		$rec = $db->get_rec("users", "avatar", "username='{$this->user}'");
		return $rec->avatar;
	}

	function get_direct_avatar(){
		$db = new database();
		$db->dblink();
		$rec = $db->get_rec("users", "avatar", "id='{$this->direct}'");
		return $rec->avatar;
	}

	function api_get_data($format, $data_only=0){
		switch($format){
			case "xml":
				$data = "<message>";
				$data .= "<id>{$this->id}</id>";
				$data .= "<user>{$this->user}</user>";
				$data .= "<user_id>{$this->user_id}</user_id>";
				$data .= "<time>{$this->time}</time>";
				$data .= "<when>".$this->how_long_ago()."</when>";
				$data .= "<reply_to>{$this->reply}</reply_to>";
				$data .= "<direct_to>{$this->direct}</direct_to>";
				$data .= "<from>{$this->from}</from>";
				$data .= "<content>{$this->msg}</content>";
				$data .= "</message>";
				break;
			case "json":
				if(!$data_only) $data = "{\"message\": ";
				$data .= "{";
				$data .= "\"id\": \"{$this->id}\",";
				$data .= "\"user\": \"{$this->user}\",";
				$data .= "\"user_id\": \"{$this->user_id}\",";
				$data .= "\"time\": \"{$this->time}\",";
				$data .= "\"when\": \"".$this->how_long_ago()."\",";
				$data .= "\"reply_to\": \"{$this->reply}\",";
				$data .= "\"direct_to\": \"{$this->direct}\",";
				$data .= "\"from\": \"{$this->from}\",";
				$data .= "\"content\": \"{$this->msg}\"";
				$data .= "}";
				if(!$data_only) $data .= "}";
				break;
			case "rss":
				$data = "<item>";
				$data .= "<title>{$this->user} wrote:</title>";
				$data .= "<link>http://gozub.com/message/{$this->id}</link>";
				$data .= "<description>{$this->msg}</description>";
				$data .= "<pubDate>".date("r", $this->time)."</pubDate>";
				$data .= "<guid>".rss_guid_prefix.$this->id."</guid>";
				$data .= "</item>";
				break;
		}
		return $data;
	}

	function is_favorite($uid){
		$db = new database;
		$db->dblink();
		$rec = $db->get_rec("favorites", "count(*) as qty", "message={$this->id} and user=$uid");
		if($rec->qty) return true;
		else return false;
	}

	function post_image($path="post_img/") {
       $ext[0] = "jpg";
       $ext[1] = "gif";
       $ext[2] = "png";
       for($i=0; $i<3; $i++) {
           if(is_file($path.$this->id.".".$ext[$i])) {
               return "<a href='post_img/{$this->id}.{$ext[$i]}' class='thickbox' title='post: {$this->id} photo'><img src='post_img/{$this->id}s.{$ext[$i]}' alt='post: {$this->id} photo' class='r' /></a>";
           }
       }
	}
}
?>
