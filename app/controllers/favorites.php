<?php
class Favorites extends RGNK_Controller {
	function Favorites() {
		parent::RGNK_Controller();
		$this->load->model('code_model', 'Code');
	}
	
	function index($page = null) {
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		
		$extWhere =  'id IN (SELECT code_id FROM ' . FAVORITES_DBTABLE . ' WHERE user_id=' . $this->currentUser['id'] . ')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$this->render_page('favorites/index', $data);
	}
	
	function page($page = null) {
		return $this->index($page);
	}
	
	function c($stub, $page = null) {
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		
		if (!isset($this->Categories->byName[strtolower($stub)])) {
			redirect('/');
		} else {
			$category = $this->Categories->byName[strtolower($stub)];
		}
		
		$extWhere = 'category_id=' . $category['id'] . ' AND id IN (SELECT code_id FROM ' . FAVORITES_DBTABLE . ' WHERE user_id=' . $this->currentUser['id'] . ')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['category'] = $category;
		$this->render_page('favorites/c', $data);
	}
	
	function s($term = null, $page = null) {
		if (is_null($term)) {
			$term = $this->input->get_post('searchTerm');
		}
		
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		$extWhere = '(`title` LIKE \'%' . $term . '%\' OR `desc` LIKE \'%' . $term . '%\' OR id IN (SELECT id FROM tags WHERE tag LIKE \'%' . $term . '%\')) AND id IN (SELECT code_id FROM ' . FAVORITES_DBTABLE . ' WHERE user_id=' . $this->currentUser['id'] . ')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['searchTerm'] = $term;
		$this->render_page('favorites/s', $data);
	}	
	
	function t($tag = null, $page = null) {
		if (is_null($tag)) {
			$tag = $this->input->get_post('tag');
		}
		
		$tag = str_replace('-', ' ', $tag);
		
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		$extWhere = 'id IN (SELECT ref_id FROM ' . TAGS_DBTABLE . ' WHERE tag LIKE \'' . $tag . '\') AND id IN (SELECT code_id FROM ' . FAVORITES_DBTABLE . ' WHERE user_id=' . $this->currentUser['id'] . ')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['tag'] = $tag;
		$this->render_page('favorites/t', $data);
	}
	
	function v($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		require_once dirname(dirname(__FILE__)) . '/third_party/geshi/geshi.php';
		$geshi = new GeSHi($snippet['code'], $snippet['hl_lang']);

		$geshi->set_header_type(GESHI_HEADER_DIV);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		$snippet['code'] = $geshi->parse_code();
		
		$data = array('snippet' => $snippet);
		$this->render_page('favorites/v', $data);
	}
	
	function d($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		$this->load->helper('download');
		force_download($snippet['stub'] . '.' . $snippet['file_ext'], $snippet['code']);
		exit;
	}
	
	function toggle($code_id) {
		if (!empty($this->currentUser)) {
			$result = 'Remove From Favorites';
			
			$sql = sprintf("SELECT * FROM %s WHERE code_id=%d AND user_id=%d", FAVORITES_DBTABLE, $code_id, $this->currentUser['id']);
			$res = $this->db->query($sql);
			
			if ($res->num_rows()) {
				$tmp = $res->row_array();
				
				$sql = sprintf("DELETE FROM %s WHERE id=%d", FAVORITES_DBTABLE, $tmp['id']);
				$this->db->query($sql);
				
				$result = 'Add To Favorites';
			} else {
				$set = array(
					'code_id' => $code_id,
					'user_id' => $this->currentUser['id']
				);
				
				$this->db->insert(FAVORITES_DBTABLE, $set);
			}
			
			echo $result;
			exit;
		} 
		
		echo 0;
		exit;
	}
}
?>