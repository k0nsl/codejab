<?php 
class Categories extends RGNK_Admin_Controller {
	function Categories() {
		parent::RGNK_Admin_Controller();
		
		$this->dbModel = $this->Categories;

		$this->addFields = array('name' => '');
		$this->editFields = array('name' => '');

		$this->singularNoun = 'category';
		$this->pluralNoun = 'categories';
		$this->tplData['PAGE_TITLE'] = 'Manage Categories';
	}
	
	function _getEditData($item) {
		$res = parent::_getEditData($item);
		
		return $res;
	}
	
	function _prepAddData($flds)  {
		$flds = parent::_prepAddData($flds);
		
		return $flds;
	}
	
	function _prepEditData($flds) {
		$flds = parent::_prepEditData($flds);
		
		return $flds;
	}
	
	function _validateAddData($flds) {
		$errors = array();
		
		if (empty($flds['name'])) {
			$errors[] = 'Empty name';
		}

		return $errors;
	}
	
	function _validateEditData($flds) {
		$errors = array();
		
		if (empty($flds['name'])) {
			$errors[] = 'Empty name';
		}
		
		return $errors;
	}
}
?>