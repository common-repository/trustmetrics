<?php
if ( !defined('ABSPATH') ) {
    exit;
}
if (isset($_GET['token'])) {
    require_once(__DIR__.'/../../../../wp-load.php');

    $token = sanitize_text_field($_GET['token']);
    
    set_transient('trustmetrics_custom_auth_token', $token, YEAR_IN_SECONDS);
	trustmetrics_enqueue_token_script();

    //update_option('trustmetrics_custom_auth_token', $token);
    
}
?>