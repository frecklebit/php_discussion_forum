<?
require_once('inc/init.php');

if($_POST['submit']){
	// set variables
	$sitename = $_POST['sitename'];
	$siteroot = $_POST['siteroot'];
	$dbname = "forum_".$_POST['dbprefix'];
	$config = 'inc/config.yaml';
	
	// create database
	if($db->query("CREATE DATABASE `$dbname` ;")){
		// connect to new database
		$db->select_database($dbname);
		
		// load sql
		$file = 'inc/forum.php';
		$fh = fopen($file, 'r');
		$sql = fread($fh, filesize($file));
		fclose($fh);
		
		$sql = preg_replace('/\[DBNAME]/m', $dbname, $sql);
		$sqls = explode(';', $sql);
		
		foreach($sqls as $sql){
			if(!empty($sql)){
				$db->query($sql);
			}
		}
		
		// write config.yaml
		$data  = "SITE_NAME: $sitename\r\n";
		$data .= "SITE_ROOT_BASE: $siteroot\r\n";
		$data .= "DBNAME: $dbname";
		if($fh = fopen($config, 'w')) {
			fwrite($fh, $data);
			fclose($fh);
			redirect_to('install.php?success=true');
		}else{ $message = "Could not write config file. Please create file $config and paste this config data:<br /> $data"; }
		
		
		// reset db connection
		$db->reset_connection();

	}else{ $message = "Database could not be created [`$dbname`]"; }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Discussion Forum Installer</title>
		<link rel="stylesheet" type="text/css" media="screen" href="/admin/css/screen.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function(){var val='';$('label').css({color:'#333'});$('input[type=text]').css({color:'#a4a4a4'});$('input[type=text]').focus(function(){if($(this).attr('class')!=='filled'){val=$(this).val();$(this).val('');$(this).css({color:'#333'});$('input[type=text]').blur(function(){if($(this).val()==''){$(this).css({color:'#a4a4a4'});$(this).val(val);}else{$(this).attr('class','filled');} val='';});}});});
		</script>
	</head>
	<body id="login">
		<div id="wrapper">
			<h1><img src="img/discuss.png" alt="discuss" width="" height="" /></h1>
			
			<div id="container">
				<h2>Discussion Forum Installer</h2>
				<?php echo output_message($message); ?>
				<? if(!$_GET['success']): ?>
				<form id="login" action="<? $_SERVER['PHP_SELF'] ?>" method="POST">
					<table cellpadding="5" cellspacing="5">
						<tr>
							<td><label for="sitename">Sitename</label></td>
							<td><input type="text" id="sitename" name="sitename" value="My New Discussion Board" /></td>
						</tr>
						<tr>
							<td><label for="siteroot">Site Root</label></td>
							<td><input type="text" id="siteroot" name="siteroot" value="/dept/forum" /></td>
						</tr>
						<tr>
							<td><label for="dbprefix">Database Prefix</label></td>
							<td><input type="text" id="dbprefix" name="dbprefix" value="dept" /></td>
						</tr>
						<tr>
							<td colspan="2"><input class="button floatright" type="submit" name="submit" value="Install" /></td>
						</tr>
					</table>
				</form>
				<? endif; ?>
				<? if($_GET['success']): ?>
				<p>Congratulations! You have successfully installed your new discussion forum and are ready to use it. Please delete the install.php file as it will no longer be needed. Please <a href="index.php">click here</a> to start your first category!</p>
				<? endif; ?>
			</div><!-- #container -->
		</div><!-- #wrapper -->
		<div id="legal">
			<h4>Content Manager</h4>
			<p>&copy;<? echo date('Y') ?>, University of Missouri - School of Medicine</p>
			<p>Office of Communications, All Right Reserved</p>
		</div><!-- #legal -->
	</body>
</html>