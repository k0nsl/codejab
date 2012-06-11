<?php
class Categories_model extends RGNK_Model {
	public $byId = array();
	public $byName = array();
	
	function Categories_model() {
		$this->dbTable = CATEGORIES_DBTABLE;
			
		parent::RGNK_Model();
		$ci =& get_instance();
		$numFavs = array();

		if (($usr = $ci->Users->getCurrentUser()) !== FALSE) {
			$sql = sprintf("SELECT COUNT(id) AS numFavs,category_id FROM %s WHERE id IN (SELECT code_id FROM %s WHERE user_id=%d) GROUP BY category_id",
				CODE_DBTABLE, FAVORITES_DBTABLE, $usr['id']);
			$res = $this->db->query($sql);
			if ($res->num_rows()) {
				foreach ($res->result_array() as $idx => $r) {
					$numFavs[$r['category_id']] = $r['numFavs'];
				}
			}
			$res->free_result();
		}
		
		$sql = sprintf("SELECT cat.*,COUNT(c.id) AS numSnippets FROM %s AS cat LEFT JOIN %s AS c ON c.category_id=cat.id GROUP BY cat.id ORDER BY cat.name ASC",
			$this->dbTable, CODE_DBTABLE);
			
		$res = $this->db->query($sql);
		
		if ($res->num_rows()) {
			foreach ($res->result_array() as $idx => $c) {
				if (isset($numFavs[$c['id']])) {
					$c['numFavs'] = $numFavs[$c['id']];
				} else {
					$c['numFavs'] = 0;
				}
				$this->byId[$c['id']] = $c;
				$this->byName[strtolower($c['name'])] = $c;
			}
		}
		
		$res->free_result();
	}
}
?>