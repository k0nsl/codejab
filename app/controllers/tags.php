<?php
class Tags extends RGNK_Controller {
	function Tags() {
		parent::RGNK_Controller();
	}
	
	function index() {
		redirect('/');
	}
	
	function delete($id) {
		$sql = sprintf('SELECT * FROM %s WHERE id=%d', TAGS_DBTABLE, $id);
		$res = $this->db->query($sql);
		
		$result = 0;
		if ($res->num_rows() > 0) {
			$tmp = $res->row_array();
			
			if (($tmp['user_id'] == $this->currentUser['id']) || is_admin()) {
				$this->db->delete(TAGS_DBTABLE, array('id' => $id));
				$result = 1;
			}
		}
		$res->free_result();
		
		echo $result;
		exit;
	}
}
?>