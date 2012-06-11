<?php
class RGNK_Model extends CI_Model {
	var $dbTable = null;
	var $idField = 'id';
	var $refType = null;
	
	function RGNK_Model() {
		parent::__construct();

		//$this->ci =& get_instance();
		
		$model_name = str_replace('_model', '', get_class($this));
		if (isset($this->RefTypes->byModel[$model_name])) {
			$this->refType = $this->RefTypes->byModel[$model_name];
		}
	}
	
	function add($data, $mutate = true) {
		if ($mutate) {
			if (isset($data['password'])) {
				$data['password'] = $this->encrypt->encode($data['password']);
			}
			
			if (isset($data['stub'])) {
				$data['stub'] = stubify($data['stub']);
			}
		}
	
		$this->db->insert($this->dbTable, $data);
		$result = $this->db->insert_id();
		
		return $result;
	}

	function by($id, $by = null) {
		$result = array();
		$sql = sprintf('SELECT * FROM %s WHERE `%s`=%d', $this->dbTable,
			$this->idField, $id);
		
		if (is_null($by)) {
			$by = $this->idField;
		}
			
		if (is_array($id)) {
			$sql = sprintf('SELECT * FROM %s WHERE `%s` IN (%s)', $this->dbTable, $by, implode(',', $id));
		} else if (is_numeric($id)) {
			$sql = sprintf('SELECT * FROM %s WHERE `%s`=%d', $this->dbTable, $by, mysql_escape_string($id));
		} else {
			$sql = sprintf("SELECT * FROM %s WHERE %s='%s'", $this->dbTable, $by, mysql_escape_string($id));
		}
		$res = $this->db->query($sql);
		$nr = $res->num_rows();
		
		if ($nr == 1) {
			$result = $res->row_array();
		} elseif ($nr > 1) {
			$result = $res->result_array();
		} else {
			$result = FALSE;
		}
		
		$res->free_result();
		return $result;
	}

	function get($w = null, $o = null) {
		$result = array();
		
		$extSql = '';
		
		if (!is_null($w)) {
			$extSql .= ' WHERE ' . $w;
		}
		
		if (!is_null($o)) {
			$extSql .= ' ORDER BY ' . $o;
		}
		$sql = sprintf("SELECT * FROM %s%s", $this->dbTable, $extSql);
		$res = $this->db->query($sql);
		
		if ($res->num_rows()) {
			foreach ($res->result_array() as $idx => $r) {
				$result[] = $r;
			}
		}
		$res->free_result();
		
		return $result;
	}
	
	function getList($page = 1, $itemsPerPage = 50, $orderBy = null, $extWhere = null) {
		$result = array('items' => array(), 'total_items' => 0, 'total_pages' => 0, 'page' => $page);
		
		$extWhereSql = '';
		$orderBySql = ' ORDER BY ' . $this->idField . ' DESC';
		if (!is_null($orderBy)) {
			$orderBySql = ' ORDER BY ' . $orderBy;
		}
		
		if (!is_null($extWhere)) {
			$extWhereSql = ' WHERE ' . $extWhere;
		}
		
		$sql = sprintf('SELECT * FROM %s AS dbt%s GROUP BY dbt.%s%s', $this->dbTable, $extWhereSql, $this->idField, $orderBySql);
		$res = $this->db->query($sql);
		$result['total_items'] = $res->num_rows();
		$result['total_pages'] = ceil($result['total_items']/$itemsPerPage);
		$offset = (($page-1)*$itemsPerPage);
		
		$res->free_result();

		if ($result['total_items']) {
			$res = $this->db->query($sql . ' LIMIT ' . $offset . ',' . $itemsPerPage);
			$result['items'] = $res->result_array();
			$res->free_result();
		}
		
		return $result;
	}
	
	function byId($id) {
		return $this->by($id, $this->idField);
	}
	
	function delete($id, $by = null) {
		$result = FALSE;
		
		if (!empty($id)) {
			if (is_null($by)) {
				$by = $this->idField;
			}
			
			if (is_array($id)) {
				$sql = sprintf('DELETE FROM %s WHERE `%s` IN (%s)', $this->dbTable, $by, implode(',', $id));
			} else if (is_numeric($id)) {
				$sql = sprintf('DELETE FROM %s WHERE `%s`=%d', $this->dbTable, $by, mysql_escape_string($id));
			} else {
				$sql = sprintf("DELETE FROM %s WHERE `%s`='%s'", $this->dbTable, $by, mysql_escape_string($id));
			}
			
			$this->db->query($sql);
			$result = $this->db->affected_rows();
		}
		
		return $result;
	}
	
	function update($data, $id, $by = null, $mutate = true) {
		if (is_null($by)) { $by = $this->idField; }
		
		if ($mutate) {
			if (isset($data['password'])) {
				$data['password'] = $this->encrypt->encode($data['password']);
			}
			
			if (isset($data['stub'])) {
				$data['stub'] = stubify($data['stub']);
			}
		}
		
		if (is_array($id)) {
			$this->db->update($this->dbTable, $data, $id);	
		} else {
			$this->db->update($this->dbTable, $data, array($by => $id));
		}
		
		return $this->db->affected_rows();
	}
}
?>
