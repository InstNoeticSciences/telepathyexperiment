<?php

class openID{
	
	public function getLoginId($arrFetch) {				
		
		if ($arrFetch){
			
			$arrFetch = self::arrayTrim($arrFetch);
			
			$fetchQuery = "SELECT id FROM users WHERE `open_id_identifier`='{$arrFetch['identifier']}' AND `open_id_provider`='{$arrFetch['providername']}' ";
			
			//echo $fetchQuery;
			
			$resource = mysql_query($fetchQuery);
			$totalRows = mysql_numrows($resource);
			
			//echo " \n total rowns: {$totalRows}";			
			
			if ($resource && $totalRows) {
				//echo ' yes in ';
				while ($row = mysql_fetch_assoc($resource)) {
					//echo " \n yues rown id : {$row['id']}";
					// if found get the required values to set session
					return $row['id'];

				}

			} else {
								
				$insertQuery = "INSERT INTO users SET `open_id_identifier`='{$arrFetch['identifier']}', `open_id_provider`='{$arrFetch['providername']}', `open_id_displayname`='{$arrFetch['displayname']}', `new`='0'  ";
				
				mysql_query($insertQuery);
				
				$userID = mysql_insert_id();
				
				$username = self::getUniqueUserName( $userID, $arrFetch['displayname'] );
				
				$updateUser = "UPDATE users SET `username`='{$username}', `new`='0' WHERE id='{$userID}' ";
				
				mysql_query($updateUser);
				
				return $userID;

			}
			
		} else {
			
			return 0;

		}
		
	}

	public function arrayTrim($array) {

		foreach ($array as $key => $value) {

			if (!is_array($value)) {
				$array[$key] = trim($value);
			}

		}

		return $array;
		
	}
	
	public function getUniqueUserName($userID, $displayName='') {
		
		$displayName = trim($displayName);
		
		if ($displayName) {
			
			$displayName = str_replace(' ', '_', $displayName );
			
			$fetchQuery = "SELECT id FROM users WHERE `username`='{$displayName}' ";					
			
			$resource = mysql_query($fetchQuery);
			$totalRows = mysql_numrows($resource);
			
			//echo "total row : {$totalRows} \n ";
			//echo "query : {$fetchQuery} \n ";
			//exit();
			
			if ( $totalRows ) {
				self::getUniqueUserName($userID);
			} else {
				return $displayName;
			}
			
		} else {
			
			srand ((double) microtime( )*10000);
			$random_number = rand( );
			
			$tempUserName = 'revo_user_'.$random_number.'_'.$userID;						
			
			$fetchQuery = "SELECT id FROM users WHERE `username`='{$tempUserName}' ";					
			
			$resource = mysql_query($fetchQuery);
			$totalRows = mysql_numrows($resource);						
			
			//echo "total row : {$totalRows} \n ";
			//echo "query : {$fetchQuery} \n ";
			
			if ( $totalRows ) {
				self::getUniqueUserName($userID);
			} else {
				return $tempUserName;
			}
			

		}
		
	}
	
}

?>