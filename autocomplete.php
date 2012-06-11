<?php
define('BASEPATH', dirname(__FILE__) . '/');
require_once dirname(__FILE__) . '/app/config/database.php';
require_once dirname(__FILE__) . '/app/config/app.php';
$myDB = mysql_connect($db[$active_group]['hostname'], $db[$active_group]['username'], $db[$active_group]['password']);
mysql_select_db($db[$active_group]['database'], $myDB);

$term = '';
if (isset($_GET['term'])) {
	$term = $_GET['term'];
}

$items = array();

$sql = "SELECT DISTINCT tag FROM " . TAGS_DBTABLE . " WHERE tag LIKE '" . $term . "%' GROUP BY tag";
$res = mysql_query($sql, $myDB);

while (($r = mysql_fetch_assoc($res)) !== FALSE) {
	$items[] = $r['tag'];
}
mysql_free_result($res);

/* UNCOMMENT IF YOU WANT AUTOCOMPLETE TO USE TITLES TOO
$sql = "SELECT DISTINCT id FROM " . CODE_DBTABLE . " WHERE title LIKE '" . $term . "%' GROUP BY id";
$res = mysql_query($sql, $myDB);

while (($r = mysql_fetch_assoc($res)) !== FALSE) {
	$items[] = $r['title'];
}
mysql_free_result($res);
*/
/* UNCOMMENT IF YOU WANT AUTOCOMPLETE TO USE DESCRIPTIONS TOO
$sql = "SELECT DISTINCT id FROM " . CODE_DBTABLE . " WHERE `desc` LIKE '" . $term . "%' GROUP BY id";
$res = mysql_query($sql, $myDB);

while (($r = mysql_fetch_assoc($res)) !== FALSE) {
	$items[] = $r['desc'];
}
mysql_free_result($res);
*/

mysql_close($myDB);

if (count($items) > 0) {
	echo '[';
	foreach ($items as $idx => $t) {
		if ($idx > 0) {
			echo ',';
		}
		
		echo '{"id": "' . $t . '", "label": "' . $t . '", "value": "' . $t . '"}';
	}
	echo ']';
} else {
	echo '[]';
}
?>