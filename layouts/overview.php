<div id="breadcrumbs">
	<ul>
		<li><a href="<? echo SITE_ROOT_BASE ?>"><? echo SITE_NAME; ?></a></li>
		<li>&rarr;</li>
		<li><a href="">Discussion Board</a></li>
	</ul>
</div>
<div id="forum-menu">
	<a class="add" href="?q=newcategory">New Category</a>
</div>
<div id="tablewrap">
	<table id="forum-body" border="0">
		<tr class="title">
			<td colspan="3">Combat Casualty Training Consortium</td>
		</tr>
		<tr>
			<th><img src="img/pages.png" alt="pages" width="9" height="11" /></th>
			<th>Category</th>
			<th>Latest Topic</th>
		</tr>
		<? 
		$sql = "SELECT
					*
				FROM
					$DBNAME.categories c
				LEFT JOIN
					$DBNAME.topics t
				ON 
					c.cat_id = t.topic_cat
				AND
					t.topic_id = (SELECT MAX(topic_id) FROM $DBNAME.topics WHERE topic_cat = c.cat_id)";
		$result = $database->query($sql);
		while($cat = $database->fetch_array($result)):
			$by = $database->fetch_array($database->select("cms.users", "*", array('id'=>$cat['topic_by'])));
		?>
		<tr>
			<td>
				<img onclick="Purge('category.<? echo $cat['cat_id']; ?>')" src="img/trash.png" alt="trash" width="12" height="12" />
			</td>
			<td class="category">
				<h3><a href="?q=topics&cat=<? echo $cat['cat_id']; ?>"><? echo $cat['cat_name']; ?></a></h3>
				<? echo $cat['cat_description']; ?>
			</td>
			<td class="subject">
				<? if(!empty($cat['topic_date'])){ ?>
				<a href="?q=posts&cat=<? echo $cat['cat_id'] ?>&id=<? echo $cat['topic_id'] ?>" title="View the latest post"><? echo date('D M j, Y g:i a', strtotime($cat['topic_date'])); ?><br />by <? echo $by['username']; ?> <img src="img/latest.gif" alt="->" />
				<? }else{ ?>
				No topics yet!
				<? } ?>
 			</td>
		</tr>
		<? endwhile; ?>
		<? if($database->num_rows($result) == 0): ?>
		<tr>
			<td colspan="3">No categories. <a href="?q=newcategory">Create a category.</a></td>
		</tr>
		<? endif; ?>
		<tr class="title">
			<td colspan="3"></td>
		</tr>
	</table>
</div>