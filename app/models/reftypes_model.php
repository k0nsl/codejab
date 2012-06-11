<?php
class Reftypes_model extends RGNK_Model {
	public $byId;	
	public $byModel;
	public $byName;
	
	function RefTypes_model() {
		$this->dbTable = REFTYPES_DBTABLE;
		
		parent::RGNK_Model();
		
		$this->byId = array();
		$this->byModel = array();
		$this->byName = array();
		
		$sql = sprintf("SELECT * FROM %s", $this->dbTable);
		$res = $this->db->query($sql);
		
		if ($res->num_rows()) {
			foreach ($res->result_array() as $idx => $rt) {
				$this->byId[$rt['id']] = $rt;
				$this->byModel[$rt['model']] = $rt;
				$this->byName[$rt['name']] = $rt;
			}
		}
		$res->free_result();
	}
}
?>