<?
$result = $database->select("$DBNAME.categories", '*', array('cat_id'=>$_GET['cat']));
if(!$result) {
	echo '<p id="notification" class="err">The category could not be displayed, please try again later.</p>';
}else{
	if($database->num_rows($result) == 0) {
		echo '<p id="notification" class="warning">This category does not exist.</p>';
	}else{
		$category = $database->fetch_array($result);
		$sql = "SELECT
					*
				FROM
					$DBNAME.topics t
				LEFT JOIN
					$DBNAME.posts p
				ON 
					t.topic_id = p.post_topic
				AND
					p.post_id = (SELECT MAX(post_id) FROM $DBNAME.posts WHERE post_topic = t.topic_id)
				WHERE
					t.topic_cat = ".$_GET['cat']."
				ORDER BY
					t.topic_id DESC";
		$result = $database->query($sql);
		if(!$result) {
			echo '<p id="notification" class="err">The topics could not be displayed, please try again later.</p>';
		}
	}
}
?>

<div id="breadcrumbs">
	<ul>
		<li><a href="<? echo SITE_ROOT_BASE; ?>"><? echo SITE_NAME; ?></a></li>
		<li>&rarr;</li>
		<li><a href="/cctc/forum/">Discussion Board</a></li>
		<li>&rarr;</li>
		<li><a href="?q=topic&cat=<? echo $_GET['cat'] ?>"><? echo substr($category['cat_name'], 0, 35); ?></a></li>
	</ul>
</div>
<div id="forum-menu">
	<a class="add" href="?q=newtopic&cat=<? echo $_GET['cat'] ?>">New Topic</a>
</div>
<div id="tablewrap">
	<table id="forum-body" border="1">
		<tr class="title">
			<td colspan="4">
				<? echo $category['cat_name']; ?>
			</td>
		</tr>
		<tr>
			<th align="center"><img src="img/pages.png" alt="pages" width="" height="" /></th>
			<th>Topic</th>
			<th>Replies</th>
			<th>Latest Post</th>
		</tr>
		<?
		while($row = $database->fetch_assoc($result)): 
			$sql = "SELECT COUNT(*) AS amt FROM $DBNAME.posts WHERE post_topic = {$row['topic_id']}";
			$count = $database->fetch_array($database->query($sql));
			$by = $database->fetch_array($database->select("cms.users", "*", array('id'=>$row['topic_by'])));
			$new = (strtotime($row['post_date']) > strtotime($session->user()->prev_login)) ? 'new' : null;
		?>
		<tr>
			<td>
				<? if($new): ?>
				<img class="new-img" src="img/unread.png" alt="NEW" title="New posts" />
				<? endif; ?>
				<? if(!$new): ?>
				<img src="img/read.png" alt="" title="No new posts"  />
				<? endif; ?>
			</td>
			<td class="category">
				<a class="<? echo $new; ?>" style="font-weight:normal;" href="?q=posts&cat=<? echo $_GET['cat'] ?>&id=<? echo $row['topic_id']; ?>"><? echo $row['topic_subject']; ?></a>
			</td>
			<td style="text-align:center;" class="<? echo $new; ?>"><? echo $count['amt']; ?></td>
			<td class="subject">
				<? echo date('D M j, Y g:i a', strtotime($row['post_date'])); ?><br />
				by <? echo $by['username']; ?>
			</td>
		</tr>
		<? endwhile; ?>
		
		<? if($database->num_rows($result) == 0): ?>
		<tr>
			<td></td>
			<td>You currently have no topics</td>
			<td></td>
			<td></td>
		</tr>
		<? endif ?>
		
		<tr class="title">
			<td colspan="4"></td>
		</tr>
	</table>
</div>