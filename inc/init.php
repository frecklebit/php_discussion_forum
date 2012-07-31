<?

// define site directories
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('PUBLIC_ROOT') ? null : define('PUBLIC_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'medicine.missouri.edu');

defined('CORE_PATH') ? null : define('CORE_PATH', PUBLIC_ROOT.DS.'includes'.DS.'classes');

// read yaml and define consts
$config = 'inc/config.yaml';
if(file_exists($config)){
	$lines = file($config);
	for ($i=0;$i<count($lines);$i++) {
		$options = explode(": ", $lines[$i]);
		defined("{$options[0]}") ? null : define("{$options[0]}", "{$options[1]}");
	}
}

defined('SITE_ROOT') ? null : define('SITE_ROOT', PUBLIC_ROOT.SITE_ROOT_BASE);

$DBNAME = DBNAME;

// load functions
require_once(CORE_PATH.DS.'functions.php');

// load core objects
require_once(CORE_PATH.DS.'Database.php');
require_once(CORE_PATH.DS.'QueryBuilder.php');
require_once(CORE_PATH.DS.'Session.php');
require_once(CORE_PATH.DS.'Department.php');
require_once(CORE_PATH.DS.'SaltPassword.php');
require_once(CORE_PATH.DS.'Group.php');
require_once(CORE_PATH.DS.'User.php');
require_once(CORE_PATH.DS.'Content.php');
require_once(CORE_PATH.DS.'Validation.php');
require_once(CORE_PATH.DS.'EmailNotification.php');
require_once(CORE_PATH.DS.'Uploader.php');
require_once(CORE_PATH.DS.'Emoticonize.php');

// load site specific objects

?>