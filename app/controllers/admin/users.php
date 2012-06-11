<?php 
class Users extends RGNK_Admin_Controller {
	function Users() {
		parent::RGNK_Admin_Controller();
		
		$this->dbModel = $this->Users;

		$this->isTaggable = false;		
		$this->addFields = array('username' => '', 'email' => '', 'password' => '', 'password2' => '', 'role' => 0, 'verified' => 0, 'submitter' => 0);
		$this->editFields = array('username' => '', 'email' => '', 'changePass' => 0, 'password' => '', 'password2' => '', 'role' => 0, 'verified' => 0, 'submitter' => 0);

		$this->singularNoun = 'user';
		$this->pluralNoun = 'users';
		$this->tplData['PAGE_TITLE'] = 'Manage Users';
	}
	
	function _getEditData($item) {
		if (isset($item['password'])) {
			$item['password'] = $this->encrypt->decode($item['password']);
			$item['password2'] = $item['password'];
		}
		
		$res = parent::_getEditData($item);
		
		return $res;
	}
	
	function _prepAddData($flds)  {
		$flds = parent::_prepAddData($flds);
		
		if (isset($flds['password2'])) {
			unset($flds['password2']);
		}
		
		return $flds;
	}
	
	function _prepEditData($flds) {
		if (isset($flds['password2'])) {
			unset($flds['password2']);
		}
		
		if (isset($flds['changePass'])) {
			if ($flds['changePass'] == 0) {
				if (isset($flds['password'])) {
					unset($flds['password']);
				}
			}
			unset($flds['changePass']);
		}
		
		return $flds;
	}
	
	function _validateAddData($flds) {
		$errors = array();
		
		if (empty($flds['username'])) {
			$errors[] = 'Empty Username';
		}
		
		if (empty($flds['password'])) {
			$errors[] = 'Empty Password';
		} elseif (strcmp($flds['password'], $flds['password2'])) {
			$errors[] = 'Passwords dont match';
		}
		
		if (empty($flds['email'])) {
			$errors[] = 'Empty Email';
		}
		
		return $errors;
	}
	
	function _validateEditData($flds) {
		$errors = array();
		
		if (empty($flds['username'])) {
			$errors[] = 'Empty Username';
		}
		
		if (empty($flds['password'])) {
			$errors[] = 'Empty Password';
		} elseif (strcmp($flds['password'], $flds['password2'])) {
			$errors[] = 'Passwords dont match';
		}
		
		if (empty($flds['email'])) {
			$errors[] = 'Empty Email';
		}
		
		return $errors;
	}
}
?>