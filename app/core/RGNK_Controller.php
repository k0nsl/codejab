<?php
class RGNK_Controller extends CI_Controller {
	private $_skipSessionInit = false;
	private $cssFiles = array('jqui' => 'smoothness/jquery-ui-1.8.6.custom', 'rgnk' => 'main');
	private $jsFiles = array('jquery' => 'jquery', 'jquery.ui' => 'jquery-ui-1.8.6.custom.min', 'rgnk' => 'main', 'jquery.form' => 'jquery.form', 'jqprint' => 'jquery.jqprint', 'jquery.timepicker' => 'jquery.timepicker');
	private $inAdmin = false;
	protected $tplData = array();
	public $currentUser = null;
	public $requireAuth = false;
	public $tplBase = '';
	
	function RGNK_Controller() {
		parent::__construct();
		
		$this->load->library('session');
		$this->load->model('users_model', 'Users');
		$this->load->model('categories_model', 'Categories');		
		
		if (isset($this->uri->segments[1])) {
			if (strtolower($this->uri->segments[1]) == 'admin') {
				$this->inAdmin = true;
			}
		}
		
		if (!isset($this->uri->rsegments[1])) {
			$this->uri->rsegments[1] = strtolower(get_class($this));
		}
		
		if (!isset($this->uri->rsegments[2])) {
			$this->uri->rsegments[2] = $this->input->get('m');
		}
		
		define('CURRENT_CONTROLLER', $this->uri->rsegments[1]);
		define('CURRENT_METHOD', $this->uri->rsegments[2]);
		define('CURRENT_PATH', CURRENT_CONTROLLER . '/' . CURRENT_METHOD);
		define('CURRENT_URL', base_url() . implode('/', $this->uri->segments));
		
		$prefix = ($this->inAdmin ? 'admin/' : '');
		$this->loadJS($prefix . CURRENT_CONTROLLER . '/' . CURRENT_METHOD);
		
		$this->currentUser = $this->Users->getCurrentUser();
		if (empty($this->currentUser)) {
			$this->currentUser = null;
		}	
		if ($this->requireAuth) {
			$this->requireAuth();
		}
		
		if (strcmp(CURRENT_CONTROLLER, 'favorites')) {
			$sql = sprintf("SELECT * FROM %s", CODE_DBTABLE);
		} else {
			$sql = sprintf("SELECT * FROM %s WHERE id IN (SELECT code_id FROM %s WHERE user_id=%d)", CODE_DBTABLE, FAVORITES_DBTABLE, $this->currentUser['id']);
		}
		$res = $this->db->query($sql);
		
		$this->tplData['totalSnippets'] = $res->num_rows();
		$res->free_result();
	}
	
	function loadCSS($file, $key = null) {
		if (is_null($key)) { $key = md5(microtime()); }
		$this->cssFiles[$key] = $file;
	}
	
	function loadJS($file, $key = null) {
		if (is_null($key)) { $key = md5(microtime()); }
		$this->jsFiles[$key] = $file;
	}

	function unloadCSS($key) {
		if (isset($this->cssFiles[$key])) {
			unset($this->cssFiles[$key]);
		}
	}
	
	function requireAuth() {
		if (is_null($this->currentUser)) {
			$this->session->set_flashdata('redir', CURRENT_URL);
			redirect('login/');
		}
	}
	
	function unloadJS($key) {
		if (isset($this->jsFiles[$key])) {
			unset($this->jsFiles[$key]);
		}
	}
	
	function render_page($tpl, $data = array()) {
		$this->tplData['CSS_FILES'] = $this->cssFiles;
		$this->tplData['JS_FILES'] = $this->jsFiles;
		
		$this->load->view($this->tplBase . $tpl, array_merge($this->tplData, $data));
	}
}

class RGNK_Admin_Controller extends RGNK_Controller {
	public $dbModel = null;
	protected $addFields = array();
	protected $editFields = array();

	public $singularNoun = 'item';
	public $pluralNoun = 'items';
	public $tplBase = 'admin/';
	
	function RGNK_Admin_Controller() {
		$this->requireAuth = true;
		
		parent::RGNK_Controller();
		
		$this->loadCSS('admin/rgnk', 'rgnk');
		$this->loadJS('admin/rgnk', 'rgnk');
	}

	function _getAddData() {
		$result = array();
		
		foreach ($this->addFields as $key => $default) {
			if (($tmp = $this->input->get_post($key)) === FALSE) {
				$tmp = $default;
			}
			
			$result[$key] = $tmp;
		}
		
		return $result;
	}
	
