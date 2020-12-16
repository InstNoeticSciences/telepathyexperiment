<?
function new_picture_size($max_w, $max_h, $path){
	$size = getimagesize($path);
	$w = $size[0];
	$h = $size[1];
	if($w <= $max_w && $h <= $max_h) $s = array(0=>$w, 1=>$h, 2=>$w, 3=>$h);
	else {
		if($w >= $h){
			if($w > $max_w){
				$new_w = $max_w;
				$new_h = ceil(($new_w * $h) / $w);
			}
			if($h > $max_h){
				$new_h = $max_h;
				$new_w = ceil(($new_h * $w) / $h);
			}
		} else {
			if($h > $max_h){
				$new_h = $max_h;
				$new_w = ceil(($new_h * $w) / $h);
			}
			if($w > $max_w){
				$new_w = $max_w;
				$new_h = ceil(($new_w * $h) / $w);
			}
		}
		$s = array(0=>$new_w, 1=>$new_h, 2=>$w, 3=>$h);
	}
	return $s;
}

function make_square($max, $dir, $file){
	$path = $dir."/".$file;
	$newpath = $dir."_mini/".$file;
	$size = getimagesize($path);
	$w = $size[0];
	$h = $size[1];
	if($w > $max_w || $h > $max_h) {
		if($w > $h){
			$neww = ($w * $max) / $h;
			$newh = $max;
		} else {
			$newh = ($h * $max) / $w;
			$neww = $max;
		}
		$oldpic = imagecreatefromjpeg($path);
		$newpic = imagecreatetruecolor($neww, $newh);
		imagecopyresampled($newpic, $oldpic, 0, 0, 0, 0, $neww, $newh, $w, $h);
		imagejpeg($newpic, $newpath, 100);
		imagedestroy($oldpic);
		imagedestroy($newpic);

		$size = getimagesize($newpath);
		$w = $size[0];
		$h = $size[1];

		if($w > $max) $x = round(($w - $max)/2);
		else $x = round(($max - $w)/2);
		if($h > $max) $y = round(($h - $max)/2);
		else $y = round(($max - $h)/2);
		$oldpic = imagecreatefromjpeg($newpath);
		$newpic = imagecreatetruecolor($max, $max);
		imagecopyresampled($newpic, $oldpic, 0, 0, $x, $y, $w, $h, $w, $h);
		imagejpeg($newpic, $newpath, 100);
		imagedestroy($oldpic);
		imagedestroy($newpic);
	}
	return $s;
}

function resize_picture($w, $h, $picture, $format){
	$format = str_replace(".", "", $format);
	switch(strtolower($format)){
		case "jpg":
			//$th_size = new_picture_size($w, $h, $picture);
			$oldpic = imagecreatefromjpeg($picture);
			$newpic = imagecreatetruecolor($w, $h);
			$size = min(imageSX($oldpic), imageSY($oldpic));
			$offsetX = (imageSX($oldpic) - $size) / 2;
			imagecopyresampled($newpic, $oldpic, 0, 0, $offsetX, 0, $w, $h, $size, $size);


			imagejpeg($newpic, $picture, 100);
			imagedestroy($oldpic);
			imagedestroy($newpic);
			break;
		case "jpeg":
			$oldpic = imagecreatefromjpeg($picture);
			$newpic = imagecreatetruecolor($w, $h);
			$size = min(imageSX($oldpic), imageSY($oldpic));
			$offsetX = (imageSX($oldpic) - $size) / 2;
			imagecopyresampled($newpic, $oldpic, 0, 0, $offsetX, 0, $w, $h, $size, $size);
			imagejpeg($newpic, $picture, 100);
			imagedestroy($oldpic);
			imagedestroy($newpic);
			break;
		case "png":
			$oldpic = imagecreatefrompng($picture);
			$newpic = imagecreatetruecolor($w, $h);
			$size = min(imageSX($oldpic), imageSY($oldpic));
			$offsetX = (imageSX($oldpic) - $size) / 2;
			//$offsetY = (imageSX($oldpic) - $size) / 2;
			imagecopyresampled($newpic, $oldpic, 0, 0, $offsetX, 0, $w, $h, $size, $size);
			imagepng($newpic, $picture, 9);
			imagedestroy($oldpic);
			imagedestroy($newpic);
			break;
		case "gif";
			$oldpic = imagecreatefromgif($picture);
			$newpic = imagecreate($w, $h);
			$size = min(imageSX($oldpic), imageSY($oldpic));
			$offsetX = (imageSX($oldpic) - $size) / 2;
			//$offsetY = (imageSX($oldpic) - $size) / 2;
			imagecopyresampled($newpic, $oldpic, 0, 0, $offsetX, 0, $w, $h, $size, $size);
			imagegif($newpic, $picture, 100);
			imagedestroy($oldpic);
			imagedestroy($newpic);
			break;
	}
}

function upload_avatar($file_field, $uid){
	if(is_file("avatars_mini/$uid.jpg")) unlink("avatars_mini/$uid.jpg");
	if(is_file("avatars_mini/$uid.jpeg")) unlink("avatars_mini/$uid.jpeg");
	if(is_file("avatars_mini/$uid.gif")) unlink("avatars_mini/$uid.gif");
	if(is_file("avatars_mini/$uid.png")) unlink("avatars_mini/$uid.png");

	if(is_uploaded_file($_FILES[$file_field]['tmp_name'])){
		$size = getimagesize($_FILES[$file_field]['tmp_name']);
		$mime = $size['mime'];
		if($mime != "image/png" && $mime != "image/jpeg" && $mime != "image/gif") return false;
		if($_FILES['userfile']['size'] > 1048576) return false;
		$size = getimagesize($_FILES[$file_field]['tmp_name']);

		$parts = explode(".", $_FILES[$file_field]['name']);
		$last = count($parts) - 1;
		$ext = $parts[$last];
		$filename = $uid.".".$ext;

		move_uploaded_file($_FILES[$file_field]['tmp_name'], "avatars_mini/$filename");
		chmod("avatars_mini/$filename", 0777);
		resize_picture(60, 60, "avatars_mini/$filename", $ext);

		//create 25x25 thumbnail
		copy("avatars_mini/$filename", "avatars25/$filename");
		resize_picture(25, 25, "avatars25/$filename", $ext);

		//update the user's avatar filename in the database
		$db = new database;
		$db->dblink();
		$db->db_update("users", "avatar='$filename'", "id=$uid");
		return $filename;
	}
}

function get_ext_from_mime($mime){
   switch($mime) {
       case "image/jpeg":
           return "jpg";
       break;
       case "image/jpg":
           return "jpg";
       break;
       case "image/gif":
           return "gif";
       break;
       case "image/png":
           return "png";
       break;
   }
}
?>