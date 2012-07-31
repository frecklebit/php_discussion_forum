CREATE TABLE [DBNAME].categories (  
    cat_id          	INT(8) NOT NULL AUTO_INCREMENT,  
    cat_name        	VARCHAR(255) NOT NULL,  
    cat_description     VARCHAR(255) NOT NULL,  
    UNIQUE INDEX cat_name_unique (cat_name),  
    PRIMARY KEY (cat_id)  
);

CREATE TABLE [DBNAME].topics (  
	topic_id        INT(8) NOT NULL AUTO_INCREMENT,  
	topic_subject       VARCHAR(255) NOT NULL,  
	topic_date      DATETIME NOT NULL,  
	topic_cat       INT(8) NOT NULL,  
	topic_by        INT(8) NOT NULL,  
	PRIMARY KEY (topic_id)  
);

CREATE TABLE [DBNAME].posts (  
    post_id         INT(8) NOT NULL AUTO_INCREMENT,  
    post_content        TEXT NOT NULL,  
    post_date       DATETIME NOT NULL,  
    post_topic      INT(8) NOT NULL,  
    post_by     INT(8) NOT NULL,  
    PRIMARY KEY (post_id)  
);

ALTER TABLE [DBNAME].topics ADD FOREIGN KEY(topic_cat) REFERENCES [DBNAME].categories(cat_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE [DBNAME].topics ADD FOREIGN KEY(topic_by) REFERENCES cms.users(id) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE [DBNAME].posts ADD FOREIGN KEY(post_topic) REFERENCES [DBNAME].topics(topic_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE [DBNAME].posts ADD FOREIGN KEY(post_by) REFERENCES cms.users(id) ON DELETE RESTRICT ON UPDATE CASCADE;

INSERT INTO [DBNAME].categories
	(cat_name, cat_description)
	VALUES (
		'Help & Support / Getting Started',
		'Instructions and tips on managing your new forum.'
	);
	
INSERT INTO [DBNAME].topics
	(topic_subject, topic_date, topic_cat, topic_by)
	VALUES (
		'Topic Essentials',
		NOW(),
		1,
		17	
	);

INSERT INTO [DBNAME].posts
	(post_content, post_date, post_topic, post_by)
	VALUES (
		'<h1>Topic Essentials</h1><p>Your new forum is very basic.  We like to keep it that way to weed out any confusion users may have.  You have the ability to create new topics as well as delete unwanted topics.</p><h2>Creating a topic</h2><p>Creating a topic is as easy as clicking the <em>Create A Topic</em> button at the top left of the screen.</p><h3>Subject</h3><p>Your subject is what you want displayed for users to click on to view related posts.</p><h3>Categories</h3><p>Categories are typically preselected since you are creating the topic under the category, but if you should happen to be in the wrong category, simply change it in the drop-down menu.</p><h3>Message</h3><p>And that leaves us with Message. Well, it\'s pretty simple.  The message is what you type your first post in but if you don\'t feel like entering one at that moment then that\'s fine, you can add it later.  Once you have completed the form for a new topic, click the <strong>Create Topic</strong> button at the bottom right of the screen.</p><h2>Deleting a topic</h2><p>Deleting a topic is simple too.  All you have to do is click the trash can icon next to the topic name, confirm that you wish to delete and let the magic happen!  Your topic and any posts affiliated with the topic will be removed.</p><h2>Modifying a topic</h2><p>Unfortunately at this time, modifying a topic is not available.  This may come up in a future release but we\'ve felt it was unnecessary since it\'s like closing a chat window at the end of your conversation with a buddy from across the world!</p><h2>But Remember!</h2><p>Due to security restrictions, you are limited to 30 minutes before getting automatically timed out. Since you\'re only typing in a text area, your forum thinks it has been neglected and logs you out.  A handy tip we suggest is, if it\'s a long post, type it up in your favorite text editor then copy and paste it here!</p><p> :) </p>',
		NOW(),
		1,
		17
	);
	
INSERT INTO [DBNAME].posts
	(post_content, post_date, post_topic, post_by)
	VALUES (
		'Replies are easy too!',
		NOW(),
		1,
		17
	);
	
INSERT INTO [DBNAME].topics
	(topic_subject, topic_date, topic_cat, topic_by)
	VALUES (
		'List of Error Codes',
		NOW(),
		1,
		17	
	);
	
INSERT INTO [DBNAME].posts
	(post_content, post_date, post_topic, post_by)
	VALUES (
		'<h1>List of Error Codes</h1><p>We like to keep security tight around here.  When you delete categories, topics or posts, we do plenty of error handling.  Here is a list of our most common error codes for when you are deleting items from your forum.</p><table id=\"forum-body\"><tr><th>Error Code</th><th>Issue</th></tr><tr><td>200</td><td>Item was successfully deleted.</td></tr><tr><td>300</td><td>Table not found. Category was not sent, this is typically a programming error so contact your manager.</td></tr><tr><td>400</td><td>Root ID not found. The ID number of the item you are trying to delete was not send.</td></tr><tr><td>501</td><td>Error deleting category, please try again. This is also a programming error, please contact your manager.</td></tr><tr><td>502</td><td>Error deleting topic, please try again. Similar to code 501.</td></tr><tr><td>503</td><td>Error deleting post, please try again. Similar to code 501.</td></tr><tr><td>512</td><td>Topics not clean, this means there was an error in deleting something under the item you tried deleting.</td></tr><tr><td>513</td><td>Posts not clean. This is similar to code 512.</td></tr></table><p>With any errors you receive, make sure you contact the University of Missouri Office of Communications.</p><h2>The HELP Command</h2><p>You can also use the HELP command to send us feedback or get help from us.  It\'s easy to do, just reply to any post by typing HELP at the beginning of the reply followed by your email address and then your message. We will reply to you as soon as we can!</p><p>Example:</p><pre>HELP yourname@domain.com How do I retrieve my password?</pre>',
		NOW(),
		2,
		17
	);
	
INSERT INTO [DBNAME].topics
	(topic_subject, topic_date, topic_cat, topic_by)
	VALUES (
		'HTML Accepted Here',
		NOW(),
		1,
		17	
	);
	
INSERT INTO [DBNAME].posts
	(post_content, post_date, post_topic, post_by)
	VALUES (
		'<h1>HTML Accepted Here</h1><p>Although we\'re simple, we\'ve secretly given you the ability to type in HTML code.  This means you can personalize your post and even add pictures, or embed videos, from the web.</p><h2>Some Basic Code</h2><ul><li><strong>h1-h6</strong> Heading tags</li><li><strong>em, strong, u</strong> Text decorations</li><li><strong>p, ul, blockquote</strong> Paragraphs, lists and quotes</li><li><strong>img</strong> Images</li></ul><p>While there are many different tags you could use, those are a few of my favorite things!</p>',
		NOW(),
		3,
		17
	);
	
INSERT INTO [DBNAME].topics
	(topic_subject, topic_date, topic_cat, topic_by)
	VALUES (
		'Meet Smiley - Emoticons',
		NOW(),
		1,
		17	
	);
	
INSERT INTO [DBNAME].posts
	(post_content, post_date, post_topic, post_by)
	VALUES (
		"<h1>We Be Smilin\'!</h1><p>Sometimes we say something and it doesn\'t come out right. So we make a facial gesture to back up what we mean. On the internet that can be a little difficult since you\'re not face-to-face with the person you are talking to. Well we have a fix for that!</p><h2>Meet Smiley :) </h2><p>Smiley does your facial expressions for you! Here is a list of facial expressions that he\'s capable of producing:</p><p><strong>NOTE:</strong> Be sure to put a space before and after your emoticon, otherwise it could get scary!</p><center><img style=\"border:1px solid #eee\" src=\"/images/emoticons/emoticonlist.png\" alt=\"emoticonlist\" /></center>",
		NOW(),
		4,
		17
	);