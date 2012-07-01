<?php

error_reporting(E_NONE); 

$db_config_path = '../app/config/database.php';

if($_POST) {

	require_once('core_class.php');
	require_once('database_class.php');

	$core = new Core();
	$database = new Database();


	if($core->validate_post($_POST) == true)
	{

		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod app/config/database.php file to 777");
		}

		if(!isset($message)) {
		  $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
      $redir .= "://".$_SERVER['HTTP_HOST'];
      $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
      $redir = str_replace('install/','install/welcome/',$redir); 
			header( 'Location: ' . $redir  ) ;
		}

	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Install | Codejab</title>

		<style type="text/css">
		  body {
		    font-size: 75%;
		    font-family: Helvetica,Arial,sans-serif;
		    width: 300px;
		    margin: 0 auto;
		  }
		  input, label {
		    display: block;
		    font-size: 18px;
		    margin: 0;
		    padding: 10px;
		    border-radius:10px;
		  }
		  label {
		    margin-top: 20px;
		  }
		  input.input_text {
		    width: 270px;
		  }
		  input#submit {
		    margin: 25px auto 0;
		    font-size: 25px;
		  }
		  fieldset {
		    padding: 15px;
		    border-radius:10px;
		  }
		  legend {
		    font-size: 18px;
		    font-weight: bold;
		  }
		  .error {
		    background: #ffd1d1;
		    border: 1px solid #ff5858;
        padding: 4px;
		  }
		</style>
	</head>
	<body>

    <center><h1>Codejab Installer</h1></center>
    <?php if(is_writable($db_config_path)){?>

		  <?php if(isset($message)) {echo '<p class="error">' . $message . '</p>';}?>

		  <form id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
          <legend>Database settings</legend>
          <label for="hostname">Hostname</label><input type="text" id="hostname" value="localhost" class="input_text" name="hostname" />
          <label for="username">Username</label><input type="text" id="username" class="input_text" name="username" />
          <label for="password">Password</label><input type="password" id="password" class="input_text" name="password" />
          <label for="database">Database Name</label><input type="text" id="database" class="input_text" name="database" />
          <input type="submit" value="Install" id="submit" />
        </fieldset>
		  </form>

	  <?php } else { ?>
      <p class="error">Please make the app/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 app/config/database.php</code></p>
	  <?php } ?>

	</body>
</html>
