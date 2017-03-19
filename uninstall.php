<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

require_once 'includes/aphph-config.php';
delete_option(APHPH_OPTION);
delete_option(APHPH_OPTION_PHDATA);
delete_option(APHPH_OPTION_VERSION);
delete_option(APHPH_OPTION_NOTICE);

$upload_dir = wp_upload_dir();
$build_path = $upload_dir['basedir'] . APHPH_DS . 'aphph';

$files = scandir($build_path);
foreach ($files as $file) {
	if ($file == '.' || $file == '..')
		continue;
	unlink ($build_path . APHPH_DS . $file);
}
@rmdir ($build_path);
?>