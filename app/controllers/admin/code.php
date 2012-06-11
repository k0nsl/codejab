<?php 
class Code extends RGNK_Admin_Controller {
	function Code() {
		parent::RGNK_Admin_Controller();

		$this->load->model('code_model', 'Code');
		$this->load->model('categories_model', 'Categories');
		
		$this->dbModel = $this->Code;

		$this->addFields = array('category_id' => 0, 'title' => '', 'desc' => '', 'code' => '', 'hl_lang' => '', 'file_ext' => 'txt', 'tags' => '', 'uploadCode' => 0, 'doesExpire' => 0, 'expires' => date('m/d/Y', strtotime('+1 month')));
		$this->editFields = array('category_id' => 0, 'title' => '', 'desc' => '', 'code' => '', 'hl_lang' => '', 'file_ext' => 'txt', 'tags' => '', 'uploadCode' => 0, 'doesExpire' => 0, 'expires' => date('m/d/Y', strtotime('+1 month')));

		$this->singularNoun = 'snippet';
		$this->pluralNoun = 'snippets';
		$this->tplData['PAGE_TITLE'] = 'Manage Code Snippets';
		
		$d = opendir(dirname(dirname(dirname(__FILE__))) . '/third_party/geshi/geshi/');
		
		$highlighters = array();
		if ($d) {
			while (($f = readdir($d)) !== FALSE) {
				if (strcmp($f, '.') && strcmp($f, '..')) {
					$highlighters[] = str_replace('.php', '', $f);
				}
			}
			closedir($d);
		}
		
		$this->tplData['highlighters'] = $highlighters;
	}
	
	function _getAddData() {
		$res = parent::_getAddData();
		
		if ($res['expires'] <= 0) {
			$res['expires'] = date('m/d/Y H:i:s', strtotime("+1 month"));
		} else {
			if (is_numeric($res['expires']) && !empty($res['expires'])) {
				$res['expires'] = date('m/d/Y H:i:s', $res['expires']);
			} else {
//				$res['expires'] = date('m/d/Y', strtotime("+1 month"));
			}
		}
		
		return $res;
	}
	
	function _getEditData($item) {
		$res = parent::_getEditData($item);

		if (($this->input->get_post('doesExpire') == 0) && ($this->input->get_post('expires') !== FALSE)) {
			$res['expires'] = 0;
		} else {
			if ($res['expires'] > 0) {
				$res['doesExpire'] = 1;
				if (!is_numeric($res['expires'])) {
					$res['expires'] = date('m/d/Y H:i:s', strtotime($res['expires']));
				} else {
					$res['expires'] = date('m/d/Y H:i:s', $res['expires']);
				}
			} else {
				$res['expires'] = date('m/d/Y H:i:s', strtotime('+1 month'));
			}
		}
		
		return $res;
	}
	
	function _prepAddData($flds)  {
		$flds = parent::_prepAddData($flds);
		
		$flds['user_id'] = $this->currentUser['id'];
		$flds['ts'] = time();
		$flds['stub'] = stubify(strtolower($flds['title']));
		
		if ($flds['uploadCode']) {
			$f = $_FILES['src'];
			
			if ($f['error'] === UPLOAD_ERR_OK) {
				$flds['code'] = file_get_contents($f['tmp_name']);
			}
		}
		
		if ($flds['doesExpire'] == 1) {
			$flds['expires'] = strtotime($flds['expires']);
		} else {
			$flds['expires'] = 0;
		}
		
		unset($flds['doesExpire']);
		unset($flds['tags']);
		unset($flds['uploadCode']);
		
//		echo '<pre>' . print_r($_FILES, true) . '</pre><pre>' . print_r($flds, true) . '</pre>';
//		exit;
		
		return $flds;
	}
	
	function _prepEditData($flds) {
		$flds = parent::_prepEditData($flds);
		$flds['stub'] = stubify(strtolower($flds['title']));
		
		if ($flds['uploadCode']) {
			$f = $_FILES['src'];
			
			if ($f['error'] === UPLOAD_ERR_OK) {
				$flds['code'] = file_get_contents($f['tmp_name']);
			}
		}
		
		if ($flds['doesExpire'] == 1) {
//			print_r($flds);
//			exit;
			$flds['expires'] = strtotime($flds['expires']);
		} else {
			$flds['expires'] = 0;
		}
		
		unset($flds['doesExpire']);
		unset($flds['tags']);
		unset($flds['uploadCode']);
		
		return $flds;
	}
	
	function _postAdd($id) {
		$tags = explode(',', $this->input->get_post('tags'));
		
		if (!empty($tags)) {
			foreach ($tags as $idx => $t) {
				$t = trim($t);
				if (!empty($t)) {
					tag_object($id, $t);
				}
			}
		}
	}
	
	function _postEdit($id) {
		$tags = explode(',', $this->input->get_post('tags'));
		
		if (!empty($tags)) {
			foreach ($tags as $idx => $t) {
				tag_object($id, trim($t));
			}
		}
	}
	
	function _validateAddData($flds) {
		$errors = array();
		
		if (empty($flds['title'])) {
			$errors[] = 'Empty title';
		}
		
		if (empty($flds['code']) && !$flds['uploadCode']) {
			$errors[] = 'Empty code snippet';
		}
		
		if (empty($flds['file_ext'])) {
			$errors[] = 'You must select a file extension for downloading the code';
		}

		return $errors;
	}
	
	function _validateEditData($flds) {
		$errors = array();
		
		if (empty($flds['title'])) {
			$errors[] = 'Empty title';
		}
		
		if (empty($flds['code'])) {
			$errors[] = 'Empty code snippet';
		}
		
		if (empty($flds['file_ext'])) {
			$errors[] = 'You must select a file extension for downloading the code';
		}
		
		return $errors;
	}
}
?>