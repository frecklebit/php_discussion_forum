<?
require_once('inc/init.php');

// if logged in, bypass
if($session->is_logged_in()){
	redirect_to('index.php');
}

// check for messages
$message = $session->message();

// on submit
if(isset($_POST['username'])){
	
	// trim & encode
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$uencode = base64_encode($username);
	
	// authenticate user
	$found_user = User::authenticate($username, $password);
	if($found_user){
		unset($_SESSION['count']);
		
		// if user timestamp unset, send to changepassword
		if($found_user->log_date != 'new'){
			// set timestamp and log user in
			User::timestamp($found_user->id);
			$session->login($found_user);
			
			// log action
			log_action('Login', "{$found_user->username} logged in.");
			
			// redirect to index
			redirect_to('index.php');
		}else{
			redirect_to('admin.php?a=changepassword&u='.base64_encode($found_user->username));
		}
	}else{
		
		// log action for user failed
		log_action('Login Failed', "{$username} attempted to log in with incorrect password.");
		$message = "Username/password combination is incorrect. ";
		
		// username/password combo was not found in the database
		// user gets 4 attempts otherwise send to password reset
	    $_SESSION['count']++;
	    $attempts = 4 - $_SESSION['count'];
	    $message .= "<br />Remaining attempts: {$attempts}";
	    $username_found = User::find_by_username($username);
	    
	    if($_SESSION['count'] == 4 && $username_found != false){
	    	unset($_SESSION['count']);
	    	redirect_to('./admin.php?a=loginfail&u='.base64_encode($username));
	    }elseif($username_found == false){
	    	unset($_SESSION['count']);
	    	$message = "User not found. If you are having trouble accessing this site, please contact the Office of Communications at<br />".IT_PHONE.".";
	    }
	}
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><? echo SITE_NAME ?></title>
		<link rel="stylesheet" type="text/css" media="screen" href="/admin/css/screen.css" />
	</head>
	<body id="login">
		<div id="wrapper">
			<h1><img src="img/discuss.png" alt="discuss" width="" height="" /></h1>
			
			<div id="container">
				<h2><? echo SITE_NAME ?></h2>
				<h3>User Login</h3>
				<?php echo output_message($message); ?>
				<form id="login" action="<? $_SERVER['PHP_SELF'] ?>" method="POST">
					<table cellpadding="5" cellspacing="5">
						<tr>
							<td><label for="username">Username</label></td>
							<td><input type="text" id="username" name="username" value="<?php echo htmlentities($username); ?>" /></td>
						</tr>
						<tr>
							<td><label for="password">Password</label></td>
							<td><input type="password" id="password" name="password" value="<?php echo htmlentities($password); ?>" /></td>
						</tr>
						<tr>
							<td colspan="2"><input class="button floatright" type="submit" name="submit" value="Login" /></td>
						</tr>
					</table>
				</form>
			</div><!-- #container -->
		</div><!-- #wrapper -->
		<div id="legal">
			<h4>Content Manager</h4>
			<p>&copy;<? echo date('Y') ?>, University of Missouri - School of Medicine</p>
			<p>Office of Communications, All Right Reserved</p>
		</div><!-- #legal -->
	</body>
</html>