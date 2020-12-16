<?

class link_catcher{
	var $message;
	function link_catcher($string) {
		$array = $this->catch_link($string);
		if(count($array)>0) {
                	for($i=0; $i<count($array); $i++) {
                        	if(strlen($array[$i][0])<link_lenght_limit){
                                	$array[$i][1] = $array[$i][0];
                        	}else if(strlen($array[$i][0])<255){
                                	$url = $array[$i][0];
                                	$db = new database;
					$db->dblink();
					$tiny = $this->generate_tiny(5);
					if(substr($url,0,4)!="http")
							$url = "http://".$url;
					$rec = $db->get_rec("tiny_url", "tiny, url, id", "url = '{$url}'");
					if($rec->url == $url) {
						$array[$i][1] = root_domain."/url/".$rec->tiny;
						//updating an expire date
						$db->db_update("tiny_url","expires = {$expires}","id = {$rec->id}");

					}else{
						$expires = time()+tiny_url_expires;
						if ($rec = $db->get_rec("tiny_url", "id,tiny, expires", "tiny = '{$tiny}'")) {
							if($rec->expires > time()) {
								//update an existing record
								$db->db_update("tiny_url","url = '{$url}', expires = {$expires}","id = {$rec->id}");
							}else{
								//generating another tiny
								$tiny = $this->generate_tiny(5);
							}

						}else{
							$id = $db->db_insert("tiny_url", "url, tiny, author_id, expires","'{$url}', '{$tiny}', {$_SESSION['user']->id}, {$expires} ");
							$i=0;
							while(!$id&&$i<100) {
								$tiny = $this->generate_tiny(5);
								$id = $db->db_insert("tiny_url", "url, tiny, author_id, expires","'{$url}', '{$tiny}', {$_SESSION['user']->id}, {$expires} ");
								$i++;
							}
						}

						
						$tiny_url = root_domain."/url/".$tiny ;
						$array[$i][1] = $tiny_url;
						
					}
					
                                	
				}else{
					//urls witch are longer than 255 will be cut to the main domain.
					$short = preg_split("/[\/,]+/", $array[$i][0]);
					$domain = "http://".$short[1]."/";
					$array[$i][1] = $domain;
					
				}
                        //echo($string);
                	$string = str_replace($array[$i][0], $array[$i][1], $string);
			//	echo("robie {$i} tiny urla z: {$array[$i][0]} na: {$array[$i][1]}<br />{$string}");

			}
			
		}
		
   				
		$this->message = urlencode(addslashes($string));
		//echo $this->message;
		/*echo("<pre>");
		print_r($array);
		echo("</pre>");*/
	}
	function make_seed() {
    		list($usec,$sec) = explode(" ", microtime());
    		return ((float)$sec+(float)$usec) * 100000;
	}
	function make_clickable($string) {
		if(substr($string,0,4)!="www.")
        		$res = "<a href=\"".$string."\" target=\"_blank\">".$string."</a>";
		else
			$res = "<a href=\"http://".$string."\" target=\"_blank\">".$string."</a>";
		return $res;
	}
	function generate_tiny($length) {
        	mt_srand($this->make_seed());
        	$possible_characters = "abcdefghijkmnopqrstuvwxyz1234567890";
        	$string = "";
        	while(strlen($string)<$length) {
                	$string .= substr($possible_characters, mt_rand()%(strlen($possible_characters)),1);
        	}
        	return $string;
	}
	function catch_link($string) {
			
		preg_match_all("/(ftp:\/\/|http:\/\/|https:\/\/|www.|ftp.)(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/", $string, $array, PREG_SET_ORDER);
		$result = $array;
		return $result;

	}


};
?>
