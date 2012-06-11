<?php 
class RGNK_Config extends CI_Config {
	function __construct() {
		parent::__construct();	
	}
	
	function RGNK_Config() {
		$this->__construct();
	}
	
	function site_url($uri = '')
	{
		if ($uri == '') {
			if ($this->item('base_url') == '') {
				return $this->item('index_page');
			} else {
				return $this->slash_item('base_url').$this->item('index_page');
			}
		}

		if ($this->item('enable_query_strings') == FALSE) {
			if (is_array($uri)) {
				$uri = implode('/', $uri);
			}

			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$this->slash_item('index_page').trim($uri, '/').$suffix;
		} else {
			if (is_array($uri)) {
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val) {
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix.$key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			if ($this->item('base_url') == '') {
				return $this->item('index_page').$uri;//'?'.$uri;
			} else{
				return $this->slash_item('base_url').$this->item('index_page').$uri;//'?'.$uri;
			}
		}
	}
}
?>