	function _getEditData($item) {
		$result = array();
		
		foreach ($this->editFields as $key => $default) {
			if (($tmp = $this->input->get_post($key)) == FALSE) {
				if (isset($item[$key])) {
					$tmp = $item[$key];
				}
			}
			
			if ($tmp === FALSE) {
				$tmp = $default;
			}
			
			$result[$key] = $tmp;
		}
		
		return $result;
	}

	function _postAdd($id) {
		return true;
	}
	
	function _postEdit($id) {
		return true;
	}
	
	function _prepAddData($item) {
		return $item;
	}
	
	function _prepEditData($item) {
		if (isset($item['id'])) {
			unset($item['id']);
		}
		
		return $item;
	}
	
	function _validateAddData($d) {
		$errors = array();
		
		return $errors;
	}
	
	function _validateEditData($d) {
		$errors = array();
		
		return $errors;
	}
	
	function add() {
		$errors = array();
		
		$do = $this->input->get_post('do');

		$fields = $this->_getAddData();
		
		if (!strcmp($do, 'add')) {
			$errors = $this->_validateAddData($fields);
			
			if (empty($errors)) {
				$dbFields = $this->_prepAddData($fields);
				if (($itemId = $this->dbModel->add($dbFields)) !== FALSE) {
					$this->_postAdd($itemId);
					$this->session->set_flashdata('g_Feedback', 'Added ' . $this->singularNoun);
					redirect('admin/' . CURRENT_CONTROLLER);
				} else {
					$errors[] = 'Error adding ' . $this->singularNoun;
				}
			}
		}
		
		$data = array_merge(array('errors' => $errors), $fields);
		$this->render_page(CURRENT_CONTROLLER . '/add', $data);
	}
	
	function edit($id) {
		$errors = array();
		
		if (($item = $this->dbModel->byId($id)) == FALSE) {
			$this->session->set_flashdata('b_Feedback', 'Invalid ' . $this->singularNoun . ' ID');
			redirect('admin/' . CURRENT_CONTROLLER . '/');
		}
		
		$do = $this->input->get_post('do');

		$fields = $this->_getEditData($item);
		
		if (!strcmp($do, 'update')) {
			$errors = $this->_validateEditData($fields);
			
			if (empty($errors)) {
				$dbFields = $this->_prepEditData($fields);
				$numUpdated = $this->dbModel->update($dbFields, $id);

				$this->_postEdit($id);
				
				$this->session->set_flashdata('g_Feedback', 'Updated ' . $this->singularNoun);
				redirect('admin/' . CURRENT_CONTROLLER);
			}
		}
		
		$data = array_merge(array('errors' => $errors, 'id' => $id), $fields);
		$this->render_page(CURRENT_CONTROLLER . '/edit', $data);
	}
	
	function index($page = 1) {
		$orderBy = null;
		$tmp_o = $this->input->get_post('o');
		
		if (!empty($tmp_o)) {
			$orderBy = $tmp_o;
		} else {
			$orderBy = 'id DESC';
		}
		
		$data = array('items' => array(), 'total_items' => 0, 'total_pages' => 0, 'page' => $page);
		
		if (!is_null($this->dbModel)) {
			$data = $this->dbModel->getList($page, ADMIN_ITEMS_PER_PAGE, $orderBy);
		}
		
		$data['o'] = $orderBy;
		
		$this->render_page(CURRENT_CONTROLLER . '/index', $data);
	}
	
	function requireAuth() {
		if (is_null($this->currentUser)) {
			$this->session->set_flashdata('redir', CURRENT_URL);
			redirect('login/');
		} else {
			if ($this->currentUser['role'] != ADMIN_ROLE) {
				$this->session->set_flashdata('redir', CURRENT_URL);
				redirect('login/');
			}
		}
	}
	
	function delete() {
		if (!is_null($this->dbModel)) {
			$items = $this->input->get_post('items');
			$numDeleted = 0;
			if (!empty($items)) {
				$numDeleted = $this->dbModel->delete($items);
			}
			$this->session->set_flashdata('g_Feedback', $numDeleted . ' ' . (($numDeleted <> 1) ? $this->pluralNoun : $this->singularNoun) . ' deleted');
		} else {
			$this->session->set_flashdata('b_Feedback', 'No DB Model');
		}
		
		redirect('admin/' . CURRENT_CONTROLLER);
	}
}
?>
