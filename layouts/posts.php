<?

$category = $db->fetch_array($db->select("$DBNAME.categories", "cat_name", array("cat_id"=>$_GET['cat'])));

$counter = 0;
if($_POST['reply-content']){
	if(!preg_match('/^HELP/', $_POST['reply-content'])) {
		$result = $database->insert("$DBNAME.posts", array('post_content'	=> $db->escape_value($_POST['reply-content']),
														   'post_date'		=> date( 'Y-m-d H:i:s' ),
														   'post_topic'		=> $_GET['id'],
														   'post_by'		=> $_SESSION['user_id']));
		if(!$result) {
			echo '<p id="notification" class="warning">Your reply has not been saved, please try again later.</p>';
		}
	}else{
		preg_match('/^(HELP)\s(\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b)\s(.*)/i', $_POST['reply-content'], $matches);
		array_shift($matches);
		
		$to = "jenkinsac@health.missouri.edu";
		
		$subject = "Forum Help : ";
		
		$headers = "From: CCTC Forum User <". strip_tags($matches[1]) . ">\r\n";
		$headers .= "Reply-To: ". strip_tags($matches[1]). "\r\n";
		
		$message = "On: ".date('m/d/y')." at ".date('h:i:s')."\r\n\r\n";
		$message .= $matches[2] ."\r\n\r\n";
		$message .= $matches[1];
		
		$mailto = mail($to, $subject, $message, $headers);
		
		if($mailto){
			echo '<p id="notification" class="success">Your help request has been sent, please allow 24-48 hours for a reply.</p>';
		}else{
			echo '<p id="notification" class="err">Your help request was not sent, please try again later.</p>';
		}
	}
}

$result = $database->select("$DBNAME.topics", '*', array('topic_id' => $_GET['id']));
if(!$result) {
	echo '<p id="notification" class="err">The topic could not be displayed, please try again later.</p>';
}else{
	$topic = $database->fetch_assoc($result);
	$sql = "SELECT
				$DBNAME.posts.post_topic,
				$DBNAME.posts.post_content,
				$DBNAME.posts.post_date,
				$DBNAME.posts.post_by,
				$DBNAME.posts.post_id,
				cms.users.id,
				cms.users.username
			FROM
				$DBNAME.posts
			LEFT JOIN
				cms.users
			ON
				$DBNAME.posts.post_by = cms.users.id
			WHERE
				$DBNAME.posts.post_topic = ".$database->escape_value($_GET['id']);
	
	$result = $database->query($sql);
	
	if(!$result) {
		echo '<p id="notification" class="err">There was an error retrieving topics. Please try again later.</p>';
	}else{
		if($database->num_rows($result) == 0){
			$database->select("$DBNAME.topics",'*',array("topic_id"=>$_GET['id']));
			if($db->affected_rows()==0)
				redirect_to('?q=topics&cat='.$_GET['cat']);
		}else{
			$sql = "SELECT COUNT(*) AS amt FROM $DBNAME.posts WHERE post_topic = {$_GET['id']}";
			$count = $database->fetch_array($database->query($sql));
		}
	}
}
?>

<!-- Breadcrumbs -->
<div id="breadcrumbs">
	<ul>
		<li><a href="<? echo SITE_ROOT_BASE ?>"><? echo SITE_NAME; ?></a></li>
		<li>&rarr;</li>
		<li><a href="/cctc/forum/">Discussion Board</a></li>
		<li>&rarr;</li>
		<li><a href="?q=topics&cat=<? echo $_GET['cat'] ?>"><? echo substr($category['cat_name'], 0, 35); ?></a></li>
		<li>&rarr;</li>
		<li><a href="?q=posts&cat=<? echo $_GET['cat'] ?>&id=<? echo $_GET['id'] ?>"><? echo substr($topic['topic_subject'], 0, 35); ?></a></li>
	</ul>
</div>

<!-- Menu bar -->
<div id="forum-menu">
	<a class="add" href="">Post Reply</a>
</div>

<!-- Table -->
<div id="tablewrap">
	<table id="forum-body" border="1">
		<!-- Title bar -->
		<tr class="title">
			<td colspan="2"><? echo $topic['topic_subject']; ?>
				<span class="right" title="Delete Topic"><? echo $count['amt'] ?> posts - 
				<img onclick="Purge('topic.<? echo $_GET['id']; ?>')" src="img/trash.png" alt="Delete" width="10" height="10" /></span>
			</td>
		</tr>
		
		<!-- Begin posts loop -->
		<? while($row = $database->fetch_assoc($result)): ?>
		<? $counter++; ?>
		<? $class = is_float($counter/2) ? 'alt':null; ?>
		<tr class="post-section">
			
			<!-- User column -->
			<td class="post <? echo $class; ?>">
				<img class="gravatar" src="img/user.gif" alt="" width="32" height="32" /><br />
				<strong><? echo $row['username'] ?></strong> sez...
			</td>
			
			<!-- Post column -->
			<td class="post-content <? echo $class; ?>">
				<!-- Date bar -->
				<? if(strtotime($row['post_date']) > strtotime($session->user()->prev_login)): ?>
				<img class="new-img" src="img/unread.png" alt="new" width="16" height="16" />
				<? endif; ?>
				
				<span class="postdate">Posted: <? echo date('D M j, Y g:i a', strtotime($row['post_date'])); ?>
					<img onclick="Purge('post.<? echo $row['post_id'] ?>')" src="img/trash.png" alt="Delete" width="10" height="10" />
				</span><br />
				
				<!-- Post body -->
				<?
				$emoticon = new Emoticonize($row['post_content']);
				echo $emoticon->getContent(); ?>
			</td>
		</tr>
		<? endwhile; ?>
		
		<? if($count == 0): ?>
		<tr class="post-section">
			<td class="post">
				<br /><strong>0</strong> posts...
			</td>
			<td class="post-content">
				<br />Create one below...
			</td>
		</tr>
		<? endif; ?>
		
		<!-- Bottom black bar -->
		<tr class="title">
			<td colspan="2"></td>
		</tr>
		
	</table>
	
	<!-- Reply box -->
	<div id="reply">
		<h3>Post Reply:</h3>
		<form method="post" action="">
			<textarea id="ta-reply" name="reply-content"></textarea>
			<input type="submit" value="" />
		</form>
	</div>
	
</div><!-- #tablewrap -->
