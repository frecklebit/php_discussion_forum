<?

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$result = $database->insert("$DBNAME.categories", array('cat_name' 			=> $_POST['cat_name'],
												  			'cat_description'	=> $_POST['cat_description']));
	
	if(!$result){
		echo '<p id="notification" class="err">Category not added</p>';
	}else {
		echo '<p id="notification" class="success">Category successfully added!</a>';
	}
}

?>
<div id="breadcrumbs">
	<ul>
		<li><a href="<? echo SITE_ROOT_BASE ?>"><? echo SITE_NAME; ?></a></li>
		<li>&rarr;</li>
		<li><a href="/cctc/forum/">Discussion Board</a></li>
	</ul>
</div>
<div id="forum-menu">
	<a class="cancel" href="/cctc/forum/">Cancel</a>
</div>
<div id="tablewrap">
	<table id="forum-body">
		<form method="post" action=""> 
			<tr class="title">
				<td colspan="2">Create a category</td>
			</tr>
			<tr>
			    <td>Category name:</td>
			    <td><input type="text" name="cat_name" /></td>
			</tr>
			<tr>
			    <td>Category description:</td>
			    <td><textarea name="cat_description" /></textarea></td> 
			</tr>
			<tr class="title">
				<td colspan="2"><input type="submit" value="Add category" /> </td>
			</tr>
		</form> 
	</table>
</div>