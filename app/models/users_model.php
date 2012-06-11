<?php
class Users_model extends RGNK_Model {
	function Users_model() {
		$this->dbTable = USERS_DBTABLE;
		
		parent::RGNK_Model();
	}
	
	function authenticate($user, $pass, $update_last_login = false) {
		$result = FALSE;
		
		$sql = sprintf("SELECT * FROM %s WHERE `username`='%s'",
			$this->dbTable, mysql_escape_string($user));
		$res = $this->db->query($sql);
		
		if ($res->num_rows()) {
			$usr = $res->row_array();
			
			if ((($usr['role'] == 0) && $usr['verified']) || ($usr['role'] == 1)) {
//			echo $this->encrypt->decode($usr['password']);
			if (!strcmp($pass, $this->encrypt->decode($usr['password']))) {
				$result = $usr;
				$akey = $result['id'] . '@:SPLIT:@' . $result['username'] . '@:SPLIT:@' . $this->encrypt->decode($result['password']);
				$this->session->set_userdata('akey', $this->encrypt->encode($akey));
				if ($update_last_login) {
					$this->update(array('last_login' => time()), $result[$this->idField]);
				}
			}
			}
		}
		$res->free_result();
		
		return $result;
	}
	
	function getCurrentUser() {
		$result = null;
		
		$auth_key = $this->session->userdata('akey');
		
		if (!empty($auth_key)) {
			$auth_key = $this->encrypt->decode($auth_key);
			
			$tmp = explode('@:SPLIT:@', $auth_key);
			
			if (count($tmp) == 3) {
				if (($user = $this->authenticate($tmp[1], $tmp[2], false)) !== FALSE) {
					if ($user['id'] == $tmp[0]) {
						$result = $user;
					}
				} else {
//					echo 'cant auth';
//					exit;
				}
			} else {
//				echo 'invalid  auth key';
//				exit;
			}
		} else {
//			echo 'empty auth key';
//			exit;
		}
		
		return $result;
	}
}
?>
