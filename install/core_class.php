<?php

class Core {

	function validate_post($data)
	{
		$counter = 0;

		if(isset($data['hostname']) AND !empty($data['hostname'])) {
			$counter++;
		}
		if(isset($data['username']) AND !empty($data['username'])) {
			$counter++;
		}
		if(isset($data['password']) AND !empty($data['password'])) {
		}
		if(isset($data['database']) AND !empty($data['database'])) {
			$counter++;
		}

		if($counter == '3') {
			return true;
		}
		else {
			return false;
		}
	}

	function show_message($type,$message) {
		return $message;
	}

	function write_config($data) {

		$template_path 	= 'database.php';
		$output_path 	= '../app/config/database.php';

		$database_file = file_get_contents($template_path);

		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);

		$handle = fopen($output_path,'w+');

		@chmod($output_path,0777);

		if(is_writable($output_path)) {

			if(fwrite($handle,$new)) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
}
