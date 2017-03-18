<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

require_once 'includes/aphph-config.php';
delete_option(APHPH_OPTION);
delete_option(APHPH_OPTION_PHDATA);
delete_option(APHPH_OPTION_VERSION);
?>