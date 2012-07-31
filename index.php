<?
	require_once('inc/init.php');
	// check if system installed
	if(!defined('DBNAME')) redirect_to('install.php');
	// check if user authenticated
	if(!$session->is_logged_in()) redirect_to('login.php');
?>
<?php require_once('inc/template.head.php') ?>

	<div id="forum">
		<noscript>
			JAVASCRIPT IS REQUIRED <br />
			For full functionality of this site it is necessary to enable JavaScript. Here are the <a href="http://www.enable-javascript.com/" target="_blank">instructions how to enable JavaScript in your web browser</a>.
			<style type="text/css">
				#content {display: none;}
			</style>
		</noscript>
		<div id="user">
			<h1><? echo SITE_NAME; ?></h1>
			<strong>Welcome back, <? echo $session->user()->username ?></strong>
			<strong><a href="logout.php">Logout</a></strong>
		</div>
		<div id="content">
				<?
					$inc = ($_GET['q']) ? $_GET['q'] : 'overview';
					require_once("layouts/$inc.php");
				?>
			
		</div><!-- #content -->
	</div><!-- #wrapper -->

<?php require_once('inc/template.foot.php') ?>