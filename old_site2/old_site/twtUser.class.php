<?php

class twtUser {
	
	public function authenticate($userName, $password) {
		
		//$nameQuery = "SELECT usr_id, usr_name, usr_passwd FROM twt_user WHERE usr_name='{$userName}'";
		$nameQuery = "SELECT id, username, pass FROM users WHERE username='{$userName}'";
		
		$resource = mysql_query($nameQuery);
		
		if ($resource) {
			while ($row = mysql_fetch_assoc($resource)) {
				if (trim($row['pass']) == trim($password)) {
					return $row['id'];
				}			    
			}
		}
			
		return false;
		
	}
	
	public function getUserDetails($userID) {
		
		$arrReturn = array();
		
		$nameQuery = "SELECT id, username, pass, email, name, location FROM users WHERE id='{$userID}'";		
		
		$resource = mysql_query($nameQuery);
		
		if ($resource) {
			while ($row = mysql_fetch_assoc($resource)) {				
				$arrReturn['username'] = $row['username'];
				$arrReturn['pass'] = $row['pass'];
				$arrReturn['email'] = $row['email'];
				$arrReturn['usr_id'] = $row['id'];
				$arrReturn['name'] = $row['name'];
				$arrReturn['location'] = $row['location'];
			}
		}
		
		return $arrReturn;
		
	}
	
	public function followUser($follower, $followed) {
		
		$insertQuery = "INSERT INTO followed SET `user` = '{$follower}', `followed` = '{$followed}', `friend_only`=0, `sms_flag`=0 ";
		
		mysql_query($insertQuery);
		
	}
	
	public function getFollowers($userID) {
		
		$arrReturn = array();
		
		$nameQuery = "SELECT user, id FROM followed WHERE followed='{$userID}'";		
		
		$resource = mysql_query($nameQuery);
		
		$cnt=0;
		if ($resource) {
			while ($row = mysql_fetch_assoc($resource)) {				
				$arrReturn[$cnt]['usr_id'] = $row['user'];
				$arrReturn[$cnt]['flw_id'] = $row['id'];
				$cnt++;
			}
		}
		
		return $arrReturn;
		
	}
	
	public function getFriends($userID) {
		
		$arrReturn = array();
		
		$nameQuery = "SELECT followed, id FROM followed WHERE user='{$userID}'";		
		
		$resource = mysql_query($nameQuery);

		$cnt=0;
		if ($resource) {
			while ($row = mysql_fetch_assoc($resource)) {				
				$arrReturn[$cnt]['usr_id'] = $row['followed'];
				$arrReturn[$cnt]['flw_id'] = $row['id'];
				$cnt++;
			}
		}
		
		return $arrReturn;
		
	}
	
}

?>