<?php
class Code extends RGNK_Controller {
	function Code() {
		parent::RGNK_Controller();
		$this->load->model('code_model', 'Code');
		
		$d = opendir(dirname(dirname(__FILE__)) . '/third_party/geshi/geshi/');
		
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
	
	function index($page = null) {
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		
		$extWhere = '(expires=0 OR expires <=' . time() .')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$this->render_page('code/index', $data);
	}
	
	function page($page = null) {
		return $this->index($page);
	}
	
	function embed($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		require_once dirname(dirname(__FILE__)) . '/third_party/geshi/geshi.php';
		$geshi = new GeSHi($snippet['code'], $snippet['hl_lang']);

		$geshi->set_header_type(GESHI_HEADER_DIV);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		$snippet['code'] = $geshi->parse_code();
		
		$data = array('snippet' => $snippet);
		header('Content-Type: text/javascript');
		$tmp = explode("\n", $snippet['code']);
		
		echo 'document.write("<link rel=\'stylesheet\' type=\'text/css\' href=\'' . site_url() . 'css/embed.css\'><div id=\'theCode\'>");';
		foreach ($tmp as $idx => $l) {
			echo 'document.write(\'' . str_replace("'", '"', $l) . '\' + "\n");' . "\n";
		}
		echo 'document.write("</div>");';
		echo 'document.write(\'<div id="fromLink"><a href="' . site_url() . '" target="_BLANK">' . EMBED_TAGLINE . '</a></div>\')';
		exit;
//		$this->render_page('code/v', $data);
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
		
		$extWhere = 'category_id=' . $category['id'] . ' AND (expires=0 OR expires <=' . time() .')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['category'] = $category;
		$this->render_page('code/c', $data);
	}
	
	function s($term = null, $page = null) {
		if (is_null($term)) {
			$term = $this->input->get_post('searchTerm');
		}
		
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		$extWhere = '(`title` LIKE \'%' . $term . '%\' OR `desc` LIKE \'%' . $term . '%\' OR id IN (SELECT id FROM tags WHERE tag LIKE \'%' . $term . '%\')) AND (expires=0 OR expires <=' . time() .')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['searchTerm'] = $term;
		$this->render_page('code/s', $data);
	}	
	
	function t($tag = null, $page = null) {
		if (is_null($tag)) {
			$tag = $this->input->get_post('tag');
		}
		
		$tag = str_replace('-', ' ', $tag);
		
		if (is_null($page) || !is_numeric($page) || empty($page)) {
			$page = 1;
		}
		$extWhere = 'id IN (SELECT ref_id FROM ' . TAGS_DBTABLE . ' WHERE tag LIKE \'' . $tag . '\') AND (expires=0 OR expires <=' . time() .')';
		
		$data = $this->Code->getList($page, ITEMS_PER_PAGE, 'ts DESC', $extWhere);
		$data['tag'] = $tag;
		$this->render_page('code/t', $data);
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
		$this->render_page('code/v', $data);
	}
	
	function d($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		$this->load->helper('download');
		force_download($snippet['stub'] . '.' . $snippet['file_ext'], $snippet['code']);
		exit;
	}
	
	function dpdf($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		$this->load->helper('pdf');
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="' . $snippet['stub'] . '.pdf"');
		
/* 		$geshi = new GeSHi($snippet['code'], $snippet['hl_lang']); */

//		$geshi->set_header_type(GESHI_HEADER_DIV);
//		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		//$snippet['code'] = $geshi->parse_code();
		
		pdf_create('<pre>' . htmlentities($snippet['code']) . '</pre>', $snippet['stub'] . '.pdf');
		//force_download($snippet['stub'] . '.' . $snippet['file_ext'], $snippet['code']);
		exit;
	}
	
	function dzip($stub) {
		if (($snippet = $this->Code->by(str_replace('.html', '', strtolower($stub)), 'LOWER(stub)')) == FALSE) {
			redirect('/');
		}
		
		$this->load->library('zip');

		$this->zip->add_data($snippet['stub'] . '.' . $snippet['file_ext'], $snippet['code']);
		$this->zip->download($snippet['stub'] . '.zip');
		exit;
	}
	
	function azip() {
		$this->load->library('zip');

		$sql = sprintf('SELECT * FROM %s WHERE (expires=0 OR expires <= %d)', CODE_DBTABLE, time());
		$res = $this->db->query($sql);
		
		foreach ($res->result_array() as $snippet) {
			$this->zip->add_data($snippet['stub'] . '.' . $snippet['file_ext'], $snippet['code']);
		}
		
		$this->zip->download(ALLCODE_ZIP_NAME . '.zip');
		exit;
	}
	
	function submit() {
		$errors = array();
		$do = $this->input->get_post('do');
		$title = $this->input->get_post('title');
		$category_id = $this->input->get_post('category_id');
		$hl_lang = $this->input->get_post('hl_lang');
		$file_ext = $this->input->get_post('file_ext');
		$desc = $this->input->get_post('desc');
		$code = $this->input->get_post('code');
		$tags = $this->input->get_post('tags');
		
		if (!strcmp($do, 'submit')) {
			if (empty($title)) {
				$errors[] = 'You must supply a title for this snippet';
			}
			
			if (empty($file_ext)) {
				$errors[] = 'You must supply a file extension for downloading';
			}
			
			if (empty($desc)) {
				$errors[] = 'You must provide a description for this snippet';
			}
			
			if (empty($code)) {
				$errors[] = 'You must submit code!';
			}
			
			if (empty($errors)) {
				$set = array(
					'user_id' => $this->currentUser['id'],
					'category_id' => $category_id,
					'stub' => stubify($title),
					'title' => mysql_escape_string($title),
					'desc' => mysql_escape_string($desc),
					'code' => mysql_escape_string($code),
					'ts' => time(),
					'hl_lang' => $hl_lang,
					'file_ext' => $file_ext,
					'expires' => 0
				);
				
				if ($this->db->insert(CODE_DBTABLE, $set)) {
					$code_id = $this->db->insert_id();
					
					$tags = trim($tags);
					$tmp_tags = explode(',', $tags);
					
					if (!empty($tmp_tags)) {
						foreach ($tmp_tags as $idx => $t) {
							$t = trim($t);
							
							if (!empty($t)) {
								tag_object($code_id, $t);
							}
						}
					}
					// add to favs
				} else {
					$errors[] = 'Error adding code';
				}
			}
		}
		
		$data = array(
			'errors' => $errors, 'title' => $title, 'category_id' => $category_id, 'hl_lang' => $hl_lang, 
			'file_ext' => $file_ext, 'desc' => $desc, 'code' => $code, 'tags' => $tags
		);
		$this->render_page('code/submit', $data);
	}
}
?>