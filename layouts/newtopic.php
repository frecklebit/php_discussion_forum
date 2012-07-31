<?

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$result = $database->insert("$DBNAME.topics", array('topic_subject'	=> $_POST['topic_subject'],
														'topic_cat'		=> $_POST['topic_cat'],
														'topic_by'		=> $_SESSION['user_id'],
														'topic_date'	=> date( 'Y-m-d H:i:s' )));
	
	if(!$result) {
		echo '<p id="notification" class="err">An error occurred while inserting your data. Please try again later.</p>';
	}else{
		$topic_id = $database->insert_id();
		$result = $database->insert("$DBNAME.posts", array('post_content'	=> $db->escape_value($_POST['post_content']),
														   'post_date'		=> date( 'Y-m-d H:i:s' ),
														   'post_topic'		=> $topic_id,
														   'post_by'		=> $_SESSION['user_id']));
		if(!$result) {
			echo '<p id="notification" class="err">An error occurred while inserting your post. Please try again later.</p>';
		}else{
			echo '<p id="notification" class="success">You have successfully created your new topic.';
		}
	}		
}

$category = $db->fetch_array($db->select("$DBNAME.categories", "cat_name", array("cat_id"=>$_GET['cat'])));
$result = $database->select("$DBNAME.categories", '*');
if(!$result) {
	echo '<p id="notification" class="err">There was an error retrieving data from database.</p>';
}else{
	if($database->num_rows($result) == 0) {
		echo '<p id="notification" class="warning">You have not created any topics.</p>';
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
	</ul>
</div>

<!-- Menu -->
<div id="forum-menu">
	<a class="cancel" href="?q=topics&cat=<? echo $_GET['cat'] ?>">Cancel</a>
</div>

<!-- Table -->
<div id="tablewrap">
	<table id="forum-body">
		<form method="post" action="">
			<tr class="title">
				<td colspan="2">Create a topic</td>
			</tr>
			<tr>
				<td>Subject:</td>
				<td><input type="text" name="topic_subject" /></td>
			</tr>
			<tr>
				<td>Category:</td>
				<td>
					<select name="topic_cat">
						<option value=""></option>
						<? while($cat = $database->fetch_array($result)): ?>
						<option <? selected($cat['cat_id'], $_GET['cat']) ?> value="<? echo $cat['cat_id']; ?>"><? echo $cat['cat_name'] ?></option>
						<? endwhile; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Message:</td>
				<td><textarea name="post_content"></textarea></td>
			</tr>
			<tr class="title">
				<td colspan="2"><input type="submit" value="Create Topic" /></td>
			</tr>
		</form>
	</table>
</div><!-- #tablewrap -->
