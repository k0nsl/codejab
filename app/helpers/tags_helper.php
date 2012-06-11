<?php 
function tag_object($ref_id, $tag, $checkForTag = true) {
	$ci =& get_instance();
	
	$tag = trim($tag);
	
	if (!empty($tag)) {
		$user_id = 0;
		if (!is_null($ci->currentUser)) {
			$user_id = $ci->currentUser['id'];
		}
	
		$hasTag = false;
	
		if ($checkForTag) {
			$hasTag = has_tag($ref_id, $tag);
		}
	
		if (!$hasTag) {
			$set = array(
				'user_id' => $user_id,
				'ref_id' => $ref_id,
				'tag' => $tag
			);
		
			$ci->db->insert(TAGS_DBTABLE, $set);
		}
	}
}

function has_tag($ref_id, $tag) {
	$ci =& get_instance();
	
	$user_id = 0;
	if (!is_null($ci->currentUser)) {
		$user_id = $ci->currentUser['id'];
	}
	
	$sql = sprintf("SELECT id FROM %s WHERE ref_id=%d AND LOWER(tag)='%s'",
		TAGS_DBTABLE, $ref_id, strtolower($tag));
	$res = $ci->db->query($sql);
	
	$result = ($res->num_rows() > 0);
	$res->free_result();
	
	return $result;
}

function get_tags($ref_id = null, $user_id = null, $limit = null) {
	$ci =& get_instance();
	$tags = array();
	
	$extSql = '';
	$limitSql = '';
	
	if (!is_null($ref_id)) {
		$extSql = ' AND ref_id=' . $ref_id;
	}

	if (!is_null($user_id)) {
		$extSql .= ' AND user_id=' . $user_id;
	}
	
	if (!is_null($limit)) {
		$limitSql = ' LIMIT ' . $limit;
	}
	
	$sql = sprintf('SELECT COUNT(id) AS total, * FROM %s WHERE 1%s GROUP BY tag', TAGS_DBTABLE, $extSql);
	$res = $ci->db->query($sql);
	
	if ($res->num_rows()) {
		$tags = $res->result_array();
	}
	$res->free_result();
	
	return $tags;
}

function tag_cloud($ref_id = null, $tagUrl = '#%s', $canDelete = true, $limit = null, $label = '') {
	$ci =& get_instance();
	
	$extSql = '';
	if (!is_null($ref_id)) {
		 $extSql .= ' AND ref_id=' . $ref_id;
	}
	
	$limitSql = '';
	if (!is_null($limit)) {
		$limitSql = ' LIMIT ' . $limit;
	}
	
	$sql = sprintf("SELECT tag,COUNT(*) AS cnt,id FROM %s WHERE 1%s GROUP BY tag ORDER BY cnt DESC,tag ASC%s", 
		TAGS_DBTABLE, $extSql, $limitSql);
	$res = $ci->db->query($sql);
	
	$tags_ct = array();
	$tags = array();
	if ($res->num_rows()) {
		foreach ($res->result_array() as $idx => $t) {
			$tags[] = $t;
			$tags_ct[$t['tag']] = $t['cnt'];
		}
	}
	$res->free_result();
	
	shuffle($tags);
	
	$data = array('ref_id' => $ref_id, 'tagUrl' => $tagUrl, 
		'tags' => $tags, 'tags_ct' => $tags_ct, 'canDelete' => (is_admin() && $canDelete), 'label' => $label);
	echo $ci->load->view('common/tags', $data);
}

$ci =& get_instance();
$ci->loadJS('tags');
?>