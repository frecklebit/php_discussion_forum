<?php
require_once('inc/init.php');

$u = ($_GET['u']) ? $_GET['u'] : null;
$site = base64_encode(SITE_NAME);
$redirect = base64_encode(urlencode('http://medicine.missouri.edu/'.SITE_ROOT_BASE));
$action = $_GET['a'];
redirect_to("/admin/$action.php?u=$u&site=$site&r=$redirect");

?>