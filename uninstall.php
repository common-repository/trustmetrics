<?php
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
    delete_transient('trustmetrics_custom_auth_token');
?>  