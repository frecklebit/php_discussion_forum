<?
require_once('../../inc/init.php');

// set varables
$TABLE 	= $_POST['obj'];
$ID 	= $_POST['id'];

// error codes
const _200 = "Successfully deleted!";
const _300 = "[Error 300] Table not found.";
const _400 = "[Error 400] Root ID not found";
const _501 = "[Error 501] Error deleting category, please try again later.";
const _502 = "[Error 502] Error deleting topic, please try again later.";
const _503 = "[Error 503] Error deleting post, please try again later.";
const _512 = "[Error 512] Topics not clean, please try again later.";
const _513 = "[Error 513] Posts not clean, please try again later.";

/**
  * Core functionality for cleaning forum
  * 
  * @return (error code) 
  * 
  */

// if root id not found, throw error & die
if(empty($ID))
{
	echo _400;
	die;
}
	
// delete category and attached topics & posts
if($TABLE == table('cat'))
{
	// query topics under category
	$r = query('topic', array('topic_cat'=>$ID));
	
	// if topics are not clean
	if(!table_clean())
	{
		// loop through topics
		while($t = $db->fetch_array($r))
		{
			// filter duplicate results
			if(is_numeric($t[0]))
			{
				// query posts under category
				$rp = query('post', array('post_topic'=>$t[0]));
				
				// if posts are not clean
				if(!table_clean())
				{
					// loop through posts
					while($p = $db->fetch_array($rp))
					{
						// filter duplicate results
						if(is_numeric($p[0]))
						{
							// delete post
							if(delete_post($p[0]))
							{
								// on success continue
								continue;
							}
							else
							{
								// on fail throw error & break loop
								echo _503;
								die;
							}
						}
					}
				}
				// delete topic
				if(delete_topic($t[0]))
				{
					// on success continue
					continue;
				}
				else
				{
					// on fail throw error & break loop
					echo _502;
					die;
				}
			}
		}
	}
	// verify topics are clean
	$r = query('topic', array('topic_cat'=>$ID));
	
	// if topics are clean
	if(table_clean())
	{
		// delete category & return status
		echo (delete_category($ID)) ? _200 : _501;
	}
	else
	{
		// if topics exists throw an error
		echo _512;
		die;
	}
}



// delete topic and attached posts
else if($TABLE == table('topic'))
{
	// query posts under topic
	$r = query('post', array('post_topic'=>$ID));
	
	// if posts are not clean
	if(!table_clean())
	{
		// loop through posts
		while($p = $db->fetch_array($r))
		{
			// filter duplicate results
			if(is_numeric($p[0]))
			{
				// delete post
				if(delete_post($p[0]))
				{
					// on success continue
					continue;
				}
				else
				{
					// on fail throw error & break loop
					echo _503;
					die;
				}
			}
		}
	}
	// verify posts are clean
	$r = query('post', array('post_topic'=>$ID));
	
	// if posts are clean
	if(table_clean())
	{
		// delete topic & return status
		echo delete_topic($ID) ? _200 : _502;
	}
	else
	{
		// if posts exist throw an error
		echo _513;
		die;
	}
}



// delete post
else if($TABLE == table('post'))
{
	// verfy post exists
	$r = query('post', array('post_id'=>$ID));
	
	// if post exists
	if(!table_clean())
	{
		// delete topic & return status
		echo delete_post($ID) ? _200 : _502;
	}
	else
	{
		// if post does not exist success
		echo _200;
		die;
	}
}



// table not found
else
{
	echo _300;
	die;
}

/**
  * Function id - build id string
  * 
  * @param (string) s
  * @return (string) 
  * 
  */
function id($s)
{
	return $s.'_id';
}

/**
  * Function table - returns database table name
  * 
  * @param (string) name
  * @return (string) 
  * 
  */
function table($name)
{
	$a = array(
		'cat'	=> 'categories',
		'topic' => 'topics',
		'post'	=> 'posts'
	);
	return $a[$name];
}

/**
  * Function database - returns database name
  * 
  * @return (string) 
  * 
  */
function database()
{
	return 'forum_cctc';
}

/**
  * Function: query - query rows in database
  * 
  * @param (string) tbl, (array) condition 
  * @return (resource) 
  * 
  */
function query($tbl='', $condition=array())
{
	global $db;
	return $db->select(database().".".table($tbl), id($tbl), $condition);
}

/**
  * Function: delete_category - remove category row from database
  * 
  * @param (int) id 
  * @return (boolean) 
  * 
  */
function delete_category($id)
{
	global $db;
	return ($db->delete(database().".".table('cat'), array('cat_id'=>$id))) ? true : false;
}

/**
  * Function: delete_topic - remove topic row from database
  * 
  * @param (int) id
  * @return (boolean) 
  * 
  */
function delete_topic($id)
{
	global $db;
	return ($db->delete(database().".".table('topic'), array('topic_id'=>$id))) ? true : false;
}

/**
  * Function: delete_post - remove post from database
  * 
  * @param (int) id
  * @return (boolean) 
  * 
  */
function delete_post($id)
{
	global $db;
	return ($db->delete(database().".".table('post'), array('post_id'=>$id))) ? true : false;
}

/**
  * Function: table_clean - used after query to check for neglected rows
  * 
  * @return (boolean) 
  * 
  */
function table_clean()
{
	global $db;
	return ($db->affected_rows() == 0);
}



?>