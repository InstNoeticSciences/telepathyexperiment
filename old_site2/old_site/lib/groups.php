<?php

class group {

	var $group_id, $parent_id, $level_id, $order_id, $group_title, $group_furl, $group_furl_hash, $group_descr, $group_tags, $group_image;

	function group($rec){
		$this->group_id		= $rec->group_id; 
		$this->parent_id	= $rec->parent_id;
		$this->level_id		= $rec->level_id;
		$this->order_id		= $rec->order_id;
		$this->group_title	= $rec->group_title;
		$this->group_furl	= $rec->group_furl;
		$this->group_furl_hash	= $rec->group_furl_hash;
		$this->group_descr	= $rec->group_descr;
		$this->group_tags	= $rec->group_tags;
		$this->group_image	= $rec->group_image;
	}


	function load_pagelist($array, $limit) {
		$j=0;
		for($i=0; $i<count($array); $i++) {
			if($i%$limit==0) $j++;
			$page[$j][$i] = $array[$i];
		}
		return $page;
	}

	function make_page_list($ile, $cur_page, $module, $user) {
		$res = "<p class=\"mid\">";
		$prev = $cur_page-1;
		$next = $cur_page+1;
		if($user!='')
			$user .="/";
		if($cur_page>1)
			$res .= "<a href=\"".$module."/".$user."".$prev."\" class=\"pagination\">&#171;</a>";
		for($i=1; $i<=$ile; $i++) {
			if($i!=$cur_page)
				$res .= "<a href=\"".$module."/".$user."".$i."\" class=\"pagination\">".$i."</a>, ";
			else{
				$res .= $i.", </li>";
			}
		}
		if($cur_page<$ile)
			$res .= "<a href=\"".$module."/".$user."".$next."\" class=\"pagination\">&raquo;</a>";
		$res .= "</ul>";
		return $res;
	}


	function get_top10_popular(){
		$db = new database;
		$db->dblink();
		$sql = 'SELECT count(*) c_c , gm.*
			FROM groups_links gl 
			LEFT JOIN groups_mes gm ON gm.group_id=gl.group_id
			GROUP BY gl.group_id
			ORDER BY c_c DESC 
			LIMIT 10';
		return $db->fselect($sql,1);
	}

	function get_top10_new(){
		$db = new database;
		$db->dblink();
		$sql = 'SELECT * FROM groups_mes ORDER BY created DESC LIMIT 10';
		return $db->fselect($sql,1);
	}


	function get_all_group_tree($where=''){
		$db = new database;
		$db->dblink();
		$groups=array();
		if ($where){
			$groups_l1 = $db->fetch_objects( $db->get_recs("groups_mes", "*", $where , "order_id ASC" ) );
			if ($groups_l1) foreach($groups_l1 as $k=>$v) $groups[$v->group_id]=$v;
			unset($groups_l1);
		}
		else {
			$groups_l1 = $db->fetch_objects( $db->get_recs("groups_mes", "*", "level_id=1", "order_id ASC" ) );
			$groups_l2 = $db->fetch_objects( $db->get_recs("groups_mes", "*", "level_id=2", "order_id ASC" ) );
			if ($groups_l1) foreach($groups_l1 as $k=>$v) $groups[$v->group_id]=$v;
			if ($groups_l2) foreach($groups_l2 as $k=>$v) $groups[$v->parent_id]->subgroups[] = $v;
			unset($groups_l1);
			unset($groups_l2);
		}
		return $groups;
	}

	function join_user($user_id, $group_fh){
		$user_id=intval($user_id);
		if (!$user_id || !$group_fh)
			return false;
		$db = new database;
		$db->dblink();
		$group_data = $db->fselect('SELECT * FROM groups_mes WHERE group_furl_hash=\''.$group_fh.'\' ');
		$group_id = $group_data->group_id;
		$groups = $db->fselect('SELECT * FROM groups_links WHERE user_id=\''.$user_id.'\' AND group_id=\''.$group_id.'\' ');
		if (sizeof($groups))
			return false;
		return $result = $db->db_insert("groups_links", "user_id, group_id", " '{$user_id}','{$group_id}' " );
	}

	function unjoin_user($user_id, $group_fh){
		$user_id=intval($user_id);
		if (!$user_id || !$group_fh)
			return false;
		$db = new database;
		$db->dblink();
		$group_data = $db->fselect('SELECT * FROM groups_mes WHERE group_furl_hash=\''.$group_fh.'\' ');
		$group_id = $group_data->group_id;
		$groups = $db->fselect('SELECT * FROM groups_links WHERE user_id=\''.$user_id.'\' AND group_id=\''.$group_id.'\' ');
		if (!sizeof($groups))
			return false;
		return $db->db_delete("groups_links", "user_id='{$user_id}' AND group_id='{$group_id}'");
	}

}

?>