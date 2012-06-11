<?php
class Code_model extends RGNK_Model {
	function Code_model() {
		$this->dbTable = CODE_DBTABLE;
		
		parent::RGNK_Model();
	}
}
?>