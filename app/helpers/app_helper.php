<?php 
function form_errors($errors, $return = false) {
	$s = '';
	
	if (!empty($errors)) {
		foreach ($errors as $e) {
			$s .= '<p class="error">' . $e . '</p>';
		}
	}
	
	if (!$return) {
		echo $s;
	} else {
		return $s;
	}
}

function is_favorite($code_id) {
	$ci =& get_instance();
	
	$result = false;
	
	if (!empty($ci->currentUser)) {
		$sql = sprintf("SELECT id FROM %s WHERE code_id=%d AND user_id=%d", FAVORITES_DBTABLE, $code_id, $ci->currentUser['id']);
		$res = $ci->db->query($sql);
		
		if ($res->num_rows()) {
			$result = true;
		}
		
		$res->free_result();
	}
	
	return $result;
}

function stubify($s) {
	$find = array('  ', ' ', '!', '#', '@', '$', '%', '^', '&', '*', '(', ')', '/', "'", '"', '?', ',', '.', '!');
	$repl = array(' ', '-', '', '', '', '', '', '', '', '', '', '', '?', '', '', '');
	
	$s = str_replace($find, $repl, strtolower($s));
	return preg_replace("/[^a-zA-Z0-9\-\s]/", "", $s);
}

function tpl_header($params = array()) {
	$ci =& get_instance();
	$ci->render_page('common/header', $params);
}

function tpl_footer($params = array()) {
	$ci = get_instance();
	$ci->render_page('common/footer', $params);
}

function is_admin() {
	$ci =& get_instance();
	$result = false;
	
	if (!is_null($ci->currentUser) && is_array($ci->currentUser)) {
		if ($ci->currentUser['role'] == ADMIN_ROLE) {
			$result = true;
		}
	}
	
	return $result;
}

function is_user() {
	$ci =& get_instance();
	$result = false;
	
	if (!is_null($ci->currentUser) && is_array($ci->currentUser)) {
		$result = true;
	}
	
	return $result;
}

function is_serialized($val){
    if (!is_string($val)){ return false; }
    if (trim($val) == "") { return false; }

    if (preg_match("/^(i|s|a|o|d):(.*);/si",$val) !== false) { return true; }
    return false;
}

function truncate_str($s, $len = 20, $append = '...') {
	if (strlen($s) > $len) {
		$s = substr($s, 0, $len) . $append;
	}
	
	return $s;
}
?>