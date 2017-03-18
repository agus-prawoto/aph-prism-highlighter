<?php
/**
 * Plugin Name: APH Prism Syntax Highlighter
 * Description: Bringing Prism Syntax Highlighter (Prismjs.com) into Wordpress easily, please go to <a href="options-general.php?page=aph-prism-highlighter" target="_blank">Settings &raquo; Prism Highlighter</a> to change some options, or you can leave it as is.
 * Version: 1.1.1
 * Author: Agus Prawoto Hadi
 * Author URI: http://www.webdevcorner.com
 */

include 'includes/aphph-config.php';

if (is_admin())
{
	/* Show admin plugin page options */
	include 'includes/aphph-admin.php';
	new Aphph_Admin();
	
	/* Show the editor within tinyMCE and QuickTags typically in the 
	  add/edit post, page and admin's edit-comments 
	*/
	include 'includes/aphph-admin-editor.php';
	new Aphph_Admin_Editor();
	
} else {
	include 'includes/aphph-front.php';
	new Aphsh_Front();
}

?>